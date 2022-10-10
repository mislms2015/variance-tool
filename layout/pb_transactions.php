<?php include_once '../layout/sub-head.php'; ?>
<style>
<?php include_once '../metro/css/style.css'; ?>
</style>
<?php
include_once "./nav.php";
include_once '../config/conn.php';
?>

<script>
function runToast(msg, indicator) {
    var toast = Metro.toast.create;
    toast(msg, null, 1500, indicator);
}
</script>

<title>PB Transactions</title>

<div class="container-fluid">
    <div class="grid">
        <div class="row mt-10">
            <div class="stub">
                <?= nav($conn, 'err', 'err'); ?>
            </div>

            <div class="cell">
            
                <table class="table row-hover row-border">
                    <thead>
                    <tr>
                        <th style="color: white;">PB Transaction<i>(s)</i></th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <button id="get-query" class="button rounded success">
                                    <span class="mif-copy ani-hover-horizontal"> Get Query</span>
                                </button>
                            </td>
                        </tr>
                    <?php
                    $query = "SELECT * FROM raw_gigalife_formatted WHERE mrns LIKE 'PB%'";
                    $res = mysqli_query($conn, $query);
                    $list = '';
                    while ($row = mysqli_fetch_object($res)) {
                        $list .= "'$row->mrns', ";
                    ?>
                    <tr>
                        <td><?=$row->mrns?></td>
                    </tr>
                    <?php } ?>
                    <?php
                    $list = substr($list, 0, -2);
                    $query_string = "SELECT number AS MIN, account_number AS 'Account Number', app_transaction_number AS 'App Transaction Number' FROM consumer.paybill_logs WHERE app_transaction_number IN ($list);";
                    ?>
                    <tr style="display: none;">
                        <td>
                            <input type="hidden" id="query-string" value="<?=$query_string?>">
                        </td>
                    </tr>
                    </tbody>
                </table>
                
            </div>
        </div>
    </div>
</div>

<?php include_once '../layout/sub-footer.php'; ?>
<script src="../metro/js/script.js"></script>

<script>
    $(document).ready(function() {
        $("#get-query").click(function() {
            var query = $('#query-string').val();
            navigator.clipboard.writeText(query);
            runToast("Query copied to clipboard!", "bg-green fg-white");
        });
    });
</script>