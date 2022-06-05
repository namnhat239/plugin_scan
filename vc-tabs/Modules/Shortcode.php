<?php

namespace OXI_TABS_PLUGINS\Modules;

/**
 * Description of Shortcode
 *
 * @author biplo
 */
class Shortcode {

    /**
     * Current Elements ID
     *
     * @since 3.3.0
     */
    public $oxiid;

    /**
     * Current User
     *
     * @since 3.3.0
     */
    public $user;

    /**
     * Current arg
     *
     * @since 3.3.0
     */
    public $arg;

    /**
     * WooCommerce keys
     *
     * @since 3.3.0
     */
    public $key;

    /**
     * Define $wpdb
     *
     * @since 3.1.0
     */
    public $database;

    /**
     * Define All Tabs Style Name
     *
     * @since 3.1.0
     */
    public $style_list;

    const RESPONSIVE_TABS_ALL_STYLE = 'get_all_oxi_responsive_tabs_style';

    // instance container
    private static $instance = null;

    public static function instance() {
        if (self::$instance == null) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Template constructor.
     */
    public function __construct() {
        $this->database = new \OXI_TABS_PLUGINS\Helper\Database();
    }

    public function get_all_style() {
        $response = get_transient(self::RESPONSIVE_TABS_ALL_STYLE);
        if (!$response) {
            $rows = $this->database->wpdb->get_results("SELECT id, name FROM " . $this->database->parent_table . " ORDER BY id DESC", ARRAY_A);
            $response = ['' => 'Default Tabs'];
            foreach ($rows as $key => $value):
                $response[$value['id']] = !empty($value['name']) ? $value['name'] : 'Shortcode ' . $value['id'];
            endforeach;
            ksort($response);
            set_transient(self::RESPONSIVE_TABS_ALL_STYLE, $response, 30 * DAY_IN_SECONDS);
        }
        return $response;
    }

    /**
     * Template constructor.
     */

    public function shortcode() {
        $style = $this->database->wpdb->get_row($this->database->wpdb->prepare('SELECT * FROM ' . $this->database->parent_table . ' WHERE id = %d ', $this->oxiid), ARRAY_A);
        if (!is_array($style) && $this->user == 'woocommerce'):
            global $product;
            delete_post_meta($product->get_ID(), '_oxilab_tabs_woo_layouts');
            $default_tabs = get_option('oxilab_tabs_woocommerce_default');
            $style = $this->database->wpdb->get_row($this->database->wpdb->prepare('SELECT * FROM ' . $this->database->parent_table . ' WHERE id = %d ', $default_tabs), ARRAY_A);
            if (!is_array($style)):
                delete_option('oxilab_tabs_woocommerce_default');
                return false;
            endif;
        endif;
        if (!is_array($style)):
            echo '**Empty Layouts, Kindly check your shortcode data**';
            return false;
        endif;

        if (!array_key_exists('rawdata', $style)):
            $Installation = new \OXI_TABS_PLUGINS\Classes\Installation();
            $Installation->Datatase();
        endif;
        $child = $this->database->wpdb->get_results($this->database->wpdb->prepare("SELECT * FROM {$this->database->child_table} WHERE styleid = %d ORDER by id ASC", $this->oxiid), ARRAY_A);
        if ($this->user == 'woocommerce'):
            $current = count($child);
            $woo = count($this->arg);

            if ($current > $woo):
                for ($i = $woo; $i < $current; $i++):
                    unset($child[$i]);
                endfor;
            else:

                for ($i = $current; $i < $woo; $i++):
                    $child[$i] = $child[0];
                endfor;
            endif;
        endif;

        $template = ucfirst($style['style_name']);
        $row = json_decode(stripslashes($style['rawdata']), true);
        if (is_array($row)):
            $cls = '\OXI_TABS_PLUGINS\Render\Views\\' . $template;
        else:
            $cls = '\OXI_TABS_PLUGINS\Render\Old_Views\\' . $template;
        endif;
        if (class_exists($cls)):
            new $cls($style, $child, $this->user, $this->arg, $this->key);
        endif;
    }

    public function render($styleid, $user = 'public', $arg = [], $keys = []) {
        if (empty((int) $styleid) || empty($user)):
            return false;
        endif;
        $this->oxiid = $styleid;
        $this->user = $user;
        $this->arg = $arg;
        $this->key = $keys;
        $this->shortcode();
    }
}
