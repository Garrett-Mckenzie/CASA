<?php
    $python_script = 'CASA_DB_Calls.py';
    $output = [];
    $return_var = 0;
    if($_SERVER['REQUEST_METHOD'] === 'post' && isset($_POST)){
        /*
        $req = $_POST['export'];
        if($req === 'donor'){
            $query = "SELECT * FROM donors;";
        }
        elseif($req === 'donations'){
            $query = "SELECT * FROM donations;";
        }
        */
        switch($_POST['export']){
            case "donor":
                $query = "SELECT * FROM donors;";
                $export_file = __DIR__ . DIRECTORY_SEPARATOR . "exports" . DIRECTORY_SEPARATOR . "donors.xlsx";

                break;
            case "donation":
                $query = "SELECT * FROM donations;";
                $export_file = __DIR__ . DIRECTORY_SEPARATOR . "exports" . DIRECTORY_SEPARATOR . "donations.xlsx";
                break;
            default:
                exit(1);
        }
        exec("python \"$python_script\" -e \"$query\" \"$export_file\"", $output, $return_var);
        if($return_var === 0){
            $download = "exports" . DIRECTORY_SEPARATOR . $_POST['export'] . "s.xlsx";
            header("Location: export.html?success=1&file=$download");
            exit;
        }
        else{
            header("Location: export.html?success=0");
            echo "Export failed";
            exit;
        }
    }
?>