<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
$theme_name = $active_theme->get( 'Name' );
if($theme_name == 'Dazzling'){
  ?>
  <style>
  .wpsc-iso ul, .wpsc-iso ol {z-index:1000 !important;}
  ul.wpsp_filter_display_container {padding:0;}
  </style>
  <?php
}elseif ($theme_name == 'Start') {
  ?>
  <style>
  .wpsc-iso ul, .wpsc-iso ol {z-index:1000 !important;}
  ul.wpsp_filter_display_container {padding:0;}
  </style>
  <?php
}elseif($theme_name == 'Avada' || $theme_name == 'Avada Child'){
  ?>
  <style>
    @media only screen and (max-width: 600px){
      #wpsc_tickets_container .col-sm-offset-3{ margin-left:0 !important; }
      .col-sm-8{width:100% !important;}
      .col-sm-4{width:100% !important;}
    }
    </style>
  <?php
}elseif ($theme_name == 'Twenty Seventeen') {
  ?>
  <style>
  .wpsc-iso ul, .wpsc-iso ol {z-index:1000 !important;}
  </style>
  <?php
}elseif ($theme_name == 'Astra Child') {
  ?>
  <style>
    .xdsoft_datetimepicker{ z-index:9999999999 !important; }
  </style>
  <?php
}elseif($theme_name == 'Astra'){
  wp_enqueue_script('wcpa-datetime');
  ?>
  <style>
    .xdsoft_datetimepicker{ z-index:9999999999 !important; }
  </style>
  <?php
}elseif($theme_name == 'Twenty Twenty'){
  ?>
  <style>
    .wpsc-iso ul, .wpsc-iso ol {z-index:1000 !important;}
    .mce-widget button{ background-color: transparent;}
    hr.widget_divider::before,hr.widget_divider::after{ background:unset;}
  </style>
  <?php
}
