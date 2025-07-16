<?php
/**
 * Simple contact form handler for Narrid pre-launch page.
 * Expects POST fields: name, gender, email, address, category, subcategory, message
 * Sends details to care@narrid.com and returns a JSON { success: bool } response.
 *
 * NOTE: For production you may want to switch to SMTP (e.g. PHPMailer) on your Contabo VPS
 *       and add server-side validation & CSRF protection.
 */

header('Content-Type: application/json');

// Allow only POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

// Helper
function sanitize($key) {
    return isset($_POST[$key]) ? trim(htmlspecialchars($_POST[$key])) : '';
}

$name       = sanitize('name');
$gender     = sanitize('gender');
$email      = sanitize('email');
$address    = sanitize('address');
$category   = sanitize('category');
$subcategory= sanitize('subcategory');
$message    = sanitize('message');

// Basic validation
if (!$name || !$gender || !$email || !$category || !$subcategory) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid email']);
    exit;
}

$to      = 'care@narrid.com';
$subject = "[Pre-Launch] Contact form – $category / $subcategory";

$body  = "Name: $name\n";
$body .= "Gender: $gender\n";
$body .= "Email: $email\n";
$body .= "Address: $address\n";
$body .= "Category: $category\n";
$body .= "Sub-Option: $subcategory\n";
$body .= "Message:\n$message\n";

$headers   = [
    'From' => 'no-reply@narrid.com',
    'Reply-To' => $email,
    'X-Mailer' => 'PHP/' . phpversion()
];

// Save to database
try {
    require_once __DIR__ . '/db.php';
    $pdo = DB::conn();
    $stmt = $pdo->prepare("INSERT INTO contact_messages (name, gender, email, address, category, subcategory, message) VALUES (?,?,?,?,?,?,?)");
    $stmt->execute([$name, $gender, $email, $address, $category, $subcategory, $message]);
} catch (Exception $e) {
    // Log error but continue to send mail
}

$success = mail($to, $subject, $body, $headers);

if ($success) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Mail failed']);
}
