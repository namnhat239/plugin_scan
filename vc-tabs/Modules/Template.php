<?php

namespace OXI_TABS_PLUGINS\Modules;

/**
 * Oxi Tabs Template 
 *
 * @since 3.3
 *
 * @author biplob018
 */
// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

class Template {

    use \OXI_TABS_PLUGINS\Helper\Public_Helper;

    /**
     * Current Elements ID
     *
     * @since 3.3.0
     */
    public $oxiid;

    /**
     * Define $wpdb
     *
     * @since 3.1.0
     */
    public $database;

    /**
     * Template constructor.
     */
    public function __construct() {
        $this->database = new \OXI_TABS_PLUGINS\Helper\Database();
        if (!function_exists('wp_print_media_templates')) {
            require_once ABSPATH . WPINC . '/media-template.php';
        }
        add_action('admin_menu', array($this, 'add_dashboard_page'));
        add_action('admin_init', array($this, 'maybe_load_template'));
        add_action('network_admin_menu', array($this, 'add_dashboard_page'));
    }

    /**
     * Register page through WordPress's hooks.
     */
    public function add_dashboard_page() {
        add_dashboard_page('', '', 'read', 'oxi-tabs-style-view', '');
    }

    public function maybe_load_template() {
        $this->oxiid = (!empty($_GET['styleid']) ? (int) $_GET['styleid'] : '');
        $page = (isset($_GET['page']) ? $_GET['page'] : '');
        if ('oxi-tabs-style-view' !== $page || $this->oxiid < 0) {
            return;
        }
        // Don't load the interface if doing an ajax call.
        if (defined('DOING_AJAX') && DOING_AJAX) {
            return;
        }
        set_current_screen();
        // Remove an action in the Gutenberg plugin ( not core Gutenberg ) which throws an error.
        remove_action('admin_print_styles', 'gutenberg_block_editor_admin_print_styles');
        $this->load_template();
    }

    private function load_template() {
        $this->enqueue_scripts();
        $this->template_header();
        $this->template_content();
        $this->template_footer();

        exit;
    }

    public function template_header() {
        ?>
        <!DOCTYPE html>
        <html <?php language_attributes(); ?>>
            <meta name="viewport" content="width=device-width"/>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
            <title><?php esc_html_e('Responsive Tabs &rsaquo; Admin template', OXI_TABS_TEXTDOMAIN); ?></title>
        <?php wp_head(); ?>
        </head>
        <body class="shortcode-addons-template-body" id="shortcode-addons-template-body">
        <?php
    }

    /**
     * Outputs the content of the current step.
     */
    public function template_content() {
        if ($this->oxiid > 0):
            $this->shortcode_render($this->oxiid, 'admin');
        endif;
    }

    /**
     * Outputs the simplified footer.
     */
    public function template_footer() {
        ?>
            <?php wp_footer(); ?>
        </body>
        </html>
        <?php
    }

    public function enqueue_scripts() {
        wp_enqueue_style('oxilab-tabs-bootstrap', OXI_TABS_URL . 'assets/backend/css/bootstrap.min.css', false, OXI_TABS_PLUGIN_VERSION);
        wp_enqueue_style('font-awsome.min', OXI_TABS_URL . 'assets/frontend/css/font-awsome.min.css', false, OXI_TABS_PLUGIN_VERSION);
        wp_enqueue_style('oxilab-admin-css', OXI_TABS_URL . 'assets/backend/css/admin.css', false, OXI_TABS_PLUGIN_VERSION);
        wp_enqueue_style('oxilab-template-css', OXI_TABS_URL . 'assets/backend/css/template.css', false, OXI_TABS_PLUGIN_VERSION);
        wp_enqueue_script('oxilab-template-js', OXI_TABS_URL . 'assets/backend/custom/template.js', false, OXI_TABS_PLUGIN_VERSION);
    }

}
