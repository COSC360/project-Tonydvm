<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
  header('Location: login.html');
  exit();
}

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

// Insert review into user_reviews
$sql = "INSERT INTO user_reviews (user_id, grocery_item_id, rating, comment) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('iiis', $user_id, $_POST['product_id'], $_POST['rating'], $_POST['comment']);
$stmt->execute();

// Close connection
$stmt->close();
$conn->close();

// Redirect to product_details.php
header('Location: product_details.php?id=' . $_POST['product_id']);
exit();
?>