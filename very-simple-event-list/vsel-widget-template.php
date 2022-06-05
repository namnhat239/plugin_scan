<?php
// disable direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// start event container
$output .= '<div id="event-'.get_the_ID().'" class="vsel-content'.vsel_event_cats().vsel_event_status().'">';
	// start event details section
	$output .= '<div class="vsel-meta vsel-meta-left" style="width:100%; box-sizing:border-box;">';
		// title
		if ($vsel_widget_atts['title_link'] == 'false') {
			$event_title = '<h3 class="vsel-meta-title">'.get_the_title().'</h3>';
		} else {
			if ( ($link_title_to_more_info == 'yes') && !empty($link) ) {
				$event_title = '<h3 class="vsel-meta-title"><a href="'.esc_url($link).'" '.$link_target.' title="'.esc_url($link).'">'.get_the_title().'</a></h3>';
			} elseif ($widget_link_title == 'yes') {
				$event_title =  '<h3 class="vsel-meta-title"><a href="'.get_permalink().'" rel="bookmark" title="'.get_the_title().'">'.get_the_title().'</a></h3>';
			} else {
				$event_title = '<h3 class="vsel-meta-title">'.get_the_title().'</h3>';
			}
		}
		if ( $widget_title_hide != 'yes' ) {
			if ( ($widget_date_hide != 'yes') && ($widget_show_icon == 'yes') && ($widget_meta_combine == 'yes') ) {
				$output .= '';
			} else {
				$output .= $event_title;
			}
		}
		// combine date icon and title or not
		if ( ($widget_title_hide != 'yes') && ($widget_date_hide != 'yes') && ($widget_show_icon == 'yes') && ($widget_meta_combine == 'yes') ) {
			$output .= '<div class="vsel-meta-combine">';
		}
		// date
		if ( $widget_date_hide != 'yes' ) {
			if ( empty($start_date) || empty($end_date) || ($start_date > $end_date) ) {
				$output .= '<div class="vsel-meta-date vsel-meta-error">';
				$output .= esc_attr__( 'Error: please reset date.', 'very-simple-event-list' );
				$output .= '</div>';
			} elseif ($end_date > $start_date) {
				if ( $widget_show_icon == 'yes' ) {
					if ($widget_date_format == 'j F Y' || $widget_date_format == 'd/m/Y' || $widget_date_format == 'd-m-Y') {
						$output .= '<div class="vsel-meta-date-icon vsel-meta-combined-date-icon"><div class="vsel-start-icon">';
						$output .= $widget_start_icon_1;
						$output .= '</div>';
						$output .= '<div class="vsel-end-icon">';
						$output .= $widget_end_icon_1;
						$output .= '</div></div>';
					} else {
						$output .= '<div class="vsel-meta-date-icon vsel-meta-combined-date-icon"><div class="vsel-start-icon">';
						$output .= $widget_start_icon_2;
						$output .= '</div>';
						$output .= '<div class="vsel-end-icon">';
						$output .= $widget_end_icon_2;
						$output .= '</div></div>';
					}
				} else {
					if ($widget_date_combine == 'yes') {
						$output .= '<div class="vsel-meta-date vsel-meta-combined-date">';
						$output .= $widget_start_default;
						$output .= ' '.esc_attr($date_separator).' ';
						$output .= $widget_end_default;
						$output .= '</div>';
					} else {
						$output .= '<div class="vsel-meta-date vsel-meta-start-date">';
						$output .= $widget_start_default;
						$output .= '</div>';
						$output .= '<div class="vsel-meta-date vsel-meta-end-date">';
						$output .= $widget_end_default;
						$output .= '</div>';
					}
				}
			} elseif ($end_date == $start_date) {
				if ( $widget_show_icon == 'yes' ) {
					if ($widget_date_format == 'j F Y' || $widget_date_format == 'd/m/Y' || $widget_date_format == 'd-m-Y') {
						$output .= '<div class="vsel-meta-date-icon vsel-meta-single-date-icon"><div class="vsel-start-icon">';
						$output .= $widget_start_icon_1;
						$output .= '</div></div>';
					} else {
						$output .= '<div class="vsel-meta-date-icon vsel-meta-single-date-icon"><div class="vsel-start-icon">';
						$output .= $widget_start_icon_2;
						$output .= '</div></div>';
					}
				} else {
					$output .= '<div class="vsel-meta-date vsel-meta-single-date">';
					$output .= $widget_same_default;
					$output .= '</div>';
				}
			}
		}
		// combine date icon and title or not
		if ( ($widget_title_hide != 'yes') && ($widget_date_hide != 'yes') && ($widget_show_icon == 'yes') && ($widget_meta_combine == 'yes') ) {
			$output .= $event_title;
			$output .= '</div>';
		}
		// time
		if ( $widget_time_hide != 'yes' ) {
			if ( $one_time_field != 'yes' ) {
				if ( ($start_date == $end_date) && ($start_time > $end_time) ) {
					$output .= '<div class="vsel-meta-time vsel-meta-error">';
					$output .= esc_attr__( 'Error: please reset time.', 'very-simple-event-list' );
					$output .= '</div>';
				} else {
					if ( $all_day_event == 'yes' ) {
						$output .= '<div class="vsel-meta-time vsel-meta-all-day">';
						$output .= esc_attr($widget_all_day_label);
						$output .= '</div>';
					} else {
						if ( ($hide_equal_time == 'yes') && ($start_time == $end_time) ) {
							$output .= '';
						} else {
							if ( $hide_end_time == 'yes' ) {
								$end = '';
							} else {
								$end = ' '.esc_attr($time_separator).' '.wp_date( esc_attr($template_time_format), esc_attr($end_date_timestamp), $utc_time_zone );
							}		
							$output .= '<div class="vsel-meta-time">';
							$output .= sprintf(esc_attr($widget_time_label), '<span>'.wp_date( esc_attr($template_time_format), esc_attr($start_date_timestamp), $utc_time_zone ).$end.'</span>' );
							$output .= '</div>';
						}
					}
				}
			} else {
				if (!empty($time)) {
					$output .= '<div class="vsel-meta-time">';
					$output .= sprintf(esc_attr($widget_time_label), '<span>'.esc_attr($time).'</span>' );
					$output .= '</div>';
				}
			}
		}
		// location
		if ( $widget_location_hide != 'yes' ) {
			if (!empty($location)) {
				$output .= '<div class="vsel-meta-location">';
				$output .= sprintf(esc_attr($widget_location_label), '<span>'.esc_attr($location).'</span>' );
				$output .= '</div>';
			}
		}
		// include acf fields
		if( class_exists('acf') && ($widget_acf_hide != 'yes') ) {
			include 'vsel-acf.php';
		}
		// more info link
		if ($link_title_to_more_info != 'yes') {
			if ( $widget_link_hide != 'yes' ) {
				if (!empty($link)) {
					$output .= '<div class="vsel-meta-link">';
					$output .= '<a href="'.esc_url($link).'" '.$link_target.' title="'.esc_url($link).'">'.esc_attr($link_label).'</a>';
					$output .= '</div>';
				}
			}
		}
		// categories
		if ( $widget_cats_hide != 'yes' ) {
			$cats_raw = wp_strip_all_tags( get_the_term_list( get_the_ID(), 'event_cat', '<span>', ' '.esc_attr($cat_separator).' ', '</span>' ) );
			$cats = get_the_term_list( get_the_ID(), 'event_cat', '<span>', ' '.esc_attr($cat_separator).' ', '</span>' );
			if( has_term( '', 'event_cat', get_the_ID() ) ) {
				if ($widget_link_cat != 'yes') {
					$output .= '<div class="vsel-meta-cats">';
					$output .= $cats_raw;
					$output .= '</div>';
				} else {
					$output .= '<div class="vsel-meta-cats">';
					$output .= $cats;
					$output .= '</div>';
				}
			}
		}
	// end event details section
	$output .= '</div>';
	// start event info section
	if ( ($widget_image_hide == 'yes') && ($widget_info_hide == 'yes') ) {
		$output .= '';
	} else {
		$output .= '<div class="vsel-image-info vsel-image-info-left" style="width:100%; box-sizing:border-box;">';
			// featured image
			if ($vsel_widget_atts['featured_image'] != 'false') {
				if ( $widget_image_hide != 'yes' ) {
					if ( has_post_thumbnail() ) {
						$image_title = get_the_title( get_post_thumbnail_id( get_the_ID() ) );
						if ($widget_link_image != 'yes') {
							$output .= get_the_post_thumbnail( get_the_ID(), $widget_image_source, array( 'class' => $widget_img_class, 'style' => $widget_image_max_width, 'alt' => $image_title ) );
						} else {
							$output .=  '<a href="'.get_permalink().'">'.get_the_post_thumbnail( get_the_ID(), $widget_image_source, array( 'class' => $widget_img_class, 'style' => $widget_image_max_width, 'alt' => $image_title ) ).'</a>';
						}
					}
				}
			}
			// event info
			if ( $widget_info_hide != 'yes' ) {
				$output .= '<div class="vsel-info">';
					if ( $vsel_widget_atts['summary'] == 'true' ) {
						if ( !empty($summary) ) {
							$output .= apply_filters( 'the_excerpt', $summary );
						} else {
							$output .= apply_filters( 'the_excerpt', get_the_excerpt() );
						}
					} else {
						if ( $widget_excerpt != 'yes' ) {
							$output .= apply_filters( 'the_content', get_the_content() );
						} elseif ( !empty($summary) ) {
							$output .= apply_filters( 'the_excerpt', $summary );
						}  else {
							$output .= apply_filters( 'the_excerpt', get_the_excerpt() );
						}
					}
				$output .= '</div>';
			}
		// end event info section
		$output .= '</div>';
	}
// end event container
$output .= '</div>';
