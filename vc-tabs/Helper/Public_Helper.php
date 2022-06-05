<?php

namespace OXI_TABS_PLUGINS\Helper;

/**
 *
 * @author biplo
 */
trait Public_Helper {

    public function font_familly_charecter($data) {
        wp_enqueue_style('' . $data . '', 'https://fonts.googleapis.com/css?family=' . $data . '');
        $data = str_replace('+', ' ', $data);
        $data = explode(':', $data);
        $data = $data[0];
        $data = '"' . $data . '"';
        return $data;
    }

    public function admin_special_charecter($data) {
        $data = html_entity_decode($data);
        $data = str_replace("\'", "'", $data);
        $data = str_replace('\"', '"', $data);
        return $data;
    }

    public function icon_font_selector($data) {
        $icon = explode(' ', $data);
        $fadata = get_option('oxi_addons_font_awesome');
        $faversion = get_option('oxi_addons_font_awesome_version');
        $faversion = explode('||', $faversion);
        if ($fadata != 'no') {
            wp_enqueue_style('font-awesome-' . $faversion[0], $faversion[1]);
        }
        $files = '<i class="' . $data . ' oxi-icons"></i>';
        return $files;
    }

    public function html_special_charecter($data) {
        $data = html_entity_decode($data);
        $data = str_replace("\'", "'", $data);
        $data = str_replace('\"', '"', $data);
        $data = do_shortcode($data, $ignore_html = false);
        return $data;
    }

    /**
     * Plugin Name Convert to View
     *
     * @since 2.0.0
     */
    public function name_converter($data) {
        $data = str_replace('tyle', 'tyle ', $data);
        return ucwords($data);
    }

    public function str_replace_first($from, $to, $content) {
        $from = '/' . preg_quote($from, '/') . '/';

        return preg_replace($from, $to, $content, 1);
    }

    public function shortcode_render($styleid, $user = 'public') {
        if (!empty((int) $styleid) && !empty($user)):


            $style = $this->database->wpdb->get_row($this->database->wpdb->prepare('SELECT * FROM ' . $this->database->parent_table . ' WHERE id = %d ', $styleid), ARRAY_A);
            if (!is_array($style) || (is_array($style) && !array_key_exists('rawdata', $style))):
                $Installation = new \OXI_TABS_PLUGINS\Classes\Installation();
                $Installation->Datatase();
                return;
            endif;
            if ($user == 'admin'):
                $response = get_transient('oxi-responsive-tabs-transient-' . $styleid);
                if ($response):
                    $new = [
                        'rawdata' => $response,
                        'stylesheet' => '',
                        'font_family' => ''
                    ];
                    $style = array_merge($style, $new);
                endif;
            endif;
            $child = $this->database->wpdb->get_results($this->database->wpdb->prepare("SELECT * FROM {$this->database->child_table} WHERE styleid = %d ORDER by id ASC", $styleid), ARRAY_A);
            $template = ucfirst($style['style_name']);
            $row = json_decode(stripslashes($style['rawdata']), true);

            if (is_array($row)):
                $cls = '\OXI_TABS_PLUGINS\Render\Views\\' . $template;
            else:
                $cls = '\OXI_TABS_PLUGINS\Render\Old_Views\\' . $template;
            endif;
            if (class_exists($cls)):
                new $cls($style, $child, $user);
            endif;
        endif;
    }

}
