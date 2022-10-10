<?php include_once '../layout/sub-head.php'; ?>
<style>
<?php include_once '../metro/css/style.css'; ?>
</style>
<?php
include_once "./nav.php";
include_once '../config/conn.php';
include_once '../config/__utils.php';
?>

<title>Import: ELP Raw Logs</title>

<div class="container-fluid">
    <div class="grid">
        <div class="row mt-10">
            <div class="stub">
                <?= nav($conn, 'import_elp_raw_logs', 'active'); ?>
            </div>

            <div class="cell">
                <?php
                $header = elpHeader();

                if (isset($_POST['submit'])) {
                
                    // Allowed mime types
                    $fileMimes = fileMimes();
                    $files = $_FILES['file'];
                
                    // Validate whether selected file is a CSV file
                    if (!empty($files)) {

                        for ($i = 0; $i < count($files['name']); $i++) {
                            $elp_raw_logs = array();
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
                                    $elp_date = mb_substr($filename, 0, 10);

                                    //mysqli_query($conn, "INSERT INTO file_uploaded (file_type, file_name) VALUES ('elp', '".$files['name'][$i]."')");
                                    $upload_file = "INSERT INTO file_uploaded (file_type, file_name, banner) VALUES ('elp', '" .$files['name'][$i]. "', '" .$elp_date. "')";
                                    
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
                                        $created_at_temp = date_create($getData[15]);
                                        $updated_at_temp = date_create($getData[16]);
                                        
                                        array_push($elp_raw_logs, array($file_upload_id, $getData[0], $getData[1], $getData[2], $getData[3], $getData[4], $getData[5], $getData[6], $getData[7], $getData[8], $getData[9], $getData[10], $getData[11], $getData[12], $getData[13], $getData[14], date_format($created_at_temp, "Y-m-d H:i:s"), date_format($updated_at_temp, "Y-m-d H:i:s")));

                                        $counter++;
                                    }

                                    // Close opened CSV file
                                    fclose($csvFile);
 

                                    //insert gigapay raw logs here:start
                                    $query = "INSERT INTO raw_logs_elp (id, file_id, type, number, corporate_id, branch_id, request_reference_number, plan_code, amount, retailer_deduct, retailer_new_balance, response_code, response_description, transaction_request_reference_number, transaction_timestamp, body, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param("ssssssssssssssssss", $upload_id, $id, $type, $number, $corporate_id, $branch_id, $request_reference_number, $plan_code, $amount, $retailer_deduct, $retailer_new_balance, $response_code, $response_description, $transaction_request_reference_number, $transaction_timestamp, $body, $created_at, $updated_at);

                                    $conn->query("START TRANSACTION");
                                    foreach ($elp_raw_logs as $res) {
                                        $upload_id = $res[0];
                                        $id = $res[1];
                                        $type = $res[2];
                                        $number = $res[3];
                                        $corporate_id = $res[4];
                                        $branch_id = $res[5];
                                        $request_reference_number = $res[6];
                                        $plan_code = $res[7];
                                        $amount = $res[8];
                                        $retailer_deduct = $res[9];
                                        $retailer_new_balance = $res[10];
                                        $response_code = $res[11];
                                        $response_description = $res[12];
                                        $transaction_request_reference_number = $res[13];
                                        $transaction_timestamp = $res[14];
                                        $body = $res[15];
                                        $created_at = $res[16];
                                        $updated_at = $res[17];
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