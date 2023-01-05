<div class="col-3 clearfix">
	<div class="col">
		<h3><i class="dashicons dashicons-book-alt"></i><?php echo esc_html__('Documentation', 'newscard'); ?></h3>
		<p><?php printf(
			/* translators: Theme Name */
			esc_html__('Read our full documentation for all the detailed information on how to setup and use %s theme.', 'newscard'), esc_html($this->theme_name) ); ?></p>
		<a class="button button-primary" target="_blank" rel="noopener noreferrer" href="https://www.themehorse.com/theme-instruction/newscard/"><?php echo esc_html__('Read Documentation', 'newscard'); ?></a>
	</div>

	<div class="col">
		<h3><i class="dashicons dashicons-portfolio"></i><?php echo esc_html__('Changelog', 'newscard'); ?></h3>
		<p><?php echo esc_html__('See the list on the latest version changes. Just see the changelog to get complete list of recent fixes and new features.', 'newscard'); ?></p>
		<a class="button button-primary" target="_blank" rel="noopener noreferrer" href="https://www.themehorse.com/changelogs/newscard-changelog/"><?php echo esc_html__('View Changelog', 'newscard'); ?></a>
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
		<h3><i class="dashicons dashicons-sos"></i><?php echo esc_html__('Contact Support', 'newscard'); ?></h3>
		<p><?php echo esc_html__('Still need support? Please create a support ticket in our dedicated forum and one of our support member will get back to you ASAP.', 'newscard'); ?></p>
		<a class="button button-primary" target="_blank" rel="noopener noreferrer" href="https://www.themehorse.com/support-forum/"><?php echo esc_html__('Contact Support', 'newscard'); ?></a>
	</div>
</div>