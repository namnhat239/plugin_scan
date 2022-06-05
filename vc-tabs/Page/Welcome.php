<?php

namespace OXI_TABS_PLUGINS\Page;

/**
 * Description of Welcome
 *
 * @author biplo
 */
class Welcome {

    use \OXI_TABS_PLUGINS\Helper\CSS_JS_Loader;

    public function __construct() {
        $this->header();
        $this->Public_Render();
    }

    public function header() {
        $this->admin_css();
        apply_filters('oxi-tabs-plugin/admin_menu', TRUE);
    }

    public function Public_Render() {
        ?>
        <div class="oxi-addons-wrapper">
            <div class="oxi-addons-import-layouts">
                <div class="about-wrap text-center">
                    <h1>Welcome to Responsive Tabs</h1>
                    <div class="about-text">
                        Thank you for Installing Responsive Tabs with WooCommerce Extension, The most friendly Tabs extension or all in one Package for any Wordpress Sites. Here's how to get started.
                    </div>
                </div>
                <div class="feature-section">
                    <div class="about-container">
                        <div class="about-addons-videos"><iframe src="https://www.youtube.com/embed/elU_RjGqX4g" frameborder="0" allowfullscreen="" class="about-video"></iframe></div>
                    </div>
                </div>
            </div>
            <div class="oxi-addons-docs-column-wrapper">
                <div class="row">
                    <div class="col-lg-6 col-md-12">
                        <div class="oxi-docs-admin-wrapper">

                            <div class="oxi-docs-admin-block">
                                <div class="oxi-docs-admin-header">
                                    <div class="oxi-docs-admin-header-icon">
                                        <span class="dashicons dashicons-format-aside"></span>
                                    </div>
                                    <h4 class="oxi-docs-admin-header-title">Documentation</h4>
                                </div>
                                <div class="oxi-docs-admin-block-content">
                                    <p>Get started by spending some time with the documentation to get familiar with Responsive Tabs with WooCommerce Extension. Build awesome Tabs  for you or your clients with ease.</p>
                                    <a href="https://www.oxilabdemos.com/responsive-tabs/docs/" class="oxi-docs-button" target="_blank">Documentation</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="oxi-docs-admin-wrapper">
                            <div class="oxi-docs-admin-block">
                                <div class="oxi-docs-admin-header">
                                    <div class="oxi-docs-admin-header-icon">
                                        <span class="dashicons dashicons-format-aside"></span>
                                    </div>
                                    <h4 class="oxi-docs-admin-header-title">Contribute to Responsive Tabs</h4>
                                </div>
                                <div class="oxi-docs-admin-block-content">
                                    <p>You can contribute to make Responsive Tabs with WooCommerce Extension better reporting bugs &amp; creating issues. Our Development team always try to make more powerfull Plugins day by day with solved Issues</p>
                                    <a href="https://wordpress.org/support/plugin/vc-tabs/" class="oxi-docs-button" target="_blank">Report a bug</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="oxi-docs-admin-wrapper">
                            <div class="oxi-docs-admin-block">
                                <div class="oxi-docs-admin-header">
                                    <div class="oxi-docs-admin-header-icon">
                                        <span class="dashicons dashicons-format-aside"></span>
                                    </div>
                                    <h4 class="oxi-docs-admin-header-title">Video Tutorials </h4>
                                </div>
                                <div class="oxi-docs-admin-block-content">
                                    <p>Unable to use Responsive Tabs with WooCommerce Extension? Don't worry you can check your web tutorials to make easier to use :) </p>
                                    <a href="https://www.youtube.com/playlist?list=PLUIlGSU2bl8ggcwHiPUVYe3-U9p21GQKU" class="oxi-docs-button" target="_blank">Video Tutorials</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <?php
    }

}
