<?php

namespace OXI_TABS_PLUGINS\Render\Old_Views;

/**
 * Description of Old Style
 *
 * @author biplob018
 */
use OXI_TABS_PLUGINS\Render\Old_Render;

class Style15 extends Old_Render {

    public function inline_public_jquery() {
        $styledata = $this->style;
        $styleid = $this->ID;
        $oxi_fixed_header = get_option('oxi_addons_fixed_header_size');
        if (empty($styledata[63])) {
            $initialopen = ':first';
        } else if ($styledata[63] == 'none') {
            $initialopen = '';
        } else {
            $initialopen = $styledata[63];
        }
        if (empty($styledata[65])) {
            $animationin = 'slideDown';
            $animationout = 'slideUp';
        } else if ($styledata[65] == 'slide') {
            $animationin = 'slideDown';
            $animationout = 'slideUp';
        } else if ($styledata[65] == 'show') {
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
        $this->CSS = '.ctu-ultimate-wrapper-'. $styleid.'{
            margin: 0 0 0 0;
            width: 100%;
            float: left;
            border-radius: '. $styledata[45].'px;
            overflow: hidden;
            background-color: '. $styledata[25].';
            -webkit-box-shadow:   '. $styledata[55].'px '. $styledata[57].'px '. $styledata[47].'px '. $styledata[59].'px '. $styledata[49].';
            -o-box-shadow:   '. $styledata[55].'px '. $styledata[57].'px '. $styledata[47].'px '. $styledata[59].'px '. $styledata[49].';
            -ms-box-shadow:   '. $styledata[55].'px '. $styledata[57].'px '. $styledata[47].'px '. $styledata[59].'px '. $styledata[49].';
            -moz-box-shadow:   '. $styledata[55].'px '. $styledata[57].'px '. $styledata[47].'px '. $styledata[59].'px '. $styledata[49].';
            box-shadow:   '. $styledata[55].'px '. $styledata[57].'px '. $styledata[47].'px '. $styledata[59].'px '. $styledata[49].';
        }
        .ctu-ulimate-style-'. $styleid.'{
            width: calc(100% - '. $styledata[35].'%);
            float: '. $styledata[51].';
            list-style: none;
            text-align: center;
            overflow: hidden;
            margin-bottom: 0;
        }
        .ctu-ulimate-style-'. $styleid.' .vc-tabs-li{
            width: 100%;
            list-style: none;
            cursor: pointer;
            display: block;
            display: -webkit-box;
            display: -ms-flexbox;
            display: -o-flexbox;
            display: -moz-flexbox;
            display: flex;
            -webkit-box-align:   center;
            -ms-flex-align:   center;
            -o-flex-align:   center;
            -moz-flex-align:   center;
            align-items:   center;
            margin-bottom: '. $styledata[19].'px;
            padding: '. $styledata[15].'px 12px;
            text-align: left;
            color: '. $styledata[3].';
            background-color:  '. $styledata[5].';
            font-size: '. $styledata[1].'px;
            font-family:    '. $this->font_familly($styledata[11]).';
            font-weight:  '. $styledata[13].';
            font-style: '. $styledata[53].';
            line-height: 100%;
            -webkit-box-shadow: inset 3px -36px 52px -24px rgba(255, 255, 255, 0.28);
            -moz-box-shadow: inset 3px -36px 52px -24px rgba(255, 255, 255, 0.28);
            -o-box-shadow: inset 3px -36px 52px -24px rgba(255, 255, 255, 0.28);
            -moz-box-shadow: inset 3px -36px 52px -24px rgba(255, 255, 255, 0.28);
            box-shadow: inset 3px -36px 52px -24px rgba(255, 255, 255, 0.28);
        }
        .ctu-ulimate-style-'. $styleid.' .vc-tabs-li.active{
            color: '. $styledata[7].';
            background-color: '. $styledata[9].';
        }
        .ctu-ulimate-style-'. $styleid.' .vc-tabs-li .oxi-icons{
            font-size: '. $styledata[17].'px !important;
            margin-right: 10px !important;
        }
        .ctu-ulimate-style-'. $styleid.' .vc-tabs-li:last-child{
            margin-bottom: 0;
        }
        .ctu-ultimate-style-'. $styleid.'-content{
            width: '. $styledata[35].'%;
            float: left;
        }
        .ctu-ultimate-style-heading-'. $styleid.'{
            width: 100%;
            float: left;
            display: -webkit-box;
            display: -ms-flexbox;
            display: -o-flexbox;
            display: -moz-flexbox;
            display: flex;
            -webkit-box-align:   center;
            -ms-flex-align:   center;
            -o-flex-align:   center;
            -moz-flex-align:   center;
            align-items:   center;
            cursor: pointer;
            display: none;
            line-height: 100%;
            color: '. $styledata[3].';
            background-color: '. $styledata[5].';
            font-size: '. $styledata[1].'px;
            padding: '. $styledata[15].'px 10px;
            font-weight: '. $styledata[13].';
            font-family:  '. $this->font_familly($styledata[11]).';
            font-style: '. $styledata[53].';
            -webkit-box-shadow: inset 3px -36px 52px -24px rgba(255, 255, 255, 0.28);
            -moz-box-shadow: inset 3px -36px 52px -24px rgba(255, 255, 255, 0.28);
            -o-box-shadow: inset 3px -36px 52px -24px rgba(255, 255, 255, 0.28);
            -ms-box-shadow: inset 3px -36px 52px -24px rgba(255, 255, 255, 0.28);
            box-shadow: inset 3px -36px 52px -24px rgba(255, 255, 255, 0.28);
        }
        .ctu-ultimate-style-heading-'. $styleid.'.active{
            color: '. $styledata[7].';
            background-color:'. $styledata[9].';
            border-radius: 5px 5px 0 0;
        }
        .ctu-ultimate-style-heading-'. $styleid.' i{
            font-size: '. $styledata[17].'px !important;
            margin-right: 10px !important;
        }
        .ctu-ulitate-style-'. $styleid.'-tabs{
            float: left;
            width: 100%;
            display: none;           
            padding: '. $styledata[27].'px '. $styledata[29].'px '. $styledata[31].'px '. $styledata[33].'px ;
            text-align: '. $styledata[43].';
        }
        .ctu-ulitate-style-'. $styleid.'-tabs p{
            font-size: '. $styledata[21].'px;
            color: '. $styledata[23].';
            font-weight: '. $styledata[41].';
            line-height: '. $styledata[37].';
            font-family: '. $this->font_familly($styledata[39]).';
            margin-bottom: 0;
            margin-top:0;
        }
        @media only screen and (max-width: 900px) {
            .ctu-ultimate-wrapper-'. $styleid.'{
                display: block;
                overflow: visible;
                border-radius:  0;
                background-color: transparent;
                -webkit-box-shadow: none;
                -o-box-shadow: none;
                -ms-box-shadow: none;
                -moz-box-shadow: none;
                box-shadow: none;
            }
            .ctu-ultimate-style-'. $styleid.'-content{
                width: 100%;
                display: block;
                overflow: visible;
            }
            .ctu-ulimate-style-'. $styleid.' {
                display: none;
            }
            .ctu-ultimate-style-heading-'. $styleid.'{
                width: 100%;
                float: left;
                display: block;
                border-radius: 5px;
                -webkit-box-shadow:   '. $styledata[55].'px '. $styledata[57].'px '. $styledata[47].'px '. $styledata[59].'px '. $styledata[49].';
                -o-box-shadow: '. $styledata[55].'px '. $styledata[57].'px '. $styledata[47].'px '. $styledata[59].'px '. $styledata[49].';
                -ms-box-shadow:  '. $styledata[55].'px '. $styledata[57].'px '. $styledata[47].'px '. $styledata[59].'px '. $styledata[49].';
                -moz-box-shadow:  '. $styledata[55].'px '. $styledata[57].'px '. $styledata[47].'px '. $styledata[59].'px '. $styledata[49].';
                box-shadow:   '. $styledata[55].'px '. $styledata[57].'px '. $styledata[47].'px '. $styledata[59].'px '. $styledata[49].';
                margin-bottom: 10px;
            }
            .ctu-ulitate-style-'. $styleid.'-tabs{
                margin-bottom: 10px;
                border-radius: 0 0 5px 5px;
                -webkit-box-shadow:   '. $styledata[55].'px '. $styledata[57].'px '. $styledata[47].'px '. $styledata[59].'px '. $styledata[49].';
                -o-box-shadow:    '. $styledata[55].'px '. $styledata[57].'px '. $styledata[47].'px '. $styledata[59].'px '. $styledata[49].';
                -ms-box-shadow:    '. $styledata[55].'px '. $styledata[57].'px '. $styledata[47].'px '. $styledata[59].'px '. $styledata[49].';
                -moz-box-shadow:   '. $styledata[55].'px '. $styledata[57].'px '. $styledata[47].'px '. $styledata[59].'px '. $styledata[49].';
                box-shadow:   '. $styledata[55].'px '. $styledata[57].'px '. $styledata[47].'px '. $styledata[59].'px '. $styledata[49].';

            }
        }
        '. $styledata[61].'';
    }

    public function default_render() {
        $styledata = $this->style;
        $styleid = $this->ID;
        echo '<div class="ctu-ultimate-wrapper-' . $styleid . '">
             <div class="ctu-ulimate-style-' . $styleid . '">';
        $linkopening = '';
        if (!empty($styledata[67]) && $styledata[67] != 'new-tab') {
            $linkopening = ", '_self'";
        }
        foreach ($this->child as $value) {
            $titlefiles = explode('{}{}{}', $value['title']);
            if (!empty($titlefiles[1]) && $this->user != 'admin') {
                $this->JQUERY .= '$(".vc-tabs-li-' . $styleid . '-id-' . $value['id'] . '").click(function() {window.open("' . $titlefiles[1] . '" ' . $linkopening . ');});';
            }
            echo '   <div class="vc-tabs-li vc-tabs-li-' . $styleid . '-id-' . $value['id'] . '" ref="#ctu-ulitate-style-' . $styleid . '-id-' . $value['id'] . '" >
                                    ' . $this->icon_font_selector($value['css']) . '
                                    ' . $this->special_charecter($titlefiles[0]) . '
                                </div>';
        }
        echo '</div>';
        echo '<div class="ctu-ultimate-style-' . $styleid . '-content">';
        foreach ($this->child as $value) {
            $titlefiles = explode('{}{}{}', $value['title']);
            echo '<div class="ctu-ultimate-style-heading-' . $styleid . ' vc-tabs-li-' . $styleid . '-id-' . $value['id'] . '" ref="#ctu-ulitate-style-' . $styleid . '-id-' . $value['id'] . '"> 
                    ' . $this->icon_font_selector($value['css']) . '
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
