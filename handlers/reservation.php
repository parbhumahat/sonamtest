<?php
header('Content-Type: application/json');
require '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

// Sanitise and validate inputs
$name   = trim($_POST['name']   ?? '');
$email  = trim($_POST['email']  ?? '');
$phone  = trim($_POST['phone']  ?? '');
$date   = trim($_POST['date']   ?? '');
$time   = trim($_POST['time']   ?? '');
$guests = (int) ($_POST['guests'] ?? 0);
$notes  = trim($_POST['notes']  ?? '');

$errors = [];
if (strlen($name) < 2)                               $errors[] = 'Name is required.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL))      $errors[] = 'A valid email address is required.';
if (strlen($phone) < 7)                              $errors[] = 'Phone number is required.';
if (!$date || $date < date('Y-m-d'))                 $errors[] = 'Please choose a date today or in the future.';
if (!preg_match('/^\d{2}:\d{2}$/', $time))           $errors[] = 'Please select a valid time.';
if ($guests < 1 || $guests > 8)                      $errors[] = 'Party size must be between 1 and 8.';

if (!empty($errors)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
    exit;
}

try {
    $db = get_db();

    // Check for an existing confirmed booking at the same date/time (optional cap at 5 concurrent)
    $check = $db->prepare("SELECT COUNT(*) FROM reservations WHERE date = ? AND time = ? AND status = 'confirmed'");
    $check->execute([$date, $time]);
    if ((int) $check->fetchColumn() >= 5) {
        echo json_encode(['success' => false, 'message' => 'Sorry, that time slot is fully booked. Please choose a different time.']);
        exit;
    }

    $stmt = $db->prepare(
        "INSERT INTO reservations (name, email, phone, date, time, guests, notes)
         VALUES (?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->execute([$name, $email, $phone, $date, $time, $guests, $notes]);

    // Send confirmation email to guest
    $formatted_date = date('l j F Y', strtotime($date));
    $formatted_time = date('g:ia', strtotime($time));
    $to      = $email;
    $subject = "Your table at Saffron & Salt — $formatted_date at $formatted_time";
    $body    = "Dear $name,\n\n"
             . "Your reservation is confirmed. Here are the details:\n\n"
             . "  Date:  $formatted_date\n"
             . "  Time:  $formatted_time\n"
             . "  Guests: $guests\n\n"
             . "We're at 14 Gillygate, York YO31 7EQ.\n"
             . "If you need to cancel or change your booking, please give us at least 24 hours' notice.\n\n"
             . "We look forward to welcoming you.\n\n"
             . "Saffron & Salt\n"
             . "01904 123 456 | hello@saffronandsalt.co.uk";
    $headers = "From: Saffron & Salt <hello@saffronandsalt.co.uk>\r\n"
             . "Reply-To: hello@saffronandsalt.co.uk\r\n"
             . "Content-Type: text/plain; charset=UTF-8";
    @mail($to, $subject, $body, $headers);

    // Notify the restaurant
    @mail(
        'hello@saffronandsalt.co.uk',
        "New reservation: $name — $formatted_date at $formatted_time ($guests guests)",
        "Name:   $name\nEmail:  $email\nPhone:  $phone\nDate:   $formatted_date\nTime:   $formatted_time\nGuests: $guests\nNotes:  $notes",
        "From: Booking System <noreply@saffronandsalt.co.uk>"
    );

    echo json_encode([
        'success' => true,
        'message' => "Thank you, $name! Your table for $guests on $formatted_date at $formatted_time is confirmed. We've sent a confirmation to $email.",
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'We couldn\'t save your reservation. Please try again or call us on 01904 123 456.']);
}
