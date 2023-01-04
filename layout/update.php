<?php include_once '../layout/sub-head.php'; ?>
<style>
<?php include_once '../metro/css/style.css'; ?>
</style>
<?php
include_once '../config/conn.php';
include_once '../config/__utils.php';

mysqli_query($conn, "DROP DATABASE db_variance");
?>

<title>Update: Patch</title>

<div class="container-fluid">
    <div class="grid">
        <div class="row mt-10">
            <div class="cell">
                <form action="#" method="post" class="multi-browse pos-top-center">
                
                    <button type="submit" name="truncateTable" id="truncateTable" class="command-button success rounded mt-3 size-small submit-import">
                        <span class="mif-checkmark icon"></span>
                        <span class="caption">
                            Update successfully patched!
                            <small>Click here.</small>
                        </span>
                    </button>


                    <div id="fileList" class="multi-browse pos-top-center"></div>
                
                </form>
                <?php
                if (isset($_POST['truncateTable'])) {
                    header("Location: ../index.php");
                }
                ?>
            </div>
            
        </div>
    </div>

</div>

<?php include_once '../layout/sub-footer.php'; ?>
<script src="../metro/js/script.js"></script>