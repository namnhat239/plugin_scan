<?php
class WeblizarTwitter extends WP_Widget
{

    public function __construct()
    {
        parent::__construct(
            'weblizar_twitter', // Base ID
            esc_html__('Customize Feeds for Twitter Widget', 'twitter-tweets'), // Name
            array('description' => esc_html__('Display latest tweets from your Twitter account', 'twitter-tweets'))
        );
    }

    /*** Front-end display of widget. ***/
    public function widget($args, $instance)
    {
        // Outputs the content of the widget
        extract($args); // Make before_widget, etc available.
        $title = apply_filters('title', $instance['title']);
        echo wp_kses_post($before_widget);
        if (!empty($title)) {
            echo wp_kses_post($before_title . $title . $after_title);
        }
        $TwitterUserName  = $instance['TwitterUserName'];
        $Theme            = $instance['Theme'];
        $Height           = $instance['Height'];
        $Width            = $instance['Width'];
        $ExcludeReplies   = $instance['ExcludeReplies'];
        $AutoExpandPhotos = $instance['AutoExpandPhotos'];
        $TwitterWidgetId  = $instance['TwitterWidgetId'];
        $tw_language      = $instance['tw_language']; ?>
        <div style="display:block;width:100%;float:left;overflow:hidden">
            <a class="twitter-timeline" data-dnt="true" href="https://twitter.com/<?php echo esc_attr($TwitterUserName); ?>" min-width="<?php echo esc_attr($Width); ?>" height="<?php echo esc_attr($Height); ?>" data-theme="<?php echo esc_attr($Theme); ?>" data-lang="<?php echo esc_attr($tw_language); ?>"></a>

        </div>
    <?php
        echo wp_kses_post($after_widget);
    }

    /** Back-end widget form. **/
    public function form($instance)
    {
        if (isset($instance['TwitterUserName'])) {
            $TwitterUserName = $instance['TwitterUserName'];
        } else {
            $TwitterUserName = 'weblizar';
        }
        if (isset($instance['Theme'])) {
            $Theme = $instance['Theme'];
        } else {
            $Theme = 'light';
        }
        if (isset($instance['Height'])) {
            $Height = $instance['Height'];
        } else {
            $Height = '450';
        }

        if (isset($instance['Width'])) {
            $Width = $instance['Width'];
        } else {
            $Width = '450';
        }

        if (isset($instance['ExcludeReplies'])) {
            $ExcludeReplies = $instance['ExcludeReplies'];
        } else {
            $ExcludeReplies = 'yes';
        }

        if (isset($instance['AutoExpandPhotos'])) {
            $AutoExpandPhotos = $instance['AutoExpandPhotos'];
        } else {
            $AutoExpandPhotos = 'yes';
        }

        if (isset($instance['tw_language'])) {
            $tw_language = $instance['tw_language'];
        } else {
            $tw_language = '';
        }
        if (isset($instance['TwitterWidgetId'])) {
            $TwitterWidgetId = $instance['TwitterWidgetId'];
        } else {
            $TwitterWidgetId = '';
        }

        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = esc_html__('Tweets', 'Widget Title Here', 'twitter-tweets');
        }
    ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'twitter-tweets'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" placeholder="<?php esc_attr_e('Enter Widget Title', 'twitter-tweets'); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('TwitterUserName')); ?>"><?php esc_html_e('Twitter Username', 'twitter-tweets'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('TwitterUserName')); ?>" name="<?php echo esc_attr($this->get_field_name('TwitterUserName')); ?>" type="text" value="<?php echo esc_attr($TwitterUserName); ?>" placeholder="<?php esc_attr_e('Enter Your Twitter Account Username', 'twitter-tweets'); ?>">
        </p>
        <p>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('TwitterWidgetId')); ?>" name="<?php echo esc_attr($this->get_field_name('TwitterWidgetId')); ?>" type="hidden" value="<?php echo esc_attr($TwitterWidgetId); ?>" placeholder="<?php esc_attr_e('Enter Your Twitter Widget ID', 'twitter-tweets'); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('Theme')); ?>"><?php esc_html_e('Theme', 'twitter-tweets'); ?></label>
            <select id="<?php echo esc_attr($this->get_field_id('Theme')); ?>" name="<?php echo esc_attr($this->get_field_name('Theme')); ?>">
               <option value="<?php echo esc_attr('light');?>" <?php selected( $Theme, 'light' );?>><?php esc_html_e( 'Light', 'twitter-tweets' ); ?></option>
									<option value="<?php echo esc_attr('dark');?>"  <?php selected( $Theme, 'Dark' );?>><?php esc_html_e( 'Dark', 'twitter-tweets' ); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('Height')); ?>"><?php esc_html_e('Height', 'twitter-tweets'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('Height')); ?>" name="<?php echo esc_attr($this->get_field_name('Height')); ?>" type="text" value="<?php echo esc_attr($Height); ?>">
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('ExcludeReplies')); ?>"><?php esc_html_e('Exclude Replies on Tweets', 'twitter-tweets'); ?></label>
            <select id="<?php echo esc_attr($this->get_field_id('ExcludeReplies')); ?>" name="<?php echo esc_attr($this->get_field_name('ExcludeReplies')); ?>">
									<option value="<?php echo esc_attr('yes');?>" <?php selected( $ExcludeReplies, 'yes' );?>><?php esc_html_e( 'Yes', 'twitter-tweets' ); ?></option>
									<option value="<?php echo esc_attr('no');?>" <?php selected( $ExcludeReplies, 'no' );?> ><?php esc_html_e( 'No', 'twitter-tweets' ); ?></option>
								</select>
            </select>
        </p>
<?php
    }
    /*
	  Sanitize widget form values as they are saved.
	  @see WP_Widget::update()
	  @param array $new_instance Values just sent to be saved.
	  @param array $old_instance Previously saved values from database.
	  @return array Updated safe values to be saved.
	*/
    public function update($new_instance, $old_instance)
    {
        $instance         = array();
        $title            = sanitize_text_field((!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : 'Widget Title Here');
        $TwitterUserName  = sanitize_text_field((!empty($new_instance['TwitterUserName'])) ? strip_tags($new_instance['TwitterUserName']) : '');
        $Theme            = sanitize_text_field('theme', (!empty($new_instance['Theme'])) ? strip_tags($new_instance['Theme']) : 'light');
        $Height           = sanitize_text_field((!empty($new_instance['Height'])) ? strip_tags($new_instance['Height']) : '450');
        $Width            = sanitize_text_field((!empty($new_instance['Width'])) ? strip_tags($new_instance['Width']) : '');
        $ExcludeReplies   = sanitize_text_field((!empty($new_instance['ExcludeReplies'])) ? strip_tags($new_instance['ExcludeReplies']) : 'yes');
        $AutoExpandPhotos = sanitize_text_field((!empty($new_instance['AutoExpandPhotos'])) ? strip_tags($new_instance['AutoExpandPhotos']) : 'yes');
        $TwitterWidgetId  = sanitize_text_field((!empty($new_instance['TwitterWidgetId'])) ? strip_tags($new_instance['TwitterWidgetId']) : '');
        $tw_language      = sanitize_text_field((!empty($new_instance['tw_language'])) ? strip_tags($new_instance['tw_language']) : '');

        $instance['title']            = $title;
        $instance['TwitterUserName']  = $TwitterUserName;
        $instance['Theme']            = $Theme;
        $instance['Height']           = $Height;
        $instance['ExcludeReplies']   = $ExcludeReplies;
        $instance['AutoExpandPhotos'] = $AutoExpandPhotos;
        $instance['TwitterWidgetId']  = $TwitterWidgetId;
        $instance['tw_language']      = $tw_language;
        $instance['Width']            = $Width;
        return $instance;
    }
}
// end of class WeblizarTwitter
// register WeblizarTwitter widget
function WeblizarTwitterWidget()
{
    register_widget('WeblizarTwitter');
}
add_action('widgets_init', 'WeblizarTwitterWidget');
