<?php

namespace OXI_TABS_PLUGINS\Render\Old_Views;

/**
 * Description of Old Style
 *
 * @author biplob018
 */
use OXI_TABS_PLUGINS\Render\Old_Render;

class Style4 extends Old_Render {

    public function inline_public_jquery() {
        $oxi_fixed_header = get_option('oxi_addons_fixed_header_size');
        $styledata = $this->style;
        $styleid = $this->ID;
        if (empty($styledata[69])) {
            $initialopen = ':first';
        } else if ($styledata[69] == 'none') {
            $initialopen = '';
        } else {
            $initialopen = $styledata[69];
        }

        $this->JQUERY .= '$(".ctu-ulimate-style-' . $styleid . ' .vc-tabs-li' . $initialopen . '").addClass("active");
                    $(".ctu-ultimate-style-heading-' . $styleid . '' . $initialopen . '").addClass("active");
                    $(".ctu-ulitate-style-' . $styleid . '-tabs' . $initialopen . '").addClass("active");
                    $(".ctu-ulimate-style-' . $styleid . ' .vc-tabs-li").click(function () {
                        if ($(this).hasClass("active")) {
                            return false;
                        } else {
                            $(".ctu-ulimate-style-' . $styleid . ' .vc-tabs-li").removeClass("active");
                            $(this).toggleClass("active");
                            $(".ctu-ulitate-style-' . $styleid . '-tabs").removeClass("active");
                            var activeTab = $(this).attr("ref");
                            $(activeTab).toggleClass("active");
                        }
                    });
                    $(".ctu-ultimate-style-heading-' . $styleid . '").click(function () {
                        if ($(this).hasClass("active")) {
                            return false;
                        } else {
                            $(".ctu-ultimate-style-heading-' . $styleid . '").removeClass("active");
                            $(this).toggleClass("active");
                            $(".ctu-ulitate-style-' . $styleid . '-tabs").removeClass("active");
                            var activeTab = $(this).attr("ref");
                            $(activeTab).addClass("active");
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
                    display: -webkit-box;
                    display: -ms-flexbox;
                    display: -o-flexbox;
                    display: -moz-flexbox;
                    display: flex;
                    -webkit-box-shadow: ' . $styledata[57] . 'px ' . $styledata[59] . 'px ' . $styledata[51] . 'px ' . $styledata[61] . 'px ' . $styledata[53] . ';
                    -o-box-shadow: ' . $styledata[57] . 'px ' . $styledata[59] . 'px ' . $styledata[51] . 'px ' . $styledata[61] . 'px ' . $styledata[53] . ';
                    -ms-box-shadow: ' . $styledata[57] . 'px ' . $styledata[59] . 'px ' . $styledata[51] . 'px ' . $styledata[61] . 'px ' . $styledata[53] . ';
                    -moz-box-shadow: ' . $styledata[57] . 'px ' . $styledata[59] . 'px ' . $styledata[51] . 'px ' . $styledata[61] . 'px ' . $styledata[53] . ';
                    box-shadow: ' . $styledata[57] . 'px ' . $styledata[59] . 'px ' . $styledata[51] . 'px ' . $styledata[61] . 'px ' . $styledata[53] . ';
                    border-radius: ' . $styledata[55] . 'px;
                    overflow: hidden;
                }
                .ctu-ulimate-style-' . $styleid . '{
                    margin: 0 0 0 0;
                    width: calc(100% - ' . $styledata[65] . '%); 
                    min-width: ' . ($styledata[15] + $styledata[19] + $styledata[25]) . 'px;
                    float: left;
                    list-style: none;
                    text-align: center;
                    -webkit-box-pack: start;
                    -ms-flex-pack: start;
                    -o-flex-pack: start;
                    -moz-flex-pack: start;
                    justify-content: flex-start;
                    overflow: hidden;
                    margin-bottom: 0;
                    background-color: ' . $styledata[5] . ';
                    padding: ' . $styledata[21] . 'px ' . $styledata[25] . 'px;
                }
                .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li{
                    width: 100%;
                    float:left;
                    position: relative;
                    list-style: none;
                    cursor: pointer;
                    max-width: ' . $styledata[15] . 'px;
                    position: relative;
                    display: block;
                    margin-bottom: 0;
                    margin-top: 0;
                    font-style: ' . $styledata[63] . ';
                    border-left: 1px solid ' . $styledata[7] . ';
                    padding: ' . $styledata[17] . 'px ' . $styledata[19] . 'px;
                    text-align: left;
                    color: ' . $styledata[3] . ';
                    font-size: ' . $styledata[1] . 'px;
                    font-family:    ' . $this->font_familly($styledata[11]) . ';
                    font-weight: ' . $styledata[13] . ';
                    line-height: 120%;
                }
                .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li.active{
                    color: ' . $styledata[9] . ';
                }
                .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li.active .ctu-absolute{
                    position: absolute;
                    top: 15%;
                    bottom: 15%;
                    width:  3px;
                    background-color: ' . $styledata[9] . ';
                    left: -2px;
                }
                .ctu-ultimate-style-' . $styleid . '-content{
                    width: ' . $styledata[65] . '%; 
                    max-width: calc(100% - ' . ($styledata[15] + $styledata[19] + $styledata[25]) . 'px);
                    float: left;
                    display: -webkit-box;
                    display: -ms-flexbox;
                    display: -o-flexbox;
                    display: -moz-flexbox;
                    display: flex;
                    overflow: hidden;
                }
                .ctu-ultimate-style-heading-' . $styleid . '{
                    width: 100%;
                    cursor: pointer;
                    float:left;
                    display: none;
                    line-height: 100%;
                    background-color: ' . $styledata[5] . ';
                    font-size: ' . $styledata[1] . 'px;
                    padding: ' . $styledata[17] . 'px 10px;
                    font-weight: ' . $styledata[13] . ';
                    font-family:  ' . $this->font_familly($styledata[11]) . ';
                }
                .ctu-ultimate-style-heading-' . $styleid . '.active{
                    color: ' . $styledata[9] . '
                }
                .ctu-ulitate-style-' . $styleid . '-tabs{
                    width:100%;
                    float:left;
                    display: none;
                    background-color: ' . $styledata[29] . ';            
                    padding: ' . $styledata[35] . 'px ' . $styledata[37] . 'px ' . $styledata[39] . 'px ' . $styledata[41] . 'px;
                    border-left: 1px solid ' . $styledata[31] . ';
                }
                .ctu-ulitate-style-' . $styleid . '-tabs.active{
                    display:block;
                }
                .ctu-ulitate-style-' . $styleid . '-tabs p{
                    font-size: ' . $styledata[23] . 'px;
                    color:' . $styledata[27] . ';
                    font-weight: ' . $styledata[47] . ';
                    font-family:  ' . $this->font_familly($styledata[45]) . ';
                    font-weight: ' . $styledata[47] . ';
                    line-height: ' . $styledata[43] . ';
                    margin-bottom: 0;
                    margin-top: 0;
                    text-align: ' . $styledata[49] . ';
                }
                .ctu-content-span{
                    max-width: 50px;
                    height: 3px;
                    background-color: ' . $styledata[9] . ';
                    margin:';

        if ($styledata[49] == 'left') {
            $this->CSS .= '0 auto 0 0';
        } elseif ($styledata[49] == 'center') {
            $this->CSS .= '0 auto';
        } elseif ($styledata[49] == 'right') {
            $this->CSS .= '0 0 0 auto';
        }

        $this->CSS .= '; margin-bottom: ' . $styledata[33] . 'px;
                }
                @media only screen and (max-width: 900px) {
                    .ctu-ultimate-wrapper-' . $styleid . '{
                        display: block;
                        -webkit-box-shadow: none;
                        -o-box-shadow: none;
                        -ms-box-shadow: none;
                        -moz-box-shadow: none;
                        box-shadow: none;
                        overflow: visible;
                        -webkit-border-radius: 0;
                        -o-border-radius: 0;
                        -ms-border-radius: 0;
                        -moz-border-radius: 0;
                        border-radius: 0;


                    }
                    .ctu-ultimate-style-' . $styleid . '-content{
                        width: 100%;
                        max-width:100%;
                        display: block;
                        overflow: visible;
                    }
                    .ctu-ulimate-style-' . $styleid . ' {
                        display: none;
                    }
                    .ctu-ultimate-style-heading-' . $styleid . '{
                        display: block;
                        -webkit-box-shadow: 0 0 5px ' . $styledata[53] . ';
                        -o-box-shadow: 0 0 5px ' . $styledata[53] . ';
                        -ms-box-shadow: 0 0 5px ' . $styledata[53] . ';
                        -moz-box-shadow: 0 0 5px ' . $styledata[53] . ';
                        box-shadow: 0 0 5px ' . $styledata[53] . ';
                        margin-bottom: 10px;
                    }
                    .ctu-ulitate-style-' . $styleid . '-tabs{
                        margin-bottom: 10px;
                        -webkit-box-shadow: 0 0 5px ' . $styledata[53] . ';
                        -o-box-shadow: 0 0 5px ' . $styledata[53] . ';
                        -ms-box-shadow: 0 0 5px ' . $styledata[53] . ';
                        -moz-box-shadow: 0 0 5px ' . $styledata[53] . ';
                        box-shadow: 0 0 5px ' . $styledata[53] . ';
                        border-left: none;
                    }
                }
                ' . $styledata[67] . ' ';
    }

    public function default_render() {
        $styledata = $this->style;
        $styleid = $this->ID;
        echo '<div class="ctu-ultimate-wrapper-' . $styleid . '">
             <div class="ctu-ulimate-style-' . $styleid . '">';
        $linkopening = '';
        if (!empty($styledata[71]) && $styledata[71] != 'new-tab') {
            $linkopening = ", '_self'";
        }
        foreach ($this->child as $value) {
            $titlefiles = explode('{}{}{}', $value['title']);
            if (!empty($titlefiles[1]) && $this->user != 'admin') {
                $jquery .= '$(".vc-tabs-li-' . $styleid . '-id-' . $value['id'] . '").click(function() {window.open("' . $titlefiles[1] . '" ' . $linkopening . ');});';
            }
            echo ' <div class="vc-tabs-li vc-tabs-li-' . $styleid . '-id-' . $value['id'] . '" ref="#ctu-ulitate-style-' . $styleid . '-id-' . $value['id'] . '">
                                ' . $this->special_charecter($titlefiles[0]) . ' 
                                 <div class="ctu-absolute"></div>
                             </div>';
        }
        echo '</div>
                    <div class="ctu-ultimate-style-' . $styleid . '-content ">';
        foreach ($this->child as $value) {
            $titlefiles = explode('{}{}{}', $value['title']);
            echo ' <div class="ctu-ultimate-style-heading-' . $styleid . ' vc-tabs-li-' . $styleid . '-id-' . $value['id'] . '" ref="#ctu-ulitate-style-' . $styleid . '-id-' . $value['id'] . '"> 
                             ' . $this->special_charecter($titlefiles[0]) . '
                        </div>
                        <div class="ctu-ulitate-style-' . $styleid . '-tabs ' . ($this->user == 'admin' ? 'oxi-addons-admin-edit-list' : '') . '" id="ctu-ulitate-style-' . $styleid . '-id-' . $value['id'] . '">
                        <div class="ctu-content-span"></div>
                            ' . $this->special_charecter($value['files']) . '
                            ' . $this->admin_edit_panel($value['id']) . '
                        </div>';
        }
        echo '</div></div>';
    }

}
