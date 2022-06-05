<?php

namespace OXI_TABS_PLUGINS\Render\Old_Views;

/**
 * Description of Old Style
 *
 * @author biplob018
 */
use OXI_TABS_PLUGINS\Render\Old_Render;

class Style12 extends Old_Render {

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
        $this->CSS .= '.ctu-ultimate-wrapper-' . $styleid . '{
            width: 100%;
            float: left;
        }
        .ctu-ulimate-style-' . $styleid . '{
            margin: 0 0 0 0;
            width: calc(100% - ' . $styledata[39] . '%);
            float: left;
            list-style: none;
            margin-bottom: 0;
        }

        .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li{
            width: calc(100% - 10px);
            position: relative;
            list-style: none;
            cursor: pointer;
            max-width: ' . $styledata[15] . 'px;
            display: block;
            margin-left:0;
            margin-bottom: ' . $styledata[19] . 'px;                
            padding: ' . $styledata[17] . 'px 15px;
            text-align: left;
            text-align: ' . $styledata[77] . ';
            color: ' . $styledata[3] . ';
            font-size: ' . $styledata[1] . 'px;
            font-family:    ' . $this->font_familly($styledata[11]) . ';
            font-weight: ' . $styledata[13] . ';
            background-color: ' . $styledata[5] . ';
            line-height: 100%;
            font-style: ' . $styledata[59] . ';
            -webkit-box-shadow: ' . $styledata[67] . 'px ' . $styledata[69] . 'px ' . $styledata[21] . 'px ' . $styledata[71] . 'px ' . $styledata[23] . ';
            -o-box-shadow:  ' . $styledata[67] . 'px ' . $styledata[69] . 'px ' . $styledata[21] . 'px ' . $styledata[71] . 'px ' . $styledata[23] . ';
            -ms-box-shadow:  ' . $styledata[67] . 'px ' . $styledata[69] . 'px ' . $styledata[21] . 'px ' . $styledata[71] . 'px ' . $styledata[23] . ';
            -moz-box-shadow: ' . $styledata[67] . 'px ' . $styledata[69] . 'px ' . $styledata[21] . 'px ' . $styledata[71] . 'px ' . $styledata[23] . ';
            box-shadow:   ' . $styledata[67] . 'px ' . $styledata[69] . 'px ' . $styledata[21] . 'px ' . $styledata[71] . 'px ' . $styledata[23] . ';
        }
        .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li.active{
            color: ' . $styledata[7] . ';
            background-color:  ' . $styledata[9] . ';
        }
        .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li.active .ctu-absolute{
            position: absolute;
            top: ' . ( ($styledata[17] * 2 + $styledata[1] - 20) / 2) . 'px;
            right: -10px;
            width: 0;
            height: 0;
            border-top: 10px solid transparent;
            border-left: 10px solid ' . $styledata[9] . ';
            border-bottom: 10px solid transparent;

        }

        .ctu-ulimate-style-' . $styleid . '.ctu-right{
            float: right;
        }
        .ctu-ulimate-style-' . $styleid . '.ctu-right  .vc-tabs-li{
            float: right;
        }
        .ctu-ulimate-style-' . $styleid . '.ctu-right .vc-tabs-li.active .ctu-absolute{
            position: absolute;
            top: ' . (($styledata[17] * 2 + $styledata[1] - 20) / 2) . 'px;
            left: -10px;
            right: auto;
            width: 0;
            height: 0;
            border-left: none;
            border-top: 10px solid transparent;
            border-right: 10px solid ' . $styledata[9] . ';
            border-bottom: 10px solid transparent;
        }
        .ctu-ultimate-style-' . $styleid . '-content{
            width: ' . $styledata[39] . '%;
            float: left;
        }
        .ctu-ultimate-style-heading-' . $styleid . '{
            width: 100%;
            float: left;
            cursor: pointer;
            display: none;
            line-height: 100%;
            color: ' . $styledata[3] . ';
            background-color: ' . $styledata[5] . ';
            font-size: ' . $styledata[1] . 'px;
            padding: ' . $styledata[17] . 'px 15px;
            font-weight: ' . $styledata[13] . ';
            font-style: ' . $styledata[59] . ';
            font-family:  ' . $this->font_familly($styledata[11]) . ';
            -webkit-box-shadow:    ' . $styledata[67] . 'px ' . $styledata[69] . 'px ' . $styledata[21] . 'px ' . $styledata[71] . 'px ' . $styledata[23] . ';
            -o-box-shadow:   ' . $styledata[67] . 'px ' . $styledata[69] . 'px ' . $styledata[21] . 'px ' . $styledata[71] . 'px ' . $styledata[23] . ';
            -ms-box-shadow:   ' . $styledata[67] . 'px ' . $styledata[69] . 'px ' . $styledata[21] . 'px ' . $styledata[71] . 'px ' . $styledata[23] . ';
            -moz-box-shadow:   ' . $styledata[67] . 'px ' . $styledata[69] . 'px ' . $styledata[21] . 'px ' . $styledata[71] . 'px ' . $styledata[23] . ';
            box-shadow:   ' . $styledata[67] . 'px ' . $styledata[69] . 'px ' . $styledata[21] . 'px ' . $styledata[71] . 'px ' . $styledata[23] . ';
            border-radius: 5px;
        }
        .ctu-ultimate-style-heading-' . $styleid . '.active{
            color: ' . $styledata[7] . ';
            background-color: ' . $styledata[9] . ';
            border-radius: 5px 5px 0 0;
        }
        .ctu-ulitate-style-' . $styleid . '-tabs{
            display: none;
            float: left;
            width: 100%;            
            border: ' . $styledata[49] . 'px solid;
            border-color: ' . $styledata[51] . ';            
            border-radius: ' . $styledata[57] . 'px;
            background-color: ' . $styledata[29] . ';
            font-weight: ' . $styledata[45] . ';            
            text-align: ' . $styledata[47] . ';
            padding: ' . $styledata[31] . 'px ' . $styledata[33] . 'px ' . $styledata[35] . 'px ' . $styledata[37] . 'px ;
            -webkit-box-shadow: ' . $styledata[61] . 'px ' . $styledata[63] . 'px ' . $styledata[21] . 'px ' . $styledata[65] . 'px ' . $styledata[55] . ';
            -o-box-shadow:  ' . $styledata[61] . 'px ' . $styledata[63] . 'px ' . $styledata[21] . 'px ' . $styledata[65] . 'px ' . $styledata[55] . ';
            -ms-box-shadow: ' . $styledata[61] . 'px ' . $styledata[63] . 'px ' . $styledata[21] . 'px ' . $styledata[65] . 'px ' . $styledata[55] . ';
            -moz-box-shadow:   ' . $styledata[61] . 'px ' . $styledata[63] . 'px ' . $styledata[21] . 'px ' . $styledata[65] . 'px ' . $styledata[55] . ';
            box-shadow:   ' . $styledata[61] . 'px ' . $styledata[63] . 'px ' . $styledata[21] . 'px ' . $styledata[65] . 'px ' . $styledata[55] . ';
        }
        .ctu-ulitate-style-' . $styleid . '-tabs p{
            font-size: ' . $styledata[25] . 'px;
            color: ' . $styledata[27] . ';
            line-height: ' . $styledata[41] . ';
            font-family:  ' . $this->font_familly($styledata[43]) . ';
            margin-top:0;
            margin-bottom: 0;
        }
        @media only screen and (max-width: 900px) {
            .ctu-ultimate-style-' . $styleid . '-content{
                width: 100%;
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
                border-radius: 0 0 5px 5px;
                -webkit-box-shadow:   0 0 3px ' . $styledata[55] . ';
                -o-box-shadow:   0 0 3px ' . $styledata[55] . ';
                -ms-box-shadow:   0 0 3px ' . $styledata[55] . ';
                -moz-box-shadow:   0 0 3px ' . $styledata[55] . ';
                box-shadow:   0 0 3px ' . $styledata[55] . ';
            }
        }
        ' . $styledata[73] . '';
    }

    public function default_render() {
        $styledata = $this->style;
        $styleid = $this->ID;
        echo '<div class="ctu-ultimate-wrapper-' . $styleid . '">
        <div class="ctu-ulimate-style-' . $styleid . ' ' . $styledata[75] . '">';
        $linkopening = '';
        if (!empty($styledata[83]) && $styledata[83] != 'new-tab') {
            $linkopening = ", '_self'";
        }
        foreach ($this->child as $value) {
            $titlefiles = explode('{}{}{}', $value['title']);
            if (!empty($titlefiles[1]) && $this->user != 'admin') {
                $this->JQUERY .= '$(".vc-tabs-li-' . $styleid . '-id-' . $value['id'] . '").click(function() {window.open("' . $titlefiles[1] . '" ' . $linkopening . ');});';
            }
            echo ' <div class="vc-tabs-li vc-tabs-li-' . $styleid . '-id-' . $value['id'] . '" ref="#ctu-ulitate-style-' . $styleid . '-id-' . $value['id'] . '" class="">
                    ' . $this->special_charecter($titlefiles[0]) . '
                   <div class="ctu-absolute"></div>
               </div>';
        }
        echo '</div>';
        echo '<div class="ctu-ultimate-style-' . $styleid . '-content">';
        foreach ($this->child as $value) {
            $titlefiles = explode('{}{}{}', $value['title']);
            echo '  <div class="ctu-ultimate-style-heading-' . $styleid . ' vc-tabs-li-' . $styleid . '-id-' . $value['id'] . '" ref="#ctu-ulitate-style-' . $styleid . '-id-' . $value['id'] . '"> 
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
