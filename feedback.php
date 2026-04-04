<?php
// feedback.php
require_once 'db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');

if (empty($name) || empty($email) || empty($message)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
     echo json_encode(['success' => false, 'message' => 'Invalid email format.']);
     exit;
}

$insert = $pdo->prepare("INSERT INTO feedbacks (name, email, message) VALUES (:name, :email, :message)");
try {
    $insert->execute([
        'name' => $name,
        'email' => $email,
        'message' => $message
    ]);
    echo json_encode(['success' => true, 'message' => 'Thank you! Your feedback has been received.']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error while saving feedback.']);
}
?>
