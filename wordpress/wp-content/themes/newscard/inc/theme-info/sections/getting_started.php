<div class="col-3 clearfix">
	<div class="col">
		<h3><i class="dashicons dashicons-megaphone"></i><?php echo esc_html__('Recommended Actions', 'newscard'); ?></h3>
		<p><?php echo esc_html__('Complete the list of steps so that you can set up your site same like our demo which is very easy to follow.', 'newscard'); ?></p>
		<a class="button button-primary" href="<?php echo esc_url( admin_url('themes.php?page=newscard-details&section=recommended_actions') ); ?>"><?php echo esc_html__('Recommended Actions', 'newscard'); ?></a>
	</div>

	<div class="col">
		<h3><i class="dashicons dashicons-book-alt"></i><?php echo esc_html__('Read Full Documentation', 'newscard'); ?></h3>
		<p><?php printf(
			/* translators: Theme Name */
			esc_html__('Read our full documentation for all the detailed information on how to setup and use %s theme.', 'newscard'), esc_html($this->theme_name) ); ?></p>
		<a class="button button-primary" target="_blank" rel="noopener noreferrer" href="https://www.themehorse.com/theme-instruction/newscard/"><?php echo esc_html__('Read Full Documentation', 'newscard'); ?></a>
	</div>

	<div class="col">
		<h3><i class="dashicons dashicons-upload"></i><?php echo esc_html__('Demo Content', 'newscard'); ?></h3>
		<p><?php echo esc_html__('Importing demo data is the easiest way to setup your site. Quickly edit everything instead of creating content from scratch.', 'newscard'); ?></p>
		<a class="button button-primary" href="<?php echo esc_url( admin_url('themes.php?page=newscard-details&section=demo_content') ); ?>"><?php echo esc_html__('Import Demo Content', 'newscard'); ?></a>
	</div>

	<div class="col">
		<h3><i class="dashicons dashicons-video-alt3"></i><?php echo esc_html__('Video Tutorial', 'newscard'); ?></h3>
		<p><?php echo esc_html__('Watch video tutorial to manually setup the front page (home page) same like our demo site instead of importing demo content.', 'newscard'); ?></p>
		<a class="button button-primary" target="_blank" rel="noopener noreferrer" href="<?php echo esc_url( 'https://youtu.be/cFQqkHfA4b4' ); ?>"><?php echo esc_html__('Watch Video Tutorial', 'newscard'); ?></a>
	</div>

	<div class="col">
		<h3><i class="dashicons dashicons-admin-customizer"></i><?php echo esc_html__('Theme Options', 'newscard'); ?></h3>
		<p><?php echo esc_html__('All settings and theme options are available via "Customizer" where you can easily customize different aspects of the theme.', 'newscard'); ?></p>
		<a class="button button-primary" href="<?php echo esc_url(admin_url('customize.php')); ?>"><?php echo esc_html__('Go to Theme Options', 'newscard'); ?></a>
	</div>
</div>