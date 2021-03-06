<div class="<?php echo implode(' ', $classes);?>" id="<?php echo esc_attr($prefix . $field['id']);?>-container"<?php echo (!empty($default) ? ' data-default="' . $default . '"' : '');?> data-type="<?php echo $field['type'];?>">
      <div class="luv-framework-field-title">
            <a href="#" class="luv-framework-reset-single-field"><?php esc_html_e('RESET TO DEFAULT', 'swift-performance')?></a>

            <strong><?php echo esc_html($label);?></strong>
            <?php if (!empty($info)):?>
                  <a href="#" class="luv-framework-show-info">?</a>
                  <div class="luv-framework-info">
                        <?php echo luv_framework_kses($info);?>
                  </div>
            <?php endif;?>
            <?php if (!empty($description)):?>
                  <div class="luv-framework-field-description">
                        <?php echo esc_html($description); ?>
                  </div>
            <?php endif;?>
      </div>
      <div class="luv-framework-field-inner">
      	<input class="luv-framework-number-field" type="number"<?php echo (isset($field['placeholder']) ? ' placeholder="' . esc_attr($field['placeholder']) . '"' : ''); ?><?php echo (isset($field['min']) ? ' min="' . esc_attr($field['min']) . '"' : ''); ?><?php echo (isset($field['max']) ? ' max="' . esc_attr($field['max']) . '"' : ''); ?><?php echo (isset($field['step']) ? ' step="' . esc_attr($field['step']) . '"' : ''); ?> name="<?php echo esc_attr($name);?>" value="<?php echo esc_attr($field['value'])?>">
      </div>
</div>
