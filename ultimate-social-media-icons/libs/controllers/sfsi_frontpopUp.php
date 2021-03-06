<?php 
  
/* show a pop on the as per user chose under section 7 */
function sfsi_frontPopUp () { 
     ob_start();
     echo sfsi_FrontPopupDiv();        
     echo  $output=ob_get_clean();
}
/* check where to be pop-shown */
function sfsi_check_PopUp($content)
{
     global $post; global $wpdb; 
     $sfsi_section7_options=  unserialize(get_option('sfsi_section7_options',false));
     if($sfsi_section7_options['sfsi_Show_popupOn']=="blogpage")
     {   
	   if(!is_feed() && !is_home() && !is_page()) {
		     $content=  sfsi_frontPopUp ().$content;
	     }
     }else if($sfsi_section7_options['sfsi_Show_popupOn']=="selectedpage")
     {
	 if(is_page() && in_array($post->ID,  unserialize($sfsi_section7_options['sfsi_Show_popupOn_PageIDs']))) {
		     $content=  sfsi_frontPopUp ().$content;
	     }
     }
      else if($sfsi_section7_options['sfsi_Show_popupOn']=="everypage") {
	 $content= sfsi_frontPopUp ().$content;
     }

     /* check for pop times */
     if($sfsi_section7_options['sfsi_Shown_pop']=="once")
     {
	$time_popUp=$sfsi_section7_options['sfsi_Shown_popupOnceTime'];
	$time_popUp=$time_popUp*1000;
	 ob_start();
	 ?>
     <script>
	    SFSI( document ).ready(function( $ ) {
	       
		setTimeout(function() {SFSI('.sfsi_outr_div').css({'z-index':'1000000',opacity:1}); SFSI('.sfsi_outr_div').fadeIn();},<?php echo $time_popUp; ?>);
		
	    });
	    
     </script>
     <?php 
     echo ob_get_clean();
     return $content;
     }
     if($sfsi_section7_options['sfsi_Shown_pop']=="ETscroll")
     {
	$time_popUp=$sfsi_section7_options['sfsi_Shown_popupOnceTime'];
	$time_popUp=$time_popUp*1000;
	    ob_start();
	 ?>
     <script>
	    SFSI( document ).scroll(function( $ ) {
	       
		 var y = jQuery(this).scrollTop();	
	    if(SFSI(window).scrollTop() + SFSI(window).height() >= SFSI(document).height()-3) {
	    SFSI('.sfsi_outr_div').css({'z-index':'1000000',opacity:1,top:SFSI(window).scrollTop()+200+"px",position:"absolute"});
	    SFSI('.sfsi_outr_div').fadeIn(200);
	   
	    
	 } else {
	
	     SFSI('.sfsi_outr_div').fadeOut();
	 }
		
	    });
     
     </script>
     <?php 
     echo ob_get_clean();
     }
     if($sfsi_section7_options['sfsi_Shown_pop']=="LimitPopUp")
     {
	 $time_popUp=$sfsi_section7_options['sfsi_Shown_popuplimitPerUserTime'];
	 $end_time=$_COOKIE['sfsi_socialPopUp']+($time_popUp*60); 
	
	$time_popUp=$time_popUp*1000;
     
	 if(!empty($end_time)) {
	 if($end_time<time())
	 {     
	 ?>
     <script>
	    SFSI( document ).ready(function( $ ) {
	       //SFSI('.sfsi_outr_div').fadeIn();
	       sfsi_setCookie('sfsi_socialPopUp',<?php echo time(); ?>,32);
		setTimeout(function() {SFSI('.sfsi_outr_div').css({'z-index':'1000000',opacity:1});SFSI('.sfsi_outr_div').fadeIn();},<?php echo $time_popUp; ?>);
		
	    });
     
     </script>
     <?php
	 }
     }
     echo ob_get_clean();
     }    

return $content;
}
/* make front end pop div */
function sfsi_FrontPopupDiv()
{
    global $wpdb;
    /* get all settings for icons saved in admin */
    $sfsi_section1_options=  unserialize(get_option('sfsi_section1_options',false));
    $custom_i=unserialize($sfsi_section1_options['sfsi_custom_files']);
    if($sfsi_section1_options['sfsi_rss_display']=='no' &&  $sfsi_section1_options['sfsi_email_display']=='no' && $sfsi_section1_options['sfsi_facebook_display']=='no' && $sfsi_section1_options['sfsi_twitter_display']=='no' &&  $sfsi_section1_options['sfsi_google_display']=='no' && $sfsi_section1_options['sfsi_share_display']=='no' && $sfsi_section1_options['sfsi_youtube_display']=='no' && $sfsi_section1_options['sfsi_pinterest_display']=='no' && $sfsi_section1_options['sfsi_linkedin_display']=='no' && empty($custom_i)) 
    {
     $icons='';return $icons;exit;
    }
    $sfsi_section7_options=  unserialize(get_option('sfsi_section7_options',false));
    $sfsi_section5=  unserialize(get_option('sfsi_section5_options',false));
    $sfsi_section4=  unserialize(get_option('sfsi_section4_options',false));
    /* calculate the width and icons display alignments */
    $heading_text=(isset($sfsi_section7_options['sfsi_popup_text'])) ? $sfsi_section7_options['sfsi_popup_text']: 'Enjoy this site? Please follow and like us!';
    $div_bgColor=(isset($sfsi_section7_options['sfsi_popup_background_color'])) ? $sfsi_section7_options['sfsi_popup_background_color']: '#fff';
    $div_FontFamily=(isset($sfsi_section7_options['sfsi_popup_font'])) ? $sfsi_section7_options['sfsi_popup_font']: 'Arial';
    $div_BorderColor=(isset($sfsi_section7_options['sfsi_popup_border_color'])) ? $sfsi_section7_options['sfsi_popup_border_color']: '#d3d3d3';
    $div_Fonttyle=(isset($sfsi_section7_options['sfsi_popup_fontStyle'])) ? $sfsi_section7_options['sfsi_popup_fontStyle']: 'normal';
    $div_FontColor=(isset($sfsi_section7_options['sfsi_popup_fontColor'])) ? $sfsi_section7_options['sfsi_popup_fontColor']: '#000';
    $div_FontSize=(isset($sfsi_section7_options['sfsi_popup_fontSize'])) ? $sfsi_section7_options['sfsi_popup_fontSize']: '26';
    $div_BorderTheekness=(isset($sfsi_section7_options['sfsi_popup_border_thickness'])) ? $sfsi_section7_options['sfsi_popup_border_thickness']: '1';
    $div_Shadow=(isset($sfsi_section7_options['sfsi_popup_border_shadow']) && $sfsi_section7_options['sfsi_popup_border_shadow']=="yes") ? $sfsi_section7_options['sfsi_popup_border_thickness']: 'no'; 
    
    $style="background-color:".$div_bgColor.";border:".$div_BorderTheekness."px solid".$div_BorderColor."; font-style:".$div_Fonttyle.";color:".$div_FontColor;
    if($sfsi_section7_options['sfsi_popup_border_shadow']=="yes")
    {
       $style.=";box-shadow:12px 30px 18px #CCCCCC;";
    }    
    $h_style="font-family:".$div_FontFamily.";font-style:".$div_Fonttyle.";color:".$div_FontColor.";font-size:".$div_FontSize."px";
    /* get all icons including custom icons */
    $custom_icons_order=unserialize($sfsi_section5['sfsi_CustomIcons_order']);
    $icons_order=array($sfsi_section5['sfsi_rssIcon_order']=>'rss',
                     $sfsi_section5['sfsi_emailIcon_order']=>'email',
                     $sfsi_section5['sfsi_facebookIcon_order']=>'facebook',
                     $sfsi_section5['sfsi_googleIcon_order']=>'google',
                     $sfsi_section5['sfsi_twitterIcon_order']=>'twitter',
                     $sfsi_section5['sfsi_shareIcon_order']=>'share',
                     $sfsi_section5['sfsi_youtubeIcon_order']=>'youtube',
                     $sfsi_section5['sfsi_pinterestIcon_order']=>'pinterest',
                     $sfsi_section5['sfsi_linkedinIcon_order']=>'linkedin',
		     $sfsi_section5['sfsi_instagramIcon_order']=>'instagram',
                    ) ;
  $icons=array();
  $elements=array();
  $icons=  unserialize($sfsi_section1_options['sfsi_custom_files']);
  if(is_array($icons))  $elements=array_keys($icons);
  $cnt=0;
  $total=count($custom_icons_order);
  if(!empty($icons) && is_array($icons)) :
  foreach($icons as $cn=>$c_icons)
  {    
      if(is_array($custom_icons_order) ) :
        if(in_array($custom_icons_order[$cnt]['ele'],$elements)) :   
            $key=key($elements);
            unset($elements[$key]);
         
            $icons_order[$custom_icons_order[$cnt]['order']]=array('ele'=>$cn,'img'=>$c_icons);
        else :
        $icons_order[]=array('ele'=>$cn,'img'=>$c_icons);
       endif;
        
       $cnt++;
      else :
      $icons_order[]=array('ele'=>$cn,'img'=>$c_icons);
      endif;
     
    }
  endif;  
    ksort($icons_order);     /* short icons in order to display */
    $icons='<div class="sfsi_outr_div" > <div class="sfsi_FrntInner" style="'.$style.'">';
	 if(!empty($heading_text))
	 {
	 $icons.='<h2 style="'.$h_style.'">'.$heading_text.'</h2>';
	 
	 }
     $ulmargin="";
     if($sfsi_section4['sfsi_display_counts']=="no")
     {
	  $ulmargin="margin-bottom:0px";
     }
     /* make icons with all settings saved in admin  */
     $icons.='<ul style="'.$ulmargin.'">';
    foreach($icons_order  as $index=>$icn) :
        
    if(is_array($icn)) { $icon_arry=$icn; $icn="custom" ; } 
    switch ($icn) :     
    case 'rss' :  if($sfsi_section1_options['sfsi_rss_display']=='yes')  $icons.= "<li>".sfsi_prepairIcons('rss',1)."</li>";  
    break;
    case 'email' :   if($sfsi_section1_options['sfsi_email_display']=='yes')   $icons.= "<li>".sfsi_prepairIcons('email',1)."</li>"; 
    break;
    case 'facebook' :  if($sfsi_section1_options['sfsi_facebook_display']=='yes') $icons.= "<li>".sfsi_prepairIcons('facebook',1)."</li>";
    break;
    case 'google' :  if($sfsi_section1_options['sfsi_google_display']=='yes')    $icons.= "<li>".sfsi_prepairIcons('google',1)."</li>";
    break;
    case 'twitter' :  if($sfsi_section1_options['sfsi_twitter_display']=='yes')    $icons.= "<li>".sfsi_prepairIcons('twitter',1)."</li>"; 
    break;
    case 'share' :  if($sfsi_section1_options['sfsi_share_display']=='yes')    $icons.= "<li id='SFshareIcon'>".sfsi_prepairIcons('share',1)."</li>";                                                                                                                                                                                                    
    break;
    case 'youtube' :  if($sfsi_section1_options['sfsi_youtube_display']=='yes')     $icons.= "<li>".sfsi_prepairIcons('youtube',1)."</li>"; 
    break;
    case 'pinterest' :   if($sfsi_section1_options['sfsi_pinterest_display']=='yes')     $icons.= "<li>".sfsi_prepairIcons('pinterest',1)."</li>";
    break;
    case 'linkedin' :  if($sfsi_section1_options['sfsi_linkedin_display']=='yes')    $icons.= "<li>".sfsi_prepairIcons('linkedin',1)."</li>"; 
    break;
    case 'instagram' :  if($sfsi_section1_options['sfsi_instagram_display']=='yes')    $icons.= "<li>".sfsi_prepairIcons('instagram',1)."</li>"; 
    break;
    case 'custom' : $icons.= "<li>". sfsi_prepairIcons($icon_arry['ele'],1)."</li>"; 
    break;    
    endswitch;
    endforeach;    
    $icons.='</ul></div ></div >';
    
    return $icons;
}

?>