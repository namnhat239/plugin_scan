<?php
$disable_media = (isset( $aptf_settings['disable_image'] )) ? $aptf_settings['disable_image'] : 0;
$disable_media = ($disable_media == 1) ? true : false;
?>
<?php
if ( !$disable_media ) {?>
    <?php
    if ( isset( $tweet->extended_entities ) ) {
        ?>
        <div class="aptf-tweet-media">
            <?php
            if ( count( $tweet->extended_entities->media ) > 1 ) {
                $image_size = 'thumb';
            } else {
                $image_size = 'large';
            }

            //$this->print_array($tweet['extended_entities']);
            foreach ( $tweet->extended_entities->media as $media ) {
                ?>
                <div class="aptf-each-media aptf-media-<?php echo $image_size; ?>" <?php
                if ( isset( $aptf_settings['image_width'] )  && $aptf_settings['image_width']!='') {
                    ?>
                     style="width:<?php echo esc_attr($aptf_settings['image_width']);?>"
                        <?php 
                }
                ?>>
                    <a href="<?php echo str_replace('http://','https://',$media->media_url); ?>" data-lightbox="<?php echo esc_attr($username); ?>"><img src="<?php echo str_replace('http://','https://',$media->media_url) . ':' . $image_size; ?>" style="max-width: 100%;"/></a>
                </div>
                <?php
                //                $this->print_array($media);
            }
            ?>
        </div>
        <?php
    }else if(isset($tweet->retweeted_status->extended_entities)){
        ?> 
        <div class="aptf-tweet-media">
            <?php
            if ( isset($tweet->retweeted_status->extended_entities->media) && count( $tweet->retweeted_status->extended_entities->media ) > 1 ) {
                $image_size = 'thumb';
            } else {
                $image_size = 'large';
            }
           
            //$this->print_array($tweet['extended_entities']);
            foreach ( $tweet->retweeted_status->extended_entities->media as $media ) {
                ?>
                <div class="aptf-each-media aptf-media-<?php echo $image_size; ?>" <?php
                if ( isset( $aptf_settings['image_width'] )  && $aptf_settings['image_width']!='') {
                    ?>
                     style="width:<?php echo esc_attr($aptf_settings['image_width']);?>"
                        <?php 
                }
                ?>>
                    <a href="<?php echo str_replace('http://','https://',$media->media_url); ?>" data-lightbox="<?php echo esc_attr($username); ?>"><img src="<?php echo str_replace('http://','https://',$media->media_url) . ':' . $image_size; ?>" style="max-width: 100%;"/></a>
                </div>
                <?php
                //$this->print_array($media);
            }
            ?>
        </div>
        <?php 
    }
    else if(isset($tweet->quoted_status->extended_entities)){
        ?> 
        <div class="aptf-tweet-media">
            <?php
            if ( isset($tweet->quoted_status->extended_entities->media) && count( $tweet->quoted_status->extended_entities->media ) > 1 ) {
                $image_size = 'thumb';
            } else {
                $image_size = 'large';
            }
           
            //$this->print_array($tweet['extended_entities']);
            foreach ( $tweet->quoted_status->extended_entities->media as $media ) {
                ?>
                <div class="aptf-each-media aptf-media-<?php echo $image_size; ?>" <?php
                if ( isset( $aptf_settings['image_width'] )  && $aptf_settings['image_width']!='') {
                    ?>
                     style="width:<?php echo esc_attr($aptf_settings['image_width']);?>"
                        <?php 
                }
                ?>>
                    <a href="<?php echo str_replace('http://','https://',$media->media_url); ?>" data-lightbox="<?php echo esc_attr($username); ?>"><img src="<?php echo str_replace('http://','https://',$media->media_url) . ':' . $image_size; ?>" style="max-width: 100%;"/></a>
                </div>
                <?php
                //$this->print_array($media);
            }
            ?>
        </div>
        <?php 
    }
}
?>