<?php
$input = file_get_contents('php://input');
$cmd = "python send_email.py";
$proc = proc_open($cmd, [
    0 => ["pipe", "r"],
    1 => ["pipe", "w"],
    2 => ["pipe", "w"]
], $pipes);

fwrite($pipes[0], $input);
fclose($pipes[0]);
$output = stream_get_contents($pipes[1]);
fclose($pipes[1]);
fclose($pipes[2]);
proc_close($proc);

echo json_encode(["status" => "sent"]);
?>
