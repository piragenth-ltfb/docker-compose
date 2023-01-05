<?php if(!defined('ABSPATH')) { die('You are not allowed to call this page directly.'); }

class PrliOnboardingController extends PrliBaseController {
  public function load_hooks() {
    // must be before PrliAppController::install() to detect a fresh install
    add_action('init', array($this, 'maybe_onboard'), 5);

    if (is_admin() && (!defined('DOING_AJAX') || !DOING_AJAX)) {
      add_action('wp_loaded', array($this, 'onboarding_intercept'));
    }
  }

  public function maybe_onboard() {
    if (get_option('prli_version') == PRLI_VERSION) {
      return;
    }

    if(!get_option('prli_db_version')) {
      // fresh install
      update_option('prli_onboard', 'welcome');
      wp_cache_delete('alloptions', 'options');
    }

    update_option('prli_version', PRLI_VERSION);
    wp_cache_delete('alloptions', 'options');
  }

  public function onboarding_intercept() {
    if(get_option('prli_onboard') == 'welcome' && delete_option('prli_onboard')) {
      wp_cache_delete('alloptions', 'options');
      nocache_headers();
      wp_redirect(admin_url("admin.php?page=pretty-link-welcome"), 307);
      exit;
    }
  }

  public function welcome_route() {
    require_once PRLI_VIEWS_PATH . '/admin/onboarding/welcome.php';
  }
}
