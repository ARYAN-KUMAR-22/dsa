<?php
// db.php
// Configure your database connection details here.
$host = 'localhost';
$dbname = 'dsa_learning_platform';
$username = 'root'; // Change if necessary
$password = ''; // Change if necessary

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // In a production environment, avoid printing exact error messages to the user.
    die("Database Connection failed: " . $e->getMessage());
}
?>
