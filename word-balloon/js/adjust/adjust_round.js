


function word_balloon_balloon_change_round(data) {

	var w_b_ava_box = document.getElementById('w_b_ava_box');
	var w_b_bal_box = document.getElementById('w_b_bal_box');
	//var w_b_bal = document.getElementById('w_b_bal');

	w_b_ava_box.classList.add( 'w_b_mta' );
	w_b_bal_box.classList.add( 'w_b_mta' );

	document.getElementById('w_b_edit_balloon_background_alpha_wrap').style.display = 'block';
	document.getElementById('w_b_edit_balloon_text_color_wrap').style.display = 'block';
	document.getElementById('w_b_edit_balloon_border_color_wrap').style.display = 'block';
	document.getElementById('w_b_edit_balloon_border_style_wrap').style.display = 'block';
	document.getElementById('w_b_edit_balloon_border_width_wrap').style.display = 'block';
	document.getElementById('w_b_edit_border_wrap').style.display = 'block';
	document.getElementById('w_b_edit_border_label').style.display = 'inline-block';

	return data;

}

function word_balloon_change_balloon_space_round(box) {

	if(word_balloon_get_avatar_name_position() === "under_avatar"){
		box["padding_bottom"] = box["name_margin"];
	}else if(word_balloon_get_avatar_name_position() === "under_balloon"){
		box["avatar_padding_bottom"] = box["name_margin"];
	}

	return box;

}


