<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reason = $_POST['reason'] ?? '';
    $recipient_email = $_POST['recipient_email'] ?? '';
    $recipient_name = $_POST['recipient_name'] ?? '';
    $sender = $_POST['sender'] ?? '';

    $prompt = "You are an assistant that writes short, professional, and warm emails.\n".
              "Sender: $sender\nRecipient name: $recipient_name\n".
              "Reason: $reason\n".
              "Write a concise, friendly email that could be sent to this recipient.";

    $data = [
        "model" => "qwen3:14b",
        "prompt" => $prompt,
        "stream" => false
    ];

    $ch = curl_init('http://localhost:11434/api/generate');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $response = curl_exec($ch);
    curl_close($ch);

    header('Content-Type: application/json');
    echo $response;
    exit;
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
        <label class="form-label">Recipient Name</label>
        <input type="text" class="form-control" name="recipient_name" placeholder="Jane Doe" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Recipient Email</label>
        <input type="email" class="form-control" name="recipient_email" placeholder="jane@example.com" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Sender Email</label>
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
</html>
