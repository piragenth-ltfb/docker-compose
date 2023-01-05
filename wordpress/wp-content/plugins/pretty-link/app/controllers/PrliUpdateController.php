<?php
if(!defined('ABSPATH')) {die('You are not allowed to call this page directly.');}

class PrliUpdateController {
  public $mothership_license, $edge_updates, $mothership_license_str, $edge_updates_str, $pro_script, $plugin_slug;

  public function __construct() {
    $this->mothership_license_str = 'plp_mothership_license';
    $this->mothership_license     = get_option($this->mothership_license_str);
    $this->edge_updates_str       = 'plp_edge_updates';
    $this->edge_updates           = get_option($this->edge_updates_str);
    $this->plugin_slug            = PRLI_PLUGIN_SLUG;

    $this->pro_script = PRLI_PATH . '/pro/pretty-link-pro.php';
  }

  public function load_hooks() {
    if(!empty($this->mothership_license)) {
      add_filter('pre_set_site_transient_update_plugins', array($this, 'queue_update'));
      add_action('wp_ajax_plp_edge_updates', array($this, 'plp_edge_updates'));
      add_filter('plugins_api', array($this, 'plugin_info'), 11, 3);
    }

    add_action('admin_init', array($this, 'activate_from_define'));

    add_action('admin_notices', array($this, 'activation_warning'));
    add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
    //add_action('prli_display_options', array($this, 'queue_button'));
  }

  public function route() {
    if(strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
      return $this->process_form();
    }
    else {
      if( isset($_GET['action']) &&
          $_GET['action'] == 'deactivate' &&
          isset($_GET['_wpnonce']) &&
          wp_verify_nonce($_GET['_wpnonce'], 'pretty-link_deactivate') ) {
        return $this->deactivate();
      }
      else {
        return $this->display_form();
      }
    }
  }

  public function set_edge_updates($updates) {
    update_option('plp_edge_updates', $updates);
    wp_cache_delete('alloptions', 'options');
    $this->edge_updates = $updates;
  }

  public function set_mothership_license($license) {
    update_option('plp_mothership_license', $license);
    wp_cache_delete('alloptions', 'options');
    $this->mothership_license = $license;
  }

  public function display_form($message='', $errors=array()) {
    // We just force the queue to update when this page is visited
    // that way we ensure the license info transient is set
    $this->manually_queue_update();

    if(!empty($this->mothership_license) && empty($errors)) {
      $li = get_site_transient( 'prli_license_info' );
    }

    require(PRLI_VIEWS_PATH.'/admin/update/ui.php');
  }

  public function process_form() {
    if(!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'],'activation_form')) {
      wp_die(__('Why you creepin\'?', 'pretty-link'));
    }

    if(!isset($_POST[$this->mothership_license_str])) {
      $this->display_form();
      return;
    }

    $message = '';
    $errors = array();
    $this->set_mothership_license(stripslashes($_POST[$this->mothership_license_str]));
    $domain = urlencode(PrliUtils::site_domain());

    try {
      $args = compact('domain');
      $act = $this->send_mothership_request("/license_keys/activate/{$this->mothership_license}", $args, 'post');
      update_option('prli_activated', true); // if we get here we're activated
      wp_cache_delete('alloptions', 'options');
      $this->manually_queue_update();
      $message = $act['message'];

      if(strpos($message, 'site has already been activated') !== false ) {
        $this->deactivate(true);
      }
    }
    catch(Exception $e) {
      $errors[] = $e->getMessage();
    }

    $this->display_form($message, $errors);
  }

  public function is_activated() {
    $activated = get_option('prli_activated');
    return (!empty($this->mothership_license) && !empty($activated));
  }

  public function was_activated_with_username_and_password() {
    $credentials = get_option('prlipro-credentials');
    $authorized  = get_option('prlipro_activated');

    return (($credentials && is_array($credentials)) &&
            (isset($credentials['username']) && !empty($credentials['username'])) &&
            (isset($credentials['password']) && !empty($credentials['password'])) &&
            ($authorized && $authorized=='true'));
  }

  public function is_installed() {
    return file_exists($this->pro_script);
  }

  public function is_installed_and_activated() {
    return ($this->is_installed() && $this->is_activated());
  }

  public function check_license_activation() {
    $aov = get_option('prli_activation_override');

    if(!empty($aov)) {
      update_option('prli_activated', true);
      wp_cache_delete('alloptions', 'options');
      return true;
    }

    $domain = urlencode(PrliUtils::site_domain());

    // Bail if there's no license key
    if(empty($this->mothership_license)) { return; }

    try {
      $act = $this->send_mothership_request("/license_keys/a/{$domain}/{$this->mothership_license}", array(), 'get');

      if(!empty($act) && is_array($act) && isset($act['status'])) {
        update_option('prli_activated', ($act['status']=='enabled'));
        wp_cache_delete('alloptions', 'options');
      }
    }
    catch(Exception $e) {
      // TODO: For now do nothing if the server can't be reached
    }
  }

  public function activate_from_define() {
    if(!$this->is_installed()) { return; }

    if(defined('PRETTYLINK_LICENSE_KEY') && $this->mothership_license != PRETTYLINK_LICENSE_KEY) {
      $message = '';
      $errors = array();
      $this->mothership_license = stripslashes(PRETTYLINK_LICENSE_KEY);
      update_option($this->mothership_license_str, PRETTYLINK_LICENSE_KEY);
      wp_cache_delete('alloptions', 'options');
      $domain = urlencode(PrliUtils::site_domain());

      try {
        $args = compact('domain');

        if(!empty($this->mothership_license)) {
          $act = $this->send_mothership_request("/license_keys/deactivate/{$this->mothership_license}", $args, 'post');
          delete_site_transient('prli_addons');
        }

        $act = $this->send_mothership_request("/license_keys/activate/".PRETTYLINK_LICENSE_KEY, $args, 'post');
        update_option('prli_activated', true); // if we get here we're activated
        wp_cache_delete('alloptions', 'options');

        $this->manually_queue_update();

        // If we're using defines then we have to do this with defines too
        $this->set_edge_updates(false);

        $message = $act['message'];
        $callback = function() use ($message) { require(PRLI_VIEWS_PATH."/admin/errors.php"); };
      }
      catch(Exception $e) {
        $error = $e->getMessage();
        $callback = function() use ($error) { require(PRLI_VIEWS_PATH."/admin/update/activation_warning.php"); };
      }

      add_action( 'admin_notices', $callback );
    }
  }

  public function deactivate($hide_form = false) {
    $domain = urlencode(PrliUtils::site_domain());
    $message = '';

    try {
      $args = compact('domain');
      $act = $this->send_mothership_request("/license_keys/deactivate/{$this->mothership_license}", $args, 'post');

      $this->manually_queue_update();

      $this->set_mothership_license('');

      // Don't need to check the mothership for this one ... we just deactivated
      update_option('prli_activated', false);
      wp_cache_delete('alloptions', 'options');

      $message = $act['message'];
    }
    catch(Exception $e) {
      update_option('prli_activated', false);
      wp_cache_delete('alloptions', 'options');
      update_option($this->mothership_license_str, '');
      wp_cache_delete('alloptions', 'options');
      $errors[] = $e->getMessage();
    }

    if(!$hide_form) { $this->display_form($message); }
  }

  public function queue_update($transient, $force=false) {
    if(!$this->is_installed() && !$this->is_activated()) { return $transient; }

    if($force || (false === ($update_info = get_site_transient('prli_update_info')))) {
      if(empty($this->mothership_license)) {
        // Just here to query for the current version
        $args = array();
        if( $this->edge_updates || ( defined( "PRETTYLINK_EDGE" ) && PRETTYLINK_EDGE ) ) {
          $args['edge'] = 'true';
        }

        $version_info = $this->send_mothership_request( "/versions/latest/pretty-link-pro-developer", $args );
        $curr_version = $version_info['version'];
        $download_url = '';
      }
      else {
        try {
          $domain = urlencode(PrliUtils::site_domain());
          $args = compact('domain');

          if( $this->edge_updates || ( defined( "PRETTYLINK_EDGE" ) && PRETTYLINK_EDGE ) ) {
            $args['edge'] = 'true';
          }

          $license_info = $this->send_mothership_request("/versions/info/{$this->mothership_license}", $args, 'post');
          $curr_version = $license_info['version'];
          $download_url = $license_info['url'];

          set_site_transient(
            'prli_license_info',
            $license_info,
            (12*HOUR_IN_SECONDS)
          );
        }
        catch(Exception $e) {
          try {
            // Just here to query for the current version
            $args = array();
            if( $this->edge_updates || ( defined( "PRETTYLINK_EDGE" ) && PRETTYLINK_EDGE ) ) {
              $args['edge'] = 'true';
            }

            $version_info = $this->send_mothership_request("/versions/latest/pretty-link-pro-developer", $args);
            $curr_version = $version_info['version'];
            $download_url = '';
          }
          catch(Exception $e) {
            if(isset($transient->response[PRLI_PLUGIN_SLUG])) {
              unset($transient->response[PRLI_PLUGIN_SLUG]);
            }

            $this->check_license_activation();
            return $transient;
          }
        }
      }

      set_site_transient(
        'prli_update_info',
        compact('curr_version', 'download_url'),
        (12*HOUR_IN_SECONDS)
      );

      $this->addons(false, true);
    }
    else {
      extract( $update_info );
    }

    if(($this->is_activated() && !$this->is_installed()) || (isset($curr_version) && version_compare($curr_version, PRLI_VERSION, '>'))) {
      $transient->response[PRLI_PLUGIN_SLUG] = (object)array(
        'id'          => $curr_version,
        'slug'        => 'pretty-link',
        'new_version' => $curr_version,
        'url'         => 'https://prettylinks.com/pl/update/url',
        'package'     => $download_url
      );
    }
    elseif(isset($transient->response[PRLI_PLUGIN_SLUG])) {
      unset($transient->response[PRLI_PLUGIN_SLUG]);
    }

    $this->check_license_activation();
    return $transient;
  }

  public function manually_queue_update() {
    $transient = get_site_transient('update_plugins');
    set_site_transient('update_plugins', $this->queue_update($transient, true));
  }

  public function queue_button() {
    ?>
    <a href="<?php echo esc_url(admin_url('admin.php?page=pretty-link-options&action=queue&_wpnonce=' . wp_create_nonce('PrliUpdateController::manually_queue_update'))); ?>" class="button"><?php esc_html_e('Check for Update', 'pretty-link')?></a>
    <?php
  }

  // Return up-to-date addon info for pretty-link & its addons
  public function plugin_info($api, $action, $args) {
    global $wp_version;

    if(!isset($action) ||
       $action != 'plugin_information' ||
       (isset($args->slug) &&
        !preg_match("#^pretty-link-(basic|plus|pro)$#", $args->slug))) {
      return $api;
    }

    // Any addons should accept the pretty-link license for now
    if(!empty($this->mothership_license)) {
      try {
        $domain = urlencode(PrliUtils::site_domain());
        $params = compact('domain');

        if($this->edge_updates || (defined('PRETTYLINK_EDGE') && PRETTYLINK_EDGE)) {
          $params['edge'] = 'true';
        }

        $plugin_info = $this->send_mothership_request(
          "/versions/plugin_information/{$args->slug}/{$this->mothership_license}",
          $params,
          'get'
        );

        if(isset($plugin_info['requires'])) { $plugin_info['requires'] = $wp_version; }
        if(isset($plugin_info['tested']))   { $plugin_info['tested']   = $wp_version; }
        if(isset($plugin_info['compatibility'])) { $plugin_info['compatibility'] = array($wp_version => array($wp_version => array(100, 0, 0))); }

        return (object)$plugin_info;
      }
      catch(Exception $e) {
        // Fail silently for now
      }
    }

    return $api;
  }

  public function send_mothership_request( $endpoint,
                                           $args=array(),
                                           $method='get',
                                           $domain='http://mothership.caseproof.com',
                                           $blocking=true ) {
    $uri = "{$domain}{$endpoint}";

    $arg_array = array(
      'method'    => strtoupper($method),
      'body'      => $args,
      'timeout'   => 15,
      'blocking'  => $blocking,
      'sslverify' => false
    );

    $resp = wp_remote_request($uri, $arg_array);

    // If we're not blocking then the response is irrelevant
    // So we'll just return true.
    if($blocking == false) {
      return true;
    }

    if(is_wp_error($resp)) {
      throw new Exception(__('You had an HTTP error connecting to Caseproof\'s Mothership API', 'pretty-link'));
    }
    else {
      if(null !== ($json_res = json_decode($resp['body'], true))) {
        if(isset($json_res['error'])) {
          throw new Exception($json_res['error']);
        }
        else {
          return $json_res;
        }
      }
      else {
        throw new Exception(__( 'Your License Key was invalid', 'pretty-link'));
      }
    }

    return false;
  }

  public function enqueue_scripts($hook) {
    if(preg_match('/_page_pretty-link-updates$/', $hook)) {
      wp_register_style('prli-settings-table', PRLI_CSS_URL.'/settings_table.css', array(), PRLI_VERSION);
      wp_enqueue_style('prli-activate-css', PRLI_CSS_URL.'/admin-activate.css', array('prli-settings-table'), PRLI_VERSION);

      wp_register_script('prli-settings-table', PRLI_JS_URL.'/settings_table.js', array(), PRLI_VERSION);
      wp_enqueue_script('prli-activate-js', PRLI_JS_URL.'/admin_activate.js', array('prli-settings-table'), PRLI_VERSION);
    }
  }

  public function activation_warning() {
    if($this->is_installed() && empty($this->mothership_license) &&
       (!isset($_REQUEST['page']) || !($_REQUEST['page']=='pretty-link-updates'))) {
      require(PRLI_VIEWS_PATH.'/admin/update/activation_warning.php');
    }
  }

  public function plp_edge_updates() {
    if(!PrliUtils::is_prli_admin() || !wp_verify_nonce($_POST['wpnonce'],'wp-edge-updates')) {
      die(json_encode(array('error' => __('You do not have access.', 'pretty-link'))));
    }

    if(!isset($_POST['edge'])) {
      die(json_encode(array('error' => __('Edge updates couldn\'t be updated.', 'pretty-link'))));
    }

    $this->set_edge_updates($_POST['edge']=='true');

    // Re-queue updates when this is checked
    $this->manually_queue_update();

    die(json_encode(array('state' => ($this->edge_updates ? 'true' : 'false'))));
  }

  public function addons($return_object=false, $force=false) {
    $license = $this->mothership_license;

    if($force) {
      delete_site_transient('prli_addons');
    }

    if(($addons = get_site_transient('prli_addons'))) {
      $addons = json_decode($addons);
    }
    else {
      $addons = array();

      if(!empty($license)) {
        try {
          $domain = urlencode(PrliUtils::site_domain());
          $args = compact('domain');

          if(defined('PRETTYLINK_EDGE') && PRETTYLINK_EDGE) { $args['edge'] = 'true'; }
          $addons = $this->send_mothership_request('/versions/addons/'.PRLI_EDITION."/{$license}", $args);
        }
        catch(Exception $e) {
          // fail silently
        }
      }

      $json = json_encode($addons);
      set_site_transient('prli_addons',$json,(HOUR_IN_SECONDS*12));

      if($return_object) {
        $addons = json_decode($json);
      }
    }

    return $addons;
  }

  public function activate_page_url() {
    return admin_url('admin.php?page=pretty-link-updates');
  }

  public function update_plugin_url() {
    return admin_url('update.php?action=upgrade-plugin&plugin=' . urlencode($this->plugin_slug) . '&_wpnonce=' . wp_create_nonce('upgrade-plugin_' . $this->plugin_slug));
  }

  public function update_plugin() {
    $this->manually_queue_update();
    wp_redirect($this->update_plugin_url());
    exit;
  }

  public function upgrade_categories() {
    $section_title = esc_html__( 'Link Categories', 'pretty-link' );
    $upgrade_link = 'https://prettylinks.com/pl/main-menu/upgrade?categories';
    include_once PRLI_VIEWS_PATH . "/admin/upgrade/categories.php";
  }

  public function upgrade_tags() {
    $section_title = esc_html__( 'Link Tags', 'pretty-link' );
    $upgrade_link = 'https://prettylinks.com/pl/main-menu/upgrade?tags';
    include_once PRLI_VIEWS_PATH . "/admin/upgrade/tags.php";
  }

  public function upgrade_reports() {
    $section_title = esc_html__( 'Link Reports', 'pretty-link' );
    $upgrade_link = 'https://prettylinks.com/pl/main-menu/upgrade?reports';
    include_once PRLI_VIEWS_PATH . "/admin/upgrade/reports.php";
  }

  public function upgrade_import_export() {
    $section_title = esc_html__( 'Import / Export', 'pretty-link' );
    $upgrade_link = 'https://prettylinks.com/pl/main-menu/upgrade?import-export';
    include_once PRLI_VIEWS_PATH . "/admin/upgrade/import-export.php";
  }

} //End class

