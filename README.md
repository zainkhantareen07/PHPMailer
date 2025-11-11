# PHPMailer Complete Email System

A comprehensive PHP email system featuring multiple implementations with PHPMailer library, security features, and various email formats.

## Features

### Email Forms
- **Email Form 1**: Basic email sending with PHP mail()
- **Email Form 2**: HTML email with styling
- **Email Form 3**: Email with file attachments
- **Email Form 4**: Secure PHPMailer implementation with SMTP

### Security Features
- Input validation and sanitization
- XSS and SQL injection protection
- Email format validation
- Secure HTML encoding
- Priority level support
- CC/BCC functionality

### PHPMailer Integration
- SMTP configuration support
- Real PHPMailer library detection
- Fallback mock implementation for demo
- Error handling and debugging

## Installation

1. Clone or download this repository
2. Place files in your web server's document root
3. Configure SMTP settings in `phpmailer_config.php` (copy from `phpmailer_config.php.example`)
4. Access `email_hub.php` to navigate between different email forms

## Configuration

### SMTP Setup
Edit `phpmailer_config.php` with your SMTP credentials:

```php
$config = [
    'host' => 'smtp.gmail.com',
    'username' => 'your-email@gmail.com',
    'password' => 'your-app-password',
    'smtp_secure' => 'tls',
    'port' => 587,
    'smtp_auth' => true,
    'smtp_debug' => 0
];
```

### Gmail Setup
For Gmail SMTP:
1. Enable 2-factor authentication
2. Generate an App Password
3. Use the App Password in configuration

## File Structure

```
PHPMailer-Complete/
├── email_hub.php              # Main navigation hub
├── email_1_basic.php          # Basic email form
├── email_2_html.php           # HTML email form
├── email_3_attachments.php    # Email with attachments
├── email_4_phpmailer.php      # Secure PHPMailer form
├── test_send.php              # Test script
├── phpmailer_config.php       # SMTP configuration
├── phpmailer_config.php.example # Configuration template
├── composer.json              # Composer dependencies
├── composer.lock              # Composer lock file
├── vendor/                    # PHPMailer library (install via composer)
└── README.md                  # This file
```

## Usage

1. Start with `email_hub.php` for navigation
2. Choose the appropriate email form based on your needs
3. Fill in the form fields and submit
4. Check for success/error messages

## Requirements

- PHP 7.0 or higher
- Web server (Apache/Nginx)
- PHPMailer library (install via Composer) or use built-in mock

## Installation with Composer

```bash
composer install
```

This will install the PHPMailer library and dependencies.

## Testing

Run the test script to verify functionality:

```bash
php test_send.php
```

## Security Notes

- Never commit real SMTP credentials to version control
- Use environment variables for production credentials
- Validate all user inputs
- Use HTTPS in production
- Regularly update dependencies

## License

This project is open source and available under the MIT License.

## Support

For issues or questions, please check the individual PHP files for comments and documentation.
