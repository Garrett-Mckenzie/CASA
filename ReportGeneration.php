<?php
// --- PHP LOGIC FOR EMAIL GENERATION ---
set_time_limit(120);
ini_set('max_execution_time', 120);
ini_set('display_errors', 0);

// === GLOBAL CONFIG ===
$OPENAI_API_KEY = "ADDME";
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

    // Process multiple recipients
    $lines = array_filter(array_map('trim', explode("\n", $multiple)));
    foreach ($lines as $line) {
       if (strpos($line, ',') === false) continue;
       list($rname, $remail) = array_map('trim', explode(',', $line, 2));
       $responses[] = generate_email($reason, $sender, $rname, $remail, $custom_prompt);
    }

    echo json_encode(['success' => true, 'emails' => $responses]);
    exit;
}

// === OpenAI Function ===
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
        <link rel="stylesheet" href="css/messages.css">
        <title>Rappahannock CASA | Email Generator</title>
        <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;700&display=swap" rel="stylesheet">

        <style>
            /* --- GLOBAL STYLES --- */
            body {
                background-color: #f9f9f9;
                font-family: 'Quicksand', sans-serif;
            }


            /* The "Card" container style */
            .content-card {
                background: #ffffff;
                border-radius: 12px;
                padding: 30px;
                box-shadow: 0 3px 10px rgba(0,0,0,0.15);
                max-width: 800px;
                margin: 20px auto;
            }

            h3 {
                margin-top: 0;
                color: #036; /* Branding Blue */
                border-bottom: 2px solid #eee;
                padding-bottom: 10px;
                margin-bottom: 20px;
            }

            /* Form Styling */
            .form-group {
                margin-bottom: 20px;
            }

            label {
                display: block;
                font-weight: bold;
                color: #444;
                margin-bottom: 8px;
            }

            /* Input styling matching the Report Page */
            input[type="text"],
            input[type="email"],
            select,
            textarea {
                padding: 10px 15px;
                width: 100%;
                font-size: 16px;
                border-radius: 8px;
                border: 1px solid #aaa;
                box-sizing: border-box;
                font-family: 'Quicksand', sans-serif;
            }

            input:focus, select:focus, textarea:focus {
                outline: 2px solid #00447b;
                border-color: #00447b;
            }

            /* Buttons matching CASA Blue */
            .btn-primary {
                background: #00447b; /* CASA Blue */
                color: white;
                padding: 12px 24px;
                border: none;
                border-radius: 8px;
                font-size: 16px;
                font-weight: bold;
                cursor: pointer;
                transition: background 0.25s ease, transform 0.2s ease;
                display: inline-block;
                width: 100%;
                text-align: center;
            }

            .btn-primary:hover {
                background: #003366; /* Slightly Darker Blue */
                transform: translateY(-2px);
            }

            .btn-secondary {
                background: #787777;
                color: white;
                padding: 10px 20px;
                border: none;
                border-radius: 8px;
                font-weight: bold;
                cursor: pointer;
            }
            .btn-secondary:hover {
                background: #5e5e5e;
            }

            /* --- CUSTOM MODAL STYLES --- */
            .modal-overlay {
                display: none;
                position: fixed;
                top: 0; left: 0;
                width: 100%; height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 2000;
                align-items: center;
                justify-content: center;
            }

            .modal-box {
                background: white;
                border-radius: 12px;
                width: 90%;
                max-width: 800px;
                max-height: 90vh;
                display: flex;
                flex-direction: column;
                box-shadow: 0 5px 15px rgba(0,0,0,0.3);
                animation: slideDown 0.3s ease-out;
            }

            @keyframes slideDown {
                from { transform: translateY(-50px); opacity: 0; }
                to { transform: translateY(0); opacity: 1; }
            }

            .modal-header {
                padding: 20px;
                border-bottom: 1px solid #eee;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .modal-title { margin: 0; color: #036; font-size: 20px; font-weight: bold; }

            .close-btn { background: none; border: none; font-size: 28px; cursor: pointer; color: #aaa; line-height: 1; }
            .close-btn:hover { color: #000; }

            .modal-body { padding: 20px; overflow-y: auto; }

            .modal-footer {
                padding: 20px;
                border-top: 1px solid #eee;
                display: flex;
                justify-content: flex-end;
                gap: 10px;
            }

            .preview-item { margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #eee; }
            .preview-item h6 { margin: 0 0 10px 0; color: #00447b; font-size: 16px; font-weight: bold; }

            /* --- FOOTER --- */
            .footer {
                background: #00447b;
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                padding: 30px 50px;
                flex-wrap: wrap;
                margin-top: 50px;
            }
            .footer-left { display: flex; flex-direction: column; align-items: center; }
            .footer-logo { width: 150px; margin-bottom: 15px; }
            .social-icons { display: flex; gap: 15px; }
            .social-icons a { color: white; font-size: 20px; }
            .footer-right { display: flex; gap: 50px; flex-wrap: wrap; }
            .footer-section { display: flex; flex-direction: column; gap: 10px; color: white; }
            .footer-topic { font-size: 18px; font-weight: bold; }
            .footer a { color: white; text-decoration: none; padding: 5px 10px; border-radius: 5px; }
            .footer a:hover { background: rgba(255, 255, 255, 0.1); }
        </style>
    </head>
    <body>
        <?php require_once('header.php') ?>

        <h1>Generate an Email</h1>

        <main class="general">

            <div class="content-card">
                <h3>Email Generator</h3>

                <form id="emailForm">
                    <div class="form-group">
                        <label>Reason</label>
                        <select name="reason">
                            <option>Thank Donor</option>
                            <option>Solicit Donation</option>
                            <option>Event Alert</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Custom Prompt (optional)</label>
                        <textarea name="custom_prompt" rows="3" placeholder="Add additional instructions: tone, details, style..."></textarea>
                    </div>

                    <div class="form-group">
                        <label>Recipients (one per line as 'Name, Email')</label>
                        <textarea name="recipients" rows="4" placeholder="Jane Doe, jane@example.com&#10;John Smith, john@example.com"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Sender (Your Email)</label>
                        <input type="email" name="sender" required placeholder="you@rappahannockcasa.com">
                    </div>

                    <button type="submit" class="btn-primary">Generate Email(s)</button>
                </form>
            </div>

            <div id="customModal" class="modal-overlay">
                <div class="modal-box">
                    <div class="modal-header">
                        <div class="modal-title">Preview Generated Email(s)</div>
                        <button type="button" class="close-btn" id="closeModalX">&times;</button>
                    </div>

                    <div class="modal-body" id="emailListContainer">
                        <p style="text-align:center; color:#666;">Generating...</p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn-secondary" id="closeModalBtn">Cancel</button>
                        <button type="button" class="btn-primary" id="sendAllBtn" style="width: auto;">Send All</button>
                    </div>
                </div>
            </div>

        </main>

        <footer class="footer">
             <div class="footer-left">
                <img src="images/RAPPAHANNOCK_v_White-300x300.png" alt="Logo" class="footer-logo">
                <div class="social-icons">
                   <a href="#"><i class="fab fa-facebook"></i></a>
                   <a href="#"><i class="fab fa-twitter"></i></a>
                   <a href="#"><i class="fab fa-instagram"></i></a>
                   <a href="#"><i class="fab fa-linkedin"></i></a>
                </div>
             </div>

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

        <script>
        document.addEventListener("DOMContentLoaded", function () {
            const modal = document.getElementById('customModal');
            const closeModalX = document.getElementById('closeModalX');
            const closeModalBtn = document.getElementById('closeModalBtn');
            const emailListContainer = document.getElementById('emailListContainer');
            let generatedData = null;

            // Modal Logic
            function hideModal() { modal.style.display = 'none'; }
            function showModal() { modal.style.display = 'flex'; }

            closeModalX.addEventListener('click', hideModal);
            closeModalBtn.addEventListener('click', hideModal);
            window.onclick = function(event) {
                if (event.target == modal) hideModal();
            }

            // Form Submission
            document.getElementById('emailForm').addEventListener('submit', async e => {
                e.preventDefault();

                const submitBtn = e.target.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerText;
                submitBtn.innerText = "Generating...";
                submitBtn.disabled = true;

                try {
                    const formData = new FormData(e.target);
                    const res = await fetch('', { method:'POST', body: formData });
                    generatedData = await res.json();

                    emailListContainer.innerHTML = '';

                    if(generatedData.success && generatedData.emails) {
                        generatedData.emails.forEach((item, i) => {
                            emailListContainer.innerHTML += `
                                <div class="preview-item">
                                    <h6>To: ${item.name} &lt;${item.email}&gt;</h6>
                                    <textarea class="email-content" rows="8" style="width:100%; padding:10px; border-radius:8px; border:1px solid #ccc; font-family:'Quicksand';">${item.response}</textarea>
                                </div>`;
                        });
                        showModal();
                    } else {
                        alert("Error: " + (generatedData.error || generatedData.response || "Unknown error"));
                    }

                } catch (err) {
                    alert("Connection Error: " + err);
                } finally {
                    submitBtn.innerText = originalText;
                    submitBtn.disabled = false;
                }
            });

            // Send All Logic
            document.getElementById('sendAllBtn').onclick = async () => {
                if (!generatedData) return;

                const texts = [...document.querySelectorAll('.email-content')].map(t => t.value);
                const senderEmail = document.querySelector('input[name="sender"]').value;

                let emailsToSend = generatedData.emails.map((item, i) => ({
                    sender: senderEmail,
                    recipient_email: item.email,
                    body: texts[i]
                }));

                const sendBtn = document.getElementById('sendAllBtn');
                sendBtn.innerText = "Sending...";
                sendBtn.disabled = true;

                try {
                    // Assuming you have a 'send_email.php' handler, or you handle it here
                    await fetch('send_email.php', {
                        method: 'POST',
                        headers: {'Content-Type':'application/json'},
                        body: JSON.stringify(emailsToSend)
                    });
                    alert("Email(s) sent successfully!");
                    hideModal();
                } catch (err) {
                    alert("Failed to send emails: " + err);
                } finally {
                    sendBtn.innerText = "Send All";
                    sendBtn.disabled = false;
                }
            };
        });
        </script>
        <script src="https://kit.fontawesome.com/yourkit.js" crossorigin="anonymous"></script>
    </body>
</html>