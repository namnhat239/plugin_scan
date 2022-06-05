<?php
// disable direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// create ical feed
function vsel_export_events() {
	$filename = urlencode( get_bloginfo('name').'-ical-' . date('Y-m-d') . '.ics' );
	$eol = "\r\n";
header("Content-Description: File Transfer");
header("Content-Disposition: attachment; filename=".$filename);
header("Content-type: text/calendar; charset=utf-8");
	$items = get_option('vsel-setting-93');
	if ( is_numeric($items) && !empty($items) ) {
		$number_of_events = $items;
	} else {
		$number_of_events  = '10';
	}
	$the_event_list = new WP_Query(array(
		'post_type' => 'event',
		'meta_key' => 'event-date',
		'orderby' => 'meta_value_num',
		'order' => 'DESC',
		'posts_per_page' => $number_of_events
	));
	if($the_event_list->have_posts()) :
$output = '';
$output .= 'BEGIN:VCALENDAR'.$eol.'';
$output .= 'VERSION:2.0'.$eol.'';
$output .= 'PRODID:-//'.get_bloginfo('name').'//NONSGML Events//EN'.$eol.'';
	while($the_event_list->have_posts()) : $the_event_list->the_post();
		$timezone = vsel_utc_timezone();
		$event = get_post( get_the_ID() );
		$title = $event->post_title;
		$start_date = wp_date("Ymd\THis", get_post_meta( get_the_ID(), 'event-start-date', true ), $timezone);
		$end_date = wp_date("Ymd\THis", get_post_meta( get_the_ID(), 'event-date', true ), $timezone);
		$modified_date = get_the_modified_date("Ymd\THis", get_the_ID());
		$location = get_post_meta( get_the_ID(), 'event-location', true );
		$url = get_the_permalink();
		$summary_raw = get_post_meta( get_the_ID(), 'event-summary', true );
		if ( empty($summary_raw) ) {
			$summary_raw = wp_trim_words( $event->post_content, 15, '...' );
		}
		$summary = preg_replace( "/\r\n/", "\\n", $summary_raw);
		$image = get_the_post_thumbnail_url( get_the_ID(), 'large' );
$output .= 'BEGIN:VEVENT'.$eol.'';
$output .= 'UID:'.esc_attr(get_the_ID()).$eol.'';
$output .= 'DTSTAMP:'.esc_attr($modified_date).$eol.'';
$output .= 'DTSTART:'.esc_attr($start_date).$eol.'';
$output .= 'DTEND:'.esc_attr($end_date).$eol.'';
$output .= 'LOCATION:'.wp_strip_all_tags($location).$eol.'';
$output .= 'DESCRIPTION:'.wp_strip_all_tags($summary).$eol.'';
$output .= 'SUMMARY:'.wp_strip_all_tags($title).$eol.'';
$output .= 'ATTACH;FMTTYPE=image/jpeg:'.esc_url($image).$eol.'';
$output .= 'URL;VALUE=URI:'.esc_url($url).$eol.'';
$output .= 'END:VEVENT'.$eol.'';
		endwhile;
$output .= 'END:VCALENDAR';
	echo $output;
	endif;
}
