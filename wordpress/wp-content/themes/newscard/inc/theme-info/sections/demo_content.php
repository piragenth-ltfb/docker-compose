<h3><a href="<?php echo esc_url( admin_url('themes.php?page=newscard-details&section=recommended_actions') ); ?>"><?php echo esc_html__('Setup manually', 'newscard'); ?></a> <?php echo esc_html__('or Follow the below steps to Import demo content:', 'newscard'); ?></h3>
<p><?php printf( esc_html__( 'Importing demo data is the easiest way to setup your site same like our demo. %s It will allow you to quickly edit everything instead of creating content from scratch.', 'newscard' ),'<br>'); ?></p>
<ol>
	<li><?php echo sprintf( esc_html__( 'Install and Activate the %1$s"One Click Demo Import"%2$s plugin. If you have not.', 'newscard' ), '<a target="_blank" href="' . esc_url('https://wordpress.org/plugins/one-click-demo-import/') . '">', '</a>' ); ?></li>
	<li><?php echo esc_html__('After activating it just go to "Import Demo Data" option under "Appearance"', 'newscard'); ?> </li>
	<li><?php echo sprintf( esc_html__( 'Download the desired demo data from %1$shere%2$s.', 'newscard' ), '<a target="_blank" href="' . esc_url('https://www.themehorse.com/theme-instruction/newscard/#DemoContent') . '">', '</a>' ); ?></li>
	<li><?php echo esc_html__('Choose a XML, WIE and DAT file and click on "Import Demo Data" button than you can see your site with our demo content.', 'newscard'); ?></li>
	<li><?php echo sprintf( esc_html__( 'Now go to the %1$sCustomize%2$s where all settings and theme options are available where you can easily customize different aspects of the theme.', 'newscard' ), '<a href="' . esc_url(admin_url('customize.php')) . '">', '</a>' ); ?></li>
</ol>