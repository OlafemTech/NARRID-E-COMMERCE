<?php
// subscribe.php – handle hero newsletter email sign-ups
declare(strict_types=1);
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit;
}

require_once __DIR__ . '/db.php';

try {
    $pdo = DB::instance();
    // Insert into DB
    $stmt = $pdo->prepare("INSERT INTO newsletter_subs (email) VALUES (:email)");
    $stmt->execute(['email' => $email]);
} catch (Exception $e) {
    // Log error in production
}

// Send email to team (basic mail, replace with SMTP/PHPMailer in prod)
$to      = 'care@narrid.com';
$subject = 'New newsletter signup';
$message = "New signup from: $email";
$headers = 'From: noreply@narrid.com' . "\r\n";
mail($to, $subject, $message, $headers);

echo json_encode(['success' => true]);
?>
