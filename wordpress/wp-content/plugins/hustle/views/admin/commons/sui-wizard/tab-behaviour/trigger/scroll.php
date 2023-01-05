<?php
/**
 * Scroll trigger settings.
 *
 * @package Hustle
 * @since 4.3.0
 */

$percentage_content = $this->render(
	'admin/commons/sui-wizard/tab-behaviour/trigger/scroll-percentage',
	array(
		'triggers' => $triggers,
	),
	true
);

$selector_content = $this->render(
	'admin/commons/sui-wizard/tab-behaviour/trigger/scroll-selector',
	array(
		'triggers' => $triggers,
	),
	true
);
?>

<?php /* translators: module type smallcaps and in singular */ ?>
<p class="sui-description" style="margin-bottom: 20px;"><?php printf( esc_html__( 'Trigger your %s when a visitor has scrolled down a certain percentage or scrolled passed an element on the page.', 'hustle' ), esc_html( $smallcaps_singular ) ); ?></p>

<?php
$this->render(
	'admin/global/sui-components/sui-tabs',
	array(
		'name'          => 'triggers.trigger_on_scroll',
		'radio'         => true,
		'saved_value'   => $triggers['on_scroll'],
		'sidetabs'      => true,
		'content'       => true,
		'content_class' => 'sui-tabs-content-lg',
		'options'       => array(
			'scrolled' => array(
				'label'   => esc_html__( 'Scroll percentage', 'hustle' ),
				'content' => $percentage_content,
			),
			'selector' => array(
				'label'   => esc_html__( 'Scroll to element', 'hustle' ),
				'content' => $selector_content,
			),
		),
	)
);