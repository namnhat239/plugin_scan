<?php

namespace OXI_TABS_PLUGINS\Render\Old_Views;

/**
 * Description of Old Style
 *
 * @author biplob018
 */
use OXI_TABS_PLUGINS\Render\Old_Render;

class Style5 extends Old_Render {

    public function inline_public_jquery() {
        $oxi_fixed_header = get_option('oxi_addons_fixed_header_size');
        $styledata = $this->style;
        $styleid = $this->ID;
        if (empty($styledata[63])) {
            $initialopen = ':first';
        } else if ($styledata[63] == 'none') {
            $initialopen = '';
        } else {
            $initialopen = $styledata[63];
        }
        if (empty($styledata[65])) {
            $animationin = 'slideDown';
            $animationout = 'slideUp';
        } else if ($styledata[65] == 'slide') {
            $animationin = 'slideDown';
            $animationout = 'slideUp';
        } else if ($styledata[65] == 'show') {
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
        $this->CSS .= '.ctu-ultimate-wrapper-' . $styleid . '{
                    width: 100%;
                    float: left;
                    -webkit-box-shadow: ' . $styledata[53] . 'px ' . $styledata[55] . 'px ' . $styledata[49] . 'px ' . $styledata[57] . 'px ' . $styledata[51] . '; 
                    -o-box-shadow: ' . $styledata[53] . 'px ' . $styledata[55] . 'px ' . $styledata[49] . 'px ' . $styledata[57] . 'px ' . $styledata[51] . '; 
                    -ms-box-shadow: ' . $styledata[53] . 'px ' . $styledata[55] . 'px ' . $styledata[49] . 'px ' . $styledata[57] . 'px ' . $styledata[51] . '; 
                    -moz-box-shadow: ' . $styledata[53] . 'px ' . $styledata[55] . 'px ' . $styledata[49] . 'px ' . $styledata[57] . 'px ' . $styledata[51] . '; 
                    box-shadow: ' . $styledata[53] . 'px ' . $styledata[55] . 'px ' . $styledata[49] . 'px ' . $styledata[57] . 'px ' . $styledata[51] . '; 
                    border-radius: ' . $styledata[25] . 'px ' . $styledata[25] . 'px 0 0;
                }
                .ctu-ulimate-style-' . $styleid . '{
                    margin: 0 0 0 0;
                    width: 100%;
                    display: -webkit-box;
                    display: -ms-flexbox;
                    display: -o-flexbox;
                    display: -moz-flexbox;
                    display: flex;
                    list-style:none;
                    float: left;
                    text-align: center;
                    margin: 0;
                    border-radius: ' . $styledata[25] . 'px ' . $styledata[25] . 'px 0 0;
                    -webkit-box-pack: ' . $styledata[17] . ';
                    -ms-flex-pack: ' . $styledata[17] . ';
                    -o-flex-pack: ' . $styledata[17] . ';
                    -moz-flex-pack: ' . $styledata[17] . ';
                    justify-content:  ' . $styledata[17] . ';
                    border-bottom: 1px solid ' . $styledata[19] . ';
                    background-color: ' . $styledata[5] . ';
                    z-index: 1;
                    position: relative;
                }
                .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li{
                    cursor: pointer;
                    float: left;
                    list-style:none;
                    margin-bottom: 0;
                    position: relative;
                    padding: ' . $styledata[23] . 'px 10px;        
                    font-size: ' . $styledata[1] . 'px;
                    line-height: 120%;
                    color: ' . $styledata[3] . ';
                    width: ' . $styledata[21] . 'px;
                    font-family:  ' . $this->font_familly($styledata[13]) . ';
                    font-weight: ' . $styledata[15] . ';
                }
                .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li span{
                    width: 100%;
                    display: block;
                    text-align: center;
                }
                .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li span .oxi-icons{
                    margin-bottom: ' . $styledata[11] . 'px !important;
                    font-size: ' . $styledata[9] . 'px !important;

                }
                .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li span .oxi-icons{
                    color: ' . $styledata[3] . ' !important;
                }
                .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li.active span .oxi-icons{
                    color: ' . $styledata[7] . ' !important;
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
                .ctu-absulote{
                    position: absolute;
                    width: 11px;
                    height: 11px;
                    margin: auto;
                    left: 0;
                    right: 0;
                    border-color: ' . $styledata[19] . ';
                    border: 1px solid;
                    border-radius: 10px;
                    bottom: -6px;
                    background-color: transparent;
                }
                .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li.active .ctu-absulote{
                    background-color: ' . $styledata[7] . ';
                    border-color: ' . $styledata[7] . ';
                }
                .ctu-ultimate-style-' . $styleid . '-content{
                    width: 100%;
                    float: left;
                }
                .ctu-ultimate-style-heading-' . $styleid . '{
                    cursor: pointer;
                    display: none;
                    width: 100%;
                    font-weight: ' . $styledata[19] . ';
                    font-family:  ' . $this->font_familly($styledata[13]) . ' ;
                    line-height: 120%;
                    padding: ' . $styledata[23] . 'px 10px;
                    text-align: center;
                    font-size: ' . $styledata[1] . 'px;
                    color: ' . $styledata[3] . ';
                    background-color: ' . $styledata[5] . ';
                    border-radius: ' . $styledata[25] . 'px;
                }
                .ctu-ultimate-style-heading-' . $styleid . '.active{
                    -webkit-transition:  all 0.5s linear;
                    -o-transition:  all 0.5s linear;
                    -ms-transition:  all 0.5s linear;
                    -moz-transition:  all 0.5s linear;
                    transition:  all 0.5s linear;
                    color: ' . $styledata[7] . ';
                    border-bottom: 1px solid ' . $styledata[19] . ';
                    border-radius: ' . $styledata[25] . 'px ' . $styledata[25] . 'px 0 0;
                }
                .ctu-ulitate-style-' . $styleid . '-tabs{
                    width: 100%;
                    display: none;
                    padding: ' . $styledata[33] . 'px ' . $styledata[35] . 'px ' . $styledata[37] . 'px ' . $styledata[39] . 'px;
                    background-color: ' . $styledata[31] . ';
                }
                .ctu-ulitate-style-' . $styleid . '-tabs p{
                    margin-bottom: 0;
                    margin-top: 0;
                    font-size: ' . $styledata[27] . 'px;
                    color: ' . $styledata[29] . ';
                    line-height: ' . $styledata[41] . ';
                    text-align: ' . $styledata[47] . ';
                    font-family:  ' . $this->font_familly($styledata[43]) . ';
                    font-weight: ' . $styledata[45] . ';
                }
                @media only screen and (max-width: 900px) {
                    .ctu-ultimate-wrapper-' . $styleid . '{
                        -webkit-box-shadow: none;
                        -o-box-shadow: none;
                        -moz-box-shadow: none;
                        -ms-box-shadow: none;
                        box-shadow: none;
                        border-radius: 0;
                    }
                    .ctu-ulimate-style-' . $styleid . ' {
                        display: none;
                    }
                    .ctu-ultimate-style-heading-' . $styleid . '{
                        display: block;
                    }
                    .ctu-ultimate-style-' . $styleid . '-content{
                        margin-bottom: 10px;
                        -webkit-box-shadow: 0 0 5px ' . $styledata[31] . ';
                        -o-box-shadow: 0 0 5px ' . $styledata[31] . ';
                        -ms-box-shadow: 0 0 5px ' . $styledata[31] . ';
                        -moz-box-shadow: 0 0 5px ' . $styledata[31] . ';
                        box-shadow: 0 0 5px ' . $styledata[31] . ';
                        border-radius: ' . $styledata[25] . 'px;
                        overflow: hidden;
                    }
                }
                ' . $styledata[61] . ' ';
    }

    public function default_render() {
        $styledata = $this->style;
        $styleid = $this->ID;

        echo '<div class="ctu-ultimate-wrapper-' . $styleid . '">
             <div class="ctu-ulimate-style-' . $styleid . '">';

        $jquery = $linkopening = '';
        if (!empty($styledata[67]) && $styledata[67] != 'new-tab') {
            $linkopening = ", '_self'";
        }
        foreach ($this->child as $value) {
            $titlefiles = explode('{}{}{}', $value['title']);
            if (!empty($titlefiles[1]) && $this->user != 'admin') {
                $jquery .= '$(".vc-tabs-li-' . $styleid . '-id-' . $value['id'] . '").click(function() {window.open("' . $titlefiles[1] . '" ' . $linkopening . ');});';
            }
            echo '<div class="vc-tabs-li vc-tabs-li-' . $styleid . '-id-' . $value['id'] . '" ref="#ctu-ulitate-style-' . $styleid . '-id-' . $value['id'] . '" class="">
                                <span>' . $this->icon_font_selector($value['css']) . '</span>
                                ' . $this->special_charecter($titlefiles[0]) . '
                                <div class="ctu-absulote"></div>
                            </div> ';
        }
        echo '  </div>';
        foreach ($this->child as $value) {
            $titlefiles = explode('{}{}{}', $value['title']);
            echo ' <div class="ctu-ultimate-style-' . $styleid . '-content">
                    <div class="ctu-ultimate-style-heading-' . $styleid . ' vc-tabs-li-' . $styleid . '-id-' . $value['id'] . '" ref="#ctu-ulitate-style-' . $styleid . '-id-' . $value['id'] . '"> 
                        ' . $this->icon_font_selector($value['css']) . '   ' . $this->special_charecter($titlefiles[0]) . '

                    </div>
                    <div class="ctu-ulitate-style-' . $styleid . '-tabs ' . ($this->user == 'admin' ? 'oxi-addons-admin-edit-list' : '') . '" id="ctu-ulitate-style-' . $styleid . '-id-' . $value['id'] . '">
                        ' . $this->special_charecter($value['files']) . '
                        ' . $this->admin_edit_panel($value['id']) . '
                    </div>
                </div>';
        }
        echo ' </div>';
    }

}
