<?php

namespace OXI_TABS_PLUGINS\Render\Old_Views;

/**
 * Description of Old Style
 *
 * @author biplob018
 */
use OXI_TABS_PLUGINS\Render\Old_Render;

class Style19 extends Old_Render {

    public function inline_public_jquery() {
        $styledata = $this->style;
        $styleid = $this->ID;
        $oxi_fixed_header = get_option('oxi_addons_fixed_header_size');
        if (empty($styledata[57])) {
            $initialopen = ':first';
        } else if ($styledata[57] == 'none') {
            $initialopen = '';
        } else {
            $initialopen = $styledata[57];
        }
        if (empty($styledata[59])) {
            $animationin = 'slideDown';
            $animationout = 'slideUp';
        } else if ($styledata[59] == 'slide') {
            $animationin = 'slideDown';
            $animationout = 'slideUp';
        } else if ($styledata[59] == 'show') {
            $animationin = 'show';
            $animationout = 'hide';
        } else {
            $animationin = 'fadeIn';
            $animationout = 'fadeOut';
        }
        $this->JQUERY .= '$(".ctu-ulimate-style-' . $styleid . ' .vc-tabs-li' . $initialopen . '").addClass("active");
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
        $this->CSS = '.ctu-ultimate-wrapper-' . $styleid . '{
                    width: 100%;
                    float: left;
                    -webkit-box-shadow:' . $styledata[49] . 'px ' . $styledata[51] . 'px ' . $styledata[43] . 'px ' . $styledata[53] . 'px ' . $styledata[45] . ';
                    -o-box-shadow:' . $styledata[49] . 'px ' . $styledata[51] . 'px ' . $styledata[43] . 'px ' . $styledata[53] . 'px ' . $styledata[45] . ';
                    -ms-box-shadow:' . $styledata[49] . 'px ' . $styledata[51] . 'px ' . $styledata[43] . 'px ' . $styledata[53] . 'px ' . $styledata[45] . ';
                    -moz-box-shadow:' . $styledata[49] . 'px ' . $styledata[51] . 'px ' . $styledata[43] . 'px ' . $styledata[53] . 'px ' . $styledata[45] . ';
                    box-shadow:' . $styledata[49] . 'px ' . $styledata[51] . 'px ' . $styledata[43] . 'px ' . $styledata[53] . 'px ' . $styledata[45] . ';
                    background-color:  ' . $styledata[23] . ';
                    border-radius: ' . $styledata[41] . 'px;
                }
                .ctu-ulimate-style-' . $styleid . '{
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
                    -webkit-box-pack: ' . $styledata[11] . ';
                    -ms-flex-pack: ' . $styledata[11] . ';
                    -o-flex-pack: ' . $styledata[11] . ';
                    -moz-flex-pack: ' . $styledata[11] . ';
                    justify-content: ' . $styledata[11] . ';
                    margin-bottom: ' . $styledata[15] . 'px;
                }
                .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li{
                    width: 100%;
                    float: left;
                    position: relative;
                    list-style: none;
                    cursor: pointer;
                    max-width: ' . $styledata[13] . 'px;
                    margin-bottom: 0;
                    padding: ' . $styledata[17] . 'px  10px;
                    text-align: center;
                    color: ' . $styledata[3] . ';
                    font-size: ' . $styledata[1] . 'px;
                    font-style:  ' . $styledata[47] . ';
                    font-family:    ' . $this->font_familly($styledata[7]) . ';
                    font-weight: ' . $styledata[9] . ';
                    line-height: 100%;
                }
                .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li.active{
                    color: ' . $styledata[5] . ';
                    border-bottom: 2px solid;
                    border-bottom-color: ' . $styledata[5] . ';
                }
                .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li.active .ctu-absolute{
                    position: absolute;
                    bottom: -10px;
                    left: 0;
                    right: 0;
                    margin: 0 auto;
                    width: 0;
                    height: 0;
                    border-left: 10px solid transparent;
                    border-right: 10px solid transparent;
                    border-top: 10px solid;
                    border-top-color: ' . $styledata[5] . ';

                }
                .ctu-ultimate-style-' . $styleid . '-content{
                    width: 100%;
                    float: left;
                }
                .ctu-ultimate-style-heading-' . $styleid . '{
                    width: 100%;
                    float:left;
                    cursor: pointer;
                    display: none;
                    line-height: 100%;
                    background-color: ' . $styledata[23] . ';
                    color: ' . $styledata[3] . ';
                    font-size: ' . $styledata[1] . 'px;
                    padding: ' . $styledata[17] . 'px 10px;
                    font-weight: ' . $styledata[9] . ';
                    font-family:  ' . $this->font_familly($styledata[7]) . ';
                    border-radius: 5px;
                    font-style:  ' . $styledata[47] . ';
                    -webkit-box-shadow:' . $styledata[49] . 'px ' . $styledata[51] . 'px ' . $styledata[43] . 'px ' . $styledata[53] . 'px ' . $styledata[45] . ';
                    -o-box-shadow:' . $styledata[49] . 'px ' . $styledata[51] . 'px ' . $styledata[43] . 'px ' . $styledata[53] . 'px ' . $styledata[45] . ';
                    -ms-box-shadow:' . $styledata[49] . 'px ' . $styledata[51] . 'px ' . $styledata[43] . 'px ' . $styledata[53] . 'px ' . $styledata[45] . ';
                    -moz-box-shadow:' . $styledata[49] . 'px ' . $styledata[51] . 'px ' . $styledata[43] . 'px ' . $styledata[53] . 'px ' . $styledata[45] . ';
                    box-shadow:' . $styledata[49] . 'px ' . $styledata[51] . 'px ' . $styledata[43] . 'px ' . $styledata[53] . 'px ' . $styledata[45] . ';
                }
                .ctu-ultimate-style-heading-' . $styleid . '.active{
                    color: ' . $styledata[5] . ';
                    border-radius: 5px 5px 0 0;
                }
                .ctu-ulitate-style-' . $styleid . '-tabs{
                    display: none;
                    float: left;
                    text-align: ' . $styledata[39] . ';            
                    padding: ' . $styledata[25] . 'px ' . $styledata[27] . 'px ' . $styledata[29] . 'px ' . $styledata[31] . 'px;
                }
                .ctu-ulitate-style-' . $styleid . '-tabs p{
                    font-size: ' . $styledata[19] . 'px;
                    color: ' . $styledata[21] . ';
                    font-weight: ' . $styledata[37] . ';
                    font-family: ' . $this->font_familly($styledata[35]) . ';
                    line-height: ' . $styledata[33] . ';
                    margin-bottom:0;
                    margin-top:0;
                }
                @media only screen and (max-width: 900px) {
                    .ctu-ultimate-wrapper-' . $styleid . '{
                        display: block;
                        background-color: transparent;
                        -webkit-box-shadow: none;
                        -o-box-shadow: none;
                        -ms-box-shadow: none;
                        -moz-box-shadow: none;
                        box-shadow: none;
                        border-radius: 0;
                    }
                    .ctu-ulimate-style-' . $styleid . ' {
                        display: none;
                    }
                    .ctu-ultimate-style-heading-' . $styleid . '{
                        display: block;
                        margin-bottom: 10px;
                    }
                    .ctu-ulitate-style-' . $styleid . '-tabs{
                        margin-bottom: 10px;
                        -webkit-box-shadow: ' . $styledata[49] . 'px ' . $styledata[51] . 'px ' . $styledata[43] . 'px ' . $styledata[53] . 'px ' . $styledata[45] . ';
                        -o-box-shadow: ' . $styledata[49] . 'px ' . $styledata[51] . 'px ' . $styledata[43] . 'px ' . $styledata[53] . 'px ' . $styledata[45] . ';
                        -ms-box-shadow: ' . $styledata[49] . 'px ' . $styledata[51] . 'px ' . $styledata[43] . 'px ' . $styledata[53] . 'px ' . $styledata[45] . ';
                        -moz-box-shadow: ' . $styledata[49] . 'px ' . $styledata[51] . 'px ' . $styledata[43] . 'px ' . $styledata[53] . 'px ' . $styledata[45] . ';
                        box-shadow:' . $styledata[49] . 'px ' . $styledata[51] . 'px ' . $styledata[43] . 'px ' . $styledata[53] . 'px ' . $styledata[45] . ';
                    }
                }
                ' . $styledata[55] . '';
    }

    public function default_render() {
        $styledata = $this->style;
        $styleid = $this->ID;
        echo ' <div class="ctu-ultimate-wrapper-' . $styleid . '">
             <div class="ctu-ulimate-style-' . $styleid . '">';

        $linkopening = '';
        if (!empty($styledata[61]) && $styledata[61] != 'new-tab') {
            $linkopening = ", '_self'";
        }
        foreach ($this->child as $value) {
            $titlefiles = explode('{}{}{}', $value['title']);
            if (!empty($titlefiles[1]) && $this->user != 'admin') {
                $this->JQUERY .= '$(".vc-tabs-li-' . $styleid . '-id-' . $value['id'] . '").click(function() {window.open("' . $titlefiles[1] . '" ' . $linkopening . ');});';
            }
            echo '<div class="vc-tabs-li vc-tabs-li-' . $styleid . '-id-' . $value['id'] . '" ref="#ctu-ulitate-style-' . $styleid . '-id-' . $value['id'] . '">
                            ' . $this->special_charecter($titlefiles[0]) . ' 
                           <div class="ctu-absolute"></div>
                      </div>';
        }
        echo '</div>';
        echo '<div class="ctu-ultimate-style-' . $styleid . '-content">';
        foreach ($this->child as $value) {
            $titlefiles = explode('{}{}{}', $value['title']);
            echo '<div class="ctu-ultimate-style-heading-' . $styleid . ' vc-tabs-li-' . $styleid . '-id-' . $value['id'] . '" ref="#ctu-ulitate-style-' . $styleid . '-id-' . $value['id'] . '"> 
                        ' . $this->special_charecter($titlefiles[0]) . '
                    </div>
                    <div class="ctu-ulitate-style-' . $styleid . '-tabs ' . ($this->user == 'admin' ? 'oxi-addons-admin-edit-list' : '') . '" id="ctu-ulitate-style-' . $styleid . '-id-' . $value['id'] . '">
                        ' . $this->special_charecter($value['files']) . '
                        ' . $this->admin_edit_panel($value['id']) . '
                    </div> ';
        }
        echo '</div> </div>';
    }

}
