<?php
  /* unserialize all saved option for second section options */
$option4=  unserialize(get_option('sfsi_section4_options',false));
    $option2=  unserialize(get_option('sfsi_section2_options',false));
  
?>
<!-- Section 2 "What do you want the icons to do?" main div Start -->
<div class="tab2">
      <!-- RSS ICON -->
    <div class="row bdr_top rss_section">
    <h2 class="rs_s">RSS</h2>
        <div class="inr_cont">
            <p>When clicked on, users can subscribe via RSS</p>
            <div class="rss_url_row">
                <h4>RSS URL</h4> <input name="sfsi_rss_url"  id="sfsi_rss_url" class="add"   type="url" value="<?php echo ($option2['sfsi_rss_url']!='') ?  $option2['sfsi_rss_url'] : '' ;?>" placeholder="E.g http://www.yoursite.com/feed" class="add" /> <span class="sfrsTxt" >For most blogs it’s <strong>http://blogname.com/feed</strong></span>
            </div>
        </div>    
    </div><!-- END RSS ICON -->
    
    <!-- EMAIL ICON -->
    <?php $feedId=get_option('sfsi_feed_id',false);?>
    <div class="row email_section">
        <h2 class="email">Email</h2>
        <div class="inr_cont">
         <p>When people click on this icon, they will see <a href="http://www.specificfeeds.com/widget/emailsubscribe/<?php echo base64_encode($feedId); ?>/<?php echo base64_encode(8); ?>" target="_new">your subscription screen</a> where they can select which messages they want to receive from your RSS feed (by email).  The service is 100% FREE, fully automatic and also makes sense if you already offer an email newsletter <a href="http://specificfeeds.com/rss" target="_new">(learn more)</a>. </p>
         <p>As this service is powered by SpecificFeeds we suggest to use the SpecificFeeds-icon, however you can also decide to show a regular email icon:</p>
           <ul class="tab_2_email_sec">
            <li><input name="sfsi_rss_icons" <?php echo ($option2['sfsi_rss_icons']=='sfsi') ?  'checked="true"' : '' ;?> type="radio" value="sfsi" class="styled"  /><span class="sf_arow"></span><label>Use SpecificFeeds icon</label></li>
            <li><input name="sfsi_rss_icons" <?php echo ($option2['sfsi_rss_icons']=='email') ?  'checked="true"' : '' ;?> type="radio" value="email" class="styled" /><span class="email_icn"></span><label>Use Email icon</label></li>
          </ul>
        </div>
    </div><!-- END EMAIL ICON -->
    
     <!-- FACEBOOK ICON -->
    <div class="row facebook_section">
    <h2 class="facebook">Facebook</h2>
        <div class="inr_cont">
        <p>The facebook icon can perform several actions. Pick below which ones it should perform. If you select several options, then users can select what they want to do <a class="rit_link pop-up" href="javascript:;"  data-id="fbex-s2">(see an example)</a>.</p>
        <p>The facebook icon should allow users to...</p> 
        
        <p class="radio_section fb_url"><input name="sfsi_facebookPage_option" <?php echo ($option2['sfsi_facebookPage_option']=='yes') ?  'checked="true"' : '' ;?>  type="checkbox" value="yes" class="styled"  /><label>Visit my Facebook page at:</label><input name="sfsi_facebookPage_url" type="url" placeholder="http://" value="<?php echo ($option2['sfsi_facebookPage_url']!='') ?  $option2['sfsi_facebookPage_url'] : 'http://' ;?>" placeholder="E.g https://www.facebook.com/your_page_name" class="add" /></p>
        
        <p class="radio_section fb_url extra_sp"><input name="sfsi_facebookLike_option" <?php echo ($option2['sfsi_facebookLike_option']=='yes') ?  'checked="true"' : '' ;?>  type="checkbox" value="yes" class="styled"  /><label>Like my blog on Facebook (+1)</label></p>
        
        <p class="radio_section fb_url extra_sp"><input name="sfsi_facebookShare_option" <?php echo ($option2['sfsi_facebookShare_option']=='yes') ?  'checked="true"' : '' ;?>  type="checkbox" value="yes" class="styled"  /><label>Share my blog with friends (on Facebook)</label></p>
        
        </div>
    </div><!-- END FACEBOOK ICON -->
    
   <!-- TWITTER ICON -->
    <div class="row twitter_section">
    <h2 class="twt">Twitter</h2>
        <div class="inr_cont twt_tab_2">
         <p>The Twitter icon can perform several actions. Pick below which ones it should perform. If you select several options, then users can select what they want to do <a class="rit_link pop-up" href="javascript:;"  data-id="twex-s2">(see an example)</a>.</p> 
         <p>The Twitter icon should allow users to...</p> 
         <div class="radio_section fb_url twt_fld"><input name="sfsi_twitter_followme"  <?php echo ($option2['sfsi_twitter_followme']=='yes') ?  'checked="true"' : '' ;?> type="checkbox" value="yes" class="styled"  /><label>Follow me on Twitter:</label><input name="sfsi_twitter_followUserName" type="text" value="<?php echo ($option2['sfsi_twitter_followUserName']!='') ?  $option2['sfsi_twitter_followUserName'] : '' ;?>" placeholder="@my_twitter_name" class="add" /></div>
         <div class="radio_section fb_url twt_fld_2"><input name="sfsi_twitter_aboutPage" <?php echo ($option2['sfsi_twitter_aboutPage']=='yes') ?  'checked="true"' : '' ;?> type="checkbox" value="yes" class="styled"  /><label>Tweet about my page:</label><textarea name="sfsi_twitter_aboutPageText" id="sfsi_twitter_aboutPageText" type="text" class="add_txt" placeholder="Hey, check out this cool site I found: www.yourname.com #Topic via@my_twitter_name" /><?php echo ($option2['sfsi_twitter_aboutPageText']!='') ?  $option2['sfsi_twitter_aboutPageText'] : 'Hey check out this cool site I found' ;?></textarea></div>
        </div>
    </div><!-- END TWITTER ICON -->
    
      <!-- GOOGLE ICON -->
    <div class="row google_section">
    <h2 class="ggle_pls">Google+</h2>
        <div class="inr_cont google_in">
        <p>The Google+ icon can perform several actions. Pick below which ones it should perform. If you select several options, then users can select what they want to do <a class="rit_link pop-up" href="javascript:;"  data-id="googlex-s2">(see an example)</a>.</p> 
        <p>The Google+ icon should allow users to...</p> 
        <p class="radio_section fb_url"><input name="sfsi_google_page" <?php echo ($option2['sfsi_google_page']=='yes') ?  'checked="true"' : '' ;?> type="checkbox" value="yes" class="styled"  /><label>Visit my Google+ page at:</label><input name="sfsi_google_pageURL" type="url" placeholder="http://" value="<?php echo ($option2['sfsi_google_pageURL']!='') ?  $option2['sfsi_google_pageURL'] : '' ;?>" class="add" /></p>
        <p class="radio_section fb_url"><input name="sfsi_googleLike_option" <?php echo ($option2['sfsi_googleLike_option']=='yes') ?  'checked="true"' : '' ;?> type="checkbox" value="yes" class="styled"  /><label>Like my blog on Google+ (+1)</label></p> 
        <p class="radio_section fb_url"><input name="sfsi_googleShare_option" <?php echo ($option2['sfsi_googleShare_option']=='yes') ?  'checked="true"' : '' ;?> type="checkbox" value="yes" class="styled"  /><label>Share my blog with friends (on Google+)</label></p>
        </div>
    </div><!-- END GOOGLE ICON -->
    
    <!-- YOUTUBE ICON -->
    <div class="row youtube_section">
    <h2 class="utube">Youtube</h2>
        <div class="inr_cont utube_inn">
        <p>The Youtube icon can perform several actions. Pick below which ones it should perform. If you select several options, then users can select what they want to do  <a class="rit_link pop-up" href="javascript:;"  data-id="ytex-s2">(see an example)</a>.</p> 
        <p>The youtube icon should allow users to... </p> 
        <p class="radio_section fb_url"><input name="sfsi_youtube_page" <?php echo ($option2['sfsi_youtube_page']=='yes') ?  'checked="true"' : '' ;?> type="checkbox" value="yes" class="styled"  /><label>Visit my Youtube page at:</label><input name="sfsi_youtube_pageUrl" type="url" placeholder="http://" value="<?php echo ($option2['sfsi_youtube_pageUrl']!='') ?  $option2['sfsi_youtube_pageUrl'] : '' ;?>" class="add" /></p>
        <p class="radio_section fb_url"><input name="sfsi_youtube_follow" <?php echo ($option2['sfsi_youtube_follow']=='yes') ?  'checked="true"' : '' ;?> type="checkbox" value="yes" class="styled"  /><label>Subscribe me on Youtube <span>(Allows people to Subscribe you directly, without leaving your blog)</span></label></p>
        <p class="extra_pp"><label>Enter username:</label><input name="sfsi_ytube_user" type="url" value="<?php echo (isset($option4['sfsi_youtube_user']) && $option4['sfsi_youtube_user']!='') ?  $option4['sfsi_youtube_user'] : '' ;?>" placeholder="youtube_user_name" class="add" /></p>
        </div>
    </div><!-- END YOUTUBE ICON -->
    
     <!-- PINTEREST ICON -->
    <div class="row pinterest_section">
    <h2 class="pinterest">Pinterest</h2>
        <div class="inr_cont">
        <p>The Pinterest icon can perform several actions. Pick below which ones it should perform. If you select several options, then users can select what they want to do   <a class="rit_link pop-up" href="javascript:;"  data-id="pinex-s2">(see an example)</a>.</p> 
        <p>The Pinterest icon should allow users to... </p> 
        <p class="radio_section fb_url"><input name="sfsi_pinterest_page" <?php echo ($option2['sfsi_pinterest_page']=='yes') ?  'checked="true"' : '' ;?>  type="checkbox" value="yes" class="styled"  /><label>Visit my Pinterest page at:</label><input name="sfsi_pinterest_pageUrl" type="url" placeholder="http://"  value="<?php echo ($option2['sfsi_pinterest_pageUrl']!='') ?  $option2['sfsi_pinterest_pageUrl'] : '' ;?>" class="add" /></p>
        <div class="pint_url">
        <p class="radio_section fb_url"><input name="sfsi_pinterest_pingBlog" <?php echo ($option2['sfsi_pinterest_pingBlog']=='yes') ?  'checked="true"' : '' ;?>  type="checkbox" value="yes" class="styled"  /><label>Pin my blog on Pinterest (+1)</label></p></div>
        </div>
    </div><!-- END PINTEREST ICON -->
    
    <!-- INSTAGRAM ICON -->
    <div class="row instagram_section">
    <h2 class="instagram">Instagram</h2>
        <div class="inr_cont">
        <p>When clicked on, users will get directed to your Instagram page.</p> 
        <p class="radio_section fb_url  cus_link instagram_space" ><label>URL</label><input name="sfsi_instagram_pageUrl" type="text" value="<?php echo (isset($option2['sfsi_instagram_pageUrl']) && $option2['sfsi_instagram_pageUrl']!='') ?  $option2['sfsi_instagram_pageUrl'] : '' ;?>" placeholder="http://" class="add"  /></p>        
        </div>
    </div><!-- END INSTAGRAM ICON -->
    
     <!-- LINKEDIN ICON -->
    <div class="row linkedin_section">
    <h2 class="linkdin">LinkedIn</h2>
        <div class="inr_cont linked_tab_2 link_in">
        <p>The LinkedIn icon can perform several actions. Pick below which ones it should perform. If you select several options, then users can select what they want to do <a class="rit_link pop-up" href="javascript:;"  data-id="linkex-s2">(see an example)</a>.</p> 
        <p>The LinkedIn icon should allow users to... </p> 
        <div class="radio_section fb_url link_1"><input name="sfsi_linkedin_page" <?php echo ($option2['sfsi_linkedin_page']=='yes') ?  'checked="true"' : '' ;?> type="checkbox" value="yes" class="styled"  /><label>Visit my Linkedin page at:</label><input name="sfsi_linkedin_pageURL" type="text" placeholder="http://" value="<?php echo ($option2['sfsi_linkedin_pageURL']!='') ?  $option2['sfsi_linkedin_pageURL'] : '' ;?>" class="add" /></div>
        <div class="radio_section fb_url link_2"><input name="sfsi_linkedin_follow" <?php echo ($option2['sfsi_linkedin_follow']=='yes') ?  'checked="true"' : '' ;?> type="checkbox" value="yes" class="styled"  /><label>Follow me on Linkedin: </label><input name="sfsi_linkedin_followCompany" type="text" value="<?php echo ($option2['sfsi_linkedin_followCompany']!='') ?  $option2['sfsi_linkedin_followCompany'] : '' ;?>" class="add" placeholder="Enter company ID, e.g. 123456" /></div>
        <div class="radio_section fb_url link_3"><input name="sfsi_linkedin_SharePage" <?php echo ($option2['sfsi_linkedin_SharePage']=='yes') ?  'checked="true"' : '' ;?> type="checkbox" value="yes" class="styled"  /><label>Share my page on LinkedIn</label></div>
        <div class="radio_section fb_url link_4"><input name="sfsi_linkedin_recommendBusines" <?php echo ($option2['sfsi_linkedin_recommendBusines']=='yes') ?  'checked="true"' : '' ;?> type="checkbox" value="yes" class="styled"  /><label class="anthr_labl">Recommend my business or product on Linkedin</label><input name="sfsi_linkedin_recommendProductId" type="text" value="<?php echo ($option2['sfsi_linkedin_recommendProductId']!='') ?  $option2['sfsi_linkedin_recommendProductId'] : '' ;?>" class="add link_dbl" placeholder="Enter Product ID, e.g. 1441" /> <input name="sfsi_linkedin_recommendCompany" type="text" value="<?php echo ($option2['sfsi_linkedin_recommendCompany']!='') ?  $option2['sfsi_linkedin_recommendCompany'] : '' ;?>" class="add" placeholder="Enter company name, e.g. Google”" /></div>
        </div>
    </div><!-- END LINKEDIN ICON -->
    
    <!-- share button -->
    <div class="row share_section">
    <h2 class="share">Share</h2>
        <div class="inr_cont">
        <p>Nothing needs to be done here – your visitors to share your site via «all the other» social media sites.  <a class="rit_link pop-up" href="javascript:;"  data-id="share-s2">(see an example).</a></p> 
        </div>
    </div>
    <!-- share end -->
    <!-- Custom icon section start here -->
   <div class="custom-links custom_section">
	<?php 
	  $costom_links=  unserialize($option2['sfsi_CustomIcon_links']);
	  $count=1; for($i=$first_key;$i<=$endkey;$i++) : ?> 
       <?php if(!empty( $icons[$i])) : ?>
       <div class="row  sfsiICON_<?php echo $i; ?> cm_lnk">
       
       <h2 class="custom"><span class="customstep2-img"><img   src="<?php echo (!empty($icons[$i])) ?  $icons[$i] : SFSI_PLUGURL.'images/custom.png';?>" id="CImg_<?php echo $new_element; ?>" style="border-radius:48%"  /> </span> <span class="sfsiCtxt">Custom <?php echo $count; ?></span></h2>
	   <div class="inr_cont ">
	   <p>Where do you want this icon to link to?</p> 
	 
	   <p class="radio_section fb_url custom_section cus_link " ><label>Link :</label><input name="sfsi_CustomIcon_links[]" type="text" value="<?php echo (isset($costom_links[$i]) && $costom_links[$i]!='') ?  $costom_links[$i] : '' ;?>" placeholder="http://" class="add" file-id="<?php echo $i; ?>" /></p>
	
	   </div>
       </div>
	 <?php $count++; endif; endfor; ?>
     </div> <!-- END Custom icon section here -->
      
     <!-- SAVE BUTTON SECTION   --> 
    <div class="save_button tab_2_sav">
        <img src="<?php echo SFSI_PLUGURL; ?>images/ajax-loader.gif" class="loader-img" />
        <a href="javascript:;" id="sfsi_save2" title="Save">Save</a>
    </div><!-- END SAVE BUTTON SECTION   -->
    <a class="sfsiColbtn closeSec" href="javascript:;" class="closeSec">Collapse area</a>
    <label class="closeSec"></label>
    <!-- ERROR AND SUCCESS MESSAGE AREA-->
    <p class="red_txt errorMsg" style="display:none"> </p>
    <p class="green_txt sucMsg" style="display:none"> </p>
    
</div><!-- END Section 2 "What do you want the icons to do?" main div -->