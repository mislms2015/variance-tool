<?php include_once '../layout/sub-head.php'; ?>
<style>
<?php include_once '../metro/css/style.css'; ?>
</style>
<?php
include_once "./nav.php";
include_once '../config/conn.php';
include_once '../config/__utils.php';
?>

<title>Import: Gigapay Raw Logs</title>

<div class="container-fluid">
    <div class="grid">
        <div class="row mt-10">
            <div class="stub">
                <?= nav($conn, 'import_gigapay_raw_logs', 'active'); ?>
            </div>

            <div class="cell">

                <!-- Query logic: Start -->
                <?php
                $header = gigapayHeader();

                if (isset($_POST['submit'])) {
                
                    // Allowed mime types
                    $fileMimes = fileMimes();
                    $files = $_FILES['file'];
                
                    // Validate whether selected file is a CSV file
                    if (!empty($files)) {

                        for ($i = 0; $i < count($files['name']); $i++) {
                            $gigapay_raw_logs = array();
                            $filename = $files['name'][$i];
                            if (in_array($files['type'][$i], $fileMimes)) {
                                $err_msg = '';

                                // Open uploaded CSV file with read-only mode
                                $csvFile = fopen($files['tmp_name'][$i], 'r');

                                // Skip the first line
                                $numcols = fgetcsv($csvFile);

                                $temp_header = array();
                                for ($header_column = 0; $header_column < count($numcols); $header_column++) {
                                    array_push($temp_header, $numcols[$header_column]);
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
                                }
                    
                                if (count($header_compare) == 0 && mysqli_num_rows($check_file) == 0) {
                                    // insert filename to table for additional validation
                                    $temp_file = substr($filename, 0, strpos($filename, "."));
                                    $gigapay_date = substr($temp_file, -10);

                                    $upload_file = "INSERT INTO file_uploaded (file_type, file_name, banner) VALUES ('gigapay', '".$files['name'][$i]. "', '" .$gigapay_date. "')";
                                    
                                    $file_upload_id = '';
                                    if ($conn->query($upload_file) === TRUE) {
                                        $last_id = $conn->insert_id;
                                        $file_upload_id = $last_id;
                                    } else {
                                        $file_upload_id = '999999999';
                                    }

                                    // Parse data from CSV file line by line
                                    $counter = 0;
                                    while (($getData = fgetcsv($csvFile, 100000000, ",")) !== FALSE) {
                                        if ($getData[8] == 'GCASH') {
                                            $payment_method_tag = 'Gcash Transaction';
                                        } else {
                                            $payment_method_tag = '';
                                        }

                                        $created_at_temp = date_create($getData[41]);
                                        $updated_at_temp = date_create($getData[42]);

                                        array_push($gigapay_raw_logs, array($file_upload_id, $getData[0], $getData[1], $getData[2], $getData[3], $getData[4], $getData[5], $getData[6], $getData[7], $getData[8], $getData[9], $getData[10], $getData[11], $getData[12], $getData[13], $getData[14], $getData[15], $getData[16], $getData[17], $getData[18], $getData[19], $getData[20], $getData[21], $getData[22], $getData[23], $getData[24], $getData[25], $getData[26], $getData[27], $getData[28], $getData[29], $getData[30], $getData[31], $getData[32], $getData[33], $getData[34], $getData[35], $getData[36], $getData[37], $getData[38], $getData[39], $getData[40], $payment_method_tag, date_format($created_at_temp, "Y-m-d H:i:s"), date_format($updated_at_temp, "Y-m-d H:i:s")));

                                        $counter++;
                                    }

                                    // Close opened CSV file
                                    fclose($csvFile);

                                    //insert gigapay raw logs here:start
                                    $query = "INSERT INTO raw_logs_gigapay (id, file_id, status, transaction_digest, number, main_number, brand, transaction_date, transaction_type, payment_method, currency, amount, keyword, action, payment_reference_number, app_transaction_number, comment, is_payment_status_updated, authentication_status_origin, wallet_amount, wallet_fees, wallet_status, wallet_request_reference_no, wallet_merchant_value, wallet_payment_token_id, paymaya_checkout_id, paymaya_void_id, paymaya_void_reason, last_four, first_six, card_type, elp_transaction_number, elp_corporation_id, elp_branch_id, elp_request_reference_number, elp_plan_code, elp_amount, elp_retailer_deduct, elp_retailer_new_balance, elp_response_code, elp_response_description, elp_transaction_timestamp, payment_method_tagged, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param("sssssssssssssssssssssssssssssssssssssssssssss", $upload_id, $id, $status, $transaction_digest, $number, $main_number, $brand, $transaction_date, $transaction_type, $payment_method, $currency, $amount, $keyword, $action, $payment_reference_number, $app_transaction_number, $comment, $is_payment_status_updated, $authentication_status_origin, $wallet_amount, $wallet_fees, $wallet_status, $wallet_request_reference_no, $wallet_merchant_value, $wallet_payment_token_id, $paymaya_checkout_id, $paymaya_void_id, $paymaya_void_reason, $last_four, $first_six, $card_type, $elp_transaction_number, $elp_corporation_id, $elp_branch_id, $elp_request_reference_number, $elp_plan_code, $elp_amount, $elp_retailer_deduct, $elp_retailer_new_balance, $elp_response_code, $elp_response_description, $elp_transaction_timestamp, $payment_method_tagged, $created_at, $updated_at);

                                    $conn->query("START TRANSACTION");
                                    foreach ($gigapay_raw_logs as $res) {
                                        $upload_id = $res[0];
                                        $id = $res[1];
                                        $status = $res[2];
                                        $transaction_digest = $res[3];
                                        $number = $res[4];
                                        $main_number = $res[5];
                                        $brand = $res[6];
                                        $transaction_date = $res[7];
                                        $transaction_type = $res[8];
                                        $payment_method = $res[9];
                                        $currency = $res[10];
                                        $amount = $res[11];
                                        $keyword = $res[12];
                                        $action = $res[13];
                                        $payment_reference_number = $res[14];
                                        $app_transaction_number = $res[15];
                                        $comment = $res[16];
                                        $is_payment_status_updated = $res[17];
                                        $authentication_status_origin = $res[18];
                                        $wallet_amount = $res[19];
                                        $wallet_fees = $res[20];
                                        $wallet_status = $res[21];
                                        $wallet_request_reference_no = $res[22];
                                        $wallet_merchant_value = $res[23];
                                        $wallet_payment_token_id = $res[24];
                                        $paymaya_checkout_id = $res[25];
                                        $paymaya_void_id = $res[26];
                                        $paymaya_void_reason = $res[27];
                                        $last_four = $res[28];
                                        $first_six = $res[29];
                                        $card_type = $res[30];
                                        $elp_transaction_number = $res[31];
                                        $elp_corporation_id = $res[32];
                                        $elp_branch_id = $res[33];
                                        $elp_request_reference_number = $res[34];
                                        $elp_plan_code = $res[35];
                                        $elp_amount = $res[36];
                                        $elp_retailer_deduct = $res[37];
                                        $elp_retailer_new_balance = $res[38];
                                        $elp_response_code = $res[39];
                                        $elp_response_description = $res[40];
                                        $elp_transaction_timestamp = $res[41];
                                        $payment_method_tagged = $res[42];
                                        $created_at = $res[43];
                                        $updated_at = $res[44];
                                        $stmt->execute();
                                    }
                                    $stmt->close();
                                    $conn->query("COMMIT");
                                    //insert gigapay raw logs here: end

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
                    }
                    else {
                        echo "
                            <div class='remark warning'>
                                <pre class='fg-red'>No file selected.</pre>
                            </div>
                        ";
                    }
                }
                ?>
                <!-- Query logic: End -->
            </div>
            
        </div>
    </div>

</div>

<?php include_once '../layout/sub-footer.php'; ?>
<script src="../metro/js/script.js"></script>