<?php

namespace OXI_TABS_PLUGINS\Render\Old_Admin;

/**
 * Description of Style1
 *
 * @author biplo
 */
use OXI_TABS_PLUGINS\Render\Old_Admin;

class Style4 extends Old_Admin {

    public function clild() {
        $title = sanitize_text_field(htmlentities($_POST['ctu-title']));
        $title .= '{}{}{}';
        $title .= $this->admin_special_charecter($_POST['ctu-link']);
        $details = sanitize_text_field(htmlentities($_POST['ctu-details']));
        $css = '';
        return ['title' => $title, 'files' => $details, 'css' => $css];
    }

    public function style() {
        $data = 'heading-font-size |' . sanitize_text_field($_POST['heading-font-size']) . '|'
                . ' heading-font-color |' . sanitize_text_field($_POST['heading-font-color']) . '|'
                . ' heading-background-color |' . sanitize_text_field($_POST['heading-background-color']) . '|'
                . ' heading-border-color |' . sanitize_text_field($_POST['heading-border-color']) . '|'
                . ' heading-font-active-color |' . sanitize_text_field($_POST['heading-font-active-color']) . '|'
                . ' heading-font-familly |' . sanitize_text_field($_POST['heading-font-familly']) . '|'
                . ' heading-font-weight |' . sanitize_text_field($_POST['heading-font-weight']) . '|'
                . ' heading-width |' . sanitize_text_field($_POST['heading-width']) . '|'
                . ' heading-padding-up-bottom |' . sanitize_text_field($_POST['heading-padding-up-bottom']) . '|'
                . ' heading-padding-left-right |' . sanitize_text_field($_POST['heading-padding-left-right']) . '|'
                . ' heading-padding-box |' . sanitize_text_field($_POST['heading-padding-box']) . '|'
                . ' content-font-size |' . sanitize_text_field($_POST['content-font-size']) . '| '
                . ' heading-padding-box-left |' . sanitize_text_field($_POST['heading-padding-box-left']) . '|'
                . ' content-font-color |' . sanitize_text_field($_POST['content-font-color']) . '| '
                . ' content-background-color |' . sanitize_text_field($_POST['content-background-color']) . '|'
                . ' content-border-left-color |' . sanitize_hex_color($_POST['content-border-left-color']) . '|'
                . ' span-margin-bottom |' . sanitize_text_field($_POST['span-margin-bottom']) . '| '
                . ' content-padding-top |' . sanitize_text_field($_POST['content-padding-top']) . '|'
                . ' content-padding-right |' . sanitize_text_field($_POST['content-padding-right']) . '| '
                . 'content-padding-bottom |' . sanitize_text_field($_POST['content-padding-bottom']) . '|'
                . ' content-padding-left |' . sanitize_text_field($_POST['content-padding-left']) . '| '
                . 'content-line-height |' . sanitize_text_field($_POST['content-line-height']) . '|'
                . ' content-font-familly |' . sanitize_text_field($_POST['content-font-familly']) . '| '
                . 'content-font-weight |' . sanitize_text_field($_POST['content-font-weight']) . '| '
                . 'content-font-align |' . sanitize_text_field($_POST['content-font-align']) . '| '
                . 'content-box-shadow-Blur |' . sanitize_text_field($_POST['content-box-shadow-Blur']) . '| '
                . 'content-box-shadow-color |' . sanitize_text_field($_POST['content-box-shadow-color']) . '| '
                . 'content-border-radius |' . sanitize_text_field($_POST['content-border-radius']) . '| '
                . ' content-box-shadow-Horizontal |' . sanitize_text_field($_POST['content-box-shadow-Horizontal']) . '|'
                . ' content-box-shadow-Vertical |' . sanitize_text_field($_POST['content-box-shadow-Vertical']) . '|'
                . ' content-box-shadow-Spread |' . sanitize_text_field($_POST['content-box-shadow-Spread']) . '|'
                . ' heading-font-style |' . sanitize_text_field($_POST['heading-font-style']) . '|'
                . ' content-width |' . sanitize_text_field($_POST['content-width']) . '|'
                . ' custom-css |' . $_POST['custom-css'] . '|'
                . ' oxi-tabs-opening |' . sanitize_text_field($_POST['oxi-tabs-opening']) . '|'
                . ' tabs-link-options |' . sanitize_text_field($_POST['tabs-link-options']) . '|';
        return $data;
    }


    public function admin_field($styledata) {
        $styleid = $this->styleid;
        if (empty($styledata[69])) {
            $styledata[69] = ':eq(1)';
        }
        if (empty($styledata[71])) {
            $styledata[71] = '';
        }
        ?>

        <div class="oxi-addons-tabs-content-tabs" id="oxilab-tabs-id-4">
            <div class="oxi-addons-col-6">
                <div class="oxi-addons-content-div">
                    <div class="oxi-head">
                        Initial Opening
                    </div>
                    <div class="form-group row form-group-sm">
                        <label for="oxi-tabs-opening" class="col-sm-6 col-form-label" data-toggle="tooltip" data-placement="top" title="Set Which tabs You want to Open Initial" >Initial Opening </label>
                        <div class="col-sm-6">
                            <select class="form-control" id="oxi-tabs-opening" name="oxi-tabs-opening">
                                <option value=":eq(0)"     <?php
                                if ($styledata[69] == ':eq(0)') {
                                    echo 'selected';
                                };
                                ?>>First</option>
                                <option value=":eq(1)"     <?php
                                if ($styledata[69] == ':eq(1)') {
                                    echo 'selected';
                                };
                                ?>>2nd</option>
                                <option value=":eq(2)"     <?php
                                if ($styledata[69] == ':eq(2)') {
                                    echo 'selected';
                                };
                                ?>>3rd</option>
                                <option value=":eq(3)"     <?php
                                if ($styledata[69] == ':eq(3)') {
                                    echo 'selected';
                                };
                                ?>>4th</option>
                                <option value=":eq(4)"     <?php
                                if ($styledata[69] == ':eq(4)') {
                                    echo 'selected';
                                };
                                ?>>5th</option>
                                <option value=":eq(5)"     <?php
                                if ($styledata[69] == ':eq(5)') {
                                    echo 'selected';
                                };
                                ?>>6th</option>
                                <option value=":eq(6)"     <?php
                                if ($styledata[69] == ':eq(6)') {
                                    echo 'selected';
                                };
                                ?>>7th</option>
                                <option value=":eq(7)"     <?php
                                if ($styledata[69] == ':eq(7)') {
                                    echo 'selected';
                                };
                                ?>>8th</option>
                                <option value=":eq(8)"     <?php
                                if ($styledata[69] == ':eq(8)') {
                                    echo 'selected';
                                };
                                ?>>9th</option>
                                <option value=":eq(9)" <?php
                                if ($styledata[69] == ':eq(9)') {
                                    echo 'selected';
                                };
                                ?>>10th</option>
                                <option value=":eq(109)"    <?php
                                if ($styledata[69] == ':eq(109)') {
                                    echo 'selected';
                                };
                                ?>>None</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row form-group-sm">
                        <label for="oxi-tabs-link" class="col-sm-6 col-form-label" data-toggle="tooltip" data-placement="top" title="Set Tabs Link Opening" >Link Opening</label>
                        <div class="col-sm-6">
                            <div class="btn-group" data-toggle="buttons">
                                <label class="btn btn-primary  <?php
                                if ($styledata[71] == 'new-tab') {
                                    echo 'active';
                                };
                                ?>" >
                                    <input type="radio" <?php
                                    if ($styledata[71] == 'new-tab') {
                                        echo 'checked';
                                    };
                                    ?> name="tabs-link-options" id="link-options1" value="new-tab"> New Tab
                                </label>
                                <label class="btn btn-primary <?php
                                if ($styledata[71] != 'new-tab') {
                                    echo 'active';
                                };
                                ?>">
                                    <input type="radio" <?php
                                    if ($styledata[71] != 'new-tab') {
                                        echo 'checked';
                                    };
                                    ?> name="tabs-link-options" id="link-options2"> Same Tab
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="oxi-addons-content-div">
                    <div class="oxi-head">
                        Font Settings
                    </div>
                    <div class="form-group row form-group-sm">
                        <label for="heading-font-size" class="col-sm-6 col-form-label" data-toggle="tooltip" data-placement="top" title="Customize Title Font Size, Based on Pixel">Font Size </label>
                        <div class="col-sm-6">
                            <input class="form-control" type="number" value="<?php echo $styledata[1]; ?>" id="heading-font-size" name="heading-font-size">
                        </div>
                    </div>
                    <div class="form-group row form-group-sm">
                        <label for="heading-font-color" class="col-sm-6 control-label heading-font-color" data-toggle="tooltip" data-placement="top" title="Set Your Title Font Color, Based on Color">Color</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control oxilab-vendor-color" id="heading-font-color" name="heading-font-color" value="<?php echo$styledata[3]; ?>" oxivalue="<?php echo$styledata[3]; ?>">
                        </div>
                    </div>
                    <div class="form-group row form-group-sm">
                        <label for="heading-background-color" class="col-sm-6 control-label heading-background-color" data-toggle="tooltip" class="tooltipLink" data-original-title="Set Your Title background Color, Based on Color">Background </label>
                        <div class="col-sm-6">
                            <input type="text" data-format="rgb" data-opacity="true" class="form-control oxilab-vendor-color" id="heading-background-color" name="heading-background-color" value="<?php echo $styledata[5]; ?>" oxivalue="<?php echo $styledata[5]; ?>">
                        </div>
                    </div>
                    <div class="form-group row form-group-sm">
                        <label for="heading-border-color" class="col-sm-6 control-label heading-border-color"  data-toggle="tooltip" data-placement="top" title="Customize your heading border color">Border</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control oxilab-vendor-color" id="heading-border-color" name="heading-border-color" value="<?php echo$styledata[7]; ?>" oxivalue="<?php echo$styledata[7]; ?>">
                        </div>
                    </div>
                    <div class="form-group row form-group-sm">
                        <label for="heading-font-active-color" class="col-sm-6 control-label heading-font-active-color"  data-toggle="tooltip" data-placement="top" title="Customize Your Active Title Font Color, Based on Color">Color Active</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control oxilab-vendor-color" id="heading-font-active-color" name="heading-font-active-color" value="<?php echo $styledata[9]; ?>" oxivalue="<?php echo $styledata[9]; ?>">
                        </div>
                    </div>

                    <div class="form-group row form-group-sm">
                        <label for="heading-font-style" class="col-sm-6 col-form-label" data-toggle="tooltip" data-placement="top" title="Customize Your Heading Font Style"> Font Style</label>
                        <div class="col-sm-6">
                            <select class="form-control" id="heading-font-style" name="heading-font-style">
                                <option <?php
                                if ($styledata[63] == 'normal') {
                                    echo 'selected';
                                }
                                ?> value="normal">Normal</option>
                                <option <?php
                                if ($styledata[63] == 'italic') {
                                    echo 'selected';
                                }
                                ?> value="italic">Italic</option>
                                <option <?php
                                if ($styledata[63] == 'oblique') {
                                    echo 'selected';
                                }
                                ?> value="oblique">Oblique</option>
                                <option <?php
                                if ($styledata[63] == 'initial') {
                                    echo 'selected';
                                }
                                ?> value="initial">Initial</option>
                                <option <?php
                                if ($styledata[63] == 'inherit') {
                                    echo 'selected';
                                }
                                ?> value="inherit">Inherit</option>
                            </select>
                        </div>
                    </div> 
                    <div class="form-group row form-group-sm">
                        <label for="heading-font-familly" class="col-sm-6 col-form-label heading-font-familly"  data-toggle="tooltip" data-placement="top" title="Choose Your Title Preferred font, Based on Google Font"> Font Family </label>
                        <div class="col-sm-6">
                            <input class="oxi-admin-font" type="text" name="heading-font-familly" id="heading-font-familly" value="<?php echo $styledata[11]; ?>" oxivalue="<?php echo $styledata[11]; ?>">
                        </div>
                    </div>
                    <div class="form-group row form-group-sm">
                        <label for="heading-font-weight" class="col-sm-6 col-form-label"  data-toggle="tooltip" data-placement="top" title=" Customize Your Title Font Weight, Based on CSS Weight" >Font Weight  </label>
                        <div class="col-sm-6">
                            <select class="form-control" id="heading-font-weight" name="heading-font-weight">
                                <option value="100"     <?php
                                if ($styledata[13] == '100') {
                                    echo 'selected';
                                };
                                ?>>100</option>
                                <option value="200"     <?php
                                if ($styledata[13] == '200') {
                                    echo 'selected';
                                };
                                ?>>200</option>
                                <option value="300"     <?php
                                if ($styledata[13] == '300') {
                                    echo 'selected';
                                };
                                ?>>300</option>
                                <option value="400"     <?php
                                if ($styledata[13] == '400') {
                                    echo 'selected';
                                };
                                ?>>400</option>
                                <option value="500"     <?php
                                if ($styledata[13] == '500') {
                                    echo 'selected';
                                };
                                ?>>500</option>
                                <option value="600"     <?php
                                if ($styledata[13] == '600') {
                                    echo 'selected';
                                };
                                ?>>600</option>
                                <option value="700"     <?php
                                if ($styledata[13] == '700') {
                                    echo 'selected';
                                };
                                ?>>700</option>
                                <option value="800"     <?php
                                if ($styledata[13] == '800') {
                                    echo 'selected';
                                };
                                ?>>800</option>
                                <option value="900"     <?php
                                if ($styledata[13] == '900') {
                                    echo 'selected';
                                };
                                ?>>900</option>
                                <option value="normal" <?php
                                if ($styledata[13] == 'normal') {
                                    echo 'selected';
                                };
                                ?>>Normal</option>
                                <option value="bold"    <?php
                                if ($styledata[13] == 'bold') {
                                    echo 'selected';
                                };
                                ?>>Bold</option>
                                <option value="lighter" <?php
                                if ($styledata[13] == 'lighter') {
                                    echo 'selected';
                                };
                                ?>>Lighter</option>
                                <option value="initial"   <?php
                                if ($styledata[13] == 'initial') {
                                    echo 'selected';
                                };
                                ?>>Initial</option>
                            </select>
                        </div>
                    </div>
                </div>

            </div>
            <div class="oxi-addons-col-6">
                <div class="oxi-addons-content-div">
                    <div class="oxi-head">
                        Body Settings
                    </div>
                    <div class="form-group row form-group-sm">
                        <label for="heading-width" class="col-sm-6 col-form-label"  data-toggle="tooltip" data-placement="top" title="Set Your Title Background Width, Based on Pixel">Width </label>
                        <div class="col-sm-6">
                            <input class="form-control" type="number" value="<?php echo $styledata[15]; ?>" id="heading-width" name="heading-width">
                        </div>
                    </div>
                    <div class="form-group row form-group-sm">
                        <label for="heading-padding-up-bottom" class="col-sm-6 col-form-label"  data-toggle="tooltip" data-placement="top" title=" Use Padding to generate space around Title, Based on Pixel">Title Padding </label>
                        <div class="col-sm-3">
                            <input class="form-control" type="number" value="<?php echo $styledata[17]; ?>" id="heading-padding-up-bottom" name="heading-padding-up-bottom">
                        </div>
                        <div class="col-sm-3">
                            <input class="form-control" type="number" value="<?php echo $styledata[19]; ?>" id="heading-padding-left-right" name="heading-padding-left-right">
                        </div>
                    </div>
                    <div class="form-group row form-group-sm">
                        <label for="heading-padding-box" class="col-sm-6 col-form-label"  data-toggle="tooltip" data-placement="top" title="Use Padding to generate space around Heading Box">Heading Padding</label>
                        <div class="col-sm-3">
                            <input class="form-control" type="number" value="<?php echo $styledata[21]; ?>" id="heading-padding-box" name="heading-padding-box">
                        </div>
                        <div class="col-sm-3">
                            <input class="form-control" type="number" value="<?php echo $styledata[25]; ?>" id="heading-padding-box-left" name="heading-padding-box-left">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="oxi-addons-tabs-content-tabs" id="oxilab-tabs-id-3">
            <div class="oxi-addons-col-6">
                <div class="oxi-addons-content-div">
                    <div class="oxi-head">
                        Font Settings
                    </div>
                    <div class="form-group row form-group-sm">
                        <label for="content-font-size" class="col-sm-6 col-form-label"  data-toggle="tooltip" data-placement="top" title="Customize Your Content Font Size, Based on Pixel">Font Size </label>
                        <div class="col-sm-6">
                            <input class="form-control" type="number" value="<?php echo $styledata[23]; ?>" id="content-font-size" name="content-font-size">
                        </div>
                    </div>

                    <div class="form-group row form-group-sm">
                        <label for="content-font-color" class="col-sm-6 control-label content-font-color"  data-toggle="tooltip" data-placement="top" title="Set Custom Content Font Color, Based on Color">Color </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control oxilab-vendor-color" id="content-font-color" name="content-font-color" value="<?php echo $styledata[27]; ?>" oxivalue="<?php echo $styledata[27]; ?>">
                        </div>
                    </div>
                    <div class="form-group row form-group-sm">
                        <label for="content-line-height" class="col-sm-6 col-form-label"  data-toggle="tooltip" data-placement="top" title="Customize Your Content Font Line Height, Based on Point">Line Height </label>
                        <div class="col-sm-6">
                            <input class="form-control" type="number" step="0.1" value="<?php echo $styledata[43]; ?>" id="content-line-height" name="content-line-height">
                        </div>
                    </div>
                    <div class="form-group row form-group-sm">
                        <label for="content-font-familly" class="col-sm-6 col-form-label content-font-color"  data-toggle="tooltip" data-placement="top" title="Choose Your Content Font, Based on Google Font"> Font Family </label>
                        <div class="col-sm-6">
                            <input class="oxi-admin-font" value="<?php echo $styledata[45]; ?>" oxivalue="<?php echo $styledata[45]; ?>" type="text" name="content-font-familly" id="content-font-familly">
                        </div>
                    </div>
                    <div class="form-group row form-group-sm">
                        <label for="content-font-weight" class="col-sm-6 col-form-label" data-toggle="tooltip" data-placement="top" title="Customize Content Font Weight, Based on CSS Weight">Font Weight  </label>
                        <div class="col-sm-6">
                            <select class="form-control" id="content-font-weight" name="content-font-weight">
                                <option value="100" <?php
                                if ($styledata[47] == '100') {
                                    echo 'selected';
                                };
                                ?>>100</option>
                                <option value="200" <?php
                                if ($styledata[47] == '200') {
                                    echo 'selected';
                                };
                                ?>>200</option>
                                <option value="300" <?php
                                if ($styledata[47] == '300') {
                                    echo 'selected';
                                };
                                ?>>300</option>
                                <option value="400" <?php
                                if ($styledata[47] == '400') {
                                    echo 'selected';
                                };
                                ?>>400</option>
                                <option value="500" <?php
                                if ($styledata[47] == '500') {
                                    echo 'selected';
                                };
                                ?>>500</option>
                                <option value="600" <?php
                                if ($styledata[47] == '600') {
                                    echo 'selected';
                                };
                                ?>>600</option>
                                <option value="700" <?php
                                if ($styledata[47] == '700') {
                                    echo 'selected';
                                };
                                ?>>700</option>
                                <option value="800" <?php
                                if ($styledata[47] == '800') {
                                    echo 'selected';
                                };
                                ?>>800</option>
                                <option value="900" <?php
                                if ($styledata[47] == '900') {
                                    echo 'selected';
                                };
                                ?>>900</option>
                                <option value="normal" <?php
                                if ($styledata[47] == 'normal') {
                                    echo 'selected';
                                };
                                ?>>Normal</option>
                                <option value="bold" <?php
                                if ($styledata[47] == 'bold') {
                                    echo 'selected';
                                };
                                ?>>Bold</option>
                                <option value="lighter" <?php
                                if ($styledata[47] == 'lighter') {
                                    echo 'selected';
                                };
                                ?>>Lighter</option>
                                <option value="initial" <?php
                                if ($styledata[47] == 'initial') {
                                    echo 'selected';
                                };
                                ?>>Initial</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="oxi-addons-content-div">
                    <div class="oxi-head">
                        Box Shadow
                    </div>  <div class="form-group row form-group-sm">
                        <label for="content-box-shadow-Horizontal" class="col-sm-6 col-form-label" data-toggle="tooltip" class="tooltipLink" data-original-title="Attach Shadow Length to Tabs, Based on Pixel">Box Shadow  Length</label>
                        <div class="col-sm-3">
                            <input class="form-control" type="number" value="<?php echo $styledata[57]; ?>" id="content-box-shadow-Horizontal" name="content-box-shadow-Horizontal">
                        </div>
                        <div class="col-sm-3">
                            <input class="form-control" type="number" value="<?php echo $styledata[59]; ?>" id="content-box-shadow-Vertical" name="content-box-shadow-Vertical">
                        </div>
                    </div>
                    <div class="form-group row form-group-sm">
                        <label for="content-box-shadow-Blur" class="col-sm-6 col-form-label" data-toggle="tooltip" class="tooltipLink" data-original-title="Attach Shadow Size to Tabs, Based on Pixel">Box Shadow Radius</label>
                        <div class="col-sm-3">
                            <input class="form-control" type="number" value="<?php echo $styledata[51]; ?>" id="content-box-shadow-Blur" name="content-box-shadow-Blur">
                        </div>
                        <div class="col-sm-3">
                            <input class="form-control" type="number" value="<?php echo $styledata[61]; ?>" id="content-box-shadow-Spread" name="content-box-shadow-Spread">
                        </div>
                    </div>
                    <div class="form-group row form-group-sm">
                        <label for="content-box-shadow-color" class="col-sm-6 control-label content-box-shadow-color"  data-toggle="tooltip" data-placement="top" title="Add custom color to Box Shadow">Box Shadow Color </label>
                        <div class="col-sm-6">
                            <input type="text" data-format="rgb" data-opacity="true" class="form-control oxilab-vendor-color" id="content-box-shadow-color" name="content-box-shadow-color" value="<?php echo $styledata[53]; ?>" oxivalue="<?php echo $styledata[53]; ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="oxi-addons-col-6">
                <div class="oxi-addons-content-div">
                    <div class="oxi-head">
                        Body Settings
                    </div>  
                    <div class="form-group row form-group-sm">
                        <label for="content-font-align" class="col-sm-6 col-form-label"  data-toggle="tooltip" data-placement="top" title="Set Content Text Position, Based on left or center or Right">Text Align  </label>
                        <div class="col-sm-6">
                            <select class="form-control" id="content-font-align" name="content-font-align">
                                <option value="left" <?php
                                if ($styledata[49] == 'left') {
                                    echo 'selected';
                                };
                                ?>>Left</option>
                                <option value="center" <?php
                                if ($styledata[49] == 'center') {
                                    echo 'selected';
                                };
                                ?>>Center</option>
                                <option value="right" <?php
                                if ($styledata[49] == 'right') {
                                    echo 'selected';
                                };
                                ?>>Right</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row form-group-sm">
                        <label for="content-width" class="col-sm-6 col-form-label"  data-toggle="tooltip" data-placement="top" title="Connfirm Your Content Width, based on Percentage">Width </label>
                        <div class="col-sm-6">
                            <input class="form-control" type="number" value="<?php echo $styledata[65]; ?>" id="content-width" name="content-width">
                        </div>
                    </div>
                    <div class="form-group row form-group-sm">
                        <label for="content-background-color" class="col-sm-6 control-label content-background-color"  data-toggle="tooltip" data-placement="top" title="Set Custom Background Color of Content Box">Background Color </label>
                        <div class="col-sm-6">
                            <input type="text" data-format="rgb" data-opacity="true" class="form-control oxilab-vendor-color" id="content-background-color" name="content-background-color" value="<?php echo $styledata[29]; ?>" oxivalue="<?php echo $styledata[29]; ?>">
                        </div>
                    </div>
                    <div class="form-group row form-group-sm">
                        <label for="content-border-left-color" class="col-sm-6 control-label content-border-left-color"  data-toggle="tooltip" data-placement="top" title="Select Border left Color">Border left Color </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control oxilab-vendor-color" id="content-border-left-color" name="content-border-left-color" value="<?php echo $styledata[31]; ?>" oxivalue="<?php echo $styledata[31]; ?>">
                        </div>
                    </div>
                    <div class="form-group row form-group-sm">
                        <label for="span-margin-bottom" class="col-sm-6 col-form-label"  data-toggle="tooltip" data-placement="top" title="Space between Content and Special Border">Span Margin Bottom </label>
                        <div class="col-sm-6">
                            <input class="form-control" type="number" value="<?php echo $styledata[33]; ?>" id="span-margin-bottom" name="span-margin-bottom">
                        </div>
                    </div>
                    <div class="form-group row form-group-sm">
                        <label for="content-padding-top" class="col-sm-6 col-form-label"  data-toggle="tooltip" data-placement="top" title="Use Padding to Generate Space Around Content as top, right, bottom, left. Based on Pixel">Padding Top Bottom</label>
                        <div class="col-sm-3">
                            <input class="form-control" type="number" value="<?php echo $styledata[35]; ?>" id="content-padding-top" name="content-padding-top">
                        </div>
                        <div class="col-sm-3">
                            <input class="form-control" type="number" value="<?php echo $styledata[39]; ?>" id="content-padding-bottom" name="content-padding-bottom">
                        </div>
                    </div>
                    <div class="form-group row form-group-sm">
                        <label for="content-padding-top" class="col-sm-6 col-form-label"  data-toggle="tooltip" data-placement="top" title="Use Padding to Generate Space Around Content as top, right, bottom, left. Based on Pixel">Padding left right </label>
                        <div class="col-sm-3">
                            <input class="form-control" type="number" value="<?php echo $styledata[37]; ?>" id="content-padding-right" name="content-padding-right">
                        </div>
                        <div class="col-sm-3">
                            <input class="form-control" type="number" value="<?php echo $styledata[41]; ?>" id="content-padding-left" name="content-padding-left">
                        </div>
                    </div>
                    <div class="form-group row form-group-sm">
                        <label for="content-border-radius" class="col-sm-6 col-form-label"  data-toggle="tooltip" data-placement="top" title="Add Rounded Corner on Tabs, Based on Pixel">Border Radius </label>
                        <div class="col-sm-6">
                            <input class="form-control" type="number" value="<?php echo $styledata[55]; ?>" id="content-border-radius" name="content-border-radius">
                        </div>
                    </div>                                                
                </div> 
            </div>
        </div>
        <div class="oxi-addons-tabs-content-tabs" id="oxilab-tabs-id-2">
            <div class="col-xs-12">
                <div class="form-group">
                    <label for="custom-css" class="custom-css">Custom CSS:</label>
                    <textarea class="form-control" rows="4" id="custom-css" name="custom-css"><?php echo $styledata[67]; ?></textarea>
                    <small class="form-text text-muted">Add Your Custom CSS.</small>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            jQuery(document).ready(function () {
                jQuery("#heading-font-size").on("change", function () {
                    jQuery("<style type='text/css'>#oxi-addons-preview-data  .ctu-ulimate-style-<?php echo $styleid; ?> .vc-tabs-li{font-size: " + jQuery("#heading-font-size").val() + "px;} </style>").appendTo("#oxi-addons-preview-data");
                    jQuery("<style type='text/css'>#oxi-addons-preview-data  .ctu-ultimate-style-heading-<?php echo $styleid; ?>{font-size: " + jQuery("#heading-font-size").val() + "px;} </style>").appendTo("#oxi-addons-preview-data");
                });
                jQuery("#heading-font-color").on("change", function () {
                    jQuery("<style type='text/css'>#oxi-addons-preview-data  .ctu-ulimate-style-<?php echo $styleid; ?> .vc-tabs-li{color: " + jQuery("#heading-font-color").val() + "; } </style>").appendTo("#oxi-addons-preview-data");
                    jQuery("<style type='text/css'>#oxi-addons-preview-data  .ctu-ultimate-style-heading-<?php echo $styleid; ?>{color: " + jQuery("#heading-font-color").val() + "; } </style>").appendTo("#oxi-addons-preview-data");
                });
                jQuery("#heading-background-color").on("change", function () {
                    jQuery("<style type='text/css'>#oxi-addons-preview-data  .ctu-ulimate-style-<?php echo $styleid; ?>{background-color: " + jQuery("#heading-background-color").val() + "; } </style>").appendTo("#oxi-addons-preview-data");
                    jQuery("<style type='text/css'>#oxi-addons-preview-data  .ctu-ultimate-style-heading-<?php echo $styleid; ?>{background-color: " + jQuery("#heading-background-color").val() + "; } </style>").appendTo("#oxi-addons-preview-data");
                });
                jQuery("#heading-border-color").on("change", function () {
                    jQuery("<style type='text/css'>#oxi-addons-preview-data  .ctu-ulimate-style-<?php echo $styleid; ?> .vc-tabs-li{border-color: " + jQuery("#heading-border-color").val() + "; } </style>").appendTo("#oxi-addons-preview-data");
                });
                jQuery("#heading-font-active-color").on("change", function () {
                    jQuery("<style type='text/css'>#oxi-addons-preview-data  .ctu-ulimate-style-<?php echo $styleid; ?> .vc-tabs-li.active{color: " + jQuery("#heading-font-active-color").val() + "; } </style>").appendTo("#oxi-addons-preview-data");
                    jQuery("<style type='text/css'>#oxi-addons-preview-data  .ctu-ultimate-style-heading-<?php echo $styleid; ?>.active{color: " + jQuery("#heading-font-active-color").val() + "; } </style>").appendTo("#oxi-addons-preview-data");
                });
                jQuery("#heading-font-style").on("change", function () {
                    jQuery("<style type='text/css'>#oxi-addons-preview-data  .ctu-ulimate-style-<?php echo $styleid; ?> .vc-tabs-li{font-style: " + jQuery("#heading-font-style").val() + "; } </style>").appendTo("#oxi-addons-preview-data");
                });
                jQuery('#heading-font-familly').change(function () {
                    var font = jQuery(this).val().replace(/\+/g, ' ');
                    font = font.split(':');
                    jQuery("<style type='text/css'>#oxi-addons-preview-data  .ctu-ulimate-style-<?php echo $styleid; ?> .vc-tabs-li{ font-family:" + font[0] + ";} </style>").appendTo(".oxi-addons-preview-data");
                    jQuery("<style type='text/css'>#oxi-addons-preview-data  .ctu-ultimate-style-heading-<?php echo $styleid; ?>{ font-family:" + font[0] + ";} </style>").appendTo(".oxi-addons-preview-data");
                });
                jQuery("#heading-font-weight").on("change", function () {
                    jQuery("<style type='text/css'>#oxi-addons-preview-data  .ctu-ulimate-style-<?php echo $styleid; ?> .vc-tabs-li{font-weight: " + jQuery("#heading-font-weight").val() + "; } </style>").appendTo("#oxi-addons-preview-data");
                    jQuery("<style type='text/css'>#oxi-addons-preview-data  .ctu-ultimate-style-heading-<?php echo $styleid; ?>{font-weight: " + jQuery("#heading-font-weight").val() + "; } </style>").appendTo("#oxi-addons-preview-data");
                });
                jQuery("#heading-font-weight").on("change", function () {
                    jQuery("<style type='text/css'>#oxi-addons-preview-data  .ctu-ulimate-style-<?php echo $styleid; ?> .vc-tabs-li{font-weight: " + jQuery("#heading-font-weight").val() + "; } </style>").appendTo("#oxi-addons-preview-data");
                    jQuery("<style type='text/css'>#oxi-addons-preview-data  .ctu-ultimate-style-heading-<?php echo $styleid; ?>{font-weight: " + jQuery("#heading-font-weight").val() + "; } </style>").appendTo("#oxi-addons-preview-data");
                });
                jQuery("#heading-width").on("change", function () {
                    var value = parseInt(jQuery('#heading-width').val(), 10);
                    var value1 = parseInt(jQuery('#heading-padding-left-right').val(), 10);
                    var value2 = parseInt(jQuery('#heading-padding-box-left').val(), 10);
                    var finalvalue = value + value1 + value2;
                    jQuery("<style type='text/css'>#oxi-addons-preview-data  .ctu-ulimate-style-<?php echo $styleid; ?>{min-width:  " + finalvalue + "px;} </style>").appendTo("#oxi-addons-preview-data");
                    jQuery("<style type='text/css'>#oxi-addons-preview-data  .ctu-ulimate-style-<?php echo $styleid; ?> .vc-tabs-li{max-width: " + jQuery("#heading-width").val() + "px; } </style>").appendTo("#oxi-addons-preview-data");
                    jQuery("<style type='text/css'>#oxi-addons-preview-data  .ctu-ultimate-style-<?php echo $styleid; ?>-content{max-width: calc(100% - " + finalvalue + "px); } </style>").appendTo("#oxi-addons-preview-data");
                });
                jQuery("#heading-padding-up-bottom").on("change", function () {
                    jQuery("<style type='text/css'>#oxi-addons-preview-data  .ctu-ulimate-style-<?php echo $styleid; ?> .vc-tabs-li{padding: " + jQuery("#heading-padding-up-bottom").val() + "px " + jQuery("#heading-padding-left-right").val() + "px;} </style>").appendTo("#oxi-addons-preview-data");
                    jQuery("<style type='text/css'>#oxi-addons-preview-data  .ctu-ultimate-style-heading-<?php echo $styleid; ?>{padding: " + jQuery("#heading-padding-up-bottom").val() + "px 10px; } </style>").appendTo("#oxi-addons-preview-data");
                });
                jQuery("#heading-padding-left-right").on("change", function () {
                    jQuery("<style type='text/css'>#oxi-addons-preview-data  .ctu-ulimate-style-<?php echo $styleid; ?> .vc-tabs-li{padding: " + jQuery("#heading-padding-up-bottom").val() + "px " + jQuery("#heading-padding-left-right").val() + "px; } </style>").appendTo("#oxi-addons-preview-data");
                });
                jQuery("#heading-padding-box").on("change", function () {
                    jQuery("<style type='text/css'>#oxi-addons-preview-data  .ctu-ulimate-style-<?php echo $styleid; ?>{padding: " + jQuery("#heading-padding-box").val() + "px " + jQuery("#heading-padding-box-left").val() + "px; } </style>").appendTo("#oxi-addons-preview-data");
                });
                jQuery("#heading-padding-box-left").on("change", function () {
                    jQuery("<style type='text/css'>#oxi-addons-preview-data  .ctu-ulimate-style-<?php echo $styleid; ?>{padding: " + jQuery("#heading-padding-box").val() + "px " + jQuery("#heading-padding-box-left").val() + "px; } </style>").appendTo("#oxi-addons-preview-data");
                });
                jQuery("#content-font-size").on("change", function () {
                    jQuery("<style type='text/css'>#oxi-addons-preview-data  .ctu-ulitate-style-<?php echo $styleid; ?>-tabs p{font-size: " + jQuery("#content-font-size").val() + "px;} </style>").appendTo("#oxi-addons-preview-data");
                });
                jQuery("#content-font-color").on("change", function () {
                    jQuery("<style type='text/css'>#oxi-addons-preview-data  .ctu-ulitate-style-<?php echo $styleid; ?>-tabs p{color: " + jQuery("#content-font-color").val() + ";} </style>").appendTo("#oxi-addons-preview-data");
                });
                jQuery("#content-line-height").on("change", function () {
                    jQuery("<style type='text/css'>#oxi-addons-preview-data  .ctu-ulitate-style-<?php echo $styleid; ?>-tabs p{line-height: " + jQuery("#content-line-height").val() + ";} </style>").appendTo("#oxi-addons-preview-data");
                });
                jQuery('#content-font-familly').change(function () {
                    var font = jQuery(this).val().replace(/\+/g, ' ');
                    font = font.split(':');
                    jQuery("<style type='text/css'>#oxi-addons-preview-data  .ctu-ulitate-style-<?php echo $styleid; ?>-tabs{ font-family:" + font[0] + ";} </style>").appendTo(".oxi-addons-preview-data");
                    jQuery("<style type='text/css'>#oxi-addons-preview-data  .ctu-ulitate-style-<?php echo $styleid; ?>-tabs p{ font-family:" + font[0] + ";} </style>").appendTo(".oxi-addons-preview-data");
                });
                jQuery("#content-font-weight").on("change", function () {
                    jQuery("<style type='text/css'>#oxi-addons-preview-data  .ctu-ulitate-style-<?php echo $styleid; ?>-tabs p{font-weight: " + jQuery("#content-font-weight").val() + ";} </style>").appendTo("#oxi-addons-preview-data");
                });
                jQuery("#content-box-shadow-Horizontal").on("change", function () {
                    jQuery("<style type='text/css'>#oxi-addons-preview-data  .ctu-ultimate-wrapper-<?php echo $styleid; ?>{box-shadow: " + jQuery("#content-box-shadow-Horizontal").val() + "px " + jQuery("#content-box-shadow-Vertical").val() + "px " + jQuery("#content-box-shadow-Blur").val() + "px " + jQuery("#content-box-shadow-Spread").val() + "px " + jQuery("#content-box-shadow-color").val() + ";} </style>").appendTo("#oxi-addons-preview-data");
                });
                jQuery("#content-box-shadow-Vertical").on("change", function () {
                    jQuery("<style type='text/css'>#oxi-addons-preview-data  .ctu-ultimate-wrapper-<?php echo $styleid; ?>{box-shadow: " + jQuery("#content-box-shadow-Horizontal").val() + "px " + jQuery("#content-box-shadow-Vertical").val() + "px " + jQuery("#content-box-shadow-Blur").val() + "px " + jQuery("#content-box-shadow-Spread").val() + "px " + jQuery("#content-box-shadow-color").val() + ";} </style>").appendTo("#oxi-addons-preview-data");
                });
                jQuery("#content-box-shadow-Blur").on("change", function () {
                    jQuery("<style type='text/css'>#oxi-addons-preview-data  .ctu-ultimate-wrapper-<?php echo $styleid; ?>{box-shadow: " + jQuery("#content-box-shadow-Horizontal").val() + "px " + jQuery("#content-box-shadow-Vertical").val() + "px " + jQuery("#content-box-shadow-Blur").val() + "px " + jQuery("#content-box-shadow-Spread").val() + "px " + jQuery("#content-box-shadow-color").val() + ";} </style>").appendTo("#oxi-addons-preview-data");
                });
                jQuery("#content-box-shadow-Spread").on("change", function () {
                    jQuery("<style type='text/css'>#oxi-addons-preview-data  .ctu-ultimate-wrapper-<?php echo $styleid; ?>{box-shadow: " + jQuery("#content-box-shadow-Horizontal").val() + "px " + jQuery("#content-box-shadow-Vertical").val() + "px " + jQuery("#content-box-shadow-Blur").val() + "px " + jQuery("#content-box-shadow-Spread").val() + "px " + jQuery("#content-box-shadow-color").val() + ";} </style>").appendTo("#oxi-addons-preview-data");
                });
                jQuery("#content-box-shadow-color").on("change", function () {
                    jQuery("<style type='text/css'>#oxi-addons-preview-data  .ctu-ultimate-wrapper-<?php echo $styleid; ?>{box-shadow: " + jQuery("#content-box-shadow-Horizontal").val() + "px " + jQuery("#content-box-shadow-Vertical").val() + "px " + jQuery("#content-box-shadow-Blur").val() + "px " + jQuery("#content-box-shadow-Spread").val() + "px " + jQuery("#content-box-shadow-color").val() + ";} </style>").appendTo("#oxi-addons-preview-data");
                    jQuery("<style type='text/css'>#oxi-addons-preview-data  .ctu-ultimate-style-heading-<?php echo $styleid; ?>{box-shadow:  0 0 5px " + jQuery("#content-box-shadow-color").val() + ";} </style>").appendTo("#oxi-addons-preview-data");
                    jQuery("<style type='text/css'>#oxi-addons-preview-data  .ctu-ulitate-style-<?php echo $styleid; ?>-tabs{box-shadow:  0 0 5px " + jQuery("#content-box-shadow-color").val() + ";} </style>").appendTo("#oxi-addons-preview-data");
                });
                jQuery("#content-font-align").on("change", function () {
                    jQuery("<style type='text/css'>#oxi-addons-preview-data  .ctu-ulitate-style-<?php echo $styleid; ?>-tabs p{text-align: " + jQuery("#content-font-align").val() + ";} </style>").appendTo("#oxi-addons-preview-data");
                });
                jQuery("#content-width").on("change", function () {
                    jQuery("<style type='text/css'>#oxi-addons-preview-data  .ctu-ulimate-style-<?php echo $styleid; ?>{width: calc(100% - " + jQuery("#content-width").val() + "%) ;} </style>").appendTo("#oxi-addons-preview-data");
                    jQuery("<style type='text/css'>#oxi-addons-preview-data   .ctu-ultimate-style-<?php echo $styleid; ?>-content{width: " + jQuery("#content-width").val() + "%; } </style>").appendTo("#oxi-addons-preview-data");
                });
                jQuery("#content-background-color").on("change", function () {
                    jQuery("<style type='text/css'>#oxi-addons-preview-data  .ctu-ulitate-style-<?php echo $styleid; ?>-tabs{background-color: " + jQuery("#content-background-color").val() + ";} </style>").appendTo("#oxi-addons-preview-data");
                });
                jQuery("#content-border-left-color").on("change", function () {
                    jQuery("<style type='text/css'>#oxi-addons-preview-data  .ctu-ulitate-style-<?php echo $styleid; ?>-tabs{border-left: 1px solid " + jQuery("#content-border-left-color").val() + ";} </style>").appendTo("#oxi-addons-preview-data");
                });
                jQuery("#span-margin-bottom").on("change", function () {
                    jQuery("<style type='text/css'>#oxi-addons-preview-data  .ctu-content-span{margin-bottom: " + jQuery("#span-margin-bottom").val() + "px;} </style>").appendTo("#oxi-addons-preview-data");
                });
                jQuery("#content-padding-top").on("change", function () {
                    jQuery("<style type='text/css'>#oxi-addons-preview-data  .ctu-ulitate-style-<?php echo $styleid; ?>-tabs{padding: " + jQuery("#content-padding-top").val() + "px " + jQuery("#content-padding-right").val() + "px " + jQuery("#content-padding-bottom").val() + "px " + jQuery("#content-padding-left").val() + "px;} </style>").appendTo("#oxi-addons-preview-data");
                });
                jQuery("#content-padding-bottom").on("change", function () {
                    jQuery("<style type='text/css'>#oxi-addons-preview-data  .ctu-ulitate-style-<?php echo $styleid; ?>-tabs{padding: " + jQuery("#content-padding-top").val() + "px " + jQuery("#content-padding-right").val() + "px " + jQuery("#content-padding-bottom").val() + "px " + jQuery("#content-padding-left").val() + "px;} </style>").appendTo("#oxi-addons-preview-data");
                });
                jQuery("#content-padding-right").on("change", function () {
                    jQuery("<style type='text/css'>#oxi-addons-preview-data  .ctu-ulitate-style-<?php echo $styleid; ?>-tabs{padding: " + jQuery("#content-padding-top").val() + "px " + jQuery("#content-padding-right").val() + "px " + jQuery("#content-padding-bottom").val() + "px " + jQuery("#content-padding-left").val() + "px;} </style>").appendTo("#oxi-addons-preview-data");
                });
                jQuery("#content-padding-left").on("change", function () {
                    jQuery("<style type='text/css'>#oxi-addons-preview-data  .ctu-ulitate-style-<?php echo $styleid; ?>-tabs{padding: " + jQuery("#content-padding-top").val() + "px " + jQuery("#content-padding-right").val() + "px " + jQuery("#content-padding-bottom").val() + "px " + jQuery("#content-padding-left").val() + "px;} </style>").appendTo("#oxi-addons-preview-data");
                });
                jQuery("#content-border-radius").on("change", function () {
                    jQuery("<style type='text/css'>#oxi-addons-preview-data  .ctu-ultimate-wrapper-<?php echo $styleid; ?>{border-radius: " + jQuery("#content-border-radius").val() + "px;} </style>").appendTo("#oxi-addons-preview-data");
                });

            });
        </script>
        <?php
    }

    public function admin_child_field() {
        ?>
        <div class="form-group col-sm-12">
            <label for="ctu-title"  data-toggle="tooltip" data-placement="top">Title</label>
            <input type="text "class="form-control" id="cau-title" name="ctu-title" value="<?php echo $this->title; ?>">
            <small class="form-text text-muted">Add or Modify Your Tabs Title.</small>
        </div>
        <div class="form-group col-sm-12">
            <label for="ctu-link"  data-toggle="tooltip" data-placement="top">Link</label>
            <input type="text "class="form-control" id="ctu-link" name="ctu-link" value="<?php echo $this->link; ?>">
            <small class="form-text text-muted">As you want to add link. Unless make it blank. Link will works only at site not edit page</small>
        </div>
        <div class="form-group col-sm-12">
            <label for="ctu-details">Details:</label>
            <?php
            wp_editor($this->admin_special_charecter($this->files), 'ctu-details', $settings = array(
                'textarea_name' => 'ctu-details',
                'wpautop' => false,
                'force_br_newlines' => true,
                'force_p_newlines' => false)
            );
            ?>
            <small class="form-text text-muted">Add or Modify Your Content.</small>
        </div>
        <?php
    }
}
