<?php
$inlineStyle = "";
$options = get_option( 'i2_pros_and_cons', i2_pros_and_cons_options_default());
$options = array_merge(i2_pros_and_cons_options_default(), $options);

    $useBorder = $options['use_border'] != null ? $options['use_border'] : '';
    $borderColor = $options['border_color'] != null ? $options['border_color'] : '';
    $borderSize = $options['border_size'] != null ? $options['border_size'] : '';
    $useBorderShadow = $options['use_border_shadow'] != null ? $options['use_border_shadow'] : '';
    $useSpaceBetweenColumn = $options['use_space_between_column'] != null ? $options['use_space_between_column'] : '';

    $headingTitleCenter = $options['heading_title_center'] != null ? $options['heading_title_center'] : '';
    $headingTitleColor = $options['heading_title_color'] != null ? $options['heading_title_color'] : '';
    $headingTitleBackground = $options['heading_title_background'] != null ? $options['heading_title_background'] : '';
    $useOuterBorder = $options['use_outer_border'] != null ? $options['use_outer_border'] : '';
    

    $headingCenter = $options['heading_center'] != null ? $options['heading_center'] : '';
    $headingFontSize = $options['heading_font_size'] != null ? $options['heading_font_size'] : '';
    $headingColor = $options['heading_color'] != null ? $options['heading_color'] : '';
    $prosHeadingBackground = $options['pros_heading_background'] != null ? $options['pros_heading_background'] : '';
    $consHeadingBackground = $options['cons_heading_background'] != null ? $options['cons_heading_background'] : '';

    $useHeadingIcon = $options['use_heading_icon'] != null ? $options['use_heading_icon'] : '';
    $headingProsIconColor = $options['heading_pros_icon_color'] != null ? $options['heading_pros_icon_color'] : '';
    $headingConsIconColor = $options['heading_cons_icon_color'] != null ? $options['heading_cons_icon_color'] : '';


    $textUnderline = $options['text_underline'] != null ? $options['text_underline'] : '';
    $sectionFontSize = $options['body_font_size'] != null ? $options['body_font_size'] : '';
    $sectionColor = $options['body_color'] != null ? $options['body_color'] : '';
    $prosBackground = $options['pros_background'] != null ? $options['pros_background'] : '';
    $consBackground = $options['cons_background'] != null ? $options['cons_background'] : '';

    $useIcon = $options['use_icons'] != null ? $options['use_icons'] : '';
    $iconTop = $options['icon_top'] != null ? $options['icon_top'] : '';

    $prosIconColor = $options['pros_icon_color'] != null ? $options['pros_icon_color'] : '';
    $consIconColor = $options['cons_icon_color'] != null ? $options['cons_icon_color'] : '';



    if($useBorder == 1){
        $inlineStyle .= " .i2-pros-cons-wrapper .i2-cons,.i2-pros-cons-wrapper .i2-pros{border: {$borderSize}px solid {$borderColor};}";
        if($useSpaceBetweenColumn == 1){
            $inlineStyle .= " .i2-pros-cons-wrapper .i2-pros{border-right: {$borderSize}px solid {$borderColor} !important;}";   
        }
        if($useOuterBorder == 1){
        $inlineStyle .= " .i2-pros-cons-wrapper .i2-pros{border-left: none; border-top:none;} .i2-pros-cons-wrapper .i2-cons{border-right: none; border-top:none}";  
          $inlineStyle .= " .no-button .i2-pros, .no-button .i2-cons{border-bottom:none;}";  
          $inlineStyle .= " .has-title .i2-pros, .has-title .i2-cons{border-top:{$borderSize}px solid {$borderColor} !important;}";  
        }
    }

    if($useBorderShadow == 1){
        $inlineStyle .= " .i2-pros-cons-wrapper .i2-pros::before, .i2-pros-cons-wrapper .i2-cons::before {content: '';position: absolute;width: 100%;bottom: 0px;z-index: -1;-webkit-box-shadow: -30px 6px 15px 1px rgba(212, 212, 212, 0.55) !important;box-shadow: -30px 6px 15px 1px rgba(212, 212, 212, 0.55)!important; }";
        $inlineStyle .= " .i2-pros-cons-wrapper .i2-pros-title::before, .i2-pros-cons-wrapper .i2-cons-title::before {content: '';position: absolute;top: 40px;bottom: 0px;width: 1px;left: 0;z-index: -1;background-color: transparent; -webkit-box-shadow: -4px 3px 10px 0px #d4d4d4 !important;box-shadow: -4px 3px 10px 0px #d4d4d4!important; }";
    }
    if($useSpaceBetweenColumn == 1){
        $inlineStyle .= " .i2-pros-cons-wrapper .i2-cons,.i2-pros-cons-wrapper .i2-pros{width: 48%;}";  
        $inlineStyle .= " .i2-pros-cons-wrapper .i2-spacer{width: 4%; display:block;}";  
    }

    if($useOuterBorder == 1){
      $inlineStyle .= " .i2-pros-cons-main-wrapper{border: {$options['border_outer_size']}px solid {$borderColor};}";
    }
    if($headingTitleCenter == 1)
     $inlineStyle .= " .i2-pros-cons-main-wrapper .i2pctitle{text-align: center!important;}";

     if($headingTitleColor != '')
     $inlineStyle .= " .i2-pros-cons-main-wrapper .i2pctitle{color: {$headingTitleColor}!important;}";

     if($headingTitleBackground != '')
     $inlineStyle .= " .i2-pros-cons-main-wrapper .i2pctitle{background-color: {$headingTitleBackground}!important;}";

    if($headingCenter == 1)
    $inlineStyle .= " .i2-pros-cons-wrapper .i2-cons-title,.i2-pros-cons-wrapper .i2-pros-title{text-align: center!important;}";

    if($headingFontSize != '')
    $inlineStyle .= " .i2-pros-cons-wrapper .i2-cons-title,.i2-pros-cons-wrapper .i2-pros-title{font-size: {$headingFontSize}px!important;}";

    if($headingColor != '')
    $inlineStyle .= " .i2-pros-cons-wrapper .i2-cons-title,.i2-pros-cons-wrapper .i2-pros-title{color: {$headingColor}!important;}";

    if($prosHeadingBackground != '')
    $inlineStyle .=  ".i2-pros-cons-wrapper .i2-pros-title {background-color: {$prosHeadingBackground} !important;}";

    if($consHeadingBackground != '')
    $inlineStyle .= " .i2-pros-cons-wrapper .i2-cons-title{background-color: {$consHeadingBackground}!important;}";

    if($useHeadingIcon == 1){
        $prosBorderColor = i2pc_rgba_onverter($prosIconColor);
        $consBorderColor = i2pc_rgba_onverter($consIconColor);
        $inlineStyle .= " .i2-pros-cons-wrapper .i2-cons-title,.i2-pros-cons-wrapper .i2-pros-title{display:none;}";
        $inlineStyle .= " .i2-pros-cons-wrapper .i2-cons,.i2-pros-cons-wrapper .i2-pros{padding-top:90px;}";
        $inlineStyle .= " .i2-pros-cons-wrapper {padding-top:80px;}";
        $inlineStyle .= " .i2-pros-cons-wrapper .i2-pros .heading-icon i {background-color:{$prosIconColor};}";
        $inlineStyle .= " .i2-pros-cons-wrapper .i2-cons .heading-icon i {background-color:{$consIconColor};}";
        $inlineStyle .= " .i2-pros-cons-wrapper .i2-pros  {border-color:{$prosIconColor}; border-right-color:{$prosIconColor} !important;border-color:{$prosBorderColor};border-right-color:{$prosBorderColor}!important;}";
        $inlineStyle .= " .i2-pros-cons-wrapper .i2-cons  {border-color:{$consIconColor};border-color:{$consBorderColor};}";
        $inlineStyle .= " .i2-pros-cons-wrapper .section{padding-right:5%; padding-left:5%;}";
        $inlineStyle .= " @media only screen and (max-width: 992px) {.i2-pros-cons-wrapper .i2-pros{margin-bottom:85px;}}";


    }


    if($textUnderline == 1)
    $inlineStyle .= " .i2-pros-cons-wrapper .section ul li{text-decoration: underline!important;}";

    if($sectionFontSize != '')
    $inlineStyle .= " .i2-pros-cons-wrapper ul li {font-size: {$sectionFontSize}px!important; line-height : "  . $sectionFontSize * 1.3 . "px;}";

    if($sectionColor != '')
    $inlineStyle .= " .i2-pros-cons-wrapper .i2-pros,.i2-pros-cons-wrapper .i2-cons {color: {$sectionColor}!important;}";

    if ($prosBackground != '')
    $inlineStyle .= " .i2-pros-cons-wrapper .i2-pros{background-color: {$prosBackground}!important;}";

    if($consBackground != '')
    $inlineStyle .= " .i2-pros-cons-wrapper .i2-cons{background-color: {$consBackground}!important;}";
   



   if(isset($prosIcon) && $prosIcon != '' && $prosIcon != '0')
   $inlineStyle .= " .i2-pros-cons-wrapper .i2-pros li{background-image: url('{$imgUrl}y{$prosIcon}.png')!important;}";

    if(isset($consIcon) && $consIcon != '' && $consIcon != '0')
    $inlineStyle .= " .i2-pros-cons-wrapper .i2-cons li{background-image: url('{$imgUrl}n{$consIcon}.png')!important;}";
    
    if(isset($prosIcon) && $prosIcon == '0'){
        $inlineStyle .= " .i2-pros-cons-wrapper .i2-pros li{background-image: none!important; padding-left: 15px !important;}";  
    }
    if(isset($consIcon) && $consIcon == '0'){
        $inlineStyle .= " .i2-pros-cons-wrapper .i2-cons li{background-image: none!important;padding-left: 15px !important;}";  
    }

    if($iconTop != ''){
        $inlineStyle .= " .i2-pros-cons-wrapper .section ul li i{top: {$iconTop}px!important;}";  
    }
    if($prosIconColor != ''){
        $inlineStyle .= " .i2-pros-cons-wrapper .i2-pros  ul li i{color: {$prosIconColor}!important;}";  
    }
    if($consIconColor != ''){
        $inlineStyle .= " .i2-pros-cons-wrapper .i2-cons ul li i{color: {$consIconColor}!important;}";  
    }


                // added box shadow v 1.3.1              
                // 'use_box_shadow' => 0,
                // 'outer_box_shadow' => 0,
                // 'box_shadow_height' => 4,
                // 'box_shadow_color' => '#000000',
                // 'box_shadow_alpha' => 50,

                /*
                .i2-pros-cons-main-wrapper .i2-pros-cons-wrapper{
	box-shadow: 0 1px 2px rgba(0,0,0,0.2);
}
                
.i2-pros-cons-main-wrapper .i2-pros-cons-wrapper .i2-pros,.i2-pros-cons-main-wrapper .i2-pros-cons-wrapper .i2-cons,{
	box-shadow: 0 1px 2px rgba(0,0,0,0.2);
}
                */

    $useBoxShadow = $options['use_box_shadow'] != null ? $options['use_box_shadow'] : '';

    if( $useBoxShadow != ''){
      $outerBoxShadow = $options['outer_box_shadow'] != null ? $options['outer_box_shadow'] : '';
      $boxShadowHeight = $options['box_shadow_height'] != null ? $options['box_shadow_height'] : 4;    
      $boxShadowWidth = $boxShadowHeight / 2;    
      $boxShadowColor = i2pc_rgba_onverter($options['box_shadow_color'], (.01 * $options['box_shadow_alpha']));

      if(isset($outerBoxShadow) && $outerBoxShadow != '' && $outerBoxShadow != '0'){
         $inlineStyle .= " .i2-pros-cons-main-wrapper .i2-pros-cons-wrapper{box-shadow: 0 {$boxShadowWidth}px {$boxShadowHeight}px {$boxShadowColor};}";  
      }else{
        $inlineStyle .= " .i2-pros-cons-main-wrapper .i2-pros-cons-wrapper .i2-pros,.i2-pros-cons-main-wrapper .i2-pros-cons-wrapper .i2-cons{box-shadow: 0 {$boxShadowWidth}px {$boxShadowHeight}px {$boxShadowColor};}"; 
      }
    }


  if($options['button_theme'] != "no-style"){

    $buttonColor = $options['button_color'] != null ? $options['button_color'] : '';
    $buttonGradientColor = $options['button_gradient_color'] != null ? $options['button_gradient_color'] : '';

    $buttonTextColor = $options['button_text_color'] != null ? $options['button_text_color'] : '';
    $buttonRadius = $options['button_radius'] != null ? $options['button_radius'] : '';
    $buttonRadiusInPercent = $options['button_radius_in_percent'] == 1 ? "%" : 'px';
    $buttonLineHeight = $options['button_line_height'] != null ? $options['button_line_height'] : '';
    $buttonMinWidth = $options['button_min_width'] != 'auto' ? $options['button_min_width'] . "%" : '';  
    $buttonFontSize = $buttonLineHeight != '' ? $buttonLineHeight * .55  : '';  

    if($buttonTextColor != ''){
      $inlineStyle .= " .i2-pros-cons-main-wrapper .i2-button-wrapper a{color: {$buttonTextColor}!important;}";  
    }
    if($buttonColor != ''){
      $inlineStyle .= " .i2-pros-cons-main-wrapper .i2-button-wrapper a{background-color: {$buttonColor}; border-color:{$buttonColor};}";  
    } 
    if($buttonGradientColor != ''){
      $inlineStyle .= " .i2-pros-cons-main-wrapper .i2-button-wrapper a:hover{background-color: {$buttonGradientColor}; border-color:{$buttonGradientColor};}";  
    } 
    if($buttonRadius != ''){
      $inlineStyle .= " .i2-pros-cons-main-wrapper .i2-button-wrapper a{-moz-border-radius: {$buttonRadius}{$buttonRadiusInPercent}!important;-webkit-border-radius: {$buttonRadius}{$buttonRadiusInPercent}!important; border-radius: {$buttonRadius}{$buttonRadiusInPercent}!important;}";  
    }  
    if($buttonLineHeight != ''){
      $inlineStyle .= " .i2-pros-cons-main-wrapper .i2-button-wrapper a{line-height : {$buttonLineHeight}px; font-size:{$buttonFontSize}px;}";  
    } 
    if($buttonMinWidth != ''){
      $inlineStyle .= " .i2-pros-cons-main-wrapper .i2-button-wrapper a{min-width:{$buttonMinWidth}; }";  
    } 

    switch ($options['button_theme']) {
         case 'i2pc-btn-theme-00' : // 'default'
        $shadow = luminanceLight($buttonColor, 0.48) ;
        $inlineStyle .= " .i2-pros-cons-main-wrapper .i2-button-wrapper a{background-color: {$buttonColor}!important;
        -moz-box-shadow: 0px 1px 0px 0px {$shadow};
        -webkit-box-shadow: 0px 1px 0px 0px {$shadow};
        box-shadow: 0px 1px 0px 0px {$shadow};
        background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, {$buttonColor}), color-stop(1, {$buttonGradientColor}));
        background:-moz-linear-gradient(top, {$buttonColor} 5%, {$buttonGradientColor} 100%);
        background:-webkit-linear-gradient(top, {$buttonColor} 5%, {$buttonGradientColor} 100%);
        background:-o-linear-gradient(top, {$buttonColor} 5%, {$buttonGradientColor} 100%);
        background:-ms-linear-gradient(top, {$buttonColor} 5%, {$buttonGradientColor} 100%);
        background:linear-gradient(to bottom, {$buttonColor} 5%, {$buttonGradientColor} 100%);
        filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='{$buttonColor}', endColorstr='{$buttonGradientColor}',GradientType=0);
        border:1px solid {$buttonColor};
        text-shadow:0px 1px 0px {$buttonColor};
        }";  
        $inlineStyle .= " .i2-pros-cons-main-wrapper .i2-button-wrapper a:hover {
        background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, {$buttonGradientColor}), color-stop(1, {$buttonColor}));
        background:-moz-linear-gradient(top, {$buttonGradientColor} 5%, {$buttonColor} 100%);
        background:-webkit-linear-gradient(top, {$buttonGradientColor} 5%, {$buttonColor} 100%);
        background:-o-linear-gradient(top, {$buttonGradientColor} 5%, {$buttonColor} 100%);
        background:-ms-linear-gradient(top, {$buttonGradientColor} 5%, {$buttonColor} 100%);
        background:linear-gradient(to bottom, {$buttonGradientColor} 5%, {$buttonColor} 100%);
        filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='{$buttonGradientColor}', endColorstr='{$buttonColor}',GradientType=0);
        background-color:{$buttonGradientColor};
        }";   
        break;
        case 'i2pc-btn-theme-02' : // '3d' :

        $inlineStyle .= " .i2-pros-cons-main-wrapper .i2-button-wrapper a{background-color: {$buttonColor}!important;
        -moz-box-shadow: inset 0px 1px 0px 0px {$shadow};
        -webkit-box-shadow: inset 0px 1px 0px 0px {$shadow};
        box-shadow: inset 0px 1px 0px 0px {$shadow};
        background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, {$buttonColor}), color-stop(1, {$buttonGradientColor}));
        background:-moz-linear-gradient(top, {$buttonColor} 5%, {$buttonGradientColor} 100%);
        background:-webkit-linear-gradient(top, {$buttonColor} 5%, {$buttonGradientColor} 100%);
        background:-o-linear-gradient(top, {$buttonColor} 5%, {$buttonGradientColor} 100%);
        background:-ms-linear-gradient(top, {$buttonColor} 5%, {$buttonGradientColor} 100%);
        background:linear-gradient(to bottom, {$buttonColor} 5%, {$buttonGradientColor} 100%);
        filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='{$buttonColor}', endColorstr='{$buttonGradientColor}',GradientType=0);
        border:1px solid {$buttonColor};
        text-shadow:0px 1px 0px {$buttonColor};
        }";  
        $inlineStyle .= " .i2-pros-cons-main-wrapper .i2-button-wrapper a:hover {
        background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, {$buttonGradientColor}), color-stop(1, {$buttonColor}));
        background:-moz-linear-gradient(top, {$buttonGradientColor} 5%, {$buttonColor} 100%);
        background:-webkit-linear-gradient(top, {$buttonGradientColor} 5%, {$buttonColor} 100%);
        background:-o-linear-gradient(top, {$buttonGradientColor} 5%, {$buttonColor} 100%);
        background:-ms-linear-gradient(top, {$buttonGradientColor} 5%, {$buttonColor} 100%);
        background:linear-gradient(to bottom, {$buttonGradientColor} 5%, {$buttonColor} 100%);
        filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='{$buttonGradientColor}', endColorstr='{$buttonColor}',GradientType=0);
        background-color:{$buttonGradientColor};
        }";   
      }    
  }


if(!function_exists('i2_pros_cons_custom_style')) {
    function i2_pros_cons_custom_style()
    {
        global $inlineStyle;
         wp_add_inline_style( 'i2-pros-cons-block-style-css', $inlineStyle );
     }
    } 

    if(!function_exists('i2_pros_cons_admin_custom_style')) {
        function i2_pros_cons_admin_custom_style()
        {
            global $inlineStyle;
             wp_add_inline_style( 'i2_pro_cons_editor_style', $inlineStyle );
         }
        } 

   add_action( 'wp_enqueue_scripts', 'i2_pros_cons_custom_style' );
   add_action( 'admin_enqueue_scripts', 'i2_pros_cons_admin_custom_style' );


   function i2pc_rgba_onverter($color, $alpha = .3) {
       if(strlen($color) !== 7){
           return $color;
       }
        $color = substr( $color, 1 );
        $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
        //Convert hexadec to rgb http://php.net/manual/en/function.hexdec.php
        $rgb =  array_map('hexdec', $hex);
        return 'rgba('.implode(",",$rgb).', ' . $alpha .')';
 
}

function luminanceLight($hexcolor, $percent)
{
  if ( strlen( $hexcolor ) < 6 ) {
    $hexcolor = $hexcolor[0] . $hexcolor[0] . $hexcolor[1] . $hexcolor[1] . $hexcolor[2] . $hexcolor[2];
  }
  $hexcolor = array_map('hexdec', str_split( str_pad( str_replace('#', '', $hexcolor), 6, '0' ), 2 ) );

  foreach ($hexcolor as $i => $color) {
    $from = $percent < 0 ? 0 : $color;
    $to = $percent < 0 ? $color : 255;
    $pvalue = ceil( ($to - $from) * $percent );
    $hexcolor[$i] = str_pad( dechex($color + $pvalue), 2, '0', STR_PAD_LEFT);
  }

  return '#' . implode($hexcolor);
}

function luminanceDark($hexcolor, $percent)
{
  if ( strlen( $hexcolor ) < 6 ) {
    $hexcolor = $hexcolor[0] . $hexcolor[0] . $hexcolor[1] . $hexcolor[1] . $hexcolor[2] . $hexcolor[2];
  }
  $hexcolor = array_map('hexdec', str_split( str_pad( str_replace('#', '', $hexcolor), 6, '0' ), 2 ) );

  foreach ($hexcolor as $i => $color) {
    $from = $percent < 0 ? 0 : $color;
    $to = $percent < 0 ? $color : 0;
    $pvalue = ceil( ($to - $from) * $percent );
    $hexcolor[$i] = str_pad( dechex($color + $pvalue), 2, '0', STR_PAD_LEFT);
  }

  return '#' . implode($hexcolor);
}

function darken_color($rgb, $darker=2) {

    $hash = (strpos($rgb, '#') !== false) ? '#' : '';
    $rgb = (strlen($rgb) == 7) ? str_replace('#', '', $rgb) : ((strlen($rgb) == 6) ? $rgb : false);
    if(strlen($rgb) != 6) return $hash.'000000';
    $darker = ($darker > 1) ? $darker : 1;

    list($R16,$G16,$B16) = str_split($rgb,2);

    $R = sprintf("%02X", floor(hexdec($R16)/$darker));
    $G = sprintf("%02X", floor(hexdec($G16)/$darker));
    $B = sprintf("%02X", floor(hexdec($B16)/$darker));

    return $hash.$R.$G.$B;
}

function i2pc_color_luminance( $hex, $percent ) {
    

	// validate hex string
	
	$hex = preg_replace( '/[^0-9a-f]/i', '', $hex );
	$new_hex = '#';
	
	if ( strlen( $hex ) < 6 ) {
		$hex = $hex[0] + $hex[0] + $hex[1] + $hex[1] + $hex[2] + $hex[2];
	}
	
	// convert to decimal and change luminosity
	for ($i = 0; $i < 3; $i++) {
		$dec = hexdec( substr( $hex, $i*2, 2 ) );
		$dec = min( max( 0, $dec + $dec * $percent ), 255 ); 
		$new_hex .= str_pad( dechex( $dec ) , 2, 0, STR_PAD_LEFT );
	}		
	
	return $new_hex;
}

function i2pc_color_luminance_2( $hexcolor, $percent ) {
    
    if ( strlen( $hexcolor ) < 6 ) {
        $hexcolor = $hexcolor[0] . $hexcolor[0] . $hexcolor[1] . $hexcolor[1] . $hexcolor[2] . $hexcolor[2];
      }
      $hexcolor = array_map('hexdec', str_split( str_pad( str_replace('#', '', $hexcolor), 6, '0' ), 2 ) );
    
      foreach ($hexcolor as $i => $color) {
        $from = $percent < 0 ? 0 : $color;
        $to = $percent < 0 ? $color : 255;
        $pvalue = ceil( ($to - $from) * $percent );
        $hexcolor[$i] = str_pad( dechex($color + $pvalue), 2, '0', STR_PAD_LEFT);
      }
    
      return '#' . implode($hexcolor);
}