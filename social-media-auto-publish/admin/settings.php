<?php
if( !defined('ABSPATH') ){ exit();}
global $current_user;
$auth_varble=0;
wp_get_current_user();
$imgpath= plugins_url()."/social-media-auto-publish/images/";
$heimg=$imgpath."support.png";
$ms0="";
$ms1="";
$ms2="";
$ms3="";
$redirecturl=admin_url('admin.php?page=social-media-auto-publish-settings&auth=1');
$domain_name=$xyzscripts_hash_val=$xyz_smap_smapsoln_userid=$xyzscripts_user_id=$xyz_smap_licence_key='';

require( dirname( __FILE__ ) . '/authorization.php' );

if(!$_POST && isset($_GET['smap_notice']) && $_GET['smap_notice'] == 'hide')
{
	if (! isset( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'],'smap-shw')){
		wp_nonce_ays( 'smap-shw');
		exit;
	}
	update_option('xyz_smap_dnt_shw_notice', "hide");
	?>
<style type='text/css'>
#smap_notice_td
{
display:none !important;
}
</style>
<div class="system_notice_area_style1" id="system_notice_area">
Thanks again for using the plugin. We will never show the message again.
 &nbsp;&nbsp;&nbsp;<span
		id="system_notice_area_dismiss">Dismiss</span>
</div>

<?php
}
if(!$_POST && isset($_GET['ln_auth_err']) && $_GET['ln_auth_err'] != '')
{
	?>
<style type='text/css'>
#smap_notice_td
{
display:none !important;
}
</style>
<div class="system_notice_area_style0" id="system_notice_area">
<?php echo esc_html($_GET['ln_auth_err']);?>
 &nbsp;&nbsp;&nbsp;<span
		id="system_notice_area_dismiss" class="xyz_smap_hide_ln_authErr">Dismiss</span>
</div>

<?php
}

$erf=0;
if(isset($_POST['fb']))
{
	if (! isset( $_REQUEST['_wpnonce'] )|| ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'xyz_smap_fb_settings_form_nonce' ))
	{
		wp_nonce_ays( 'xyz_smap_fb_settings_form_nonce' );
		exit();
	}
	
	$ss=array();$appid='';$appsecret='';
	if(isset($_POST['smap_pages_list']))
	$ss=$_POST['smap_pages_list'];
	
	$smap_pages_list_ids="";


	if(!empty($ss))//$ss!="" && count($ss)>0
	{
		for($i=0;$i<count($ss);$i++)
		{
			$smap_pages_list_ids.=$ss[$i].",";
		}

	}
	else
		$smap_pages_list_ids.=-1;

	$smap_pages_list_ids=rtrim($smap_pages_list_ids,',');


	update_option('xyz_smap_pages_ids',$smap_pages_list_ids);



	$applidold=get_option('xyz_smap_application_id');
	$applsecretold=get_option('xyz_smap_application_secret');
	//$fbidold=get_option('xyz_smap_fb_id');
	$posting_method=intval($_POST['xyz_smap_po_method']);
	$posting_permission=intval($_POST['xyz_smap_post_permission']);
	$app_name=sanitize_text_field($_POST['xyz_smap_application_name']);
	$xyz_smap_app_sel_mode=intval($_POST['xyz_smap_app_sel_mode']);
	$xyz_smap_app_sel_mode_old=get_option('xyz_smap_app_sel_mode');
	if ($xyz_smap_app_sel_mode==0){
	$appid=sanitize_text_field($_POST['xyz_smap_application_id']);
	$appsecret=sanitize_text_field($_POST['xyz_smap_application_secret']);
	}
	$messagetopost=$_POST['xyz_smap_message'];
	$xyz_smap_clear_fb_cache=$_POST['xyz_smap_clear_fb_cache'];
	//$fbid=$_POST['xyz_smap_fb_id'];
	if($app_name=="" && $posting_permission==1)
	{
		$ms0="Please fill facebook application name.";
		$erf=1;
	}
	else if($appid=="" && $posting_permission==1 && $xyz_smap_app_sel_mode==0)
	{
		$ms1="Please fill facebook application id.";
		$erf=1;
	}
	elseif($appsecret=="" && $posting_permission==1 && $xyz_smap_app_sel_mode==0)
	{
		$ms2="Please fill facebook application secret.";
		$erf=1;
	}
	else
	{
		$erf=0;
		if(($appid!=$applidold || $appsecret!=$applsecretold) && $xyz_smap_app_sel_mode==0)
		{
			update_option('xyz_smap_af',1);
			update_option('xyz_smap_fb_token','');
		}	
		else if ($xyz_smap_app_sel_mode_old != $xyz_smap_app_sel_mode)
		{
			update_option('xyz_smap_af',1);
			update_option('xyz_smap_fb_token','');
			update_option('xyz_smap_page_names','');
		}
	/* 	if($messagetopost=="")
		{
			$messagetopost="New post added at {BLOG_TITLE} - {POST_TITLE}";
		} */
		update_option('xyz_smap_application_name',$app_name);
		if ($xyz_smap_app_sel_mode==0){
		update_option('xyz_smap_application_id',$appid);
		update_option('xyz_smap_application_secret',$appsecret);
		}
		update_option('xyz_smap_post_permission',$posting_permission);
		update_option('xyz_smap_app_sel_mode',$xyz_smap_app_sel_mode);
		
		update_option('xyz_smap_po_method',$posting_method);
		update_option('xyz_smap_message',$messagetopost);
		update_option('xyz_smap_clear_fb_cache', $xyz_smap_clear_fb_cache);


	}
}


$tms1="";
$tms2="";
$tms3="";
$tms4="";
$tms5="";
$tms6="";
$tredirecturl=admin_url('admin.php?page=social-media-auto-publish-settings&authtwit=1');


$terf=0;
if(isset($_POST['twit']))
{
	if (! isset( $_REQUEST['_wpnonce'] )|| ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'xyz_smap_tw_settings_form_nonce' ))
	{
		wp_nonce_ays( 'xyz_smap_tw_settings_form_nonce' );
		exit();
	}

	$tappid=sanitize_text_field($_POST['xyz_smap_twconsumer_id']);
	$tappsecret=sanitize_text_field($_POST['xyz_smap_twconsumer_secret']);
	$twid=sanitize_text_field($_POST['xyz_smap_tw_id']);
	$taccess_token=sanitize_text_field($_POST['xyz_smap_current_twappln_token']);
	$taccess_token_secret=sanitize_text_field($_POST['xyz_smap_twaccestok_secret']);
	$tposting_permission=intval($_POST['xyz_smap_twpost_permission']);
	$tposting_image_permission=intval($_POST['xyz_smap_twpost_image_permission']);
	$tmessagetopost=$_POST['xyz_smap_twmessage'];
	$xyz_smap_twtr_char_limit=$_POST['xyz_smap_twtr_char_limit'];
	$xyz_smap_twtr_char_limit=intval($xyz_smap_twtr_char_limit);
	if ($xyz_smap_twtr_char_limit<140 )
		$xyz_smap_twtr_char_limit=140;
	if($tappid=="" && $tposting_permission==1)
	{
		$terf=1;
		$tms1="Please fill api key.";

	}
	elseif($tappsecret=="" && $tposting_permission==1)
	{
		$tms2="Please fill api secret.";
		$terf=1;
	}
	elseif($twid=="" && $tposting_permission==1)
	{
		$tms3="Please fill twitter username.";
		$terf=1;
	}
	elseif($taccess_token=="" && $tposting_permission==1)
	{
		$tms4="Please fill twitter access token.";
		$terf=1;
	}
	elseif($taccess_token_secret=="" && $tposting_permission==1)
	{
		$tms5="Please fill twitter access token secret.";
		$terf=1;
	}
	elseif($tmessagetopost=="" && $tposting_permission==1)
	{
		$tms6="Please fill message format for posting.";
		$terf=1;
	}
	else
	{
		$terf=0;
		if($tmessagetopost=="")
		{
			$tmessagetopost="{POST_TITLE}-{PERMALINK}";
		}

		update_option('xyz_smap_twconsumer_id',$tappid);
		update_option('xyz_smap_twconsumer_secret',$tappsecret);
		update_option('xyz_smap_tw_id',$twid);
		update_option('xyz_smap_current_twappln_token',$taccess_token);
		update_option('xyz_smap_twaccestok_secret',$taccess_token_secret);
		update_option('xyz_smap_twmessage',$tmessagetopost);
		update_option('xyz_smap_twpost_permission',$tposting_permission);
		update_option('xyz_smap_twpost_image_permission',$tposting_image_permission);
		update_option('xyz_smap_twtr_char_limit', $xyz_smap_twtr_char_limit);
		
	}
}

$lms1="";
$lms2="";
$lms3=$lms4="";
$lerf=0;

if(isset($_POST['linkdn']))
{
	if (! isset( $_REQUEST['_wpnonce'] )|| ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'xyz_smap_ln_settings_form_nonce' ))
	{
		wp_nonce_ays( 'xyz_smap_ln_settings_form_nonce' );
		exit();
	}
	$lnaf=get_option('xyz_smap_lnaf');
	$posting_method=$_POST['xyz_smap_lnpost_method'];
	$lnappikeyold=get_option('xyz_smap_lnapikey');
	$lnapisecretold=get_option('xyz_smap_lnapisecret');
	$xyz_smap_ln_api_permissionold = get_option('xyz_smap_ln_api_permission');
	$lnappikey=sanitize_text_field($_POST['xyz_smap_lnapikey']);
	$lnapisecret=sanitize_text_field($_POST['xyz_smap_lnapisecret']);
	
	$lmessagetopost=trim($_POST['xyz_smap_lnmessage']);
	
	$lnposting_permission=intval($_POST['xyz_smap_lnpost_permission']);
	$xyz_smap_lnshare_to_profile=get_option('xyz_smap_lnshare_to_profile');
	if (isset($_POST['xyz_smap_lnshare_to_profile']))
	$xyz_smap_lnshare_to_profile=intval($_POST['xyz_smap_lnshare_to_profile']);
	$xyz_smap_ln_shareprivate=intval($_POST['xyz_smap_ln_shareprivate']);
// 	$xyz_smap_ln_sharingmethod=intval($_POST['xyz_smap_ln_sharingmethod']);
	$xyz_smap_ln_api_permission=intval($_POST['xyz_smap_ln_api_permission']);
	$xyz_smap_ln_share_post_company=array();
	if(isset($_POST['xyz_smap_ln_share_post_company']))
		$xyz_smap_ln_share_post_company=$_POST['xyz_smap_ln_share_post_company'];
		$xyz_smap_ln_company_ids='';
		if(!empty($xyz_smap_ln_share_post_company))//count($xyz_smap_ln_share_post_company)>0
		{
			for($i=0;$i<count($xyz_smap_ln_share_post_company);$i++)
			{
				if($xyz_smap_ln_share_post_company[$i] !=''){
					$xyz_smap_ln_share_post_company_ids_and_names=explode('-',$xyz_smap_ln_share_post_company[$i] );
					$xyz_smap_ln_company_ids.=$xyz_smap_ln_share_post_company_ids_and_names[0].',';
				}
				
			}
			$xyz_smap_ln_company_ids=rtrim($xyz_smap_ln_company_ids,',');
		}
		if ($xyz_smap_ln_api_permission==2)
		$xyz_smap_ln_company_ids=get_option('xyz_smap_ln_company_ids');
	if($lnappikey=="" && $lnposting_permission==1 && $xyz_smap_ln_api_permission!=2)
	{
		$lms1="Please fill linkedin api key";
		$lerf=1;
	}
	elseif($lnapisecret=="" && $lnposting_permission==1  && $xyz_smap_ln_api_permission!=2)
	{
		$lms2="Please fill linked api secret";
		$lerf=1;
	}
	elseif($lnaf==0 && $xyz_smap_ln_company_ids=="" && $xyz_smap_lnshare_to_profile!=1 && $lnposting_permission==1)
	{
		$lms3="Please select share post to profile or company page ";
		$lerf=1;
	}
	elseif($lnaf==0 && $lmessagetopost=='' && $posting_method==1)
	{
		$lms4="Please fill message format for posting ";
		$lerf=1;
	}
	else
	{
		$lerf=0;
		if($lnappikey!=$lnappikeyold || $lnapisecret!=$lnapisecretold || $xyz_smap_ln_api_permission!=$xyz_smap_ln_api_permissionold)
		{
			update_option('xyz_smap_lnaf',1);
		}

		update_option('xyz_smap_lnapikey',$lnappikey);
		update_option('xyz_smap_lnapisecret',$lnapisecret);
		update_option('xyz_smap_lnpost_permission',$lnposting_permission);
		update_option('xyz_smap_lnpost_method', $posting_method);
		update_option('xyz_smap_ln_shareprivate',$xyz_smap_ln_shareprivate);
// 		update_option('xyz_smap_ln_sharingmethod',$xyz_smap_ln_sharingmethod);
		update_option('xyz_smap_lnmessage',$lmessagetopost);
		update_option('xyz_smap_ln_company_ids', $xyz_smap_ln_company_ids);
		update_option('xyz_smap_lnshare_to_profile', $xyz_smap_lnshare_to_profile);
		update_option('xyz_smap_ln_api_permission', $xyz_smap_ln_api_permission);
	}	
}

if((isset($_POST['twit']) && $terf==0) || (isset($_POST['fb']) && $erf==0) || (isset($_POST['linkdn']) && $lerf==0))
{
	?>

<div class="system_notice_area_style1" id="system_notice_area">
	Settings updated successfully. &nbsp;&nbsp;&nbsp;<span
		id="system_notice_area_dismiss">Dismiss</span>
</div>
<?php }
if(isset($_GET['msg']) && $_GET['msg']==1)
{
?>
<div class="system_notice_area_style0" id="system_notice_area">
	Unable to authorize the linkedin application. Please check the details. &nbsp;&nbsp;&nbsp;<span
		id="system_notice_area_dismiss">Dismiss</span>
</div>
	<?php 
}
if(isset($_GET['msg']) && $_GET['msg']==2)
{
	?>
<div class="system_notice_area_style0" id="system_notice_area">
The state does not match. You may be a victim of CSRF. &nbsp;&nbsp;&nbsp;<span
		id="system_notice_area_dismiss">Dismiss</span>
</div>
	
<?php 
}
if(isset($_GET['msg']) && $_GET['msg']==3) //response['body'] not set
{
?>

<div class="system_notice_area_style0" id="system_notice_area">
Unable to authorize the facebook application. Please check your curl/fopen and firewall settings. &nbsp;&nbsp;&nbsp;<span
		id="system_notice_area_dismiss">Dismiss</span>
</div>
<?php
}if(isset($_GET['msg']) && $_GET['msg'] == 4){
?>
<div class="system_notice_area_style1" id="system_notice_area">
Account has been authenticated successfully.&nbsp;&nbsp;&nbsp;<span
id="system_notice_area_dismiss">Dismiss</span>
</div>
<?php 	
}
if(isset($_GET['msg']) && $_GET['msg']==5)
{
	?>
<div class="system_notice_area_style1" id="system_notice_area">
	Successfully connected to xyzscripts member area. &nbsp;&nbsp;&nbsp;<span
		id="system_notice_area_dismiss">Dismiss</span>
</div>
	<?php 
}
if(isset($_GET['msg']) && ($_GET['msg']==6|| $_GET['msg']==7))
{
	?>
<div class="system_notice_area_style1" id="system_notice_area">
Selected pages saved successfully. &nbsp;&nbsp;&nbsp;<span
id="system_notice_area_dismiss">Dismiss</span>
</div>
<?php 	
}
if((isset($_POST['twit']) && $terf==1)|| (isset($_POST['fb']) && $erf==1) || (isset($_POST['linkdn']) && $lerf==1))
{
	?>
<div class="system_notice_area_style0" id="system_notice_area">
	<?php 
	if(isset($_POST['fb']))
	{
		echo esc_html($ms0);echo esc_html($ms1);echo esc_html($ms2);
	}
	else if(isset($_POST['twit']))
	{
		echo esc_html($tms1);echo esc_html($tms2);echo esc_html($tms3);echo esc_html($tms4);echo esc_html($tms5);echo esc_html($tms6);
	}
	else if(isset($_POST['linkdn']))
	{
		echo esc_html($lms1);echo esc_html($lms2);echo esc_html($lms3);echo esc_html($lms4);
	}
	?>
	&nbsp;&nbsp;&nbsp;<span id="system_notice_area_dismiss">Dismiss</span>
</div>
<?php } ?>
<script type="text/javascript">
function detdisplay_smap(id)
{
	document.getElementById(id).style.display='';
}
function dethide_smap(id)
{
	document.getElementById(id).style.display='none';
}

/*function drpdisplay()
{
	var shmethod= document.getElementById('xyz_smap_ln_sharingmethod').value;
	if(shmethod==1)	
	{
		document.getElementById('shareprivate').style.display="none";
	}
	else
	{
		document.getElementById('shareprivate').style.display="";
	}
}*/
</script>

<div style="width: 100%">
<div class="xyz_smap_tab">
  <button class="xyz_smap_tablinks" onclick="xyz_smap_open_tab(event, 'xyz_smap_facebook_settings')" id="xyz_smap_default_fbtab_settings">Facebook Settings</button>
   <button class="xyz_smap_tablinks" onclick="xyz_smap_open_tab(event, 'xyz_smap_twitter_settings')" id="xyz_smap_default_twtab_settings">Twitter Settings</button>
   <button class="xyz_smap_tablinks" onclick="xyz_smap_open_tab(event, 'xyz_smap_linkedin_settings')" id="xyz_smap_default_lntab_settings">LinkedIn Settings</button>
   <button class="xyz_smap_tablinks" onclick="xyz_smap_open_tab(event, 'xyz_smap_basic_settings')" id="xyz_smap_basic_tab_settings">General Settings</button>
</div>
<div id="xyz_smap_facebook_settings" class="xyz_smap_tabcontent">
	<?php
	$af=get_option('xyz_smap_af');
	$appid=get_option('xyz_smap_application_id');
	$appsecret=get_option('xyz_smap_application_secret');
	//$fbid=get_option('xyz_smap_fb_id');
	$posting_method=get_option('xyz_smap_po_method');
	$posting_message=get_option('xyz_smap_message');
	$xyz_smap_app_sel_mode=get_option('xyz_smap_app_sel_mode');
	if($xyz_smap_app_sel_mode==0)
	{
	if($af==1 && $appid!="" && $appsecret!="")
	{
		?>
			<span style="color: red;" id="auth_message" >Application needs authorisation</span> <br>
	<form method="post">
	<?php wp_nonce_field( 'xyz_smap_fb_auth_form_nonce' );?>

		<input type="submit" class="submit_smap_new" name="fb_auth"
			value="Authorize" /><br><br>

	</form>
	<?php }
			else if($af==0 && $appid!="" && $appsecret!="")
	{
		?>
	<form method="post">
	<?php wp_nonce_field( 'xyz_smap_fb_auth_form_nonce' );?>
	<input type="submit" class="submit_smap_new" name="fb_auth"
	value="Reauthorize" title="Reauthorize the account" /><br><br>
	
	</form>
	<?php }
		}
		elseif ($xyz_smap_app_sel_mode==1){
	 		$domain_name=trim(get_option('siteurl'));
	 		$xyz_smap_smapsoln_userid=intval(trim(get_option('xyz_smap_smapsoln_userid')));
	 		$xyzscripts_hash_val=trim(get_option('xyz_smap_xyzscripts_hash_val'));
	 		$xyzscripts_user_id=trim(get_option('xyz_smap_xyzscripts_user_id'));
	 		$xyz_smap_accountId=0;
	 		$xyz_smap_licence_key='';
	 		$request_hash=md5($xyzscripts_user_id.$xyzscripts_hash_val);
	 		$auth_secret_key=md5('smapsolutions'.$domain_name.$xyz_smap_accountId.$xyz_smap_smapsoln_userid.$xyzscripts_user_id.$request_hash.$xyz_smap_licence_key.'smap');
			if($af==1 )
			{
				?>
	 			<span id='ajax-save' style="display:none;"><img	class="img"  title="Saving details"	src="<?php echo plugins_url('../images/ajax-loader.gif',__FILE__);?>" style="width:65px;height:70px; "></span>
		 			<span id="auth_message">
		 				<span style="color: red;" >Application needs authorisation</span> <br>
		 				<form method="post">
		 			     <?php wp_nonce_field( 'xyz_smap_fb_auth_form_nonce' );?>
		 			     <input type="hidden" value="<?php echo  (is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST']; ?>" id="parent_domain">
		 					<input type="submit" class="submit_smap_new" name="fb_auth"
	 						value="Authorize" onclick="javascript:return smap_popup_fb_auth('<?php echo urlencode($domain_name);?>','<?php echo $xyz_smap_smapsoln_userid;?>','<?php echo $xyzscripts_user_id;?>','<?php echo $xyzscripts_hash_val;?>','<?php echo $auth_secret_key;?>','<?php echo $request_hash;?>');void(0);"/><br><br>
	 				</form></span>
		 				<?php }
		 				else if($af==0 )
		 				{
		 					?>
	 					<span id='ajax-save' style="display:none;"><img	class="img"  title="Saving details"	src="<?php echo plugins_url('../images/ajax-loader.gif',__FILE__);?>" style="width:65px;height:70px; "></span>
		 				<form method="post" id="re_auth_message">
		 				<?php wp_nonce_field( 'xyz_smap_fb_auth_form_nonce' );?>
		 				<input type="hidden" value="<?php echo  (is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST']; ?>" id="parent_domain">
		 				<input type="submit" class="submit_smap_new" name="fb_auth"
	 				value="Reauthorize" title="Reauthorize the account" onclick="javascript:return smap_popup_fb_auth('<?php echo urlencode($domain_name);?>','<?php echo $xyz_smap_smapsoln_userid;?>','<?php echo $xyzscripts_user_id;?>','<?php echo $xyzscripts_hash_val;?>','<?php echo $auth_secret_key;?>','<?php echo $request_hash;?>');void(0);"/><br><br>
		 				</form>
		 				<?php }
		 	}


	if(isset($_GET['auth']) && $_GET['auth']==1 && get_option("xyz_smap_fb_token")!="")
	{
		?>

	<span style="color: green;">Application is authorized, go posting.
	</span><br>

	<?php 	
	}
	?>

	
	<table class="widefat" style="width: 99%;background-color: #FFFBCC" id= "xyz_smap_app_creation_note">
	<tr>
	<td id="bottomBorderNone" style="border: 1px solid #FCC328;">
	
	<div>


		<b>Note :</b> You have to create a Facebook application before filling the following details.
		<b><a href="https://developers.facebook.com/apps" target="_blank">Click here</a></b> to create new Facebook application. 
	<br>In the application page in facebook, navigate to <b>Apps >Add Product > Facebook Login >Quickstart >Web > Site URL</b>. Set the site url as : 
		<span style="color: red;"><?php echo  (is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST']; ?></span>
		<br>And then navigate to <b>Apps > Facebook Login > Settings</b>. Set the Valid OAuth redirect URIs as :<br>
		<span style="color: red;"> <?php echo admin_url('admin.php?page=social-media-auto-publish-settings&auth=1'); ?> </span>
	   	 <br/>For detailed step by step instructions <b><a href="http://help.xyzscripts.com/docs/social-media-auto-publish/faq/how-can-i-create-facebook-application/" target="_blank">Click here</a></b>.
	</div>

	</td>
	</tr>
	</table>
	
	<form method="post">
	<?php wp_nonce_field( 'xyz_smap_fb_settings_form_nonce' );?>
		<input type="hidden" value="config">





			<div style="font-weight: bold;padding: 3px;">All fields given below are mandatory</div> 
			<table class="widefat xyz_smap_widefat_table" style="width: 99%">
			<tr valign="top">
					<td>Enable auto publish post to my facebook account
					</td>
					<td  class="switch-field">
						<label id="xyz_smap_post_permission_yes"><input type="radio" name="xyz_smap_post_permission" value="1" <?php  if(get_option('xyz_smap_post_permission')==1) echo 'checked';?>/>Yes</label>
						<label id="xyz_smap_post_permission_no"><input type="radio" name="xyz_smap_post_permission" value="0" <?php  if(get_option('xyz_smap_post_permission')==0) echo 'checked';?>/>No</label>
					</td>
				</tr>
			<tr valign="top">
					<td width="50%">Application name
					<br/><span style="color: #0073aa;">[This is for tracking purpose]</span>
					</td>
					<td><input id="xyz_smap_application_name"
						name="xyz_smap_application_name" type="text"
						value="<?php if($ms0=="") {echo esc_html(get_option('xyz_smap_application_name'));}?>" />
					</td>
				</tr>
				<tr valign="top">
			<td width="50%">Application Selection
			</td>
				<td>
				<input type="radio" name="xyz_smap_app_sel_mode" id="xyz_smap_app_sel_mode_reviewd" value="0" <?php if($xyz_smap_app_sel_mode==0) echo 'checked';?>>
				<span style="color: #a7a7a7;font-weight: bold;">Own App ( requires app submission and Facebook review -<a href="http://help.xyzscripts.com/docs/social-media-auto-publish/faq/how-can-i-create-facebook-application/" style="color: #a7a7a7;text-decoration: underline; " target="_blank" >Help</a>)</span>
				<br>
				<div class="xyz_smap_facebook_settings" style="display: none;" onmouseover="detdisplay_smap('xyz_smap_app_review')" onmouseout="dethide_smap('xyz_smap_app_review')"><span style="padding-left: 25px;color: #0073aa;">App approval service available for 50 USD
				</span><br/>
				<div id="xyz_smap_app_review" class="smap_informationdiv" style="display: none;width: 400px;">
				<b>Expected time frame:</b><br/>30 days<br/>
				<b>Required details:</b><br/>1. WordPress login<br/>
				2. Admin access to Facebook developer app for review submission (temporary).<br/>
				For more details contact <a href="https://xyzscripts.com/support/" target="_blank" >Support Desk</a> .
				</div>
				</div><br/>
				<input type="radio" name="xyz_smap_app_sel_mode" id="xyz_smap_app_sel_mode_xyzapp" value="1" <?php if($xyz_smap_app_sel_mode==1) echo 'checked';?>>
				<span style="color: #000000;font-size: 13px;background-color: #f7a676;font-weight: 500;padding: 3px 5px;"><i class="fa fa-star-o" aria-hidden="true" style="margin-right:5px;"></i>SMAPsolution.com's App ( ready to publish )<i class="fa fa-star-o" aria-hidden="true" style="margin-right:5px;"></i></span><br> <span style="padding-left: 30px;">Starts from 10 USD per year</span><br>
				<?php if(get_option('xyz_smap_smapsoln_userid')==0)
				{?>
				<span style="color: #ff5e00;padding-left: 27px;font-size: small;"><b>30 DAYS FREE TRIAL AVAILABLE*</b></span>
				<br/>
				<?php }?>
				<a target="_blank" href="https://help.xyzscripts.com/docs/social-media-auto-publish/faq/how-can-i-use-the-alternate-solution-for-publishing-posts-to-facebook/" style="padding-left: 30px;">How to use smapsolution.com's application?</a>
				</td>
			</tr>
						<?php 
			if( ($xyzscripts_user_id =='' || $xyzscripts_hash_val=='') && $xyz_smap_app_sel_mode==1)
			{  ?>
			<tr valign="top" id="xyz_smap_conn_to_xyzscripts">
			<td width="50%">	</td>
			<td width="50%">
			<span id='ajax-save-xyzscript_acc' style="display:none;"><img	class="img"  title="Saving details"	src="<?php echo plugins_url('../images/ajax-loader.gif',__FILE__);?>" style="width:65px;height:70px; "></span>
			<span id="connect_to_xyzscripts"style="background-color: #1A87B9;color: white; padding: 4px 5px;
    text-align: center; text-decoration: none;   display: inline-block;border-radius: 4px;">
			<a href="javascript:smap_popup_connect_to_xyzscripts();void(0);" style="color:white !important;">Connect your xyzscripts account</a>
			</span>
			</td>
			</tr>
			<?php }?>
				<tr valign="top" class="xyz_smap_facebook_settings">
					<td width="50%">Application id
					</td>
					<td><input id="xyz_smap_application_id"
						name="xyz_smap_application_id" type="text"
						value="<?php if($ms1=="") {echo esc_html(get_option('xyz_smap_application_id'));}?>" />
						</td>
				</tr>

				<tr valign="top" class="xyz_smap_facebook_settings">
					<td>Application secret<?php   $apsecret=get_option('xyz_smap_application_secret');?>
						
					</td>
					<td><input id="xyz_smap_application_secret"
						name="xyz_smap_application_secret" type="text"
						value="<?php if($ms2=="") {echo esc_html($apsecret); }?>" />
					</td>
				</tr>
				<tr valign="top">
					<td>Posting method
					<br/><span style="color: #0073aa;">[Create app album(with <b>Application name</b>) in the Facebook pages,<br/>if you are using the posting method <b>Upload image to app album</b>]</span>
					</td>
					<td>
					<select id="xyz_smap_po_method" name="xyz_smap_po_method">
							<option value="3"
				<?php  if(get_option('xyz_smap_po_method')==3) echo 'selected';?>>Simple text message</option>
				
				<optgroup label="Text message with image">
					<option value="4"
					<?php  if(get_option('xyz_smap_po_method')==4) echo 'selected';?>>Upload image to app album</option>
					<option value="5"
					<?php  if(get_option('xyz_smap_po_method')==5) echo 'selected';?>>Upload image to timeline album</option>
				</optgroup>
				
				<optgroup label="Text message with attached link">
					<option value="1"
					<?php  if(get_option('xyz_smap_po_method')==1) echo 'selected';?>>Attach
						your blog post</option>
					<option value="2"
					<?php  if(get_option('xyz_smap_po_method')==2) echo 'selected';?>>
						Share a link to your blog post</option>
					</optgroup>
					</select>
					</td>
				</tr>
				<tr valign="top">
					<td>Message format for posting <img src="<?php echo $heimg?>"
						onmouseover="detdisplay_smap('xyz_fb')" onmouseout="dethide_smap('xyz_fb')" style="width:13px;height:auto;">
						<div id="xyz_fb" class="smap_informationdiv" style="display: none;">
							{POST_TITLE} - Insert the title of your post.<br />{PERMALINK} -
							Insert the URL where your post is displayed.<br />{POST_EXCERPT}
							- Insert the excerpt of your post.<br />{POST_CONTENT} - Insert
							the description of your post.<br />{BLOG_TITLE} - Insert the name
							of your blog.<br />{USER_NICENAME} - Insert the nicename
							of the author.<br />{POST_ID} - Insert the ID of your post.
							<br />{POST_PUBLISH_DATE} - Insert the publish date of your post.
							<br />{USER_DISPLAY_NAME} - Insert the display name of the author.
						</div><br/><span style="color: #0073aa;">[Optional in the case of <b>Text message with attached link</b><br/> or <b>Text message with image</b> posting methods]</span></td>
	<td>
	<select name="xyz_smap_fb_info" id="xyz_smap_fb_info" onchange="xyz_smap_fb_info_insert(this)">
		<option value ="0" selected="selected">--Select--</option>
		<option value ="1">{POST_TITLE}  </option>
		<option value ="2">{PERMALINK} </option>
		<option value ="3">{POST_EXCERPT}  </option>
		<option value ="4">{POST_CONTENT}   </option>
		<option value ="5">{BLOG_TITLE}   </option>
		<option value ="6">{USER_NICENAME}   </option>
		<option value ="7">{POST_ID}   </option>
		<option value ="8">{POST_PUBLISH_DATE}   </option>
		<option value ="9">{USER_DISPLAY_NAME}   </option>
		</select> </td></tr><tr><td>&nbsp;</td><td>
		<textarea id="xyz_smap_message"  name="xyz_smap_message" style="height:80px !important;" ><?php 
								echo esc_textarea(get_option('xyz_smap_message'));?></textarea>
	</td></tr>
	
	
	<tr valign="top">
					<td>Clear facebook cache before publishing to facebook
					</td>
					<td  class="switch-field">
						<label id="xyz_smap_clear_fb_cache_yes"><input type="radio" name="xyz_smap_clear_fb_cache" value="1" <?php  if(get_option('xyz_smap_clear_fb_cache')==1) echo 'checked';?>/>Yes</label>
						<label id="xyz_smap_clear_fb_cache_no"><input type="radio" name="xyz_smap_clear_fb_cache" value="0" <?php  if(get_option('xyz_smap_clear_fb_cache')==0) echo 'checked';?>/>No</label>
					</td>
				</tr>
	

				<?php 

				$xyz_acces_token=get_option('xyz_smap_fb_token');
				if($xyz_acces_token!="" && $xyz_smap_app_sel_mode==0 ){
				
					$offset=0;$limit=100;$data=array();
					//$fbid=get_option('xyz_smap_fb_id');
					do
					{
						$result1="";$pagearray1="";
						$pp=wp_remote_get("https://graph.facebook.com/".XYZ_SMAP_FB_API_VERSION."/me/accounts?access_token=$xyz_acces_token&limit=$limit&offset=$offset",array('sslverify'=> (get_option('xyz_smap_peer_verification')=='1') ? true : false));
						if(is_array($pp))
						{
							$result1=$pp['body'];
							$pagearray1 = json_decode($result1);
							if(is_array($pagearray1->data))
								$data = array_merge($data, $pagearray1->data);
						}
						else
							break;
							$offset += $limit;
							// 						if(!is_array($pagearray1->paging))
								// 							break;
								// 					}while(array_key_exists("next", $pagearray1->paging));
					}while(isset($pagearray1->paging->next));
				
				
					$count=0;
					if (!empty($data))
					$count=count($data);
						
					$smap_pages_ids1=get_option('xyz_smap_pages_ids');
					$smap_pages_ids0=array();
					if($smap_pages_ids1!="")
						$smap_pages_ids0=explode(",",$smap_pages_ids1);
				
						$smap_pages_ids=array();
						if (!empty($smap_pages_ids0)){
						for($i=0;$i<count($smap_pages_ids0);$i++)
						{
							if($smap_pages_ids0[$i]!="-1")
								$smap_pages_ids[$i]=trim(substr($smap_pages_ids0[$i],0,strpos($smap_pages_ids0[$i],"-")));
								else
									$smap_pages_ids[$i]=$smap_pages_ids0[$i];
						}}
				
						//$data[$i]->id."-".$data[$i]->access_token
						?>
				
			<tr valign="top"><td>
					Select facebook pages for auto publish 
				</td>
				<td>
				
				<div class="scroll_checkbox">
				<input type="checkbox" id="select_all_pages" >Select All
				<br>
			
				<?php 
				for($i=0;$i<$count;$i++)
				{
			          $pgid=$data[$i]->id;
					$page_name[$pgid]=$data[$i]->name;
				?>
				<input type="checkbox" class="selpages" name="smap_pages_list[]"  value="<?php  echo $data[$i]->id."-".$data[$i]->access_token;?>" <?php if(in_array($data[$i]->id, $smap_pages_ids)) echo "checked" ?>><?php echo $data[$i]->name; ?>
				<br><?php }
				//	$page_name=base64_encode(serialize($page_name));?>
			<!--  	<input type="hidden" value="<?php //echo $page_name;?>" name="hidden_page_name" >-->
				</div>
				</td></tr>
			<?php 
			}
			elseif ($xyz_smap_app_sel_mode==1 && $af==0)// &&pagelist frm smap solutions is not empty )
			{
				$xyz_smap_page_names=stripslashes(get_option('xyz_smap_page_names'));
				?>
							<tr id="xyz_smap_selected_pages_tr" style="<?php if($xyz_smap_page_names=='')echo "display:none;";?>">
							<td>Selected facebook pages for auto publish</td>
							<td>
							<div>
							<div class="scroll_checkbox" id="xyz_smap_selected_pages" style="float: left;" >
							<?php
							if($xyz_smap_page_names!=''){
								$xyz_smap_page_names_array=json_decode($xyz_smap_page_names);
								foreach ($xyz_smap_page_names_array as $sel_pageid=>$sel_pagename)
								{
								?>
							 <input type="checkbox" class="selpages" name="smap_pages_list[]"  value="<?php echo $sel_pageid;?>" disabled checked="checked"><?php echo $sel_pagename; ?><br>
								<?php }}
							?>
							</div>
							<div style="float: left;width: 10px;color: #ce5c19;font-size: 20px;">*</div>
							</div>
							</td>
							</tr>
					<?php }
			?>
				<tr><td   id="bottomBorderNone"></td>
					<td  id="bottomBorderNone"><div style="height: 50px;">
							<input type="submit" class="submit_smap_new"
								style=" margin-top: 10px; "
								name="fb" value="Save" /></div>
					</td>
				</tr>
				<?php if(get_option('xyz_smap_smapsoln_userid')==0){?>
				<tr><td style='color: #ce5c19;padding-left:0px;'>*Free trial is available only for first time users</td></tr>
				<?php }
				else{?>
				<tr><td style='color: #ce5c19;padding-left:0px;'>*Use reauthorize button to change selected values</td></tr>
				<?php }?>
			</table>

	</form>
</div>
<div id="xyz_smap_twitter_settings" class="xyz_smap_tabcontent">

	<?php



	$tappid=get_option('xyz_smap_twconsumer_id');
	$tappsecret=get_option('xyz_smap_twconsumer_secret');
	$twid=get_option('xyz_smap_tw_id');
	$taccess_token=get_option('xyz_smap_current_twappln_token');
	//$posting_method=get_option('xyz_smap_po_method');
	//$posting_message=get_option('xyz_smap_twmessage');



	?>


<table class="widefat" style="width: 99%;background-color: #FFFBCC">
<tr>
<td id="bottomBorderNone" style="border: 1px solid #FCC328;">
	<div>
		<b>Note :</b> You have to create a Twitter application before filling in following fields. 	
		<br><b><a href="https://developer.twitter.com/en/apps/create" target="_blank">Click here</a></b> to create new application. Specify the website for the application as :	<span style="color: red;"><?php echo  (is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST']; ?>		 </span> 
		 <br>In the twitter application, navigate to	<b>Settings > Application Type > Access</b>. Select <b>Read and Write</b> option. 
		 <br>After updating access, navigate to <b>Details > Your access token</b> in the application and	click <b>Create my access token</b> button.
		<br>For detailed step by step instructions <b><a href="http://help.xyzscripts.com/docs/social-media-auto-publish/faq/how-can-i-create-twitter-application/" target="_blank">Click here</a></b>.

	</div>
</td>
</tr>
</table>


	<form method="post">
		<?php wp_nonce_field( 'xyz_smap_tw_settings_form_nonce' );?>
		<input type="hidden" value="config">



			<div style="font-weight: bold;padding: 3px;">All fields given below are mandatory</div> 
			<table class="widefat xyz_smap_widefat_table" style="width: 99%">
			<tr valign="top">
				<td>Enable auto publish	posts to my twitter account	</td>
				<td  class="switch-field">
				<label id="xyz_smap_twpost_permission_yes"><input type="radio" name="xyz_smap_twpost_permission" value="1" <?php  if(get_option('xyz_smap_twpost_permission')==1) echo 'checked';?>/>Yes</label>
				<label id="xyz_smap_twpost_permission_no"><input type="radio" name="xyz_smap_twpost_permission" value="0" <?php  if(get_option('xyz_smap_twpost_permission')==0) echo 'checked';?>/>No</label>
				</td>
				</tr>
				
				<tr valign="top">
					<td width="50%">API key
					</td>
					<td><input id="xyz_smap_twconsumer_id"
						name="xyz_smap_twconsumer_id" type="text"
						value="<?php if($tms1=="") {echo esc_html(get_option('xyz_smap_twconsumer_id'));}?>" />
						<a href="http://help.xyzscripts.com/docs/social-media-auto-publish/faq/how-can-i-create-twitter-application/" target="_blank">How can I create a Twitter Application?</a>
					</td>
				</tr>

				<tr valign="top">
					<td>API secret
					</td>
					<td><input id="xyz_smap_twconsumer_secret"
						name="xyz_smap_twconsumer_secret" type="text"
						value="<?php if($tms2=="") { echo esc_html(get_option('xyz_smap_twconsumer_secret')); }?>" />
					</td>
				</tr>
				<tr valign="top">
					<td>Twitter username
					</td>
					<td><input id="xyz_smap_tw_id" class="al2tw_text"
						name="xyz_smap_tw_id" type="text"
						value="<?php if($tms3=="") {echo esc_html(get_option('xyz_smap_tw_id'));}?>" />
					</td>
				</tr>
				<tr valign="top">
					<td>Access token
					</td>
					<td><input id="xyz_smap_current_twappln_token" class="al2tw_text"
						name="xyz_smap_current_twappln_token" type="text"
						value="<?php if($tms4=="") {echo esc_html(get_option('xyz_smap_current_twappln_token'));}?>" />
					</td>
				</tr>
				<tr valign="top">
					<td>Access	token secret
					</td>
					<td><input id="xyz_smap_twaccestok_secret" class="al2tw_text"
						name="xyz_smap_twaccestok_secret" type="text"
						value="<?php if($tms5=="") {echo esc_html(get_option('xyz_smap_twaccestok_secret'));}?>" />
					</td>
				</tr>
				<tr valign="top">
					<td>Message format for posting <img src="<?php echo $heimg?>"
						onmouseover="detdisplay_smap('xyz_tw')" onmouseout="dethide_smap('xyz_tw')" style="width:13px;height:auto;">
						<div id="xyz_tw" class="smap_informationdiv"
							style="display: none; font-weight: normal;">
							{POST_TITLE} - Insert the title of your post.<br />{PERMALINK} -
							Insert the URL where your post is displayed.<br />{POST_EXCERPT}
							- Insert the excerpt of your post.<br />{POST_CONTENT} - Insert
							the description of your post.<br />{BLOG_TITLE} - Insert the name
							of your blog.<br />{USER_NICENAME} - Insert the nicename
							of the author.<br />{POST_ID} - Insert the ID of your post.
							<br />{POST_PUBLISH_DATE} - Insert the publish date of your post.
							<br />{USER_DISPLAY_NAME} - Insert the display name of the author.
						</div></td>
	<td>
	<select name="xyz_smap_tw_info" id="xyz_smap_tw_info" onchange="xyz_smap_tw_info_insert(this)">
		<option value ="0" selected="selected">--Select--</option>
		<option value ="1">{POST_TITLE}  </option>
		<option value ="2">{PERMALINK} </option>
		<option value ="3">{POST_EXCERPT}  </option>
		<option value ="4">{POST_CONTENT}   </option>
		<option value ="5">{BLOG_TITLE}   </option>
		<option value ="6">{USER_NICENAME}   </option>
		<option value ="7">{POST_ID}   </option>
		<option value ="8">{POST_PUBLISH_DATE}   </option>
		<option value ="9">{USER_DISPLAY_NAME}   </option>
		</select> </td></tr><tr><td>&nbsp;</td><td>
		<textarea id="xyz_smap_twmessage"  name="xyz_smap_twmessage" style="height:80px !important;" ><?php if($tms6=="") {
								echo esc_textarea(get_option('xyz_smap_twmessage'));}?></textarea>
	</td></tr>
				<tr valign="top">
					<td>Attach image to twitter post
					</td>
					<td  class="switch-field">
						<label id="xyz_smap_twpost_image_permission_yes"><input type="radio" name="xyz_smap_twpost_image_permission" value="1" <?php  if(get_option('xyz_smap_twpost_image_permission')==1) echo 'checked';?>/>Yes</label>
						<label id="xyz_smap_twpost_image_permission_no"><input type="radio" name="xyz_smap_twpost_image_permission" value="0" <?php  if(get_option('xyz_smap_twpost_image_permission')==0) echo 'checked';?>/>No</label>
					</td>
				</tr>
				
				<tr valign="top">
	<td>Twitter character limit  <img src="<?php echo $heimg?>"
							onmouseover="detdisplay_smap('xyz_smap_tw_char_limit')" onmouseout="dethide_smap('xyz_smap_tw_char_limit')" style="width:13px;height:auto;">
							<div id="xyz_smap_tw_char_limit" class="smap_informationdiv" style="display: none;">
							The character limit of tweets  is 280.<br/>
							Use 140 for languages like Chinese, Japanese and Korean<br/> which won't get the 280 character length limit.<br />
							</div></td>
	<td>
	<input id="xyz_smap_twtr_char_limit"  name="xyz_smap_twtr_char_limit" type="text" value="<?php echo get_option('xyz_smap_twtr_char_limit');?>" style="width: 200px">
	</td></tr>
				
				<tr>
			<td   id="bottomBorderNone"></td>
					<td   id="bottomBorderNone"><div style="height: 50px;">
							<input type="submit" class="submit_smap_new"
								style=" margin-top: 10px; "
								name="twit" value="Save" /></div>
					</td>
				</tr>
			</table>

	</form>
</div>
	<div id="xyz_smap_linkedin_settings" class="xyz_smap_tabcontent">
	

<?php
$lnappikey=get_option('xyz_smap_lnapikey');
$lnapisecret=get_option('xyz_smap_lnapisecret');
$lmessagetopost=get_option('xyz_smap_lnmessage');
$xyz_smap_ln_company_ids=get_option('xyz_smap_ln_company_ids');
// if ($xyz_smap_ln_company_ids=='')
// 	$xyz_smap_ln_company_ids=-1;

$lnaf=get_option('xyz_smap_lnaf');
if ( get_option('xyz_smap_ln_api_permission')!=2){
	if($lnaf==1 && $lnappikey!="" && $lnapisecret!="" )

		{ ?>
	
	<span style="color:red; ">Application needs authorisation</span><br>	
            <form method="post" >
			<?php wp_nonce_field( 'xyz_smap_ln_auth_form_nonce' );?>
			<input type="submit" class="submit_smap_new" name="lnauth" value="Authorize	" />
			<br><br>
			</form>
			<?php  }
			if($lnaf==0 && $lnappikey!="" && $lnapisecret!="" )
			
		{?>
			
			<form method="post" >
			<?php wp_nonce_field( 'xyz_smap_ln_auth_form_nonce' );?>
			<input type="submit" class="submit_smap_new" name="lnauth" value="Reauthorize" title="Reauthorize the account" />
			<br><br>
			</form>
			<?php  }
}
else{
	//add trim
	$domain_name=trim(get_option('siteurl'));
	$xyz_smap_smapsoln_userid_ln=intval(trim(get_option('xyz_smap_smapsoln_userid_ln')));
	$xyzscripts_hash_val=trim(get_option('xyz_smap_xyzscripts_hash_val'));
	$xyzscripts_user_id=trim(get_option('xyz_smap_xyzscripts_user_id'));
	$xyz_smap_accountId=0;
	$xyz_smap_licence_key='';
	$request_hash=md5($xyzscripts_user_id.$xyzscripts_hash_val);
	$auth_secret_key=md5('smapsolutions'.$domain_name.$xyz_smap_accountId.$xyz_smap_smapsoln_userid_ln.$xyzscripts_user_id.$request_hash.$xyz_smap_licence_key.'smap');
	if($lnaf==1 )
	{
			?>
	 			<span id='ajax-save' style="display:none;"><img	class="img"  title="Saving details"	src="<?php echo plugins_url('../images/ajax-loader.gif',__FILE__);?>" style="width:65px;height:70px; "></span>
	 			<span id="auth_message">
	 				<span style="color: red;" >Application needs authorisation</span> <br>
	 				<form method="post">
	 			     <?php wp_nonce_field( 'xyz_smap_fb_auth_nonce' );?>
	 			     <input type="hidden" value="<?php echo  (is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST']; ?>" id="parent_domain">
	 					<input type="submit" class="submit_smap_new" name="lnauth"
	 						value="Authorize" onclick="javascript:return smap_popup_ln_auth('<?php echo urlencode($domain_name);?>','<?php echo $xyz_smap_smapsoln_userid_ln;?>','<?php echo $xyzscripts_user_id;?>','<?php echo $xyzscripts_hash_val;?>','<?php echo $auth_secret_key;?>','<?php echo $request_hash;?>');void(0);"/><br><br>
	 				</form></span>
	 				<?php }
	 				else if($lnaf==0 )
	 				{
 					?>
 					<span id='ajax-save' style="display:none;"><img	class="img"  title="Saving details"	src="<?php echo plugins_url('../images/ajax-loader.gif',__FILE__);?>" style="width:65px;height:70px; "></span>
	 				<form method="post" id="re_auth_message">
	 				<?php wp_nonce_field( 'xyz_smap_fb_auth_nonce' );?>
	 				<input type="hidden" value="<?php echo  (is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST']; ?>" id="parent_domain">
	 				<input type="submit" class="submit_smap_new" name="lnauth"
	 				value="Reauthorize" title="Reauthorize the account" onclick="javascript:return smap_popup_ln_auth('<?php echo urlencode($domain_name);?>','<?php echo $xyz_smap_smapsoln_userid_ln;?>','<?php echo $xyzscripts_user_id;?>','<?php echo $xyzscripts_hash_val;?>','<?php echo $auth_secret_key;?>','<?php echo $request_hash;?>');void(0);"/><br><br>
	 				</form>
	 				<?php }
}?>
			
	<table class="widefat" style="width: 99%;background-color: #FFFBCC" id="xyz_linkedin_settings_note" >
	<tr>
	<td id="bottomBorderNone" style="border: 1px solid #FCC328;">
	<div>

		<b>Note :</b> You have to create a Linkedin application before filling the following details.
		<b><a href="https://www.linkedin.com/secure/developer?newapp" target="_blank">Click here</a></b> to create new Linkedin application. 
		<br>Specify the website url for the application as : 
		<span style="color: red;"><?php echo  (is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST']; ?></span>
		<br>Specify the authorized redirect url as :  
		<span style="color: red;"><?php echo  admin_url().'admin.php'; ?></span>
<br>For detailed step by step instructions <b><a href="http://help.xyzscripts.com/docs/social-media-auto-publish/faq/how-can-i-create-linkedin-application/" target="_blank">Click here</a></b>.
	</div>

	</td>
	</tr>
	</table>

	<form method="post" >
		<?php wp_nonce_field( 'xyz_smap_ln_settings_form_nonce' );?>
	<div style="font-weight: bold;padding: 3px;">All fields given below are mandatory</div> 
	
	<table class="widefat xyz_smap_widefat_table"  style="width: 99%;">
		
	<tr valign="top"><td>Enable auto publish posts to my linkedin account</td>
		<td width="50%" class="switch-field">
			<label id="xyz_smap_lnpost_permission_yes"><input type="radio" name="xyz_smap_lnpost_permission" value="1" <?php  if(get_option('xyz_smap_lnpost_permission')==1) echo 'checked';?>/>Yes</label>
			<label id="xyz_smap_lnpost_permission_no"><input type="radio" name="xyz_smap_lnpost_permission" value="0" <?php  if(get_option('xyz_smap_lnpost_permission')==0) echo 'checked';?>/>No</label>
		</td>
	</tr>
	<tr valign="top"><td width="50%"> V2 API usage <span class="mandatory">*</span>
	</td>
	<td>
	<input type="radio" name="xyz_smap_ln_api_permission" id="xyz_smap_ln_api_permission_basic" value="0" <?php if (get_option('xyz_smap_ln_api_permission')==0) echo 'checked';?>/>
	<span style="color: #a7a7a7;font-weight: bold;">Own app-Basic profile fields only</span><br>
	<input type="radio" name="xyz_smap_ln_api_permission" id="xyz_smap_ln_api_permission_company" value="1" <?php if (get_option('xyz_smap_ln_api_permission')==1) echo 'checked';?>/>
	<span style="color: #a7a7a7;font-weight: bold;">Own app-Basic profile fields + company pages <br/><span style='padding-left: 25px;'>(requires app submission and LinkedIn review)</span></span><br/>
	<span style="padding-left: 25px;"><a href="http://help.xyzscripts.com/docs/social-media-auto-publish/faq/how-can-i-create-linkedin-application/" target="_blank">How can I create a Linkedin Application?</a></span><br/>
	<input type="radio" name="xyz_smap_ln_api_permission" id="xyz_smap_ln_api_permission_smapsolutions" value="2" <?php if(get_option('xyz_smap_ln_api_permission')==2) echo 'checked';?>>
	<span style="color: #000000;font-size: 13px;background-color: #f7a676;font-weight: 500;padding: 3px 5px;"><i class="fa fa-star-o" aria-hidden="true" style="margin-right:5px;"></i>SMAPsolution.com's App ( ready to publish )<i class="fa fa-star-o" aria-hidden="true" style="margin-right:5px;"></i></span><br> <span style="padding-left: 25px;">Starts from 10 USD per year</span>
	<br>
	<?php if(get_option('xyz_smap_smapsoln_userid_ln')==0)
	{?>
	<span style="color: #ff5e00;padding-left: 27px;font-size: small;"><b>30 DAYS FREE TRIAL AVAILABLE*</b></span>
	<br/>
	<?php }?>
	
	</td></tr>
	<?php if( ($xyzscripts_user_id =='' || $xyzscripts_hash_val=='') && get_option('xyz_smap_ln_api_permission')==2)
	{  ?>
	<tr valign="top" id="xyz_smap_conn_to_xyzscripts">
	<td width="50%">	</td>
	<td width="50%">
	<span id='ajax-save-xyzscript_acc_ln' style="display:none;"><img	class="img"  title="Saving details"	src="<?php echo plugins_url('../images/ajax-loader.gif',__FILE__);?>" style="width:65px;height:70px; "></span>
	<span id="connect_to_xyzscripts_ln" style="background-color: #1A87B9;color: white; padding: 4px 5px;
    text-align: center; text-decoration: none;   display: inline-block;border-radius: 4px;">
	<a href="javascript:smap_popup_connect_to_xyzscripts();void(0);" style="color:white !important;">Connect your xyzscripts account</a>
	</span>
	</td>
	</tr>
	<?php }?>
	<tr valign="top" class="xyz_linkedin_settings">
	<td width="50%">Client ID </td>					
	<td>
		<input id="xyz_smap_lnapikey" name="xyz_smap_lnapikey" type="text" value="<?php if($lms1=="") {echo esc_html(get_option('xyz_smap_lnapikey'));}?>"/>
		<a href="http://help.xyzscripts.com/docs/social-media-auto-publish/faq/how-can-i-create-linkedin-application/" target="_blank">How can I create a Linkedin Application?</a>
	</td></tr>
	

	<tr valign="top" class="xyz_linkedin_settings"><td>Client Secret</td>
	<td>
		<input id="xyz_smap_lnapisecret" name="xyz_smap_lnapisecret" type="text" value="<?php if($lms2=="") { echo esc_html(get_option('xyz_smap_lnapisecret')); }?>" />
	</td></tr>
	
	<tr valign="top">
					<td>Message format for posting <img src="<?php echo $heimg?>"
						onmouseover="detdisplay_smap('xyz_ln')" onmouseout="dethide_smap('xyz_ln')" style="width:13px;height:auto;">
						<div id="xyz_ln" class="smap_informationdiv"
							style="display: none; font-weight: normal;">
							{POST_TITLE} - Insert the title of your post.<br />{PERMALINK} -
							Insert the URL where your post is displayed.<br />{POST_EXCERPT}
							- Insert the excerpt of your post.<br />{POST_CONTENT} - Insert
							the description of your post.<br />{BLOG_TITLE} - Insert the name
							of your blog.<br />{USER_NICENAME} - Insert the nicename
							of the author.<br />{POST_ID} - Insert the ID of your post.
							<br />{POST_PUBLISH_DATE} - Insert the publish date of your post.
							<br />{USER_DISPLAY_NAME} - Insert the display name of the author.
						</div><br/><span style="color: #0073aa;">[Optional]</span></td>
	<td>
	<select name="xyz_smap_ln_info" id="xyz_smap_ln_info" onchange="xyz_smap_ln_info_insert(this)">
		<option value ="0" selected="selected">--Select--</option>
		<option value ="1">{POST_TITLE}  </option>
		<option value ="2">{PERMALINK} </option>
		<option value ="3">{POST_EXCERPT}  </option>
		<option value ="4">{POST_CONTENT}   </option>
		<option value ="5">{BLOG_TITLE}   </option>
		<option value ="6">{USER_NICENAME}   </option>
		<option value ="7">{POST_ID}   </option>
		<option value ="8">{POST_PUBLISH_DATE}   </option>
		<option value ="9">{USER_DISPLAY_NAME}   </option>
		</select> </td></tr><tr><td>&nbsp;</td><td>
		<textarea id="xyz_smap_lnmessage"  name="xyz_smap_lnmessage" style="height:80px !important;" ><?php echo esc_textarea(get_option('xyz_smap_lnmessage'));?></textarea>
	</td></tr>
	
	<tr valign="top">
		<td>Posting method
		</td>
		<td>
		<select id="xyz_smap_lnpost_method" name="xyz_smap_lnpost_method">
				<option value="1"
	<?php  if(get_option('xyz_smap_lnpost_method')==1) echo 'selected';?>>Simple text message</option>
				<option value="2"
	<?php  if(get_option('xyz_smap_lnpost_method')==2) echo 'selected';?>>Attach your blog post </option>
					<option value="3"
	<?php  if(get_option('xyz_smap_lnpost_method')==3) echo 'selected';?>>Text message with image </option>
		</select>
		</td>
	</tr>
	<!-- ///////////////////////////////// -->
	<?php if (get_option('xyz_smap_lnaf')==0 && get_option('xyz_smap_ln_api_permission')==2)
	{
		?>
	<tr valign="top" id="share_post_profile"><td>Share post to profile	<span class="mandatory">*</span> </td>
	<td  class="switch-field">
	<?php  if(get_option('xyz_smap_lnshare_to_profile')==0){?>
		<label id="xyz_smap_lnshare_to_profile_smap_yes" class="xyz_smap_toggle_off"><input type="radio" name="xyz_smap_lnshare_to_profile_smap" value="1" disabled/>Yes</label>
		<label id="xyz_smap_lnshare_to_profile_smap_no" class="xyz_smap_toggle_on"><input type="radio" name="xyz_smap_lnshare_to_profile_smap" value="0" checked/>No</label>
		<?php }
		elseif(get_option('xyz_smap_lnshare_to_profile')==1){
			?>
		<label id="xyz_smap_lnshare_to_profile_smap_yes" class="xyz_smap_toggle_on"><input type="radio" name="xyz_smap_lnshare_to_profile_smap" value="1" checked/>Yes</label>
		<label id="xyz_smap_lnshare_to_profile_smap_no" class="xyz_smap_toggle_off"><input type="radio" name="xyz_smap_lnshare_to_profile_smap" value="0" disabled/>No</label>
			<?php 
		}?>
		  <span style="width: 10px;color: #ce5c19;font-size: 20px;">*</span>
		</td>
	</tr> 
			<?php 
	}else{?>
	<!-- ///////////////////////////////// -->
	<tr valign="top">
	<td>Share post to profile</td>
	<td  class="switch-field">
		<label id="xyz_smap_lnshare_to_profile_yes" ><input type="radio" name="xyz_smap_lnshare_to_profile" value="1" <?php  if(get_option('xyz_smap_lnshare_to_profile')==1) echo 'checked';?>/>Yes</label>
		<label id="xyz_smap_lnshare_to_profile_no" ><input type="radio" name="xyz_smap_lnshare_to_profile" value="0" <?php  if(get_option('xyz_smap_lnshare_to_profile')==0) echo 'checked';?>/>No</label>
	</td>
	</tr>
	<?php }?>
	
	<tr valign="top" id="shareprivate">
<!-- 	<input type="hidden" name="xyz_smap_ln_sharingmethod" id="xyz_smap_ln_sharingmethod" value="0"> -->
	<td>Share post content with</td>
	<td  class="switch-field">
		<label id="xyz_smap_ln_shareprivate_yes" ><input type="radio" name="xyz_smap_ln_shareprivate" value="1" <?php  if(get_option('xyz_smap_ln_shareprivate')==1) echo 'checked';?>/>Connections</label>
		<label id="xyz_smap_ln_shareprivate_no" ><input type="radio" name="xyz_smap_ln_shareprivate" value="0" <?php  if(get_option('xyz_smap_ln_shareprivate')==0) echo 'checked';?>/>Public</label>
		</td>
	</tr>
		<?php if(get_option('xyz_smap_lnaf')==0 && get_option('xyz_smap_ln_api_permission')==1){?>
		<tr valign="top" id="share_post_company"><td>Select pages for auto publish </td>
		<td>
			<?php 
			$ln_acc_tok_arr='';
			$xyz_smap_application_lnarray=get_option('xyz_smap_application_lnarray');
			if ($xyz_smap_application_lnarray!='')
			$ln_acc_tok_arr=json_decode($xyz_smap_application_lnarray);
			//if ($ln_acc_tok_arr)
			//$xyz_smap_application_lnarray=$ln_acc_tok_arr->access_token;
			$ln_publish_status=array();
			$xyz_smap_ln_company_idArray=explode(',',$xyz_smap_ln_company_ids);
			?><div class="scroll_checkbox" style="width:220px !important;" >
				<?php if(isset($ln_acc_tok_arr->access_token))
				{		
				$ln_err_flag=0;
				$url="https://api.linkedin.com/v2/organizationalEntityAcls?q=roleAssignee&role=ADMINISTRATOR&projection=(elements*(*,roleAssignee~(localizedFirstName,%20localizedLastName),%20organizationalTarget~(localizedName)))&oauth2_access_token=".$ln_acc_tok_arr->access_token;
				$ar=wp_remote_get($url);
				if (is_object( $ar ) &&  is_a( $ar, 'wp_Error' ))
						echo "Failed to fetch company details.";
				elseif (is_array($ar))
				{
					$ar=json_decode($ar['body'],true);
							if (isset($ar['elements'])){
					$ar=$ar['elements'];
					foreach ($ar as $ark)
					{ 
						if (strpos($ark['organizationalTarget'], 'urn:li:organizationBrand') !== false)
							$comp_id=str_replace('urn:li:organizationBrand:', '',$ark['organizationalTarget']);
						else
						$comp_id=str_replace('urn:li:organization:', '',$ark['organizationalTarget']);
						?>
					<input type="checkbox" name="xyz_smap_ln_share_post_company[]"  value="<?php echo $comp_id."-".$ark['organizationalTarget~']['localizedName']; ?>" <?php if(in_array($comp_id, $xyz_smap_ln_company_idArray)) echo "checked" ?>><?php echo $ark['organizationalTarget~']['localizedName']; ?><br/>
   				 <?php } }
   				 else $ln_err_flag=1;
   				 if ($ln_err_flag==1){
   				 	echo "No companies found.";
   				 	if (isset($ar['body']))print_r($ar['body']);}
   				 }
					}
				else {echo "No companies found.";}
				?></div>
		</td>
	</tr>
	<?php }elseif (get_option('xyz_smap_ln_api_permission')==2 && get_option('xyz_smap_lnaf')==0 ){
		if (get_option('xyz_smap_ln_page_names')!=''){
		$xyz_smap_ln_company_names=unserialize(base64_decode(get_option('xyz_smap_ln_page_names')));
		////////////////////////////////////smap api linkedin //////////////////////////////
		?>
				<tr valign="top" id="share_post_company"><td>Share post to company page </td>
				<td>
				<div>
					<div class="scroll_checkbox" style="float: left;"><?php
						foreach ($xyz_smap_ln_company_names as $xyz_ln_company_id => $xyz_ln_company_name)
						   {?>
							 <input type="checkbox" name="xyz_smap_ln_share_post_company[]"  value="<?php echo $xyz_ln_company_id."-".$xyz_ln_company_name; ?>" <?php /*if(in_array($xyz_ln_company_id, $xyz_smap_ln_company_idArray))*/ echo "checked" ?> disabled><?php echo $xyz_ln_company_name; ?><br/>
	   				       <?php 	$ln_company_name[$xyz_ln_company_id]=$xyz_ln_company_name;
						   } 
	   				 	$ln_company_name1=base64_encode(serialize($ln_company_name));
					?>
	                 </div>
	                 <div style="float: left;width: 10px;color: #ce5c19;font-size: 20px;">*</div>
	             </div>
	                   <input type="hidden" value="<?php echo $ln_company_name1;?>" name="hidden_company_name" >
				</td>
			</tr> 
			<?php 
		}
 }?>
		<tr>
			<td   id="bottomBorderNone"></td>
					<td   id="bottomBorderNone"><div style="height: 50px;">
							<input type="submit" class="submit_smap_new"
								style=" margin-top: 10px; "
								name="linkdn" value="Save" /></div>
					</td>
				</tr>
		<?php if(get_option('xyz_smap_smapsoln_userid_ln')==0){?>
		<tr><td style='color: #ce5c19;padding-left:0px;'>*Free trial is available only for first time users</td></tr>
		<?php }
		else{?>
			<tr><td style='color: #ce5c19;padding-left:0px;'>*Use reauthorize button to change selected values</td></tr>
			<?php }?>

</table>


</form>
</div>


	<?php 

	if(isset($_POST['bsettngs']))
	{
		if (! isset( $_REQUEST['_wpnonce'] )|| ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'xyz_smap_basic_settings_form_nonce' ))
		{
			wp_nonce_ays( 'xyz_smap_basic_settings_form_nonce' );
			exit();
		}

		$xyz_smap_include_pages=intval($_POST['xyz_smap_include_pages']);
		$xyz_smap_include_posts=intval($_POST['xyz_smap_include_posts']);
		
		if($_POST['xyz_smap_cat_all']=="All")
			$smap_category_ids=$_POST['xyz_smap_cat_all'];//radio btn name
		else
		{
			$smap_category_ids=$_POST['xyz_smap_catlist'];//dropdown
			$smap_category_ids=implode(',', $smap_category_ids);
		}

		$xyz_customtypes="";
		
        if(isset($_POST['post_types']))
		$xyz_customtypes=$_POST['post_types'];
        $xyz_smap_peer_verification=intval($_POST['xyz_smap_peer_verification']);
        $xyz_smap_premium_version_ads=intval($_POST['xyz_smap_premium_version_ads']);
        $xyz_smap_default_selection_edit=intval($_POST['xyz_smap_default_selection_edit']);
        $xyz_smap_free_enforce_og_tags=intval($_POST['xyz_smap_free_enforce_og_tags']);
        //$xyz_smap_future_to_publish=$_POST['xyz_smap_future_to_publish'];
//         $xyz_smap_utf_decode_enable=intval($_POST['xyz_smap_utf_decode_enable']);
		$smap_customtype_ids="";
		
		$xyz_smap_applyfilters="";
		if(isset($_POST['xyz_smap_applyfilters']))
			$xyz_smap_applyfilters=$_POST['xyz_smap_applyfilters'];
		
		if($xyz_customtypes!="")
		{
			for($i=0;$i<count($xyz_customtypes);$i++)
			{
				$smap_customtype_ids.=$xyz_customtypes[$i].",";
			}

		}
		$smap_customtype_ids=rtrim($smap_customtype_ids,',');

		$xyz_smap_applyfilters_val="";
		if($xyz_smap_applyfilters!="")
		{
			for($i=0;$i<count($xyz_smap_applyfilters);$i++)
			{
			$xyz_smap_applyfilters_val.=$xyz_smap_applyfilters[$i].",";
		}
		
		}
		$xyz_smap_applyfilters_val=rtrim($xyz_smap_applyfilters_val,',');
		
		
		update_option('xyz_smap_include_pages',$xyz_smap_include_pages);
		update_option('xyz_smap_include_posts',$xyz_smap_include_posts);
		if($xyz_smap_include_posts==0)
			update_option('xyz_smap_include_categories',"All");
		else
			update_option('xyz_smap_include_categories',$smap_category_ids);
		update_option('xyz_smap_std_apply_filters',$xyz_smap_applyfilters_val);
		update_option('xyz_smap_include_customposttypes',$smap_customtype_ids);
		update_option('xyz_smap_peer_verification',$xyz_smap_peer_verification);
		update_option('xyz_smap_premium_version_ads',$xyz_smap_premium_version_ads);
		update_option('xyz_smap_default_selection_edit',$xyz_smap_default_selection_edit);
		update_option('xyz_smap_free_enforce_og_tags',$xyz_smap_free_enforce_og_tags);
		//update_option('xyz_smap_std_future_to_publish',$xyz_smap_future_to_publish);
	}

	//$xyz_smap_future_to_publish=get_option('xyz_smap_std_future_to_publish');
	$xyz_credit_link=get_option('xyz_credit_link');
	$xyz_smap_include_pages=get_option('xyz_smap_include_pages');
	$xyz_smap_include_posts=get_option('xyz_smap_include_posts');
	$xyz_smap_include_categories=get_option('xyz_smap_include_categories');
	if ($xyz_smap_include_categories!='All')
	$xyz_smap_include_categories=explode(',', $xyz_smap_include_categories);
	$xyz_smap_include_customposttypes=get_option('xyz_smap_include_customposttypes');
	$xyz_smap_apply_filters=get_option('xyz_smap_std_apply_filters');
	$xyz_smap_peer_verification=get_option('xyz_smap_peer_verification');
	$xyz_smap_premium_version_ads=get_option('xyz_smap_premium_version_ads');
	$xyz_smap_default_selection_edit=get_option('xyz_smap_default_selection_edit');
	$xyz_smap_free_enforce_og_tags=get_option('xyz_smap_free_enforce_og_tags');
// 	$xyz_smap_utf_decode_enable=get_option('xyz_smap_utf_decode_enable');
	?>
<div id="xyz_smap_basic_settings" class="xyz_smap_tabcontent">
		<form method="post">
	<?php wp_nonce_field( 'xyz_smap_basic_settings_form_nonce' );?>
			<table class="widefat xyz_smap_widefat_table" style="width: 99%">
<tr><td><h2>Basic Settings</h2></td></tr>

				<tr valign="top">
					<td  colspan="1">Publish wordpress `posts` to social media
					</td>
					<td  class="switch-field">
						<label id="xyz_smap_include_posts_yes"><input type="radio" name="xyz_smap_include_posts" value="1" <?php  if($xyz_smap_include_posts==1) echo 'checked';?>/>Yes</label>
						<label id="xyz_smap_include_posts_no"><input type="radio" name="xyz_smap_include_posts" value="0" <?php  if($xyz_smap_include_posts==0) echo 'checked';?>/>No</label>
					</td>
				</tr>
				<tr valign="top">

					<td  colspan="1" width="50%">Publish wordpress `pages` to social media </td>
					<td  class="switch-field">
						<label id="xyz_smap_include_pages_yes"><input type="radio" name="xyz_smap_include_pages" value="1" <?php  if($xyz_smap_include_pages==1) echo 'checked';?>/>Yes</label>
						<label id="xyz_smap_include_pages_no"><input type="radio" name="xyz_smap_include_pages" value="0" <?php  if($xyz_smap_include_pages==0) echo 'checked';?>/>No</label>
					</td>
				</tr>
			
					<?php 
					$xyz_smap_hide_custompost_settings='';
					$args=array(
							'public'   => true,
							'_builtin' => false
					);
					$output = 'names'; // names or objects, note names is the default
					$operator = 'and'; // 'and' or 'or'
					$post_types=get_post_types($args,$output,$operator);

					$ar1=explode(",",$xyz_smap_include_customposttypes);
					$cnt=count($post_types);
					if($cnt==0)
					$xyz_smap_hide_custompost_settings = 'style="display: none;"';//echo 'NA';
					?>
				<tr valign="top" <?php echo $xyz_smap_hide_custompost_settings;?>>

					<td  colspan="1">Select wordpress custom post types for auto publish</td>
					<td>
					<?php 		foreach ($post_types  as $post_type ) {

						echo '<input type="checkbox" name="post_types[]" value="'.$post_type.'" ';
						if(in_array($post_type, $ar1))
						{
							echo 'checked="checked"/>';
						}
						else
							echo '/>';

						echo $post_type.'<br/>';

					}?>
					</td>
					</tr>
					<tr><td><h2>Advanced Settings</h2></td></tr>
					<tr valign="top">
						<td>Enforce og tags for Facebook and LinkedIn<img src="<?php echo $heimg?>" onmouseover="detdisplay_smap('xyz_smap_free_enforce_og')" onmouseout="dethide_smap('xyz_smap_free_enforce_og')" style="width:13px;height:auto;">
						<div id="xyz_smap_free_enforce_og" class=smap_informationdiv style="display: none;width: 400px;">
						If you enable, Open Graph tags will be generated while posting to Facebook and LinkedIn.<br/>
						When sharing links to Facebook and LinkedIn, <b>Facebook/LinkedIn Crawler</b> uses internal heuristics to set the preview image for your content when using the posting method <b>Share a link to your blog post</b> or <b>Attach your blog post</b> in Facebook and also when sharing to LinkedIn.
						<br/>If Open Graph tags are present, most major content sharing platform's crawler will not have to rely on it's own analysis to determine what content will be shared, which improves the likelihood that the information that is shared is exactly what you intended.
						</div>
						</td>
						<td  class="switch-field">
							<label id="xyz_smap_free_enforce_og_tags_yes" class="xyz_smap_toggle_off"><input type="radio" name="xyz_smap_free_enforce_og_tags" value="1" <?php  if($xyz_smap_free_enforce_og_tags==1) echo 'checked';?>/>Yes</label>
							<label id="xyz_smap_free_enforce_og_tags_no" class="xyz_smap_toggle_on"><input type="radio" name="xyz_smap_free_enforce_og_tags" value="0" <?php  if($xyz_smap_free_enforce_og_tags==0) echo 'checked';?>/>No</label>
					    </td>
					</tr> 

	<tr valign="top" id="selPostCat">

					<td  colspan="1">Select post categories for auto publish
					</td>
					<td class="switch-field">
	                <input type="hidden" value="<?php echo esc_html($xyz_smap_include_categories);?>" name="xyz_smap_sel_cat" 
			id="xyz_smap_sel_cat"> 
					<label id="xyz_smap_include_categories_no">
					<input type="radio"	name="xyz_smap_cat_all" id="xyz_smap_cat_all" value="All" onchange="rd_cat_chn(1,-1)" <?php if($xyz_smap_include_categories=="All") echo "checked"?>>All<font style="padding-left: 10px;"></font></label>
					<label id="xyz_smap_include_categories_yes">
					<input type="radio"	name="xyz_smap_cat_all" id="xyz_smap_cat_all" value=""	onchange="rd_cat_chn(1,1)" <?php if($xyz_smap_include_categories!="All") echo "checked"?>>Specific</label>
					<br /> <br /> <div class="scroll_checkbox"  id="cat_dropdown_span">
					<?php 

						$args = array(
								'show_option_all'    => '',
								'show_option_none'   => '',
								'orderby'            => 'name',
								'order'              => 'ASC',
								'show_last_update'   => 0,
								'show_count'         => 0,
								'hide_empty'         => 0,
								'child_of'           => 0,
								'exclude'            => '',
								'echo'               => 0,
								'selected'           => '1 3',
								'hierarchical'       => 1,
								'id'                 => 'xyz_smap_catlist',
								'class'              => 'postform',
								'depth'              => 0,
								'tab_index'          => 0,
								'taxonomy'           => 'category');

						if(count(get_categories($args))>0)
					{
						$smap_categories=get_categories($args);
						foreach ($smap_categories as $smap_cat)
						{
							$cat_id[]=$smap_cat->cat_ID;
							$cat_name[]=$smap_cat->cat_name;
							?>
							<input type="checkbox" name="xyz_smap_catlist[]"  value="<?php  echo $smap_cat->cat_ID;?>" <?php if(is_array($xyz_smap_include_categories)) if(in_array($smap_cat->cat_ID, $xyz_smap_include_categories)) echo "checked"; ?>/><?php echo $smap_cat->cat_name; ?>
							<br/><?php }
					}
						else
							echo "NIL";

						?><br /> <br /> </div>
					</td>
				</tr>

					<tr valign="top">

					<td scope="row" colspan="1" width="50%">Auto publish on editing posts/pages/custom post types
					</td>
					<td>
					<input type="radio" name="xyz_smap_default_selection_edit" value="1" <?php  if($xyz_smap_default_selection_edit==1) echo 'checked';?>/>Enabled
					<br/><input type="radio" name="xyz_smap_default_selection_edit" value="0" <?php  if($xyz_smap_default_selection_edit==0) echo 'checked';?>/>Disabled
					<br/><input type="radio" name="xyz_smap_default_selection_edit" value="2" <?php  if($xyz_smap_default_selection_edit==2) echo 'checked';?>/>Use settings from post creation or post updation
					</td>
					</tr>
					
					<tr valign="top">
					
					<td scope="row" colspan="1" width="50%">Enable SSL peer verification in remote requests</td>
					<td  class="switch-field">
						<label id="xyz_smap_peer_verification_yes"><input type="radio" name="xyz_smap_peer_verification" value="1" <?php  if($xyz_smap_peer_verification==1) echo 'checked';?>/>Yes</label>
						<label id="xyz_smap_peer_verification_no"><input type="radio" name="xyz_smap_peer_verification" value="0" <?php  if($xyz_smap_peer_verification==0) echo 'checked';?>/>No</label>
					</td>
					</tr>
					
				<tr valign="top">
					<td scope="row" colspan="1">Apply filters during publishing	</td>
					<td>
					<?php 
					$ar2=explode(",",$xyz_smap_apply_filters);
					for ($i=0;$i<3;$i++ ) {
						$filVal=$i+1;
						
						if($filVal==1)
							$filName='the_content';
						else if($filVal==2)
							$filName='the_excerpt';
						else if($filVal==3)
							$filName='the_title';
						else $filName='';
						
						echo '<input type="checkbox" name="xyz_smap_applyfilters[]"  value="'.$filVal.'" ';
						if(in_array($filVal, $ar2))
						{
							echo 'checked="checked"/>';
						}
						else
							echo '/>';
					
						echo '<label>'.$filName.'</label><br/>';
					
					}
					?>
					</td>
				</tr>

<!--  <tr valign="top">
		
					<td  colspan="1" width="50%">Enable utf-8 decoding before publishing
					</td>
					<td  class="switch-field">
						<label id="xyz_smap_utf_decode_enable_yes"><input type="radio" name="xyz_smap_utf_decode_enable" value="1" <?php // if($xyz_smap_utf_decode_enable==1) echo 'checked';?>/>Yes</label>
						<label id="xyz_smap_utf_decode_enable_no"><input type="radio" name="xyz_smap_utf_decode_enable" value="0" <?php // if($xyz_smap_utf_decode_enable==0) echo 'checked';?>/>No</label>
					</td>
				</tr>-->
<tr><td><h2>Other Settings</h2></td></tr>
				<tr valign="top">

					<td  colspan="1">Enable credit link to author
					</td>
					<td  class="switch-field">
						<label id="xyz_credit_link_yes"><input type="radio" name="xyz_credit_link" value="smap" <?php  if($xyz_credit_link=='smap') echo 'checked';?>/>Yes</label>
						<label id="xyz_credit_link_no"><input type="radio" name="xyz_credit_link" value="<?php echo $xyz_credit_link!='smap'?$xyz_credit_link:0;?>" <?php  if($xyz_credit_link!='smap') echo 'checked';?>/>No</label>
					</td>
				</tr>
				
				<tr valign="top">

					<td  colspan="1">Enable premium version ads
					</td>
					<td  class="switch-field">
						<label id="xyz_smap_premium_version_ads_yes"><input type="radio" name="xyz_smap_premium_version_ads" value="1" <?php  if($xyz_smap_premium_version_ads==1) echo 'checked';?>/>Yes</label>
						<label id="xyz_smap_premium_version_ads_no"><input type="radio" name="xyz_smap_premium_version_ads" value="0" <?php  if($xyz_smap_premium_version_ads==0) echo 'checked';?>/>No</label>
					</td>
				</tr>
				<tr>
					<td id="bottomBorderNone">
					</td>
<td id="bottomBorderNone"><div style="height: 50px;">
<input type="submit" class="submit_smap_new" style="margin-top: 10px;"	value=" Update Settings" name="bsettngs" /></div></td>
				</tr>
			</table>
		</form>
		</div>
</div>		
<?php if (is_array($xyz_smap_include_categories))
$xyz_smap_include_categories1=implode(',', $xyz_smap_include_categories);
else 
	$xyz_smap_include_categories1=$xyz_smap_include_categories;
	?>
	<script type="text/javascript">
	//drpdisplay();
var catval='<?php echo esc_html($xyz_smap_include_categories1); ?>';
var custtypeval='<?php echo esc_html($xyz_smap_include_customposttypes); ?>';
var get_opt_cats='<?php echo esc_html(get_option('xyz_smap_include_posts'));?>';
jQuery(document).ready(function() {
			jQuery('.xyz_smap_hide_ln_authErr').click(function() {
				 var base = '<?php echo admin_url('admin.php?page=social-media-auto-publish-settings');?>';
				  window.location.href = base;
		});

	<?php 
			if(isset($_POST['bsettngs']))
			{?>
			document.getElementById("xyz_smap_basic_tab_settings").click();	
			// Get the element with id="xyz_smap_default_tab_settings" and click on it 
			<?php }
			else if(isset($_POST['twit'])){?> 
			document.getElementById("xyz_smap_default_twtab_settings").click();
				
			<?php }
			else if(isset($_POST['linkdn']) ||  isset($_GET['err'])){?>
			document.getElementById("xyz_smap_default_lntab_settings").click();
				
			<?php }
			else{
				if (isset($_POST['fb'])){?>
				document.getElementById("xyz_smap_default_fbtab_settings").click();
				
				<?php }
				else if( isset($_GET['msg'])&& ($_GET['msg']==4 ||$_GET['msg']==7)){
					?>
					document.getElementById("xyz_smap_default_lntab_settings").click();
					<?php 
				}
				else{?>
					document.getElementById("xyz_smap_default_fbtab_settings").click();
					<?php 
				}
			}?>
	
	  if(catval=="All")
		  jQuery("#cat_dropdown_span").hide();
	  else
		  jQuery("#cat_dropdown_span").show();

	  if(get_opt_cats==0)
		  jQuery('#selPostCat').hide();
	  else
		  jQuery('#selPostCat').show();

  jQuery("#select_all_pages").click(function(){
		
		jQuery(".selpages").prop("checked",jQuery("#select_all_pages").prop("checked"));
	});
   var xyz_credit_link=jQuery("input[name='xyz_credit_link']:checked").val();
   if(xyz_credit_link=='smap')
	   xyz_credit_link=1;
   else
	   xyz_credit_link=0;
   XyzSmapToggleRadio(xyz_credit_link,'xyz_credit_link');
   
   var xyz_smap_cat_all=jQuery("input[name='xyz_smap_cat_all']:checked").val();
   if (xyz_smap_cat_all == 'All') 
	   xyz_smap_cat_all=0;
   else 
	   xyz_smap_cat_all=1;
   XyzSmapToggleRadio(xyz_smap_cat_all,'xyz_smap_include_categories'); 
  

   var smap_toggle_element_ids=['xyz_smap_post_permission','xyz_smap_include_categories','xyz_smap_default_selection_edit','xyz_smap_peer_verification',
		'xyz_smap_twpost_image_permission','xyz_smap_twpost_permission','xyz_smap_ln_shareprivate',
		 'xyz_smap_lnpost_permission','xyz_smap_include_pages','xyz_smap_include_posts','xyz_credit_link','xyz_smap_premium_version_ads','xyz_smap_lnshare_to_profile','xyz_smap_free_enforce_og_tags','xyz_smap_clear_fb_cache'];

   jQuery.each(smap_toggle_element_ids, function( index, value ) {
		   checkedval= jQuery("input[name='"+value+"']:checked").val();
		   XyzSmapToggleRadio(checkedval,value); 
		   if(value=='xyz_smap_lnshare_to_profile')
				xyz_smap_show_visibility(checkedval);
   	});
var xyz_smap_lnshare_to_profile='<?php echo get_option('xyz_smap_lnshare_to_profile'); ?>';
   		xyz_smap_show_visibility(xyz_smap_lnshare_to_profile);
   var xyz_smap_app_sel_mode=jQuery("input[name='xyz_smap_app_sel_mode']:checked").val();
   if(xyz_smap_app_sel_mode !=0){
		jQuery('.xyz_smap_facebook_settings').hide();
		jQuery('#xyz_smap_app_creation_note').hide();
		jQuery('#xyz_smap_conn_to_xyzscripts').show();
   }
   else{
	   	jQuery('.xyz_smap_facebook_settings').show();
	   	jQuery('#xyz_smap_app_creation_note').show();
	   	jQuery('#xyz_smap_conn_to_xyzscripts').hide();
	   		}
   jQuery("input[name='xyz_smap_app_sel_mode']").click(function(){
	   var xyz_smap_app_sel_mode=jQuery("input[name='xyz_smap_app_sel_mode']:checked").val();
	   if(xyz_smap_app_sel_mode !=0){
		    jQuery('#xyz_smap_app_creation_note').hide();
			jQuery('.xyz_smap_facebook_settings').hide();
			jQuery('#xyz_smap_conn_to_xyzscripts').show();
			}
		   else{
			jQuery('#xyz_smap_app_creation_note').show(); 
		   	jQuery('.xyz_smap_facebook_settings').show();
		   	jQuery('#xyz_smap_conn_to_xyzscripts').hide();
		   	}
	   });
   var xyz_smap_app_sel_mode=jQuery("input[name='xyz_smap_ln_api_permission']:checked").val();
   if(xyz_smap_app_sel_mode ==2){
		jQuery('.xyz_linkedin_settings').hide();
		jQuery('#xyz_linkedin_settings_note').hide();
   }
   else{
	   	jQuery('.xyz_linkedin_settings').show();
		jQuery('#xyz_linkedin_settings_note').show();
   }
   jQuery("input[name='xyz_smap_ln_api_permission']").click(function(){
	   var xyz_smap_app_sel_mode=jQuery("input[name='xyz_smap_ln_api_permission']:checked").val();
	   if(xyz_smap_app_sel_mode ==2){
			jQuery('.xyz_linkedin_settings').hide();
			jQuery('#xyz_linkedin_settings_note').hide();
	  		}
		   else{
		   	jQuery('.xyz_linkedin_settings').show();
		   	jQuery('#xyz_linkedin_settings_note').show();
		   	}
	   });
   window.addEventListener('message', function(e) {
	   xyz_smap_ProcessChildMessage(e.data);
	} , false);
	}); 
	
function setcat(obj)
{
var sel_str="";
for(k=0;k<obj.options.length;k++)
{
if(obj.options[k].selected)
sel_str+=obj.options[k].value+",";
}


var l = sel_str.length; 
var lastChar = sel_str.substring(l-1, l); 
if (lastChar == ",") { 
	sel_str = sel_str.substring(0, l-1);
}

document.getElementById('xyz_smap_sel_cat').value=sel_str;

}


function rd_cat_chn(val,act)
{
	if(val==1)
	{
		if(act==-1)
		  jQuery("#cat_dropdown_span").hide();
		else
		  jQuery("#cat_dropdown_span").show();
	}
}

function xyz_smap_fb_info_insert(inf){
	
    var e = document.getElementById("xyz_smap_fb_info");
    var ins_opt = e.options[e.selectedIndex].text;
    if(ins_opt=="0")
    	ins_opt="";
    var str=jQuery("textarea#xyz_smap_message").val()+ins_opt;
    jQuery("textarea#xyz_smap_message").val(str);
    jQuery('#xyz_smap_fb_info :eq(0)').prop('selected', true);
    jQuery("textarea#xyz_smap_message").focus();

}
function xyz_smap_tw_info_insert(inf){
	
    var e = document.getElementById("xyz_smap_tw_info");
    var ins_opt = e.options[e.selectedIndex].text;
    if(ins_opt=="0")
    	ins_opt="";
    var str=jQuery("textarea#xyz_smap_twmessage").val()+ins_opt;
    jQuery("textarea#xyz_smap_twmessage").val(str);
    jQuery('#xyz_smap_tw_info :eq(0)').prop('selected', true);
    jQuery("textarea#xyz_smap_twmessage").focus();

}

function xyz_smap_ln_info_insert(inf){
	
    var e = document.getElementById("xyz_smap_ln_info");
    var ins_opt = e.options[e.selectedIndex].text;
    if(ins_opt=="0")
    	ins_opt="";
    var str=jQuery("textarea#xyz_smap_lnmessage").val()+ins_opt;
    jQuery("textarea#xyz_smap_lnmessage").val(str);
    jQuery('#xyz_smap_ln_info :eq(0)').prop('selected', true);
    jQuery("textarea#xyz_smap_lnmessage").focus();

}
function xyz_smap_show_postCategory(val)
{
	if(val==0)
		jQuery('#selPostCat').hide();
	else
		jQuery('#selPostCat').show();
}
function xyz_smap_show_visibility(val)
{
	if(val==0)
		jQuery('#shareprivate').hide();
	else
		jQuery('#shareprivate').show();
}
var smap_toggle_element_ids=['xyz_smap_post_permission','xyz_smap_include_categories','xyz_smap_default_selection_edit','xyz_smap_peer_verification',
	'xyz_smap_twpost_image_permission','xyz_smap_twpost_permission','xyz_smap_ln_shareprivate',
	 'xyz_smap_lnpost_permission','xyz_smap_include_pages','xyz_smap_include_posts','xyz_credit_link','xyz_smap_premium_version_ads','xyz_smap_lnshare_to_profile','xyz_smap_free_enforce_og_tags','xyz_smap_clear_fb_cache'];

jQuery.each(smap_toggle_element_ids, function( index, value ) {
	jQuery("#"+value+"_no").click(function(){
		XyzSmapToggleRadio(0,value);
		if(value=='xyz_smap_include_posts')
			xyz_smap_show_postCategory(0);
		if(value=='xyz_smap_lnshare_to_profile')
			xyz_smap_show_visibility(0);
	});
	jQuery("#"+value+"_yes").click(function(){
		XyzSmapToggleRadio(1,value);
		if(value=='xyz_smap_include_posts')
			xyz_smap_show_postCategory(1);
		if(value=='xyz_smap_lnshare_to_profile')
			xyz_smap_show_visibility(1);
	});
	});
function smap_popup_fb_auth(domain_name,xyz_smap_smapsoln_userid,xyzscripts_user_id,xyzscripts_hash_val,auth_secret_key,request_hash)
{
	if(xyzscripts_user_id==''|| xyzscripts_hash_val==''){
		if(jQuery('#system_notice_area').length==0)
			jQuery('body').append('<div class="system_notice_area_style0" id="system_notice_area"></div>');
			jQuery("#system_notice_area").html('Please connect your xyzscripts member account  <span id="system_notice_area_dismiss">Dismiss</span>');
			jQuery("#system_notice_area").show();
			jQuery('#system_notice_area_dismiss').click(function() {
				jQuery('#system_notice_area').animate({
					opacity : 'hide',
					height : 'hide'
				}, 500);
			});
			return false;
	}
	else{
	var childWindow = null;
	var smap_licence_key='';
	var account_id=0;
	var smap_solution_url='<?php echo XYZ_SMAP_SOLUTION_AUTH_URL;?>';
	childWindow = window.open(smap_solution_url+"authorize/facebook.php?smap_id="+xyz_smap_smapsoln_userid+"&account_id="+account_id+
			"&domain_name="+domain_name+"&xyzscripts_user_id="+xyzscripts_user_id+"&smap_licence_key="+smap_licence_key+"&auth_secret_key="+auth_secret_key+"&free_plugin_source=smap&request_hash="+request_hash, "SmapSolutions Authorization", "toolbar=yes,scrollbars=yes,resizable=yes,left=500,width=600,height=600");
	return false;	}
}
function smap_popup_ln_auth(domain_name,xyz_smap_smapsoln_userid,xyzscripts_user_id,xyzscripts_hash_val,auth_secret_key,request_hash)
{
	if(xyzscripts_user_id==''|| xyzscripts_hash_val==''){
		if(jQuery('#system_notice_area').length==0)
			jQuery('body').append('<div class="system_notice_area_style0" id="system_notice_area"></div>');
			jQuery("#system_notice_area").html('Please connect your xyzscripts member account  <span id="system_notice_area_dismiss">Dismiss</span>');
			jQuery("#system_notice_area").show();
			jQuery('#system_notice_area_dismiss').click(function() {
				jQuery('#system_notice_area').animate({
					opacity : 'hide',
					height : 'hide'
				}, 500);
			});
			return false;
	}
	else{
	var childWindow = null;
	var smap_licence_key='';
	var account_id=0;
	var smap_solution_url='<?php echo XYZ_SMAP_SOLUTION_AUTH_URL;?>';
	childWindow = window.open(smap_solution_url+"authorize_linkedIn/linkedin.php?smap_ln_auth_id="+xyz_smap_smapsoln_userid+"&account_id="+account_id+
			"&domain_name="+domain_name+"&xyzscripts_user_id="+xyzscripts_user_id+"&smap_licence_key="+smap_licence_key+"&auth_secret_key="+auth_secret_key+"&free_plugin_source=smap&request_hash="+request_hash, "SmapSolutions Authorization", "toolbar=yes,scrollbars=yes,resizable=yes,left=500,width=600,height=600");
	return false;	}
}
function smap_popup_connect_to_xyzscripts()
{
	var childWindow = null;
	var smap_xyzscripts_url='<?php echo "https://smap.xyzscripts.com/index.php?page=index/register";?>';
	childWindow = window.open(smap_xyzscripts_url, "Connect to xyzscripts", "toolbar=yes,scrollbars=yes,resizable=yes,left=500,width=600,height=600");
	return false;	
}
function xyz_smap_ProcessChildMessage(message) {
	var messageType = message.slice(0,5);
	if(messageType==="error")
	{
		message=message.substring(6);
		if(jQuery('#system_notice_area').length==0)
		jQuery('body').append('<div class="system_notice_area_style0" id="system_notice_area"></div>');
		jQuery("#system_notice_area").html(message+' <span id="system_notice_area_dismiss">Dismiss</span>');
		jQuery("#system_notice_area").show();
		jQuery('#system_notice_area_dismiss').click(function() {
			jQuery('#system_notice_area').animate({
				opacity : 'hide',
				height : 'hide'
			}, 500);
		});
	}
	var obj1=jQuery.parseJSON(message);
	if(obj1.content &&  obj1.userid && obj1.xyzscripts_user)
	{
		var xyz_userid=obj1.userid;var xyz_user_hash=obj1.content;
		var xyz_smap_xyzscripts_accinfo_nonce= '<?php echo wp_create_nonce('xyz_smap_xyzscripts_accinfo_nonce');?>';
		var dataString = { 
				action: 'xyz_smap_xyzscripts_accinfo_auto_update', 
				xyz_userid: xyz_userid ,
				xyz_user_hash: xyz_user_hash,
				dataType: 'json',
				_wpnonce: xyz_smap_xyzscripts_accinfo_nonce
			};
		jQuery("#connect_to_xyzscripts").hide();
		jQuery("#connect_to_xyzscripts_ln").hide();
		jQuery("#ajax-save-xyzscript_acc").show();
		jQuery("#ajax-save-xyzscript_acc_ln").show();
		jQuery.post(ajaxurl, dataString ,function(response) {
			 if(response==1)
			       	alert("You do not have sufficient permissions");
			else{
 		  var base_url = '<?php echo admin_url('admin.php?page=social-media-auto-publish-settings');?>';
  		 window.location.href = base_url+'&msg=5';
		}
 		});
	}
	else if(obj1.pages && obj1.smapsoln_userid)
	{
// 	var obj1=jQuery.parseJSON(message);
	var obj=obj1.pages;
	var secretkey=obj1.secretkey;
	var xyz_smap_fb_numericid=obj1.xyz_fb_numericid;
	var smapsoln_userid=obj1.smapsoln_userid;
	var list='';
	for (var key in obj) {
	  if (obj.hasOwnProperty(key)) {
	    var val = obj[key];
	    list=list+"<input type='checkbox' value='"+key+"' checked='checked' disabled>"+val+"<br>";
	  }
	}
	jQuery("#xyz_smap_selected_pages").html(list);
	jQuery("#xyz_smap_selected_pages_tr").show();
	jQuery("#auth_message").hide();
	jQuery("#re_auth_message").show();
	var xyz_smap_selected_pages_nonce= '<?php echo wp_create_nonce('xyz_smap_selected_pages_nonce');?>';
	var pages_obj = JSON.stringify(obj);
	var dataString = { 
			action: 'xyz_smap_selected_pages_auto_update', 
			pages: pages_obj ,
			smap_secretkey: secretkey,
			xyz_fb_numericid: xyz_smap_fb_numericid,
			smapsoln_userid:smapsoln_userid,
			dataType: 'json',
			_wpnonce: xyz_smap_selected_pages_nonce
		};			
		jQuery("#re_auth_message").hide();
		jQuery("#auth_message").hide();
		jQuery("#ajax-save").show();
	jQuery.post(ajaxurl, dataString ,function(response) {
		 if(response==1)
		       	alert("You do not have sufficient permissions");
		else{
		  var base_url = '<?php echo admin_url('admin.php?page=social-media-auto-publish-settings');?>';//msg - 
		 window.location.href = base_url+'&msg=6';
	}
		});
	}
	else if((obj1.xyz_ln_user_id) && (obj1.ln_pages))
	{
	var obj=obj1.ln_pages;
	var secretkey=obj1.secretkey;
	var smapsoln_userid=obj1.smapsoln_userid;
	var xyz_ln_user_id=obj1.xyz_ln_user_id;
	var xyz_smap_xyzscripts_hash_val=obj1.xyz_smap_xyzscripts_hash_val;
	var xyz_smap_xyzscripts_user_id=obj1.xyz_smap_xyzscripts_user_id;
	var xyz_smap_ln_selected_pages_nonce= '<?php echo wp_create_nonce('xyz_smap_ln_selected_pages_nonce');?>';
	var pages_obj = obj;
	var list='';
	for (var key in obj) {
	  if (obj.hasOwnProperty(key)) {
	    var val = obj[key];
	    list=list+"<input type='checkbox' value='"+key+"' checked='checked' disabled>"+val+"<br>";
}
	}
	jQuery("#xyz_smap_selected_pages_ln").html(list);
	jQuery("#xyz_smap_selected_pages_ln_tr").show();
	jQuery("#auth_message").hide();
	jQuery("#re_auth_message").show();
// 	var pages_obj = JSON.stringify(obj);
	var dataString = { 
			action: 'xyz_smap_ln_selected_pages_auto_update',
			pages: pages_obj ,
			smap_secretkey: secretkey,
			xyz_smap_premium_xyzscripts_user_id: xyz_smap_xyzscripts_user_id,
			smapsoln_userid:smapsoln_userid,
			xyz_ln_user_id:xyz_ln_user_id,
			xyz_smap_xyzscripts_hash_val:xyz_smap_xyzscripts_hash_val,
			dataType: 'json',
			_wpnonce: xyz_smap_ln_selected_pages_nonce
		};			
		jQuery("#re_auth_message").hide();
		jQuery("#auth_message").hide();
		jQuery("#ajax-save").show();
	jQuery.post(ajaxurl, dataString ,function(response) {
		 if(response==1)
		       	alert("You do not have sufficient permissions");
		else{
		  var base_url = '<?php echo admin_url('admin.php?page=social-media-auto-publish-settings');?>';//msg - 
		window.location.href = base_url+'&msg=7';
    }
		});

    }
	
	
}
</script>
	<?php 
?>
