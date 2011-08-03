<?php 

$absolute_path = __FILE__;
$path_to_file = explode( 'wp-content', $absolute_path );
$path_to_wp = $path_to_file[0];
require_once( $path_to_wp.'/wp-load.php' );


$pc_post=$_GET['pc_post'];



header("Content-type: text/xml");

$output = <<<XML

<Piecemaker>
  <Contents>

XML;


$pc_height=get_option('slider_height');
$images = theme_functions('slideShow_getImages', get_post_meta($pc_post,'slideshow_set_value',true) );
$time=get_option('piecemaker_animation');
$autoplay=get_option('piecemaker_autoplay');
$delay=get_option('piecemaker_delay');

foreach ($images as $image){

	 $image_src = THEME_SCRIPTS.'/timthumb.php?src='.$image['src'].'&amp;h='.$pc_height.'&amp;w=960&amp;zc=1'; 
   	 $output.='<Image Source="'.$image_src.'" Title="'.$image['title'].'">';

	 $output.='<Text>&lt;h1&gt;'.$image['title'].'&lt;/h1&gt;&lt;p&gt;'.$image['desc'].'&lt;/p&gt;</Text>';
	 
	 if($image['link']) $output.='<Hyperlink URL="'.$image['link'].'" Target="_blank" />';
	 $output.='</Image>';
	 
	 
}

  
$output .= <<<XML
  </Contents>
  <Settings ImageWidth="960" ImageHeight="{$pc_height}" LoaderColor="0x333333" InnerSideColor="0x222222" SideShadowAlpha="0.8" DropShadowAlpha="0.7" DropShadowDistance="25" DropShadowScale="0.95" DropShadowBlurX="40" DropShadowBlurY="4" MenuDistanceX="20" MenuDistanceY="20" MenuColor1="0x999999" MenuColor2="0x333333" MenuColor3="0xFFFFFF" ControlSize="100" ControlDistance="20" ControlColor1="0x222222" ControlColor2="0xFFFFFF" ControlAlpha="0.8" ControlAlphaOver="0.95" ControlsX="480" ControlsY="280&#xD;&#xA;" ControlsAlign="center" TooltipHeight="30" TooltipColor="0x1a1a1a" TooltipTextY="5" TooltipTextStyle="P-Italic" TooltipTextColor="0xFFFFFF" TooltipMarginLeft="5" TooltipMarginRight="7" TooltipTextSharpness="50" TooltipTextThickness="-100" InfoWidth="400" InfoBackground="0x000000" InfoBackgroundAlpha="0.8" InfoMargin="15" InfoSharpness="0" InfoThickness="0" Autoplay="{$autoplay}" FieldOfView="45"></Settings>
  <Transitions>
    <Transition Pieces="9" Time="{$time}" Transition="easeInOutBack" Delay="{$delay}" DepthOffset="300" CubeDistance="30"></Transition>
    <Transition Pieces="10" Time="{$time}" Transition="easeInOutElastic" Delay="{$delay}" DepthOffset="200" CubeDistance="10"></Transition>
    <Transition Pieces="4" Time="{$time}" Transition="easeInOutCubic" Delay="{$delay}" DepthOffset="500" CubeDistance="50"></Transition>
    <Transition Pieces="6" Time="{$time}" Transition="easeInSine" Delay="{$delay}" DepthOffset="900" CubeDistance="5"></Transition>
  </Transitions>
</Piecemaker>
XML;






echo $output;



?>