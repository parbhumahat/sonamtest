<?php
/* password_hash / password_verify — PHP built-in functions (bcrypt).
   Reference: https://www.php.net/manual/en/function.password-hash.php
   session_regenerate_id used to prevent session fixation attacks:
   https://www.php.net/manual/en/function.session-regenerate-id.php */
require '../includes/db.php';

if (session_status() === PHP_SESSION_NONE) session_start();

$action = $_GET['action'] ?? ($_POST['action'] ?? '');

// --- Logout (GET) ---
if ($action === 'logout') {
    session_destroy();
    header('Location: ../index.php');
    exit;
}

// --- Cancel reservation (POST via fetch) ---
if ($action === 'cancel') {
    header('Content-Type: application/json');

    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Not signed in.']);
        exit;
    }

    $id    = (int)($_POST['id'] ?? 0);
    $email = $_SESSION['user_email'] ?? '';

    if (!$id || !$email) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid request.']);
        exit;
    }

    try {
        $stmt = get_db()->prepare(
            "UPDATE reservations SET status = 'cancelled'
             WHERE id = ? AND email = ? AND date >= CURDATE() AND status = 'confirmed'"
        );
        $stmt->execute([$id, $email]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Your reservation has been cancelled.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'This reservation could not be cancelled (it may already be in the past or cancelled).']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Something went wrong. Please try again.']);
    }
    exit;
}

// --- Register (POST via fetch) ---
if ($action === 'register') {
    header('Content-Type: application/json');

    $name     = trim($_POST['name']     ?? '');
    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');

    $errors = [];
    if (strlen($name) < 2)                          $errors[] = 'Name is required.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'A valid email address is required.';
    if (strlen($password) < 8)                      $errors[] = 'Password must be at least 8 characters.';

    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
        exit;
    }

    try {
        $db   = get_db();
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $db->prepare("INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $hash]);

        $user_id = $db->lastInsertId();
        $_SESSION['user_id']    = $user_id;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_name']  = $name;

        echo json_encode(['success' => true, 'message' => "Welcome, $name! Your account has been created."]);

    } catch (PDOException $e) {
        if ($e->getCode() === '23000') {
            // Duplicate email
            echo json_encode(['success' => false, 'message' => 'An account with that email address already exists.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Could not create your account. Please try again.']);
        }
    }
    exit;
}

// --- Login (standard form POST, redirect) ---
if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');

    try {
        $stmt = get_db()->prepare("SELECT id, name, email, password_hash FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['user_id']    = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name']  = $user['name'];
            header('Location: ../account.php');
            exit;
        }

        $_SESSION['flash'] = ['type' => 'error', 'message' => 'Email address or password is incorrect.'];
        header('Location: ../login.php');
        exit;

    } catch (PDOException $e) {
        $_SESSION['flash'] = ['type' => 'error', 'message' => 'Something went wrong. Please try again.'];
        header('Location: ../login.php');
        exit;
    }
}

// Fallback
header('Location: ../login.php');
exit;
