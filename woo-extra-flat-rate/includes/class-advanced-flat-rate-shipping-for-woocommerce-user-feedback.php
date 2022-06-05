<?php

/**
 * Plugin review class.
 * Prompts users to give a review of the plugin on WordPress.org after a period of usage.
 *
 * Heavily based on code by CoBlocks
 * https://github.com/coblocks/coblocks/blob/master/includes/admin/class-coblocks-feedback.php
 *
 * @package   FlatRate
 * @author    theDotstore from FlatRate
 * @link      https://editorsFlatRate.com
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * Main Feedback Notice Class
 */
class WooFlatRate_User_Feedback
{
    /**
     * Name.
     *
     * @var string $name
     */
    private  $name ;
    /**
     * Slug.
     *
     * @var string $slug
     */
    private  $slug ;
    /**
     * Date Option.
     *
     * @var string $slug
     */
    private  $date_option ;
    /**
     * Review Rating.
     *
     * @var string $slug
     */
    private  $rr_option ;
    /**
     * Suggest New Feature.
     *
     * @var string $slug
     */
    private  $snf_option ;
    /**
     * Checkout Our Premium Plugin.
     *
     * @var string $slug
     */
    private  $copp_option ;
    /**
     * Sticky transiest
     *
     * @var string $slug
     */
    private  $sticky_transient ;
    /**
     * Magic method constructor
     *
     * @var string $args
     */
    public function __construct( $args )
    {
        $this->slug = $args['slug'];
        $this->name = $args['name'];
        $this->date_option = $this->slug . '_activation_date';
        $this->rr_option = $this->slug . '_rr';
        $this->copp_option = $this->slug . '_copp';
        $this->sticky_transient = $this->slug;
        add_action( 'admin_init', array( $this, 'check_installation_date' ) );
        add_action( 'admin_init', array( $this, 'set_no_bug' ), 5 );
    }
    
    /**
     * Set the plugin to no longer bug users if user asks not to be.
     */
    public function set_no_bug()
    {
        $this_day = gmdate( 'Y-m-d' );
        $today = date_create( $this_day );
        // phpcs:ignore
        $get_nonce = filter_input( INPUT_GET, '_wpnonce', FILTER_SANITIZE_STRING );
        
        if ( isset( $get_nonce ) ) {
            if ( is_admin() || current_user_can( 'manage_options' ) ) {
                
                if ( wp_verify_nonce( $get_nonce, 'editorsflatrate-rr-nounce' ) ) {
                    set_transient( $this->sticky_transient . 'sticky_rr_note', '0' );
                } else {
                    
                    if ( wp_verify_nonce( $get_nonce, 'editorsflatrate-copp-nounce' ) ) {
                        delete_transient( $this->sticky_transient . 'sticky_copp_note' );
                        set_transient( $this->sticky_transient . 'sticky_copp_note_cancel', $today );
                    }
                
                }
            
            }
        } else {
            return;
        }
    
    }
    
    public function check_installation_date()
    {
        add_option( $this->date_option, time() );
        /*
         * Today date
         */
        $this_day = gmdate( 'Y-m-d' );
        $today = date_create( $this_day );
        //$this_day
        $today_date_form = date_format( $today, 'Y-m-d' );
        /*
         * Installation date
         */
        $install_date = get_option( $this->date_option );
        $install_date_format = gmdate( 'Y-m-d', $install_date );
        $start_date = date_create( $install_date_format );
        /*
         * Diff date
         */
        $diff = date_diff( $today, $start_date );
        $day_diff = $diff->format( "%a" );
        $current_diff = $day_diff + 1;
        /*
         * Check interval based on 7 days for review rating
         */
        $check_sticky_rr_note = get_transient( $this->sticky_transient . 'sticky_rr_note' );
        
        if ( '0' !== $check_sticky_rr_note ) {
            if ( 0 === $current_diff % 7 ) {
                set_transient( $this->sticky_transient . 'sticky_rr_note', '1' );
            }
        } else {
            set_transient( $this->sticky_transient . 'sticky_rr_note', '0' );
        }
        
        /*
         * Check if all condition match for checkout our premium plugins
         */
        if ( '1' === $check_sticky_rr_note ) {
            add_action( 'admin_notices', array( $this, 'display_notice_for_rr' ) );
        }
        /*
         * Check date when user has clicked on 'no thanks' button for checkout our premium plugins
         */
        $check_sticky_copp_note_cancel = get_transient( $this->sticky_transient . 'sticky_copp_note_cancel' );
        $check_sticky_copp_note_cancel_format = '';
        if ( !empty($check_sticky_copp_note_cancel) ) {
            $check_sticky_copp_note_cancel_format = date_format( $check_sticky_copp_note_cancel, 'Y-m-d' );
        }
        /*
         * Check interval based on 5 days for checkout our premium plugins
         */
        if ( 0 === $current_diff % 5 ) {
            
            if ( $check_sticky_copp_note_cancel_format !== $today_date_form ) {
                set_transient( $this->sticky_transient . 'sticky_copp_note', '1' );
            } else {
                set_transient( $this->sticky_transient . 'sticky_copp_note', '0' );
            }
        
        }
    }
    
    /**
     * Seconds to words.
     *
     * @param string $seconds Seconds in time.
     *
     * @return string
     */
    public function seconds_to_words( $seconds )
    {
        // Get the years.
        $years = intval( $seconds ) / YEAR_IN_SECONDS % 100;
        
        if ( $years > 1 ) {
            /* translators: Number of years */
            return sprintf( __( '%s years', 'advanced-flat-rate-shipping-for-woocommerce' ), $years );
        } elseif ( $years > 0 ) {
            return __( 'a year', 'advanced-flat-rate-shipping-for-woocommerce' );
        }
        
        // Get the weeks.
        $weeks = intval( $seconds ) / WEEK_IN_SECONDS % 52;
        
        if ( $weeks > 1 ) {
            /* translators: Number of weeks */
            return sprintf( __( '%s weeks', 'advanced-flat-rate-shipping-for-woocommerce' ), $weeks );
        } elseif ( $weeks > 0 ) {
            return __( 'a week', 'advanced-flat-rate-shipping-for-woocommerce' );
        }
        
        // Get the days.
        $days = intval( $seconds ) / DAY_IN_SECONDS % 7;
        
        if ( $days > 1 ) {
            /* translators: Number of days */
            return sprintf( __( '%s days', 'advanced-flat-rate-shipping-for-woocommerce' ), $days );
        } elseif ( $days > 0 ) {
            return __( 'a day', 'advanced-flat-rate-shipping-for-woocommerce' );
        }
        
        // Get the hours.
        $hours = intval( $seconds ) / HOUR_IN_SECONDS % 24;
        
        if ( $hours > 1 ) {
            /* translators: Number of hours */
            return sprintf( __( '%s hours', 'advanced-flat-rate-shipping-for-woocommerce' ), $hours );
        } elseif ( $hours > 0 ) {
            return __( 'an hour', 'advanced-flat-rate-shipping-for-woocommerce' );
        }
        
        // Get the minutes.
        $minutes = intval( $seconds ) / MINUTE_IN_SECONDS % 60;
        
        if ( $minutes > 1 ) {
            /* translators: Number of minutes */
            return sprintf( __( '%s minutes', 'advanced-flat-rate-shipping-for-woocommerce' ), $minutes );
        } elseif ( $minutes > 0 ) {
            return __( 'a minute', 'advanced-flat-rate-shipping-for-woocommerce' );
        }
        
        // Get the seconds.
        $seconds = intval( $seconds ) % 60;
        
        if ( $seconds > 1 ) {
            /* translators: Number of seconds */
            return sprintf( __( '%s seconds', 'advanced-flat-rate-shipping-for-woocommerce' ), $seconds );
        } elseif ( $seconds > 0 ) {
            return __( 'a second', 'advanced-flat-rate-shipping-for-woocommerce' );
        }
    
    }
    
    /**
     * Display the admin notice for review rating.
     */
    public function display_notice_for_rr()
    {
        $current_page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );
        $no_bug_url = wp_nonce_url( admin_url( 'admin.php?page=' . $current_page . '&' . $this->rr_option . '=true' ), 'editorsflatrate-rr-nounce' );
        $time = $this->seconds_to_words( time() - get_site_option( $this->date_option ) );
        ?>
		<div class="notice updated editorsFlatRate-notice">
			<div class="editorsFlatRate-notice-inner">
				<div class="editorsFlatRate-notice-icon">
					<img src="<?php 
        echo  esc_url( plugins_url( 'admin/images/thedotstore-images/popular-plugins/Advanced-Flat-Rate-Shipping-Method.png', dirname( __FILE__ ) ) ) ;
        ?>"
					     alt="<?php 
        printf( esc_attr__( '%s WordPress Plugin', 'advanced-flat-rate-shipping-for-woocommerce' ), esc_attr( $this->name ) );
        ?>"/>
				</div>
				<div class="editorsFlatRate-notice-content">
					<h3>
						<?php 
        printf( esc_html__( 'Are you enjoying %s Plugin?', 'advanced-flat-rate-shipping-for-woocommerce' ), esc_html( $this->name ) );
        ?>
					</h3>
					<p>
						<?php 
        printf( esc_html__( 'You have been using our shipping plugin for %2$s now. Mind leaving a review to let us know what you think? We\'d really appreciate it!', 'advanced-flat-rate-shipping-for-woocommerce' ), esc_html( $this->name ), esc_html( $time ) );
        ?>
					</p>
				</div>
				<div class="editorsFlatRate-install-now">
					<?php 
        $review_url = '';
        $review_url = esc_url( 'https://wordpress.org/plugins/woo-extra-flat-rate/#reviews' );
        printf( '<a href="%1$s" class="button button-primary editorsFlatRate-install-button" target="_blank">%2$s</a>', esc_url( $review_url ), esc_html__( 'Leave a Review', 'advanced-flat-rate-shipping-for-woocommerce' ) );
        ?>
					<a href="<?php 
        echo  esc_url( $no_bug_url ) ;
        ?>" class="no-thanks">
						<?php 
        echo  esc_html__( 'No thanks / I already have', 'advanced-flat-rate-shipping-for-woocommerce' ) ;
        ?>
					</a>
				</div>
			</div>
		</div>
		<?php 
    }
    
    /**
     * Display notice for checkout our premium plugins.
     */
    public function display_notice_for_copp()
    {
        $no_bug_url = wp_nonce_url( admin_url( 'admin.php?page=afrsm-pro-list&' . $this->copp_option . '=true' ), 'editorsflatrate-copp-nounce' );
        ?>
		<div class="notice updated editorsFlatRate-notice">
			<div class="editorsFlatRate-notice-inner">
				<div class="editorsFlatRate-notice-icon">
					<?php 
        /* translators: 1. Name */
        ?>
					<img src="<?php 
        echo  esc_url( plugins_url( 'admin/images/thedotstore-images/popular-plugins/Advanced-Flat-Rate-Shipping-Method.png', dirname( __FILE__ ) ) ) ;
        ?>" alt="<?php 
        printf( esc_attr__( '%s WordPress Plugin', 'advanced-flat-rate-shipping-for-woocommerce' ), esc_attr( $this->name ) );
        ?>"/>
				</div>
				<div class="editorsFlatRate-notice-content">
					<?php 
        /* translators: 1. Name */
        ?>
					<h3>
						<?php 
        printf( esc_html__( 'Checkout our other Popular Premium WooCommerce plugins', 'advanced-flat-rate-shipping-for-woocommerce' ) );
        ?>
					</h3>
					<p>
						<?php 
        /* translators: 1. Name, 2. Time */
        ?>
						<?php 
        printf( esc_html__( ' Trusted By Over 40,000+ WooCommerce Store Owner', 'advanced-flat-rate-shipping-for-woocommerce' ) );
        ?>
					</p>
				</div>
				<div class="editorsFlatRate-install-now">
					<?php 
        $review_url = esc_url( 'https://www.thedotstore.com/' );
        printf( '<a href="%1$s" class="button button-primary editorsFlatRate-install-button" target="_blank">%2$s</a>', esc_url( $review_url ), esc_html__( 'Learn More', 'advanced-flat-rate-shipping-for-woocommerce' ) );
        ?>
					<a href="<?php 
        echo  esc_url( $no_bug_url ) ;
        ?>" class="no-thanks">
						<?php 
        echo  esc_html__( 'No thanks', 'advanced-flat-rate-shipping-for-woocommerce' ) ;
        ?>
					</a>
				</div>
			</div>
		</div>
		<?php 
    }

}
/*
* Instantiate the WooFlatRate_User_Feedback class.
*/
new WooFlatRate_User_Feedback( array(
    'slug' => 'editorsflat_rate_plugin_feedback',
    'name' => __( 'Advanced Flat Rate shipping for Woocommerce', 'advanced-flat-rate-shipping-for-woocommerce' ),
) );