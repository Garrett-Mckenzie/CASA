<?php
set_time_limit(120);
ini_set('max_execution_time', 120);
ini_set('display_errors', 0);

// === GLOBAL CONFIG ===
$env = parse_ini_file(__DIR__ . '/.api_env');
$OPENAI_API_KEY = $env['OPENAI_API_KEY'] ?? '';
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

require_once __DIR__ . '/database/dbinfo.php';

// =====================================================
// AJAX: get donors who donated to an event AND not thanked
// =====================================================
if (isset($_GET['action']) && $_GET['action'] === 'unthanked_donors') {
	header('Content-Type: application/json');

	$eventId = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;
	if ($eventId <= 0) {
		echo json_encode(['success' => false, 'error' => 'Invalid event ID']);
		exit;
	}

	$con = connect();
	if (!$con) {
		echo json_encode(['success' => false, 'error' => 'Database connection failed']);
		exit;
	}
	$lines = array_filter(array_map('trim', explode("\n", $multiple)));
	foreach ($lines as $line) {
		if (strpos($line, ',') === false) continue;
		list($rname, $remail) = array_map('trim', explode(',', $line, 2));
		$responses[] = generate_email($reason, $sender, $rname, $remail, $custom_prompt);
	}

	$sql = "
	SELECT DISTINCT d.id, d.first, d.last, d.email
	FROM donations don
	JOIN donors d ON don.donorID = d.id
	WHERE don.eventID = $eventIdEsc
	  AND don.thanked = 0
	ORDER BY d.last, d.first
    ";

	$result = mysqli_query($con, $sql);
	if (!$result) {
		$err = mysqli_error($con);
		mysqli_close($con);
		echo json_encode(['success' => false, 'error' => "SQL error: $err"]);
		exit;
	}

	$donors = [];
	while ($row = mysqli_fetch_assoc($result)) {
		$donors[] = $row;
	}
	mysqli_free_result($result);
	mysqli_close($con);

	echo json_encode(['success' => true, 'donors' => $donors]);
	exit;
}

// =====================================================
// POST: generate emails via OpenAI
// =====================================================
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

// =====================================================
// Helper: call OpenAI
// =====================================================
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

	$decoded   = json_decode($response, true);
	$generated = $decoded['choices'][0]['message']['content'] ?? "Invalid Model Output";

	return [
		'success'  => true,
		'name'     => $recipient_name,
		'email'    => $recipient_email,
		'response' => $generated
	];
}

// =====================================================
// Load events for dropdown on initial page load
// =====================================================
$events = [];
try {
	$con = connect();
	if ($con) {
		$sql = "SELECT id, name, startDate, endDate FROM dbevents ORDER BY startDate DESC";
		$result = mysqli_query($con, $sql);
		if ($result) {
			while ($row = mysqli_fetch_assoc($result)) {
				$events[] = $row;
			}
			mysqli_free_result($result);
		}
		mysqli_close($con);
	}
} catch (Throwable $e) {
	// Silent for HTML; JSON handlers will catch fatal issues
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

    <!-- Event + Unthanked Donors UI -->
    <div class="mb-3">
	<label class="form-label" for="eventSelect">Select Event (optional)</label>
	<select class="form-select" id="eventSelect">
	    <option value="">-- Choose an event --</option>
	    <?php foreach ($events as $ev): ?>
		<option value="<?= htmlspecialchars($ev['id']) ?>">
		    <?= htmlspecialchars($ev['name']) ?>
		    <?php if (!empty($ev['startDate'])): ?>
			(<?= htmlspecialchars($ev['startDate']) ?>
			<?php if (!empty($ev['endDate']) && $ev['endDate'] !== $ev['startDate']): ?>
			    – <?= htmlspecialchars($ev['endDate']) ?>
			<?php endif; ?>
			)
		    <?php endif; ?>
		</option>
	    <?php endforeach; ?>
	</select>
	<div class="form-text">
	    Pick an event to load donors who donated to it and have <strong>not yet been thanked</strong>.
	    Click a donor to add them to the Recipients list below.
	</div>
    </div>

    <div class="mb-3">
	<label class="form-label">Unthanked Donors for Selected Event</label>
	<ul id="donorList" class="list-group"></ul>
    </div>

    <div class="mb-3">
	<label class="form-label" for="reasonSelect">Reason</label>
	<select class="form-select" name="reason" id="reasonSelect">
	    <option>Thank Donor</option>
	    <option>Solicit Donation</option>
	    <option>Event Alert</option>
	</select>
    </div>

    <div class="mb-3">
	<label class="form-label" for="customPrompt">Custom Prompt (optional)</label>
	<textarea class="form-control" name="custom_prompt" id="customPrompt" rows="3"
	    placeholder="Add additional instructions: tone, details, style..."></textarea>
    </div>

    <div class="mb-3">
	<label class="form-label" for="recipientsTextarea">Recipients (one per line as 'Name, Email')</label>
	<textarea class="form-control" name="recipients" id="recipientsTextarea" rows="4"
	    placeholder="Jane Doe, jane@example.com&#10;John Smith, john@example.com"></textarea>
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
// Load unthanked donors when selecting an event
const donorListEl        = document.getElementById('donorList');
const eventSelectEl      = document.getElementById('eventSelect');
const recipientsTextarea = document.getElementById('recipientsTextarea');

if (eventSelectEl) {
	eventSelectEl.addEventListener('change', async (e) => {
	const eventId = e.target.value;
	donorListEl.innerHTML = '';

	if (!eventId) return;

	try {
		const resp = await fetch(`?action=unthanked_donors&event_id=${encodeURIComponent(eventId)}`);
		const data = await resp.json();

		if (!data.success) {
			donorListEl.innerHTML =
				`<li class="list-group-item text-danger">${data.error || 'Error loading donors.'}</li>`;
			return;
		}

		if (!data.donors || data.donors.length === 0) {
			donorListEl.innerHTML =
				'<li class="list-group-item">No unthanked donors for this event.</li>';
			return;
		}

		data.donors.forEach(d => {
		const li = document.createElement('li');
		li.className = 'list-group-item list-group-item-action';
		li.style.cursor = 'pointer';
		li.textContent = `${d.first} ${d.last} – ${d.email}`;

		li.addEventListener('click', () => {
		const line = `${d.first} ${d.last}, ${d.email}`;
		const current = recipientsTextarea.value.trim();
		recipientsTextarea.value = current ? current + "\n" + line : line;
		});

		donorListEl.appendChild(li);
		});
	} catch (err) {
		donorListEl.innerHTML =
			'<li class="list-group-item text-danger">Unexpected error loading donors.</li>';
	}
});
}

// Existing: submit → generate emails
document.getElementById('emailForm').addEventListener('submit', async e => {
e.preventDefault();

const formData = new FormData(e.target);
const res = await fetch('', { method:'POST', body: formData });
const data = await res.json();

if (!data.success) {
	alert(data.error || 'Failed to generate emails.');
	return;
}

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
