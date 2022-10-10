<?php
function nav($conn, $page, $state) {
    switch ($page) {
        case 'import_gigapay_raw_logs':
            $import_gigapay_raw_logs = 'active';
            $import_elp_raw_logs = '';
            $import_splunk = '';
            $import_gigalife_summary = '';
            $delete_gigapay_raw_logs = '';
            $delete_elp_raw_logs = '';
            $delete_splunk = '';
            $delete_gigalife_summary = '';
        break;
        case 'import_elp_raw_logs':
            $import_gigapay_raw_logs = '';
            $import_elp_raw_logs = 'active';
            $import_splunk = '';
            $import_gigalife_summary = '';
            $delete_gigapay_raw_logs = '';
            $delete_elp_raw_logs = '';
            $delete_splunk = '';
            $delete_gigalife_summary = '';
        break;
        case 'import_splunk':
            $import_gigapay_raw_logs = '';
            $import_elp_raw_logs = '';
            $import_splunk = 'active';
            $import_gigalife_summary = '';
            $delete_gigapay_raw_logs = '';
            $delete_elp_raw_logs = '';
            $delete_splunk = '';
            $delete_gigalife_summary = '';
        break;
        case 'import_gigalife_summary':
            $import_gigapay_raw_logs = '';
            $import_elp_raw_logs = '';
            $import_splunk = '';
            $import_gigalife_summary = 'active';
            $delete_gigapay_raw_logs = '';
            $delete_elp_raw_logs = '';
            $delete_splunk = '';
            $delete_gigalife_summary = '';
        break;
        case 'delete_gigapay_raw_logs':
            $import_gigapay_raw_logs = '';
            $import_elp_raw_logs = '';
            $import_splunk = '';
            $import_gigalife_summary = '';
            $delete_gigapay_raw_logs = 'active';
            $delete_elp_raw_logs = '';
            $delete_splunk = '';
            $delete_gigalife_summary = '';
        break;
        case 'delete_elp_raw_logs':
            $import_gigapay_raw_logs = '';
            $import_elp_raw_logs = '';
            $import_splunk = '';
            $import_gigalife_summary = '';
            $delete_gigapay_raw_logs = '';
            $delete_elp_raw_logs = 'active';
            $delete_splunk = '';
            $delete_gigalife_summary = '';
        break;
        case 'delete_splunk':
            $import_gigapay_raw_logs = '';
            $import_elp_raw_logs = '';
            $import_splunk = '';
            $import_gigalife_summary = '';
            $delete_gigapay_raw_logs = '';
            $delete_elp_raw_logs = '';
            $delete_splunk = 'active';
            $delete_gigalife_summary = '';
        break;
        case 'delete_gigalife_summary':
            $import_gigapay_raw_logs = '';
            $import_elp_raw_logs = '';
            $import_splunk = '';
            $import_gigalife_summary = '';
            $delete_gigapay_raw_logs = '';
            $delete_elp_raw_logs = '';
            $delete_splunk = '';
            $delete_gigalife_summary = 'active';
        break;
        default:
            $import_gigapay_raw_logs = '';
            $import_elp_raw_logs = '';
            $import_splunk = '';
            $import_gigalife_summary = '';
            $delete_gigapay_raw_logs = '';
            $delete_elp_raw_logs = '';
            $delete_splunk = '';
            $delete_gigalife_summary = '';
    }

    $gigapay_table = mysqli_query($conn, "SELECT * FROM file_uploaded WHERE file_type = 'gigapay' ORDER BY banner ASC");
    $elp_table = mysqli_query($conn, "SELECT * FROM file_uploaded WHERE file_type = 'elp' ORDER BY banner ASC");
    $splunk_table = mysqli_query($conn, "SELECT * FROM file_uploaded WHERE file_type = 'splunk' ORDER BY banner ASC");
    $gigalife_table = mysqli_query($conn, "SELECT * FROM file_uploaded WHERE file_type = 'gigalife' ORDER BY banner ASC");

    $gigapay_list = '';
    $gigapay_count = 0;
    $elp_list = '';
    $elp_count = 0;
    $splunk_list = '';
    $splunk_count = 0;
    $gigalife_list = '';
    $gigalife_count = 0;

    //gigapay
    while ($row_gigapay = mysqli_fetch_object($gigapay_table)) {
        $gigapay_count++;
        $gigapay_list .= "<li><a href='table.php?file_type=gigapay&type=date&date_del=$row_gigapay->banner'>Delete $row_gigapay->banner</a></li>";
    }
    $gigapay_msg = ($gigapay_count == 0) ? 'Table':$gigapay_count.' Log(s)';
    $gigapay_list .= "<li><a href='table.php?file_type=gigapay&type=table'>Truncate $gigapay_msg</a></li>";

    //elp
    while ($row_elp = mysqli_fetch_object($elp_table)) {
        $elp_count++;
        $elp_list .= "<li><a href='table.php?file_type=elp&type=date&date_del=$row_elp->banner'>Delete $row_elp->banner</a></li>";
    }
    $elp_msg = ($elp_count == 0) ? 'Table':$elp_count.' Log(s)';
    $elp_list .= "<li><a href='table.php?file_type=elp&type=table'>Truncate $elp_msg</a></li>";

    //splunk
    while ($row_splunk = mysqli_fetch_object($splunk_table)) {
        $splunk_count++;
        $splunk_list .= "<li><a href='table.php?file_type=splunk&type=date&date_del=$row_splunk->banner'>Delete $row_splunk->banner</a></li>";
    }
    $splunk_msg = ($splunk_count == 0) ? 'Table':$splunk_count.' Log(s)';
    $splunk_list .= "<li><a href='table.php?file_type=splunk&type=table'>Truncate $splunk_msg</a></li>";

    //gigalife
    while ($row_gigalife = mysqli_fetch_object($gigalife_table)) {
        $gigalife_count++;
        $gigalife_list .= "<li><a href='table.php?file_type=gigalife&type=date&date_del=$row_gigalife->banner'>Delete $row_gigalife->banner</a></li>";
    }
    $gigalife_msg = ($gigalife_count == 0) ? 'Table':$gigalife_count.' Log(s)';
    $gigalife_list .= "<li><a href='table.php?file_type=gigalife&type=table'>Truncate $gigalife_msg</a></li>";

    if ($state == 'active') {
        $navigation = "
                        <ul class='v-menu'>
                            <li class='menu-title'>General</li>
                            <li><a href='../'><span class='mif-home icon'></span>Home</a></li>
                            <li class='menu-title'>Import</li>
                            <li class='$import_gigapay_raw_logs'><a href='./import.php?ref=gigapay'><span class='mif-cloud-upload icon'></span>Gigapay Raw Logs</a></li>
                            <li class='$import_elp_raw_logs'><a href='./import.php?ref=elp'><span class='mif-cloud-upload icon'></span>ELP Raw Logs</a></li>
                            <li class='$import_splunk'><a href='./import.php?ref=splunk'><span class='mif-cloud-upload icon'></span>Splunk</a></li>
                            <li class='$import_gigalife_summary'><a href='./import.php?ref=gigalife'><span class='mif-cloud-upload icon'></span>Gigalife Summary</a></li>
                            <li class='menu-title'>Tables</li>
                            <li class='$delete_gigapay_raw_logs'>
                                <a href='#' data-hotkey='Alt+1'><span class='mif-document-file-sql icon'></span>Gigapay Raw Logs</a>
                                <ul class='v-menu' data-role='dropdown'>
                                    $gigapay_list
                                </ul>
                            </li>
                            <li class='$delete_elp_raw_logs'>
                                <a href='#' data-hotkey='Alt+2'><span class='mif-document-file-sql icon'></span>ELP Raw Logs</a>
                                <ul class='v-menu' data-role='dropdown'>
                                    $elp_list
                                </ul>
                            </li>
                            <li class='$delete_splunk'>
                                <a href='#' data-hotkey='Alt+3'><span class='mif-document-file-sql icon'></span>Splunk</a>
                                <ul class='v-menu' data-role='dropdown'>
                                    $splunk_list
                                </ul>
                            </li>
                            <li class='$delete_gigalife_summary'>
                                <a href='#' data-hotkey='Alt+4'><span class='mif-document-file-sql icon'></span>Gigalife Summary</a>
                                <ul class='v-menu' data-role='dropdown'>
                                    $gigalife_list
                                </ul>
                            </li>
                        </ul>
                        ";
    } else {
        $navigation = "
                        <ul class='v-menu'>
                            <li class='menu-title'>General</li>
                            <li><a href='../'><span class='mif-home icon'></span>Home</a></li>
                        </ul>
                        ";
    }

    return $navigation;
}
?>