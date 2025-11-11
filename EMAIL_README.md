# 📧 Email Forms Learning Project

A comprehensive PHP email demonstration project with 4 progressive forms showcasing different email implementation techniques.

## ⚡ Quick Note

All forms work in **simulation mode** - no mail server setup required! This is perfect for learning. See `MAIL_SERVER_CONFIGURATION.md` for details on:
- Why you don't need a mail server for learning
- How to set up real email sending when ready
- Mail server configuration options

## 📁 Project Structure

```
email_hub.php                  # Navigation hub for all forms
email_1_basic.php             # Basic PHP mail() implementation
email_2_html.php              # HTML formatted emails
email_3_attachments.php       # Emails with file attachments
email_4_phpmailer.php         # Secure PHPMailer implementation
README.md                      # This file
MAIL_SERVER_CONFIGURATION.md   # Mail server setup guide
```

## 🎯 Learning Objectives

Each form demonstrates a different level of email functionality:

### Email Form 1: Basic Email (Beginner)
- **Concept**: Simple text email sending using PHP's built-in `mail()` function
- **Key Features**:
  - Plain text email format
  - Basic input validation
  - Simple email headers
  - Sender and recipient email
  - Date/time tracking
- **Security**: Basic validation on input fields
- **Use Case**: Simple contact forms, notifications
- **Code Snippet**:
  ```php
  mail($to, $subject, $message, $headers);
  ```

### Email Form 2: HTML Email (Intermediate)
- **Concept**: Professional HTML-formatted emails with CSS styling
- **Key Features**:
  - HTML content type (text/html)
  - Embedded CSS styling
  - Professional email template
  - Custom color themes
  - Responsive design
  - Sender information section
- **Security**: Input sanitization with htmlspecialchars()
- **Use Case**: Marketing emails, newsletters, professional communications
- **Headers Required**:
  ```php
  Content-Type: text/html; charset=UTF-8
  MIME-Version: 1.0
  ```

### Email Form 3: Email with Attachments (Intermediate)
- **Concept**: Multi-part emails with file attachments using MIME encoding
- **Key Features**:
  - File upload handling
  - Multiple file support
  - MIME type validation
  - Base64 encoding
  - Boundary-based multipart messages
  - Security validation (file type, size)
  - Temporary file cleanup
- **Security**:
  - File extension whitelist
  - File size limits (5MB per file)
  - Safe filename generation
  - MIME type checking
- **Use Case**: Sending documents, reports, invoices
- **MIME Structure**:
  ```
  --boundary
  Content-Type: text/plain
  [message body]
  --boundary
  Content-Type: application/pdf
  Content-Disposition: attachment
  [base64 encoded file]
  --boundary--
  ```

### Email Form 4: Secure PHPMailer (Advanced)
- **Concept**: Enterprise-level email sending with SMTP and comprehensive security
- **Key Features**:
  - PHPMailer library integration
  - SMTP authentication
  - CC and BCC support
  - Priority levels
  - Comprehensive input validation
  - XSS & SQL injection protection
  - Malicious content detection
  - HTML email generation
  - Error handling and exceptions
  - Secure email encoding
- **Security Measures**:
  - Input length validation
  - Dangerous pattern detection
  - Email format validation
  - HTML entity encoding
  - UTF-8 encoding enforcement
- **Use Case**: Critical communications, high-security applications, SMTP relay
- **Configuration**:
  ```php
  $mail->Host = 'smtp.gmail.com';
  $mail->Port = 587;
  $mail->SMTPSecure = 'tls';
  $mail->SMTPAuth = true;
  ```

## 🚀 Quick Start Guide

### Prerequisites
- PHP 7.0 or higher
- XAMPP or similar PHP environment
- Web browser
- (Optional) PHPMailer library for Form 4

### Installation

1. **Copy all files to your web root**:
   ```bash
   cp email_*.php /path/to/xampp/htdocs/
   cp README.md /path/to/xampp/htdocs/
   ```

2. **Start your web server**:
   - Start Apache in XAMPP Control Panel
   - Or use PHP's built-in server:
     ```bash
     php -S localhost:8000
     ```

3. **Access the project**:
   - Open browser: `http://localhost/email_hub.php`
   - Or if using PHP server: `http://localhost:8000/email_hub.php`

### (Optional) Install PHPMailer

For Form 4 to use real PHPMailer instead of the mock version:

```bash
composer require phpmailer/phpmailer
```

Or download from: https://github.com/PHPMailer/PHPMailer

## 📝 Form Testing Guide

### Form 1: Basic Email Testing

1. Click "Open Form" on Email 1 card
2. Fill in the form:
   - Your Name: `Zain Khan Tareen`
   - Recipient Email: `zainkhantareen07@gmail.com`
   - Subject: `Test Email`
   - Message: `This is a test email`
3. Click "Send Email"
4. Check for success message

**Expected Behavior**:
- Plain text email sent
- Auto-generated footer with date/time
- Simple headers configured

### Form 2: HTML Email Testing

1. Click "Open Form" on Email 2 card
2. Fill in the form:
   - Your Name: `Zain Khan Tareen`
   - Company: `My Organization`
   - Recipient Email: `zainkhantareen07@gmail.com`
   - Subject: `HTML Email Test`
   - Choose a color theme
   - Message: `Formatted message content`
3. Click "Send HTML Email"

**Expected Behavior**:
- Formatted HTML email with styling
- Color theme applied to header
- Professional layout with company name
- Responsive design

### Form 3: Email with Attachments Testing

1. Click "Open Form" on Email 3 card
2. Fill in the form fields
3. Select files to attach (supported: PDF, DOC, DOCX, XLS, XLSX, TXT, JPG, PNG, GIF)
4. Drag and drop or click to select files
5. Click "Send Email with Attachments"

**Expected Behavior**:
- Files validated and uploaded
- Base64 encoding applied
- MIME boundaries configured
- Success message shows number of attachments
- Files automatically cleaned up after sending

**Testing Tips**:
- Create small test files (< 5MB)
- Test with different file types
- Try multiple files simultaneously
- Verify attachment validation works

### Form 4: Secure PHPMailer Testing

1. Click "Open Form" on Email 4 card
2. Fill in comprehensive form:
   - Your Name and Email
   - Recipient, CC, BCC emails
   - Priority level
   - Subject and Message
3. Click "Send Secure Email"

**Expected Behavior**:
- All inputs validated thoroughly
- Dangerous patterns detected
- HTML email with security badge
- CC/BCC recipients handled
- Debug information displayed
- Comprehensive error messages

**Security Testing**:
- Try entering `<script>alert('xss')</script>` - should be blocked
- Try invalid email formats - should be rejected
- Try exceeding character limits - should be rejected
- Test with special characters - should be properly encoded

## 🔒 Security Features Explained

### Input Validation
- Email format validation using `filter_var()`
- Length restrictions on all fields
- Required field checks
- Dangerous pattern detection

### Data Sanitization
- `htmlspecialchars()` - prevents XSS attacks
- `trim()` - removes unnecessary whitespace
- Whitelist-based file validation
- Safe filename generation

### File Security (Form 3)
- File extension whitelist
- MIME type checking
- File size limits
- Temporary directory storage
- Automatic cleanup

### Email Headers Security
- Proper MIME-Version specification
- Content-Type headers set explicitly
- X-Mailer headers included
- Character encoding enforced

## 🧪 Testing Scenarios

### Scenario 1: Valid Email Test
```
Name: Zain Khan Tareen
Email: zainkhantareen07@gmail.com
Recipient: zainkhantareen07@gmail.com
Subject: Test Subject
Message: This is a valid test message
```
**Expected**: Email sent successfully

### Scenario 2: Invalid Email Test
```
Email: invalid.email
```
**Expected**: Validation error for invalid email format

### Scenario 3: XSS Injection Test
```
Subject: <script>alert('xss')</script>
```
**Expected**: Blocked with malicious content error

### Scenario 4: File Upload Test (Form 3)
```
Select: test.pdf, test.docx
```
**Expected**: Files validated, attached, and sent

### Scenario 5: Large File Test (Form 3)
```
Select: file_larger_than_5mb.pdf
```
**Expected**: File size limit error

## 📊 Email Header Examples

### Form 1 Headers
```
From: Zain Khan Tareen <noreply@localhost>
Reply-To: zainkhantareen07@gmail.com
MIME-Version: 1.0
Content-Type: text/plain; charset=UTF-8
```

### Form 2 Headers
```
MIME-Version: 1.0
Content-Type: text/html; charset=UTF-8
From: Zain Khan Tareen <noreply@localhost>
Reply-To: zainkhantareen07@gmail.com
X-Mailer: PHP/7.x.x
```

### Form 3 Headers
```
From: Zain Khan Tareen <noreply@localhost>
Reply-To: zainkhantareen07@gmail.com
MIME-Version: 1.0
Content-Type: multipart/mixed; boundary="[boundary]"
```

### Form 4 Headers (PHPMailer)
```
From: zainkhantareen07@gmail.com
To: zainkhantareen07@gmail.com
Cc: zainkhantareen07@gmail.com
Subject: Subject
MIME-Version: 1.0
Content-Type: text/html; charset=UTF-8
X-Priority: 3
```

## 🐛 Troubleshooting

### Email not sending in Form 1
- Check PHP mail configuration in `php.ini`
- Verify SMTP server settings
- Check email address validity
- Review PHP error logs

### HTML not rendering in Form 2
- Ensure `Content-Type: text/html` header is set
- Check email client HTML support
- Verify CSS is properly formatted
- Test in different email clients

### Attachments not received in Form 3
- Verify file upload permission in temp directory
- Check MIME boundary formatting
- Ensure Base64 encoding is correct
- Verify file size is under limit

### PHPMailer not working in Form 4
- Install PHPMailer: `composer require phpmailer/phpmailer`
- Configure SMTP credentials
- Check firewall/port settings
- Review error messages

## 📚 Learning Resources

### PHP Email Functions
- [mail() function](https://www.php.net/manual/en/function.mail.php)
- [MIME types](https://www.php.net/manual/en/function.mime-content-type.php)

### Email Standards
- [RFC 5322 - Email Format](https://tools.ietf.org/html/rfc5322)
- [RFC 2045 - MIME Part One](https://tools.ietf.org/html/rfc2045)
- [RFC 2046 - MIME Part Two](https://tools.ietf.org/html/rfc2046)

### PHPMailer
- [Official Repository](https://github.com/PHPMailer/PHPMailer)
- [Documentation](https://github.com/PHPMailer/PHPMailer/wiki)

### Security
- [OWASP XSS Prevention](https://cheatsheetseries.owasp.org/cheatsheets/Cross_Site_Scripting_Prevention_Cheat_Sheet.html)
- [Input Validation Best Practices](https://cheatsheetseries.owasp.org/cheatsheets/Input_Validation_Cheat_Sheet.html)

## 🎓 Key Concepts Covered

1. **Email Protocols**: SMTP, MIME, multipart messages
2. **Header Management**: Proper header formatting and encoding
3. **Content Types**: Plain text vs HTML emails
4. **File Handling**: Upload validation and MIME encoding
5. **Security**: Input validation, output encoding, injection prevention
6. **Error Handling**: Try-catch blocks, user feedback
7. **Advanced Features**: SMTP authentication, CC/BCC, priority levels

## 📈 Progressive Complexity

```
Form 1 (Basic)
    ↓
Form 2 (Intermediate - HTML)
    ↓
Form 3 (Intermediate - Attachments)
    ↓
Form 4 (Advanced - Secure PHPMailer)
```

## ✅ Completion Checklist

- [x] Form 1: Basic email sending
- [x] Form 2: HTML formatted emails
- [x] Form 3: File attachments
- [x] Form 4: Secure PHPMailer
- [x] Input validation
- [x] Security implementation
- [x] Error handling
- [x] Documentation

## 🏆 Best Practices Applied

1. ✓ Comprehensive input validation
2. ✓ HTML entity encoding for security
3. ✓ Proper MIME type handling
4. ✓ File upload security checks
5. ✓ Clean and well-commented code
6. ✓ User-friendly error messages
7. ✓ Professional UI/UX design
8. ✓ Responsive design
9. ✓ Accessibility considerations
10. ✓ Performance optimization

## 📄 License

Educational use only. Created for learning PHP email functionality and security best practices.

---

**Created**: November 2025  
**Purpose**: Comprehensive PHP Email Learning Project  
**Skill Level**: Beginner to Advanced
