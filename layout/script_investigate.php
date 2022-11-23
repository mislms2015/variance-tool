<?php
include_once "../config/conn.php";

$mrns = array();
$mrns_final = array();
$mrn_update = array();
$mrns_update_final = array();

####tagging all the gcash transaction
$investigate = mysqli_query($conn, "SELECT * FROM raw_gigalife_formatted WHERE remarks <> '' AND tagging = ''");

while ($row = mysqli_fetch_object($investigate)) {
    array_push($mrns, array($row->id, $row->mrns));
}

$stmt = $conn->prepare("SELECT
payment_method, app_transaction_number, elp_transaction_number, payment_method_tagged
FROM
raw_logs_gigapay
    WHERE
    payment_method = ?
    AND
    (app_transaction_number = ?
    OR
    elp_transaction_number = ?)");
$stmt->bind_param('sss', $payment, $mrn_app, $mrn_elp);

foreach($mrns as $mrn):
    $payment = 'GCASH';
    $mrn_app = $mrn[1];
    $mrn_elp = $mrn[1];
    $stmt->execute();

    $stmt->bind_result($col1, $col2, $col3, $col4);

    while ($stmt->fetch()) {
        array_push($mrn_update, array($col4, $mrn[0]));
    }
    
endforeach;

$query = "UPDATE raw_gigalife_formatted SET tagging = ? WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("si", $mrn_tag, $mrn_id);

$conn->query("START TRANSACTION");
foreach ($mrn_update as $res) {
    $mrn_tag = $res[0];
    $mrn_id = $res[1];
    $stmt->execute();
}
$stmt->close();
$conn->query("COMMIT");


####tagging the official investigation
$investigate_final = mysqli_query($conn, "SELECT * FROM raw_gigalife_formatted WHERE remarks <> '' AND tagging = ''");

while ($row = mysqli_fetch_object($investigate_final)) {
    array_push($mrns_final, array($row->id, $row->mrns, $row->gateway_reference_no));
}

$stmt = $conn->prepare("SELECT
raw_logs_gigapay.status AS 'Gigapay Status',
raw_logs_gigapay.app_transaction_number AS 'Gigapay App Transaction Number',
raw_logs_gigapay.elp_transaction_number AS 'Gigapay ELP Transaction Number',
raw_logs_elp.type AS 'ELP Type',
raw_logs_elp.response_description AS 'ELP Response Description',
raw_logs_splunk.app_transaction_number AS 'Splunk App Transaction Number',
raw_logs_splunk.file_id AS 'Splunk Gateway',
raw_logs_splunk.state AS 'Splunk State'
FROM
raw_logs_gigapay
    LEFT JOIN
raw_logs_elp ON raw_logs_gigapay.elp_transaction_number = raw_logs_elp.transaction_request_reference_number AND raw_logs_gigapay.elp_transaction_number <> ''
    LEFT JOIN
raw_logs_splunk ON raw_logs_gigapay.app_transaction_number = raw_logs_splunk.app_transaction_number
WHERE
raw_logs_gigapay.app_transaction_number = ?
OR raw_logs_gigapay.elp_transaction_number = ?");
$stmt->bind_param("ss", $mrn_app_final, $mrn_elp_final);

function getTag($giga_status, $splunk_state) {
    $giga_statuses = array('FOR_VERIFICATION', 'WALLET_PENDING', 'VERIFICATION_SUCCESSFUL', 'FOR_AUTHENTICATION');
    $splunk_statuses = array('PAYMENT_FAILED', 'AUTH_FAILED', 'PAYMENT_EXPIRED', 'PAYMENT_CANCELLED', 'FOR_AUTHENTICATION', 'VOIDED');

    if ($giga_status == 'ELP_SUCCESSFUL') {
        $tagging = 'Elp_Successful';
    } else if (in_array(trim($giga_status), $giga_statuses) && trim($splunk_state) == 'PAYMENT_SUCCESS') {
            $tagging = 'Failed - For Refund';
    } else if (in_array(trim($giga_status), $giga_statuses) && in_array(trim($splunk_state), $splunk_statuses)) {
            $tagging = 'Not Subject for refund';
    } else if (in_array(trim($giga_status), $giga_statuses) && trim($splunk_state) == '' ) {
            $tagging = 'Email to NOC';
    } else {
        $tagging = '---';
    }

    return $tagging;
}

foreach($mrns_final as $mrn):
    $mrn_app_final = $mrn[1];
    $mrn_elp_final = $mrn[1];
    $stmt->execute();

    $stmt->store_result();
    $number_rows = $stmt->num_rows;

    $stmt->bind_result($gigapay_status, $gigapay_app_trans, $gigapay_elp_trans, $elp_type, $elp_response_desc, $splunk_app_trans, $splunk_gateway, $splunk_state);

    if ($number_rows > 0) {
        while ($stmt->fetch()) {
            
            $tagging = getTag($gigapay_status, $splunk_state);

            if ($number_rows > 1) {
                //validate here if more than 1 return, check if splunk gateway is match
                if (trim($mrn[2]) == trim($splunk_gateway)) {
                    array_push($mrns_update_final, array($mrn[0], $tagging));
                } 
                // issue raise here when multiple splunk, override to last response of tagging
                // } else {
                //     // if not get the prope response
                //     array_push($mrns_update_final, array($mrn[0], $tagging));
                // }
            } else if ($number_rows == 1) {
                    array_push($mrns_update_final, array($mrn[0], $tagging));
            }
        }
    } else {
        $mrn_elp = $mrn[1];
        $query_elp = mysqli_query($conn, "SELECT * FROM raw_logs_elp WHERE transaction_request_reference_number = '$mrn_elp' OR body LIKE '%$mrn_elp%' LIMIT 1");
        if (mysqli_num_rows($query_elp) > 0) {
            $elp_res = mysqli_fetch_object($query_elp);
            $elp_min = $elp_res->number;
            $elp_updated_at = $elp_res->updated_at;

            $query_gigapay = mysqli_query($conn, "SELECT * FROM raw_logs_gigapay WHERE number = '$elp_min' ORDER BY ABS(TIMESTAMPDIFF(SECOND, updated_at, '$elp_updated_at')) LIMIT 1");
            $gigapay_res = mysqli_fetch_object($query_gigapay);
            $gigapay_res_status = $gigapay_res->status;
            $gigapay_payment_method = $gigapay_res->payment_method;
            $gigapay_payment_reference_number = $gigapay_res->payment_reference_number;
            //add paymaya_checkout_id here for || validation except payment_reference_number
            $gigapay_app_transaction_number = $gigapay_res->app_transaction_number;

            //check if gcash
            if ($gigapay_payment_method == 'GCASH') {
                array_push($mrns_update_final, array($mrn[0], 'Gcash Transaction'));
            } else {
                //check proper tagging
                $query_splunk = mysqli_query($conn, "SELECT * FROM raw_logs_splunk WHERE app_transaction_number = '$gigapay_payment_reference_number' OR app_transaction_number = '$gigapay_app_transaction_number' LIMIT 1");
                $splunk_res = mysqli_fetch_object($query_splunk);
                $splunk_res_state = $splunk_res->state;

                $tagging = getTag($gigapay_res_status, $splunk_res_state);
                array_push($mrns_update_final, array($mrn[0], $tagging));
            }
        } else {
            array_push($mrns_update_final, array($mrn[0], 'For escalation to L3'));
        }
    }
endforeach;

$query_final = "UPDATE raw_gigalife_formatted SET tagging = ? WHERE id = ?";
$stmt_final = $conn->prepare($query_final);
$stmt_final->bind_param("si", $mrn_tag_final, $mrn_id_final);

$conn->query("START TRANSACTION");
foreach ($mrns_update_final as $res_final) {
    $mrn_id_final = $res_final[0];
    $mrn_tag_final = $res_final[1];
    $stmt_final->execute();
}
$stmt_final->close();
$conn->query("COMMIT");

echo 'success';
?>