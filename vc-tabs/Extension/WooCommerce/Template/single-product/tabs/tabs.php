<?php
if (!defined('ABSPATH')) {
    exit;
}




$check_customization = json_decode(stripslashes(get_option('oxilab_tabs_woocommerce_customize_default_tabs')), true);

if (is_array($check_customization) && count($check_customization) > 1):
    $customize = [];
    foreach ($check_customization as $key => $value) {
        if (isset($value['title']) && $value['title'] != ''):
            $customize['title'][$key] = $value['title'];
        endif;
        if (isset($value['icon']) && $value['icon'] != ''):
            $customize['icon'][$key] = $value['icon'];
        endif;
        if (isset($value['priority']) && $value['priority'] != ''):
            $customize['priority'][$key] = $value['priority'];
        endif;
        if (isset($value['callback']) && $value['callback'] != ''):
            $customize['callback'][$key] = $value['callback'];
        endif;
        if (isset($value['unset']) && $value['unset'] == 'on'):
            $customize['unset'][$key] = $value['unset'];
        endif;
    }
    if (count($customize) > 0):
        update_option('oxi_product_tabs_customize', $customize);
        add_filter('woocommerce_product_tabs', 'oxi_remove_product_tabs', 98);
    endif;

    function oxi_remove_product_tabs($tabs) {
        $customize = get_option('oxi_product_tabs_customize');
        if (isset($customize['unset'])):
            foreach ($customize['unset'] as $k => $value) {
                if (isset($tabs[$k])):
                    unset($tabs[$k]);
                endif;
            }
        endif;
        if (isset($customize['title'])):
            foreach ($customize['title'] as $k => $value) {
                if (isset($tabs[$k])):
                    $tabs[$k]['title'] = $value;
                endif;
            }
        endif;
        if (isset($customize['icon'])):
            foreach ($customize['icon'] as $k => $value) {
                if (isset($tabs[$k])):
                    $tabs[$k]['custom_icon'] = $value;
                endif;
            }
        endif;
        if (isset($customize['priority'])):
            foreach ($customize['priority'] as $k => $value) {
                if (isset($tabs[$k])):
                    $tabs[$k]['priority'] = $value;
                endif;
            }
        endif;
        if (isset($customize['callback'])):
            foreach ($value as $k => $value) {
                if (isset($tabs[$k])):
                    $tabs[$k]['callback'] = $value;
                endif;
            }

        endif;
        return $tabs;
    }

endif;

$product_tabs = apply_filters('woocommerce_product_tabs', array());
if (!empty($product_tabs)) :
    global $product;
    $currenttabs = get_post_meta($product->get_ID(), '_oxilab_tabs_woo_layouts');
    if (empty($currenttabs) || $currenttabs == ''):
        $currenttabs = get_option('oxilab_tabs_woocommerce_default');
    endif;
    if ((int) $currenttabs):
        $tabs = [];
        $i = 0;
        foreach ($product_tabs as $key => $product_tab) :
            $tabs[$i] = $key;
            $i++;
        endforeach;

        echo '<div class="woocommerce-tabs wc-tabs-wrapper">';
        $render = new \OXI_TABS_PLUGINS\Modules\Shortcode();
        $render->render($currenttabs, 'woocommerce', $product_tabs, $tabs);
        echo '</div>';
        do_action('woocommerce_product_after_tabs');
    else:
        ?>
        <div class="woocommerce-tabs wc-tabs-wrapper">
            <ul class="tabs wc-tabs" role="tablist">
                <?php foreach ($product_tabs as $key => $product_tab) : ?>
                    <li class="<?php echo esc_attr($key); ?>_tab" id="tab-title-<?php echo esc_attr($key); ?>" role="tab" aria-controls="tab-<?php echo esc_attr($key); ?>">
                        <a href="#tab-<?php echo esc_attr($key); ?>">
                            <?php echo wp_kses_post(apply_filters('woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key)); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <?php foreach ($product_tabs as $key => $product_tab) : ?>
                <div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--<?php echo esc_attr($key); ?> panel entry-content wc-tab" id="tab-<?php echo esc_attr($key); ?>" role="tabpanel" aria-labelledby="tab-title-<?php echo esc_attr($key); ?>">
                    <?php
                    if (isset($product_tab['callback'])) {
                        call_user_func($product_tab['callback'], $key, $product_tab);
                    }
                    ?>
                </div>
            <?php endforeach; ?>

            <?php do_action('woocommerce_product_after_tabs'); ?>
        </div>
    <?php
    endif;
    
endif;

