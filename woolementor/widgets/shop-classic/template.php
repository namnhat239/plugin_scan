<?php
$widget_id  = $args['widget_id'];

if ( !wcd_is_pro_activated() && !wcd_is_preview_mode() && !wcd_is_edit_mode() ) {
    $wishlist_show_hide = 'no';
}

$settings   = $args['settings'];

$products   = wcd_query_products( $settings );     
$user_id    = get_current_user_id();
$wishlist   = wcd_get_wishlist( $user_id );

extract( $settings );
?>

<div class="wl-sc-product-style wl-shop-wrapper">
    <?php do_action( 'codesigner_before_shop_loop' ); ?>
	<div class="cx-container">
        <div class="cx-grid">
        <?php
        if( $products->have_posts()) : 
            while( $products->have_posts()) : $products->the_post();
                $product_id = get_the_ID();
                $product    = wc_get_product( $product_id );
                $thumbnail  = get_the_post_thumbnail_url( $product_id, $image_thumbnail_size );
                $fav_product= in_array( $product_id, $wishlist );

                if ( !empty( $fav_product ) ) {
                    $fav_item = 'fav-item';
                }
                else {
                    $fav_item = '';
                }
                ?>

                <div class="wl-sc-single-product <?php echo esc_attr( $alignment ); ?>">
                    <?php do_action( 'codesigner_before_shop_loop_item' ); ?>

                    <div class="wl-sc-single-widget">
                        <?php if ( 'yes' == $sale_ribbon_show_hide && $product->is_on_sale() ): ?>
                            <div class="wl-sc-corner-ribbon">
                                <?php
                                printf( '<span>%1$s</span>',
                                    esc_html( $settings['sale_ribbon_text' ] )
                                );
                                ?>
                            </div>
                        <?php endif;

                        if ( 'yes' == $image_show_hide ): ?>
                            <div class="wl-sc-product-img">

                                <?php if ( 'none' == $image_on_click ): ?>
                                    <img src="<?php echo esc_html( $thumbnail ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>"/>  
                                <?php elseif ( 'zoom' == $image_on_click ) : ?>
                                    <a class="wl-sc-product-image-zoom" href="<?php echo esc_url( $thumbnail ); ?>"><img src="<?php echo esc_url( $thumbnail ); ?>" alt=""/></a>
                                <?php elseif ( 'product_page' == $image_on_click ) : ?>
                                    <a href="<?php the_permalink(); ?>">
                                        <img src="<?php echo esc_url( $thumbnail ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>"/>                              
                                    </a>
                                <?php endif; ?>

                            </div>
                        <?php endif;

                        if( 'outofstock' == $product->get_stock_status() && 'yes' == $stock_show_hide ): ?>
                            <div class="wl-sc-stock">
                                <?php echo esc_html( $stock_ribbon_text ); ?>
                            </div>
                        <?php endif; ?>

                        <div class="wl-sc-product-details">
                            <div class="wl-sc-product-info">
                                <?php do_action( 'codesigner_shop_loop_item_title' ); ?>
                                <div class="wl-sc-product-name"><a class="wl-gradient-heading" href="<?php the_permalink(); ?>"><?php echo esc_html( $product->get_name() ); ?></a></div>
                                <?php do_action( 'codesigner_after_shop_loop_item_title' ); ?>

                                <h2 class="wl-sc-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></h2>
                            </div>
                            <div class="wl-sc-info-icons">
                                <?php 
                                if ( 'yes' == $wishlist_show_hide ): ?>
                                    <div class="wl-sc-product-fav ajax_add_to_wish <?php echo esc_attr( $fav_item ); ?>" data-product_id="<?php esc_attr_e( $product_id ); ?>">
                                        <i class="<?php echo esc_attr( $wishlist_icon['value'] ); ?>"></i>
                                    </div>
                                <?php endif;

                                do_action( 'codesigner_before_cart_button', $product, $widget_id, $settings );

                                if ( 'yes' == $cart_show_hide ): ?>
                                    <?php if ( 'simple' == $product->get_type() ): ?>
                                        <div class="wl-cart-area">
                                            <a href="?add-to-cart=<?php esc_attr_e( $product_id ); ?>" data-quantity="1" class="wl-sc-product-cart button product_type_<?php echo esc_attr( $product->get_type() ); ?> add_to_cart_button ajax_add_to_cart" data-product_id="<?php esc_attr_e( $product_id ); ?>" ><i class="<?php echo esc_attr( $cart_icon['value'] ); ?>"></i></a>
                                        </div>
                                    <?php else: ?>
                                        <div class="wl-cart-area">
                                            <a href="<?php echo get_permalink( $product_id ); ?>" data-quantity="1" class="wl-sc-product-cart button product_type_<?php echo esc_attr( $product->get_type() ); ?>" data-product_id="<?php esc_attr_e( $product_id ); ?>" ><i class="<?php echo esc_attr( $cart_icon['value'] ); ?>"></i></a>
                                        </div>
                                    <?php endif;
                                endif; 

                                do_action( 'codesigner_after_cart_button', $product, $widget_id, $settings );
                                ?>
                            </div>
                        </div>
                    </div>

                    <?php do_action( 'codesigner_after_shop_loop_item' ); ?>
                </div>

            <?php endwhile; wp_reset_query(); else: 

            echo "<p>" . __( 'No Product Found!', 'woolementor' ) . "</p>";

        endif;
        ?>
        </div>
	</div>
    <?php do_action( 'codesigner_after_shop_loop' ); ?>
</div>

<?php 
if ( 'yes' == $pagination_show_hide ):

    $class = '';
    if ( defined('DOING_AJAX') && DOING_AJAX ) {
        $class = 'wl-ajax-filter-pagination';
    }
    echo "<div class='wl-sc-pagination ".esc_attr( $class )."'>";
    
    /**
    * woolementor pagination
    */
    wcd_pagination( $products, $pagination_left_icon, $pagination_right_icon ); 

    echo '</div>';
endif;

?>
<script>
    jQuery(function($){
        $(".wl-sc-product-image-zoom").fancybox({
            arrows: true,
            'transitionIn'  :   'elastic',
            'transitionOut' :   'elastic',
            'speedIn'       :   600, 
            'speedOut'      :   200, 
            'overlayShow'   :   false

        }).attr('data-fancybox', 'gallery');
    })
</script>