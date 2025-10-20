<?php
    $python_script = 'CASA_DB_Calls.py';
    $output = [];
    $return_var = 0;
    if($_SERVER['REQUEST_METHOD'] === 'post' && isset($_FILES['files'])){
        $fileset = $_FILES['files'];
        foreach($fileset['name'] as $index => $name){
            if($fileset['error'][$index] === UPLOAD_ERR_OK){
                // Fetch from temp directory
                $temp = sys_get_temp_dir() . DIRECTORY_SEPARATOR . basename($name);
                if(move_uploaded_file($fileset['tmp_name']['name'], $temp)){
                    exec("python \"$python_script\" -i \"$temp\"", $output, $return_var);

                    if($return_var === 0){
                        echo "Imported $name<br>";
                    } else{
                        echo "Error importing $name<br>";
                    }

                    unlink($temp);
                }
                else {
                    echo "Failed to fetch $name<br>";
                }
            } else {
                echo "Failed to upload $name<br>";
            }
        }
    } else {
        echo "Invalid request<br>";
    }
?>
<!DOCTYPE html>
<html>
    <body>
        <main>
            <p name="Response"></p>
        </main>
    </body>
</html>