<?php

/*
 * Including the sdk of php
 */

use Aspose\Cells\Cloud\Api\CellsApi;

require_once(__DIR__ . '/vendor/autoload.php');

$ape_sid = get_option('aspose-cloud-app-sid');
$ape_key = get_option('aspose-cloud-app-key');

if(empty($ape_sid) || empty($ape_key)) {
    return '<div><h2 style="color: red">Please enter Aspose SID and Key on plugin settings page.</h2></div>';
    //   return;
}

/*
 * Assign Base Product URL
 */
$apiInstance = new CellsApi($ape_sid,$ape_key,"v3.0","https://api.aspose.cloud");
$folder = "Temp"; // string | 
$storage_name = null; // string | storage name.

$post_params = $_POST;
//echo "<pre>"; print_r($post_params); exit;
$excel_file = $post_params['excel_file_name'];
if(!isset($post_params['sheet_name']) || empty($post_params['sheet_name']) ) {
    $sheet_name = 'Sheet1';
}

$excel_file = $post_params['filename'];

$ext = pathinfo($excel_file, PATHINFO_EXTENSION);


if($ext == 'xls' || $ext == 'xlsx') {

    if(isset($post_params['col']) && is_array($post_params['col'])){
        //$total_rows = $func->getRowsCount(0,10000);
        $total_rows = $apiInstance->cellsGetWorksheetRows($excel_file, $sheet_name, $folder, $storage_name)->getRows()->getRowsCount();
        $next_row = $total_rows + 1;
        foreach($post_params['col'] as $key=>$value){
            $cell = generateAlphabet($key) . $next_row;
            $apiInstance->cellsPostWorksheetCellSetValue($excel_file, $sheet_name, $cell, $value, 'string', '', $folder, $storage_name);
        }
        echo 'Record has been updated!';
    }
} else {
    echo "Wrong File was selected!";
}