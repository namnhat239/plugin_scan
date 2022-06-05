<?php

namespace OXI_TABS_PLUGINS\Page;

/**
 * Description of Create
 *
 * @author biplo
 */
class Create {

    /**
     * Define $wpdb
     *
     * @since 3.1.0
     */
    public $database;
    public $local_template;

    /**
     * Define Page Type
     *
     * @since 3.1.0
     */
    public $layouts;

    use \OXI_TABS_PLUGINS\Helper\Public_Helper;
    use \OXI_TABS_PLUGINS\Helper\CSS_JS_Loader;

    public $IMPORT = [];
    public $TEMPLATE;

    /**
     * Constructor of Oxilab tabs Home Page
     *
     * @since 2.0.0
     */
    public function __construct() {
        $this->database = new \OXI_TABS_PLUGINS\Helper\Database();
        $this->layouts = (isset($_GET) ? $_GET : '');
        $this->CSSJS_load();
        $this->Render();
    }

   

    /**
     * Admin Notice JS file loader
     * @return void
     */
    public function admin_ajax_load() {
        wp_enqueue_script("jquery");
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-widget');
        wp_enqueue_script('jquery-ui-mouse');
        wp_enqueue_script('jquery-ui-accordion');
        wp_enqueue_script('jquery-ui-autocomplete');
        wp_enqueue_script('jquery-ui-slider');
        wp_enqueue_script('jquery-ui-draggable');
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('oxi-tabs-create', OXI_TABS_URL . '/assets/backend/custom/create.js', false, OXI_TABS_PLUGIN_VERSION);
    }

    public function get_local_tempalte() {
        $basename = array_map('basename', glob(OXI_TABS_PATH . 'Render/Json/' . '*.json', GLOB_BRACE));
        foreach ($basename as $key => $value) {
            $onlyname = explode('ultimateand', str_replace('.json', '', $value))[1];
            if ((int) $onlyname):
                $this->local_template[$onlyname] = $value;
            endif;
        }
        ksort($this->local_template);
        return;
    }

    /**
     * Generate safe path
     * @since v1.0.0
     */
    public function safe_path($path) {

        $path = str_replace(['//', '\\\\'], ['/', '\\'], $path);
        return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
    }

    public function Render() {
        ?>
        <div class="oxi-addons-row">
            <?php
            if (array_key_exists('import', $this->layouts)):
                $this->Import_header();
                $this->Import_template();
            else:
                $this->Create_header();
                $this->Create_template();
            endif;
            ?>
        </div>
        <?php
    }

    public function Import_header() {
        ?>
        <div class="oxi-addons-wrapper">
            <div class="oxi-addons-import-layouts">
                <h1>Responsive Tabs › Import Template
                </h1>
                <p> Select Tabs layout and import for future use. </p>
            </div>
        </div>
        <?php
    }

    public function Create_header() {
        ?>
        <div class="oxi-addons-wrapper">
            <div class="oxi-addons-import-layouts">
                <h1>Responsive Tabs › Create New
                </h1>
                <p> Select Tabs layouts, give your Tabs name and create new Tabs. </p>
            </div>
        </div>
        <?php
    }

    public function Create_new() {
        echo _('<div class="oxi-addons-row">
                        <div class="oxi-addons-col-1 oxi-import">
                            <div class="oxi-addons-style-preview">
                                <div class="oxilab-admin-style-preview-top">
                                    <a href="' . admin_url("admin.php?page=oxi-tabs-ultimate-new&import") . '">
                                        <div class="oxilab-admin-add-new-item">
                                            <span>
                                                <i class="fas fa-plus-circle oxi-icons"></i>  
                                                Import Templates
                                            </span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>');

        echo __('<div class="modal fade" id="oxi-addons-style-create-modal" >
                        <form method="post" id="oxi-addons-style-modal-form">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content">
                                    <div class="modal-header">                    
                                        <h4 class="modal-title">New Tabs</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <div class=" form-group row">
                                            <label for="addons-style-name" class="col-sm-6 col-form-label" oxi-addons-tooltip="Give your Shortcode Name Here">Name</label>
                                            <div class="col-sm-6 addons-dtm-laptop-lock">
                                                <input class="form-control" type="text" value="" id="addons-style-name"  name="addons-style-name">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <input type="hidden" id="responsive-tabs-template-id" name="responsive-tabs-template-id" value="">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        <input type="submit" class="btn btn-success" name="addonsdatasubmit" id="addonsdatasubmit" value="Save">
                                      </div>
                                </div>
                            </div>
                        </form>
                    </div>');
        ?>
        <div class="modal fade" tabindex="-1" role="dialog" id="oxi-addons-style-web-template" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Web Template</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    public function Create_template() {
        $create_new = 'false';
        ?>
        <div class="oxi-addons-row">
            <?php
            foreach ($this->IMPORT as $value) {
                $Style = 'Style' . $value;

                if (array_key_exists($value, $this->local_template)):
                    $folder = $this->safe_path(OXI_TABS_PATH . 'Render/Json/');
                    $template_data = json_decode(file_get_contents($folder . $this->local_template[$value]), true);
                    $C = 'OXI_TABS_PLUGINS\Render\Views\\' . $Style;
                    ?>
                    <div class="oxi-addons-col-1" id="<?php echo $Style; ?>">
                        <div class="oxi-addons-style-preview">
                            <div class="oxi-addons-style-preview-top oxi-addons-center">
                                <?php
                                if (class_exists($C) && isset($template_data['style']['rawdata'])):
                                    new $C($template_data['style'], $template_data['child']);
                                endif;
                                ?>
                            </div>
                            <div class="oxi-addons-style-preview-bottom">
                                <div class="oxi-addons-style-preview-bottom-left">
                                    <?php echo $template_data['style']['name']; ?>
                                </div>
                                <div class="oxi-addons-style-preview-bottom-right">
                                    <form method="post" style=" display: inline-block; " class="shortcode-addons-template-deactive">
                                        <input type="hidden" name="oxideletestyle" value="<?php echo $value; ?>">
                                        <button class="btn btn-warning oxi-addons-addons-style-btn-warning" title="Delete"  type="submit" value="Deactive" name="addonsstyledelete">Deactive</button>  
                                    </form>
                                    <button type="button" class="btn btn-info oxi-addons-addons-web-template" template-id="<?php echo $value; ?>">Web Template</button>
                                    <button type="button" class="btn btn-success oxi-addons-addons-template-create oxi-addons-addons-js-create" data-toggle="modal" template-id="<?php echo $value; ?>">Create Style</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                endif;
            }
            ?>
        </div>
        <?php
        $this->Create_new();
    }

    public function Import_template() {
        ?>
        <div class="oxi-addons-row">
            <?php
            foreach ($this->local_template as $id => $value) {
                if (!array_key_exists($id, $this->IMPORT)):
                    $folder = $this->safe_path(OXI_TABS_PATH . 'Render/Json/');

                    $template_data = json_decode(file_get_contents($folder . $value), true);
                    $C = 'OXI_TABS_PLUGINS\Render\Views\\Style' . ucfirst($id);
                    ?>
                    <div class="oxi-addons-col-1" id="Style<?php echo $id; ?>">
                        <div class="oxi-addons-style-preview">
                            <div class="oxi-addons-style-preview-top oxi-addons-center">
                                <?php
                                if (class_exists($C) && isset($template_data['style']['rawdata'])):
                                    new $C($template_data['style'], $template_data['child']);
                                endif;
                                ?>
                            </div>
                            <div class="oxi-addons-style-preview-bottom">
                                <div class="oxi-addons-style-preview-bottom-left">
                                    <?php echo $template_data['style']['name']; ?>
                                </div>
                                <div class="oxi-addons-style-preview-bottom-right">
                                    <?php
                                    if ($id > 7 && apply_filters('oxi-tabs-plugin/pro_version', true) == false):
                                        ?>
                                        <form method="post" style=" display: inline-block; " class="shortcode-addons-template-pro-only">
                                            <button class="btn btn-warning oxi-addons-addons-style-btn-warning" title="Pro Only"  type="submit" value="pro only" name="addonsstyleproonly">Pro Only</button>  
                                        </form>
                                        <?php
                                    else:
                                        ?>
                                        <form method="post" style=" display: inline-block; " class="shortcode-addons-template-import">
                                            <input type="hidden" name="oxiimportstyle" value="<?php echo $id; ?>">
                                            <button class="btn btn-success oxi-addons-addons-template-create" title="import"  type="submit" value="Import" name="addonsstyleimport">Import</button>  
                                        </form>
                                    <?php
                                    endif;
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                endif;
            }
            ?>
        </div>
        <?php
    }
 public function CSSJS_load() {
        $this->admin_css_loader();
        $this->admin_ajax_load();
        apply_filters('oxi-tabs-plugin/admin_menu', TRUE);
        $template = $this->database->wpdb->get_results($this->database->wpdb->prepare("SELECT * FROM {$this->database->import_table} WHERE type = %s ORDER by name ASC", 'responsive-tabs'), ARRAY_A);
        if (count($template) < 1):
            for ($i = 1; $i < 5; $i++) {
                $this->database->wpdb->query($this->database->wpdb->prepare("INSERT INTO {$this->database->import_table} (type, name) VALUES (%s, %s)", array('responsive-tabs', $i)));
                $this->IMPORT[$i] = $i;
            }
        else:
            foreach ($template as $value) {
                $this->IMPORT[(int) $value['name']] = $value['name'];
            }
        endif;
        ksort($this->IMPORT);
        $this->get_local_tempalte();
    }
}
