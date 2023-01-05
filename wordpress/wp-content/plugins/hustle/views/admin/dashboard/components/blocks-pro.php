<?php
/**
 * Dashboard blocks for pro users.
 *
 * @uses ./../../popup/dashboard
 * @uses ./../../slidein/dashboard
 * @uses ./../../embedded/dashboard
 * @uses ./../../sshare/dashboard
 *
 * @package Hustle
 * @since 4.3.0
 */

$args_popups = array(
	'capability' => $capability,
	'popups'     => $popups,
);

$args_slideins = array(
	'capability' => $capability,
	'slideins'   => $slideins,
);

$args_embeds = array(
	'capability' => $capability,
	'embeds'     => $embeds,
);

$args_sshare = array(
	'capability'      => $capability,
	'social_sharings' => $social_sharings,
);
?>

<div class="sui-row">

	<div class="sui-col-md-6">

		<?php
		// WIDGET: Pop-ups.
		$this->render( 'admin/popup/dashboard', $args_popups );

		// WIDGET: Embeds.
		$this->render( 'admin/embedded/dashboard', $args_embeds );
		?>

	</div>

	<div class="sui-col-md-6">

		<?php
		// WIDGET: Slide-ins.
		$this->render( 'admin/slidein/dashboard', $args_slideins );

		// WIDGET: Social Shares.
		$this->render( 'admin/sshare/dashboard', $args_sshare );
		?>

	</div>

</div>