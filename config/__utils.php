<?php
// this method is to display popover
function popOver($description) {
    return "data-role='popover' data-popover-text='$description' data-popover-hide='1500'";
}

// this method is to validate filemimes type
function fileMimes() {
    return array(
        'text/x-comma-separated-values',
        'text/comma-separated-values',
        'application/octet-stream',
        'application/vnd.ms-excel',
        'application/x-csv',
        'text/x-csv',
        'text/csv',
        'application/csv',
        'application/excel',
        'application/vnd.msexcel',
        'text/plain',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    );
}

// this is a specific declaration of filemimes for excel
function fileMimesExcel() {
    return array(
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    );
}

// this method is a header validation for gigapay
function gigapayHeader() {
    return array('id', 'status', 'transaction_digest', 'number', 'main_number', 'brand', 'transaction_date', 'transaction_type', 'payment_method', 'currency', 'amount', 'keyword', 'action', 'payment_reference_number', 'app_transaction_number', 'comment', 'is_payment_status_updated', 'authentication_status_origin', 'wallet_amount', 'wallet_fees', 'wallet_status', 'wallet_request_reference_no', 'wallet_merchant_value', 'wallet_payment_token_id', 'paymaya_checkout_id', 'paymaya_void_id', 'paymaya_void_reason', 'last_four', 'first_six', 'card_type', 'elp_transaction_number', 'elp_corporation_id', 'elp_branch_id', 'elp_request_reference_number', 'elp_plan_code', 'elp_amount', 'elp_retailer_deduct', 'elp_retailer_new_balance', 'elp_response_code', 'elp_response_description', 'elp_transaction_timestamp', 'created_at', 'updated_at');
}

// this method is a header validation for elp
function elpHeader() {
    return array('id', 'type', 'number', 'corporate_id', 'branch_id', 'request_reference_number', 'plan_code', 'amount', 'retailer_deduct', 'retailer_new_balance', 'response_code', 'response_description', 'transaction_request_reference_number', 'transaction_timestamp', 'body', 'created_at', 'updated_at');
}

//this method is a header validation for splunk
function splunkHeader() {
    return array('_time', 'id', 'processor_ref_no', 'app_transaction_number', 'state');
}


//this method is a header validation for gigalife
function gigalifeHeader() {
    return array('Paymaya', '', '', '', '', '', '', '', '', 'MultiSys', '', '', '', '', '', '', 'Iload', '', '', '', 'Reconciliation Results', '', '', '', '', 'Investigation Result', '');
}

?>