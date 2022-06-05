<?php

class ufbl_Widget extends WP_Widget {

	public function __construct() {
        $widget_ops = array(
            'classname' => 'ufbl_widget',
            'description' => 'Ultimate Form Builder Lite - Widget',
        );
        parent::__construct('ufbl_widget', 'Ultimate Form Builder Lite', $widget_ops);
    }

    public function form($instance) {

        // outputs the options form on admin

        $title = isset($instance['title'])?$instance['title']:'';

        $form_id = isset($instance['form_id'])?$instance['form_id']:'';

        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_attr_e('Title:', 'ultimate-form-builder-lite'); ?></label>

            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">

            <label for="<?php echo esc_attr($this->get_field_id('form_id')); ?>"><?php esc_attr_e('Choose form:', 'ultimate-form-builder-lite'); ?></label>
            <br>
            <?php
                global $wpdb;
                $ufbl_sql = 'select * from '.UFBL_FORM_TABLE;
                $ufbl_form_results = $wpdb->get_results($ufbl_sql, ARRAY_A); ?>
                <select class="widefat" name="<?php esc_attr_e($this->get_field_name('form_id')); ?>">
                    <option value=""><?php esc_html_e('Select form','ufbl'); ?></option>
                <?php
                foreach($ufbl_form_results as $ufbl_form){
                    if($ufbl_form['form_status'] === '1') { ?>
                    <option value="<?php esc_attr_e($ufbl_form['form_id']); ?>"><?php esc_html_e($ufbl_form['form_title']); ?></option>
                    <?php }
                } ?>
                </select>
        </p>
        <?php }

    public function widget($args, $instance) {
        // outputs the content of the widget
        echo $args[ 'before_widget' ];
        if ( !empty($instance[ 'title' ]) ) {
            echo $args[ 'before_title' ] . apply_filters('widget_title', $instance[ 'title' ]) . $args[ 'after_title' ];
        }        
        $tid = $instance[ 'form_id' ];
        $shtc = '[ufbl form_id="'.$tid.'"]';
        if (!empty ($tid) ){
            echo do_shortcode($shtc);
        }
        echo $args[ 'after_widget' ];
        
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance[ 'title' ] = (!empty($new_instance[ 'title' ]) ) ? sanitize_text_field($new_instance[ 'title' ]) : '';
        $instance['form_id'] = (!empty($new_instance['form_id']) ) ? sanitize_text_field($new_instance['form_id']) : '';

        return $instance;
    }
}
