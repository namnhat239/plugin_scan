<?php

namespace OXI_TABS_PLUGINS\Extension\WooCommerce;

/**
 * Description of Admin
 *
 * @author biplo
 */
class Admin {

    // instance container
    private static $instance = null;

    /**
     * Define $wpdb
     *
     * @since 3.1.0
     */
    public $database;

    public static function instance() {
        if (self::$instance == null) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function render_html() {
        global $post;

        $tab_data = maybe_unserialize(get_post_meta($post->ID, '_oxilab_tabs_woo_data', true));
        $tab_data = is_array($tab_data) ? $tab_data : array();
        ?>
        <div class="woo-oxilab-tabs-admin-container">
            <div class="woo-oxilab-tabs-admin-header">
                <h3>Custom Tabs</h3>
                <p>Add Custom Tabs for this Product include tabs priority value and custom callback function if you want.</p>
            </div>
            <div class="woo-oxilab-tabs-admin-body">
                <?php
                $this->render_tabs_data($tab_data);
                ?>
            </div>
            <?php
            $this->generate_new_tabs();
            ?>
        </div>
        <?php
    }

    protected function render_tabs_data($tab_data) {
        $i = 1;
        foreach ($tab_data as $tab) {
            ?>
            <div class="woo-oxilab-tabs-admin-tabs oxi-hidden">
                <div class="oxi-woo-header">
                    <div class="oxi-woo-header-text"><?php echo isset($tab['title']) ? $tab['title'] : 'Tabs Title'; ?></div>
                    <div class="oxi-delete-button"></div>
                </div>
                <div class="woo-oxi-content">
                    <?php
                    $this->woocommerce_wp_text_input('layouts', $tab);
                    $this->woocommerce_wp_wysiwyg_input('layouts', $tab, $i);
                    ?>
                </div>
            </div>
            <?php
            $i++;
        }
    }

    protected function woocommerce_wp_text_input($i, $tab) {

        woocommerce_wp_text_input(
                array(
                    'id' => '_oxilab_tabs_woo_' . $i . '_tab_title_[]',
                    'label' => __('Tab Title', OXI_TABS_TEXTDOMAIN),
                    'description' => '',
                    'value' => isset($tab['title']) ? $tab['title'] : 'Tabs Title',
                    'placeholder' => __('Tab Title', OXI_TABS_TEXTDOMAIN),
                    'class' => 'oxilab_tabs_woo_' . $i . '_title_field'
                )
        );
        woocommerce_wp_text_input(
                array(
                    'id' => '_oxilab_tabs_woo_' . $i . '_tab_priority_[]',
                    'label' => __('Tab Priority', OXI_TABS_TEXTDOMAIN),
                    'description' => '',
                    'value' => isset($tab['priority']) ? $tab['priority'] : 0,
                    'placeholder' => __('Tabs Priority', OXI_TABS_TEXTDOMAIN),
                    'class' => 'oxilab_tabs_woo_' . $i . '_priority_field'
                )
        );
        woocommerce_wp_text_input(
                array(
                    'id' => '_oxilab_tabs_woo_' . $i . '_tab_callback_[]',
                    'label' => __('Callback Function', OXI_TABS_TEXTDOMAIN),
                    'description' => '',
                    'value' => isset($tab['callback']) ? $tab['callback'] : '',
                    'placeholder' => __('Add callback function else make it blank', OXI_TABS_TEXTDOMAIN),
                    'class' => 'oxilab_tabs_woo_' . $i . '_callback_field'
                )
        );
    }

    protected function woocommerce_wp_wysiwyg_input($i, $tab, $key = '') {
        echo '<div class="form-field-tinymce _oxilab_tabs_woo_layouts_content_field _oxilab_tabs_woo_layouts_tab_content_' . $i . '_field">';
        if (!isset($tab['content'])):
            $tab['content'] = '';
        endif;
        $editor_settings = array(
            'textarea_name' => '_oxilab_tabs_woo_' . $i . '_tab_content_[]'
        );

        wp_editor($tab['content'], '_oxilab_tabs_woo_' . $i . '_tab_content_' . $key, $editor_settings);

        if (isset($tab['description']) && $tab['description']) {
            echo '<span class="description">' . $tab['description'] . '</span>';
        }

        echo '</div>';
    }

    protected function generate_new_tabs() {
        ?>
        <div class="oxi-woo-tabs-add-rows">

            <div class="oxi-woo-tabs-add-rows-button">
                Add Field
            </div>
            <div class="oxi-woo-tabs-add-rows-store">
                <div class="woo-oxilab-tabs-admin-tabs">
                    <div class="oxi-woo-header">
                        <div class="oxi-woo-header-text">Text Tabs</div>
                        <div class="oxi-delete-button"></div>
                    </div>
                    <div class="woo-oxi-content">
                        <?php
                        $this->woocommerce_wp_text_input('store', ['title' => 'Tabs Title', 'priority' => 0, 'callback' => '']);
                        echo '<p class="form-field-tinymce _oxilab_tabs_woo_store_tab_content_field">       <textarea class="_oxilab_tabs_woo_store_tab_content_" name="_oxilab_tabs_woo_store_tab_content_" id="_oxilab_tabs_woo_store_tab_content_" placeholder="HTML and text to display" rows="2" cols="20" style="width:100%; min-height:10rem;"></textarea> ';
                        echo '</p>';
                        ?>
                    </div>
                </div>
            </div>
            <?php ?>
        </div>
        <?php
    }

}
