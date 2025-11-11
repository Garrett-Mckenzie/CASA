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

    // Normalize input to always be an array of messages
    $emails = isset($decoded[0]) ? $decoded : [$decoded];

    $results = [];

    foreach ($emails as $email) {
        $cmd = "python send_email.py";
        $proc = proc_open($cmd, [
            0 => ["pipe", "r"], // stdin
            1 => ["pipe", "w"], // stdout
            2 => ["pipe", "w"]  // stderr
        ], $pipes);

        if (!is_resource($proc)) {
            $results[] = ["recipient" => $email['recipient_email'] ?? 'unknown', "success" => false, "error" => "Failed to start Python process"];
            continue;
        }

        // Send JSON to Python stdin
        fwrite($pipes[0], json_encode($email));
        fclose($pipes[0]);

        $output = stream_get_contents($pipes[1]);
        $error = stream_get_contents($pipes[2]);

        fclose($pipes[1]);
        fclose($pipes[2]);
        $exit_code = proc_close($proc);

        $results[] = [
            "recipient" => $email['recipient_email'] ?? 'unknown',
            "success" => $exit_code === 0,
            "output" => trim($output),
            "error" => trim($error)
        ];
    }

    echo json_encode(["success" => true, "results" => $results]);
} catch (Throwable $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
?>
