<?php
defined( 'ABSPATH' ) || exit;

function word_balloon_adjust_balloon_bump_2($atts , $load_setting , $balloon_data){

	if($atts['font_color']){
		$balloon_data['text_color'] = 'color:' .$atts['font_color']. ';';
	}

	$balloon_data['box_padding_top'] = $load_setting['name_margin'];
	if($atts['name_position'] === 'on_avatar'){
		$balloon_data['box_padding_top'] = $load_setting['name_margin'] * 2;
	}elseif($atts['name_position'] === 'on_balloon'){
		$balloon_data['box_padding_top'] = '';
	}

	return $balloon_data;

}

/*
function word_balloon_adjust_avatar_bump_2($atts , $load_setting , $avatar_data){

	return $avatar_data;

}
*/
