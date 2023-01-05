<?php if(!defined('ABSPATH')) {die('You are not allowed to call this page directly.');} ?>
<?php
global $plp_update;
$mothership_license_str = isset($_POST[$plp_update->mothership_license_str]) ? sanitize_key($_POST[$plp_update->mothership_license_str]) : $plp_update->mothership_license;
?>

<div class="prli-page-title"><?php esc_html_e('Pretty Links Pro License', 'pretty-link'); ?></div>

<?php if( !isset($li) or empty($li) ): ?>
  <p class="description">
    <?php
      printf(
        // translators: %1$s: link to pro site, %2$s: link to pro site login page
        esc_html__('You must have a License Key to enable automatic updates for Pretty Links Pro. If you don\'t have a License please go to %1$s to get one. If you do have a license you can login at %2$s to manage your licenses and site activations.', 'pretty-link'),
        '<a href="https://prettylinks.com/pl/license-alert/buy">prettylinks.com</a>',
        '<a href="https://prettylinks.com/pl/license-alert/login">prettylinks.com/login</a>'
      );
    ?>
  </p>
  <form name="activation_form" method="post" action="">
    <?php wp_nonce_field('activation_form'); ?>

    <table class="form-table">
      <tr class="form-field">
        <td valign="top" width="225px"><?php esc_html_e('Enter Your Pretty Links Pro License Key:', 'pretty-link'); ?></td>
        <td>
          <input type="text" name="<?php echo esc_attr($plp_update->mothership_license_str); ?>" value="<?php echo esc_attr($mothership_license_str); ?>"/>
        </td>
      </tr>
    </table>
    <p class="submit">
      <input type="submit" name="Submit" class="button button-primary" value="<?php echo esc_attr(sprintf(__('Activate License Key on %s', 'pretty-link'), PrliUtils::site_domain())); ?>" />
    </p>
  </form>

  <?php if(!$plp_update->is_installed()): ?>
    <div>&nbsp;</div>

    <div class="prli-page-title" id="prli_upgrade"><?php esc_html_e('Upgrade to Pro', 'pretty-link'); ?></div>

    <div>
      <?php
        printf(
          // translators: %1$s: open link tag, %2$s: close link tag
          esc_html__('It looks like you haven\'t %1$supgraded to Pretty Links Pro%2$s yet. Here are just a few things you could be doing with pro:', 'pretty-link'),
          '<a href="https://prettylinks.com/pl/license-alert/upgrade" target="_blank">',
          '</a>'
        );
      ?>
    </div>

    <div>&nbsp;</div>

    <ul style="padding-left: 25px;">
      <li>&bullet; <?php esc_html_e('Auto-replace keywords throughout your site with Pretty Links', 'pretty-link'); ?></li>
      <li>&bullet; <?php esc_html_e('Protect your affiliate links by using Cloaked Redirects', 'pretty-link'); ?></li>
      <li>&bullet; <?php esc_html_e('Redirect based on a visitor\'s location', 'pretty-link'); ?></li>
      <li>&bullet; <?php esc_html_e('Auto-prettylink your Pages &amp; Posts', 'pretty-link'); ?></li>
      <li>&bullet; <?php esc_html_e('Find out what works and what doesn\'t by split testing your links', 'pretty-link'); ?></li>
      <li>&bullet; <?php esc_html_e('And much, much more!', 'pretty-link'); ?></li>
    </ul>

    <div>&nbsp;</div>
    <div><?php esc_html_e('Plus, upgrading is fast, easy and won\'t disrupt any of your existing links or data. And there\'s even a 14 day money back guarantee.', 'pretty-link'); ?></div>
    <div>&nbsp;</div>
    <div><?php esc_html_e('We think you\'ll love it!', 'pretty-link'); ?></div>
    <div>&nbsp;</div>
    <div><a href="https://prettylinks.com/pl/license-alert/upgrade-1" class="button button-primary"><?php esc_html_e('Upgrade to Pro today!', 'pretty-link'); ?></a></div>
  <?php endif; ?>
<?php else: ?>
  <div class="prli-license-active">
    <div><h4><?php esc_html_e('Active License Key Information:', 'pretty-link'); ?></h4></div>
    <table>
      <tr>
        <td><?php esc_html_e('License Key:', 'pretty-link'); ?></td>
        <td>********-****-****-****-<?php echo esc_html(substr($li['license_key']['license'], -12)); ?></td>
      </tr>
      <tr>
        <td><?php esc_html_e('Status:', 'pretty-link'); ?></td>
        <td><b><?php echo esc_html(sprintf(__('Active on %s', 'pretty-link'), PrliUtils::site_domain())); ?></b></td>
      </tr>
      <tr>
        <td><?php esc_html_e('Product:', 'pretty-link'); ?></td>
        <td><?php echo esc_html($li['product_name']); ?></td>
      </tr>
      <tr>
        <td><?php esc_html_e('Activations:', 'pretty-link'); ?></td>
        <td>
          <?php
            printf(
              // translators: %1$s: open b tag, %2$s: close b tag, %3$d: current activation count, %4$s: max activations
              esc_html__('%1$s%3$d of %4$s%2$s sites have been activated with this license key', 'pretty-link'),
              '<b>',
              '</b>',
              esc_html($li['activation_count']),
              esc_html(ucwords($li['max_activations']))
            );
          ?>
        </td>
      </tr>
    </table>
    <div class="prli-deactivate-button"><a href="<?php echo esc_url(admin_url('admin.php?page=pretty-link-updates&action=deactivate&_wpnonce='.wp_create_nonce('pretty-link_deactivate'))); ?>" class="button button-primary" onclick="return confirm('<?php echo esc_attr(sprintf(__("Are you sure? Pretty Links Pro will not be functional on %s if this License Key is deactivated.", 'pretty-link'), PrliUtils::site_domain())); ?>');"><?php echo esc_html(sprintf(__('Deactivate License Key on %s', 'pretty-link'), PrliUtils::site_domain())); ?></a></div>
  </div>
  <?php if(!$this->is_installed()): ?>
    <div><a href="<?php echo esc_url($this->update_plugin_url()); ?>" class="button button-primary"><?php esc_html_e('Upgrade plugin to Pro', 'pretty-link'); ?></a></div>
    <div>&nbsp;</div>
  <?php endif; ?>
  <?php require(PRLI_VIEWS_PATH.'/admin/update/edge_updates.php'); ?>
  <br/>
  <div id="prli-version-string"><?php printf(esc_html__("You're currently running version %s of Pretty Links Pro", 'pretty-link'), '<b>'.esc_html(PRLI_VERSION).'</b>'); ?></div>
<?php endif; ?>

