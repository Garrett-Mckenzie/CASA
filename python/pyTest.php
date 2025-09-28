<?php
    $python_script = 'CASA_DB_Calls.py';
    $file = 'CASA_Donor_Example.xlsx';
    $output = [];
    $return_var = 0;
    exec("python \"$python_script\" -e \"$file\"", $output, $return_var);

    if($return_var === 0){
        echo "Script executed successfully. Output:\n";
        foreach($output as $line){
            echo $line . "\n";
        }
    }
    else{
        echo "Error executing script. Return code: $return_var\n";
    }
?>