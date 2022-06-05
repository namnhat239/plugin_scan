<?php
defined( 'ABSPATH' ) || exit;


function word_balloon_adjust_balloon_clay($atts , $load_setting , $balloon_data){

	if($atts['font_color']){
		$balloon_data['text_color'] = 'color:' .$atts['font_color']. ';';
	}
	if($atts['bg_color']){
		$balloon_data['background_color'] = 'background:' . $atts['bg_color'] . ';';
	}

	$on_balloon_margin = 0;
	if($atts['name_position'] === 'on_balloon'){
		$on_balloon_margin = $load_setting['name_margin'];
	}elseif($atts['name_position'] === 'on_avatar'){
		$on_balloon_margin = -$load_setting['name_margin'];
	}

	if($atts['name_position'] === 'on_avatar') $balloon_data['box_padding_top'] = $load_setting['name_margin'];

	return $balloon_data;

}

function word_balloon_adjust_avatar_clay($atts , $load_setting , $avatar_data){

	if($atts['name_position'] === "on_balloon"){
		$avatar_data["avatar_padding_top"] = $load_setting['name_margin'];
	}

	return $avatar_data;

}
