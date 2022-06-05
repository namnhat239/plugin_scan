<?php if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * @var array $rules
 */

$prefix = 'category';

?>

<tr class="form-field">
    <th scope="row" valign="top">
        <label for="attribute_label">Tiered pricing</label>
    </th>
    <td>

        <p class="form-field" data-tiered-price-type-percentage data-tiered-price-type>
        <span data-price-rules-wrapper>
            <span data-price-rules-container>
                <span data-price-rules-input-wrapper style="display: flex">
                    <input type="number" style="margin-right: 10px;" min="2"
                           placeholder="<?php _e( 'Quantity', 'tier-pricing-table' ); ?>"
                           class="price-quantity-rule price-quantity-rule--simple"
                           disabled>
                    <input type="number" max="99" placeholder="<?php _e( 'Percent discount', 'tier-pricing-table' ); ?>"
                           class="price-quantity-rule--simple"
                           disabled>
                <span class="notice-dismiss remove-price-rule" style="position: relative"></span>
            </span>
            <br>
        </span>

    <button class="button" disabled>
        <?php _e( 'New tier', 'tier-pricing-table' ); ?>
    </button>

    </span>
        </p>

        <br>
        <p class="description">
            Assign percentage discounts for products that have this category. Rules can be overridden
            in product.
        </p>
        <p style="color: red">Available only in the premium version</p>
        <a target="_blank"
           href="<?php echo esc_attr( tpt_fs_activation_url() ) ?>"><?php _e( 'Upgrade', 'tier-pricing-table' ); ?></a>
        <br>
    </td>
</tr>
