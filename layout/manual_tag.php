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

<script>
function runToast(msg, indicator) {
    var toast = Metro.toast.create;
    toast(msg, null, 1500, indicator);
}
</script>

<title>Manual Investigation</title>

<div class="container-fluid">
    <div class="grid">
        <div class="row mt-10">
            <div class="stub">
                <ul class='v-menu'>
                    <li class='menu-title'>General</li>
                    <li><a href='../'><span class='mif-home icon'></span>Home</a></li>
                    <?php if ($_POST['type'] == 'unknown_tag') { ?>
                        <li><a href='./unknown_tag.php'><span class='mif-backspace icon'></span>Back</a></li>
                    <?php } else if ($_POST['type'] == 'noc') { ?>
                        <li><a href='./mail_to_noc.php'><span class='mif-backspace icon'></span>Back</a></li>
                    <?php } ?>
                </ul>
            </div>

            <div class="cell">
                <div class='remark info'>
                    <?php
                    if (isset($_POST['submit_manual_tag'])) {
                        if ($_POST['manual_tag'] != '') {
                            $mrn_id = $_POST['mrn_id'];
                            $tagging = $_POST['manual_tag'];
                            $query = "UPDATE raw_gigalife_formatted SET tagging = '$tagging' WHERE id = '$mrn_id'";
                            if (mysqli_query($conn, $query)) {
                    ?>
                        <div class='remark info'>
                            <pre class='fg-green' style="color: black;">Manual tagging successfully applied. <b><i><?=$tagging?></i></b></pre>
                        </div>
                    <?php
                            }
                        } else { ?>
                        <div class='remark warning'>
                            <pre class='fg-warning' style="color: black;">Please select tagging.</pre>
                        </div>
                    <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once '../layout/sub-footer.php'; ?>
<script src="../metro/js/script.js"></script>