<?php
// livesearch.php
header("Content-Type: application/json");

// Connect to the database
$host = 'localhost';
$user = 'username';
$password = '76865732';
$database = 'db_76865732';
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$search_query = '%' . $_GET['item-name'] . '%';
$selected_store = $_GET['store'];
$selected_city = $_GET['location'];

$sql = "SELECT grocery_items.id, grocery_items.name, grocery_items.description, grocery_items.image_url, stores.name AS store_name, grocery_item_prices.price
FROM grocery_items
JOIN grocery_item_prices ON grocery_items.id = grocery_item_prices.grocery_item_id
JOIN stores ON grocery_item_prices.store_id = stores.id
WHERE grocery_items.name LIKE ?
AND stores.city = ?
AND stores.name = ?
ORDER BY grocery_items.name ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param('sss', $search_query, $selected_city, $selected_store);
$stmt->execute();

$result = $stmt->get_result();

$rows = array();
while($row = $result->fetch_assoc()) {
  $rows[] = $row;
}

echo json_encode($rows);

$stmt->close();
$conn->close();
?>

