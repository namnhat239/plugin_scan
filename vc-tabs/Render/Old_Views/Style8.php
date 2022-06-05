<?php

namespace OXI_TABS_PLUGINS\Render\Old_Views;

/**
 * Description of Old Style
 *
 * @author biplob018
 */
use OXI_TABS_PLUGINS\Render\Old_Render;

class Style8 extends Old_Render {

    public function inline_public_jquery() {
        $styledata = $this->style;
        $styleid = $this->ID;
        $oxi_fixed_header = get_option('oxi_addons_fixed_header_size');
        if (empty($styledata[47])) {
            $initialopen = ':first';
        } else if ($styledata[47] == 'none') {
            $initialopen = '';
        } else {
            $initialopen = $styledata[47];
        }
        if (empty($styledata[49])) {
            $animationin = 'slideDown';
            $animationout = 'slideUp';
        } else if ($styledata[49] == 'slide') {
            $animationin = 'slideDown';
            $animationout = 'slideUp';
        } else if ($styledata[49] == 'show') {
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
                }
                .ctu-ulimate-style-' . $styleid . '{
                    margin: 0 0 0 0;
                    width: 100%;
                    float: left;
                    list-style: none;            
                    display: -webkit-box;
                    display: -ms-flexbox;
                    display: -o-flexbox;
                    display: -moz-flexbox;
                    display: flex;
                    text-align: center;
                    -webkit-box-pack: ' . $styledata[17] . ';
                    -ms-flex-pack: ' . $styledata[17] . ';
                    -o-flex-pack: ' . $styledata[17] . ';
                    -moz-flex-pack: ' . $styledata[17] . ';
                    justify-content: ' . $styledata[17] . ';
                    margin-bottom: 0;
                }
                .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li{
                    width: ' . $styledata[15] . 'px;
                    float: left;
                    z-index: 2;
                    position: relative;
                    bottom: -1px;
                    list-style: none;
                    cursor: pointer;
                    margin-bottom: 0;
                    border-top: 1px solid;
                    border-right: 1px solid;
                    border-bottom: 1px solid;
                    border-color: ' . $styledata[43] . ';
                    padding: ' . $styledata[19] . 'px 10px;
                    text-align: center;
                    color: ' . $styledata[3] . ';
                    background-color:  ' . $styledata[5] . ';
                    font-size: ' . $styledata[1] . 'px;
                    font-family:    ' . $this->font_familly($styledata[11]) . ';
                    font-weight: ' . $styledata[13] . ';
                    line-height: 130%;
                }
                .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li:first-child{
                    border-left: 1px solid ' . $styledata[43] . ';
                }
                .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li.active{
                    color: ' . $styledata[7] . ';
                    background-color:  ' . $styledata[9] . ';
                    border-bottom-color: ' . $styledata[9] . ';
                }
                .ctu-ultimate-style-' . $styleid . '-content{
                    width: 100%;
                    float: left;
                }
                .ctu-ultimate-style-heading-' . $styleid . '{
                    width: 100%;
                    cursor: pointer;
                    display: none;
                    line-height: 100%;
                    color: ' . $styledata[3] . ';
                    background-color: ' . $styledata[5] . ';
                    font-size: ' . $styledata[1] . 'px;
                    padding: ' . $styledata[19] . 'px 10px;
                    font-weight: ' . $styledata[15] . ';
                    font-family:  ' . $this->font_familly($styledata[11]) . ';
                }
                .ctu-ultimate-style-heading-' . $styleid . '.active{
                    color: ' . $styledata[7] . ';
                    background-color: ' . $styledata[9] . ';
                    border-bottom: 1px solid ' . $styledata[43] . ';
                }
                .ctu-ulitate-style-' . $styleid . '-tabs{
                    display: none;
                    border: 1px solid ' . $styledata[43] . ';           
                    background-color:' . $styledata[25] . ';           
                    padding: ' . $styledata[27] . 'px ' . $styledata[29] . 'px ' . $styledata[31] . 'px ' . $styledata[33] . 'px ;


                }
                .ctu-ulitate-style-' . $styleid . '-tabs p{
                    font-size: ' . $styledata[21] . 'px;
                    color: ' . $styledata[23] . ';
                    font-weight: ' . $styledata[39] . ';
                    line-height: ' . $styledata[35] . ';
                    font-family:  ' . $this->font_familly($styledata[37]) . ';
                    text-align: ' . $styledata[41] . ';
                }
                @media only screen and (max-width: 900px) {
                    .ctu-ultimate-wrapper-' . $styleid . '{
                        display: block;
                    }
                    .ctu-ultimate-style-' . $styleid . '-content{
                        width: 100%;
                        margin-bottom: 10px;
                        border: 1px solid ' . $styledata[43] . ';
                    }
                    .ctu-ulimate-style-' . $styleid . ' {
                        display: none;
                    }
                    .ctu-ultimate-style-heading-' . $styleid . '{
                        display: block;
                    }
                    .ctu-ulitate-style-' . $styleid . '-tabs{
                        border:none;
                    }
                }
                ' . $styledata[45] . '';
    }

    public function default_render() {
        $styledata = $this->style;
        $styleid = $this->ID;
        echo '<div class="ctu-ultimate-wrapper-' . $styleid . '">
             <div class="ctu-ulimate-style-' . $styleid . '">';
        $linkopening = '';
        if (!empty($styledata[51]) && $styledata[51] != 'new-tab') {
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
        echo '</div>';
    }

}
