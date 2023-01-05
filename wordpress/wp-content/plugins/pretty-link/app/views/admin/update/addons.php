<?php if(!defined('ABSPATH')) {die('You are not allowed to call this page directly.');} ?>

<div class="prli-page-title"><?php esc_html_e('Pretty Link Add-ons', 'pretty-link'); ?></div>

<?php $addons = PrliUpdateController::addons(true); ?>

<?php if(empty($addons)): ?>
  <h3><?php esc_html_e('There were no Add-ons found for your license or lack thereof...', 'pretty-link'); ?></h3>
<?php else: ?>
  <table class="widefat">

    <thead>
      <tr>
        <th><?php esc_html_e('Add-on', 'pretty-link'); ?></th>
        <th><?php esc_html_e('Description', 'pretty-link'); ?></th>
        <th><?php esc_html_e('Install', 'pretty-link'); ?></th>
      </tr>
    </thead>

    <tbody>
      <?php $alternate = true; ?>
      <?php foreach($addons as $slug => $info):
        $info = (object)$info;

        $update_available = false;
        if(($installed = (isset($info->extra_info->directory) && is_dir(WP_PLUGIN_DIR . '/' . $info->extra_info->directory)))) {
          $update_available = PrliAddonsHelper::is_update_available($info->extra_info->main_file, $info->version);
        }

        include_once(ABSPATH . 'wp-admin/includes/plugin.php');
        $active = (isset($info->extra_info->main_file) && is_plugin_active($info->extra_info->main_file));

        ?>
        <tr class="<?php echo $alternate ? 'alternate' : ''; ?>">
          <td>
            <strong>
            <?php

              echo esc_html(isset($info->extra_info->list_title) ? $info->extra_info->list_title : $info->product_name);

              if($update_available) {
                echo ' ' . esc_html__('(Update Available)', 'pretty-link');
              }
            ?>
            </strong>
          </td>
          <td><?php echo esc_html($info->extra_info->description); ?></td>
          <td>
            <?php if($installed && $active): ?>
              <a class="button" href="" style="pointer-events: none;" disabled><?php esc_html_e('Installed & Active', 'pretty-link'); ?></a>
            <?php elseif($installed && !$active): ?>
              <a class="button" href="" style="pointer-events: none;" disabled><?php esc_html_e('Installed & Inactive', 'pretty-link'); ?></a>
            <?php else: ?>
              <a class="button button-primary" href="<?php echo esc_url(PrliAddonsHelper::install_url($slug)); ?>"><?php esc_html_e('Install', 'pretty-link'); ?></a>
            <?php endif; ?>
          </td>
        </tr>

        <?php $alternate = !$alternate; ?>
      <?php endforeach; ?>
    </tbody>

  </table>
<?php endif; ?>

