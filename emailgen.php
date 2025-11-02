<?php
session_cache_expire(30);
    session_start();

    $loggedIn = false;
    $accessLevel = 0;
    $userID = null;
    if (isset($_SESSION['_id'])) {
        $loggedIn = true;
        // 0 = not logged in, 1 = standard user, 2 = manager (Admin), 3 super admin (TBI)
        $accessLevel = $_SESSION['access_level'];
        $userID = $_SESSION['_id'];
    }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $reason = $_POST['reason'] ?? '';
    $recipient_email = $_POST['recipient_email'] ?? '';
    $recipient_name = $_POST['recipient_name'] ?? '';
    $sender = $_POST['sender'] ?? '';

    $prompt = "You are an assistant that writes short, professional, and warm emails.\n" .
          "Sender: $sender\nRecipient name: $recipient_name\n" .
          "Reason: $reason\n" .
          "My name is Rappahannock CASA\n".
          "Write a concise (30–50 word) friendly email that could be sent to this recipient.\n\n" .
          "NEVER include placeholders, brackets, or text like [name], [insert], or (your text here). " .
          "Only write complete, natural sentences ready to send.";

    $data = [
        "model" => "llama3.2:3b",
        "prompt" => $prompt,
        "stream" => false
    ];

    try {
        //use IPv4 loopback (faster + avoids IPv6/localhost timeout)
        $ch = curl_init('http://127.0.0.1:11434/api/generate');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_TIMEOUT => 120,                // allow long model load
            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4 // force IPv4
        ]);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            echo json_encode([
                'success' => false,
                'response' => "Connection error: $error"
            ]);
            exit;
        }

        if (!$response || trim($response) === '') {
            echo json_encode([
                'success' => false,
                'response' => "Model returned no output. Make sure the Ollama container and llama3.2:3b are running."
            ]);
            exit;
        }

        $decoded = json_decode($response, true);
        if (!$decoded || !isset($decoded['response'])) {
            echo json_encode([
                'success' => false,
                'response' => "Invalid JSON from Ollama. Full output:\n" . substr($response, 0, 200)
            ]);
            exit;
        }

        echo json_encode([
            'success' => true,
            'response' => $decoded['response']
        ]);
        exit;

    } catch (Throwable $e) {
        echo json_encode([
            'success' => false,
            'response' => "Server error: " . $e->getMessage()
        ]);
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <?php require_once('universal.inc') ?>
  <title>Rappahannock CASA | Email Generator</title>
</head>
<body>
    <?php require_once('header.php') ?>
    <h1>Generate an Email</h1>
  <div class="container mt-4 p-4 bg-white rounded shadow">
    <h3 class="mb-3">Email Generator</h3>
    <form id="emailForm">
      <div class="mb-3">
        <label class="form-label">Reason for Email:</label>
        <select class="form-select" name="reason">
          <option>Thank Donor</option>
          <option>Solicit Donation</option>
          <option>Event Alert</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Recipient Name:</label>
        <input type="text" class="form-control" name="recipient_name" placeholder="Jane Doe" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Recipient Email:</label>
        <input type="email" class="form-control" name="recipient_email" placeholder="jane@example.com" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Sender Email:</label>
        <input type="email" class="form-control" name="sender" value="yourname@gmail.com" required>
      </div>

      <button type="submit" class="btn btn-primary">Generate Email</button>
    </form>

    <!-- Modal -->
    <div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="emailModalLabel">Preview Email</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <textarea id="emailContent" class="form-control" rows="12"></textarea>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-success" id="sendEmailBtn">Send Email</button>
          </div>
        </div>
      </div>
    </div>
  </div>

<script>
document.getElementById('emailForm').addEventListener('submit', async e => {
    e.preventDefault();
    const formData = new FormData(e.target);
    const response = await fetch('', { method: 'POST', body: formData });
    const data = await response.json();

    // fill modal textarea
    document.getElementById('emailContent').value = data.response || "No response from model.";
    const modal = new bootstrap.Modal(document.getElementById('emailModal'));
    modal.show();

    // attach send button behavior
    document.getElementById('sendEmailBtn').onclick = async () => {
        const editedEmail = document.getElementById('emailContent').value;
        const payload = {
            sender: formData.get('sender'),
            recipient_email: formData.get('recipient_email'),
            body: editedEmail
        };
        await fetch('send_email.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(payload)
        });
        alert('Email sent!');
        modal.hide();
    };
});
</script>
</body>
<footer class="footer" style="margin-top: 100px;">
        <!-- Left Side: Logo & Socials -->
        <div class="footer-left">
            <img src="images/RAPPAHANNOCK_v_White-300x300.png" alt="Logo" class="footer-logo">
            <div class="social-icons">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-linkedin"></i></a>
            </div>
        </div>

        <!-- Right Side: Page Links -->
        <div class="footer-right">
            <div class="footer-section">
                <div class="footer-topic">Connect</div>
                <a href="https://www.facebook.com/RappCASA/" target="_blank">Facebook</a>
                <a href="https://www.instagram.com/rappahannock_casa/" target="_blank">Instagram</a>
                <a href="https://rappahannockcasa.com/" target="_blank">Main Website</a>
            </div>
            <div class="footer-section">
                <div class="footer-topic">Contact Us</div>
                <a href="mailto:rappcasa@gmail.com">rappcasa@gmail.com</a>
                <a href="tel:5407106199">540-710-6199</a>
            </div>
        </div>
    </footer>
</html>
