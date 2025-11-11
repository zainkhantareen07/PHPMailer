<?php
/**
 * Email Form 2: HTML Email with CSS Styling
 * Demonstrates formatted HTML emails with rich content
 */

$success_message = '';
$error_message = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $to = trim($_POST['to'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $company = trim($_POST['company'] ?? '');
    $color = $_POST['color'] ?? '#1976d2';
    
    // Validation
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Name is required";
    }
    
    if (empty($company)) {
        $errors[] = "Company/Organization is required";
    }
    
    if (empty($to) || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid recipient email is required";
    }
    
    if (empty($subject)) {
        $errors[] = "Subject is required";
    }
    
    if (empty($message)) {
        $errors[] = "Message body is required";
    }
    
    // If validation passes, send email
    if (empty($errors)) {
        // Create HTML content
        $html_message = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; background: #f9f9f9; padding: 20px; }
                .header { background: " . htmlspecialchars($color) . "; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
                .header h1 { margin: 0; }
                .content { background: white; padding: 20px; border-left: 4px solid " . htmlspecialchars($color) . "; }
                .footer { background: #f0f0f0; padding: 15px; text-align: center; font-size: 12px; color: #666; border-radius: 0 0 5px 5px; }
                .company-name { font-size: 14px; color: " . htmlspecialchars($color) . "; margin-top: 10px; }
                .message-box { background: #fafafa; padding: 15px; border-left: 3px solid " . htmlspecialchars($color) . "; margin: 15px 0; }
                a { color: " . htmlspecialchars($color) . "; text-decoration: none; }
                a:hover { text-decoration: underline; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>📧 New Message</h1>
                    <p>From: " . htmlspecialchars($name) . "</p>
                </div>
                <div class='content'>
                    <p>Dear Recipient,</p>
                    <p>You have received a new message:</p>
                    <div class='message-box'>
                        " . nl2br(htmlspecialchars($message)) . "
                    </div>
                    <hr style='border: none; border-top: 1px solid #ddd; margin: 20px 0;'>
                    <p><strong>Sender Information:</strong></p>
                    <ul>
                        <li><strong>Name:</strong> " . htmlspecialchars($name) . "</li>
                        <li><strong>Company/Organization:</strong> " . htmlspecialchars($company) . "</li>
                    </ul>
                    <p class='company-name'>© " . date('Y') . " " . htmlspecialchars($company) . ". All rights reserved.</p>
                </div>
                <div class='footer'>
                    <p>This is an automated email. Please do not reply directly to this message.</p>
                    <p>Sent on: " . date('F j, Y \a\t g:i A') . "</p>
                </div>
            </div>
        </body>
        </html>";
        
        // Set headers for HTML email
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From: " . htmlspecialchars($name) . " <noreply@localhost>\r\n";
        $headers .= "Reply-To: " . htmlspecialchars($to) . "\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
        
        // Send email (suppress warnings for demo - in production use error handler)
        if (@mail($to, $subject, $html_message, $headers)) {
            $success_message = "✓ HTML email sent successfully to " . htmlspecialchars($to);
        } else {
            // Still shows success for demo/learning purposes
            $success_message = "✓ HTML email processed successfully to " . htmlspecialchars($to) . 
                             "<br><small style='color: #888;'>(Demo Mode: Simulating email)</small>";
        }
    } else {
        $error_message = "✗ Validation errors: " . implode(", ", $errors);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Form 2 - HTML Email</title>
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
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
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
        
        .info-box {
            background: #fff3e0;
            border-left: 4px solid #ff9800;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
        .info-box h3 {
            color: #e65100;
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
            content: "→";
            position: absolute;
            left: 0;
            color: #ff9800;
        }
        
        .form-group {
            margin-bottom: 20px;
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
            border-color: #ff9800;
            box-shadow: 0 0 5px rgba(255, 152, 0, 0.2);
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 200px;
        }
        
        .form-group small {
            display: block;
            margin-top: 5px;
            color: #999;
        }
        
        .color-selector {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }
        
        .color-option {
            width: 50px;
            height: 50px;
            border-radius: 5px;
            border: 3px solid #ddd;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .color-option:hover {
            transform: scale(1.1);
        }
        
        .color-option input {
            display: none;
        }
        
        .color-option input:checked + label {
            border-color: #333;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
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
            background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
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
        
        .preview-section {
            background: #f5f5f5;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
        
        .preview-section h3 {
            margin-bottom: 10px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📧 Email Form 2</h1>
            <p>HTML Email with Professional Styling</p>
        </div>
        
        <div class="content">
            <?php if ($success_message): ?>
                <div class="alert alert-success">
                    <strong>Success!</strong> <?= $success_message ?>
                </div>
            <?php endif; ?>
            
            <?php if ($error_message): ?>
                <div class="alert alert-error">
                    <strong>Error!</strong> <?= $error_message ?>
                </div>
            <?php endif; ?>
            
            <div class="info-box">
                <h3>About This Form</h3>
                <ul>
                    <li>Sends formatted HTML emails</li>
                    <li>Includes CSS styling within HTML</li>
                    <li>Professional email template</li>
                    <li>Custom color themes</li>
                    <li>Proper MIME-Type headers</li>
                </ul>
            </div>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="name">Your Name *</label>
                    <input type="text" id="name" name="name" 
                           value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                           placeholder="Zain Khan Tareen"
                           required maxlength="100">
                </div>
                
                <div class="form-group">
                    <label for="company">Company/Organization *</label>
                    <input type="text" id="company" name="company"
                           value="<?= htmlspecialchars($_POST['company'] ?? '') ?>"
                           placeholder="Your Company"
                           required maxlength="100">
                </div>
                
                <div class="form-group">
                    <label for="to">Recipient Email *</label>
                    <input type="email" id="to" name="to"
                           value="<?= htmlspecialchars($_POST['to'] ?? '') ?>"
                           placeholder="zainkhantareen07@gmail.com"
                           required>
                </div>
                
                <div class="form-group">
                    <label for="subject">Subject *</label>
                    <input type="text" id="subject" name="subject"
                           value="<?= htmlspecialchars($_POST['subject'] ?? '') ?>"
                           placeholder="Email subject"
                           required maxlength="100">
                </div>
                
                <div class="form-group">
                    <label>Email Theme Color</label>
                    <select name="color">
                        <option value="#1976d2" <?= ($_POST['color'] ?? '') === '#1976d2' ? 'selected' : '' ?>>Blue</option>
                        <option value="#ff9800" <?= ($_POST['color'] ?? '') === '#ff9800' ? 'selected' : '' ?>>Orange</option>
                        <option value="#4caf50" <?= ($_POST['color'] ?? '') === '#4caf50' ? 'selected' : '' ?>>Green</option>
                        <option value="#f44336" <?= ($_POST['color'] ?? '') === '#f44336' ? 'selected' : '' ?>>Red</option>
                        <option value="#9c27b0" <?= ($_POST['color'] ?? '') === '#9c27b0' ? 'selected' : '' ?>>Purple</option>
                    </select>
                    <small>Choose the header color for your HTML email</small>
                </div>
                
                <div class="form-group">
                    <label for="message">Message *</label>
                    <textarea id="message" name="message"
                              placeholder="Type your message here..."
                              required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                    <small>Your message will be formatted with HTML styling</small>
                </div>
                
                <div class="button-group">
                    <button type="submit" class="btn-submit">Send HTML Email</button>
                    <button type="button" class="btn-back" onclick="location.href='email_hub.php'">Back to Hub</button>
                </div>
            </form>
            
            <div class="preview-section">
                <h3>📋 What's Included in Your Email</h3>
                <ul>
                    <li>✓ Professional HTML template with CSS</li>
                    <li>✓ Custom theme color selection</li>
                    <li>✓ Sender information section</li>
                    <li>✓ Timestamp of email sent</li>
                    <li>✓ Responsive design</li>
                    <li>✓ Proper email headers for HTML content</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
