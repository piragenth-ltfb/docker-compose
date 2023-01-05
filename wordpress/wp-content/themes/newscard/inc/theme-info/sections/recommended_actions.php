<div class="theme-info-recommended-action-box" style="padding-top: 15px;">
	<h3>
		<i class="dashicons dashicons-info info-demo-content"></i>
		<a href="<?php echo esc_url( admin_url('themes.php?page=newscard-details&section=demo_content') ); ?>">
			<?php echo esc_html__('Import Demo Content', 'newscard'); ?>
		</a>
		<?php echo esc_html__('or', 'newscard'); ?>
		<a target="_blank" href="<?php echo esc_url( 'https://youtu.be/cFQqkHfA4b4' ); ?>">
			<?php echo esc_html__('Watch Video Tutorial', 'newscard'); ?>
		</a>
		<?php echo esc_html__('or Follow the below steps to setup manually:', 'newscard'); ?>
	</h3>
</div>
<div class="theme-info-recommended-action-box">
	<h3><?php echo esc_html__('Step 1: Create a new page with "Front Page Template"', 'newscard'); ?></h3>
	<ol>
		<li><?php echo esc_html__('Create a new page with any title', 'newscard'); ?></li>
		<li><?php echo esc_html__('Select "Front Page Template" for the option Page Attributes > Template which you can find it from the right section of the page editor.', 'newscard'); ?> </li>
		<li><?php echo esc_html__('Click on Publish', 'newscard'); ?></li>
	</ol>
	<a class="button" target="_blank" href="<?php echo esc_url(admin_url('post-new.php?post_type=page')); ?>"><?php echo esc_html__('Create New Page', 'newscard'); ?></a>
</div>
<div class="theme-info-recommended-action-box">
	<h3><?php echo esc_html__('Step 2: Set "Your homepage displays" to "A Static Page"', 'newscard'); ?></h3>
	<ol>
		<li><?php echo esc_html__('Go to "Appearance > Customize > Homepage Settings"', 'newscard'); ?></li>
		<li><?php echo esc_html__('Set "Your homepage displays" to "A Static Page"', 'newscard'); ?></li>
		<li><?php echo esc_html__('Select the page that you have created in the step 1 for "Homepage"', 'newscard'); ?></li>
		<li><?php echo esc_html__('Click on Publish', 'newscard'); ?></li>
	</ol>
	<a class="button" target="_blank" href="<?php echo esc_url(admin_url('options-reading.php')); ?>"><?php echo esc_html__('Assign Static Page', 'newscard'); ?></a>
</div>

<div class="theme-info-recommended-action-box">
	<h3><?php echo esc_html__('Step 3: Set Widgets', 'newscard'); ?></h3>
	<ol>
		<li><?php echo esc_html__('Go to "Appearance > Widgets"', 'newscard'); ?></li>
		<li><?php echo esc_html__('You can see 3 additional widgets "TH: Horizontal/Vertical Posts, TH: Card/Block Posts and TH: Recent Posts" which is specally designed for this theme. Drag and Drop these widget in "Front Page Content Section" or "Front Page Sidebar Section" as per your wish', 'newscard'); ?></li>
		<li><?php echo esc_html__('Set up the content/settings accordingly to the widget options', 'newscard'); ?></li>
		<li><?php echo esc_html__('Click on Save', 'newscard'); ?></li>
	</ol>
	<a class="button" target="_blank" href="<?php echo esc_url(admin_url('widgets.php')); ?>"><?php echo esc_html__('Set Widgets', 'newscard'); ?></a>
</div>

<div class="theme-info-recommended-action-box">
	<h3><?php echo esc_html__('Step 4: Theme Options', 'newscard'); ?></h3>
	<p><?php echo esc_html__('Theme uses customizer API for theme options. All settings and theme options are available via "Appearance > Customize" where you can easily customize different aspects of the theme', 'newscard'); ?></p>
	<a class="button" href="<?php echo esc_url(admin_url('customize.php')); ?>"><?php echo esc_html__('Go to Theme Options', 'newscard'); ?></a>
</div>