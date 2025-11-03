<?php
    $python_script = 'dbManager.py';
    $date = date('m-d-y');
    $output = [];
    $return_var = 0;
    $temp = "";
    $export_file = null;
    if(is_dir("exports") === false){
        mkdir("exports");
    }
    error_log(print_r($_POST, true));
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)){
        $req = $_POST['export'];
        if($req === 'donor'){
            $query = "SELECT * FROM donors";
            $dup = 0;
            while(file_exists("exports" . DIRECTORY_SEPARATOR . "donorExport" . $date . ($dup > 0 ? "_$dup" : "") . ".xlsx")){
                $dup++;
            }
            $export_file = "exports" . DIRECTORY_SEPARATOR . "donorExport" . ($dup > 0 ? "_$dup" : "") . $date . ".xlsx";
        }
        elseif($req === 'donation'){
            if(isset($_POST['startDate']) && isset($_POST['endDate']) && !empty($_POST['startDate']) && !empty($_POST['endDate'])){
                $start = $_POST['startDate'];
                $end = $_POST['endDate'];
                $query = "SELECT * FROM donations WHERE donation_date BETWEEN '$start' AND '$end'";
            }
            else{
                $query = "SELECT * FROM donations";
            }
            $export_file = "exports" . DIRECTORY_SEPARATOR . "donationExport" . $date . ".xlsx";
        }
        else{
            header("Location: export.html?success=0");
        }

        if(file_exists($export_file)){
            if($_POST['overwrite'] !== 'true'){
                header("Location: export.html?success=0&error=file_exists");
                exit;
            }
            unlink($export_file);
        }
        if($export_file != null) { exec("python \"$python_script\" -e \"$query\" \"$export_file\"", $output, $return_var); } # python dbManager.py -e "SELECT" exports\donorExport.xlsx
        else { header("Location: export.html?success=0&error=no_file&post=" . urldecode(serialize($_POST))); exit; }
        header("Location: export.html?success=1&file=$export_file");
        exit;
    }

    // Delete file on Download
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteFile'])){
        $file = $_POST['deleteFile'];
        if(file_exists($file)){ unlink($file); header("Location: export.html?success=1"); }
        else { header("Location: export.html?success=0&error=noFile"); }
        exit;
    }
?>
