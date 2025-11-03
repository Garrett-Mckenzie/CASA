<?php
    $python_bin = '/usr/bin/python3';
    $python_script = __DIR__ . DIRECTORY_SEPARATOR . 'dbManager.py';
    $output = [];
    $return_var = 0;

    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['files'])){
        $fileset = $_FILES['files'];
        foreach($fileset['name'] as $index => $name){
            if($fileset['error'][$index] === UPLOAD_ERR_OK){
                // Fetch from temp directory
                $temp = sys_get_temp_dir() . DIRECTORY_SEPARATOR . basename($name);
                if(move_uploaded_file($fileset['tmp_name'][$index], $temp)){
                    $cmd = $python_bin . " " . escapeshellarg($python_script) . ' -i ' . escapeshellarg($temp);
                    exec($cmd, $output, $return_var);
                    unlink($temp);
                    header("Location: import.html?success=1");
                    exit;
                }
                else{
                    header("Location: import.html?success=0&error=move_failed");
                    exit;
                }
            }
            else{
                header("Location: import.html?success=0&error=upload_failed");
                exit;
            }
        }
    }
    else{
        header("Location: import.html?success=0&error=post_failed");
        exit;
    }
?>
