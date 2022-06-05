<?php

namespace OXI_TABS_PLUGINS\Render\Old_Views;

/**
 * Description of Old Style
 *
 * @author biplob018
 */
use OXI_TABS_PLUGINS\Render\Old_Render;

class Style16 extends Old_Render {

    public function inline_public_jquery() {
        $styledata = $this->style;
        $styleid = $this->ID;
        $oxi_fixed_header = get_option('oxi_addons_fixed_header_size');
        if (empty($styledata[77])) {
            $initialopen = ':first';
        } else if ($styledata[77] == 'none') {
            $initialopen = '';
        } else {
            $initialopen = $styledata[77];
        }
        if (empty($styledata[79])) {
            $animationin = 'slideDown';
            $animationout = 'slideUp';
        } else if ($styledata[79] == 'slide') {
            $animationin = 'slideDown';
            $animationout = 'slideUp';
        } else if ($styledata[79] == 'show') {
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
        $this->CSS = '.ctu-ulimate-style-' . $styleid . '{
                        width: calc(100% - ' . $styledata[37] . '%);
                        float: ' . $styledata[11] . ';
                        list-style: none;
                        text-align: center;
                        overflow: hidden;
                        margin-bottom: 0;
                        padding: ' . $styledata[19] . 'px;
                    }
                    .ctu-ultimate-wrapper-' . $styleid . '{
                        margin: 0 0 0 0;
                        width: 100%;
                        float: left;
                        border-radius: ' . $styledata[55] . 'px;
                        -webkit-box-shadow:   ' . $styledata[63] . 'px ' . $styledata[65] . 'px ' . $styledata[57] . 'px ' . $styledata[67] . 'px ' . $styledata[59] . ';
                        -o-box-shadow:   ' . $styledata[63] . 'px ' . $styledata[65] . 'px ' . $styledata[57] . 'px ' . $styledata[67] . 'px ' . $styledata[59] . ';
                        -ms-box-shadow:  ' . $styledata[63] . 'px ' . $styledata[65] . 'px ' . $styledata[57] . 'px ' . $styledata[67] . 'px ' . $styledata[59] . ';
                        -moz-box-shadow:   ' . $styledata[63] . 'px ' . $styledata[65] . 'px ' . $styledata[57] . 'px ' . $styledata[67] . 'px ' . $styledata[59] . ';
                        box-shadow:   ' . $styledata[63] . 'px ' . $styledata[65] . 'px ' . $styledata[57] . 'px ' . $styledata[67] . 'px ' . $styledata[59] . ';
                        background-color: ' . $styledata[35] . ';
                    }
                    .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li{
                        width: 100%;
                        list-style: none;
                        cursor: pointer;
                        display: block;
                        margin-bottom: ' . $styledata[19] . 'px;
                        padding: ' . $styledata[17] . 'px;
                        text-align: center;
                        color: ' . $styledata[3] . ';
                        background-color:  ' . $styledata[5] . ' ;
                        font-style:' . $styledata[61] . ';
                        font-size: ' . $styledata[1] . 'px;
                        font-family:    ' . $this->font_familly($styledata[13]) . ';
                        font-weight: ' . $styledata[15] . ';
                        line-height: 100%;
                        border-radius: ' . $styledata[25] . 'px;
                        -webkit-box-shadow:  ' . $styledata[69] . 'px ' . $styledata[71] . 'px ' . $styledata[27] . 'px ' . $styledata[73] . 'px ' . $styledata[29] . ';
                        -o-box-shadow: ' . $styledata[69] . 'px ' . $styledata[71] . 'px ' . $styledata[27] . 'px ' . $styledata[73] . 'px ' . $styledata[29] . ';
                        -ms-box-shadow: ' . $styledata[69] . 'px ' . $styledata[71] . 'px ' . $styledata[27] . 'px ' . $styledata[73] . 'px ' . $styledata[29] . ';
                        -moz-box-shadow: ' . $styledata[69] . 'px ' . $styledata[71] . 'px ' . $styledata[27] . 'px ' . $styledata[73] . 'px ' . $styledata[29] . ';
                        box-shadow: ' . $styledata[69] . 'px ' . $styledata[71] . 'px ' . $styledata[27] . 'px ' . $styledata[73] . 'px ' . $styledata[29] . ';
                    }
                    .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li:last-child{
                        margin-bottom: 0;
                    }
                    .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li.active{
                        color: ' . $styledata[7] . ';
                        background-color: ' . $styledata[9] . ';
                    }
                    .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li .oxi-icons{
                        display: block !important;
                        padding-bottom: ' . $styledata[23] . 'px !important;
                        font-size: ' . $styledata[21] . 'px !important;
                    }
                    .ctu-ultimate-style-' . $styleid . '-content{
                        width: ' . $styledata[37] . '%;
                        float: left;
                        overflow: hidden;
                    }
                    .ctu-ultimate-style-heading-' . $styleid . '{
                        width: 100%;
                        float:left;
                        cursor: pointer;
                        display: none;
                        line-height: 100%;
                        font-size: ' . $styledata[1] . 'px;
                        padding: ' . $styledata[17] . 'px;
                        font-weight: ' . $styledata[15] . ';
                        font-family:  ' . $this->font_familly($styledata[13]) . ';
                        -webkit-box-shadow: ' . $styledata[69] . 'px ' . $styledata[71] . 'px ' . $styledata[27] . 'px ' . $styledata[73] . 'px ' . $styledata[29] . ';
                        -o-box-shadow: ' . $styledata[69] . 'px ' . $styledata[71] . 'px ' . $styledata[27] . 'px ' . $styledata[73] . 'px ' . $styledata[29] . ';
                        -ms-box-shadow: ' . $styledata[69] . 'px ' . $styledata[71] . 'px ' . $styledata[27] . 'px ' . $styledata[73] . 'px ' . $styledata[29] . ';
                        -moz-box-shadow: ' . $styledata[69] . 'px ' . $styledata[71] . 'px ' . $styledata[27] . 'px ' . $styledata[73] . 'px ' . $styledata[29] . ';
                        box-shadow: ' . $styledata[69] . 'px ' . $styledata[71] . 'px ' . $styledata[27] . 'px ' . $styledata[73] . 'px ' . $styledata[29] . ';
                        border-radius: ' . $styledata[25] . 'px;
                        font-style:' . $styledata[61] . ';
                        color: ' . $styledata[3] . ';
                        background-color: ' . $styledata[5] . ';
                    }
                    .ctu-ultimate-style-heading-' . $styleid . '.active{
                        color: ' . $styledata[7] . '; 
                        background-color: ' . $styledata[9] . ';
                    }
                    .ctu-ulitate-style-' . $styleid . '-tabs{
                        display: none;
                        width:100%;
                        float:left;           
                        padding: ' . $styledata[39] . 'px ' . $styledata[41] . 'px ' . $styledata[43] . 'px ' . $styledata[45] . 'px ;
                        text-align: ' . $styledata[53] . '
                    }
                    .ctu-ulitate-style-' . $styleid . '-tabs p{
                        font-size: ' . $styledata[31] . 'px;
                        color:' . $styledata[33] . ';
                        font-weight: ' . $styledata[51] . ';
                        line-height: ' . $styledata[47] . ';
                        font-family:  ' . $this->font_familly($styledata[49]) . ';
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
                            border-radius:0;
                            background-color:transparent;
                        }
                        .ctu-ultimate-style-' . $styleid . '-content{
                            width: 100%;
                            float: left;
                            overflow:   visible;
                        }
                        .ctu-ulimate-style-' . $styleid . ' {
                            display: none;
                        }
                        .ctu-ultimate-style-heading-' . $styleid . '{
                            display: block;
                            margin-bottom: 10px;
                        }
                        .ctu-ulitate-style-' . $styleid . '-tabs{
                            border-radius: ' . $styledata[55] . 'px;
                            margin-bottom: 10px;
                            -webkit-box-shadow:   ' . $styledata[63] . 'px ' . $styledata[65] . 'px ' . $styledata[57] . 'px ' . $styledata[67] . 'px ' . $styledata[59] . ';
                            -o-box-shadow:   ' . $styledata[63] . 'px ' . $styledata[65] . 'px ' . $styledata[57] . 'px ' . $styledata[67] . 'px ' . $styledata[59] . ';
                            -ms-box-shadow:   ' . $styledata[63] . 'px ' . $styledata[65] . 'px ' . $styledata[57] . 'px ' . $styledata[67] . 'px ' . $styledata[59] . ';
                            -moz-box-shadow:  ' . $styledata[63] . 'px ' . $styledata[65] . 'px ' . $styledata[57] . 'px ' . $styledata[67] . 'px ' . $styledata[59] . ';
                            box-shadow:   ' . $styledata[63] . 'px ' . $styledata[65] . 'px ' . $styledata[57] . 'px ' . $styledata[67] . 'px ' . $styledata[59] . ';
                        }
                    }
                     ' . $styledata[75] . '';
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
            echo ' <div class="vc-tabs-li vc-tabs-li-' . $styleid . '-id-' . $value['id'] . '" ref="#ctu-ulitate-style-' . $styleid . '-id-' . $value['id'] . '">
                               ' . $this->icon_font_selector($value['css']) . '
                                ' . $this->special_charecter($titlefiles[0]) . '
                            </div>';
        }
        echo '</div>';
        echo '<div class="ctu-ultimate-style-' . $styleid . '-content">';
        foreach ($this->child as $value) {
            $titlefiles = explode('{}{}{}', $value['title']);
            echo '<div class="ctu-ultimate-style-heading-' . $styleid . ' vc-tabs-li-' . $styleid . '-id-' . $value['id'] . '" ref="#ctu-ulitate-style-' . $styleid . '-id-' . $value['id'] . '"> 
                    ' .  $this->icon_font_selector($value['css']) . '
                   ' .  $this->special_charecter($titlefiles[0]) . '
               </div>
                <div class="ctu-ulitate-style-' . $styleid . '-tabs ' . ($this->user == 'admin' ? 'oxi-addons-admin-edit-list' : '') . '" id="ctu-ulitate-style-' . $styleid . '-id-' . $value['id'] . '">
                    ' .  $this->special_charecter($value['files']) . '
                    ' . $this->admin_edit_panel($value['id']) . '
                </div> ';
        }
        echo '</div></div> ';
    }

}
