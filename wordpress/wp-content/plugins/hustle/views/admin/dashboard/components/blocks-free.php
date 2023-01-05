<?php
/**
 * Dashboard blocks for free users.
 *
 * @uses ./box-upsell
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

		// WIDGET: Slide-ins.
		$this->render( 'admin/slidein/dashboard', $args_slideins );
		?>

	</div>

	<div class="sui-col-md-6">

		<?php
		// WIDGET: Upsell.
		$this->render( 'admin/dashboard/components/box-upsell' );

		// WIDGET: Social Shares.
		$this->render( 'admin/sshare/dashboard', $args_sshare );
		?>

	</div>

</div>