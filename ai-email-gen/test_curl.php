<?php
$url = "http://localhost:11434/api/tags";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
if ($response === false) {
    echo "Error: " . curl_error($ch) . "\n";
} else {
    echo $response;
}
curl_close($ch);
