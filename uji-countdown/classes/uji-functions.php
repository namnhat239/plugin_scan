<?php
/**
 * Get all saved styles
 *
 * @since    2.1.3
 * 
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function ujic_styles_get( $first = '') {
        global $wpdb;
        $ujic_styles = $wpdb->get_results("SELECT style, title, link FROM " . $wpdb->prefix . "uji_counter ORDER BY `time` DESC");
        $ujic_sel = array();
        
        if ( !empty($ujic_styles) ) {
            foreach ( $ujic_styles as $ujic ) {
                $ujic_sel[$ujic->link] =  $ujic->title;
            }

         if($first){
                 $selop = array( '' => $first );
                 $ujic_sel = array_merge($selop, $ujic_sel);
         }   
         return $ujic_sel;
        }
}
        
        
function ujic_datetime_get($nr) {
        $ujic_sel = array();
        for ( $i = 0; $i <= $nr; $i++ ) {
             $ujic_sel[$i]['text'] = $num[sprintf("%02s", $i)] = sprintf("%02s", $i);
             $ujic_sel[$i]['value'] = $i;
        }

        return $ujic_sel;
}

function ujic_reclab_get() {
        $tlab = array('second'=>  __( 'Second(s)', 'ujicountdown' ),
                      'minute'=>  __( 'Minute(s)', 'ujicountdown' ),
                      'hour'=>  __('Hour(s)', 'ujicountdown' ),
                      'day'=>  __( 'Day(s)', 'ujicountdown' ),
                      'week'=>  __( 'Week(s)', 'ujicountdown' ),
                      'month'=>  __( 'Month(s)', 'ujicountdown' ));
        $i=0;
        foreach ( $tlab as $v => $n ) {
            $ujic_sel[$i]['text'] = $n;
            $ujic_sel[$i]['value'] = $v;
            $i++;
        }

        return $ujic_sel;
    }

?>