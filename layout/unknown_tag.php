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

<title>Unknown Tagging</title>

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
                        <th style="color: white;">MRN</th>
                        <th style="color: white;">Tagging</th>
                        <th style="color: white;">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $query = "SELECT * FROM raw_gigalife_formatted WHERE tagging = '---'";
                    $res = mysqli_query($conn, $query);
                    while ($row = mysqli_fetch_object($res)) {
                    ?>
                    <tr>
                        <td><?=$row->mrns?></td>
                        <td><?=$row->tagging?></td>
                        <td><a href="./manual_investigate.php?id=<?=$row->id?>&mrn=<?=$row->mrns?>&type=unknown_tag" class="button rounded warning"><i class="mif-search"> Investigate</i></a></td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
                
            </div>
        </div>
    </div>
</div>

<?php include_once '../layout/sub-footer.php'; ?>
<script src="../metro/js/script.js"></script>