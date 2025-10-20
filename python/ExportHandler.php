<?php
    $python_script = 'CASA_DB_Calls.py';
    $output = [];
    $return_var = 0;
    $temp = "";
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)){
        $req = $_POST['export'];
        if($req === 'donor'){
            $query = "SELECT * FROM donors;";
        }
        elseif($req === 'donations'){
            $query = "SELECT * FROM donations;";
        }
        else{
            header("Location: export.html?success=0");
        }
        
        exec("python \"$python_script\" -e \"$query\" \"$export_file\"", $output, $return_var);
        $temp = "exec successful";
        if($return_var === 0){
            $download = "exports" . DIRECTORY_SEPARATOR . 'donorExport' . ".xlsx";
            header("Location: export.html?success=1&file=$download");
            exit;
        }
        else{
            header("Location: export.html?success=0");
            exit;
        }
    }
    else{
        header("Location: export.html?success=0&file=$temp");
        exit;
    }
?>
