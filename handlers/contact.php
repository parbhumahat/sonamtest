<?php
header('Content-Type: application/json');
require '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

$name    = trim($_POST['name']    ?? '');
$email   = trim($_POST['email']   ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

$errors = [];
if (strlen($name) < 2)                          $errors[] = 'Name is required.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'A valid email address is required.';
if (empty($subject))                            $errors[] = 'Please choose a subject.';
if (strlen($message) < 10)                      $errors[] = 'Your message must be at least 10 characters.';

if (!empty($errors)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
    exit;
}

try {
    $db = get_db();
    $stmt = $db->prepare(
        "INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)"
    );
    $stmt->execute([$name, $email, $subject, $message]);

    // Notify restaurant
    @mail(
        'hello@saffronandsalt.co.uk',
        "New message: $subject — from $name",
        "Name:    $name\nEmail:   $email\nSubject: $subject\n\n$message",
        "From: Website Contact <noreply@saffronandsalt.co.uk>\r\nReply-To: $email"
    );

    // Auto-reply to sender
    $autoReply = "Hi $name,\n\nThank you for getting in touch. We've received your message and will get back to you within one working day.\n\nIf your enquiry is urgent, please call us on 01904 123 456.\n\nWarm regards,\nSaffron & Salt\n14 Gillygate, York YO31 7EQ";
    @mail($email, 'We got your message — Saffron & Salt', $autoReply, "From: Saffron & Salt <hello@saffronandsalt.co.uk>");

    echo json_encode([
        'success' => true,
        'message' => "Thank you, $name — we've received your message and will reply within one working day.",
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Something went wrong saving your message. Please email us directly at hello@saffronandsalt.co.uk.']);
}
