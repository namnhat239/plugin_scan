<?php

namespace OXI_TABS_PLUGINS\Render\Old_Views;

/**
 * Description of Old Style
 *
 * @author biplob018
 */
use OXI_TABS_PLUGINS\Render\Old_Render;

class Style1 extends Old_Render {

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
        $this->JQUERY .= ' 
                $(".ctu-ulimate-style-' . $styleid . ' .vc-tabs-li' . $initialopen . '").addClass("active");
                $(".ctu-ultimate-style-heading-' . $styleid . '' . $initialopen . '").addClass("active");
                $(".ctu-ulitate-style-' . $styleid . '-tabs' . $initialopen . '").' . $animationin . '("slow");
                    
                $(".ctu-ulimate-style-' . $styleid . ' .vc-tabs-li").click(function () {
                    if ($(this).hasClass("active")) {
                        return false;
                    } else {
                        $(".ctu-ulimate-style-' . $styleid . ' .vc-tabs-li").removeClass("active");
                        $(this).toggleClass("active");
                        $(".ctu-ulitate-style-' . $styleid . '-tabs").' . $animationout . '();
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
                            overflow:hidden;
                            list-style: none;
                            margin-top: 10px;
                            text-align: center;            
                            -webkit-box-pack: ' . $styledata[15] . ';
                            -ms-flex-pack: ' . $styledata[15] . ';
                            -o-flex-pack: ' . $styledata[15] . ';
                            -moz-flex-pack: ' . $styledata[15] . ';
                            justify-content: ' . $styledata[15] . ' ;
                            border-top: ' . $styledata[53] . 'px solid ' . $styledata[9] . ';
                            margin-bottom: ' . $styledata[55] . 'px;
                            background-color: ' . $styledata[5] . ';
                            -webkit-border-radius: ' . $styledata[21] . 'px ' . $styledata[21] . 'px 0 0;
                            -ms-border-radius: ' . $styledata[21] . 'px ' . $styledata[21] . 'px 0 0;
                            -o-border-radius: ' . $styledata[21] . 'px ' . $styledata[21] . 'px 0 0;
                            -moz-border-radius: ' . $styledata[21] . 'px ' . $styledata[21] . 'px 0 0;
                            border-radius: ' . $styledata[21] . 'px ' . $styledata[21] . 'px 0 0;
                            -webkit-box-shadow: ' . $styledata[57] . 'px ' . $styledata[59] . 'px ' . $styledata[23] . 'px ' . $styledata[61] . 'px ' . $styledata[25] . ';
                            -ms-box-shadow: ' . $styledata[57] . 'px ' . $styledata[59] . 'px ' . $styledata[23] . 'px ' . $styledata[61] . 'px ' . $styledata[25] . '; 
                            -o-box-shadow: ' . $styledata[57] . 'px ' . $styledata[59] . 'px ' . $styledata[23] . 'px ' . $styledata[61] . 'px ' . $styledata[25] . '; 
                            -moz-box-shadow: ' . $styledata[57] . 'px ' . $styledata[59] . 'px ' . $styledata[23] . 'px ' . $styledata[61] . 'px ' . $styledata[25] . '; 
                            box-shadow: ' . $styledata[57] . 'px ' . $styledata[59] . 'px ' . $styledata[23] . 'px ' . $styledata[61] . 'px ' . $styledata[25] . '; 
                        }
                        .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li{
                            cursor: pointer;
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
                            padding: ' . $styledata[19] . 'px 10px;
                            margin: 0;
                            font-size: ' . $styledata[1] . 'px;
                            width: ' . $styledata[17] . 'px;
                            float: left;
                            font-family: ' . $this->font_familly($styledata[11]) . ';
                            color: ' . $styledata[3] . ';
                            border-right: 1px solid  ' . $styledata[9] . ';
                            font-weight:  ' . $styledata[13] . ';   
                        }
                        .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li:last-child{
                            border-right: none;
                        }
                        .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li.active{
                            color: ' . $styledata[7] . ';
                            position: relative;
                            -webkit-transition:  all 0.5s linear;
                            -ms-transition:  all 0.5s linear;
                            -o-transition:  all 0.5s linear;
                            -moz-transition:  all 0.5s linear;
                            transition:  all 0.5s linear;
                            background-color: ' . $styledata[9] . ';
                        }

                        .ctu-ultimate-style-' . $styleid . '-content{
                            width: 100%;
                            float: left;
                        }
                        .ctu-ultimate-style-heading-' . $styleid . '{
                            cursor: pointer;
                            -webkit-border-radius: ' . $styledata[21] . 'px;
                            -ms-border-radius: ' . $styledata[21] . 'px;
                            -o-border-radius: ' . $styledata[21] . 'px;
                            -moz-border-radius: ' . $styledata[21] . 'px;
                            border-radius: ' . $styledata[21] . 'px;
                            padding: ' . $styledata[19] . 'px 5px;
                            text-align: center;
                            font-size: ' . $styledata[1] . 'px;
                            width: 100%;
                            display: inline-block;
                            margin-bottom: 3px;
                            color: ' . $styledata[3] . ';
                            background-color: ' . $styledata[5] . ';
                            font-family: ' . $this->font_familly($styledata[11]) . ';
                            font-weight:  ' . $styledata[13] . ';
                            display: none;
                        }
                        .ctu-ultimate-style-heading-' . $styleid . '.active{
                            color: ' . $styledata[7] . ';
                            -webkit-transition:  all 0.5s linear;
                            -ms-transition:  all 0.5s linear;
                            -o-transition:  all 0.5s linear;
                            -moz-transition:  all 0.5s linear;
                            transition:  all 0.5s linear;
                            background-color: ' . $styledata[9] . ';
                        }
                        .ctu-ulitate-style-' . $styleid . '-tabs{
                            width: 100%;
                            float:left;
                            background-color:  ' . $styledata[31] . ' ;    
                            display: none;
                            -webkit-box-shadow: ' . $styledata[63] . 'px ' . $styledata[65] . 'px ' . $styledata[49] . 'px ' . $styledata[67] . 'px ' . $styledata[51] . ';
                            -ms-box-shadow: ' . $styledata[63] . 'px ' . $styledata[65] . 'px ' . $styledata[49] . 'px ' . $styledata[67] . 'px ' . $styledata[51] . ';
                            -o-box-shadow: ' . $styledata[63] . 'px ' . $styledata[65] . 'px ' . $styledata[49] . 'px ' . $styledata[67] . 'px ' . $styledata[51] . ';
                            -moz-box-shadow: ' . $styledata[63] . 'px ' . $styledata[65] . 'px ' . $styledata[49] . 'px ' . $styledata[67] . 'px ' . $styledata[51] . ';
                            box-shadow: ' . $styledata[63] . 'px ' . $styledata[65] . 'px ' . $styledata[49] . 'px ' . $styledata[67] . 'px ' . $styledata[51] . ';
                            margin-bottom:10px;
                            padding: ' . $styledata[33] . 'px ' . $styledata[35] . 'px ' . $styledata[37] . 'px ' . $styledata[39] . 'px;
                        }
                        .ctu-ulitate-style-' . $styleid . '-tabs p{          
                            font-size: ' . $styledata[27] . 'px;
                            color:  ' . $styledata[29] . ';
                            line-height: ' . $styledata[41] . ';
                            text-align: ' . $styledata[47] . ';           
                            font-family: ' . $this->font_familly($styledata[43]) . ';
                            font-weight: ' . $styledata[45] . ';
                            margin: 0;
                        }

                        @media only screen and (max-width: 900px) {
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
        $linkopening = '';
        if (!empty($styledata[75]) && $styledata[75] != 'new-tab') {
            $linkopening = ", '_self'";
        }
        echo '<div class="ctu-ultimate-wrapper ctu-ultimate-wrapper-' . $styleid . '"><div class="ctu-ulimate-style-' . $styleid . '">';

        foreach ($this->child as $value) {
            $titlefiles = explode('{}{}{}', $value['title']);
            if (!empty($titlefiles[1])) {
                $this->JQUERY .= '$(".vc-tabs-li-' . $styleid . '-id-' . $value['id'] . '").click(function() {window.open("' . $titlefiles[1] . '" ' . $linkopening . ');});';
            }
            echo '<div class="vc-tabs-li vc-tabs-li-' . $styleid . '-id-' . $value['id'] . '" ref="#ctu-ulitate-style-' . $styleid . '-id-' . $value['id'] . '">
                            ' . $this->special_charecter($titlefiles[0]) . '';
            echo '</div>';
        }
        echo ' </div> <div class="ctu-ultimate-style-' . $styleid . '-content">';

        foreach ($this->child as $value) {
            $titlefiles = explode('{}{}{}', $value['title']);
            echo '  <div class="ctu-ultimate-style-heading-' . $styleid . ' vc-tabs-li-' . $styleid . '-id-' . $value['id'] . '" ref="#ctu-ulitate-style-' . $styleid . '-id-' . $value['id'] . '"> 
                              ' . $this->special_charecter($titlefiles[0]) . '
                    </div>
                    <div class="ctu-ulitate-style-' . $styleid . '-tabs  ' . ($this->user == 'admin' ? 'oxi-addons-admin-edit-list' : '') . ' " id="ctu-ulitate-style-' . $styleid . '-id-' . $value['id'] . '">
                       ' . $this->special_charecter($value['files']) . '
                       ' . $this->admin_edit_panel($value['id']) . '     
            </div>';
        }
        echo ' </div> </div>';
    }

}
