<?php 

/* social helper class include all function which are used to intract with  */

class sfsi_SocialHelper {
private $url,$timeout=10;

/* get twitter followers */
function sfsi_get_tweets($username,$tw_settings) {
    
   require_once(SFSI_DOCROOT.'/helpers/twitter-api/twitteroauth.php');
    $settings = array(
    'oauth_access_token' => "335692958-JuqG7ArGrblrccHl3veVRFOdg64BUQZ7XpIs8x3Q",
    'oauth_access_token_secret' => "A1l0LMrAVb3UeBbkpgigQr8O1EgfPcfG5USWg8cTcQyvg",
    'consumer_key' => "d8OCu7GokBpy7DT17L5X1Q",
    'consumer_secret' => "HUUEHS5rVSzaY57tICF9dVIaJ3bC5vwSZR9gWqq8QQ"
    );
    // Replace the four parameters below with the information from your Twitter developer application.
    $twitterConnection = new TwitterOAuth($tw_settings['tw_consumer_key'],$tw_settings['tw_consumer_secret'], $tw_settings['tw_oauth_access_token_secret']);

    // Send the API request
    $twitterData = $twitterConnection->get('users/show', array('screen_name' =>$username));
    // Extract the follower and tweet counts
    $followerCount = $twitterData->followers_count;
    return $followerCount;
}
/* get linkedIn counts */
function sfsi_get_linkedin($url) {
   $json_string = $this->file_get_contents_curl("http://www.linkedin.com/countserv/count/share?url=$url&format=json");
   $json = json_decode($json_string, true);
   return isset($json['count'])? intval($json['count']):0;
}
/* get linkedIn follower */
function sfsi_getlinkedin_follower($ln_company,$APIsettings)
{      
   require_once(SFSI_DOCROOT.'/helpers/linkedin-api/linkedin-api.php');
   $url='http'.(empty($_SERVER['HTTPS'])?'':'s').'://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
   $linkedin = new LinkedIn($APIsettings['ln_api_key'], $APIsettings['ln_secret_key'],$APIsettings['ln_oAuth_user_token'], $url );
   $followers = $linkedin->getCompanyFollowersByName($ln_company); 
   return  strip_tags($followers);
}
/* get facebook likes */
function sfsi_get_fb($url) {
   $json_string = $this->file_get_contents_curl('http://api.facebook.com/restserver.php?method=links.getStats&format=json&urls='.$url);
   $json = json_decode($json_string, true);
   return isset($json[0])? $json[0]:0;
}
/* get google+ follwers  */
function sfsi_get_google($url,$google_api_key)
{   
   if(filter_var($url, FILTER_VALIDATE_URL) && !empty($google_api_key))
  {
    $url=parse_url($url);
    $url_path=explode('/',$url['path']);
    if(isset($url_path))
    {     end($url_path);
          $key=key($url_path);
          
    $page_id = $url_path[$key];
    }
    if($this->sfsi_get_http_response_code("https://www.googleapis.com/plus/v1/people/$page_id?key=$google_api_key")!="404")
    {        
        $data = $this->file_get_contents_curl("https://www.googleapis.com/plus/v1/people/$page_id?key=$google_api_key");     
        $data = json_decode($data, true);
      
        return $this->format_num($data['circledByCount']); 
    }
    else
    {
        return 0;
    }    
   }
 else {
      return 0;
  }
    


}
/* get google+ likes */
function sfsi_getPlus1($url) {
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, "https://clients6.google.com/rpc");
  curl_setopt($curl, CURLOPT_POST, 1);
  curl_setopt($curl, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"' . $url . '","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]');
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
  $curl_results = curl_exec ($curl);
  curl_close ($curl);
  $json = json_decode($curl_results, true);
  
  return intval( $json[0]['result']['metadata']['globalCounts']['count'] );
}
/* get youtube subscribers  */
function sfsi_get_youtube($user)
{
    
    $xmlData = @file_get_contents('http://gdata.youtube.com/feeds/api/users/' . $user);
    
    if($xmlData)
    {   
        $xmlData = str_replace('yt:', 'yt', $xmlData);
        $xml = new SimpleXMLElement($xmlData);
        $subs = $xml->ytstatistics['subscriberCount'];
        $subs=$this->format_num($subs);
    }
    else
    {
        $subs=0;
    }    
    return $subs;
}
/* get addthis counts  */
function sfsi_get_atthis()
{
    $url='http'.(empty($_SERVER['HTTPS'])?'':'s').'://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
    $json_string = @file_get_contents('http://api-public.addthis.com/url/shares.json?url='.$url);
    $json = json_decode($json_string, true);
    return isset($json['shares'])? $this->format_num((int) $json['shares']):0;
    
}
 /* get pinit counts  */       
function sfsi_get_pinterest($url) {
$return_data = $this->file_get_contents_curl('http://api.pinterest.com/v1/urls/count.json?callback=receiveCount&url='.$url);
$json_string = preg_replace('/^receiveCount\((.*)\)$/', "\\1", $return_data);
$json = json_decode($json_string, true);
return isset($json['count'])?intval($json['count']):0;
}
 /* get pinit counts for a user  */
function get_UsersPins($user_name,$board)
{   
    $query=$user_name.'/'.$board;
    $url_respon=$this->sfsi_get_http_response_code('http://api.pinterest.com/v3/pidgets/boards/'.$query.'/pins/');
    if($url_respon!=404)
    {    
    $return_data = $this->file_get_contents_curl('http://api.pinterest.com/v3/pidgets/boards/'.$query.'/pins/');
    $json_string = preg_replace('/^receiveCount\((.*)\)$/', "\\1", $return_data);
    $json = json_decode($json_string, true);
    }
    else
    {
        $json['data']['user']['pin_count']=0;
    }    
    return isset($json['data']['user']['pin_count'])? intval($json['data']['user']['pin_count']):0;
}

 /* send curl request   */
private function file_get_contents_curl($url){

$ch=curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
curl_setopt($ch, CURLOPT_FAILONERROR, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
$cont = curl_exec($ch);
if(curl_error($ch))
{
//die(curl_error($ch));
}
return $cont;
}
 /* convert no. to 2K,3M format   */
function format_num($num, $precision = 0) {
   if ($num >= 1000 && $num < 1000000) {
    $n_format = number_format($num/1000,$precision).'k';
    } else if ($num >= 1000000 && $num < 1000000000) {
    $n_format = number_format($num/1000000,$precision).'m';
   } else if ($num >= 1000000000) {
   $n_format=number_format($num/1000000000,$precision).'b';
   } else {
   $n_format = $num;
    }
  return $n_format;
  }
  
  /* create on page facebook links option */
public function sfsi_FBlike($permalink) {
        $send = 'false';
        $width = 180;
        $show_count=0;
        $fb_like_html = '<fb:like href="'.$permalink.'" width="'.$width.'" send="'.$send.'" showfaces="false" ';
        if($show_count) { 
                $fb_like_html .= 'layout="button"';
        } else {
                $fb_like_html .= 'layout="button"';
        }
        $fb_like_html .= ' action="like"></fb:like>';
        return $fb_like_html;exit;
}
  /* create on page facebook share option */
 public function sfsiFB_Share($permalink) {
		$fb_share_html = '<fb:share-button href="'.$permalink.'" width="140" ';
                $fb_share_html .= 'type="button"';
		$fb_share_html .= '></fb:share-button>';
		return $fb_share_html;
	}
  /* create on page google share option */      
  public function sfsi_Googlelike($permalink) {
      $show_count=0;  
      $google_html = '<div class="g-plusone" data-href="' . $permalink . '" ';
        if($show_count) {
                $google_html .= 'data-size="bubble" ';
        } else {
                $google_html .= 'data-size="large" data-annotation="none" ';
        }
        $google_html .= '></div>';
        return $google_html;
}      
  /* create on page google share option */      
  public function sfsi_GoogleShare($permalink) {
      $show_count=1;
      
      $google_html = '<div class="g-plus" data-action="share" data-annotation="none" data-height="24" data-href="'.$permalink.'">' . $permalink . '"></div>';
        return $google_html;
}
 /* create on page twitter follow option */ 
 public function sfsi_twitterFollow($tw_username) {
               
		$twitter_html = '<a href="https://twitter.com/'.$tw_username.'" class="twitter-follow-button"  data-show-count="false" >Follow </a>';
		
		return $twitter_html;
	} 
 /* create on page twitter share icon */
 public function sfsi_twitterShare($permalink,$tweettext) {
		$twitter_html = '<a rel="nofollow" href="http://twitter.com/share" data-count="none" class="sr-twitter-button twitter-share-button" lang="en" data-url="'.$permalink.'" data-text="'.$tweettext.'" ></a>';
	               
                return $twitter_html;
	} 
 /* create on page youtube subscribe icon */       
 public function sfsi_YouTubeSub($yuser) {
		$youtube_html = '<div class="g-ytsubscribe" data-channel="'.$yuser.'" data-layout="default" data-count="hidden"></div>';
		
		return $youtube_html;
	}  
 /* create on page pinit button icon */      
public function sfsi_PinIt($url='') {       
	$pin_it_html = '<a href="//www.pinterest.com/pin/create/button/" data-pin-do="buttonBookmark" ><img src="//assets.pinterest.com/images/pidgets/pinit_fg_en_rect_gray_20.png" /></a>';
	return $pin_it_html;
}
 /* get instragram followers */
public function sfsi_get_instagramFollowers($user_name)
{
    /* get instagram user id */
    $return_data = $this->file_get_contents_curl('http://jelled.com/ajax/instagram?do=username&username='.$user_name.'&format=json');
    $json_string = preg_replace('/^receiveCount\((.*)\)$/', "\\1", $return_data);
    $json = json_decode($json_string, true);
    $user_id=$json['data'][0]['id']; 
    $return_data = $this->file_get_contents_curl('https://api.instagram.com/v1/users/'.$user_id.'/?access_token=53042481.ab103e5.0c6f8f50471a4e1f97595f8db529a47a');
    $json_string = preg_replace('/^receiveCount\((.*)\)$/', "\\1", $return_data);
    $json = json_decode($json_string, true);
    return $this->format_num($json['data']['counts']['followed_by'],0);

}
 /* create linkedIn  follow button */
 public function sfsi_LinkedInFollow($company_id)
 {
    return  $ifollow='<script type="IN/FollowCompany" data-id="'.$company_id.'" data-counter="none"></script>';
 }
  /* create linkedIn  recommend button */
 public function sfsi_LinkedInRecommend($company_name,$product_id)
 {
    return  $ifollow='<script type="IN/RecommendProduct" data-company="'.$company_name.'" data-product="'.$product_id.'"></script>';
 }
 /* create linkedIn  share button */
  public function sfsi_LinkedInShare($url='')
 {
      $url=(isset($url))? $url :  home_url();
      return  $ifollow='<script type="IN/Share" data-url="'.$url.'"></script>';
 }
 /* get no of subscribers from specificfeeds for current blog */
public function  SFSI_getFeedSubscriber($feedid)
{
    $curl = curl_init();  
     
    curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => 'http://www.specificfeeds.com/wordpress/countsubscriber',
        CURLOPT_USERAGENT => 'sf rss request',
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => array(
            'feed_id' => $feedid
        )
    ));
     /* Send the request & save response to $resp */
        $resp = curl_exec($curl);
        $resp=json_decode($resp);
        curl_close($curl);
          $feeddata=stripslashes_deep($resp->subscriber_count);
         return $this->format_num($feeddata);exit;
         
}
    /* check response from a url */
    private function sfsi_get_http_response_code($url) {
    $headers = get_headers($url);
    return substr($headers[0], 9, 3);
}
  
}/* end of class */
 
		
?>