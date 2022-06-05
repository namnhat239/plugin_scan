<?php

namespace OXI_TABS_PLUGINS\Render\Old_Views;

/**
 * Description of Old Style
 *
 * @author biplob018
 */
use OXI_TABS_PLUGINS\Render\Old_Render;

class Style7 extends Old_Render {

    public function inline_public_jquery() {
        $styledata = $this->style;
        $styleid = $this->ID;
        $oxi_fixed_header = get_option('oxi_addons_fixed_header_size');
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
        $this->CSS .= '.ctu-ulimate-style-' . $styleid . '{
                    margin: 0 0 0 0;
                    width: 100%;
                    display: -webkit-box;
                    display: -ms-flexbox;
                    display: -o-flexbox;
                    display: -moz-flexbox;
                    display: flex;
                    margin-bottom: 0;
                    list-style: none;
                    background-color:  ' . $styledata[5] . ';
                    -webkit-box-pack: ' . $styledata[19] . ';;
                    -ms-flex-pack: ' . $styledata[19] . ';;
                    -o-flex-pack: ' . $styledata[19] . ';;
                    -moz-flex-pack: ' . $styledata[19] . ';;
                    justify-content:  ' . $styledata[19] . ';
                    border-bottom: 1px solid ' . $styledata[11] . ';
                }
                .ctu-ultimate-wrapper-' . $styleid . '{
                    width: 100%;
                    float: left;
                    -webkit-box-shadow: ' . $styledata[51] . 'px ' . $styledata[53] . 'px ' . $styledata[47] . 'px ' . $styledata[55] . 'px ' . $styledata[49] . '; 
                    -o-box-shadow: ' . $styledata[51] . 'px ' . $styledata[53] . 'px ' . $styledata[47] . 'px ' . $styledata[55] . 'px ' . $styledata[49] . '; 
                    -ms-box-shadow: ' . $styledata[51] . 'px ' . $styledata[53] . 'px ' . $styledata[47] . 'px ' . $styledata[55] . 'px ' . $styledata[49] . '; 
                    -moz-box-shadow:' . $styledata[51] . 'px ' . $styledata[53] . 'px ' . $styledata[47] . 'px ' . $styledata[55] . 'px ' . $styledata[49] . '; 
                    box-shadow: ' . $styledata[51] . 'px ' . $styledata[53] . 'px ' . $styledata[47] . 'px ' . $styledata[55] . 'px ' . $styledata[49] . '; 
                    border: 1px solid ' . $styledata[45] . ';
                    border-radius: ' . $styledata[59] . 'px ' . $styledata[59] . 'px 0 0;
                    overflow: hidden;
                }
                .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li{
                    width: ' . $styledata[17] . 'px;
                    float: left;
                    list-style: none;
                    text-align: center;
                    cursor: pointer;
                    margin-bottom: 0;
                    font-size: ' . $styledata[1] . 'px;
                    color: ' . $styledata[3] . ';
                    font-family:  ' . $this->font_familly($styledata[13]) . ';
                    font-weight: ' . $styledata[15] . ';
                    padding: ' . $styledata[21] . 'px 10px;
                    border-right: 1px solid ' . $styledata[11] . ';

                }
                .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li:last-child{
                    border-right: none;
                }
                .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li.active{
                    color: ' . $styledata[7] . ';
                    background-color: ' . $styledata[9] . ';
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
                    background-color: ' . $styledata[5] . ';
                    font-size: ' . $styledata[1] . 'px;
                    color: ' . $styledata[3] . ';
                    padding: ' . $styledata[21] . 'px 10px;
                    font-weight: ' . $styledata[15] . ';
                    font-family:  ' . $this->font_familly($styledata[13]) . ';
                }
                .ctu-ultimate-style-heading-' . $styleid . '.active{
                    color: #003eff;
                    background-color: ' . $styledata[9] . ';
                    border-bottom: 1px solid ' . $styledata[11] . ';
                }
                .ctu-ulitate-style-' . $styleid . '-tabs{
                    width: 100%;
                    float: left;
                    display: none;            
                    background-color: ' . $styledata[27] . ';           
                    padding: ' . $styledata[29] . 'px ' . $styledata[31] . 'px ' . $styledata[33] . 'px ' . $styledata[35] . 'px;
                }
                .ctu-ulitate-style-' . $styleid . '-tabs p{
                    margin-bottom: 0;
                    margin-top: 0;
                    font-size: ' . $styledata[23] . 'px;
                    color: ' . $styledata[25] . ';
                    font-weight: ' . $styledata[41] . ';
                    line-height: ' . $styledata[37] . ';
                    font-family:  ' . $this->font_familly($styledata[39]) . ';
                }
                @media only screen and (max-width: 900px) {
                    .ctu-ultimate-wrapper-' . $styleid . '{
                        display: block;
                        -webkit-box-shadow: none;
                        -o-box-shadow: none;
                        -ms-box-shadow: none;
                        -moz-box-shadow: none;
                        box-shadow: none;
                        border: none;
                        overflow: visible;
                    }
                    .ctu-ultimate-style-' . $styleid . '-content{
                        -webkit-box-shadow:  0 0 ' . $styledata[47] . 'px ' . $styledata[49] . ';
                        -o-box-shadow:  0 0 ' . $styledata[47] . 'px ' . $styledata[49] . ';
                        -ms-box-shadow:   0 0 ' . $styledata[47] . 'px ' . $styledata[49] . ';
                        -moz-box-shadow:  0 0 ' . $styledata[47] . 'px ' . $styledata[49] . ';
                        box-shadow:   0 0 ' . $styledata[47] . 'px ' . $styledata[49] . ';
                        border: 1px solid  ' . $styledata[45] . ';
                        border-radius: ' . $styledata[59] . 'px;
                        overflow: hidden;
                        margin-bottom: 10px;
                    }
                    .ctu-ulimate-style-' . $styleid . ' {
                        display: none;
                    }
                    .ctu-ultimate-style-heading-' . $styleid . '{
                        display: block;
                    }

                }
                ' . $styledata[61] . '';
    }

    public function default_render() {
        $styledata = $this->style;
        $styleid = $this->ID;
        echo '<div class="ctu-ultimate-wrapper-' . $styleid . '">
             <div class="ctu-ulimate-style-' . $styleid . '">';
        $linkopening = '';
        if (!empty($styledata[67]) && $styledata[67] != 'new-tab') {
            $linkopening = ", '_self'";
        }
        foreach ($this->child as $value) {
            $titlefiles = explode('{}{}{}', $value['title']);
            if (!empty($titlefiles[1]) && $this->user != 'admin') {
                $this->JQUERY .= '$(".vc-tabs-li-' . $styleid . '-id-' . $value['id'] . '").click(function() {window.open("' . $titlefiles[1] . '" ' . $linkopening . ');});';
            }
            echo '<div class="vc-tabs-li vc-tabs-li-' . $styleid . '-id-' . $value['id'] . '" ref="#ctu-ulitate-style-' . $styleid . '-id-' . $value['id'] . '">
                                ' . $this->special_charecter($titlefiles[0]) . ' 
                            </div>';
        }
        echo '</div>';
        foreach ($this->child as $value) {
            $titlefiles = explode('{}{}{}', $value['title']);
            echo ' <div class="ctu-ultimate-style-' . $styleid . '-content">
                        <div class="ctu-ultimate-style-heading-' . $styleid . ' vc-tabs-li-' . $styleid . '-id-' . $value['id'] . '" ref="#ctu-ulitate-style-' . $styleid . '-id-' . $value['id'] . '"> 
                            ' . $this->special_charecter($titlefiles[0]) . '
                        </div>
                        <div class="ctu-ulitate-style-' . $styleid . '-tabs ' . ($this->user == 'admin' ? 'oxi-addons-admin-edit-list' : '') . '" id="ctu-ulitate-style-' . $styleid . '-id-' . $value['id'] . '">
                            ' . $this->special_charecter($value['files']) . '
                            ' . $this->admin_edit_panel($value['id']) . '
                        </div>
                    </div> ';
        }
        echo ' </div>  ';
    }

}
