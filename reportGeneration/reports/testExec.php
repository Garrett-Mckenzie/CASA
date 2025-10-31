<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$python = '/Applications/XAMPP/xamppfiles/htdocs/CASA/venv/bin/python';
$script = '/Applications/XAMPP/xamppfiles/htdocs/CASA/reportGeneration/reports/testExec.py';

$cmd = escapeshellcmd("$python $script 2>&1");
exec($cmd, $output, $status);

echo "<pre>";
echo "CMD: $cmd\n";
echo "STATUS: $status\n";
echo "OUTPUT:\n" . implode("\n", $output) . "\n";
echo "</pre>";
?>
