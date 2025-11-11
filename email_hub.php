<?php
/**
 * Email Forms Hub - Navigation page for all email demonstrations
 * Demonstrates understanding of basic, HTML, attachment-based, and secure email sending
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Forms Hub</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            max-width: 1000px;
            width: 100%;
        }
        
        .header {
            text-align: center;
            color: white;
            margin-bottom: 50px;
        }
        
        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        
        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        .forms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }
        
        .form-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .form-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
        }
        
        .form-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
        }
        
        .form-header h2 {
            font-size: 1.5rem;
            margin-bottom: 5px;
        }
        
        .form-header .level {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .form-body {
            padding: 20px;
        }
        
        .form-body p {
            color: #555;
            margin-bottom: 15px;
            line-height: 1.6;
        }
        
        .features {
            list-style: none;
            margin: 15px 0;
        }
        
        .features li {
            color: #666;
            padding: 5px 0;
            padding-left: 25px;
            position: relative;
        }
        
        .features li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #667eea;
            font-weight: bold;
        }
        
        .form-footer {
            padding: 0 20px 20px;
            text-align: center;
        }
        
        .btn {
            display: inline-block;
            padding: 10px 25px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: opacity 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }
        
        .btn:hover {
            opacity: 0.9;
        }
        
        .btn-secondary {
            background: #e0e0e0;
            color: #333;
        }
        
        .btn-secondary:hover {
            background: #d0d0d0;
        }
        
        .footer {
            text-align: center;
            color: white;
            margin-top: 50px;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .difficulty {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 0.8rem;
            margin-top: 10px;
        }
        
        .difficulty.basic {
            background: #4caf50;
            color: white;
        }
        
        .difficulty.intermediate {
            background: #ff9800;
            color: white;
        }
        
        .difficulty.advanced {
            background: #f44336;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📧 Email Forms Hub</h1>
            <p>Master PHP Email Functionality with 4 Progressive Examples</p>
        </div>
        
        <div class="forms-grid">
            <!-- Email 1: Basic PHP Mail -->
            <div class="form-card">
                <div class="form-header">
                    <h2>Email 1</h2>
                    <div class="level">Basic Email</div>
                </div>
                <div class="form-body">
                    <p><strong>Simple Text Email Sending</strong></p>
                    <ul class="features">
                        <li>PHP mail() function</li>
                        <li>Plain text format</li>
                        <li>Basic validation</li>
                        <li>Simple headers</li>
                    </ul>
                    <div class="difficulty basic">Difficulty: Basic</div>
                </div>
                <div class="form-footer">
                    <a href="email_1_basic.php" class="btn">Open Form</a>
                </div>
            </div>
            
            <!-- Email 2: HTML Email -->
            <div class="form-card">
                <div class="form-header">
                    <h2>Email 2</h2>
                    <div class="level">HTML Email</div>
                </div>
                <div class="form-body">
                    <p><strong>Formatted HTML Email with Styling</strong></p>
                    <ul class="features">
                        <li>HTML content type</li>
                        <li>CSS styling</li>
                        <li>Rich formatting</li>
                        <li>Professional design</li>
                    </ul>
                    <div class="difficulty intermediate">Difficulty: Intermediate</div>
                </div>
                <div class="form-footer">
                    <a href="email_2_html.php" class="btn">Open Form</a>
                </div>
            </div>
            
            <!-- Email 3: Email with Attachments -->
            <div class="form-card">
                <div class="form-header">
                    <h2>Email 3</h2>
                    <div class="level">Attachments</div>
                </div>
                <div class="form-body">
                    <p><strong>Email with File Upload & Attachments</strong></p>
                    <ul class="features">
                        <li>File upload handling</li>
                        <li>MIME encoding</li>
                        <li>Multi-part messages</li>
                        <li>Security validation</li>
                    </ul>
                    <div class="difficulty intermediate">Difficulty: Intermediate</div>
                </div>
                <div class="form-footer">
                    <a href="email_3_attachments.php" class="btn">Open Form</a>
                </div>
            </div>
            
            <!-- Email 4: Secure PHPMailer -->
            <div class="form-card">
                <div class="form-header">
                    <h2>Email 4</h2>
                    <div class="level">PHPMailer</div>
                </div>
                <div class="form-body">
                    <p><strong>Advanced SMTP with Security</strong></p>
                    <ul class="features">
                        <li>PHPMailer library</li>
                        <li>SMTP authentication</li>
                        <li>Input sanitization</li>
                        <li>Error handling</li>
                    </ul>
                    <div class="difficulty advanced">Difficulty: Advanced</div>
                </div>
                <div class="form-footer">
                    <a href="email_4_phpmailer.php" class="btn">Open Form</a>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <p>💡 Each form demonstrates progressive complexity in email handling with PHP</p>
            <p style="margin-top: 10px; opacity: 0.7;">Created for learning email functionality and security best practices</p>
        </div>
    </div>
</body>
</html>
