<?php
// livesearch.php
header("Content-Type: application/json");

$host = 'localhost';
$user = 'username';
$password = 'password';
$database = 'grocery_tracker';

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

$search_query = $_GET['item-name'];
$selected_store = $_GET['store'];
$selected_city = $_GET['location'];

$sql = "SELECT grocery_items.id, grocery_items.name, grocery_items.description, grocery_items.image_url, stores.name AS store_name, grocery_item_prices.price
FROM grocery_items
JOIN grocery_item_prices ON grocery_items.id = grocery_item_prices.grocery_item_id
JOIN stores ON grocery_item_prices.store_id = stores.id
WHERE grocery_items.name LIKE '%$search_query%'
AND stores.city = '$selected_city'
AND stores.name = '$selected_store'
ORDER BY grocery_items.name ASC";

$result = $conn->query($sql);

$rows = array();
while($row = $result->fetch_assoc()) {
  $rows[] = $row;
}

echo json_encode($rows);

mysqli_close($conn);
?>
