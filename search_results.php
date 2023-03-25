<?php
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

// Get search query and selected store and city from the form submission
$search_query = '%' . $_GET['item-name'] . '%';
$selected_store = $_GET['store'];
$selected_city = $_GET['location'];

// Build and execute SQL query using prepared statements
$sql = "SELECT grocery_items.id, grocery_items.name, grocery_items.description, grocery_items.image_url, stores.name AS store_name, grocery_item_prices.price
FROM grocery_items
JOIN grocery_item_prices ON grocery_items.id = grocery_item_prices.grocery_item_id
JOIN stores ON grocery_item_prices.store_id = stores.id
WHERE grocery_items.name LIKE ?
AND (stores.city = ? OR ? = '')
AND (stores.name = ? OR ? = '')
ORDER BY grocery_items.name ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param('sssss', $search_query, $selected_city, $selected_city, $selected_store, $selected_store);
$stmt->execute();

$result = $stmt->get_result();

// Check if there are any results
if ($result->num_rows > 0) {
  echo "<table>";
  echo "<tr><th>Image</th><th>ID</th><th>Name</th><th>Price($CAN)</th></tr>";
  // Output data of each row
  while($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td><img src='" . $row["image_url"] . "' alt='" . $row["name"] . "' width='100' height='100'></td>";
    echo "<td>" . $row["id"]. "</td>";
    echo "<td><a href='product_details.php?id=" . $row['id'] . "'>" . $row['name'] . "</a></td>";
    echo "<td>" . $row["price"]. "</td>";
    echo "</tr>";
}
echo "</table>";
} else {
  echo "No results found.";
}

// Close connection
$stmt->close();
$conn->close();
?>
