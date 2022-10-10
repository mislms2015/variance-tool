<?php include_once '../layout/sub-head.php'; ?>
<style>
<?php include_once '../metro/css/style.css'; ?>
</style>
<?php
include_once "./nav.php";
include_once '../config/conn.php';
include_once '../config/__utils.php';
require_once "../Classes/PHPExcel.php";
?>

<title>Import: Gigalife Summary</title>

<div class="container-fluid">
    <div class="grid">
        <div class="row mt-10">
            <div class="stub">
                <?= nav($conn, 'import_gigalife_summary', 'active'); ?>
            </div>

            <div class="cell">
                <?php
                $header = gigalifeHeader();
                
                if (isset($_POST['submit'])) {

                    // Allowed mime types
                    $fileMimes = fileMimesExcel();
                    $files = $_FILES['file'];

                    // Validate whether selected file is a CSV file
                    if (!empty($files)) {

                        for ($i = 0; $i < count($files['name']); $i++) {
                            $mrn = array();
                            $rowArray = array();
                            $filename = $files['name'][$i];
                            if (in_array($files['type'][$i], $fileMimes)) {
                                $path = $files['tmp_name'][$i];
                
                                $reader = PHPExcel_IOFactory::createReaderForFile($path);
                                $excel_Obj = $reader->load($path);
                                
                                //Get the last sheet in excel
                                $worksheet=$excel_Obj->getActiveSheet();
                                
                                //Get the first sheet in excel
                                $worksheet = $excel_Obj->getSheet('0');
                                
                                $lastRow = $worksheet->getHighestRow();
                                $colomncount = $worksheet->getHighestDataColumn();
                                $colomncount_number = PHPExcel_Cell::columnIndexFromString($colomncount);

                                //get main header
                                $temp_header = array();
                                for($row = 1; $row <= 1; $row++) {
                                    for($col = 0; $col <= $colomncount_number; $col++) {
                                            array_push($temp_header, $worksheet->getCell(PHPExcel_Cell::stringFromColumnIndex($col).$row)->getValue());
                                    }
                                }

                                $header_compare = array_diff($header,$temp_header);

                                //check file if uploaded
                                $check_file = mysqli_query($conn, "SELECT * FROM file_uploaded WHERE file_name = '". $filename ."'");

                                if (count($header_compare) > 0) {
                                    $err_msg = "<b><i>$filename header not match.</i></b>";
                                } else if (mysqli_num_rows($check_file) > 0) {
                                    $err_msg = "<b><i>$filename file already uploaded.</i></b>";
                                } else {
                                    $err_msg = '';
                                    $target_dir = "../storage/gigalife/";
                                    $target_file = $target_dir . basename($files['name'][$i]);
                                    move_uploaded_file($files['tmp_name'][$i], $target_file);
                                }

                                //try to insert storing of data here
                                if (count($header_compare) == 0 && mysqli_num_rows($check_file) == 0) {
                                    $temp_file = substr($filename, 0, strpos($filename, "."));
                                    $gigalife_date_temp = substr($temp_file, -8);

                                    $upload_file = "INSERT INTO file_uploaded (file_type, file_name, banner) VALUES ('gigalife', '".$files['name'][$i]. "', '" .$gigalife_date_temp. "')";
                                    
                                    $file_upload_id = '';
                                    if ($conn->query($upload_file) === TRUE) {
                                        $last_id = $conn->insert_id;
                                        $file_upload_id = $last_id;
                                    } else {
                                        $file_upload_id = '999999999';
                                    }

                                    //store data to array for batch insert: start
                                    $counter = 0;
                                    for($row = 3; $row <= $lastRow; $row++) {
                                        $counter++;
                                        $colArray = array();
                                        for($col = 0; $col < $colomncount_number; $col++) {
                                            array_push($colArray, $worksheet->getCell(PHPExcel_Cell::stringFromColumnIndex($col).$row)->getValue());
                                        }
                                        //column count as number. eg.: 0 => A, 1 => B, 2 => C...
                                        $gateway_reference_no = trim($worksheet->getCell(PHPExcel_Cell::stringFromColumnIndex(4).$row)->getValue());
                                        $paymaya_mrn = trim($worksheet->getCell(PHPExcel_Cell::stringFromColumnIndex(21).$row)->getValue());
                                        $iload_rn = trim($worksheet->getCell(PHPExcel_Cell::stringFromColumnIndex(22).$row)->getValue());
                                        $ern_rrn = trim($worksheet->getCell(PHPExcel_Cell::stringFromColumnIndex(23).$row)->getValue());
                                        $remarks = trim($worksheet->getCell(PHPExcel_Cell::stringFromColumnIndex(25).$row)->getValue());

                                        //insert here for the temp table: start
                                        if ($paymaya_mrn != 'Not Found') {
                                            $id = $paymaya_mrn;
                                        } else if ($paymaya_mrn == 'Not Found' && $iload_rn != 'Not Found') {
                                            $explode_iload_rn = explode(' | ', $iload_rn);
                                
                                            $id = $explode_iload_rn[0];
                                        } else if ($paymaya_mrn == 'Not Found' && $iload_rn == 'Not Found') {
                                            $explode_ern_rrn = explode(' | ', $ern_rrn);
                                
                                            $id = $explode_ern_rrn[0];
                                        }

                                        $check_pb = substr($id, 0, 2);

                                        if ($check_pb == 'PB') {
                                            $tagging = 'Not Subject for Refund';
                                        } else {
                                            $tagging = '';
                                        }

                                        array_push($mrn, array($id, $gateway_reference_no, $remarks, $tagging));
                                        //insert here for the temp table: end

                                        array_unshift($colArray, $file_upload_id);
                                        array_push($rowArray, $colArray);
                                    }
                                    //store data to array for batch insert: end

                                    //insert generated giga mrn here:start
                                    $query = "INSERT INTO raw_gigalife_formatted (mrns, gateway_reference_no, remarks, tagging) VALUES (?, ?, ?, ?)";
                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param("ssss", $gateway_reference_no_formatted, $mrn_formatted, $remarks_formatted, $tagged);

                                    $conn->query("START TRANSACTION");
                                    foreach ($mrn as $res) {
                                        $gateway_reference_no_formatted = $res[0];
                                        $mrn_formatted = $res[1];
                                        $remarks_formatted = $res[2];
                                        $tagged = $res[3];
                                        $stmt->execute();
                                    }
                                    $stmt->close();
                                    $conn->query("COMMIT");
                                    //insert generated giga mrn here: end

                                    //insert gigalife summary here:start
                                    $query = "INSERT INTO raw_gigalife (id, mid, transaction_type, merchant_reference_number, settlement_amount, gateway_reference_no, masked_card_number, auth_code, transaction_reference_no, blank_one, elp_reference_number, merchant_reference_no, original_currency_amount, payment_reference_no, multisys_status, blank_two, blank_three, reference_nummber, amount, iload_status, blank_four, variance_no, paymaya_mrn, iload_rn, ern_rrn, action, remarks) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param("sssssssssssssssssssssssssss", $upload_id, $mid, $transaction_type, $merchant_reference_number, $settlement_amount, $gateway_reference_no, $masked_card_number, $auth_code, $transaction_reference_no, $blank_one, $elp_reference_number, $merchant_reference_no, $original_currency_amount, $payment_reference_no, $multisys_status, $blank_two, $blank_three, $reference_nummber, $amount, $iload_status, $blank_four, $variance_no, $paymaya_mrn_ins, $iload_rn, $ern_rrn_ins, $action, $remarks_ins);

                                    $conn->query("START TRANSACTION");
                                    foreach ($rowArray as $res) {
                                        $upload_id = trim($res[0]);
                                        $mid = trim($res[1]);
                                        $transaction_type = trim($res[2]);
                                        $merchant_reference_number = trim($res[3]);
                                        $settlement_amount = trim($res[4]);
                                        $gateway_reference_no = trim($res[5]);
                                        $masked_card_number = trim($res[6]);
                                        $auth_code = trim($res[7]);
                                        $transaction_reference_no = trim($res[8]);
                                        $blank_one = trim($res[9]);
                                        $elp_reference_number = trim($res[10]);
                                        $merchant_reference_no = trim($res[11]);
                                        $original_currency_amount = trim($res[12]);
                                        $payment_reference_no = trim($res[13]);
                                        $multisys_status = trim($res[14]);
                                        $blank_two = trim($res[15]);
                                        $blank_three = trim($res[16]);
                                        $reference_nummber = trim($res[17]);
                                        $amount = trim($res[18]);
                                        $iload_status = trim($res[19]);
                                        $blank_four = trim($res[20]);
                                        $variance_no = trim($res[21]);
                                        $paymaya_mrn_ins = trim($res[22]);
                                        $iload_rn = trim($res[23]);
                                        $ern_rrn_ins = trim($res[24]);
                                        $action = trim($res[25]);
                                        $remarks_ins = trim($res[26]);
                                        $stmt->execute();
                                    }
                                    $stmt->close();
                                    $conn->query("COMMIT");
                                    //insert gigalife summary here: end

                                    echo "
                                        <div class='remark info'>
                                            <pre class='fg-green'><b><i>$filename</i></b> successfully imported. $counter rows inserted.</pre>
                                        </div>

                                        <audio autoplay>
                                            <source src='../asset/sound/chime.mp3'>
                                        </audio>
                                    ";
                                } else {
                                    echo "
                                        <div class='remark warning'>
                                            <pre class='fg-red'>$err_msg</pre>
                                        </div>
                                    ";
                                }
                            } else {
                                echo "
                                    <div class='remark warning'>
                                        <pre class='fg-red'>$filename invalid file.</pre>
                                    </div>
                                ";
                            }
                        }
                    } else {
                        echo "
                            <div class='remark warning'>
                                <pre class='fg-red'>No file selected.</pre>
                            </div>
                        ";
                    }
                
                }
                ?>
            </div>

        </div>
    </div>

</div>

<?php include_once '../layout/sub-footer.php'; ?>
<script src="../metro/js/script.js"></script>