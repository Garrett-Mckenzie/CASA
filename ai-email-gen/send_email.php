<?php
header('Content-Type: application/json');
set_time_limit(120);
ini_set('display_errors', 0);

try {
    $input = file_get_contents('php://input');
    $decoded = json_decode($input, true);

    if (!$decoded) {
        throw new Exception("Invalid JSON payload");
    }

    $emails = isset($decoded[0]) ? $decoded : [$decoded];
    $results = [];

    foreach ($emails as $email) {

        $cmd = "python send_email.py";

        $proc = proc_open($cmd, [
            0 => ["pipe", "r"],
            1 => ["pipe", "w"],
            2 => ["pipe", "w"]
        ], $pipes);

        if (!is_resource($proc)) {
            $results[] = [
                "recipient" => $email['recipient_email'],
                "success"   => false,
                "output"    => "",
                "error"     => "Failed to start python"
            ];
            continue;
        }

        fwrite($pipes[0], json_encode($email));
        fclose($pipes[0]);

        // Capture Python stdout/stderr
        $output = stream_get_contents($pipes[1]);
        $error  = stream_get_contents($pipes[2]);

        fclose($pipes[1]);
        fclose($pipes[2]);

        $exit = proc_close($proc);

        $results[] = [
            "recipient" => $email['recipient_email'],
            "success"   => ($exit === 0),
            "output"    => trim($output),
            "error"     => trim($error)
        ];
    }

    echo json_encode(["success" => true, "results" => $results]);

} catch (Throwable $e) {
    echo json_encode(["success"=>false,"error"=>$e->getMessage()]);
}
?>
