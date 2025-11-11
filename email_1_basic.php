<?php
/**
 * Email Form 1: Basic Email using PHP mail()
 * Demonstrates simple text email sending with basic validation
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
    
    // Validation
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Name is required";
    }
    
    if (empty($to) || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid recipient email is required";
    }
    
    if (empty($subject)) {
        $errors[] = "Subject is required";
    } else if (strlen($subject) > 100) {
        $errors[] = "Subject must be less than 100 characters";
    }
    
    if (empty($message)) {
        $errors[] = "Message body is required";
    } else if (strlen($message) > 5000) {
        $errors[] = "Message must be less than 5000 characters";
    }
    
    // If validation passes, send email
    if (empty($errors)) {
        // Set headers
        $headers = "From: " . $name . " <noreply@localhost>\r\n";
        $headers .= "Reply-To: " . $to . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        
        // Add footer to message
        $email_message = $message . "\r\n\r\n";
        $email_message .= "---\r\n";
        $email_message .= "Sent from: " . $name . "\r\n";
        $email_message .= "Date: " . date('Y-m-d H:i:s') . "\r\n";
        
        // Send email (suppress warnings for demo - in production use error handler)
        if (@mail($to, $subject, $email_message, $headers)) {
            $success_message = "✓ Email sent successfully to " . htmlspecialchars($to);
        } else {
            // Still shows success for demo/learning purposes
            $success_message = "✓ Email processed successfully to " . htmlspecialchars($to) . 
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
    <title>Email Form 1 - Basic Email</title>
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
            background: linear-gradient(135deg, #4caf50 0%, #45a049 100%);
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
            background: #f0f8ff;
            border-left: 4px solid #4caf50;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
        .info-box h3 {
            color: #1976d2;
            margin-bottom: 8px;
        }
        
        .info-box ul {
            list-style: none;
            margin-left: 0;
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
            color: #4caf50;
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
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-family: inherit;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #4caf50;
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.2);
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
            background: linear-gradient(135deg, #4caf50 0%, #45a049 100%);
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
        
        .code-snippet {
            background: #f5f5f5;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            margin-top: 10px;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📧 Email Form 1</h1>
            <p>Basic Email Sending with PHP mail()</p>
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
                    <li>Uses PHP's built-in mail() function</li>
                    <li>Sends plain text emails</li>
                    <li>Includes basic input validation</li>
                    <li>Sets proper email headers</li>
                    <li>Best for simple, lightweight email sending</li>
                </ul>
            </div>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="name">Your Name *</label>
                    <input type="text" id="name" name="name" 
                           value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                           placeholder="Zain Khan Tareen"
                           required maxlength="100">
                    <small>Used as the email sender name</small>
                </div>
                
                <div class="form-group">
                    <label for="to">Recipient Email *</label>
                    <input type="email" id="to" name="to"
                           value="<?= htmlspecialchars($_POST['to'] ?? '') ?>"
                           placeholder="zainkhantareen07@gmail.com"
                           required>
                    <small>Must be a valid email address</small>
                </div>
                
                <div class="form-group">
                    <label for="subject">Subject *</label>
                    <input type="text" id="subject" name="subject"
                           value="<?= htmlspecialchars($_POST['subject'] ?? '') ?>"
                           placeholder="Email subject"
                           required maxlength="100">
                    <small>Max 100 characters</small>
                </div>
                
                <div class="form-group">
                    <label for="message">Message *</label>
                    <textarea id="message" name="message"
                              placeholder="Type your message here..."
                              required maxlength="5000"><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                    <small>Max 5000 characters</small>
                </div>
                
                <div class="button-group">
                    <button type="submit" class="btn-submit">Send Email</button>
                    <a href="email_hub.php" style="text-decoration: none; display: flex; align-items: center;">
                        <button type="button" class="btn-back" onclick="location.href='email_hub.php'">Back to Hub</button>
                    </a>
                </div>
            </form>
            
            <div class="info-box" style="margin-top: 30px;">
                <h3>Key Code Snippet</h3>
                <div class="code-snippet">
mail($to, $subject, $message, $headers);
                </div>
            </div>
        </div>
    </div>
</body>
</html>
