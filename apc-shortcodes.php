<?php

function generateAlphabet($na) {
    $sa = "";
    while ($na >= 0) {
        $sa = chr($na % 26 + 65) . $sa;
        $na = floor($na / 26) - 1;
    }
    return $sa;
}

function apc_shortcode( $atts) {

    $atts = shortcode_atts( array(
        'action' => 'list',
        'filename' => ''
    ), $atts, 'apc' );


    $filename = $atts['filename'];
    $action =  $atts['action'];

    global $wpdb;
    if(!empty($filename) && $action == 'entry'){

        if(isset($_POST) && isset($_POST['apc-submit']) && !empty($_POST['apc-submit'])) {

            include_once('apc-sdk-shortcode-call.php');
        }

        $sql_query = "SELECT head_row FROM ".$wpdb->prefix."apc_shortcodes WHERE `filename` = '".$filename."' ";
        $sql_result = $wpdb->get_results($sql_query);
        $html_string = '<form method="post"><table cellspacing="10" cellpadding="10">';
        $html_string .='<input type="hidden" name="filename" value = "'.$filename.'" />';
        if(is_array($sql_result) && count($sql_result) > 0 ) {
            foreach($sql_result as $sql_res){
                $head_row_array = unserialize($sql_res->head_row);
                foreach($head_row_array as $key=>$head){
                    $html_string .='<tr>';

                    $html_string .='<td><label for ="col_'.$key.'"> '.$head.' </label></td>';
                    $html_string .='<td> <input type="text" name="col[]" id="col_'.$key.'" placeholder="Enter '.$head.'" /> </td> ';
                    $html_string .='</tr>';
                }
            }
            $html_string .='<tr><td colspan="2" style="text-align: right;"><input type="submit" name="apc-submit" class="button" value = "Save" /></td></tr>';
            $html_string .='</table></form>';

            return $html_string;
        } else {
            return "Invalid File.";
        }


    } else {
        if($action == "list" && !empty($filename)) {

            return include_once('apc-sdk-shortcode-listing-call.php');
        } else {
            return 'Invalid File Name.';
        }
    }

}

add_shortcode( 'apc', 'apc_shortcode' );

