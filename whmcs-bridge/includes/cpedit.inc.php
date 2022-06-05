<table class="optiontable wbridge" cellpadding="2" cellspacing="2">
    <?php if ($controlpanelOptions) {
        foreach ($controlpanelOptions as $value) {
            if ($value['type'] == "text" || $value['type'] == "password") {
                $text_value = get_option($value['id']) != '' ? get_option($value['id']) : ((isset($value['std'])) ? $value['std'] : '');
                if (function_exists('whmcs_bridge_sso_password_scrambler') && $value['type'] == 'password') {
                    $text_value = whmcs_bridge_sso_password_scrambler($text_value, true);
                } else if ($value['type'] != 'password') {
                    $text_value = htmlentities(stripslashes($text_value));
                }
                ?>
                <tr>
                    <td colspan="2">
                        <div class="alert info small">
                            <?php echo wp_kses_post($value['desc']); ?>
                        </div>
                    </td>
                </tr>
                <tr align="left">
                    <th scope="row" class="wb_lbl"><?php echo wp_kses_post($value['name']); ?></th>
                    <td><input name="<?php echo esc_attr($value['id']); ?>" id="<?php echo esc_attr($value['id']); ?>"
                       type="<?php echo esc_attr($value['type']); ?>"
                       value="<?php echo esc_attr($text_value); ?>"
                       size="40"
                       class="ipt"
                    /></td>
                </tr>

            <?php } elseif ($value['type'] == "info") { ?>

                <tr>
                    <td colspan="2">
                        <div class="alert info small">
                            <?php echo wp_kses_post($value['desc']); ?>
                        </div>
                    </td>
                </tr>
                <tr align="left">
                    <th scope="row" class="wb_lbl"><?php echo wp_kses_post($value['name']); ?></th>
                </tr>

            <?php } elseif ($value['type'] == "checkbox") { ?>

                <tr>
                    <td colspan="2">
                        <div class="alert info small">
                            <?php echo wp_kses_post($value['desc']); ?>
                        </div>
                    </td>
                </tr>
                <tr align="left">
                    <th scope="row" class="wb_lbl"><?php echo wp_kses_post($value['name']); ?></th>
                    <td><input class="ipt" name="<?php echo esc_attr($value['id']); ?>" id="<?php echo esc_attr($value['id']); ?>"
                               type="checkbox"
                               value="checked"
                            <?php if (get_option($value['id']) != "") {
                                echo esc_attr(" checked");
                            } ?>
                            /></td>

                </tr>

            <?php } elseif ($value['type'] == "textarea") { ?>
                <tr>
                    <td colspan="2">
                        <div class="alert info small">
                            <?php echo wp_kses_post($value['desc']); ?>
                        </div>
                    </td>
                </tr>
                <tr align="left">
                    <th scope="row" class="wb_lbl" colspan="2"><?php echo wp_kses_post($value['name']); ?></th>
                </tr>
                <tr align="left">
                    <td colspan="2" align="center"><textarea class="ipt" name="<?php echo esc_attr($value['id']); ?>" id="<?php echo esc_attr($value['id']); ?>" cols="50"
                                  rows="8"/><?php if (get_option($value['id']) != "") {
                            echo esc_textarea(stripslashes(get_option($value['id'])));
                        } else {
                            echo isset($value['std']) ? esc_textarea($value['std']) : esc_textarea('');
                        } ?></textarea></td>

                </tr>
            <?php } elseif ($value['type'] == "select") { ?>
                <tr>
                    <td colspan="2">
                        <div class="alert info small">
                            <?php echo wp_kses_post($value['desc']); ?>
                        </div>
                    </td>
                </tr>
                <tr align="left">
                    <th scope="top" class="wb_lbl"><?php echo wp_kses_post($value['name']); ?></th>
                    <td><select class="ipt" name="<?php echo esc_attr($value['id']) ?>" id="<?php echo esc_attr($value['id']); ?>">
                            <?php foreach ($value['options'] as $option) { ?>
                                <option <?php if (get_option($value['id']) == $option) {
                                    echo esc_attr(' selected="selected"');
                                } ?>><?php echo esc_attr($option); ?></option>
                            <?php } ?>
                        </select></td>
                </tr>

            <?php } elseif ($value['type'] == "selectwithkey") { ?>

                <tr>
                    <td colspan="2">
                        <div class="alert info small">
                            <?php echo wp_kses_post($value['desc']); ?>
                        </div>
                    </td>
                </tr>
                <tr align="left">
                    <th scope="top" class="wb_lbl"><?php echo wp_kses_post($value['name']); ?></th>
                    <td><select class="ipt" name="<?php echo esc_attr($value['id']); ?>" id="<?php echo esc_attr($value['id']); ?>">
                            <?php foreach ($value['options'] as $key => $option) { ?>
                                <option value="<?php echo esc_attr($key); ?>"
                                    <?php
                                    if (get_option($value['id']) == $key) {
                                        echo esc_attr(' selected="selected"');
                                    } elseif (!get_option($value['id']) && isset($value['std']) && $value['std'] == $key) {
                                        echo esc_attr(' selected="selected"');
                                    }
                                    ?>
                                    ><?php echo $option; ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>

            <?php } elseif ($value['type'] == "heading") { ?>

                <tr>
                    <td colspan="2">
                        <div class="alert info small">
                            <?php echo wp_kses_post($value['desc']); ?>
                        </div>
                    </td>
                </tr>
                <tr valign="top">
                    <td colspan="2" style="text-align: left;">
                        <h2><?php echo wp_kses_post($value['name']); ?></h2>
                    </td>
                </tr>

            <?php
            }
        } //end foreach
    }
    ?>
</table>