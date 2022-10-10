<?php include_once '../layout/sub-head.php'; ?>
<style>
<?php include_once '../metro/css/style.css'; ?>
</style>
<?php
include_once "./nav.php";
include_once '../config/conn.php';
?>

<style>
    table, tr, th, td {
        border: 1px solid white !important;
    }

    th {
        color: white !important;
    }

    table {
        font-size: 11px !important;
    }

    .update-tag {
        border: 1px solid white;
        padding: 3px;
    }
</style>

<title>Manual Investigation</title>

<div class="container-fluid">
    <div class="grid">
        <div class="row mt-10">
            <div class="stub">
                <ul class='v-menu'>
                    <li class='menu-title'>General</li>
                    <li><a href='../'><span class='mif-home icon'></span>Home</a></li>
                    <?php if ($_GET['type'] == 'unknown_tag') { ?>
                        <li><a href='./unknown_tag.php'><span class='mif-backspace icon'></span>Back</a></li>
                    <?php } else if ($_GET['type'] == 'noc') { ?>
                        <li><a href='./mail_to_noc.php'><span class='mif-backspace icon'></span>Back</a></li>
                    <?php } ?>
                </ul>
            </div>

            <div class="cell">
            
                <table class="table sub-compact row-hover row-border">
                    <thead>
                    <tr>
                        <th colspan=10><i>MRN Hits</i></th>
                    </tr>
                    <tr>
                        <th>MRN / RN</th>
                        <th>Gigapay Status</th>
                        <th>Gigapay App Transaction Number</th>
                        <th>Gigapay ELP Transaction Number</th>
                        <th>ELP Type</th>
                        <th>ELP Response Description</th>
                        <th>Splunk App Transaction Number</th>
                        <th>Splunk Gateway</th>
                        <th>Splunk State</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $mrn = $_GET['mrn'];
                    $query = "SELECT
                    raw_logs_gigapay.status AS 'gigapay_status',
                    raw_logs_gigapay.app_transaction_number AS 'gigapay_app_transaction_numberr',
                    raw_logs_gigapay.elp_transaction_number AS 'gigapay_elp_transaction_number',
                    raw_logs_gigapay.created_at AS 'gigapay_created_at',
                    raw_logs_gigapay.updated_at AS 'gigapay_updated_at',
                    raw_logs_elp.type AS 'elp_type',
                    raw_logs_elp.response_description AS 'elp_response_description',
                    raw_logs_elp.created_at AS 'elp_created_at',
                    raw_logs_elp.updated_at AS 'elp_updated_at',
                    raw_logs_splunk._time AS 'splunk_time',
                    raw_logs_splunk.app_transaction_number AS 'splunk_app_transaction_number',
                    raw_logs_splunk.file_id AS 'splunk_gateway',
                    raw_logs_splunk.state AS 'splunk_state'
                    FROM
                    raw_logs_gigapay
                        LEFT JOIN
                    raw_logs_elp ON raw_logs_gigapay.elp_transaction_number = raw_logs_elp.transaction_request_reference_number AND raw_logs_gigapay.elp_transaction_number <> ''
                        LEFT JOIN
                    raw_logs_splunk ON raw_logs_gigapay.app_transaction_number = raw_logs_splunk.app_transaction_number
                    WHERE
                    raw_logs_gigapay.app_transaction_number = '$mrn'
                    OR raw_logs_gigapay.elp_transaction_number = '$mrn'";
                    $res = mysqli_query($conn, $query);
                    while ($row = mysqli_fetch_object($res)) {
                    ?>
                    <tr>
                        <td><b><?=$mrn?></b></td>
                        <td>
                            <b><?=$row->gigapay_status?></b>
                            <br /><br />
                            <i><?=$row->gigapay_created_at?></i>
                            <br />
                            <i><?=$row->gigapay_updated_at?></i>
                        </td>
                        <td><b><?=$row->gigapay_app_transaction_numberr?></b></td>
                        <td><b><?=$row->gigapay_elp_transaction_number?></b></td>
                        <td><b><?=$row->elp_type?></b></td>
                        <td>
                            <b><?=$row->elp_response_description?></b>
                            <br /><br />
                            <i><?=$row->elp_created_at?></i>
                            <br />
                            <i><?=$row->elp_updated_at?></i>
                        </td>
                        <td><b><?=$row->splunk_app_transaction_number?></b></td>
                        <td><b><?=$row->splunk_gateway?></b></td>
                        <td>
                            <b><?=$row->splunk_state?></b>
                            <br /><br />
                            <i><?=$row->splunk_time?></i>
                        </td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>

                <form action="./manual_tag.php" method="post" class="update-tag pos-top-center mt-15">
                    <div class="form-group">
                        <input type="hidden" name="mrn_id" value="<?=$_GET['id']?>">
                        <input type="hidden" name="type" value="<?=$_GET['type']?>">
                        <select data-role="select" name="manual_tag"
                                data-cls-option="fg-cyan"
                                data-cls-selected-item="bg-teal fg-white"
                                data-cls-selected-item-remover="bg-darkTeal fg-white">
                            <option value="">Select Proper Tagging</option>
                            <option value="Elp_Successful" data-template="<span class='mif-verified icon'></span> $1">ELP Successful</option>
                            <option value="Gcash Transaction" data-template="<span class='mif-discover icon'></span> $1">Gcash Transaction</option>
                            <option value="Failed - For Refund" data-template="<span class='mif-money icon'></span> $1">Failed - For Refund</option>
                            <option value="Not Subject for refund" data-template="<span class='mif-uninstall icon'></span> $1">Not Subject for refund</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button class="button rounded success" name="submit_manual_tag">Submit Tag</button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div>

<?php include_once '../layout/sub-footer.php'; ?>
<script src="../metro/js/script.js"></script>