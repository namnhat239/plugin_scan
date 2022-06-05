<?php

namespace OXI_TABS_PLUGINS\Render\Old_Views;

/**
 * Description of Old Style
 *
 * @author biplob018
 */
use OXI_TABS_PLUGINS\Render\Old_Render;

class Style13 extends Old_Render {

    public function inline_public_jquery() {
        $styledata = $this->style;
        $styleid = $this->ID;
        $oxi_fixed_header = get_option('oxi_addons_fixed_header_size');
        if (empty($styledata[75])) {
            $initialopen = ':first';
        } else if ($styledata[75] == 'none') {
            $initialopen = '';
        } else {
            $initialopen = $styledata[75];
        }
        if (empty($styledata[77])) {
            $animationin = 'slideDown';
            $animationout = 'slideUp';
        } else if ($styledata[77] == 'slide') {
            $animationin = 'slideDown';
            $animationout = 'slideUp';
        } else if ($styledata[77] == 'show') {
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
        $this->CSS = '
        .ctu-ulimate-style-' . $styleid . '{
            margin: 0 0 0 0;
            width: 100%;
            float: left;
            display: -webkit-box;
            display: -ms-flexbox;
            display: -o-flexbox;
            display: -moz-flexbox;
            display: flex;
            list-style: none;
            text-align: center;
            -webkit-box-pack: ' . $styledata[15] . ';
            -ms-flex-pack:' . $styledata[15] . ';
            -o-flex-pack: ' . $styledata[15] . ';
            -moz-flex-pack:' . $styledata[15] . ';
            justify-content: ' . $styledata[15] . ';
            margin-bottom: ' . $styledata[23] . 'px;
        }
        .ctu-ultimate-wrapper-' . $styleid . '{
            width: 100%;
            float: left;
        }
        .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li{
            width: 100%;
            list-style: none;
            cursor: pointer;
            float: left;
            max-width: ' . $styledata[17] . 'px;
            margin-bottom: 0;
            padding: ' . $styledata[19] . 'px 10px;
            text-align: center;
            color: ' . $styledata[3] . ';
            background-color:  ' . $styledata[5] . ';
            font-size: ' . $styledata[1] . 'px;
            font-family:    ' . $this->font_familly($styledata[11]) . ';
            font-weight: ' . $styledata[13] . ';
            line-height: 100%;
            font-style: ' . $styledata[59] . ';
            border-radius: ' . $styledata[25] . 'px;
            -webkit-box-shadow: ' . $styledata[67] . 'px ' . $styledata[69] . 'px ' . $styledata[27] . 'px ' . $styledata[71] . 'px ' . $styledata[29] . ';
            -o-box-shadow: ' . $styledata[67] . 'px ' . $styledata[69] . 'px ' . $styledata[27] . 'px ' . $styledata[71] . 'px ' . $styledata[29] . ';
            -ms-box-shadow: ' . $styledata[67] . 'px ' . $styledata[69] . 'px ' . $styledata[27] . 'px ' . $styledata[71] . 'px ' . $styledata[29] . ';
            -moz-box-shadow: ' . $styledata[67] . 'px ' . $styledata[69] . 'px ' . $styledata[27] . 'px ' . $styledata[71] . 'px ' . $styledata[29] . ';
            box-shadow: ' . $styledata[67] . 'px ' . $styledata[69] . 'px ' . $styledata[27] . 'px ' . $styledata[71] . 'px ' . $styledata[29] . ';
            margin-right: ' . $styledata[21] . 'px;
        }
        .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li:last-child{
            margin-right: 0;
        }
        .ctu-ulimate-style-' . $styleid . ' .vc-tabs-li.active{
            color: ' . $styledata[7] . ';
            background-color:  ' . $styledata[9] . ';
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
            background-color: ' . $styledata[5] . ';
            font-size: ' . $styledata[1] . 'px;
            padding: ' . $styledata[19] . 'px 10px;
            font-weight: ' . $styledata[13] . ';
            font-style: ' . $styledata[59] . ';
            font-family:  ' . $this->font_familly($styledata[11]) . ';
            border-radius:5px;
            -webkit-box-shadow:  ' . $styledata[67] . 'px ' . $styledata[69] . 'px ' . $styledata[27] . 'px ' . $styledata[71] . 'px ' . $styledata[29] . ';
            -o-box-shadow:  ' . $styledata[67] . 'px ' . $styledata[69] . 'px ' . $styledata[27] . 'px ' . $styledata[71] . 'px ' . $styledata[29] . ';
            -ms-box-shadow:  ' . $styledata[67] . 'px ' . $styledata[69] . 'px ' . $styledata[27] . 'px ' . $styledata[71] . 'px ' . $styledata[29] . ';
            -moz-box-shadow:  ' . $styledata[67] . 'px ' . $styledata[69] . 'px ' . $styledata[27] . 'px ' . $styledata[71] . 'px ' . $styledata[29] . ';
            box-shadow: ' . $styledata[67] . 'px ' . $styledata[69] . 'px ' . $styledata[27] . 'px ' . $styledata[71] . 'px ' . $styledata[29] . ';

        }
        .ctu-ultimate-style-heading-' . $styleid . '.active{
            color: ' . $styledata[7] . ';
            background-color: ' . $styledata[9] . ';
        }
        .ctu-ulitate-style-' . $styleid . '-tabs{
            display: none;
            width: 100%;
            float: left;            
            background-color: ' . $styledata[35] . ';           
            padding: ' . $styledata[37] . 'px ' . $styledata[39] . 'px ' . $styledata[41] . 'px ' . $styledata[43] . 'px;
            text-align: ' . $styledata[51] . ';
            border-radius: ' . $styledata[53] . 'px;
            -webkit-box-shadow: ' . $styledata[61] . 'px ' . $styledata[63] . 'px  ' . $styledata[55] . 'px ' . $styledata[65] . 'px  ' . $styledata[57] . ';
            -o-box-shadow: ' . $styledata[61] . 'px ' . $styledata[63] . 'px  ' . $styledata[55] . 'px ' . $styledata[65] . 'px  ' . $styledata[57] . ';
            -ms-box-shadow: ' . $styledata[61] . 'px ' . $styledata[63] . 'px  ' . $styledata[55] . 'px ' . $styledata[65] . 'px  ' . $styledata[57] . ';
            -moz-box-shadow: ' . $styledata[61] . 'px ' . $styledata[63] . 'px  ' . $styledata[55] . 'px ' . $styledata[65] . 'px  ' . $styledata[57] . ';
            box-shadow: ' . $styledata[61] . 'px ' . $styledata[63] . 'px  ' . $styledata[55] . 'px ' . $styledata[65] . 'px  ' . $styledata[57] . ';
        }
        .ctu-ulitate-style-' . $styleid . '-tabs p{
            font-size: ' . $styledata[31] . 'px;
            color: ' . $styledata[33] . ';
            font-weight: ' . $styledata[49] . ';
            font-family: ' . $this->font_familly($styledata[47]) . ';
            line-height: ' . $styledata[45] . ';
            margin-bottom: 0;
            margin-top: 0;
        }
        @media only screen and (max-width: 900px) {
            .ctu-ulimate-style-' . $styleid . ' {
                display: none;
            }
            .ctu-ultimate-style-heading-' . $styleid . '{
                display: block;
                -webkit-box-shadow:  ' . $styledata[67] . 'px ' . $styledata[69] . 'px ' . $styledata[27] . 'px ' . $styledata[71] . 'px ' . $styledata[29] . ';
                -o-box-shadow: ' . $styledata[67] . 'px ' . $styledata[69] . 'px ' . $styledata[27] . 'px ' . $styledata[71] . 'px ' . $styledata[29] . ';
                -ms-box-shadow: ' . $styledata[67] . 'px ' . $styledata[69] . 'px ' . $styledata[27] . 'px ' . $styledata[71] . 'px ' . $styledata[29] . ';
                -moz-box-shadow:  ' . $styledata[67] . 'px ' . $styledata[69] . 'px ' . $styledata[27] . 'px ' . $styledata[71] . 'px ' . $styledata[29] . ';
                box-shadow: ' . $styledata[67] . 'px ' . $styledata[69] . 'px ' . $styledata[27] . 'px ' . $styledata[71] . 'px ' . $styledata[29] . ';
                margin-bottom: 10px;
            }
            .ctu-ulitate-style-' . $styleid . '-tabs{
                margin-bottom: 10px;
            }
        }
        ' . $styledata[73] . '';
    }

    public function default_render() {
        $styledata = $this->style;
        $styleid = $this->ID;
        echo '<div class="ctu-ultimate-wrapper-' . $styleid . '">
            <div class="ctu-ulimate-style-' . $styleid . '">';
       
         $linkopening = '';
        if (!empty($styledata[79]) && $styledata[79] != 'new-tab') {
            $linkopening = ", '_self'";
        }

        foreach ($this->child as $value) {
            $titlefiles = explode('{}{}{}', $value['title']);
            if (!empty($titlefiles[1]) && $this->user != 'admin') {
                $this->JQUERY .= '$(".vc-tabs-li-' . $styleid . '-id-' . $value['id'] . '").click(function() {window.open("' . $titlefiles[1] . '" ' . $linkopening . ');});';
            }
            echo ' <div class="vc-tabs-li vc-tabs-li-' . $styleid . '-id-' . $value['id'] . '" ref="#ctu-ulitate-style-' . $styleid . '-id-' . $value['id'] . '" class="">
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
        echo '</div></div>';
    }

}
