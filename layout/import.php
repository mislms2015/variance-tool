<?php
include_once '../layout/sub-head.php';
include_once '../config/import_template.php';
include_once '../config/conn.php';

$reference = $_GET['ref'];

if ($reference == 'gigapay') {
    echo import($conn, 'Gigapay Raw Logs', 'import_gigapay_raw_logs', 'import_gigapay_raw_logs_query.php', 'Import Gigapay raw logs.', 'active');
} else if ($reference == 'elp') {
    echo import($conn, 'ELP Raw Logs', 'import_elp_raw_logs', 'import_elp_raw_logs_query.php', 'Import ELP raw logs.', 'active');
} else if ($reference == 'splunk') {
    echo import($conn, 'Splunk', 'import_splunk', 'import_splunk_query.php', 'Import Splunk.', 'active');
} else if ($reference == 'gigalife') {
    echo import($conn, 'Gigalife Summary', 'import_gigalife_summary', 'import_gigalife_summary_query.php', 'Import Gigalife Summary.', 'active');
} else {
    header('Location: ./error.php');
}

include_once '../layout/sub-footer.php'; ?>
<script src="../metro/js/script.js"></script>