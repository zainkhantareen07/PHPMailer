<?php
/**
 * Email Form 4: Secure PHPMailer Implementation
 * Demonstrates advanced email sending with SMTP, security, and error handling
 */

$success_message = '';
$error_message = '';
$debug_info = '';

// PHPMailer configuration (using Gmail SMTP as example)
// Note: You would need to install PHPMailer via Composer or include the library
// For this demo, we'll show a functional implementation

// Try to locate Composer's autoload from several likely locations
$phpmailer_available = false;
$autoload_paths = [
    __DIR__ . '/vendor/autoload.php',                 // same folder (if composer used here)
    __DIR__ . '/phpmailercgpt/vendor/autoload.php',    // sibling phpmailercgpt folder
    __DIR__ . '/../phpmailercgpt/vendor/autoload.php', // parent -> phpmailercgpt
    __DIR__ . '/../../phpmailercgpt/vendor/autoload.php'// two levels up
];
foreach ($autoload_paths as $phpmailer_path) {
    if (file_exists($phpmailer_path)) {
        require_once $phpmailer_path;
        $phpmailer_available = true;
        break;
    }
}

// Load optional local config (not committed) or example config
$config = [];
$local_config = __DIR__ . '/phpmailer_config.php';
$example_config = __DIR__ . '/phpmailer_config.php.example';
if (file_exists($local_config)) {
    // phpmailer_config.php must set $config = [ ... ];
    require $local_config;
} elseif (file_exists($example_config)) {
    require $example_config;
}

// If PHPMailer isn't available via Composer, create a lightweight mock for demo
if (!$phpmailer_available) {
    // Mock PHPMailer class for demonstration
    class PHPMailer {
        private $from = '';
        private $to = [];
        private $subject = '';
        private $body = '';
        private $isHtml = true;
        private $host = '';
        private $port = 587;
        private $username = '';
        private $password = '';
        private $smtpAuth = false;
        private $smtpSecure = 'tls';
        
        public function setFrom($email, $name = '') {
            $this->from = $email;
        }
        
        public function addAddress($email, $name = '') {
            $this->to[] = $email;
        }
        
        public function Subject($subject) {
            $this->subject = $subject;
        }
        
        public function isHTML($isHtml = true) {
            $this->isHtml = $isHtml;
        }
        
        public function Body($body) {
            $this->body = $body;
        }
        
        public function send() {
            // For demonstration purposes, simulate successful email sending
            // without actually connecting to SMTP server. This avoids
            // "Failed to connect to mailserver" errors on local machines.
            return true;
        }
    }
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $to = trim($_POST['to'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $cc = trim($_POST['cc'] ?? '');
    $bcc = trim($_POST['bcc'] ?? '');
    $priority = $_POST['priority'] ?? '3';
    
    // Validation with detailed checks
    $errors = [];
    
    // Sender validation
    if (empty($name)) {
        $errors[] = "Your name is required";
    } elseif (strlen($name) > 100) {
        $errors[] = "Name must be less than 100 characters";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid sender email is required";
    }
    
    // Recipient validation
    if (empty($to) || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid recipient email is required";
    }
    
    // CC validation
    if (!empty($cc)) {
        $cc_emails = array_map('trim', explode(',', $cc));
        foreach ($cc_emails as $cc_email) {
            if (!filter_var($cc_email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid email in CC field: " . htmlspecialchars($cc_email);
            }
        }
    }
    
    // BCC validation
    if (!empty($bcc)) {
        $bcc_emails = array_map('trim', explode(',', $bcc));
        foreach ($bcc_emails as $bcc_email) {
            if (!filter_var($bcc_email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid email in BCC field: " . htmlspecialchars($bcc_email);
            }
        }
    }
    
    // Subject and message validation
    if (empty($subject)) {
        $errors[] = "Subject is required";
    } elseif (strlen($subject) > 100) {
        $errors[] = "Subject must be less than 100 characters";
    }
    
    if (empty($message)) {
        $errors[] = "Message body is required";
    } elseif (strlen($message) > 5000) {
        $errors[] = "Message must be less than 5000 characters";
    }
    
    // Check for SQL injection and XSS patterns
    $dangerous_patterns = ['<script', 'javascript:', 'onerror=', 'onclick=', 'union select', 'drop table'];
    foreach ([$subject, $message, $name] as $field) {
        foreach ($dangerous_patterns as $pattern) {
            if (stripos($field, $pattern) !== false) {
                $errors[] = "Potentially malicious content detected";
                break;
            }
        }
    }
    
    // If validation passes, send email
    if (empty($errors)) {
        // Create HTML email body
        $html_body = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; }
                .container { max-width: 600px; margin: 0 auto; background: #f9f9f9; padding: 20px; }
                .header { background: #667eea; color: white; padding: 20px; text-align: center; }
                .content { background: white; padding: 20px; }
                .footer { background: #f0f0f0; padding: 15px; text-align: center; font-size: 12px; }
                .priority { display: inline-block; padding: 5px 10px; background: #ff9800; color: white; border-radius: 3px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Secure Email Message</h1>
                </div>
                <div class='content'>
                    <p>Dear Recipient,</p>
                    <p>You have received a new message:</p>
                    <div style='background: #f5f5f5; padding: 15px; border-left: 3px solid #667eea; margin: 15px 0;'>
                        " . nl2br(htmlspecialchars($message)) . "
                    </div>
                    <hr>
                    <p><strong>Message Details:</strong></p>
                    <ul>
                        <li><strong>From:</strong> " . htmlspecialchars($name) . " (" . htmlspecialchars($email) . ")</li>
                        <li><strong>Priority:</strong> <span class='priority'>" . htmlspecialchars(['Low', 'Normal', 'High'][$priority - 1] ?? 'Normal') . "</span></li>
                        <li><strong>Date:</strong> " . date('F j, Y \a\t g:i A') . "</li>
                    </ul>
                </div>
                <div class='footer'>
                    <p>This email was sent using secure PHPMailer implementation.</p>
                </div>
            </div>
        </body>
        </html>";

        // Now attempt to send the message (real PHPMailer or mock)
        try {
            // Create a new PHPMailer instance. Support both the real PHPMailer
            // (installed via Composer) and the lightweight mock above.
            $use_real_phpmailer = class_exists('PHPMailer\\PHPMailer\\PHPMailer');

            if ($use_real_phpmailer) {
                // Fully-qualified names used to avoid top-level 'use' statements
                $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

                // Apply SMTP configuration from $config (if provided)
                $mail->SMTPDebug = $config['smtp_debug'] ?? 0;
                $mail->isSMTP();
                $mail->Host = $config['host'] ?? 'smtp.example.com';
                $mail->SMTPAuth = $config['smtp_auth'] ?? true;
                $mail->Username = $config['username'] ?? '';
                $mail->Password = $config['password'] ?? '';
                $mail->SMTPSecure = $config['smtp_secure'] ?? 'tls';
                $mail->Port = $config['port'] ?? 587;
                if (!empty($config['smtp_options']) && is_array($config['smtp_options'])) {
                    $mail->SMTPOptions = $config['smtp_options'];
                }

                // Envelope
                $mail->setFrom($email, $name);
                $mail->addAddress($to);

                // Add CC recipients using correct PHPMailer method
                if (!empty($cc)) {
                    $cc_emails = array_map('trim', explode(',', $cc));
                    foreach ($cc_emails as $cc_email) {
                        $mail->addCC($cc_email);
                    }
                }
                // Add BCC recipients
                if (!empty($bcc)) {
                    $bcc_emails = array_map('trim', explode(',', $bcc));
                    foreach ($bcc_emails as $bcc_email) {
                        $mail->addBCC($bcc_email);
                    }
                }

                // Subject and body (real PHPMailer API)
                $mail->Subject = $subject;
                $mail->isHTML(true);
                $mail->Body = $html_body;

                // Attempt to send via SMTP
                try {
                    $mail->send();
                    $success_message = "✓ Secure email sent (via configured SMTP).<br>";
                    $success_message .= "Recipients: " . htmlspecialchars($to);
                    if (!empty($cc)) {
                        $success_message .= "<br>CC: " . htmlspecialchars($cc);
                    }
                    $debug_info = "SMTP: host={$mail->Host}; port={$mail->Port}; auth=" . ($mail->SMTPAuth ? 'yes' : 'no');
                } catch (\PHPMailer\PHPMailer\Exception $e) {
                    // Provide more actionable guidance for authentication failures
                    $err = $e->getMessage();
                    $error_message = "✗ Mailer Error: " . htmlspecialchars($err);
                    if (stripos($err, 'authenticate') !== false || stripos($err, 'Auth') !== false || stripos($err, 'Username and Password not accepted') !== false) {
                        $error_message .= "<br><small>Please verify your SMTP username/password. If using Gmail, use an App Password when 2FA is enabled and enable SMTP access.</small>";
                    }
                }
            } else {
                // Fallback to the demo/mock PHPMailer above
                $mail = new PHPMailer();

                // Set sender/recipient using mock API
                $mail->setFrom($email, $name);
                $mail->addAddress($to);
                if (!empty($cc)) {
                    $cc_emails = array_map('trim', explode(',', $cc));
                    foreach ($cc_emails as $cc_email) {
                        $mail->addAddress($cc_email);
                    }
                }

                $mail->Subject($subject);
                $mail->isHTML(true);
                $mail->Body($html_body);

                // Simulate send (mock returns true)
                if ($mail->send()) {
                    $success_message = "✓ Secure email simulated successfully!<br>";
                    $success_message .= "Recipients: " . htmlspecialchars($to);
                    if (!empty($cc)) {
                        $success_message .= "<br>CC: " . htmlspecialchars($cc);
                    }
                    $success_message .= "<br><small style='color: #666; margin-top: 10px;'>ℹ️ (Demo Mode: Simulating email transmission - no actual mail server connection required)</small>";
                    $debug_info = "Email simulated (mock PHPMailer). Validation: PASSED | Encoding: UTF-8";
                } else {
                    $error_message = "✗ Failed to send email using PHPMailer (mock)";
                }
            }

        } catch (Exception $e) {
            $error_message = "✗ Mailer Exception: " . htmlspecialchars($e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Form 4 - Secure PHPMailer</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 700px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        
        .header p {
            opacity: 0.9;
        }
        
        .content {
            padding: 30px;
        }
        
        .security-badge {
            display: inline-block;
            background: #4caf50;
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            margin-bottom: 20px;
        }
        
        .info-box {
            background: #ffebee;
            border-left: 4px solid #f44336;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
        .info-box h3 {
            color: #c62828;
            margin-bottom: 8px;
        }
        
        .info-box ul {
            list-style: none;
        }
        
        .info-box li {
            color: #555;
            padding: 5px 0;
            padding-left: 20px;
            position: relative;
        }
        
        .info-box li:before {
            content: "🔒";
            position: absolute;
            left: 0;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .form-row.full {
            grid-template-columns: 1fr;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }
        
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-family: inherit;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #f44336;
            box-shadow: 0 0 5px rgba(244, 67, 54, 0.2);
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 150px;
        }
        
        .form-group small {
            display: block;
            margin-top: 5px;
            color: #999;
        }
        
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }
        
        button {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: opacity 0.3s;
        }
        
        .btn-submit {
            background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);
            color: white;
        }
        
        .btn-submit:hover {
            opacity: 0.9;
        }
        
        .btn-back {
            background: #e0e0e0;
            color: #333;
        }
        
        .btn-back:hover {
            background: #d0d0d0;
        }
        
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            animation: slideIn 0.3s ease;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .alert-success {
            background: #d4edda;
            border-left: 4px solid #28a745;
            color: #155724;
        }
        
        .alert-error {
            background: #f8d7da;
            border-left: 4px solid #f5c6cb;
            color: #721c24;
        }
        
        .debug-info {
            background: #f5f5f5;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            font-size: 0.85rem;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔒 Email Form 4</h1>
            <p>Secure PHPMailer with Advanced Security</p>
        </div>
        
        <div class="content">
            <div class="security-badge">✓ SECURE EMAIL SYSTEM</div>
            
            <?php if ($success_message): ?>
                <div class="alert alert-success">
                    <strong>Success!</strong> <?= $success_message ?>
                </div>
                <?php if ($debug_info): ?>
                    <div class="debug-info">📊 <?= $debug_info ?></div>
                <?php endif; ?>
            <?php endif; ?>
            
            <?php if ($error_message): ?>
                <div class="alert alert-error">
                    <strong>Error!</strong> <?= $error_message ?>
                </div>
            <?php endif; ?>
            
            <div class="info-box">
                <h3>Security Features</h3>
                <ul>
                    <li>PHPMailer library for SMTP</li>
                    <li>Input validation & sanitization</li>
                    <li>XSS & SQL injection protection</li>
                    <li>Email format validation</li>
                    <li>Secure HTML encoding</li>
                    <li>Priority level support</li>
                </ul>
            </div>
            
            <form method="POST" action="">
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Your Name *</label>
                        <input type="text" id="name" name="name" 
                               value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                               placeholder="Zain Khan Tareen"
                               required maxlength="100">
                        <small>Sender name</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Your Email *</label>
                        <input type="email" id="email" name="email"
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                               placeholder="zainkhantareen07@gmail.com"
                               required>
                        <small>Sender email address</small>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="to">Recipient Email *</label>
                        <input type="email" id="to" name="to"
                               value="<?= htmlspecialchars($_POST['to'] ?? '') ?>"
                               placeholder="zainkhantareen07@gmail.com"
                               required>
                        <small>Primary recipient</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="priority">Priority Level</label>
                        <select name="priority">
                            <option value="1" <?= ($_POST['priority'] ?? '3') === '1' ? 'selected' : '' ?>>Low</option>
                            <option value="2" <?= ($_POST['priority'] ?? '3') === '2' ? 'selected' : '' ?>>Normal</option>
                            <option value="3" selected>High</option>
                        </select>
                        <small>Message priority</small>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="cc">CC Emails</label>
                        <input type="email" id="cc" name="cc"
                               value="<?= htmlspecialchars($_POST['cc'] ?? '') ?>"
                               placeholder="zainkhantareen07@gmail.com">
                        <small>Separate multiple emails with commas</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="bcc">BCC Emails</label>
                        <input type="email" id="bcc" name="bcc"
                               value="<?= htmlspecialchars($_POST['bcc'] ?? '') ?>"
                               placeholder="zainkhantareen07@gmail.com">
                        <small>Separate multiple emails with commas</small>
                    </div>
                </div>
                
                <div class="form-row full">
                    <div class="form-group">
                        <label for="subject">Subject *</label>
                        <input type="text" id="subject" name="subject"
                               value="<?= htmlspecialchars($_POST['subject'] ?? '') ?>"
                               placeholder="Email subject"
                               required maxlength="100">
                        <small>Max 100 characters</small>
                    </div>
                </div>
                
                <div class="form-row full">
                    <div class="form-group">
                        <label for="message">Message *</label>
                        <textarea id="message" name="message"
                                  placeholder="Type your secure message here..."
                                  required maxlength="5000"><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                        <small>Max 5000 characters</small>
                    </div>
                </div>
                
                <div class="button-group">
                    <button type="submit" class="btn-submit">Send Secure Email</button>
                    <button type="button" class="btn-back" onclick="location.href='email_hub.php'">Back to Hub</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>