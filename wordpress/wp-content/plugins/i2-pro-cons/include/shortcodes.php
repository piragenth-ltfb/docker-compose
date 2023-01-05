<?php

function i2_pros_and_cons($atts, $content = null)
{

    $atts = shortcode_atts([
        'pros' => '',
        'cons' => '',
        'show_title' => false,
        'title' => '',
        'show_button' => false,
        'link_text' => '',
        'link' => '',
        'pros_title' => __('Pros', 'i2-pros-and-cons'),
        'cons_title' =>  __('Cons', 'i2-pros-and-cons'),
        'button_icon' => '',
        'pros_icon' => '',
        'cons_icon' => '',
        'heading_pros_icon' => '',
        'heading_cons_icon' => '',
        'use_heading_icon' => '',
        'className' => '',
        'button_display_block' => false
    ], $atts, 'i2_pros_and_cons');

    //  echo '<pre>'; 
    // // echo "dd :" . $atts['show_title'] === true;
    //  print_r($atts); 
    //  echo '</pre>';
    // // var_dump($atts['show_title']);
    //  exit();

    if (strlen($content) > 10) {
        $data = explode("###ER##GF####", do_shortcode($content), 2);
        $atts['pros'] = $data[0];
        $atts['cons'] = $data[1];
    }
    $options = get_option('i2_pros_and_cons', i2_pros_and_cons_options_default());
    // $icon =  isset( $atts['type'] ) && $atts['type'] == 'cons' ? $options['cons_icon'] : $options['pros_icon'];  
    // $useIcon = $options['use_icons'] == 1? "has-icon" : "no-icon"; 
    $prosIcon = $atts['pros_icon'] == '' ? $options['pros_icon'] : $atts['pros_icon'];
    $consIcon = $atts['cons_icon'] == '' ? $options['cons_icon'] : $atts['cons_icon'];
    $useHeadingIcon = $atts['use_heading_icon'] == '' ? $options['use_heading_icon'] : intval($atts['use_heading_icon']);

    // main wrapper classes
    $mwclasses = 'i2-pros-cons-main-wrapper theme-' . $options['parent_theme'];

    $mwclasses .=  $atts['className'] != '' ? ' ' . $atts['className'] : '';
    $mwclasses .=  $atts['show_title'] == 'true' ? ' has-title' : ' no-title';
    $mwclasses .=  $atts['show_button'] == 'true' ? ' has-button' : ' no-button';
    $mwclasses .=  $options['use_space_between_column'] == 1 ? ' has-space-between-column' : ' no-space-between-column';
    $mwclasses .=  $options['use_outer_border'] == 1 ? ' has-outer-border' : ' no-outer-border';
    $mwclasses .=  $options['use_round_corner'] == 1 ? ' has-round-corner' : ' no-round-corner';
    $mwclasses .=  $useHeadingIcon == 1 ? ' has-heading-icon' : ' no-heading-icon';

    //  echo $prosIcon;
    //  echo $options['pros_icon'];
    //  echo $atts['pros_icon'];



    // $headingProsIcon = $atts['heading_pros_icon'] == '' ? $options['heading_pros_icon'] : $atts['heading_pros_icon'];
    // $headingConsIcon = $atts['heading_cons_icon'] == '' ? $options['heading_cons_icon'] : $atts['heading_cons_icon'];
    // $buttonIcon = $atts['button_icon'] == '' ? $options['button_icon'] : $atts['button_icon'];

    $data  = '<div class="i2-pros-cons-icons ' . $mwclasses . '">';

    if ($atts['show_title'] == 'true') {
        $data .= '<h3 class="i2pctitle">' . $atts['title']  . '</h3>';
    }

    $data .= '<div class="i2-pros-cons-wrapper"><div class="i2-pros">';
    if ($useHeadingIcon == 1) {
        $headingProsIcon = $atts['heading_pros_icon'] == '' ? $options['heading_pros_icon'] : $atts['heading_pros_icon'];
        $data .= "<div class='heading-icon'><i class='{$headingProsIcon}'></i></div>";
    }
    $data .= '<strong class="i2-pros-title">' . $atts['pros_title']  . '</strong>';
    $data .= '<div class="section">';
    $data .= i2_pros_cons_list($atts['pros'], $options['use_icons'], $prosIcon);
    $data .= '</div></div>';
    if ($options['use_space_between_column'] == 1) {
        $data .= '<div class="i2-spacer"></div>';
    }
    $data .= '<div class="i2-cons">';
    if ($useHeadingIcon == 1) {
        $headingConsIcon = $atts['heading_cons_icon'] == '' ? $options['heading_cons_icon'] : $atts['heading_cons_icon'];
        $data .= "<div class='heading-icon'><i class='{$headingConsIcon}'></i></div>";
    }
    $data .= '<strong class="i2-cons-title">' . $atts['cons_title']  . '</strong>';
    $data .= '<div class="section">';
    $data .= i2_pros_cons_list($atts['cons'], $options['use_icons'], $consIcon);
    $data .= '</div></div></div>';

    if ($atts['show_button'] == 'true') {
        // var_dump($atts['button_display_block']);
        if (substr($atts['link'], 0, 4) !== "http") {
            $atts['link'] = 'https://' . $atts['link'];
        }
        $data .= '<div class="i2-button-wrapper' . ($atts['button_display_block'] == 'true' ? ' i2pc-block' : '')  . '"><a class="' . $options['button_theme'] . '" href="' .  $atts['link'] . '" rel="nofollow sponsored" target="_blank"> <i class="' . ($atts['button_icon'] == '' ? $options['button_icon'] : $atts['button_icon']) . '"></i> <span class="i2pc-btn-text">' .  $atts['link_text'] . '</span></a></div>';
    }
    $data .= '</div>';

    return $data;

    //return "<{$atts['heading']}>{$atts['title']}</{$atts['heading']}>";
}
add_shortcode('i2_pros_and_cons', 'i2_pros_and_cons');


function i2_pros_cons_list($data, $useIcon, $icon)
{
    // echo '<pre>'; 
    //  print_r($data); 
    //  echo '</pre>';
    $useIconClass = $useIcon == 1 ? "has-icon" : "no-icon";
    // $lines = explode("\n", $data);
    //  $lines = explode("\n", $data);
    $lines =  preg_split('/\r\n|\r|\n|<br[^>]*>/', $data);
    $list = "<ul class='{$useIconClass}'>";
    foreach ($lines as $key => $value) {
        if (strlen($value) > 0) {
            $list .= "<li>" . ($useIcon == 1 ? "<i class='{$icon}'></i>" : "") . $value . "</li>";
        }
    }
    return    $list . '</ul>';
}


add_shortcode('i2pc', 'i2_pros_and_cons');
function i2_cons_sc($attr, $content = null)
{
    return  $content;
}
function i2_pros_sc($attr, $content = null)
{
    return  $content . "###ER##GF####";
}
add_shortcode('i2pros', 'i2_pros_sc');
add_shortcode('i2cons', 'i2_cons_sc');
