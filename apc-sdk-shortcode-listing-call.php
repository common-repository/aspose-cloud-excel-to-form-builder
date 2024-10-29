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

$sheet_name = 'Sheet1';

$excel_file = $filename;

$ext = pathinfo($excel_file, PATHINFO_EXTENSION);


if($ext == 'xls' || $ext == 'xlsx') {

    try{
        $apiInstance = new CellsApi($ape_sid,$ape_key,"v3.0","https://api.aspose.cloud");
        $folder = "Temp"; // string | 
        $storage_name = null; // string | storage name.

        $cols = $apiInstance->cellsGetWorksheetColumns($excel_file, $sheet_name, $folder, $storage_name)->getColumns()->getMaxColumn();
        $rows = $apiInstance->cellsGetWorksheetRows($excel_file, $sheet_name, $folder, $storage_name)->getRows()->getRowsCount();
        $html_string = '<table cellspacing="10" cellpadding="10" width="100%">';

        for($row = 1; $row <= $rows; $row++){
            $html_string .= '<tr>';
            for($col = 0; $col <= $cols; $col++){
                $cell = generateAlphabet($col) . $row;

                $result = $apiInstance->cellsGetWorksheetCell($excel_file, $sheet_name, $cell, $folder, $storage_name);
                $result = json_decode(json_encode($result), true);
                $cell_value = $result['Cell']['Value'] ;

                if($row == '1') {
                    $html_string .= '<th>' . $cell_value . '</th>';
                } else {
                    $html_string .= '<td>' . $cell_value . '</td>';
                }
            }
            $html_string .= '</tr>';
        }
        $html_string .= '</table>';
        return $html_string;
        //echo "<pre>"; print_r($rows); exit;
    } catch (Exception $e) {
        return 'Exception: ' . $e->getMessage() . PHP_EOL;
    }
} else {
    return "Wrong File was selected!";
}