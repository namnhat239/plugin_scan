<?php
namespace ULTP;

defined('ABSPATH') || exit;

class Options_Addons{
    public function __construct() {
        add_submenu_page(
            'ultp-settings', 
            __('Addons', 'ultimate-post'),
            __('Addons', 'ultimate-post'),
            'manage_options', 
            'ultp-addons', 
            array( $this, 'create_admin_page'), 10
        );
        add_filter('ultp_settings', array($this, 'get_addon_settings'), 10, 1);
    }


    public static function get_addon_settings($config) {
        $arr = array(
            'addon' => array(
                'label' => __('Blocks', 'ultimate-post'),
                'attr' => array(
                    'addon_enable' => array(
                        'type'  => 'heading',
                        'label' => __('Enable/Disable Blocks', 'ultimate-post'),
                    ),
                    'post_grid_1' => array(
                        'type' => 'switch',
                        'label' => __('Enable / Disable', 'ultimate-post'),
                        'default' => true,
                        'desc' => __('Post Grid #1 Block.', 'ultimate-post')
                    ),
                    'post_grid_2' => array(
                        'type' => 'switch',
                        'label' => __('Enable / Disable', 'ultimate-post'),
                        'default' => true,
                        'desc' => __('Post Grid #2 Block.', 'ultimate-post')
                    ),
                    'post_grid_3' => array(
                        'type' => 'switch',
                        'label' => __('Enable / Disable', 'ultimate-post'),
                        'default' => true,
                        'desc' => __('Post Grid #3 Block.', 'ultimate-post')
                    ),
                    'post_grid_4' => array(
                        'type' => 'switch',
                        'label' => __('Enable / Disable', 'ultimate-post'),
                        'default' => true,
                        'desc' => __('Post Grid #4 Block.', 'ultimate-post')
                    ),
                    'post_grid_5' => array(
                        'type' => 'switch',
                        'label' => __('Enable / Disable', 'ultimate-post'),
                        'default' => true,
                        'desc' => __('Post Grid #5 Block.', 'ultimate-post')
                    ),
                    'post_grid_6' => array(
                        'type' => 'switch',
                        'label' => __('Enable / Disable', 'ultimate-post'),
                        'default' => true,
                        'desc' => __('Post Grid #6 Block.', 'ultimate-post')
                    ),
                    'post_grid_7' => array(
                        'type' => 'switch',
                        'label' => __('Enable / Disable', 'ultimate-post'),
                        'default' => true,
                        'desc' => __('Post Grid #7 Block.', 'ultimate-post')
                    ),
                    'post_list_1' => array(
                        'type' => 'switch',
                        'label' => __('Enable / Disable', 'ultimate-post'),
                        'default' => true,
                        'desc' => __('Post List #1 Block.', 'ultimate-post')
                    ),
                    'post_list_2' => array(
                        'type' => 'switch',
                        'label' => __('Enable / Disable', 'ultimate-post'),
                        'default' => true,
                        'desc' => __('Post List #2 Block.', 'ultimate-post')
                    ),
                    'post_list_3' => array(
                        'type' => 'switch',
                        'label' => __('Enable / Disable', 'ultimate-post'),
                        'default' => true,
                        'desc' => __('Post List #3 Block.', 'ultimate-post')
                    ),
                    'post_list_4' => array(
                        'type' => 'switch',
                        'label' => __('Enable / Disable', 'ultimate-post'),
                        'default' => true,
                        'desc' => __('Post List #4 Block.', 'ultimate-post')
                    ),
                    'post_module_1' => array(
                        'type' => 'switch',
                        'label' => __('Enable / Disable', 'ultimate-post'),
                        'default' => true,
                        'desc' => __('Post Module #1 Block.', 'ultimate-post')
                    ),
                    'post_module_2' => array(
                        'type' => 'switch',
                        'label' => __('Enable / Disable', 'ultimate-post'),
                        'default' => true,
                        'desc' => __('Post Module #2 Block.', 'ultimate-post')
                    ),
                    'post_slider_1' => array(
                        'type' => 'switch',
                        'label' => __('Enable / Disable', 'ultimate-post'),
                        'default' => true,
                        'desc' => __('Post Slider #1 Block.', 'ultimate-post')
                    ),
                    'heading' => array(
                        'type' => 'switch',
                        'label' => __('Enable / Disable', 'ultimate-post'),
                        'default' => true,
                        'desc' => __('Heading Block.', 'ultimate-post')
                    ),
                    'image' => array(
                        'type' => 'switch',
                        'label' => __('Enable / Disable', 'ultimate-post'),
                        'default' => true,
                        'desc' => __('Image Block.', 'ultimate-post')
                    ),
                    'taxonomy' => array(
                        'type' => 'switch',
                        'label' => __('Enable / Disable', 'ultimate-post'),
                        'default' => true,
                        'desc' => __('Taxonomy Block.', 'ultimate-post')
                    ),
                    'wrapper' => array(
                        'type' => 'switch',
                        'label' => __('Enable / Disable', 'ultimate-post'),
                        'default' => true,
                        'desc' => __('Wrapper Block.', 'ultimate-post')
                    )
                )
            )
        );
        return array_merge($config, $arr);
    }
  

    public static function all_addons(){
        $all_addons = array(
            'ultp_category' => array(
                'name' => __( 'Category', 'ultimate-post' ),
                'desc' => __( 'Choose your desired color and Image for categories or any taxonomy.', 'ultimate-post' ),
                'img' => ULTP_URL.'/assets/img/addons/category-style.svg',
                'is_pro' => true
            ),
            'ultp_builder' => array(
                'name' => __( 'Builder', 'ultimate-post' ),
                'desc' => __( 'Design template for Archive, Category, Custom Taxonomy, Date, and Search Page.', 'ultimate-post' ),
                'img' => ULTP_URL.'/assets/img/addons/builder-icon.svg',
                'is_pro' => true
            ),
            'ultp_progressbar' => array(
                'name' => __( 'Progressbar', 'ultimate-post' ),
                'desc' => __( 'Let the users see a graphical indicator to know the reading progress of a blog post.', 'ultimate-post' ),
                'img' => ULTP_URL.'/assets/img/addons/progressbar.svg',
                'is_pro' => true
            ),
            'ultp_yoast' => array(
                'name' => __( 'Yoast Meta', 'ultimate-post' ),
                'desc' => __( 'Show Yoast meta description in the excerpt.', 'ultimate-post' ),
                'img' => ULTP_URL.'/assets/img/addons/yoast.svg',
                'is_pro' => true,
                'required' => array(
                    'name' => 'Yoast',
                    'slug' => 'wordpress-seo/wp-seo.php'
                )
            ),
            'ultp_aioseo' => array(
                'name' => __( 'All in One SEO Meta', 'ultimate-post' ),
                'desc' => __( 'Show All in One SEO meta description in the excerpt.', 'ultimate-post' ),
                'img' => ULTP_URL.'/assets/img/addons/aioseo.svg',
                'is_pro' => true,
                'required' => array(
                    'name' => 'All in One SEO',
                    'slug' => 'all-in-one-seo-pack/all_in_one_seo_pack.php'
                )
            ),
            'ultp_rankmath' => array(
                'name' => __( 'RankMath Meta', 'ultimate-post' ),
                'desc' => __( 'Show RankMath meta description in the excerpt.', 'ultimate-post' ),
                'img' => ULTP_URL.'/assets/img/addons/rankmath.svg',
                'is_pro' => true,
                'required' => array(
                    'name' => 'RankMath',
                    'slug' => 'seo-by-rank-math/rank-math.php'
                )
            ),
            'ultp_seopress' => array(
                'name' => __( 'SEOPress Meta', 'ultimate-post' ),
                'desc' => __( 'Show SEOPress meta description in the excerpt.', 'ultimate-post' ),
                'img' => ULTP_URL.'/assets/img/addons/seopress.svg',
                'is_pro' => true,
                'required' => array(
                    'name' => 'SEOPress',
                    'slug' => 'wp-seopress/seopress.php'
                )
            ),
            'ultp_squirrly' => array(
                'name' => __( 'Squirrly Meta', 'ultimate-post' ),
                'desc' => __( 'Show Squirrly meta description in the excerpt.', 'ultimate-post' ),
                'img' => ULTP_URL.'/assets/img/addons/squirrly.svg',
                'is_pro' => true,
                'required' => array(
                    'name' => 'Squirrly',
                    'slug' => 'squirrly-seo/squirrly.php'
                ),
            ),
        );
        return apply_filters('ultp_addons_config', $all_addons);
    }

    /**
     * Settings page output
     */
    public function create_admin_page() { ?>
        <style>
            .style-css{
                background-color: #f2f2f2;
                -webkit-font-smoothing: subpixel-antialiased;
            }
        </style>

        <div class="ultp-option-body">
            
            <?php require_once ULTP_PATH . 'classes/options/Heading.php'; ?>

            <div class="ultp-content-wrap ultp-addons-wrap">
                <div class="ultp-text-center"><h2 class="ultp-admin-title"><?php esc_html_e('All Addons', 'ultimate-post'); ?></h2></div> 
                <div class="ultp-addons-items">
                    <?php
                        $option_value = ultimate_post()->get_setting();
                        $addons_data = self::all_addons();
                        foreach ($addons_data as $key => $val) {
                            $require_plugin = '';
                            if (isset($val['required'])) {
                                $active_plugins = get_option( 'active_plugins', array() );
                                if (is_multisite()) {
                                    $active_plugins = array_merge($active_plugins, array_keys(get_site_option( 'active_sitewide_plugins', array() )));
                                }
                                if ( !in_array($val['required']['slug'], apply_filters('active_plugins', $active_plugins)) ) {
                                    $require_plugin = $val['required']['name'];
                                }
                            }
                            echo '<div class="ultp-addons-item ultp-admin-card">';
                                echo '<div class="ultp-addons-item-content">';
                                    echo '<img src="'.esc_url($val['img']).'" />';
                                    echo '<h4>'.esc_html($val['name']).'</h4>';
                                    echo '<div class="ultp-addons-desc">'.esc_html($val['desc']).'</div>';
                                echo '</div>';
                            if( $val['is_pro'] && !defined('ULTP_PRO_VER') ){
                                echo '<div class="ultp-addons-btn">';
                                    echo '<a class="ultp-btn ultp-btn-default" target="_blank" href="'.esc_url(ultimate_post()->get_premium_link()).'">'.esc_html__("Get Pro", "ultimate-post").'</a>';
                                echo '</div>';
                            } else if ($require_plugin) {
                                echo '<div class="ultp-addons-btn">';
                                    echo esc_html__('This addon required ').'<b>'.esc_html($require_plugin).'</b>';
                                echo '</div>';
                            } else {
                                echo '<div class="ultp-addons-btn">';
                                    echo '<label class="ultp-switch">';
                                        echo '<input class="ultp-addons-enable" '.(($val['is_pro'] && (!defined('ULTP_PRO_VER'))) ? 'disabled' : '').' data-addon="'.esc_attr($key).'" type="checkbox" '.( isset($option_value[$key]) && $option_value[$key] == 'true' ? 'checked' : '' ).'>';
                                        echo '<span class="ultp-slider ultp-round"></span>';
                                    echo '</label>';
                                echo '</div>';
                            }
                            echo '</div>';
                        }
                    ?> 
                </div>
            </div>
        </div>

    <?php }
}