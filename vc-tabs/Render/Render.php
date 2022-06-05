<?php

namespace OXI_TABS_PLUGINS\Render;

/**
 * Render Core Class
 *
 * 
 * @author biplob018
 * @package Oxilab Tabs Ultimate
 * @since 3.3.0
 */
class Render {

    /**
     * Current Elements id
     *
     * @since 3.3.0
     */
    public $oxiid;

    /**
     * Current Elements Style Data
     *
     * @since 3.3.0
     */
    public $style = [];

    /**
     * Current Elements Style Full
     *
     * @since 3.3.0
     */
    public $dbdata = [];

    /**
     * Current Elements multiple list data
     *
     * @since 3.3.0
     */
    public $child = [];

    /**
     * Current Elements Global CSS Data
     *
     * @since 3.3.0
     */
    public $CSSDATA = [];

    /**
     * Current Elements Global CSS Data
     *
     * @since 3.3.0
     */
    public $inline_css;

    /**
     * Current Elements Global JS Handle
     *
     * @since 3.3.0
     */
    public $JSHANDLE = 'oxi-tabs-ultimate';

    /**
     * Current Elements Global DATA WRAPPER
     *
     * @since 3.3.0
     */
    public $WRAPPER;

    /**
     * Current Elements Admin Control
     *
     * @since 3.3.0
     */
    public $admin;

    /**
     * load constructor
     *
     * @since 3.3.0
     */

    /**
     * Define $wpdb
     *
     * @since 3.3.0
     */
    public $database;

    /**
     * Database Style Name
     *
     * @since 3.3.0
     */
    public $style_name;

    /**
     * Public Attribute
     *
     * @since 3.3.0
     */
    public $attribute;

    /**
     * Public Header Class
     *
     * @since 3.3.0
     */
    public $headerclass;

    /**
     * Public arg
     *
     * @since 3.3.0
     */
    public $arg;

    /**
     * Public keys
     *
     * @since 3.3.0
     */
    public $keys;

    /**
     * Public keys
     *
     * @since 3.3.0
     */
    public $childkeys;

    public function __construct(array $dbdata = [], array $child = [], $admin = 'user', array $arg = [], array $keys = []) {
        if (count($dbdata) > 0):
            $this->dbdata = $dbdata;
            $this->child = $child;
            $this->admin = $admin;
            $this->arg = $arg;
            $this->keys = $keys;
            $this->style_name = ucfirst($dbdata['style_name']);
            $this->database = new \OXI_TABS_PLUGINS\Helper\Database();
            if (array_key_exists('id', $this->dbdata)):
                $this->oxiid = $this->dbdata['id'];
            else:
                $this->oxiid = rand(100000, 200000);
            endif;
            $this->loader();
        endif;
    }
     public function defualt_value($id) {
        return [
            'oxi-tabs-modal-title' => 'Lorem Ipsum',
            'oxi-tabs-modal-sub-title' => '',
            'oxi-tabs-modal-title-additional' => '',
            'oxi-tabs-modal-icon' => 'fab fa-facebook-f',
            'oxi-tabs-modal-number' => 1,
            'oxi-tabs-modal-image-select' => 'media-library',
            'oxi-tabs-modal-image-image' => '',
            'oxi-tabs-modal-image-image-alt' => '',
            'oxi-tabs-modal-image-url' => '',
            'oxi-tabs-modal-components-type' => 'wysiwyg',
            'oxi-tabs-modal-link-url' => '',
            'oxi-tabs-modal-desc' => '',
            'shortcodeitemid' => $id,
            'oxi-tabs-modal-link-target' => 0
        ];
    }

    /**
     * Current element loader
     *
     * @since 3.3.0
     */
    public function loader() {
        $this->style = json_decode(stripslashes($this->dbdata['rawdata']), true);
        $this->CSSDATA = $this->dbdata['stylesheet'];
        $this->WRAPPER = 'oxi-tabs-wrapper-' . $this->dbdata['id'];
        $this->hooks();
    }

    /**
     * load css and js hooks
     *
     * @since 3.3.0
     */
    public function hooks() {
        $this->public_jquery();
        $this->public_css();
        $this->public_frontend_loader();
        $this->render();
        $inlinecss = $this->inline_public_css() . $this->inline_css . (array_key_exists('oxi-tabs-custom-css', $this->style) ? $this->style['oxi-tabs-custom-css'] : '');
        $inlinejs = $this->inline_public_jquery();
        if ($this->CSSDATA == '' && $this->admin == 'admin') {
            $cls = '\OXI_TABS_PLUGINS\Render\Admin\\' . $this->style_name;
            $CLASS = new $cls('admin');
            $inlinecss .= $CLASS->inline_template_css_render($this->style);
        } else {
            echo $this->font_familly_validation(json_decode(($this->dbdata['font_family'] != '' ? $this->dbdata['font_family'] : "[]"), true));
            $inlinecss .= $this->CSSDATA;
        }
        if ($inlinejs != ''):
            if ($this->admin == 'admin'):
                echo _('<script>
                        (function ($) {
                            setTimeout(function () {');
                echo $inlinejs;
                echo _('    }, 2000);
                        })(jQuery)</script>');
            else:
                $jquery = '(function ($) {' . $inlinejs . '})(jQuery);';
                wp_add_inline_script($this->JSHANDLE, $jquery);
            endif;
        endif;
        if ($inlinecss != ''):
            $inlinecss = html_entity_decode($inlinecss);
            if ($this->admin == 'admin'):
                //only load while ajax called
                echo _('<style>');
                echo $inlinecss;
                echo _('</style>');
            else:
                wp_add_inline_style('oxi-tabs-ultimate', $inlinecss);
            endif;
        endif;
    }

    /**
     * front end loader css and js
     *
     * @since 3.3.0
     */
    public function public_frontend_loader() {
        wp_enqueue_script("jquery");
        wp_enqueue_style('oxi-tabs-ultimate', OXI_TABS_URL . 'assets/frontend/css/style.css', false, OXI_TABS_PLUGIN_VERSION);
        wp_enqueue_style('oxi-plugin-animate', OXI_TABS_URL . 'assets/frontend/css/animate.css', false, OXI_TABS_PLUGIN_VERSION);
        wp_enqueue_style('oxi-tabs-' . strtolower($this->style_name), OXI_TABS_URL . 'assets/frontend/style/' . strtolower($this->style_name) . '.css', false, OXI_TABS_PLUGIN_VERSION);
        wp_enqueue_script('oxi-tabs-ultimate', OXI_TABS_URL . 'assets/frontend/js/tabs.js', false, OXI_TABS_PLUGIN_VERSION);
    }

    /**
     * load current element render since 3.3.0
     *
     * @since 3.3.0
     */
    public function render() {

        $this->public_attribute($this->style);

        echo '<div class="oxi-addons-container ' . $this->WRAPPER . '" id="' . $this->WRAPPER . '">
                 <div class="oxi-addons-row">';
        if ($this->admin == 'admin'):
            echo '<input type="hidden" id="oxi-addons-iframe-background-color" name="oxi-addons-iframe-background-color" value="' . (is_array($this->style) ? array_key_exists('oxilab-preview-color', $this->style) ? $this->style['oxilab-preview-color'] : '#FFF' : '#FFF') . '">';
        endif;
        $this->default_render($this->style, $this->child, $this->admin);
        echo '   </div>
              </div>';
    }

    /**
     * load current element render since 3.3.0
     *
     * @since 3.3.0
     */
    public function public_attribute($style) {

        $this->attribute = [
            'header' => get_option('oxi_addons_fixed_header_size'),
            'animation' => array_key_exists('oxi-tabs-gen-animation', $style) ? $style['oxi-tabs-gen-animation'] : '',
            'initial' => array_key_exists('oxi-tabs-gen-opening', $style) ? $style['oxi-tabs-gen-opening'] : '',
            'trigger' => array_key_exists('oxi-tabs-gen-trigger', $style) ? $style['oxi-tabs-gen-trigger'] : '',
            'type' => array_key_exists('oxi-tabs-gen-event', $style) ? $style['oxi-tabs-gen-event'] : '',
            'lap' => array_key_exists('oxi-tabs-desc-content-height-lap', $style) ? $style['oxi-tabs-desc-content-height-lap'] : 'no',
            'tab' => array_key_exists('oxi-tabs-desc-content-height-tab', $style) ? $style['oxi-tabs-desc-content-height-tab'] : 'no',
            'mob' => array_key_exists('oxi-tabs-desc-content-height-mob', $style) ? $style['oxi-tabs-desc-content-height-mob'] : 'no',
        ];

        $responsive = ' ';
        if ($style['oxi-tabs-heading-responsive-mode'] == 'oxi-tabs-heading-responsive-static'):
            $responsive .= $style['oxi-tabs-header-horizontal-tabs-alignment-horizontal'] . ' ' . $style['oxi-tabs-header-horizontal-mobile-alignment-horizontal'] . ' ';
            $responsive .= $style['oxi-tabs-header-vertical-tabs-alignment'] . '  ' . $style['oxi-tabs-header-vertical-tabs-alignment-horizontal'] . ' ';
            $responsive .= $style['oxi-tabs-header-vertical-mobile-alignment'] . '  ' . $style['oxi-tabs-header-vertical-mobile-alignment-horizontal'] . ' ';
        endif;
        $this->headerclass = $style['oxi-tabs-gen-event'] . ' ' . $style['oxi-tabs-heading-responsive-mode'] . ' ' . $style['oxi-tabs-heading-alignment'] . ' ' . $style['oxi-tabs-heading-horizontal-position'] . ' ' . $style['oxi-tabs-heading-vertical-position'] . ' ' . $responsive;
    }

    /**
     * load public jquery
     *
     * @since 3.3.0
     */
    public function public_jquery() {
        echo '';
    }

    /**
     * load public css
     *
     * @since 3.3.0
     */
    public function public_css() {
        echo '';
    }

    /**
     * load inline public jquery
     *
     * @since 3.3.0
     */
    public function inline_public_jquery() {
        echo '';
    }

    /**
     * load inline public css
     *
     * @since 3.3.0
     */
    public function inline_public_css() {
        echo '';
    }

    /**
     * load default render
     *
     * @since 3.3.0
     */
    public function default_render($style, $child, $admin) {
        echo '';
    }

    /**
     * load default render
     *
     * @since 3.3.0
     */
    public function Json_Decode($rawdata) {
        return $rawdata != '' ? json_decode(stripcslashes($rawdata), true) : [];
    }

    public function font_familly_validation($data = []) {
        $api = get_option('oxi_addons_google_font');
        if ($api == 'no'):
            return;
        endif;
        foreach ($data as $value) {
            wp_enqueue_style('' . $value . '', 'https://fonts.googleapis.com/css?family=' . $value . '');
        }
    }

    public function array_render($id, $style) {
        if (array_key_exists($id, $style)):
            return $style[$id];
        endif;
    }

    public function text_render($data) {
        return do_shortcode(str_replace('spTac', '&nbsp;', str_replace('spBac', '<br>', html_entity_decode($data))), $ignore_html = false);
    }

    public function CatStringToClassReplacce($string, $number = '000') {
        $entities = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', "t");
        $replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]", " ");
        return 'sa_STCR_' . str_replace($replacements, $entities, urlencode($string)) . $number;
    }

    public function url_render($id, $style) {
        $link = [];
        if (array_key_exists($id . '-url', $style) && $style[$id . '-url'] != ''):
            $link['url'] = $style[$id . '-url'];
            if (array_key_exists($id . '-target', $style) && $style[$id . '-target'] != '0'):
                $link['target'] = $style[$id . '-target'];
            else:
                $link['target'] = '';
            endif;
        endif;
        return $link;
    }

    public function media_render($id, $style) {
        $url = '';
        if (array_key_exists($id . '-select', $style)):
            if ($style[$id . '-select'] == 'media-library'):
                $url = $style[$id . '-image'];
            else:
                $url = $style[$id . '-url'];
            endif;
            if (array_key_exists($id . '-image-alt', $style) && $style[$id . '-image-alt'] != ''):
                $r = 'src="' . $url . '" alt="' . $style[$id . '-image-alt'] . '" ';
            else:
                $r = 'src="' . $url . '" ';
            endif;
            return $r;
        endif;
    }

    public function excerpt($limit = 10) {
        $limit++;
        $excerpt = explode(' ', get_the_excerpt(), $limit);
        if (count($excerpt) >= $limit) {
            array_pop($excerpt);
            $excerpt = implode(" ", $excerpt) . '...';
        } else {
            $excerpt = implode(" ", $excerpt);
        }
        $excerpt = preg_replace('`[[^]]*]`', '', $excerpt);
        return $excerpt;
    }

    public function post_title($limit = 10) {
        $limit++;
        $title = explode(' ', get_the_title(), $limit);
        if (count($title) >= $limit) {
            array_pop($title);
            $title = implode(" ", $title) . '...';
        } else {
            $title = implode(" ", $title);
        }
        return $title;
    }

    public function truncate($str, $length = 24) {
        if (mb_strlen($str) > $length) {
            return mb_substr($str, 0, $length) . '...';
        } else {
            return $str;
        }
    }

    public function tabs_url_render($style) {
        if ($style['oxi-tabs-modal-components-type'] == 'link'):
            $data = $this->url_render('oxi-tabs-modal-link', $style);
            if (count($data) >= 1):
                return ' data-link=\'' . json_encode($data) . '\'';
            endif;
        endif;
    }

    public function tabs_content_render_tag($style, $child) {

        $number = array_key_exists('oxi-tabs-desc-tags-max', $style) ? $style['oxi-tabs-desc-tags-max'] : 10;
        $smallest = array_key_exists('oxi-tabs-desc-tags-small', $style) ? $style['oxi-tabs-desc-tags-small'] : 10;
        $largest = array_key_exists('oxi-tabs-desc-tags-big', $style) ? $style['oxi-tabs-desc-tags-big'] : 10;
        $show_count = array_key_exists('oxi-tabs-desc-tags-show-count', $style) ? $style['oxi-tabs-desc-tags-show-count'] : 1;

        $tags = get_tags();
        $args = array(
            'smallest' => $smallest,
            'largest' => $largest,
            'unit' => 'px',
            'number' => $number,
            'format' => 'flat',
            'separator' => " ",
            'orderby' => 'count',
            'order' => 'DESC',
            'show_count' => $show_count,
            'echo' => false
        );
        return wp_generate_tag_cloud($tags, $args);
    }

    public function tabs_content_render_commment($style, $child) {
        $number = array_key_exists('oxi-tabs-desc-comment-max', $style) ? $style['oxi-tabs-desc-comment-max'] : 5;
        $show_avatar = array_key_exists('oxi-tabs-desc-comment-show-avatar', $style) ? $style['oxi-tabs-desc-comment-show-avatar'] : 1;
        $avatar_size = array_key_exists('oxi-tabs-desc-comment-avatar-size', $style) ? $style['oxi-tabs-desc-comment-avatar-size'] : 65;
        $comment_length = array_key_exists('oxi-tabs-desc-comment-comment-lenth', $style) ? $style['oxi-tabs-desc-comment-comment-lenth'] : 90;

        $recent_comments = get_comments(array(
            'number' => $number,
            'status' => 'approve',
            'post_status' => 'publish'
        ));
        $public = '';
        if ($recent_comments) : foreach ($recent_comments as $comment) :
                $public .= '<div class="oxi-tabs-comment">';
                if ($show_avatar) :
                    $public .= ' <div class="oxi-tabs-comment-avatar">
                                    <a href="' . get_comment_link($comment->comment_ID) . '">
                                        ' . get_avatar($comment->comment_author_email, $avatar_size) . '
                                    </a>
                                </div>';
                endif;
                $public .= '<div class="oxi-tabs-comment-body">
                                <div class=oxi-tabs-comment-meta">
                                    <a href="' . get_comment_link($comment->comment_ID) . '">
                                        <span class="oxi-tabs-comment-author">' . get_comment_author($comment->comment_ID) . ' </span> - <span class="oxi-tabs-comment-post">' . get_the_title($comment->comment_post_ID) . '</span>
                                    </a>
                                </div>
                                <div class="oxi-tabs-comment-content">
                                    ' . $this->truncate(strip_tags(apply_filters('get_comment_text', $comment->comment_content)), $comment_length) . '
                                </div>
                            </div>
                            </div>';
            endforeach;
        else :
            $public .= ' <div class="oxi-tabs-comment">
                            <div class="no-comments">No comments yet</div>
                        </div>';
        endif;
        return $public;
    }

    public function tabs_content_render_recent($style, $child) {
        $show_thumb = array_key_exists('oxi-tabs-desc-recent-thumb-condi', $style) ? $style['oxi-tabs-desc-recent-thumb-condi'] : 1;
        $thumb_size = array_key_exists('oxi-tabs-desc-recent-thumb', $style) ? $style['oxi-tabs-desc-recent-thumb'] : 65;
        $date = array_key_exists('oxi-tabs-desc-recent-meta-date', $style) ? $style['oxi-tabs-desc-recent-meta-date'] : 1;
        $comment = array_key_exists('oxi-tabs-desc-recent-meta-comment', $style) ? $style['oxi-tabs-desc-recent-meta-comment'] : 1;
        $content = array_key_exists('oxi-tabs-desc-recent-content-lenth', $style) ? $style['oxi-tabs-desc-recent-content-lenth'] : 90;
        $number = array_key_exists('oxi-tabs-desc-recent-post', $style) ? $style['oxi-tabs-desc-recent-post'] : 5;
        $public = '';

        $query = new \WP_Query('posts_per_page=' . $number);

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $extra = '';
                if ($date):
                    $extra .= '    <div class="oxi-tabs-recent-date">
                                       ' . get_the_date('M d, Y') . '
                                    </div>';
                endif;

                if ($comment):
                    if (!empty($extra)):
                        $extra .= '&nbsp&bull;&nbsp';
                    endif;
                    $number = (int) get_comments_number($query->post->ID);
                    $extra .= '    <div class="oxi-tabs-recent-comment">
                                        ' . ($number > 1 ? $number . ' Comment' : ($number > 0 ? 'One Comment' : 'No Comment')) . '
                                    </div>';
                endif;
                $image_url = wp_get_attachment_image_src(get_post_thumbnail_id(), $thumb_size);
                $public .= '<div class="oxi-tabs-recent-post">';
                if ($show_thumb) {
                    $image = $image_url[0] != '' ? $image_url[0] : '';
                    $public .= '    <div class="oxi-tabs-recent-avatar">
                                        <a href="' . get_permalink($query->post->ID) . '">
                                           <img class="oxi-image" src="' . $image . '">
                                        </a>
                                    </div>';
                }
                $public .= '<div class="oxi-tabs-recent-body">
                                <div class="oxi-tabs-recent-meta">
                                    <a href="' . get_permalink($query->post->ID) . '">
                                        ' . get_the_title($query->post->ID) . '
                                    </a>
                                </div>
                                ' . (!empty($extra) ? '<div class="oxi-tabs-recent-postmeta">' . $extra . '</div>' : '') . '
                                <div class="oxi-tabs-recent-content">
                                    ' . $this->truncate(strip_tags(get_the_content()), $content) . '
                                </div>
                            </div>';
                $public .= '</div>';
                $extra = '';
            }
            wp_reset_postdata();
        }

        return $public;
    }

    public function tabs_content_render_popular($style, $child) {
        $show_thumb = array_key_exists('oxi-tabs-desc-popular-thumb-condi', $style) ? $style['oxi-tabs-desc-popular-thumb-condi'] : 1;
        $thumb_size = array_key_exists('oxi-tabs-desc-popular-thumb', $style) ? $style['oxi-tabs-desc-popular-thumb'] : 65;
        $date = array_key_exists('oxi-tabs-desc-popular-meta-date', $style) ? $style['oxi-tabs-desc-popular-meta-date'] : 1;
        $comment = array_key_exists('oxi-tabs-desc-popular-meta-comment', $style) ? $style['oxi-tabs-desc-popular-meta-comment'] : 1;
        $content = array_key_exists('oxi-tabs-desc-popular-content-lenth', $style) ? $style['oxi-tabs-desc-popular-content-lenth'] : 90;
        $number = array_key_exists('oxi-tabs-desc-popular-post', $style) ? $style['oxi-tabs-desc-popular-post'] : 5;
        $public = '';

        $query = new \WP_Query(
                array('ignore_sticky_posts' => 1,
            'posts_per_page' => $number,
            'post_status' => 'publish',
            'orderby' => 'meta_value_num',
            'meta_key' => '_oxi_post_view_count',
            'order' => 'desc')
        );
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $extra = '';
                if ($date):
                    $extra .= '    <div class="oxi-tabs-popular-date">
                                       ' . get_the_date('M d, Y') . '
                                    </div>';
                endif;

                if ($comment):
                    if (!empty($extra)):
                        $extra .= '&nbsp&bull;&nbsp';
                    endif;
                    $number = (int) get_comments_number($query->post->ID);
                    $extra .= '    <div class="oxi-tabs-popular-comment">
                                        ' . ($number > 1 ? $number . ' Comment' : ($number > 0 ? 'One Comment' : 'No Comment')) . '
                                    </div>';
                endif;
                $image_url = wp_get_attachment_image_src(get_post_thumbnail_id(), $thumb_size);
                $public .= '<div class="oxi-tabs-popular-post">';
                if ($show_thumb) {
                    $image = $image_url[0] != '' ? $image_url[0] : '';
                    $public .= '    <div class="oxi-tabs-popular-avatar">
                                        <a href="' . get_permalink($query->post->ID) . '">
                                           <img class="oxi-image" src="' . $image . '">
                                        </a>
                                    </div>';
                }
                $public .= '<div class="oxi-tabs-popular-body">
                                <div class="oxi-tabs-popular-meta">
                                    <a href="' . get_permalink($query->post->ID) . '">
                                        ' . get_the_title($query->post->ID) . '
                                    </a>
                                </div>
                                ' . (!empty($extra) ? '<div class="oxi-tabs-popular-postmeta">' . $extra . '</div>' : '') . '
                                <div class="oxi-tabs-popular-content">
                                    ' . $this->truncate(strip_tags(get_the_content()), $content) . '
                                </div>
                            </div>';
                $public .= '</div>';
                $extra = '';
            }
            wp_reset_postdata();
        }

        return $public;
    }

    public function tabs_content_render($style, $child) {
        if ($this->admin == 'woocommerce'):
            $key = $this->keys[$this->childkeys];
            $tabs = $this->arg[$key];
            ob_start();
            if (isset($tabs['callback'])):
                call_user_func($tabs['callback'], $key, $tabs);
            endif;
            return ob_get_clean();
        elseif ($child['oxi-tabs-modal-components-type'] == 'popular-post'):
            return $this->tabs_content_render_popular($style, $child);
        elseif ($child['oxi-tabs-modal-components-type'] == 'recent-post'):
            return $this->tabs_content_render_recent($style, $child);
        elseif ($child['oxi-tabs-modal-components-type'] == 'recent-comment'):
            return $this->tabs_content_render_commment($style, $child);
        elseif ($child['oxi-tabs-modal-components-type'] == 'tag'):
            return $this->tabs_content_render_tag($style, $child);
        elseif ($child['oxi-tabs-modal-components-type'] == 'nested-tabs'):
            return $this->tabs_content_render_nested_tabs($style, $child);
        else:
            return $this->special_charecter($child['oxi-tabs-modal-desc']);
        endif;
    }

    public function tabs_content_render_nested_tabs($style, $child) {
        $shortcode = array_key_exists('oxi-tabs-modal-nested-tabs', $child) ? $child['oxi-tabs-modal-nested-tabs'] : '';
        if ($shortcode > 0):
            ob_start();
            echo \OXI_TABS_PLUGINS\Classes\Bootstrap::instance()->shortcode_render($shortcode, 'user');
            return ob_get_clean();
        endif;
        return;
    }

    public function special_charecter($data) {
        $data = html_entity_decode($data);
        $data = str_replace("\'", "'", $data);
        $data = str_replace('\"', '"', $data);
        $data = do_shortcode($data, $ignore_html = false);
        return $data;
    }

    public function header_responsive_static_render($style = [], $ids = []) {
        $render = ' ';
        foreach ($ids as $type) {
            $render .= $style['oxi-tabs-heading-tabs-show-' . $type] . ' ';
            $render .= $style['oxi-tabs-heading-mobile-show-' . $type] . ' ';
        }
        return $render;
    }

    public function title_special_charecter($array, $title, $subtitle) {
        $r = '<div class=\'oxi-tabs-header-li-title\'>';
        $t = false;
        if (!empty($array[$title]) && $array[$title] != ''):
            $t = true;
            if ($this->admin == 'woocommerce'):
                $key = $this->keys[$this->childkeys];
                $tabs = $this->arg[$key];
                $r .= '<div class=\'oxi-tabs-main-title\'>';
                $r .= wp_kses_post(apply_filters('woocommerce_product_' . $key . '_tab_title', $tabs['title'], $key));
                $r .= '</div>';
            else:
                $r .= '<div class=\'oxi-tabs-main-title\'>' . $this->special_charecter($array[$title]) . '</div>';
            endif;
        endif;
        if (!empty($array[$subtitle]) && $array[$subtitle] != ''):
            $t = true;
            $r .= '<div class=\'oxi-tabs-sub-title\'>' . $this->special_charecter($array[$subtitle]) . '</div>';
        endif;
        $r .= '</div>';
        if ($t):
            return $r;
        endif;
    }

    public function number_special_charecter($data) {
        if (!empty($data) && $data != ''):
            return '<div class=\'oxi-tabs-header-li-number\'>' . $this->special_charecter($data) . '</div>';
        endif;
    }

    public function font_awesome_render($data) {
        if (empty($data) || $data == ''):
            return;
        endif;
        if ($this->admin == 'woocommerce'):
            $key = $this->keys[$this->childkeys];
            if (isset($this->arg[$key]['custom_icon'])):
                $data = $this->arg[$key]['custom_icon'];
            endif;

        endif;

        $fadata = get_option('oxi_addons_font_awesome');
        if ($fadata != 'no'):
            wp_enqueue_style('font-awsome.min', OXI_TABS_URL . 'assets/frontend/css/font-awsome.min.css', false, OXI_TABS_PLUGIN_VERSION);
        endif;
        $files = '<i class="' . $data . ' oxi-icons"></i>';
        return $files;
    }

    public function image_special_render($id = '', $array = []) {
        $value = $this->media_render($id, $array);
        if (!empty($value)):
            return ' <img  class=\'oxi-tabs-header-li-image\' ' . $value . '>';
        endif;
    }

    public function admin_edit_panel($id) {
        $data = '';
        if ($this->admin == 'admin'):
            $data = '   <div class="oxi-addons-admin-absulote">
                            <div class="oxi-addons-admin-absulate-edit">
                                <button class="btn btn-primary shortcode-addons-template-item-edit" type="button" value="' . $id . '">Edit</button>
                            </div>
                            <div class="oxi-addons-admin-absulate-delete">
                                <button class="btn btn-danger shortcode-addons-template-item-delete" type="submit" value="' . $id . '">Delete</button>
                            </div>
                        </div>';
        endif;
        return $data;
    }

   

}
