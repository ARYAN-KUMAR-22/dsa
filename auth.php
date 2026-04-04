<?php
// auth.php
session_start();
require_once 'db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$action = $_POST['action'] ?? '';

if ($action === 'register') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
         echo json_encode(['success' => false, 'message' => 'Invalid email format.']);
         exit;
    }

    // Check if user runs exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username OR email = :email");
    $stmt->execute(['username' => $username, 'email' => $email]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Username or Application already exists.']);
        exit;
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    
    $insert = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password_hash)");
    try {
        $insert->execute([
            'username' => $username,
            'email' => $email,
            'password_hash' => $hash
        ]);
        
        // Auto-login after registration
        $_SESSION['user_id'] = $pdo->lastInsertId();
        $_SESSION['username'] = $username;
        
        echo json_encode(['success' => true, 'message' => 'Registration successful.', 'redirect' => 'dashboard.php']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error during registration.']);
    }
    
} elseif ($action === 'login') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Email and password are required.']);
        exit;
    }
    
    $stmt = $pdo->prepare("SELECT id, username, password_hash, is_admin FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['is_admin'] = (bool)$user['is_admin'];
        
        // Set an optional 'remember me' cookie (expires in 30 days)
        if (isset($_POST['remember'])) {
             setcookie('user_login', $user['username'], time() + (86400 * 30), "/");
        }
        
        $redirect = $_SESSION['is_admin'] ? 'admin.php' : 'dashboard.php';
        echo json_encode(['success' => true, 'message' => 'Login successful.', 'redirect' => $redirect]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid email or password.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid action.']);
}
?>
