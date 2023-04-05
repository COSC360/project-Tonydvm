

<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
session_start();


require_once 'connect.php';

// Get user ID from session
$user_id = $_SESSION['user']['id'];

// Get grocery_item_id from query parameters
$grocery_item_id = $_GET['grocery_item_id'];

// Insert item into cart
$sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id, $_POST['product_id'], $_POST['quantity']]);

// Close statement
$stmt = null;

// Close connection
$pdo = null;

// Redirect to landing.php
header('Location: landing.php');
exit();
?>
