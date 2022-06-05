<?php
/**
 * Uji Countdown Front
 *
 * Handles front-end/shorcodes
 *
 * @author   WPmanage
 * @category Front
 * @package  Uji-Countdown/Classes
 * @version  2.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class UjiCountdown extends Uji_Countdown {
       
    /**
     * Init vars
     *
     * @since     2.0
     */
    public static function uji_vars() {
        return array(   'class' => 'ujic_pos',
                        'ujic_style' => 'ujic_style',
                        'ujic_txt_size' => 'ujic_size',
                        'ujic_col_dw' => 'ujic_col_dw',
                        'ujic_col_up' => 'ujic_col_up',
                        'ujic_col_txt' => 'ujic_col_txt',
                        'ujic_col_sw' => 'ujic_col_sw',
                        'ujic_col_lab' => 'ujic_col_lab',
                        'ujic_lab_sz' => 'ujic_lab_sz',
                        'ujic_thick' => 'ujic_thick',
                        'ujic_txt' => 'ujic_txt',
                        'ujic_ani' => 'ujic_ani',
                        'ujic_d' => 'ujic_d',
                        'ujic_h' => 'ujic_h',
                        'ujic_m' => 'ujic_m',
                        'ujic_s' => 'ujic_s',
                        'ujic_y' => 'ujic_y',
                        'ujic_o' => 'ujic_o',
                        'ujic_w' => 'ujic_w',
                        'ujic_goof' => 'ujic_goof',
                        'ujic_post' => 'time');
    }

    /**
     * Initialize the plugin frontend.
     *
     * @since     2.0
     */
    public function __construct() {
        //add the shortcode
        add_shortcode( 'ujicountdown', array( $this, 'ujic_shortcode' ) );

    }

    /**
     * The shortcode
     *
     * @since    2.0
     */
    public function ujic_shortcode( $atts, $content = null ) {
        extract( shortcode_atts( array(
                       // 'style' => "classic",
                        'id' => "",
                        'expire' => "",
                        'timer' => "",
                        'hide' => "",
                        'url' => "",
                        'subscr' => "",
                        'recurring' => "",
                        'rectype' => "",
                        'repeats' => ""
                        ), $atts ) );
       
        //Increment counters
        static $ujic_count = 0;
        $ujic_count++;

        $rectime = false;

        //2015/03/24 05:05
        $unx_time = strtotime( $expire . ":00" );
        $now_time = (int) current_time( 'timestamp' );
        
        $expired = ($now_time > $unx_time) ? true : false;
        
        //Reccuring time
        if( $expired && $rectype && $recurring && is_numeric( $recurring ) ) {
            //add multiple hour -> hours
            $rectype = intval($recurring) > 1 ? $rectype.'s' : $rectype;
             
            //Repeats
            if( $repeats && intval($repeats) > 0 ){
                //add time
                for( $t=1; $t<=intval($repeats); $t++){
                    $ujictime = strtotime( '+' . $t . ' ' . $rectype, $unx_time ); 
                    if( $now_time < $ujictime){
                        $rectime = true;
                        break;
                    }
                }
            }else{
                 //init time
                 $ujictime = strtotime( '+' . $recurring . ' ' . $rectype, $unx_time );
                 $t = 1;
                 //repeat unlimited times
                 while( $now_time > $ujictime){
                     $ujictime = strtotime( '+' . ($recurring*$t) . ' ' . $rectype, $unx_time );
                     $t++;
                 }
                 $rectime = true;
            }
        } else{
                if( $expired && $url){
                        $this->expired_redirect($url);
                }
        }
        
        //End Reccuring

        if ( ($hide == "true" && $now_time > $unx_time && !$rectime && !$timer) || ( $ujic_count > 1 && !$this->ujic_pro() ) ) {
            
            return $content;
            
        } else {
            
            //reccuring time
            if($rectime)
                $expire = date('Y/m/d H:i', $ujictime); //2015/03/24 05:05
            
            $uji_mc =  apply_filters('ujic_count_timers', $ujic_count);

            //get all vars
            $get_vars = self::uji_vars();

            foreach ($get_vars as $nm => $var){
                 ${$nm} = $this->sel_ujic_db( $id, $var );
                 $storeVal[$nm] = ${$nm};
            }

            $ujic_id = ($uji_mc) ? 'ujiCountdown' . $ujic_count : 'ujiCountdown';
            $classh = !empty( $ujic_style ) ? ' ujic-' . $ujic_style : '';
            $hclass =!empty( $class ) ? ' ujic_' . $class . '' : '';
            
            //Days Cicle
            $exp_time = strtotime($expire);
            $post_time = strtotime($ujic_post);
            $difference =  $exp_time - $post_time;
            $difference = ($difference < 0) ? $difference = 0 : $difference;
            $exp_d =  floor($difference/60/60/24);
            $exp_days = !empty($exp_d) ? $exp_d : "2000";

            //enqueue
            //wp_enqueue_style( $this->ujic_pro_sx().'-uji-countdown' );
            wp_enqueue_style('ujicountdown-uji-countdown');
            wp_enqueue_script( $this->ujic_pro_sx().'-core' );
            $ujic_count = ($uji_mc) ? $ujic_count : '';
            wp_localize_script( $this->ujic_pro_sx().'-core', 'ujiCount'.$ujic_count, apply_filters( 'ujic_front_localize_script', array(
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'uji_plugin' => plugins_url(),
                'uji_style' => $ujic_style,
                'ujic_id' => $ujic_id,
                'expire' => $expire,
                'timer' => $timer,
                'exp_days'=> $exp_days,
                'Years' => ( $this->ujic_get_option('ujic_years') ) ? $this->ujic_get_option('ujic_years')  : __( "Years", 'ujicountdown' ),
                'Year' => ( $this->ujic_get_option('ujic_year') ) ? $this->ujic_get_option('ujic_year')  : __( "Year", 'ujicountdown' ),
                'Months' => ( $this->ujic_get_option('ujic_months') ) ? $this->ujic_get_option('ujic_months')  : __( "Months", 'ujicountdown' ),
                'Month' => ( $this->ujic_get_option('ujic_month') ) ? $this->ujic_get_option('ujic_month')  : __( "Month", 'ujicountdown' ),
                'Weeks' => ( $this->ujic_get_option('ujic_weeks') ) ? $this->ujic_get_option('ujic_weeks')  : __( "Weeks", 'ujicountdown' ),
                'Week' => ( $this->ujic_get_option('ujic_week') ) ? $this->ujic_get_option('ujic_week')  : __( "Week", 'ujicountdown' ),
                'Days' => ( $this->ujic_get_option('ujic_days') ) ? $this->ujic_get_option('ujic_days')  : __( "Days", 'ujicountdown' ),
                'Day' => ( $this->ujic_get_option('ujic_day') ) ? $this->ujic_get_option('ujic_day')  : __( "Day", 'ujicountdown' ),
                'Hours' => ( $this->ujic_get_option('ujic_hours') ) ? $this->ujic_get_option('ujic_hours')  :  __( "Hours", 'ujicountdown' ),
                'Hour' => ( $this->ujic_get_option('ujic_hour') ) ? $this->ujic_get_option('ujic_hour')  :  __( "Hour", 'ujicountdown' ),
                'Minutes' => ( $this->ujic_get_option('ujic_minutes') ) ? $this->ujic_get_option('ujic_minutes')  : __( "Minutes", 'ujicountdown' ),
                'Minute' => ( $this->ujic_get_option('ujic_minute') ) ? $this->ujic_get_option('ujic_minute')  : __( "Minute", 'ujicountdown' ),
                'Seconds' => ( $this->ujic_get_option('ujic_seconds') ) ? $this->ujic_get_option('ujic_seconds')  : __( "Seconds", 'ujicountdown' ),
                'Second' => ( $this->ujic_get_option('ujic_second') ) ? $this->ujic_get_option('ujic_second')  : __( "Second", 'ujicountdown' ),
                'ujic_txt_size' => $ujic_txt_size,
                'ujic_thick' => $ujic_thick,
                'ujic_col_dw' => $ujic_col_dw,
                'ujic_col_up' => $ujic_col_up,
                'ujic_col_txt' => $ujic_col_txt,
                'ujic_col_sw' => $ujic_col_sw,
                'ujic_col_lab' => $ujic_col_lab,
                'ujic_lab_sz' => $ujic_lab_sz,
                'ujic_txt' => $ujic_txt,
                'ujic_ani' => $ujic_ani,
                'ujic_url' => $url,
                'ujic_goof' => $ujic_goof,
                'uji_center' => $classh,
                'ujic_d' => $ujic_d, //Main format: Days
                'ujic_h' => $ujic_h, //Main format: Hours
                'ujic_m' => $ujic_m, //Main format: Minutes
                'ujic_s' => $ujic_s, //Main format: Seconds
                'ujic_y' => $ujic_y, //Secondary format: Years
                'ujic_o' => $ujic_o, //Secondary format: Months
                'ujic_w' => $ujic_w, //Secondary format: Weeks
                'uji_time' => date_i18n( 'M j, Y H:i:s' ) ."+0000",
                'uji_hide' => ($hide == "true") ? 'true' : 'false',
                'ujic_rtl' => ( $this->ujic_get_option('ujic_rtl') ) ? $this->ujic_get_option('ujic_rtl')  : false,
                'uji_utime' => ( $this->ujic_get_option('ujic_utime') ) ? $this->ujic_get_option('ujic_utime')  : false
            ) ) );

            //ExtendStyle
            $extStyle = '';
            if(has_filter('ujic_shortcode_extendStyle')){
                $extStyle .= apply_filters( 'ujic_shortcode_extendStyle', $ujic_style, $storeVal);              
            }
            
            wp_enqueue_script( 'ujicirc-js' ); 
          
            wp_enqueue_script( $this->ujic_pro_sx().'-init' );
            
            //Filter shortcode
            $ujicvars = array( 'id' => $id, 'hclass' => $hclass, 'subscr' => $subscr );
            $formHtmlCode = apply_filters( 'ujic_shortcode_extend', $ujicvars);
            if( !$formHtmlCode )
                return strip_shortcodes( '<div class="ujic-hold' . $hclass . '"> <div class="ujiCountdown' . $classh . '" id="' . $ujic_id . '">'.$extStyle.'</div></div>' . $content );
            //Else with extension
            
            $formHtmlCode = (is_array($formHtmlCode)) ? '' : $formHtmlCode;
	    $htmlCode = strip_shortcodes('<div class="ujic-hold' . $hclass . '"> <div id = "uji-wrapper" class = "ujicf"> <div class="ujicf ujiCountdown' . $classh . '" id="' . $ujic_id . '">'.$extStyle.'</div>'.$formHtmlCode.'</div></div>' . $content );
                return $htmlCode;
        }
    }
    
    
    public function expired_redirect( $url ){
        wp_enqueue_script('ujiCountRedirect');
        $script  = 'ujiCountRedirect = '. $url .'; ';
        wp_add_inline_script('ujiCountRedirect', $script, 'before');
    }


}

?>