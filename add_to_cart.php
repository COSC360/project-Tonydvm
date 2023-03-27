<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
session_start();

// Connect to the database
$host = 'localhost';
$user = '76865732';
$password = '76865732';
$database = 'db_76865732';
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get user ID from session
$user_id = $_SESSION['user']['id'];

// Insert item into cart
$sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('iii', $user_id, $_POST['product_id'], $_POST['quantity']);
$stmt->execute();

// Close connection
$stmt->close();
$conn->close();

// Redirect to landing.php
header('Location: landing.php');
exit();
?>