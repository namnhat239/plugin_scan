<?php

namespace OXI_TABS_PLUGINS\Render\Old_Views;

/**
 * Description of Old Style
 *
 * @author biplob018
 */
use OXI_TABS_PLUGINS\Render\Old_Render;

class Style3 extends Old_Render {

    public function inline_public_jquery() {
        $styledata = $this->style;
        $styleid = $this->ID;
        $oxi_fixed_header = get_option('oxi_addons_fixed_header_size');
        if (empty($styledata[73])) {
            $initialopen = ':first';
        } else if ($styledata[73] == 'none') {
            $initialopen = '';
        } else {
            $initialopen = $styledata[73];
        }
        if (empty($styledata[75])) {
            $animationin = 'slideDown';
            $animationout = 'slideUp';
        } else if ($styledata[75] == 'slide') {
            $animationin = 'slideDown';
            $animationout = 'slideUp';
        } else if ($styledata[75] == 'show') {
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
        $this->CSS .= '.ctu-ultimate-wrapper-' . $styleid . '{
                    width: 100%;
                    float: left;
                }
                .ctu-ulimate-style-' . $styleid . '{
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
                    -webkit-box-pack: ' . $styledata[19] . ';
                    -ms-flex-pack: ' . $styledata[19] . ';
                    -o-flex-pack: ' . $styledata[19] . ';
                    -moz-flex-pack: ' . $styledata[19] . ';
                    justify-content: ' . $styledata[19] . ';
                    overflow: hidden;
                    margin-bottom: 0;
                }
                .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li{
                    cursor: pointer;
                    float: left;
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
                    margin-bottom: 0;
                    -webkit-box-shadow: ' . $styledata[57] . 'px ' . $styledata[59] . 'px ' . $styledata[25] . 'px ' . $styledata[61] . 'px ' . $styledata[27] . ';
                    -o-box-shadow: ' . $styledata[57] . 'px ' . $styledata[59] . 'px ' . $styledata[25] . 'px ' . $styledata[61] . 'px ' . $styledata[27] . '; 
                    -ms-box-shadow: ' . $styledata[57] . 'px ' . $styledata[59] . 'px ' . $styledata[25] . 'px ' . $styledata[61] . 'px ' . $styledata[27] . '; 
                    -moz-box-shadow: ' . $styledata[57] . 'px ' . $styledata[59] . 'px ' . $styledata[25] . 'px ' . $styledata[61] . 'px ' . $styledata[27] . '; 
                    box-shadow: ' . $styledata[57] . 'px ' . $styledata[59] . 'px ' . $styledata[25] . 'px ' . $styledata[61] . 'px ' . $styledata[27] . '; 
                    text-align: center;
                    position: relative;
                    margin: 0 ' . $styledata[55] . 'px 0 0;
                    padding: ' . $styledata[23] . 'px 10px;
                    bottom: -5px;
                    font-size: ' . $styledata[1] . 'px;
                    line-height: 120%;
                    border-top: 5px solid ' . $styledata[7] . ';
                    font-style:  ' . $styledata[69] . ';
                    color: ' . $styledata[3] . ';
                    background-color: ' . $styledata[5] . ';
                    width: ' . $styledata[21] . 'px;
                    font-family:  ' . $this->font_familly($styledata[15]) . ';
                    font-weight: ' . $styledata[17] . ';
                }
                .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li:last-child{
                    margin-right: 0;
                }
                .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li.active{
                    position: relative;
                    -webkit-transition:  all 0.5s linear;
                    -o-transition:  all 0.5s linear;
                    -ms-transition:  all 0.5s linear;
                    -moz-transition:  all 0.5s linear;
                    transition:  all 0.5s linear;
                    color: ' . $styledata[9] . ';
                    background-color: ' . $styledata[11] . ';
                    border-color: ' . $styledata[13] . ';
                    bottom: 0;
                    z-index: 1;
                }
                .ctu-ultimate-style-' . $styleid . '-content{
                    width: 100%;
                    float: left;
                }
                .ctu-ultimate-style-heading-' . $styleid . '{
                    cursor: pointer;
                    display: none;
                    width: 100%;
                    font-weight: ' . $styledata[17] . ';
                    line-height: 120%;
                    padding: ' . $styledata[23] . 'px 10px;
                    text-align: center;
                    font-size: ' . $styledata[1] . 'px;
                    font-family:  ' . $this->font_familly($styledata[15]) . ';
                    background-color:  ' . $styledata[5] . ';
                    color: ' . $styledata[3] . ';
                }
                .ctu-ultimate-style-heading-' . $styleid . '.active{
                    -webkit-transition:  all 0.5s linear;
                    -o-transition:  all 0.5s linear;
                    -ms-transition:  all 0.5s linear;
                    -moz-transition:  all 0.5s linear;
                    transition:  all 0.5s linear;
                    color: ' . $styledata[9] . ';
                    background-color:  ' . $styledata[11] . ';
                }
                .ctu-ulitate-style-' . $styleid . '-tabs{
                    width: 100%;
                    display: none;
                    padding: ' . $styledata[35] . 'px ' . $styledata[37] . 'px ' . $styledata[39] . 'px ' . $styledata[41] . 'px;
                    font-size: ' . $styledata[29] . 'px;
                    color: ' . $styledata[31] . ';
                    line-height: ' . $styledata[43] . ';
                    text-align: ' . $styledata[49] . ';
                    background-color: ' . $styledata[33] . ';
                    font-family:  ' . $this->font_familly($styledata[45]) . ';
                    -webkit-box-shadow: ' . $styledata[63] . 'px ' . $styledata[65] . 'px ' . $styledata[53] . 'px ' . $styledata[67] . 'px ' . $styledata[51] . ';
                    -o-box-shadow: ' . $styledata[63] . 'px ' . $styledata[65] . 'px ' . $styledata[53] . 'px ' . $styledata[67] . 'px ' . $styledata[51] . '; 
                    -ms-box-shadow: ' . $styledata[63] . 'px ' . $styledata[65] . 'px ' . $styledata[53] . 'px ' . $styledata[67] . 'px ' . $styledata[51] . '; 
                    -moz-box-shadow: ' . $styledata[63] . 'px ' . $styledata[65] . 'px ' . $styledata[53] . 'px ' . $styledata[67] . 'px ' . $styledata[51] . '; 
                    box-shadow: ' . $styledata[63] . 'px ' . $styledata[65] . 'px ' . $styledata[53] . 'px ' . $styledata[67] . 'px ' . $styledata[51] . '; 
                    font-weight: ' . $styledata[47] . ';
                }
                .ctu-ulitate-style-' . $styleid . '-tabs p{
                    font-size: ' . $styledata[29] . 'px;
                    color: ' . $styledata[31] . ';
                    font-family:  ' . $this->font_familly($styledata[45]) . ';
                    font-weight: ' . $styledata[47] . ';
                    text-align: ' . $styledata[49] . ';
                    margin-bottom: 0;
                    margin-top: 0;
                }
                @media only screen and (max-width: 900px) {
                    .ctu-ulimate-style-' . $styleid . ' {
                        display: none;
                    }
                    .ctu-ultimate-style-heading-' . $styleid . '{
                        display: block;
                    }
                    .ctu-ultimate-style-' . $styleid . '-content{
                        margin-bottom: 10px;
                    }
                }
                ' . $styledata[71] . '';
    }

    public function default_render() {
        $styledata = $this->style;
        $styleid = $this->ID;
        $linkopening = '';
        echo '<div class="ctu-ultimate-wrapper-' . $styleid . '">
             <div class="ctu-ulimate-style-' . $styleid . '">';
        if (!empty($styledata[77]) && $styledata[77] != 'new-tab') {
            $linkopening = ", '_self'";
        }
        foreach ($this->child as $value) {
            $titlefiles = explode('{}{}{}', $value['title']);
            if (!empty($titlefiles[1]) && $this->user != 'admin') {
                $this->JQUERY .= '$(".vc-tabs-li-' . $styleid . '-id-' . $value['id'] . '").click(function() {window.open("' . $titlefiles[1] . '" ' . $linkopening . ');});';
            }
            echo '  <div class="vc-tabs-li vc-tabs-li-' . $styleid . '-id-' . $value['id'] . '" ref="#ctu-ulitate-style-' . $styleid . '-id-' . $value['id'] . '" class="">
                    ' . $this->special_charecter($titlefiles[0]) . '
                </div>';
        }
        echo ' </div>';
        foreach ($this->child as $value) {
            $titlefiles = explode('{}{}{}', $value['title']);
            echo '      <div class="ctu-ultimate-style-' . $styleid . '-content">
                        <div class="ctu-ultimate-style-heading-' . $styleid . ' vc-tabs-li-' . $styleid . '-id-' . $value['id'] . '" ref="#ctu-ulitate-style-' . $styleid . '-id-' . $value['id'] . '"> 
                            ' . $this->special_charecter($titlefiles[0]) . '

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
