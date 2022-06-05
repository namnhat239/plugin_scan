<?php /**
       * @author    William Sergio Minossi
       * @copyright 2018
       */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
global $wpdb;
$table_name = $wpdb->prefix . "sbb_blacklist";

$stopbadbots_current__url = esc_url_raw($_SERVER['REQUEST_URI']);

if(stripos($stopbadbots_current__url, 'page=stop_bad_bots_plugin') === false) {
    $query = "SELECT * FROM `$table_name` WHERE botblocked > 0 order by botblocked DESC limit 5";
} else {
    $query = "SELECT * FROM `$table_name` WHERE botblocked > 0 order by botblocked DESC limit 10";
}
  
  
$results9 = $wpdb->get_results(sanitize_text_field($query));
if($wpdb->num_rows < 1) {
    esc_attr_e('No bots blocked by Nickname. Please, try again tomorrow','stopbadbots');
    return;
}
echo '<table class="greyGridTable">';
echo '<thead>';
echo "<tr><th>";
echo __("Bot","stopbadbots");
echo "<br />";
echo  __("Nickname","stopbadbots");
echo "</th><th>";
echo __("Num","stopbadbots");
echo "<br />";
echo __("Blocked","stopbadbots");
echo "</th></tr>";
echo '</thead>';
$count = 0;
foreach($results9 as $bot){
    if($count > 0 ) {
        echo "</tr>";
    }
    echo "<tr>";   
    echo "<td>";
    echo esc_attr($bot->botnickname);
    echo "</td>";
    echo "<td>";
    echo esc_attr($bot->botblocked);
    echo "</td>";    
    echo "</tr>";
       $count++;
}
echo "</table>"; 