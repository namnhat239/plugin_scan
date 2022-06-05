<?php

namespace OXI_TABS_PLUGINS\Render\Old_Views;

/**
 * Description of Old Style
 *
 * @author biplob018
 */
use OXI_TABS_PLUGINS\Render\Old_Render;

class Style2 extends Old_Render {

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
                });
                var contentliwidth = $(".ctu-ulimate-style-' . $styleid . ' .vc-tabs-li").outerWidth();
                var count = $(".ctu-ulimate-style-' . $styleid . ' .vc-tabs-li").length;
                var fullwidth = $(".ctu-ulimate-style-' . $styleid . '").width();
                var widthresult = contentliwidth * count;
                if (fullwidth <= widthresult) {
                    var eachwidth = parseInt(fullwidth / count) + "px";
                    $(".ctu-ulimate-style-' . $styleid . ' .vc-tabs-li").css("width", eachwidth);
                };';
    }

    public function inline_public_css() {
        $styledata = $this->style;
        $styleid = $this->ID;

        $this->CSS .= ' .ctu-ulimate-style-' . $styleid . '{
                    margin: 0 0 0 0;
                    width: 100%;
                    display: -webkit-box;
                    display: -ms-flexbox;
                    display: -o-flexbox;
                    display: -moz-flexbox;
                    display: flex;
                    float: left;
                    list-style: none;
                    text-align: center;
                    -webkit-box-pack: ' . $styledata[13] . ';
                    -ms-flex-pack: ' . $styledata[13] . ';
                    -o-flex-pack: ' . $styledata[13] . ';
                    -moz-flex-pack: ' . $styledata[13] . ';
                    justify-content: ' . $styledata[13] . ';
                    background-color: ' . $styledata[5] . ';
                    margin-bottom: 0;
                }
                .ctu-ultimate-wrapper-' . $styleid . '{
                    width: 100%;
                    float: left;
                    background: transparent;
                    overflow: hidden;
                    border-radius: ' . $styledata[19] . 'px ' . $styledata[19] . 'px 0 0;
                    -webkit-box-shadow: ' . $styledata[49] . 'px  ' . $styledata[51] . 'px ' . $styledata[43] . 'px ' . $styledata[53] . 'px ' . $styledata[45] . ';
                    -o-box-shadow: ' . $styledata[49] . 'px  ' . $styledata[51] . 'px ' . $styledata[43] . 'px ' . $styledata[53] . 'px ' . $styledata[45] . ';
                    -ms-box-shadow: ' . $styledata[49] . 'px  ' . $styledata[51] . 'px ' . $styledata[43] . 'px ' . $styledata[53] . 'px ' . $styledata[45] . ';
                    -moz-box-shadow: ' . $styledata[49] . 'px  ' . $styledata[51] . 'px ' . $styledata[43] . 'px ' . $styledata[53] . 'px ' . $styledata[45] . ';
                    box-shadow: ' . $styledata[49] . 'px  ' . $styledata[51] . 'px ' . $styledata[43] . 'px ' . $styledata[53] . 'px ' . $styledata[45] . ';
                }
                .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li{
                    cursor: pointer;
                    float: left;
                    text-align: center;
                    list-style: none;
                    display: -webkit-box;
                    display: -ms-flexbox;
                    display: -o-flexbox;
                    display: -moz-flexbox;
                    display: flex;
                    -webkit-box-pack: center;
                    -ms-flex-pack: center;
                    -o-flex-pack: center;
                    -moz-flex-pack: center;
                    justify-content: center ;
                    -webkit-box-align: center;
                    -ms-flex-align: center;
                    -o-flex-align: center;
                    -moz-flex-align: center;
                    align-items: center;
                    position: relative;
                    font-style: ' . $styledata[47] . ';
                    margin: ' . $styledata[17] . 'px 0;
                    padding: 0 10px;
                    font-size: ' . $styledata[1] . 'px;
                    line-height: 130%;
                    border-right: 1px solid #ccc;
                    color: ' . $styledata[3] . ';
                    width: ' . $styledata[15] . 'px;
                    font-family: ' . $this->font_familly($styledata[9]) . ';
                    font-weight: ' . $styledata[11] . ';
                }
                .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li:last-child{
                    border-right: none;
                }
                .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li.active{
                    position: relative;
                    -webkit-transition:  all 0.5s linear;
                    -o-transition:  all 0.5s linear;
                    -ms-transition:  all 0.5s linear;
                    -moz-transition:  all 0.5s linear;
                    transition:  all 0.5s linear;
                    color: ' . $styledata[7] . ';
                }
                .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li .ctu-absolute{
                    position: absolute;
                    margin: auto;
                    bottom: -' . $styledata[17] . 'px;
                    left: 0;
                    right: 0;
                    width: 30px;
                    height: 30px;
                    display: none;
                    width: 0;
                    height: 0;
                    border-left: 10px solid transparent;
                    border-right: 10px solid transparent;
                    border-bottom: 10px solid ' . $styledata[7] . ';
                }
                .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li.active .ctu-absolute{
                    display: block;
                }
                .ctu-ultimate-style-' . $styleid . '-content{
                    width: 100%;
                    float: left;
                }
                .ctu-ultimate-style-heading-' . $styleid . '{
                    cursor: pointer;
                    display: none;
                    width: 100%;
                    font-size: ' . $styledata[1] . 'px;
                    font-weight: ' . $styledata[11] . ';
                    font-family: ' . $this->font_familly($styledata[9]) . ';
                    line-height: 120%;
                    font-style: ' . $styledata[47] . ';
                    padding: ' . $styledata[17] . 'px 10px;
                    text-align: center;            
                    border-radius: ' . $styledata[19] . 'px;            
                    background-color:  ' . $styledata[5] . ';
                    color: ' . $styledata[3] . ';
                }
                .ctu-ultimate-style-heading-' . $styleid . '.active{
                    -webkit-transition:  all 0.5s linear;
                    -o-transition:  all 0.5s linear;
                    -ms-transition:  all 0.5s linear;
                    -moz-transition:  all 0.5s linear;
                    transition:  all 0.5s linear;
                    border-radius: ' . $styledata[19] . 'px ' . $styledata[19] . 'px 0 0;
                    color: ' . $styledata[7] . ';
                }
                .ctu-ulitate-style-' . $styleid . '-tabs{
                    width: 100%;
                    display: none;
                    padding: ' . $styledata[27] . 'px ' . $styledata[29] . 'px ' . $styledata[31] . 'px ' . $styledata[33] . 'px;            
                    background-color: ' . $styledata[25] . ';
                }
                .ctu-ulitate-style-' . $styleid . '-tabs p{
                    line-height: ' . $styledata[35] . ';
                    font-family:  ' . $this->font_familly($styledata[37]) . ';
                    font-weight: ' . $styledata[39] . ';
                    color: ' . $styledata[23] . ';
                    font-size: ' . $styledata[21] . 'px;
                    text-align: ' . $styledata[41] . ';
                    margin-top: 0;
                    margin-bottom: 0;
                }
                @media only screen and (max-width: 900px) {
                    .ctu-ultimate-wrapper-' . $styleid . '{
                        -webkit-box-shadow: none !important;
                        -o-box-shadow: none !important;
                        -ms-box-shadow: none !important;
                        -moz-box-shadow: none !important;
                        box-shadow: none !important;
                    }
                    .ctu-ulimate-style-' . $styleid . ' {
                        display: none;
                    }
                    .ctu-ultimate-style-heading-' . $styleid . '{
                        display: block;
                    }
                    .ctu-ultimate-style-' . $styleid . '-content{
                        margin-bottom: 10px;
                    } 
                    .ctu-ultimate-wrapper-' . $styleid . '{
                        -webkit-box-shadow: none !important;
                        -o-box-shadow: none !important;
                        -ms-box-shadow: none !important;
                        -moz-box-shadow: none !important;
                        box-shadow: none !important;
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
        echo ' </div>';
        foreach ($this->child as $value) {
            $titlefiles = explode('{}{}{}', $value['title']);
            echo '<div class="ctu-ultimate-style-' . $styleid . '-content">
                    <div class="ctu-ultimate-style-heading-' . $styleid . ' vc-tabs-li-' . $styleid . '-id-' . $value['id'] . '" ref="#ctu-ulitate-style-' . $styleid . '-id-' . $value['id'] . '"> 
                        ' . $this->special_charecter($titlefiles[0]) . '
                    </div>
                    <div class="ctu-ulitate-style-' . $styleid . '-tabs ' . ($this->user == 'admin' ? 'oxi-addons-admin-edit-list' : '') . '" id="ctu-ulitate-style-' . $styleid . '-id-' . $value['id'] . '">
                        ' . $this->special_charecter($value['files']) . '
                        ' . $this->admin_edit_panel($value['id']) . '
                    </div>
                </div>';
        }
        echo '</div>';
    }

}
