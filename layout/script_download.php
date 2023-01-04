<?php
include_once "../config/conn.php";
require_once '../Classes/PHPExcel/IOFactory.php';

$query_gigalife_filename = mysqli_query($conn, "SELECT * FROM file_uploaded WHERE file_type = 'gigalife' LIMIT 1");
$res = mysqli_fetch_object($query_gigalife_filename);

$file_name = $res->file_name;
$explode_filename = explode('.', $res->file_name);
$new_file_name = $explode_filename[0]. ' - Validated.' .$explode_filename[1];

$excel2 = PHPExcel_IOFactory::createReader('Excel2007');
$excel2 = $excel2->load('../storage/gigalife/'.$file_name); // Empty Sheet
$excel2->setActiveSheetIndex(0);

$query_gigalife_formatted = mysqli_query($conn, "SELECT * FROM raw_gigalife_formatted");

$z = 3;
while ($res_formmatted = mysqli_fetch_object($query_gigalife_formatted)) {
    $tag = $res_formmatted->tagging;
    $remarks = $res_formmatted->remarks;
    if ($remarks != '' && $tag == '') {
        $new_tag = 'Investigate Manually';        
    } else {
        $new_tag = $tag;
    }
    //$excel2->getActiveSheet()->setCellValue('Z'.$z, $res_formmatted->tagging);
    $excel2->getActiveSheet()->setCellValue('Z'.$z, $new_tag);
    $z++;
}

$objWriter = PHPExcel_IOFactory::createWriter($excel2, 'Excel2007');
$objWriter->save('../storage/gigalife-validated/'.$new_file_name);

echo json_encode(
    array(
        "res" => "success",
        "file" => "./storage/gigalife-validated/$new_file_name"
        )
);

?>