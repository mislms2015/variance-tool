<?php
include_once './layout/head.php';
include_once './config/__utils.php';
include_once './config/conn.php';
?>
<script>
function runToast(msg, indicator) {
    var toast = Metro.toast.create;
    toast(msg, null, 10000, indicator);
}
</script>
<title>Variance Investigation Tool</title>

    <div class="container-fluid start-screen h-100" id="mainContainer">
        <h1 class="start-screen-title">Variance Investigation</h1>

        <span class="mif-spinner ani-spin mif-5x fg-teal" id="spinner"></span>

        <div class="tiles-area clear">
            <!-- GIGAPAY RAW LOGS: START -->
            <div class="tiles-grid tiles-group size-2 fg-white" data-group-title="Gigapay Raw Logs">
                <a href="./layout/import.php?ref=gigapay" data-role="tile" data-cover="./images/gigapay_raw_logs.jpg" data-size="wide">
                </a>
                <?php
                $gigapay_list_counter = 0;
                $gigapay_list = mysqli_query($conn, "SELECT * FROM file_uploaded WHERE file_type = 'gigapay' ORDER BY banner ASC");
                while ($row_gigapay = mysqli_fetch_object($gigapay_list)) {
                    $gigapay_list_counter++;
                ?>
                    <a href="./layout/table.php?file_type=gigapay&type=date&date_del=<?=$row_gigapay->banner?>" data-role="tile" class="bg-green fg-white">
                        <span class="mif-calendar icon"></span>
                        <span class="branding-bar pt-1">Delete</span>
                        <span class="badge-bottom"><?=$row_gigapay->banner?></span>
                    </a>
                <?php } if ($gigapay_list_counter > 0) { ?>
                    <a href="./layout/table.php?file_type=gigapay&type=table" data-role="tile" class="bg-red fg-white">
                        <span class="mif-database icon"></span>
                        <span class="branding-bar pt-1">Truncate</span>
                        <span class="badge-bottom"><?=$gigapay_list_counter. ' log(s)'?></span>
                    </a>
                <?php } ?>
            </div>
            <!-- GIGAPAY RAW LOGS: END -->

            <!-- ELP RAW LOGS: START -->
            <div class="tiles-grid tiles-group size-2 fg-white" data-group-title="ELP Raw Logs">
                <a href="./layout/import.php?ref=elp" data-role="tile" data-cover="./images/elp_raw_logs.jpg" data-size="wide">
                </a>
                <?php
                $elp_list_counter = 0;
                $elp_list = mysqli_query($conn, "SELECT * FROM file_uploaded WHERE file_type = 'elp' ORDER BY banner ASC");
                while ($row_elp = mysqli_fetch_object($elp_list)) {
                    $elp_list_counter++;
                ?>
                    <a href="./layout/table.php?file_type=elp&type=date&date_del=<?=$row_elp->banner?>" data-role="tile" class="bg-teal fg-white">
                        <span class="mif-calendar icon"></span>
                        <span class="branding-bar pt-1">Delete</span>
                        <span class="badge-bottom"><?=$row_elp->banner?></span>
                    </a>
                <?php } if ($elp_list_counter > 0) { ?>
                    <a href="./layout/table.php?file_type=elp&type=table" data-role="tile" class="bg-red fg-white">
                        <span class="mif-database icon"></span>
                        <span class="branding-bar pt-1">Truncate</span>
                        <span class="badge-bottom"><?=$elp_list_counter. ' log(s)'?></span>
                    </a>
                <?php } ?>
            </div>
            <!-- ELP RAW LOGS: END -->

            <!-- SPLUNK: START -->
            <div class="tiles-grid tiles-group size-2 fg-white" data-group-title="Splunk">
                <a href="./layout/import.php?ref=splunk" data-role="tile" data-cover="./images/splunk.jpg" data-size="wide">
                </a>
                <?php
                $splunk_list_counter = 0;
                $splunk_list = mysqli_query($conn, "SELECT * FROM file_uploaded WHERE file_type = 'splunk' ORDER BY banner ASC");
                while ($row_splunk = mysqli_fetch_object($splunk_list)) {
                    $splunk_list_counter++;
                ?>
                    <a href="./layout/table.php?file_type=splunk&type=date&date_del=<?=$row_splunk->banner?>" data-role="tile" class="bg-violet fg-white">
                        <span class="mif-calendar icon"></span>
                        <span class="branding-bar pt-1">Delete</span>
                        <span class="badge-bottom"><?=$row_splunk->banner?></span>
                    </a>
                <?php } if ($splunk_list_counter > 0) { ?>
                    <a href="./layout/table.php?file_type=splunk&type=table" data-role="tile" class="bg-red fg-white">
                        <span class="mif-database icon"></span>
                        <span class="branding-bar pt-1">Truncate</span>
                        <span class="badge-bottom"><?=$splunk_list_counter. ' log(s)'?></span>
                    </a>
                <?php } ?>
            </div>
            <!-- SPLUNK: END -->

            <!-- GIGALIFE SUMMARY: START -->
            <div class="tiles-grid tiles-group size-2 fg-white" data-group-title="Gigalife Summary">
                <a href="./layout/import.php?ref=gigalife" data-role="tile" data-cover="./images/gigalife_summary.jpg" data-size="wide">
                </a>
                <?php
                $gigalife_list_counter = 0;
                $gigalife_list = mysqli_query($conn, "SELECT * FROM file_uploaded WHERE file_type = 'gigalife' ORDER BY banner ASC");
                while ($row_gigalife = mysqli_fetch_object($gigalife_list)) {
                    $gigalife_list_counter++;
                ?>
                    <a href="./layout/table.php?file_type=gigalife&type=date&date_del=<?=$row_gigalife->banner?>" data-role="tile" class="bg-pink fg-white">
                        <span class="mif-calendar icon"></span>
                        <span class="branding-bar pt-1">Delete</span>
                        <span class="badge-bottom"><?=$row_gigalife->banner?></span>
                    </a>
                <?php } if ($gigalife_list_counter > 0) { ?>
                    <a href="./layout/table.php?file_type=gigalife&type=table" data-role="tile" class="bg-red fg-white">
                        <span class="mif-database icon"></span>
                        <span class="branding-bar pt-1">Truncate</span>
                        <span class="badge-bottom"><?=$gigalife_list_counter. ' log(s)'?></span>
                    </a>
                <?php } ?>
            <!-- GIGALIFE SUMMARY: END -->
            </div>

            <!-- DATABASE SETTINGS: START -->
            <div class="tiles-grid tiles-group size-1 fg-white" data-group-title="Settings">
                <div id="investigate" data-role="tile" data-size="small" class="bg-teal">
                    <img src="./images/magnifying.png" class="icon" <?=popOver('Investigate')?>>
                </div>
                <div id="download_validated" data-role="tile" data-size="small" class="bg-blue">
                    <img src="./images/download.png" class="icon" <?=popOver('Download Validated')?>>
                </div>
                <a href="./layout/mail_to_noc.php" data-role="tile" data-size="small" class="bg-pink">
                    <img src="./images/mail_to_noc.png" class="icon" <?=popOver('Mail to NOC')?>>
                </a>
                <a href="./layout/pb_transactions.php" data-role="tile" data-size="small" class="bg-green">
                    <img src="./images/pb_transaction.png" class="icon" <?=popOver('PB Transaction')?>>
                </a>
                </a>
                <a href="./layout/unknown_tag.php" data-role="tile" data-size="small" class="bg-yellow">
                    <img src="./images/unknown.svg" class="icon" <?=popOver('Unknown Tagging')?>>
                </a>
                <a href="./layout/truncate.php" data-role="tile" data-size="small" class="bg-black">
                    <img src="./images/truncate.png" class="icon" <?=popOver('Truncate Tables')?>>
                </a>
            </div>
            <!-- DATABASE SETTINGS: END -->

            <audio id='success'>
                <source src='./asset/sound/chime.mp3'>
            </audio>
        </div>

    </div>

<?php include_once './layout/footer.php'; ?>

<script>
    $(document).ready(function() {
        const audio = new Audio("./asset/sound/chime.mp3" );

        //investigate variance ajax
        $("#investigate").click(function() {
            $('#spinner').css('visibility','visible');
            $('#mainContainer').attr('disabled', true);
            
            $.ajax({
                method: "GET",
                url: "./layout/script_investigate.php"
            }).then(
                function(response){ //success
                    if (response == 'success'){
                        $('#spinner').css('visibility','hidden');
                        $('#mainContainer').removeAttr("disabled");
                        audio.play();
                        runToast("Investigation successfully performed!", "bg-green fg-white");
                    } else {
                        console.log('fail');
                    }
                },
                function(xhr){console.log('error')} // error
            );
        });

        //download validated file ajax
        $("#download_validated").click(function() {
            $('#spinner').css('visibility','visible');
            $('#mainContainer').attr('disabled', true);
            
            $.ajax({
                method: "GET",
                url: "./layout/script_download.php",
                dataType: "JSON"
            }).then(
                function(response){ //success
                    //console.log(response);
                    var parsed_data = JSON.parse(response);
                    if (parsed_data.res == 'success'){
                        $('#spinner').css('visibility','hidden');
                        $('#mainContainer').removeAttr("disabled");
                        audio.play();
                        runToast("Validated file successfully downloaded!", "bg-green fg-white");

                        window.location.href = parsed_data.file;
                    } else {
                        $('#spinner').css('visibility','hidden');
                        $('#mainContainer').removeAttr("disabled");
                        runToast("No file to download!", "bg-red fg-white");
                    }
                    
                },
                function(xhr){
                    console.log('error');
                } // error
            );
        });
    });
    
</script>