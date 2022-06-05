<?php

namespace OXI_TABS_PLUGINS\Render\Old_Views;

/**
 * Description of Old Style
 *
 * @author biplob018
 */
use OXI_TABS_PLUGINS\Render\Old_Render;

class Style9 extends Old_Render {

    public function inline_public_jquery() {
        $styledata = $this->style;
        $styleid = $this->ID;
        $oxi_fixed_header = get_option('oxi_addons_fixed_header_size');
        if (empty($styledata[71])) {
            $initialopen = ':first';
        } else if ($styledata[71] == 'none') {
            $initialopen = '';
        } else {
            $initialopen = $styledata[71];
        }
        if (empty($styledata[73])) {
            $animationin = 'slideDown';
            $animationout = 'slideUp';
        } else if ($styledata[73] == 'slide') {
            $animationin = 'slideDown';
            $animationout = 'slideUp';
        } else if ($styledata[73] == 'show') {
            $animationin = 'show';
            $animationout = 'hide';
        } else {
            $animationin = 'fadeIn';
            $animationout = 'fadeOut';
        }
        $this->JQUERY .= '  $(".ctu-ulimate-style-' . $styleid . ' .vc-tabs-li' . $initialopen . '").addClass("active");
                            $(".ctu-ultimate-style-heading-' . $styleid . '' . $initialopen . '").addClass("active");
                            $(".ctu-ulitate-style-' . $styleid . '-tabs' . $initialopen . '").' . $animationin . '();
                            $(".ctu-ulimate-style-' . $styleid . ' .vc-tabs-li").click(function () {
                                if ($(this).hasClass("active")) {
                                    return false;
                                } else {
                                    $(".ctu-ulimate-style-' . $styleid . ' .vc-tabs-li").removeClass("active");
                                    $(this).toggleClass("active");
                                    $(".ctu-ulitate-style-' . $styleid . '-tabs").' . $animationout . '("slow");
                                    var activeTab = $(this).attr("ref");
                                    $(activeTab).' . $animationin . '("slow");
                                }
                            });
                            $(".ctu-ultimate-style-heading-' . $styleid . '").click(function () {
                                if ($(this).hasClass("active")) {
                                    return false;
                                } else {
                                    $(".ctu-ultimate-style-heading-' . $styleid . '").removeClass("active");
                                    $(this).toggleClass("active");
                                    $(".ctu-ulitate-style-' . $styleid . '-tabs").' . $animationout . '("slow");
                                    var activeTab = $(this).attr("ref");
                                    $(activeTab).' . $animationin . '("slow");
                                    var headerheight = ' . $oxi_fixed_header . ';
                                    $("html, body").animate({
                                        scrollTop: $(".ctu-ultimate-wrapper-' . $styleid . '").offset().top - headerheight
                                    }, 2000);
                                }
                            });';
    }

    public function inline_public_css() {
        $styledata = $this->style;
        $styleid = $this->ID;
        $this->CSS .= ' .ctu-ulimate-style-' . $styleid . '{
                            margin: 0 0 0 0;
                            width: 100%;
                            float: left;
                            list-style: none;
                            text-align: center;
                            display: -webkit-box;
                            display: -ms-flexbox;
                            display: -o-flexbox;
                            display: -moz-flexbox;
                            display: flex;
                            -webkit-box-pack:  ' . $styledata[17] . ';
                            -ms-flex-pack:  ' . $styledata[17] . ';
                            -o-flex-pack:  ' . $styledata[17] . ';
                            -moz-flex-pack:  ' . $styledata[17] . ';
                            justify-content: ' . $styledata[17] . ';
                            margin-bottom: ' . $styledata[21] . 'px;
                            -webkit-box-shadow:   ' . $styledata[61] . 'px ' . $styledata[63] . 'px ' . $styledata[23] . 'px ' . $styledata[65] . 'px ' . $styledata[25] . ';
                            -o-box-shadow:    ' . $styledata[61] . 'px ' . $styledata[63] . 'px ' . $styledata[23] . 'px ' . $styledata[65] . 'px ' . $styledata[25] . ';
                            -ms-box-shadow:    ' . $styledata[61] . 'px ' . $styledata[63] . 'px ' . $styledata[23] . 'px ' . $styledata[65] . 'px ' . $styledata[25] . ';
                            -moz-box-shadow:    ' . $styledata[61] . 'px ' . $styledata[63] . 'px ' . $styledata[23] . 'px ' . $styledata[65] . 'px ' . $styledata[25] . ';
                            box-shadow:   ' . $styledata[61] . 'px ' . $styledata[63] . 'px ' . $styledata[23] . 'px ' . $styledata[65] . 'px ' . $styledata[25] . ';
                            background-color: ' . $styledata[5] . ';
                            border-radius: ' . $styledata[27] . 'px ' . $styledata[27] . 'px 0 0 ;
                            margin-bottom: ' . $styledata[21] . 'px
                        }
                        .ctu-ultimate-wrapper-' . $styleid . '{
                            width: 100%;
                            float: left;
                        }
                        .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li{
                            position: relative;
                            list-style: none;
                            cursor: pointer;
                            width: ' . $styledata[15] . 'px;
                            float: left;
                            padding: 5px 10px ;
                            text-align: center;
                            color: ' . $styledata[3] . ';
                            font-size: ' . $styledata[1] . 'px;
                            font-family:    ' . $this->font_familly($styledata[11]) . ';
                            font-weight: ' . $styledata[13] . ';
                            line-height: 130%;
                            font-style: ' . $styledata[67] . ';
                            margin: ' . $styledata[19] . 'px 0;
                            border-right: 1px solid;
                            border-color: ' . $styledata[9] . ';
                        }
                        .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li:last-child{
                            border-right: none
                        }
                        .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li.active{
                            color: ' . $styledata[7] . ';
                        }
                        .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li.active .ctu-absolute{
                            position: absolute;
                            left: 0;
                            right: 0;
                            bottom: calc(-10px - ' . $styledata[19] . 'px );
                            margin: 0 auto;
                            width: 0;
                            height: 0;
                            border-left: 10px solid transparent;
                            border-right: 10px solid transparent;
                            border-top: 10px solid ' . $styledata[5] . ';
                        }
                        .ctu-ultimate-style-' . $styleid . '-content{
                            width: 100%;
                            float: left;
                        }

                        .ctu-ultimate-style-heading-' . $styleid . '{
                            width: 100%;
                            cursor: pointer;
                            float: left;
                            display: none;
                            line-height: 100%;
                            color: ' . $styledata[3] . ';
                            background-color: ' . $styledata[5] . ';
                            font-size: ' . $styledata[1] . 'px;
                            padding: ' . $styledata[19] . 'px 10px;
                            font-weight: ' . $styledata[13] . ';
                            box-shadow:   ' . $styledata[61] . 'px ' . $styledata[63] . 'px ' . $styledata[23] . 'px ' . $styledata[65] . 'px ' . $styledata[25] . ';
                            font-family:  ' . $this->font_familly($styledata[11]) . ';
                            font-style: ' . $styledata[67] . ';
                            border-radius: ' . $styledata[27] . 'px;
                        }
                        .ctu-ultimate-style-heading-' . $styleid . '.active{
                            color: ' . $styledata[7] . ';
                            margin-bottom: 10px;
                            border-radius:  ' . $styledata[27] . 'px ' . $styledata[27] . 'px 0 0;
                        }

                        .ctu-ulitate-style-' . $styleid . '-tabs{
                            width: 100%;
                            float: left;
                            display: none;
                            background-color: ' . $styledata[33] . ';
                            font-weight:' . $styledata[47] . ';
                            text-align: ' . $styledata[49] . ';
                            -webkit-box-shadow:  ' . $styledata[55] . 'px ' . $styledata[57] . 'px   ' . $styledata[51] . 'px ' . $styledata[59] . 'px  ' . $styledata[53] . ';
                            -o-box-shadow:  ' . $styledata[55] . 'px ' . $styledata[57] . 'px   ' . $styledata[51] . 'px ' . $styledata[59] . 'px  ' . $styledata[53] . ';
                            -ms-box-shadow:   ' . $styledata[55] . 'px ' . $styledata[57] . 'px   ' . $styledata[51] . 'px ' . $styledata[59] . 'px  ' . $styledata[53] . ';
                            -moz-box-shadow:   ' . $styledata[55] . 'px ' . $styledata[57] . 'px   ' . $styledata[51] . 'px ' . $styledata[59] . 'px  ' . $styledata[53] . ';
                            box-shadow:   ' . $styledata[55] . 'px ' . $styledata[57] . 'px   ' . $styledata[51] . 'px ' . $styledata[59] . 'px  ' . $styledata[53] . ';
                            padding: ' . $styledata[35] . 'px ' . $styledata[37] . 'px ' . $styledata[39] . 'px ' . $styledata[41] . 'px ;
                            border-radius: 0 0 ' . $styledata[27] . 'px ' . $styledata[27] . 'px;
                        }
                        .ctu-ulitate-style-' . $styleid . '-tabs p{
                            font-size: ' . $styledata[29] . 'px;
                            color: ' . $styledata[31] . ';
                            line-height: ' . $styledata[43] . ';
                            font-family:    ' . $this->font_familly($styledata[45]) . ';
                            margin-top: 0;
                            margin-bottom: 0;
                        }
                        @media only screen and (max-width: 900px) {
                            .ctu-ultimate-wrapper-' . $styleid . '{
                                display: block;
                            }
                            .ctu-ultimate-style-' . $styleid . '-content{
                                width: 100%;
                                margin-bottom: 10px;
                            }
                            .ctu-ulimate-style-' . $styleid . ' {
                                display: none;
                            }
                            .ctu-ultimate-style-heading-' . $styleid . '{
                                display: block;
                            }
                        }
                        ' . $styledata[69] . '';
    }

    public function default_render() {
        $styledata = $this->style;
        $styleid = $this->ID;
        echo '<div class="ctu-ultimate-wrapper-' . $styleid . '">';
        $linkopening = '';
        if (!empty($styledata[75]) && $styledata[75] != 'new-tab') {
            $linkopening = ", '_self'";
        }
        echo '<div class="ctu-ulimate-style-' . $styleid . '">';
        foreach ($this->child as $value) {
            $titlefiles = explode('{}{}{}', $value['title']);
            if (!empty($titlefiles[1]) && $this->user != 'admin') {
                $this->JQUERY .= '$(".vc-tabs-li-' . $styleid . '-id-' . $value['id'] . '").click(function() {window.open("' . $titlefiles[1] . '" ' . $linkopening . ');});';
            }
            echo '<div class="vc-tabs-li vc-tabs-li-' . $styleid . '-id-' . $value['id'] . '" ref="#ctu-ulitate-style-' . $styleid . '-id-' . $value['id'] . '">
                                ' . $this->icon_font_selector($value['css']) . '
                                ' . $this->special_charecter($titlefiles[0]) . '
                                    <div class="ctu-absolute"></div>
                            </div>';
        }
        echo '</div>';
        foreach ($this->child as $value) {
            $titlefiles = explode('{}{}{}', $value['title']);
            echo ' <div class="ctu-ultimate-style-' . $styleid . '-content">
                    <div class="ctu-ultimate-style-heading-' . $styleid . ' vc-tabs-li-' . $styleid . '-id-' . $value['id'] . '" ref="#ctu-ulitate-style-' . $styleid . '-id-' . $value['id'] . '"> 
                        ' . $this->icon_font_selector($value['css']) . ' ' . $this->special_charecter($titlefiles[0]) . '
                    </div>
                    <div class="ctu-ulitate-style-' . $styleid . '-tabs ' . ($this->user == 'admin' ? 'oxi-addons-admin-edit-list' : '') . '" id="ctu-ulitate-style-' . $styleid . '-id-' . $value['id'] . '">
                        ' . $this->special_charecter($value['files']) . '
                        ' . $this->admin_edit_panel($value['id']) . '
                    </div>
                </div> ';
        }
        echo '</div>';
    }

}
