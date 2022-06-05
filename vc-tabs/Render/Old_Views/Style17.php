<?php

namespace OXI_TABS_PLUGINS\Render\Old_Views;

/**
 * Description of Old Style
 *
 * @author biplob018
 */
use OXI_TABS_PLUGINS\Render\Old_Render;

class Style17 extends Old_Render {

    public function inline_public_jquery() {
        $styledata = $this->style;
        $styleid = $this->ID;
        $oxi_fixed_header = get_option('oxi_addons_fixed_header_size');

        if (empty($styledata[79])) {
            $initialopen = ':first';
        } else if ($styledata[79] == 'none') {
            $initialopen = '';
        } else {
            $initialopen = $styledata[79];
        }
        if (empty($styledata[81])) {
            $animationin = 'slideDown';
            $animationout = 'slideUp';
        } else if ($styledata[81] == 'slide') {
            $animationin = 'slideDown';
            $animationout = 'slideUp';
        } else if ($styledata[81] == 'show') {
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
            -webkit-box-pack: ' . $styledata[15] . ';
            -ms-flex-pack: ' . $styledata[15] . ';
            -o-flex-pack: ' . $styledata[15] . ';
            -moz-flex-pack: ' . $styledata[15] . ';
            justify-content: ' . $styledata[15] . ';
            margin-bottom: ' . $styledata[27] . 'px;
        }
        .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li{
            width: 100%;
            float: left;
            list-style: none;
            cursor: pointer;
            max-width: ' . $styledata[21] . 'px;
            display: block;
            margin-bottom: 0;
            padding: ' . $styledata[23] . 'px 10px;
            text-align: center;
            color: ' . $styledata[3] . ';
            background-color: ' . $styledata[5] . ';
            border: ' . $styledata[17] . 'px solid ;
            border-color: ' . $styledata[19] . ';
            font-size: ' . $styledata[1] . 'px;
            font-family:    ' . $this->font_familly($styledata[11]) . ';
            font-weight: ' . $styledata[13] . ';
            line-height: 100%;
            border-radius: ' . $styledata[29] . 'px;
            font-style: ' . $styledata[63] . ';
            margin-right: ' . $styledata[25] . 'px;
            -webkit-box-shadow:  ' . $styledata[71] . 'px ' . $styledata[73] . 'px ' . $styledata[31] . 'px ' . $styledata[75] . 'px ' . $styledata[33] . ';
            -o-box-shadow:  ' . $styledata[71] . 'px ' . $styledata[73] . 'px ' . $styledata[31] . 'px ' . $styledata[75] . 'px ' . $styledata[33] . ';
            -ms-box-shadow:  ' . $styledata[71] . 'px ' . $styledata[73] . 'px ' . $styledata[31] . 'px ' . $styledata[75] . 'px ' . $styledata[33] . ';
            -moz-box-shadow:  ' . $styledata[71] . 'px ' . $styledata[73] . 'px ' . $styledata[31] . 'px ' . $styledata[75] . 'px ' . $styledata[33] . ';
            box-shadow: ' . $styledata[71] . 'px ' . $styledata[73] . 'px ' . $styledata[31] . 'px ' . $styledata[75] . 'px ' . $styledata[33] . ';
        }
        .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li:last-child{
            margin-right: 0;
        }
        .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li.active{
            color: ' . $styledata[7] . ';
            background-color: ' . $styledata[9] . ';
            border-color: ' . $styledata[9] . ';
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
            color: ' . $styledata[3] . ';
            background-color: ' . $styledata[5] . ';
            border: ' . $styledata[17] . 'px solid ' . $styledata[19] . ';
            font-size: ' . $styledata[1] . 'px;
            padding: ' . $styledata[23] . 'px 10px;
            font-weight: ' . $styledata[13] . ';
            font-family:  ' . $this->font_familly($styledata[11]) . ';
            margin-bottom: ' . $styledata[27] . 'px;
            font-style: ' . $styledata[63] . ';
            -webkit-box-shadow: ' . $styledata[71] . 'px ' . $styledata[73] . 'px ' . $styledata[31] . 'px ' . $styledata[75] . 'px ' . $styledata[33] . ';
            -o-box-shadow: ' . $styledata[71] . 'px ' . $styledata[73] . 'px ' . $styledata[31] . 'px ' . $styledata[75] . 'px ' . $styledata[33] . ';
            -ms-box-shadow: ' . $styledata[71] . 'px ' . $styledata[73] . 'px ' . $styledata[31] . 'px ' . $styledata[75] . 'px ' . $styledata[33] . ';
            -moz-box-shadow: ' . $styledata[71] . 'px ' . $styledata[73] . 'px ' . $styledata[31] . 'px ' . $styledata[75] . 'px ' . $styledata[33] . ';
            box-shadow: ' . $styledata[71] . 'px ' . $styledata[73] . 'px ' . $styledata[31] . 'px ' . $styledata[75] . 'px ' . $styledata[33] . ';
            border-radius: ' . $styledata[29] . 'px;
        }
        .ctu-ultimate-style-heading-' . $styleid . '.active{
            color: ' . $styledata[7] . ';
            background-color: ' . $styledata[9] . ';
            border-color: ' . $styledata[9] . ';   
            border-radius: 5px 5px 0 0;
        }
        .ctu-ulitate-style-' . $styleid . '-tabs{
            display: none;
            width:100%;
            float:left;
            background-color: ' . $styledata[39] . ';
            text-align: ' . $styledata[55] . ';
            padding: ' . $styledata[41] . 'px ' . $styledata[43] . 'px ' . $styledata[45] . 'px ' . $styledata[47] . 'px ;
            border-radius: ' . $styledata[57] . 'px;
            -webkit-box-shadow: ' . $styledata[65] . 'px ' . $styledata[67] . 'px ' . $styledata[59] . 'px ' . $styledata[59] . 'px ' . $styledata[61] . ';
            -o-box-shadow: ' . $styledata[65] . 'px ' . $styledata[67] . 'px ' . $styledata[59] . 'px ' . $styledata[59] . 'px ' . $styledata[61] . ';
            -ms-box-shadow: ' . $styledata[65] . 'px ' . $styledata[67] . 'px ' . $styledata[59] . 'px ' . $styledata[59] . 'px ' . $styledata[61] . ';
            -moz-box-shadow:' . $styledata[65] . 'px ' . $styledata[67] . 'px ' . $styledata[59] . 'px ' . $styledata[59] . 'px ' . $styledata[61] . ';
            box-shadow: ' . $styledata[65] . 'px ' . $styledata[67] . 'px ' . $styledata[59] . 'px ' . $styledata[59] . 'px ' . $styledata[61] . ';
        } 
        .ctu-ulitate-style-' . $styleid . '-tabs p{
            font-size: ' . $styledata[35] . 'px;
            color: ' . $styledata[37] . ';
            font-family:  ' . $this->font_familly($styledata[51]) . ';
            font-weight: ' . $styledata[53] . ';
            line-height: ' . $styledata[49] . ';
            margin-top:0;
            margin-bottom:0;
        }
        @media only screen and (max-width: 900px) {
            .ctu-ultimate-wrapper-' . $styleid . '{
                display: block;
            }
            .ctu-ultimate-style-' . $styleid . '-content{
                width: 100%;
                display: block;
            }
            .ctu-ulimate-style-' . $styleid . ' {
                display: none;
            }
            .ctu-ultimate-style-heading-' . $styleid . '{
                display: block;
            }
            .ctu-ulitate-style-' . $styleid . '-tabs{
                margin-bottom: 10px;
                border-radius: 0 0 5px 5px;
            }
        }
        ' . $styledata[77] . '';
    }

    public function default_render() {
        $styledata = $this->style;
        $styleid = $this->ID;
        echo '<div class="ctu-ultimate-wrapper-' . $styleid . '">
            <div class="ctu-ulimate-style-' . $styleid . '">';

        $linkopening = '';
        if (!empty($styledata[81]) && $styledata[81] != 'new-tab') {
            $linkopening = ", '_self'";
        }
        foreach ($this->child as $value) {
            $titlefiles = explode('{}{}{}', $value['title']);
            if (!empty($titlefiles[1]) && $this->user != 'admin') {
                $this->JQUERY .= '$(".vc-tabs-li-' . $styleid . '-id-' . $value['id'] . '").click(function() {window.open("' . $titlefiles[1] . '" ' . $linkopening . ');});';
            }
            echo '  <div class="vc-tabs-li vc-tabs-li-' . $styleid . '-id-' . $value['id'] . '" ref="#ctu-ulitate-style-' . $styleid . '-id-' . $value['id'] . '">
                                    ' . $this->special_charecter($titlefiles[0]) . ' 
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
