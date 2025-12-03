<?php
set_time_limit(120);
ini_set('max_execution_time', 120);
ini_set('display_errors', 0);

// === GLOBAL CONFIG ===
$env = parse_ini_file(__DIR__.'/.api_env');
$OPENAI_API_KEY = $env['OPENAI_API_KEY'];
$OPENAI_MODEL   = "gpt-4o-mini";

// === Unified JSON error handling ===
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

	$reason        = $_POST['reason'] ?? '';
	$sender        = $_POST['sender'] ?? '';
	$multiple      = trim($_POST['recipients'] ?? '');
	$custom_prompt = $_POST['custom_prompt'] ?? '';

	if (empty($multiple)) {
		echo json_encode(["success" => false, "error" => "Please provide at least one recipient."]);
		exit;
	}

	$responses = [];


	$lines = array_filter(array_map('trim', explode("\n", $multiple)));
	foreach ($lines as $line) {
		if (strpos($line, ',') === false) continue;
		list($rname, $remail) = array_map('trim', explode(',', $line, 2));
		$responses[] = generate_email($reason, $sender, $rname, $remail, $custom_prompt);
	}

	echo json_encode(['success' => true, 'emails' => $responses]);
	exit;
}

// === OpenAI Email Generation ===
function generate_email($reason, $sender, $recipient_name, $recipient_email, $custom_prompt)
{
	global $OPENAI_API_KEY, $OPENAI_MODEL;

	$prompt =
		"Write a short, warm, professional donor email.\n" .
		"Sender: $sender\nRecipient: $recipient_name\nReason: $reason\n" .
		"Write naturally without placeholders.\n";

	if (!empty(trim($custom_prompt))) {
		$prompt .= "\nAdditional user instructions:\n$custom_prompt\n";
	}

	$payload = json_encode([
		"model" => $OPENAI_MODEL,
		"messages" => [
			["role" => "system", "content" => "You are a helpful email-writing assistant."],
			["role" => "user", "content" => $prompt]
		],
		"temperature" => 0.7
	]);

	$ch = curl_init("https://api.openai.com/v1/chat/completions");
	curl_setopt_array($ch, [
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_POST => true,
		CURLOPT_HTTPHEADER => [
			"Authorization: Bearer $OPENAI_API_KEY",
			"Content-Type: application/json"
		],
		CURLOPT_POSTFIELDS => $payload,
		CURLOPT_TIMEOUT => 120,
		CURLOPT_SSL_VERIFYPEER => true
	]);

	$response = curl_exec($ch);
	$error    = curl_error($ch);
	curl_close($ch);

	if ($error) {
		return [
			'success' => false,
			'name'    => $recipient_name,
			'email'   => $recipient_email,
			'response' => "OpenAI connection error: $error"
		];
	}

	$decoded = json_decode($response, true);
	$generated = $decoded['choices'][0]['message']['content'] ?? "Invalid Model Output";

	return [
		'success'  => true,
		'name'     => $recipient_name,
		'email'    => $recipient_email,
		'response' => $generated
	];
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
	<label class="form-label">Reason</label>
	<select class="form-select" name="reason">
	    <option>Thank Donor</option>
	    <option>Solicit Donation</option>
	    <option>Event Alert</option>
	</select>
    </div>

    <div class="mb-3">
	<label class="form-label">Custom Prompt (optional)</label>
	<textarea class="form-control" name="custom_prompt" rows="3"
	    placeholder="Add additional instructions: tone, details, style..."></textarea>
    </div>

    <div class="mb-3">
	<label class="form-label">Recipients (one per line as 'Name, Email')</label>
	<textarea class="form-control" name="recipients" rows="4"
	    placeholder="Jane Doe, jane@example.com&#10;John Smith, john@example.com"></textarea>
    </div>

    <div class="mb-3">
	<label class="form-label">Sender (Your Email)</label>
	<input type="email" class="form-control" name="sender" required>
    </div>

    <button type="submit" class="btn btn-primary">Generate Email(s)</button>
</form>

<!-- Modal -->
<div class="modal fade" id="emailModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
	<h5 class="modal-title">Preview Generated Email(s)</h5>
	<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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
const res = await fetch('', { method:'POST', body: formData });
const data = await res.json();

const container = document.getElementById('emailListContainer');
container.innerHTML = '';

data.emails.forEach((item, i) => {
container.innerHTML += `
	<div class="mb-4">
	    <h6>${item.name} &lt;${item.email}&gt;</h6>
	    <textarea class="form-control email-content" rows="8">${item.response}</textarea>
	    <hr />
	</div>`;
});

const modal = new bootstrap.Modal(document.getElementById('emailModal'));
modal.show();

document.getElementById('sendAllBtn').onclick = async () => {
const texts = [...document.querySelectorAll('.email-content')].map(t => t.value);

let emailsToSend = data.emails.map((item, i) => ({
sender: formData.get('sender'),
	recipient_email: item.email,
	body: texts[i]
}));

await fetch('send_email.php', {
method: 'POST',
	headers: {'Content-Type':'application/json'},
	body: JSON.stringify(emailsToSend)
});

alert("Email(s) sent!");
modal.hide();
};
});
</script>

</body>
</html>
