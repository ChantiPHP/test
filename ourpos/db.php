<?php
$host = 'localhost';  // Database host
$db = 'pos_system';   // Database name
$user = 'root';       // Database username (default for XAMPP)
$pass = '';           // Database password (default for XAMPP)

try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>

