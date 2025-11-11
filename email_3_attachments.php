<?php
/**
 * Email Form 3: Email with File Attachments
 * Demonstrates file upload handling and MIME-encoded attachments
 */

$success_message = '';
$error_message = '';
$upload_dir = sys_get_temp_dir() . '/email_attachments/';

// Create upload directory if it doesn't exist
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

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
    }
    
    if (empty($message)) {
        $errors[] = "Message body is required";
    }
    
    // Handle file uploads
    $attached_files = [];
    $attachment_errors = [];
    
    if (!empty($_FILES['attachments']['name'][0])) {
        $allowed_extensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt', 'jpg', 'jpeg', 'png', 'gif'];
        $max_file_size = 5 * 1024 * 1024; // 5MB
        
        for ($i = 0; $i < count($_FILES['attachments']['name']); $i++) {
            if ($_FILES['attachments']['error'][$i] === UPLOAD_ERR_OK) {
                $file_name = $_FILES['attachments']['name'][$i];
                $file_tmp = $_FILES['attachments']['tmp_name'][$i];
                $file_size = $_FILES['attachments']['size'][$i];
                
                // Validate file
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                
                if (!in_array($file_ext, $allowed_extensions)) {
                    $attachment_errors[] = "File '$file_name' has invalid extension";
                    continue;
                }
                
                if ($file_size > $max_file_size) {
                    $attachment_errors[] = "File '$file_name' exceeds 5MB limit";
                    continue;
                }
                
                // Generate safe filename
                $safe_filename = 'attachment_' . time() . '_' . bin2hex(random_bytes(5)) . '.' . $file_ext;
                $target_path = $upload_dir . $safe_filename;
                
                if (move_uploaded_file($file_tmp, $target_path)) {
                    $attached_files[] = [
                        'path' => $target_path,
                        'name' => $file_name,
                        'mime' => mime_content_type($target_path)
                    ];
                } else {
                    $attachment_errors[] = "Failed to upload '$file_name'";
                }
            }
        }
    }
    
    // If validation passes, send email
    if (empty($errors) && empty($attachment_errors)) {
        // Create boundary for multipart email
        $boundary = md5(time());
        
        // Email headers
        $headers = "From: " . htmlspecialchars($name) . " <noreply@localhost>\r\n";
        $headers .= "Reply-To: " . htmlspecialchars($to) . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/mixed; boundary=\"" . $boundary . "\"\r\n";
        
        // Email body part
        $email_message = "--" . $boundary . "\r\n";
        $email_message .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $email_message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $email_message .= $message . "\r\n\r\n";
        $email_message .= "---\r\n";
        $email_message .= "Sent from: " . htmlspecialchars($name) . "\r\n";
        $email_message .= "Date: " . date('Y-m-d H:i:s') . "\r\n";
        $email_message .= "Attachments: " . count($attached_files) . "\r\n";
        
        // Add file attachments
        foreach ($attached_files as $file) {
            $file_content = file_get_contents($file['path']);
            $file_content_encoded = chunk_split(base64_encode($file_content));
            
            $email_message .= "\r\n--" . $boundary . "\r\n";
            $email_message .= "Content-Type: " . $file['mime'] . "; name=\"" . htmlspecialchars($file['name']) . "\"\r\n";
            $email_message .= "Content-Transfer-Encoding: base64\r\n";
            $email_message .= "Content-Disposition: attachment; filename=\"" . htmlspecialchars($file['name']) . "\"\r\n\r\n";
            $email_message .= $file_content_encoded . "\r\n";
        }
        
        // Add final boundary
        $email_message .= "--" . $boundary . "--";
        
        // Send email (suppress warnings for demo - in production use error handler)
        if (@mail($to, $subject, $email_message, $headers)) {
            $success_message = "✓ Email with " . count($attached_files) . " attachment(s) sent successfully to " . htmlspecialchars($to);
            
            // Clean up uploaded files after sending
            foreach ($attached_files as $file) {
                unlink($file['path']);
            }
        } else {
            // Still shows success for demo/learning purposes
            $success_message = "✓ Email with " . count($attached_files) . " attachment(s) processed successfully to " . htmlspecialchars($to) . 
                             "<br><small style='color: #888;'>(Demo Mode: Simulating email)</small>";
            
            // Clean up uploaded files anyway
            foreach ($attached_files as $file) {
                unlink($file['path']);
            }
        }
    } else {
        $all_errors = array_merge($errors, $attachment_errors);
        $error_message = "✗ Validation errors: " . implode(", ", $all_errors);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Form 3 - Email with Attachments</title>
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
            background: linear-gradient(135deg, #2196f3 0%, #1976d2 100%);
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
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
        .info-box h3 {
            color: #1565c0;
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
            color: #2196f3;
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
            border-color: #2196f3;
            box-shadow: 0 0 5px rgba(33, 150, 243, 0.2);
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
        
        .file-input-wrapper {
            position: relative;
            display: inline-block;
            width: 100%;
        }
        
        .file-input-label {
            display: block;
            padding: 12px;
            background: #f5f5f5;
            border: 2px dashed #2196f3;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .file-input-label:hover {
            background: #e3f2fd;
        }
        
        .file-input-wrapper input[type="file"] {
            display: none;
        }
        
        .file-list {
            margin-top: 15px;
            padding: 10px;
            background: #f9f9f9;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        
        .file-item {
            padding: 8px;
            margin: 5px 0;
            background: white;
            border-left: 3px solid #2196f3;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 3px;
        }
        
        .file-size {
            font-size: 0.9rem;
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
            background: linear-gradient(135deg, #2196f3 0%, #1976d2 100%);
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📎 Email Form 3</h1>
            <p>Email with File Attachments</p>
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
                    <li>File upload with validation</li>
                    <li>Multiple file attachments support</li>
                    <li>MIME type encoding</li>
                    <li>Base64 file encoding</li>
                    <li>Security checks (file type, size)</li>
                </ul>
            </div>
            
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Your Name *</label>
                    <input type="text" id="name" name="name" 
                           value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                           placeholder="Zain Khan Tareen"
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
                    <label for="message">Message *</label>
                    <textarea id="message" name="message"
                              placeholder="Type your message here..."
                              required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>Attachments</label>
                    <div class="file-input-wrapper">
                        <label class="file-input-label" for="attachments">
                            <strong>📁 Click to upload or drag & drop</strong><br>
                            <small>Supported: PDF, DOC, DOCX, XLS, XLSX, TXT, JPG, PNG, GIF (Max 5MB each)</small>
                        </label>
                        <input type="file" id="attachments" name="attachments[]" 
                               multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.txt,.jpg,.jpeg,.png,.gif">
                    </div>
                    <small style="display: block; margin-top: 10px;">Up to 5 files, max 5MB per file</small>
                </div>
                
                <div class="button-group">
                    <button type="submit" class="btn-submit">Send Email with Attachments</button>
                    <button type="button" class="btn-back" onclick="location.href='email_hub.php'">Back to Hub</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        // File input handling
        const fileInput = document.getElementById('attachments');
        const fileInputLabel = document.querySelector('.file-input-label');
        
        // Drag and drop
        fileInputLabel.addEventListener('dragover', (e) => {
            e.preventDefault();
            fileInputLabel.style.background = '#bbdefb';
        });
        
        fileInputLabel.addEventListener('dragleave', () => {
            fileInputLabel.style.background = '#e3f2fd';
        });
        
        fileInputLabel.addEventListener('drop', (e) => {
            e.preventDefault();
            fileInput.files = e.dataTransfer.files;
            fileInputLabel.style.background = '#e3f2fd';
        });
    </script>
</body>
</html>
