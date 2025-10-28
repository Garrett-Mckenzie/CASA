<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// full paths
$python = "/Applications/XAMPP/xamppfiles/htdocs/CASA/venv/bin/python";
$script = __DIR__ . '/../generate_report.py';

// run python safely and capture all output
$cmd = $python . ' ' . escapeshellarg($script) . ' 2>&1';

exec($cmd, $output, $status);

echo "<pre>";
echo "CMD: $cmd\n\n";
echo "STATUS: $status\n";
echo "OUTPUT:\n" . implode("\n", $output) . "\n";
echo "</pre>";

// if success
if ($status === 0) {
    $pdfPath = trim(end($output));
    if (file_exists($pdfPath)) {
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="report.pdf"');
        readfile($pdfPath);
        exit;
    } else {
        echo "PDF not found at: $pdfPath";
    }
} else {
    echo "Python failed with status $status<br>";
}
?>

