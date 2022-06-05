<?php

namespace OXI_TABS_PLUGINS\Classes;

/**
 * Description of Tabs Rest API
 *
 * @author $biplob018
 */
class Build_Api {

    /**
     * Define $wpdb
     *
     * @since 3.3.0
     */
    public $database;
    public $request;
    public $rawdata;
    public $styleid;
    public $childid;

    const RESPONSIVE_TABS_ALL_STYLE = 'get_all_oxi_responsive_tabs_style';
    const API = 'https://www.oxilabdemos.com/responsive-tabs/wp-json/responsivetabsapi/v2/';

    /**
     * Constructor of plugin class
     *
     * @since 3.3.0
     */
    public function __construct() {
        $this->database = new \OXI_TABS_PLUGINS\Helper\Database();
        $this->build_api();
    }

    public function fixed_data($agr) {
        return hex2bin($agr);
    }

    public function build_api() {
        add_action('rest_api_init', function () {
            register_rest_route(untrailingslashit('oxilabtabsultimate/v1/'), '/(?P<action>\w+)/', array(
                'methods' => array('GET', 'POST'),
                'callback' => [$this, 'api_action'],
                'permission_callback' => array($this, 'get_permissions_check'),
            ));
        });
    }

    public function get_permissions_check($request) {
        $user_role = get_option('oxi_addons_user_permission');
        $role_object = get_role($user_role);
        $first_key = '';
        if (isset($role_object->capabilities) && is_array($role_object->capabilities)) {
            reset($role_object->capabilities);
            $first_key = key($role_object->capabilities);
        } else {
            $first_key = 'manage_options';
        }
        return current_user_can($first_key);
    }

    public function api_action($request) {
        $this->request = $request;
        $wpnonce = $request['_wpnonce'];
        if (!wp_verify_nonce($wpnonce, 'wp_rest')):
            return new \WP_REST_Request('Invalid URL', 422);
        endif;
        $this->rawdata = addslashes($request['rawdata']);
        $this->styleid = $request['styleid'];
        $this->childid = $request['childid'];
        $action_class = strtolower($request->get_method()) . '_' . sanitize_key($request['action']);
        if (method_exists($this, $action_class)):
            return $this->{$action_class}();
        endif;
        return 'Silence is Golden';
    }

    public function array_replace($arr = [], $search = '', $replace = '') {
        array_walk($arr, function (&$v) use ($search, $replace) {
            $v = str_replace($search, $replace, $v);
        });
        return $arr;
    }

    /**
     * Generate safe path
     * @since v1.0.0
     */
    public function safe_path($path) {

        $path = str_replace(['//', '\\\\'], ['/', '\\'], $path);
        return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
    }

    public function post_create_new() {
        delete_transient(self::RESPONSIVE_TABS_ALL_STYLE);
        if (!empty($this->styleid)):
            $styleid = (int) $this->styleid;
            $newdata = $this->database->wpdb->get_row($this->database->wpdb->prepare('SELECT * FROM ' . $this->database->parent_table . ' WHERE id = %d ', $styleid), ARRAY_A);
            $old = false;
            if (array_key_exists('css', $newdata) && $newdata['css'] != ''):
                $old = true;
                $this->database->wpdb->query($this->database->wpdb->prepare("INSERT INTO {$this->database->parent_table} (name, style_name, rawdata, css) VALUES (%s, %s, %s, %s)", array($this->rawdata, $newdata['style_name'], $newdata['rawdata'], $newdata['css'])));
            else:
                $this->database->wpdb->query($this->database->wpdb->prepare("INSERT INTO {$this->database->parent_table} (name, style_name, rawdata) VALUES ( %s, %s, %s)", array($this->rawdata, $newdata['style_name'], $newdata['rawdata'])));
            endif;
            $redirect_id = $this->database->wpdb->insert_id;
            if ($redirect_id > 0):
                if ($old == true):
                    $child = $this->database->wpdb->get_results($this->database->wpdb->prepare("SELECT * FROM {$this->database->child_table} WHERE styleid = %d ORDER by id ASC", $styleid), ARRAY_A);
                    foreach ($child as $value) {
                        $this->database->wpdb->query($this->database->wpdb->prepare("INSERT INTO {$this->database->child_table} (styleid, rawdata, title, files, css) VALUES (%d, %s, %s, %s, %s)", array($redirect_id, $value['rawdata'], $value['title'], $value['files'], $value['css'])));
                    }
                else:
                    $raw = json_decode(stripslashes($newdata['rawdata']), true);
                    $raw['style-id'] = $redirect_id;
                    $name = ucfirst($newdata['style_name']);
                    $CLASS = '\OXI_TABS_PLUGINS\Render\Admin\\' . $name;
                    $C = new $CLASS('admin');
                    $f = $C->template_css_render($raw);
                    $child = $this->database->wpdb->get_results($this->database->wpdb->prepare("SELECT * FROM {$this->database->child_table} WHERE styleid = %d ORDER by id ASC", $styleid), ARRAY_A);
                    foreach ($child as $value) {
                        $this->database->wpdb->query($this->database->wpdb->prepare("INSERT INTO {$this->database->child_table} (styleid, rawdata) VALUES (%d, %s)", array($redirect_id, $value['rawdata'])));
                    }
                endif;
                return admin_url("admin.php?page=oxi-tabs-ultimate-new&styleid=$redirect_id");
            endif;
        else:

            $params = json_decode(stripslashes($this->rawdata), true);
            $folder = $this->safe_path(OXI_TABS_PATH . 'Render/Json/');
            $filename = 'responsive-tabs-and-accordions-ultimateand' . $params['responsive-tabs-template-id'] . '.json';
            return $this->post_json_import($folder, $filename, $params['addons-style-name']);

        endif;
    }

    public function post_json_import($folder, $filename, $name = 'truee') {
        if (is_file($folder . $filename)) {
            $this->rawdata = file_get_contents($folder . $filename);
            $params = json_decode($this->rawdata, true);
            $style = $params['style'];
            $child = $params['child'];
            if ($name != 'truee'):
                $style['name'] = $name;

            endif;

            $this->database->wpdb->query($this->database->wpdb->prepare("INSERT INTO {$this->database->parent_table} (name, style_name, rawdata) VALUES ( %s, %s, %s)", array($style['name'], $style['style_name'], $style['rawdata'])));
            $redirect_id = $this->database->wpdb->insert_id;

            if ($redirect_id > 0):
                $raw = json_decode(stripslashes($style['rawdata']), true);
                $raw['style-id'] = $redirect_id;
                $style_name = ucfirst($style['style_name']);
                $CLASS = '\OXI_TABS_PLUGINS\Render\Admin\\' . $style_name;
                $C = new $CLASS('admin');

                $f = $C->template_css_render($raw);
                foreach ($child as $value) {
                    $this->database->wpdb->query($this->database->wpdb->prepare("INSERT INTO {$this->database->child_table} (styleid, rawdata) VALUES (%d,  %s)", array($redirect_id, $value['rawdata'])));
                }
                if ($name != 'truee'):
                    return admin_url("admin.php?page=oxi-tabs-ultimate-new&styleid=$redirect_id");
                endif;

            endif;
        }
    }

    public function post_shortcode_delete() {
        delete_transient(self::RESPONSIVE_TABS_ALL_STYLE);
        $styleid = (int) $this->styleid;
        if ($styleid):
            $this->database->wpdb->query($this->database->wpdb->prepare("DELETE FROM {$this->database->parent_table} WHERE id = %d", $styleid));
            $this->database->wpdb->query($this->database->wpdb->prepare("DELETE FROM {$this->database->child_table} WHERE styleid = %d", $styleid));
            return 'done';
        else:
            return 'Silence is Golden';
        endif;
    }

    public function get_shortcode_export() {
        $styleid = (int) $this->styleid;
        if ($styleid):
            $style = $this->database->wpdb->get_row($this->database->wpdb->prepare("SELECT * FROM {$this->database->parent_table} WHERE id = %d", $styleid), ARRAY_A);
            $child = $this->database->wpdb->get_results($this->database->wpdb->prepare("SELECT * FROM {$this->database->child_table} WHERE styleid = %d ORDER by id ASC", $styleid), ARRAY_A);
            $filename = 'responsive-tabs-and-accordions-ultimateand' . $style['id'] . '.json';
            $files = [
                'style' => $style,
                'child' => $child,
            ];
            $finalfiles = json_encode($files);
            $this->send_file_headers($filename, strlen($finalfiles));
            @ob_end_clean();
            flush();
            echo $finalfiles;
            die;
        else:
            return 'Silence is Golden';
        endif;
    }

    /**
     * Send file headers.
     *
     *
     * @param string $file_name File name.
     * @param int    $file_size File size.
     */
    private function send_file_headers($file_name, $file_size) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $file_name);
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . $file_size);
    }

    public function post_shortcode_deactive() {
        $params = json_decode(stripslashes($this->rawdata), true);
        $id = (int) $params['oxideletestyle'];
        $tabs = 'responsive-tabs';
        if ($id > 0):
            $this->database->wpdb->query($this->database->wpdb->prepare("DELETE FROM {$this->database->import_table} WHERE name = %s and type = %s", $id, $tabs));
            return 'done';
        else:
            return 'Silence is Golden';
        endif;
    }

    public function post_shortcode_active() {
        $params = json_decode(stripslashes($this->rawdata), true);
        $id = (int) $params['oxiimportstyle'];
        $tabs = 'responsive-tabs';
        if ($id > 0):
            $this->database->wpdb->query($this->database->wpdb->prepare("INSERT INTO {$this->database->import_table} (type, name) VALUES (%s, %s)", array($tabs, $id)));
            return admin_url("admin.php?page=oxi-tabs-ultimate-new#Style" . $id);
        else:
            return 'Silence is Golden';
        endif;
    }

    /**
     * Template Style Data
     *
     * @since 9.3.0
     */
    public function post_elements_template_style() {
        $settings = json_decode(stripslashes($this->rawdata), true);
        $StyleName = sanitize_text_field($settings['style-name']);
        $stylesheet = '';
        if ((int) $this->styleid):
            $transient = 'oxi-responsive-tabs-transient-' . $this->styleid;
            delete_transient($transient);
            $this->database->wpdb->query($this->database->wpdb->prepare("UPDATE {$this->database->parent_table} SET rawdata = %s, stylesheet = %s WHERE id = %d", $this->rawdata, $stylesheet, $this->styleid));
            $name = ucfirst($StyleName);
            $cls = '\OXI_TABS_PLUGINS\Render\Admin\\' . $name;
            $CLASS = new $cls('admin');
            return $CLASS->template_css_render($settings);
        endif;
    }

    /**
     * Template Style Data
     *
     * @since 9.3.0
     */
    public function post_template_change() {
        $rawdata = sanitize_text_field($this->rawdata);
        if ((int) $this->styleid):
            $this->database->wpdb->query($this->database->wpdb->prepare("UPDATE {$this->database->parent_table} SET style_name = %s WHERE id = %d", $rawdata, $this->styleid));
            return admin_url("admin.php?page=oxi-tabs-ultimate-new&styleid=$this->styleid");
        endif;
    }

    /**
     * Template Name Change
     *
     * @since 9.3.0
     */
    public function post_template_name() {
        delete_transient(self::RESPONSIVE_TABS_ALL_STYLE);
        $settings = json_decode(stripslashes($this->rawdata), true);
        $name = sanitize_text_field($settings['addonsstylename']);
        $id = $settings['addonsstylenameid'];
        if ((int) $id):
            $this->database->wpdb->query($this->database->wpdb->prepare("UPDATE {$this->database->parent_table} SET name = %s WHERE id = %d", $name, $id));
            return 'success';
        endif;
        return 'Silence is Golden';
    }

    /**
     * Template Name Change
     *
     * @since 9.3.0
     */
    public function post_elements_rearrange_modal_data() {
        if ((int) $this->styleid):
            $child = $this->database->wpdb->get_results($this->database->wpdb->prepare("SELECT * FROM {$this->database->child_table} WHERE styleid = %d ORDER by id ASC", $this->styleid), ARRAY_A);
            $render = [];
            foreach ($child as $k => $value) {
                $data = json_decode(stripcslashes($value['rawdata']));
                $render[$value['id']] = $data;
            }
            return json_encode($render);
        endif;
        return 'Silence is Golden';
    }

    /**
     * Template Name Change
     *
     * @since 9.3.0
     */
    public function post_elements_older_rearrange_modal_data() {
        $rawdata = json_decode(stripslashes($this->rawdata), true);
        if ((int) $this->styleid && count($rawdata) == 2):
            $child = $this->database->wpdb->get_results($this->database->wpdb->prepare("SELECT * FROM {$this->database->child_table} WHERE styleid = %d ORDER by id ASC", $this->styleid), ARRAY_A);
            $render = [];
            foreach ($child as $k => $value) {
                $data = explode($rawdata[1], $value['title']);
                $render[$value['id']] = $data[$rawdata[0]];
            }
            return json_encode($render);
        endif;
        return 'Silence is Golden';
    }

    /**
     * Template Name Change
     *
     * @since 9.3.0
     */
    public function post_elements_template_rearrange_save_data() {
        $params = explode(',', $this->rawdata);
        foreach ($params as $value) {
            if ((int) $value):
                $data = $this->database->wpdb->get_row($this->database->wpdb->prepare("SELECT * FROM {$this->database->child_table} WHERE id = %d ", $value), ARRAY_A);
                if (array_key_exists('title', $data)):
                    $this->database->wpdb->query($this->database->wpdb->prepare("INSERT INTO {$this->database->child_table} (styleid, rawdata, title, files, css) VALUES (%d, %s, %s, %s, %s)", array($data['styleid'], $data['rawdata'], $data['title'], $data['files'], $data['css'])));
                else:
                    $this->database->wpdb->query($this->database->wpdb->prepare("INSERT INTO {$this->database->child_table} (styleid, rawdata) VALUES (%d, %s)", array($data['styleid'], $data['rawdata'])));
                endif;
                $redirect_id = $this->database->wpdb->insert_id;
                if ($redirect_id == 0) {
                    return;
                }
                if ($redirect_id != 0) {
                    $this->database->wpdb->query($this->database->wpdb->prepare("DELETE FROM {$this->database->child_table} WHERE id = %d", $value));
                }
            endif;
        }
        return 'success';
    }

    /**
     * Template Modal Data
     *
     * @since 9.3.0
     */
    public function post_elements_template_modal_data() {
        if ((int) $this->styleid):
            if ((int) $this->childid):
                $this->database->wpdb->query($this->database->wpdb->prepare("UPDATE {$this->database->child_table} SET rawdata = %s WHERE id = %d", $this->rawdata, $this->childid));
            else:
                $this->database->wpdb->query($this->database->wpdb->prepare("INSERT INTO {$this->database->child_table} (styleid, rawdata) VALUES (%d, %s )", array($this->styleid, $this->rawdata)));
            endif;
        endif;
        return 'ok';
    }

    /**
     * Template Template Render
     *
     * @since 9.3.0
     */
    public function post_elements_template_render_data() {
        $transient = 'oxi-responsive-tabs-transient-' . $this->styleid;
        set_transient($transient, $this->rawdata, 1 * HOUR_IN_SECONDS);
        return 'Transient Done';
    }

    /**
     * Template Modal Data Edit Form
     *
     * @since 9.3.0
     */
    public function post_elements_template_modal_data_edit() {
        if ((int) $this->childid):
            $listdata = $this->database->wpdb->get_row($this->database->wpdb->prepare("SELECT * FROM {$this->database->child_table} WHERE id = %d ", $this->childid), ARRAY_A);
            $returnfile = json_decode(stripslashes($listdata['rawdata']), true);
            $returnfile['shortcodeitemid'] = $this->childid;
            return json_encode($returnfile);
        else:
            return 'Silence is Golden';
        endif;
    }

    /**
     * Template Child Delete Data
     *
     * @since 9.3.0
     */
    public function post_elements_template_modal_data_delete() {
        if ((int) $this->childid):
            $this->database->wpdb->query($this->database->wpdb->prepare("DELETE FROM {$this->database->child_table} WHERE id = %d ", $this->childid));
            return 'done';
        else:
            return 'Silence is Golden';
        endif;
    }

    /**
     * Admin Notice API  loader
     * @return void
     */
    public function post_oxi_recommended() {
        $data = 'done';
        update_option('responsive_tabs_with_accordions_recommended', $data);
        return $data;
    }

    /**
     * Admin Notice Recommended  loader
     * @return void
     */
    public function post_notice_dissmiss() {
        $notice = $this->request['notice'];
        if ($notice == 'maybe'):
            $data = strtotime("now");
            update_option('responsive_tabs_with_accordions_activation_date', $data);
        else:
            update_option('responsive_tabs_with_accordions_no_bug', $notice);
        endif;
        return $notice;
    }

   

    /**
     * Admin Settings
     * @return void
     */
    public function post_oxi_addons_user_permission() {
        if (!current_user_can('manage_options')) {
            return;
        }
        $rawdata = json_decode(stripslashes($this->rawdata), true);
        $value = sanitize_text_field($rawdata['value']);
        update_option('oxi_addons_user_permission', $value);
        return '<span class="oxi-confirmation-success"></span>';
    }
    
    /**
     * Admin Settings
     * @return void
     */
    public function post_oxi_addons_font_awesome() {
        if (!current_user_can('manage_options')) {
            return;
        }
        $rawdata = json_decode(stripslashes($this->rawdata), true);
        $value = sanitize_text_field($rawdata['value']);
        update_option('oxi_addons_font_awesome', $value);
        return '<span class="oxi-confirmation-success"></span>';
    }

    /**
     * Admin Settings
     * @return void
     */
    public function post_oxilab_tabs_woocommerce() {
        if (!current_user_can('manage_options')) {
            return;
        }
         $rawdata = json_decode(stripslashes($this->rawdata), true);
        $value = sanitize_text_field($rawdata['value']);
        update_option('oxilab_tabs_woocommerce', $value);
        return '<span class="oxi-confirmation-success"></span>';
    }

    /**
     * Admin Settings
     * @return void
     */
    public function post_oxi_tabs_use_the_content() {
        if (!current_user_can('manage_options')) {
            return;
        }
       $rawdata = json_decode(stripslashes($this->rawdata), true);
        $value = sanitize_text_field($rawdata['value']);
        update_option('oxi_tabs_use_the_content', $value);
        return '<span class="oxi-confirmation-success"></span>';
    }

    /**
     * Admin Settings
     * @return void
     */
    public function post_oxilab_tabs_woocommerce_default() {
        if (!current_user_can('manage_options')) {
            return;
        }
         $rawdata = json_decode(stripslashes($this->rawdata), true);
        $value = sanitize_text_field($rawdata['value']);
        update_option('oxilab_tabs_woocommerce_default', $value);
        return '<span class="oxi-confirmation-success"></span>';
    }

    /**
     * Admin Settings
     * @return void
     */
    public function post_customize_default_tabs() {
        if (!current_user_can('manage_options')) {
            return;
        }
        update_option('oxilab_tabs_woocommerce_customize_default_tabs', $this->rawdata);
        return '<span class="oxi-confirmation-success"></span>';
    }
    

    /**
     * Admin Settings
     * @return void
     */
    public function post_oxi_addons_fixed_header_size() {
        if (!current_user_can('manage_options')) {
            return;
        }
        $rawdata = json_decode(stripslashes($this->rawdata), true);
        $value = sanitize_text_field($rawdata['value']);
        update_option('oxi_addons_fixed_header_size', $value);
        return '<span class="oxi-confirmation-success"></span>';
    }


   

    /**
     * Admin License
     * @return void
     */
    public function post_oxi_license() {
        $rawdata = json_decode(stripslashes($this->rawdata), true);
        $new = $rawdata['license'];
        $old = get_option('responsive_tabs_with_accordions_license_key');
        $status = get_option('responsive_tabs_with_accordions_license_status');
        if ($new == ''):
            if ($old != '' && $status == 'valid'):
                $this->deactivate_license($old);
            endif;
            delete_option('responsive_tabs_with_accordions_license_key');
            $data = ['massage' => '<span class="oxi-confirmation-blank"></span>', 'text' => ''];
        else:
            update_option('responsive_tabs_with_accordions_license_key', $new);
            delete_option('responsive_tabs_with_accordions_license_status');
            $r = $this->activate_license($new);
            if ($r == 'success'):
                $data = ['massage' => '<span class="oxi-confirmation-success"></span>', 'text' => 'Active'];
            else:
                $data = ['massage' => '<span class="oxi-confirmation-failed"></span>', 'text' => $r];
            endif;
        endif;
        return $data;
    }

    public function activate_license($key) {
        $api_params = array(
            'edd_action' => 'activate_license',
            'license' => $key,
            'item_name' => urlencode('Responsive Tabs'),
            'url' => home_url()
        );

        $response = wp_remote_post('https://www.oxilab.org', array('timeout' => 15, 'sslverify' => false, 'body' => $api_params));

        if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {
            if (is_wp_error($response)) {
                $message = $response->get_error_message();
            } else {
                $message = __('An error occurred, please try again.');
            }
        } else {
            $license_data = json_decode(wp_remote_retrieve_body($response));

            if (false === $license_data->success) {

                switch ($license_data->error) {

                    case 'expired' :

                        $message = sprintf(
                                __('Your license key expired on %s.'), date_i18n(get_option('date_format'), strtotime($license_data->expires, current_time('timestamp')))
                        );
                        break;

                    case 'revoked' :

                        $message = __('Your license key has been disabled.');
                        break;

                    case 'missing' :

                        $message = __('Invalid license.');
                        break;

                    case 'invalid' :
                    case 'site_inactive' :

                        $message = __('Your license is not active for this URL.');
                        break;

                    case 'item_name_mismatch' :

                        $message = sprintf(__('This appears to be an invalid license key for %s.'), Responsive_Tabs_with_Accordions);
                        break;

                    case 'no_activations_left':

                        $message = __('Your license key has reached its activation limit.');
                        break;

                    default :

                        $message = __('An error occurred, please try again.');
                        break;
                }
            }
        }

        if (!empty($message)) {
            return $message;
        }
        update_option('responsive_tabs_with_accordions_license_status', $license_data->license);
        return 'success';
    }

    public function deactivate_license($key) {
        $api_params = array(
            'edd_action' => 'deactivate_license',
            'license' => $key,
            'item_name' => urlencode('Responsive Tabs'),
            'url' => home_url()
        );
        $response = wp_remote_post('https://www.oxilab.org', array('timeout' => 15, 'sslverify' => false, 'body' => $api_params));
        if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {

            if (is_wp_error($response)) {
                $message = $response->get_error_message();
            } else {
                $message = __('An error occurred, please try again.');
            }
            return $message;
        }
        $license_data = json_decode(wp_remote_retrieve_body($response));
        if ($license_data->license == 'deactivated') {
            delete_option('responsive_tabs_with_accordions_license_status');
            delete_option('responsive_tabs_with_accordions_license_key');
        }
        return 'success';
    }

//    Web Template Data
//    Only for Oxilab Development
//



    public function post_web_template() {
        $styleid = (int) $this->styleid;
        $template = $this->local_tempalte('style' . $styleid);
        return $template;
    }

    public function local_tempalte($template) {
        $URL = self::API . 'template/' . $this->styleid;
        $request = wp_remote_request($URL);
        if (!is_wp_error($request)) {
            $response = json_decode(wp_remote_retrieve_body($request), true);
        } else {
            return $request->get_error_message();
        }

        $data = json_decode($response, true);
        $render = '';
        $vs = get_option($this->fixed_data('726573706f6e736976655f746162735f776974685f6163636f7264696f6e735f6c6963656e73655f737461747573'));
        foreach ($data as $key => $value) {
            if ($vs == $this->fixed_data('76616c6964')) {
                $button = '<button type="button" class="btn btn-success oxi-addons-addons-web-template-import-button" web-data="' . $key . '">Import</button>';
            } else {
                $button = '<button class="btn btn-warning oxi-addons-addons-style-btn-warning" title="Pro Only" type="submit" value="pro only" name="addonsstyleproonly">Pro Only</button>';
            }
            $render .= '<div class="oxi-addons-col-1">
                                    <div class="oxi-addons-style-preview">
                                        <div class="oxi-addons-style-preview-top oxi-addons-center">
                                            <img class="oxi-addons-web-template-image" src="' . $value['image'] . '">
                                        </div>
                                        <div class="oxi-addons-style-preview-bottom">
                                            <div class="oxi-addons-style-preview-bottom-left">
                                                ' . $value['name'] . '
                                            </div>
                                            <div class="oxi-addons-style-preview-bottom-right">
                                                ' . $button . '
                                            </div>
                                        </div>
                                    </div>
                                </div>';
        }
        return $render;
    }

    public function post_web_import() {
        delete_transient(self::RESPONSIVE_TABS_ALL_STYLE);
        if ((int) $this->styleid):
            $URL = self::API . 'files/' . $this->styleid;
            $request = wp_remote_request($URL);
            if (!is_wp_error($request)) {
                $response = json_decode(wp_remote_retrieve_body($request), true);
            } else {
                return $request->get_error_message();
            }
            $rawdata = json_decode($response, true);
            $style = $rawdata['style'];
            $child = $rawdata['child'];

            $this->database->wpdb->query($this->database->wpdb->prepare("INSERT INTO {$this->database->parent_table} (name, style_name, rawdata) VALUES ( %s, %s, %s)", array($style['name'], $style['style_name'], $style['rawdata'])));
            $redirect_id = $this->database->wpdb->insert_id;
            if ($redirect_id > 0):
                $raw = json_decode(stripslashes($style['rawdata']), true);
                $raw['style-id'] = $redirect_id;
                $name = ucfirst($style['style_name']);
                $CLASS = '\OXI_TABS_PLUGINS\Render\Admin\\' . $name;
                $C = new $CLASS('admin');
                $f = $C->template_css_render($raw);
                foreach ($child as $value) {
                    $this->database->wpdb->query($this->database->wpdb->prepare("INSERT INTO {$this->database->child_table} (styleid, rawdata) VALUES (%d,  %s)", array($redirect_id, $value['rawdata'])));
                }
                return admin_url("admin.php?page=oxi-tabs-ultimate-new&styleid=$redirect_id");
            endif;
        endif;
    }

}
