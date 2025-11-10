<?php
set_time_limit(120);
ini_set('max_execution_time', 120);
ini_set('display_errors', 0);

// Unified JSON error handling
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'response' => "PHP error: $errstr"]);
    exit;
});
set_exception_handler(function ($e) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'response' => "Exception: " . $e->getMessage()]);
    exit;
});

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $reason = $_POST['reason'] ?? '';
    $sender = $_POST['sender'] ?? '';
    $multiple = trim($_POST['recipients'] ?? '');
    $recipient_name = $_POST['recipient_name'] ?? '';
    $recipient_email = $_POST['recipient_email'] ?? '';

    $responses = [];

    // If multiple recipients were provided, process each line
    if (!empty($multiple)) {
        $lines = array_filter(array_map('trim', explode("\n", $multiple)));
        foreach ($lines as $line) {
            if (strpos($line, ',') === false) continue;
            list($rname, $remail) = array_map('trim', explode(',', $line, 2));

            $responses[] = generate_email($reason, $sender, $rname, $remail);
        }
        echo json_encode(['success' => true, 'emails' => $responses]);
        exit;
    }

    // Otherwise handle single recipient
    $result = generate_email($reason, $sender, $recipient_name, $recipient_email);
    echo json_encode($result);
    exit;
}

// === Helper function to call Ollama API safely ===
function generate_email($reason, $sender, $recipient_name, $recipient_email) {
    $prompt = "You are an assistant that writes short, professional, and warm emails.\n" .
              "Sender: $sender\nRecipient name: $recipient_name\n" .
              "Reason: $reason\n" .
              "My name is Rapp CASA. Write a concise, friendly email that could be sent to this recipient. " .
              "Do not include any bracketed info for me to fill out; if unsure of any info, leave it out.";

    $data = [
        "model" => "llama3.2:3b",
        "prompt" => $prompt,
        "stream" => false
    ];

    try {
        $ch = curl_init('http://127.0.0.1:11434/api/generate');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_TIMEOUT => 120,
            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4
        ]);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return [
                'success' => false,
                'name' => $recipient_name,
                'email' => $recipient_email,
                'response' => "Connection error: $error"
            ];
        }

        $decoded = json_decode($response, true);
        if (!$decoded || !isset($decoded['response'])) {
            return [
                'success' => false,
                'name' => $recipient_name,
                'email' => $recipient_email,
                'response' => "Invalid response from model."
            ];
        }

        return [
            'success' => true,
            'name' => $recipient_name,
            'email' => $recipient_email,
            'response' => $decoded['response']
        ];
    } catch (Throwable $e) {
        return [
            'success' => false,
            'name' => $recipient_name,
            'email' => $recipient_email,
            'response' => "Server error: " . $e->getMessage()
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Email Generator</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="p-4 bg-light">
  <div class="container mt-4 p-4 bg-white rounded shadow">
    <h3 class="mb-3">AI Email Generator</h3>
    <form id="emailForm">
      <div class="mb-3">
        <label class="form-label">Reason for Email</label>
        <select class="form-select" name="reason">
          <option>Thank Donor</option>
          <option>Solicit Donation</option>
          <option>Event Alert</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Recipients (optional — one per line as "Name, Email")</label>
        <textarea class="form-control" name="recipients" rows="4" placeholder="Jane Doe, jane@example.com&#10;John Smith, john@example.com"></textarea>
        <div class="form-text">Leave blank to generate for a single recipient below.</div>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Recipient Name</label>
          <input type="text" class="form-control" name="recipient_name" placeholder="Jane Doe">
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Recipient Email</label>
          <input type="email" class="form-control" name="recipient_email" placeholder="jane@example.com">
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Sender Email</label>
        <input type="email" class="form-control" name="sender" value="yourname@gmail.com" required>
      </div>

      <button type="submit" class="btn btn-primary">Generate Email(s)</button>
    </form>

    <!-- Modal -->
    <div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="emailModalLabel">Preview Generated Email(s)</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" id="emailListContainer"></div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-success" id="sendAllBtn">Send All</button>
          </div>
        </div>
      </div>
    </div>
  </div>

<script>
document.getElementById('emailForm').addEventListener('submit', async e => {
    e.preventDefault();
    const formData = new FormData(e.target);
    const res = await fetch('', { method: 'POST', body: formData });
    const data = await res.json();

    const container = document.getElementById('emailListContainer');
    container.innerHTML = '';

    if (data.success && data.response) {
        // single email mode
        container.innerHTML = `
            <h6>Preview</h6>
            <textarea class="form-control email-content" rows="10">${data.response}</textarea>`;
    } else if (data.emails && data.emails.length > 0) {
        // multi mode
        data.emails.forEach((item, idx) => {
            const block = document.createElement('div');
            block.classList.add('mb-4');
            block.innerHTML = `
              <h6>${item.name} &lt;${item.email}&gt;</h6>
              <textarea class="form-control mb-2 email-content" rows="8">${item.response}</textarea>
              <hr/>`;
            container.appendChild(block);
        });
    } else {
        container.innerHTML = '<p>No response from model.</p>';
    }

    const modal = new bootstrap.Modal(document.getElementById('emailModal'));
    modal.show();

    document.getElementById('sendAllBtn').onclick = async () => {
        const texts = [...document.querySelectorAll('.email-content')].map(t => t.value);
        const emails = (data.emails || [{
            sender: formData.get('sender'),
            recipient_email: formData.get('recipient_email'),
            body: texts[0]
        }]).map((e, i) => ({
            sender: formData.get('sender'),
            recipient_email: e.email || formData.get('recipient_email'),
            body: texts[i] || texts[0]
        }));

        await fetch('send_email.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(emails)
        });
        alert('Email(s) sent!');
        modal.hide();
    };
});
</script>
</body>
</html>