<?php

/*
 * Including the sdk of php
 */
use Aspose\Cells\Cloud\Api\CellsApi;

require_once(__DIR__ . '/vendor/autoload.php');
$upload_dir = wp_upload_dir();

$excel_file = $post_params['excel_file_name'];
if(!isset($post_params['sheet_name']) || empty($post_params['sheet_name']) ) {
    $sheet_name = 'Sheet1';
}

$filename = $upload_dir['path'] . '/' . $post_params['excel_file_name'];

$ext = pathinfo($filename, PATHINFO_EXTENSION);

if($ext == 'xls' || $ext == 'xlsx') {

    $uploadpath = $upload_dir;
    $uploadpath = str_replace('/','\\',$uploadpath);
    $uploadpath = $uploadpath . '\\';
   
    $apiInstance = new CellsApi($ape_sid,$ape_key,"v3.0","https://api.aspose.cloud");
    $folder = "Temp"; // string | 
    $storage_name = null; // string | storage name.

    $uploadpath = str_replace("\\","/",$uploadpath);
    $uploadFile = $filename;
   
    try{
    $result = $apiInstance->uploadFile($folder, $uploadFile, $storage_name);
    if(empty($result['Errors'])) {

        $total_cols = $apiInstance->cellsGetWorksheetColumns($excel_file, $sheet_name, $folder, $storage_name)->getColumns()->getMaxColumn() + 1;
        $total_rows = $apiInstance->cellsGetWorksheetRows($excel_file, $sheet_name, $folder, $storage_name)->getRows()->getRowsCount();
        $headings = array();
        for($i = 0; $i <= $total_cols; $i++) {

            $cell = generateAlphabet($i) . '1';
            $result = $apiInstance->cellsGetWorksheetCell($excel_file, $sheet_name, $cell, $folder, $storage_name);
            $result = json_decode(json_encode($result), true);
            $headings[] = $result['Cell']['Value'] ;
        }

        $ser_head = serialize($headings);

        global $wpdb;
        //$sql = " DELETE FROM " .$wpdb->prefix."apc_shortcodes WHERE `filename` = '$excel_file'; ";
        //$wpdb->query($sql);
        if($wpdb->delete($wpdb->prefix."apc_shortcodes", array('filename' => $excel_file)) === false){
            echo "<br>Can not delete file info from Database";
        }
        //$querystr = "
        //    INSERT INTO " .$wpdb->prefix."apc_shortcodes SET `filename` = '$excel_file', `head_row` = '$ser_head'
         //";
        //$wpdb->query($querystr);
        
        
        $id = $wpdb->get_var( 'SELECT id FROM ' . $wpdb->prefix.'apc_shortcodes' . ' ORDER BY id DESC LIMIT 1');
        
        if($wpdb->insert($wpdb->prefix."apc_shortcodes", array('id'=>$id+1, 'filename'=>$excel_file, 'head_row' => $ser_head )) === false){
            echo "<br>Can not insert the file info to Database";
        }
    } else {
        echo "<br>File upload failed:" . $result['Errors']['Message'];
    }
} catch (Exception $ex){
    echo '<br>Exception:'. $ex->getMessage() ;
}
} else {
    echo "<br>Wrong File was selected!";
}