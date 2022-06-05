<?php

namespace OXI_TABS_PLUGINS\Render\Old_Views;

/**
 * Description of Old Style
 *
 * @author biplob018
 */
use OXI_TABS_PLUGINS\Render\Old_Render;

class Style6 extends Old_Render {

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
        $this->CSS .= '.ctu-ultimate-wrapper-' . $styleid . '{
                            width: 100%;
                            float: left;
                            display: flex;
                            background-color: ' . $styledata[23] . ';
                            -webkit-box-shadow:' . $styledata[47] . 'px ' . $styledata[49] . 'px ' . $styledata[43] . 'px ' . $styledata[51] . 'px ' . $styledata[45] . '; 
                            -o-box-shadow: ' . $styledata[47] . 'px ' . $styledata[49] . 'px ' . $styledata[43] . 'px ' . $styledata[51] . 'px ' . $styledata[45] . '; 
                            -ms-box-shadow: ' . $styledata[47] . 'px ' . $styledata[49] . 'px ' . $styledata[43] . 'px ' . $styledata[51] . 'px ' . $styledata[45] . '; 
                            -moz-box-shadow: ' . $styledata[47] . 'px ' . $styledata[49] . 'px ' . $styledata[43] . 'px ' . $styledata[51] . 'px ' . $styledata[45] . '; 
                            box-shadow: ' . $styledata[47] . 'px ' . $styledata[49] . 'px ' . $styledata[43] . 'px ' . $styledata[51] . 'px ' . $styledata[45] . '; 
                        }
                        .ctu-ulimate-style-' . $styleid . '{
                            margin: 0 0 0 0;
                            float: left;
                            list-style: none;
                            width: ' . $styledata[33] . 'px;
                            margin-bottom: 0;
                        }
                        .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li{
                            width: 95%;
                            margin-bottom: 0;
                            -webkit-transition: all 0.3s linear;
                            -o-transition: all 0.3s linear;
                            -ms-transition: all 0.3s linear;
                            -moz-transition: all 0.3s linear;
                            transition: all 0.3s linear;
                            cursor: pointer;
                            position: relative;
                            font-style: ' . $styledata[53] . ';
                            padding: ' . $styledata[15] . 'px ' . $styledata[17] . 'px;
                            margin-right: 5%;
                            font-size: ' . $styledata[1] . 'px;
                            color: ' . $styledata[3] . ';
                            background-color: ' . $styledata[5] . ';
                            font-weight: ' . $styledata[13] . ';
                            font-family:  ' . $this->font_familly($styledata[11]) . ';
                            -webkit-box-shadow: inset 3px -36px 52px -24px rgba(255, 255, 255, 0.28);
                            -moz-box-shadow: inset 3px -36px 52px -24px rgba(255, 255, 255, 0.28);
                            -o-box-shadow: inset 3px -36px 52px -24px rgba(255, 255, 255, 0.28);
                            -ms-box-shadow: inset 3px -36px 52px -24px rgba(255, 255, 255, 0.28);
                            box-shadow: inset 3px -36px 52px -24px rgba(255, 255, 255, 0.28);
                        }
                        .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li.active{
                            color: ' . $styledata[7] . ';
                            width: 100%;
                            margin-right: 0;
                            background-color:  ' . $styledata[9] . ';
                        }
                        .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li.active .ctu-absolute{
                            position: absolute;
                            left: 0;
                            top: 15%;
                            bottom: 15%;
                            width: 3px;
                            background-color:  ' . $styledata[7] . ';
                        }
                        .ctu-ultimate-style-' . $styleid . '-content{
                            width: calc(100% - ' . $styledata[33] . 'px);
                            float: left;
                        }
                        .ctu-ultimate-style-heading-' . $styleid . '{
                            width: 100%;
                            cursor: pointer;
                            display: none;
                            line-height: 100%;
                            background-color: ' . $styledata[5] . ';
                            font-size: ' . $styledata[1] . 'px;
                            padding: ' . $styledata[15] . 'px ' . $styledata[17] . 'px;
                            font-weight: ' . $styledata[13] . ';
                            font-family:  ' . $this->font_familly($styledata[11]) . ';
                        }
                        .ctu-ultimate-style-heading-' . $styleid . '.active{
                            color: ' . $styledata[7] . ';
                            background-color: ' . $styledata[9] . ';
                        }
                        .ctu-ulitate-style-' . $styleid . '-tabs{
                            display: -webkit-box;
                            display: -ms-flexbox;
                            display: -moz-flexbox;
                            display: -o-flexbox;
                            display: flex;
                            display: none;
                            text-align: ' . $styledata[41] . ';            
                            padding: ' . $styledata[25] . 'px ' . $styledata[27] . 'px ' . $styledata[29] . 'px ' . $styledata[31] . 'px;
                        }
                        .ctu-ulitate-style-' . $styleid . '-tabs p{
                            font-size: ' . $styledata[19] . 'px;
                            color: ' . $styledata[21] . ';
                            line-height: ' . $styledata[35] . ';
                            font-family:  ' . $this->font_familly($styledata[37]) . ';
                            font-weight: ' . $styledata[39] . ';
                            margin-bottom:0;
                            margin-top:0;
                        }
                        @media only screen and (max-width: 900px) {
                            .ctu-ultimate-wrapper-' . $styleid . '{
                                display: block;
                                -webkit-box-shadow: none;
                                -o-box-shadow: none;
                                -ms-box-shadow: none;
                                -moz-box-shadow: none;
                                box-shadow: none;
                                background-color: transparent;
                            }
                            .ctu-ultimate-style-' . $styleid . '-content{
                                width: 100%;
                                border-left: none;
                                display: block;
                                overflow:   visible;
                            }
                            .ctu-ulimate-style-' . $styleid . ' {
                                display: none;
                            }
                            .ctu-ultimate-style-heading-' . $styleid . '{
                                display: block;
                                -webkit-box-shadow:   0 0 5px ' . $styledata[45] . ';
                                -o-box-shadow:   0 0 5px ' . $styledata[45] . ';
                                -ms-box-shadow:   0 0 ' . $styledata[45] . ';
                                -moz-box-shadow:   0 0 ' . $styledata[45] . ';
                                box-shadow:   0 0 5px ' . $styledata[45] . ';
                                margin-bottom: 10px;
                            }
                            .ctu-ulitate-style-' . $styleid . '-tabs{
                                margin-bottom: 10px;
                                background-color: ' . $styledata[23] . ';
                                -webkit-box-shadow:   0 0 5px ' . $styledata[45] . ';
                                -o-box-shadow:   0 0 5px ' . $styledata[45] . ';
                                -ms-box-shadow:   0 0 5px ' . $styledata[45] . ';
                                -moz-box-shadow:   0 0 5px ' . $styledata[45] . ';
                                box-shadow:   0 0 5px ' . $styledata[45] . ';
                            }
                        }
                        ' . $styledata[55] . ' ';
    }

    public function default_render() {
        $styledata = $this->style;
        $styleid = $this->ID;
        echo '<div class="ctu-ultimate-wrapper-' . $styleid . '">
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
            echo '<div class="vc-tabs-li vc-tabs-li-' . $styleid . '-id-' . $value['id'] . '" ref="#ctu-ulitate-style-' . $styleid . '-id-' . $value['id'] . '" class="">
                                ' . $this->special_charecter($titlefiles[0]) . '
                                <div class="ctu-absolute"></div>
                            </div>';
        }
        echo ' </div><div class="ctu-ultimate-style-' . $styleid . '-content">';
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
