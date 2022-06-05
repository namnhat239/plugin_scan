<?php

namespace OXI_TABS_PLUGINS\Render\Old_Views;

/**
 * Description of Old Style
 *
 * @author biplob018
 */
use OXI_TABS_PLUGINS\Render\Old_Render;

class Style11 extends Old_Render {

    public function inline_public_jquery() {
        $styledata = $this->style;
        $styleid = $this->ID;
        $oxi_fixed_header = get_option('oxi_addons_fixed_header_size');
        if (empty($styledata[69])) {
            $initialopen = ':first';
        } else if ($styledata[69] == 'none') {
            $initialopen = '';
        } else {
            $initialopen = $styledata[69];
        }
        if (empty($styledata[71])) {
            $animationin = 'slideDown';
            $animationout = 'slideUp';
        } else if ($styledata[71] == 'slide') {
            $animationin = 'slideDown';
            $animationout = 'slideUp';
        } else if ($styledata[61] == 'show') {
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
            display: flex;
            text-align: center;
            justify-content: ' . $styledata[13] . ';
            margin-bottom: ' . $styledata[19] . 'px;
        }
        .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li{
            list-style: none;
            width: 100%;
            float: left;
            cursor: pointer;
            max-width: ' . $styledata[11] . 'px;
            position: relative;
            margin-bottom: 0;
            border-top: 5px solid ;
            padding: ' . $styledata[15] . 'px 10px;
            margin-right: ' . $styledata[17] . 'px;
            text-align: center;
            background-color: ' . $styledata[5] . ';
            font-size: ' . $styledata[1] . 'px;
            font-family:    ' . $this->font_familly($styledata[7]) . ';
            font-weight: ' . $styledata[11] . ';
            line-height: 100%;
            font-style: ' . $styledata[65] . ';
            box-shadow:  ' . $styledata[59] . 'px ' . $styledata[61] . 'px ' . $styledata[23] . 'px ' . $styledata[63] . 'px ' . $styledata[25] . ';
        }
        .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li span{
            color: ' . $styledata[3] . ';
            width: 100%;
            text-align: center;
            float: left;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li.active span{
            color: inherit;
        }
        .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li:last-child{
            margin-right: 0;
        }
        .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li span .oxi-icons,  
        .ctu-ultimate-style-heading-' . $styleid . ' span i{
            padding-right: 8px !important;
            font-size: ' . $styledata[21] . 'px !important;
        }
        .ctu-ultimate-style-' . $styleid . '-content{
            width:100%;
            float: left;
        }
        .ctu-ultimate-style-heading-' . $styleid . '{
            width: 100%;
            float: left;
            cursor: pointer;
            display: none;
            line-height: 100%;
            background-color:' . $styledata[5] . ';
            font-size: ' . $styledata[1] . 'px;
            padding: ' . $styledata[15] . 'px 10px;
            font-weight: ' . $styledata[9] . ';
            font-style: ' . $styledata[65] . ';
            font-family:  ' . $this->font_familly($styledata[7]) . ';
            box-shadow:  ' . $styledata[59] . 'px ' . $styledata[61] . 'px ' . $styledata[23] . 'px ' . $styledata[63] . 'px ' . $styledata[25] . ';
        }
        .ctu-ultimate-style-heading-' . $styleid . ' span{
            color: ' . $styledata[3] . ';
            width: 100%;
            text-align: center;
            float: left;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .ctu-ultimate-style-heading-' . $styleid . ' span .oxi-icons{
            font-size:' . $styledata[21] . 'px !important;
        }
        .ctu-ultimate-style-heading-' . $styleid . '.active span{
            color: inherit;
        }
        .ctu-ulitate-style-' . $styleid . '-tabs{
            width: 100%;
            float: left;
            display: none;            
            background-color: ' . $styledata[31] . ';           
            padding: ' . $styledata[33] . 'px ' . $styledata[35] . 'px ' . $styledata[37] . 'px ' . $styledata[39] . 'px ;
            text-align: ' . $styledata[47] . ';
            box-shadow: ' . $styledata[53] . 'px ' . $styledata[55] . 'px ' . $styledata[49] . 'px ' . $styledata[57] . 'px ' . $styledata[51] . ';
        }
        .ctu-ulitate-style-' . $styleid . '-tabs p{
            color: ' . $styledata[29] . ';
            font-size: ' . $styledata[27] . 'px;
            font-weight: ' . $styledata[45] . ';
            line-height: ' . $styledata[41] . ';
            font-family: ' . $this->font_familly($styledata[43]) . ';
            margin-bottom: 0;
            margin-top:0;
        }
        @media only screen and (max-width: 900px) {
            .ctu-ultimate-wrapper-' . $styleid . '{
                display: block;
                box-shadow: none;
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
                box-shadow:  ' . $styledata[59] . 'px ' . $styledata[61] . 'px ' . $styledata[23] . 'px ' . $styledata[63] . 'px ' . $styledata[25] . ';
                margin-bottom: ' . $styledata[19] . 'px;
            }
            .ctu-ulitate-style-' . $styleid . '-tabs{
                margin-bottom: 10px;
            }
        }
        ' . $styledata[67] . '';
    }

    public function default_render() {
        $styledata = $this->style;
        $styleid = $this->ID;
        echo '<div class="ctu-ultimate-wrapper-' . $styleid . '">
                <div class="ctu-ulimate-style-' . $styleid . '">';
        $linkopening = '';
        if (!empty($styledata[73]) && $styledata[73] != 'new-tab') {
            $linkopening = ", '_self'";
        }
        foreach ($this->child as $value) {
            $cssfile = explode('|', $value['css']);
            $titlefiles = explode('{}{}{}', $value['title']);
            if (!empty($titlefiles[1]) && $$this->user != 'admin') {
                $this->JQUERY .= '$(".vc-tabs-li-' . $styleid . '-id-' . $value['id'] . '").click(function() {window.open("' . $titlefiles[1] . '" ' . $linkopening . ');});';
            }
            echo ' <div class="vc-tabs-li vc-tabs-li-' . $styleid . '-id-' . $value['id'] . '" ref="#ctu-ulitate-style-' . $styleid . '-id-' . $value['id'] . '" style="color :' . $cssfile[1] . '; border-color: ' . $cssfile[1] . '">
                                <span> 
                                    ' . $this->icon_font_selector($cssfile[3]) . '
                                    ' . $this->special_charecter($titlefiles[0]) . ' 
                                </span>
                            </div>';
        }
        echo '</div>';

        echo '<div class="ctu-ultimate-style-' . $styleid . '-content">';
        foreach ($this->child as $value) {
            $cssfile = explode('|', $value['css']);
            $titlefiles = explode('{}{}{}', $value['title']);
            echo '  <div class="ctu-ultimate-style-heading-' . $styleid . ' vc-tabs-li-' . $styleid . '-id-' . $value['id'] . '" ref="#ctu-ulitate-style-' . $styleid . '-id-' . $value['id'] . '" style="color :' . $cssfile[1] . '"> 
                        <span> 
                             ' . $this->special_charecter($titlefiles[0]) . ' 
                        </span>
                    </div>
                    <div class="ctu-ulitate-style-' . $styleid . '-tabs ' . ($this->user == 'admin' ? 'oxi-addons-admin-edit-list' : '') . '" id="ctu-ulitate-style-' . $styleid . '-id-' . $value['id'] . '">
                        ' . $this->special_charecter($value['files']) . '
                        ' . $this->admin_edit_panel($value['id']) . '
                    </div> ';
        }
        echo '</div> </div> ';
    }

}
