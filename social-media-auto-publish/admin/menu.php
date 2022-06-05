<?php
if( !defined('ABSPATH') ){ exit();}
add_action('admin_menu', 'xyz_smap_menu');

function xyz_smap_add_admin_scripts()
{
	wp_enqueue_script('jquery');
	wp_register_script( 'xyz_notice_script_smap', plugins_url('social-media-auto-publish/js/notice.js') );
	wp_enqueue_script( 'xyz_notice_script_smap' );
	
	wp_register_style('xyz_smap_style', plugins_url('social-media-auto-publish/css/style.css'));
	wp_enqueue_style('xyz_smap_style');
	wp_register_style( 'xyz_smap_font_style',plugins_url('social-media-auto-publish/css/font-awesome.min.css'));
	wp_enqueue_style('xyz_smap_font_style');
}

add_action("admin_enqueue_scripts","xyz_smap_add_admin_scripts");


function xyz_smap_menu()
{
	add_menu_page('Social Media Auto Publish - Manage settings', 'Social Media Auto Publish', 'manage_options', 'social-media-auto-publish-settings', 'xyz_smap_settings',plugin_dir_url( XYZ_SMAP_PLUGIN_FILE ) . 'images/smap.png');
	$page=add_submenu_page('social-media-auto-publish-settings', 'Social Media Auto Publish - Manage settings', ' Settings', 'manage_options', 'social-media-auto-publish-settings' ,'xyz_smap_settings');
	if(get_option('xyz_smap_xyzscripts_hash_val')!=''&& get_option('xyz_smap_xyzscripts_user_id')!='')
		add_submenu_page('social-media-auto-publish-settings', 'Social Media Auto Publish - Manage Authorizations', 'Manage Authorizations', 'manage_options', 'social-media-auto-publish-manage-authorizations' ,'xyz_smap_manage_authorizations');
	add_submenu_page('social-media-auto-publish-settings', 'Social Media Auto Publish - Logs', 'Logs', 'manage_options', 'social-media-auto-publish-log' ,'xyz_smap_logs'); 
	add_submenu_page('social-media-auto-publish-settings', 'Social Media Auto Publish - About', 'About', 'manage_options', 'social-media-auto-publish-about' ,'xyz_smap_about');
	add_submenu_page('social-media-auto-publish-settings', 'Social Media Auto Publish - Suggest Feature', 'Suggest a Feature', 'manage_options', 'social-media-auto-publish-suggest-features' ,'xyz_smap_suggest_feature');
}


function xyz_smap_settings()
{
	$_POST = stripslashes_deep($_POST);
	$_GET = stripslashes_deep($_GET);	
	$_POST = xyz_trim_deep($_POST);
	$_GET = xyz_trim_deep($_GET);
	
	require( dirname( __FILE__ ) . '/header.php' );
	require( dirname( __FILE__ ) . '/settings.php' );
	require( dirname( __FILE__ ) . '/footer.php' );
}



function xyz_smap_about()
{
	require( dirname( __FILE__ ) . '/header.php' );
	require( dirname( __FILE__ ) . '/about.php' );
	require( dirname( __FILE__ ) . '/footer.php' );
}

function xyz_smap_logs()
{
	$_POST = stripslashes_deep($_POST);
	$_GET = stripslashes_deep($_GET);
	$_POST = xyz_trim_deep($_POST);
	$_GET = xyz_trim_deep($_GET);
	
	require( dirname( __FILE__ ) . '/header.php' );
	require( dirname( __FILE__ ) . '/logs.php' );
	require( dirname( __FILE__ ) . '/footer.php' );
}
function xyz_smap_suggest_feature()
{
	require( dirname( __FILE__ ) . '/header.php' );
	require( dirname( __FILE__ ) . '/suggest_feature.php' );
	require( dirname( __FILE__ ) . '/footer.php' );
}
function xyz_smap_manage_authorizations()
{
	require( dirname( __FILE__ ) . '/header.php' );
	require( dirname( __FILE__ ) . '/manage-auth.php' );
	require( dirname( __FILE__ ) . '/footer.php' );
}
add_action('wp_head', 'xyz_smap_insert_og_image_tag_for_fb');
function xyz_smap_insert_og_image_tag_for_fb(){

 	global $post;
 	if (empty($post))
 		$post=get_post();
 		if (!empty($post) && get_option('xyz_smap_free_enforce_og_tags')==1){
	$postid= $post->ID;
	$excerpt='';$attachmenturl='';$name='';
	if(isset($postid ) && $postid>0)
	{
		$xyz_smap_apply_filters=get_option('xyz_smap_std_apply_filters');
		$get_post_meta_insert_og=0;
		$get_post_meta_insert_og=get_post_meta($postid,"xyz_smap_insert_og",true); 
		if (($get_post_meta_insert_og==1)&&(strpos($_SERVER["HTTP_USER_AGENT"], "facebookexternalhit/") !== false || strpos($_SERVER["HTTP_USER_AGENT"], "Facebot") !== false || strpos($_SERVER["HTTP_USER_AGENT"], "LinkedInBot") !== false))
		{
			$ar2=explode(",",$xyz_smap_apply_filters);
			$excerpt = $post->post_excerpt;
			if(in_array(2, $ar2))
				$excerpt = apply_filters('the_excerpt', $excerpt);
				$excerpt = html_entity_decode($excerpt, ENT_QUOTES, get_bloginfo('charset'));
				$excerpt = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $excerpt);
				if($excerpt=="")
				{
					$content = $post->post_content;
					if(in_array(1, $ar2))
						$content = apply_filters('the_content', $content);
						if($content!="")
						{
							$content1=$content;
							$content1=strip_tags($content1);
							$content1=strip_shortcodes($content1);
							$content1 = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $content1);
							$content1=  preg_replace("/\\[caption.*?\\].*?\\[.caption\\]/is", "", $content1);
							$content1 = preg_replace('/\[.+?\]/', '', $content1);
							$excerpt=implode(' ', array_slice(explode(' ', $content1), 0, 50));
						}
				}
				else
				{
					$excerpt=strip_tags($excerpt);
					$excerpt=strip_shortcodes($excerpt);
				}
				$excerpt=str_replace("&nbsp;","",$excerpt);
				$name = $post->post_title;
				if(in_array(3, $ar2))
					$name = apply_filters('the_title', $name,$postid);
					$name = html_entity_decode($name, ENT_QUOTES, get_bloginfo('charset'));
					$name=strip_tags($name);
					$name=strip_shortcodes($name);
			$attachmenturl=xyz_smap_getimage($postid, $post->post_content);
			if(!empty( $name ))
				echo '<meta property="og:title" content="'.$name.'" />';
			if (!empty($excerpt))
				echo '<meta property="og:description" content="'.$excerpt.'" />';
			if(!empty($attachmenturl))
				echo '<meta property="og:image" content="'.$attachmenturl.'" />';
				update_post_meta($postid, "xyz_smap_insert_og", "0");
		}
	}
}
}
?>
