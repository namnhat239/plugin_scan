<?php

namespace OXI_TABS_PLUGINS\Render\Old_Views;

/**
 * Description of Old Style
 *
 * @author biplob018
 */
use OXI_TABS_PLUGINS\Render\Old_Render;

class Style18 extends Old_Render {

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
        $this->JQUERY .= ' $(".ctu-ulimate-style-' . $styleid . ' .vc-tabs-li' . $initialopen . '").addClass("active");
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
            text-align: center;
            background-color: ' . $styledata[33] . ';
            -webkit-box-shadow:   ' . $styledata[59] . 'px ' . $styledata[61] . 'px ' . $styledata[53] . 'px  ' . $styledata[63] . 'px ' . $styledata[55] . ';
            -o-box-shadow:   ' . $styledata[59] . 'px ' . $styledata[61] . 'px ' . $styledata[53] . 'px  ' . $styledata[63] . 'px ' . $styledata[55] . ';
            -ms-box-shadow:  ' . $styledata[59] . 'px ' . $styledata[61] . 'px ' . $styledata[53] . 'px  ' . $styledata[63] . 'px ' . $styledata[55] . ';
            -moz-box-shadow:  ' . $styledata[59] . 'px ' . $styledata[61] . 'px ' . $styledata[53] . 'px  ' . $styledata[63] . 'px ' . $styledata[55] . ';
            box-shadow:   ' . $styledata[59] . 'px ' . $styledata[61] . 'px ' . $styledata[53] . 'px  ' . $styledata[63] . 'px ' . $styledata[55] . ';
            border-radius: ' . $styledata[51] . 'px;
        }
        .ctu-ulimate-style-' . $styleid . '{
            display: inline-block; 
            margin: 0 0 0 0;
            list-style: none;
            text-align: center;
            overflow: hidden;
            margin-bottom: 0;
            margin-top: ' . $styledata[21] . 'px;
            background-color: ' . $styledata[5] . ';
            border-radius: ' . $styledata[23] . 'px;
            padding: ' . $styledata[19] . 'px;
            -webkit-box-shadow: ' . $styledata[65] . 'px ' . $styledata[67] . 'px ' . $styledata[25] . 'px ' . $styledata[69] . 'px ' . $styledata[27] . ';
            -o-box-shadow: ' . $styledata[65] . 'px ' . $styledata[67] . 'px ' . $styledata[25] . 'px ' . $styledata[69] . 'px ' . $styledata[27] . ';
            -ms-box-shadow: ' . $styledata[65] . 'px ' . $styledata[67] . 'px ' . $styledata[25] . 'px ' . $styledata[69] . 'px ' . $styledata[27] . ';
            -moz-box-shadow: ' . $styledata[65] . 'px ' . $styledata[67] . 'px ' . $styledata[25] . 'px ' . $styledata[69] . 'px ' . $styledata[27] . ';
            box-shadow: ' . $styledata[65] . 'px ' . $styledata[67] . 'px ' . $styledata[25] . 'px ' . $styledata[69] . 'px ' . $styledata[27] . ';
        }
        .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li{
            list-style: none;
            cursor: pointer;
            width: ' . $styledata[15] . 'px;
            display: inline-block;
            margin-bottom: 0;
            padding: ' . $styledata[17] . 'px 10px;
            text-align: center;
            color: ' . $styledata[3] . ';
            font-size: ' . $styledata[1] . 'px;
            font-style: ' . $styledata[57] . ';
            font-family:    ' . $this->font_familly($styledata[11]) . ';
            font-weight: ' . $styledata[13] . ';
            line-height: 100%;
            border-radius: ' . $styledata[23] . 'px;

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
            float: left;
            cursor: pointer;
            display: none;
            line-height: 100%;
            color: ' . $styledata[3] . ';
            background-color:' . $styledata[5] . ';
            font-size: ' . $styledata[1] . 'px;
            padding: ' . $styledata[17] . 'px 10px;
            font-weight: ' . $styledata[13] . ';            
            font-style: ' . $styledata[57] . ';
            font-family:  ' . $this->font_familly($styledata[11]) . ';
            -webkit-box-shadow:  ' . $styledata[65] . 'px ' . $styledata[67] . 'px ' . $styledata[25] . 'px ' . $styledata[69] . 'px ' . $styledata[27] . ';
            -o-box-shadow:  ' . $styledata[65] . 'px ' . $styledata[67] . 'px ' . $styledata[25] . 'px ' . $styledata[69] . 'px ' . $styledata[27] . ';
            -ms-box-shadow:  ' . $styledata[65] . 'px ' . $styledata[67] . 'px ' . $styledata[25] . 'px ' . $styledata[69] . 'px ' . $styledata[27] . ';
            -moz-box-shadow: ' . $styledata[65] . 'px ' . $styledata[67] . 'px ' . $styledata[25] . 'px ' . $styledata[69] . 'px ' . $styledata[27] . ';
            box-shadow: ' . $styledata[65] . 'px ' . $styledata[67] . 'px ' . $styledata[25] . 'px ' . $styledata[69] . 'px ' . $styledata[27] . ';
            margin-bottom: 10px;
            border-radius: 5px;
        }
        .ctu-ultimate-style-heading-' . $styleid . '.active{
            color: ' . $styledata[7] . ';
            background-color:' . $styledata[9] . ';
            border-radius: 5px 5px 0 0;
        }
        .ctu-ulitate-style-' . $styleid . '-tabs{
            width: 100%;
            float: left;
            display: none;            
            text-align: ' . $styledata[49] . ';
            padding: ' . $styledata[35] . 'px ' . $styledata[37] . 'px ' . $styledata[39] . 'px ' . $styledata[41] . 'px ;
        }
        .ctu-ulitate-style-' . $styleid . '-tabs p{
            color: ' . $styledata[31] . ';
            font-family: ' . $this->font_familly($styledata[45]) . ';
            font-weight: ' . $styledata[47] . ';
            font-size: ' . $styledata[29] . 'px;
            line-height: ' . $styledata[43] . ';
            margin-bottom:0;
            margin-top:0;
        }
        @media only screen and (max-width: 900px) {
            .ctu-ultimate-wrapper-' . $styleid . '{
                display: block;
                background-color: transparent;
                -webkit-box-shadow: none;
                -o-box-shadow: none;
                -ms-box-shadow: none;
                -moz-box-shadow: none;
                box-shadow: none;
                border-radius: 0;
            }
            .ctu-ultimate-style-' . $styleid . '-content{
                width: 100%;
            }
            .ctu-ulimate-style-' . $styleid . ' {
                display: none;
            }
            .ctu-ultimate-style-heading-' . $styleid . '{
                display: block;
            }
            .ctu-ulitate-style-' . $styleid . '-tabs{
                margin-bottom: 10px;
                background-color: ' . $styledata[33] . ';
                -webkit-box-shadow:  ' . $styledata[59] . 'px ' . $styledata[61] . 'px ' . $styledata[53] . 'px  ' . $styledata[63] . 'px ' . $styledata[55] . ';
                -o-box-shadow:   ' . $styledata[59] . 'px ' . $styledata[61] . 'px ' . $styledata[53] . 'px  ' . $styledata[63] . 'px ' . $styledata[55] . ';
                -ms-box-shadow:   ' . $styledata[59] . 'px ' . $styledata[61] . 'px ' . $styledata[53] . 'px  ' . $styledata[63] . 'px ' . $styledata[55] . ';
                -moz-box-shadow:   ' . $styledata[59] . 'px ' . $styledata[61] . 'px ' . $styledata[53] . 'px  ' . $styledata[63] . 'px ' . $styledata[55] . ';
                box-shadow:   ' . $styledata[59] . 'px ' . $styledata[61] . 'px ' . $styledata[53] . 'px  ' . $styledata[63] . 'px ' . $styledata[55] . ';
                border-radius: 0 0 5px 5px;
            }
        }
        ' . $styledata[71] . '';
    }

    public function default_render() {
        $styledata = $this->style;
        $styleid = $this->ID;
        echo '<div class="ctu-ultimate-wrapper-' . $styleid . '">
             <div class="ctu-ulimate-style-' . $styleid . '">';
        
        $linkopening = '';
        if (!empty($styledata[77]) && $styledata[77] != 'new-tab') {
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
        echo '</div> </div> ';
    }

}
