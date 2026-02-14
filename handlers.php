<?php
// Database initialization and handlers
define('DB_PATH', __DIR__ . '/data.db');

// Read configuration from environment (for cloud deployments)
$DB_DRIVER = getenv('DB_DRIVER') ?: 'sqlite'; // sqlite, mysql, pgsql
$DB_HOST = getenv('DB_HOST') ?: '127.0.0.1';
$DB_NAME = getenv('DB_NAME') ?: 'app_db';
$DB_USER = getenv('DB_USER') ?: '';
$DB_PASS = getenv('DB_PASS') ?: '';
$APP_CSRF_ENABLED = getenv('APP_CSRF_ENABLED') === '1' ? true : false;

// Start session for optional CSRF tokens
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize database if it doesn't exist
function initDatabase() {
    global $DB_DRIVER, $DB_HOST, $DB_NAME, $DB_USER, $DB_PASS;
    try {
        if ($DB_DRIVER === 'sqlite') {
            if (!file_exists(DB_PATH)) {
                $db = new PDO('sqlite:' . DB_PATH);
            } else {
                $db = new PDO('sqlite:' . DB_PATH);
            }
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            createTables($db);
            return $db;
        }

        // For mysql or pgsql, construct DSN from environment variables
        if ($DB_DRIVER === 'mysql') {
            $dsn = "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4";
        } else {
            $dsn = "{$DB_DRIVER}:host={$DB_HOST};dbname={$DB_NAME}";
        }

        $db = new PDO($dsn, $DB_USER, $DB_PASS, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        createTables($db);
        return $db;

    } catch (Exception $e) {
        error_log('Database initialization failed: ' . $e->getMessage());
        throw new Exception('Database initialization failed: ' . $e->getMessage());
    }
}

// CORS headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-CSRF-Token');
header('Content-Type: application/json');

// Handle preflight OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Error handling
error_reporting(E_ALL);
set_error_handler(function($errno, $errstr) {
    error_log("PHP error [$errno]: $errstr");
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error. Please try again.']);
    exit;
});

$action = $_REQUEST['action'] ?? '';

try {
    if ($action === 'quote') {
        handleQuoteRequest();
    } elseif ($action === 'subscribe') {
        handleSubscribe();
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error. Please try again.']);
}

function handleQuoteRequest() {
    try {
        $db = initDatabase();

        // Rate limiting: max 10 submissions per 60 seconds per IP
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        if (!checkRateLimit($db, $ip, 10, 60, 'quote')) {
            http_response_code(429);
            echo json_encode(['success' => false, 'message' => 'Too many requests. Please try again later.']);
            return;
        }

        // Validate input
        $name = sanitize($_POST['name'] ?? '');
        $email = sanitizeEmail($_POST['email'] ?? '');
        $phone = sanitize($_POST['phone'] ?? '');
        $message = sanitize($_POST['message'] ?? '');

        if (empty($name) || empty($email)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Name and email are required.']);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
            return;
        }

        // Validate email length (prevent buffer overflow)
        if (strlen($email) > 255) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Email address is too long.']);
            return;
        }

        // Insert into database using prepared statements (prevents SQL injection)
        $stmt = $db->prepare("INSERT INTO quotes (name, email, phone, message) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $phone, $message]);

        // Send email to admin using sanitized data (prevents header injection)
        $to = getenv('ADMIN_EMAIL') ?: 'info@rivernilleconstruction.co.ke';
        $subject = "New Quote Request from " . $name;
        $body = "Name: $name\nEmail: $email\nPhone: $phone\n\nMessage:\n$message";
        // Security headers prevent newline injection and other email header attacks
        $headers = "From: noreply@rivernilleconstruction.co.ke\r\nReply-To: " . $email . "\r\nX-Mailer: PHP/" . phpversion();
        
        // Try native mail but log outcome — replace with PHPMailer/SMTP in production
        $mailResult = false;
        try {
            $mailResult = @mail($to, $subject, $body, $headers);
            if (!$mailResult) error_log('Mail send failed for quote request from ' . $email);
        } catch (Exception $me) {
            error_log('Mail exception: ' . $me->getMessage());
        }

        echo json_encode([
            'success' => true,
            'message' => 'Thank you! We will contact you soon.'
        ]);
    } catch (Exception $e) {
        error_log('handleQuoteRequest error: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error processing request. Please try again.']);
    }
}

function handleSubscribe() {
    try {
        $db = initDatabase();

        // Rate limiting for subscribe: max 6 per 60 seconds per IP
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        if (!checkRateLimit($db, $ip, 6, 60, 'subscribe')) {
            http_response_code(429);
            echo json_encode(['success' => false, 'message' => 'Too many requests. Please try again later.']);
            return;
        }

        $email = sanitizeEmail($_POST['email'] ?? '');

        if (empty($email)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Email is required.']);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
            return;
        }

        // Validate email length
        if (strlen($email) > 255) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Email address is too long.']);
            return;
        }

        // Check if already subscribed
        $stmt = $db->prepare("SELECT id FROM subscribers WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->fetch()) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Already subscribed.']);
            return;
        }

        // Insert into database using prepared statements
        $stmt = $db->prepare("INSERT INTO subscribers (email) VALUES (?)");
        $stmt->execute([$email]);

        // Send confirmation email with safe headers (prevents header injection)
        $subject = "Welcome to RiverNille Newsletter";
        $body = "Thank you for subscribing to RiverNille Construction updates!\n\nWe'll keep you informed about our latest projects and services.";
        $headers = "From: noreply@rivernilleconstruction.co.ke\r\nX-Mailer: PHP/" . phpversion();
        try {
            $mailResult = @mail($email, $subject, $body, $headers);
            if (!$mailResult) error_log('Mail send failed for subscription to ' . $email);
        } catch (Exception $me) {
            error_log('Mail exception: ' . $me->getMessage());
        }

        echo json_encode([
            'success' => true,
            'message' => 'Welcome! Check your email for confirmation.'
        ]);
    } catch (Exception $e) {
        error_log('handleSubscribe error: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Subscription failed. Please try again.']);
    }
}

function createTables($db) {
    $driver = $db->getAttribute(PDO::ATTR_DRIVER_NAME);
    if ($driver === 'sqlite') {
        $db->exec("CREATE TABLE IF NOT EXISTS subscribers (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            email TEXT UNIQUE NOT NULL,
            subscribed_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");

        $db->exec("CREATE TABLE IF NOT EXISTS quotes (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            email TEXT NOT NULL,
            phone TEXT,
            message TEXT,
            submitted_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");

        $db->exec("CREATE TABLE IF NOT EXISTS rate_limits (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            ip TEXT NOT NULL,
            action TEXT NOT NULL,
            created_at INTEGER NOT NULL
        )");
    } elseif ($driver === 'mysql') {
        $db->exec("CREATE TABLE IF NOT EXISTS subscribers (
            id INT PRIMARY KEY AUTO_INCREMENT,
            email VARCHAR(255) UNIQUE NOT NULL,
            subscribed_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $db->exec("CREATE TABLE IF NOT EXISTS quotes (
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            phone VARCHAR(50),
            message TEXT,
            submitted_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $db->exec("CREATE TABLE IF NOT EXISTS rate_limits (
            id INT PRIMARY KEY AUTO_INCREMENT,
            ip VARCHAR(255) NOT NULL,
            action VARCHAR(100) NOT NULL,
            created_at INT NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    } else {
        // Fallback: try generic SQL
        $db->exec("CREATE TABLE IF NOT EXISTS subscribers (
            id SERIAL PRIMARY KEY,
            email TEXT UNIQUE NOT NULL,
            subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        $db->exec("CREATE TABLE IF NOT EXISTS quotes (
            id SERIAL PRIMARY KEY,
            name TEXT NOT NULL,
            email TEXT NOT NULL,
            phone TEXT,
            message TEXT,
            submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        $db->exec("CREATE TABLE IF NOT EXISTS rate_limits (
            id SERIAL PRIMARY KEY,
            ip TEXT NOT NULL,
            action TEXT NOT NULL,
            created_at INTEGER NOT NULL
        )");
    }
}

function checkRateLimit($db, $ip, $maxCalls = 10, $windowSeconds = 60, $action = 'global') {
    try {
        $now = time();
        $threshold = $now - $windowSeconds;
        // Cleanup old entries
        $stmt = $db->prepare('DELETE FROM rate_limits WHERE created_at < ?');
        $stmt->execute([$threshold]);

        $stmt = $db->prepare('SELECT COUNT(*) as cnt FROM rate_limits WHERE ip = ? AND action = ? AND created_at >= ?');
        $stmt->execute([$ip, $action, $threshold]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $count = $row ? (int)$row['cnt'] : 0;

        if ($count >= $maxCalls) {
            return false;
        }

        $stmt = $db->prepare('INSERT INTO rate_limits (ip, action, created_at) VALUES (?, ?, ?)');
        $stmt->execute([$ip, $action, $now]);
        return true;
    } catch (Exception $e) {
        error_log('Rate limiter error: ' . $e->getMessage());
        return true;
    }
}

function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Sanitize email to prevent header injection
 * Removes any newlines, carriage returns, or tab characters
 * 
 * Attack prevention:
 * - Blocks: "user@example.com\nBcc: attacker@example.com"
 * - Blocks: "user@example.com%0ABcc: attacker@example.com"
 * - Blocks: tab characters that could break headers
 */
function sanitizeEmail($input) {
    // Remove all potentially dangerous characters for email headers
    $email = trim($input);
    $email = str_replace(["\r", "\n", "\t"], '', $email);
    $email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
    
    // Additional safety: remove any control characters
    $email = preg_replace('/[\x00-\x1F\x7F]/', '', $email);
    
    return $email;
}
?>
