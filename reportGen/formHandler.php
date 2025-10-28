<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$reportName = escapeshellarg("reportName:" . $_POST['reportName']);
$os = escapeshellarg("os:" . $_POST['os']);

$pythonPath = "/Applications/XAMPP/xamppfiles/htdocs/CASA/venv/bin/python";
$scriptPath = "/Applications/XAMPP/xamppfiles/htdocs/CASA/reportGen/generate_report.py";

$cmd = "$pythonPath $scriptPath $reportName $os 2>&1";

echo "<pre>Running command:\n\nCOMMAND: $cmd\n\n";

$output = [];
$return_var = null;
exec($cmd, $output, $return_var);
echo "$cmd\n";
echo "RETURN CODE: $return_var\n\nOUTPUT:\n\n";
print_r($output);
echo "</pre>";

if ($return_var === 0 && !empty($output)) {
    $filename = trim(end($output));
    echo "<div class='alert alert-success'> Report generated: <a href='./report_output/$filename' target='_blank'>$filename</a></div>";
} else {
    echo "<div class='alert alert-danger'>There was a problem on our end, please exit the page.</div>";
}
?>
