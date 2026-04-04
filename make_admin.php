<?php
// make_me_admin.php
// Run this file once in your browser to seed an admin account!
require_once 'db.php';

$username = 'admin';
$email = 'admin@algolens.dev';
$password = 'admin123'; // Make sure to change this!
$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("SELECT id FROM users WHERE username = 'admin'");
$stmt->execute();

if ($stmt->fetch()) {
    die("Admin already exists! You can log in using username: admin, password: admin123");
}

$insert = $pdo->prepare("INSERT INTO users (username, email, password_hash, is_admin) VALUES (:username, :email, :password_hash, 1)");
$insert->execute([
    'username' => $username,
    'email' => $email,
    'password_hash' => $hash
]);

echo "Success! Admin account created. <br> Username: admin <br> Password: admin123 <br> <a href='index.php'>Go to Login</a>";
// Delete this file after running for security.
?>
