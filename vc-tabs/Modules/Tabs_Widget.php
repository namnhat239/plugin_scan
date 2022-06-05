<?php

namespace OXI_TABS_PLUGINS\Modules;

class Tabs_Widget extends \WP_Widget {

    function __construct() {
        parent::__construct(
                'responsive_tabs_with_accordions_widget', __('Responsive Tabs with Accordions', 'responsive_tabs_with_accordions_widget_widget'), array('description' => __('Responsive Tabs with Accordions Widget', 'responsive_tabs_with_accordions_widget_widget'),)
        );
    }

    public function tabs_register_tabswidget() {
        register_widget($this);
    }

    public function widget($args, $instance) {
        $title = apply_filters('widget_title', $instance['title']);
        echo $args['before_widget'];
        echo \OXI_TABS_PLUGINS\Classes\Bootstrap::instance()->shortcode_render($title, 'user');
        echo $args['after_widget'];
    }
  public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title']) ) ? strip_tags($new_instance['title']) : '';
        return $instance;
    }
    public function form($instance) {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = __('1', 'responsive_tabs_with_accordions_widget_widget');
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Style ID:'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <?php
    }

  

}
