<?php
// CLI test runner to simulate POST to email_4_phpmailer.php
// Tests real PHPMailer SMTP functionality

// Ensure we're in the correct directory
chdir(__DIR__);

// Simulate POST for email_4_phpmailer.php
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST = [
    'name' => 'Zain Khan Tareen',
    'email' => 'zk344693@gmail.com',
    'to' => 'zainkhantareen07@gmail.com',
    'subject' => 'PHPMailer SMTP Test - Real Email',
    'message' => 'This is a test message sent via real PHPMailer SMTP. If you receive this, the configuration is working correctly!',
    'cc' => '',
    'bcc' => '',
    'priority' => '3'
];

// Include the form script which will process the POST
// Capture output so we can inspect the success message
ob_start();
include __DIR__ . '/email_4_phpmailer.php';
$html = ob_get_clean();

// Debug output: show variables set by the included script
echo "=== PHPMailer SMTP Test Results ===\n";
echo "Testing real email sending via Gmail SMTP...\n\n";

// success and error messages (if set)
if (isset($success_message)) {
    echo "✅ SUCCESS MESSAGE: "; var_export($success_message); echo "\n";
} else {
    echo "❌ success_message: [NOT SET]\n";
}

if (isset($error_message)) {
    echo "❌ ERROR MESSAGE: "; var_export($error_message); echo "\n";
} else {
    echo "✅ error_message: [NOT SET]\n";
}

// Check for debug info
if (isset($debug_info)) {
    echo "📊 DEBUG INFO: "; var_export($debug_info); echo "\n";
}

// Check if PHPMailer was detected
global $phpmailer_available, $use_real_phpmailer;
if (isset($phpmailer_available)) {
    echo "📚 PHPMailer Available: " . ($phpmailer_available ? 'YES' : 'NO') . "\n";
}
if (isset($use_real_phpmailer)) {
    echo "🔧 Using Real PHPMailer: " . ($use_real_phpmailer ? 'YES' : 'NO') . "\n";
}

// Also print a short excerpt of the rendered HTML to help debugging
echo "\n--- HTML RESPONSE EXCERPT (first 500 chars) ---\n";
echo substr($html, 0, 500) . "\n";

echo "\n=== Test Complete ===\n";
echo "If you see 'Secure email sent (via configured SMTP)', the email was sent successfully!\n";
echo "Check your inbox at zainkhantareen07@gmail.com\n";
?>
