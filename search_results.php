<!DOCTYPE html>
<html>

<head>
  <title>Search Results</title>
  <link rel="stylesheet" href="css/reset.css" />
  <link rel="stylesheet" href="css/landing.css">
  <script src="js/livesearch.js"></script>

  <script src="showHint.js">
  </script>
</head>

<body>
  <header>
    <?php
      require_once 'header_min.php';
    ?>
  </header>
  <main>
    <h1>Search Results</h1>
    <form method="post" class="search-form" action="">
      <label for="item-name">Search for Item Name:</label>
      <input type="text" id="item-name" autocomplete="off" name="item-name" placeholder="Search for a product..."
        onkeyup="showHint(this.value)">
      <div id="search-suggestions"></div>

      <label for="store">Store:</label>
      <select id="store" name="store">
        <option value="">Any Store</option>
        <option value="Save-On Foods">Save-On Foods</option>
        <option value="Canadian Supermarket">Canadian Supermarket</option>
        <option value="Costco">Costco</option>
        <option value="Walmart">Walmart</option>
        <option value="Independent Grocer">Independent Grocer</option>
      </select>



      <label for="location">Location:</label>
      <select id="location" name="location">
        <option value="">Any Location</option>
        <option value="Toronto">Toronto</option>
        <option value="Vancouver">Vancouver</option>
        <option value="Montreal">Montreal</option>
        <option value="Calgary">Calgary</option>
        <option value="Kelowna">Kelowna</option>
        <option value="Edmonton">Edmonton</option>
        <option value="Ottawa">Ottawa</option>
        <option value="Quebec City">Quebec City</option>
      </select>

      <button type="submit">Search</button>

      <div id="search-results">
        <!-- Search results will be displayed here -->
      </div>
    </form>
    <p>Suggestions: <span id="txtHint"></span></p>

    <div class="body-container">
    <?php
      // Include the connect.php file to establish a connection using the $pdo variable
      require_once 'connect.php';

      // Get search query and selected store and city from form submission
      /*
      $search_query = '%' . $_POST['item-name'] . '%';
      $selected_city = $_POST['location'];
      $selected_store = $_POST['store'];

      // if null values are passed, set them to empty strings
      if ($selected_city == null) {
        $selected_city = '';
      }
      if ($selected_store == null) {
        $selected_store = '';
      }
      */

      $search_query = isset($_POST['item-name']) ? '%' . $_POST['item-name'] . '%' : '%';
      $selected_city = isset($_POST['location']) ? $_POST['location'] : '';
      $selected_store = isset($_POST['store']) ? $_POST['store'] : '';

      if (isset($_GET['category'])) {
        $selected_category = $_GET['category'];
      } else {
        $selected_category = '%';
      }


      // trim whitespace from search query
      $search_query = trim($search_query);
      $sql = "SELECT grocery_items.id, grocery_items.name, grocery_items.description, grocery_items.image_url, stores.name AS store_name, grocery_item_prices.price
      FROM grocery_items
      JOIN grocery_item_prices ON grocery_items.id = grocery_item_prices.grocery_item_id
      JOIN stores ON grocery_item_prices.store_id = stores.id
      WHERE grocery_items.name LIKE ?
      AND (grocery_items.category_name = ? OR ? = '%')
      AND (stores.city = ? OR ? = '')
      AND (stores.name = ? OR ? = '')
      ORDER BY grocery_items.name ASC";

      $stmt = $pdo->prepare($sql);
      $stmt->execute([$search_query, $selected_category, $selected_category, $selected_city, $selected_city, $selected_store, $selected_store]);

      // Build and execute SQL query using prepared statements
      /*
      $sql = "SELECT grocery_items.id, grocery_items.name, grocery_items.description, grocery_items.image_url, stores.name AS store_name, grocery_item_prices.price
      FROM grocery_items
      JOIN grocery_item_prices ON grocery_items.id = grocery_item_prices.grocery_item_id
      JOIN stores ON grocery_item_prices.store_id = stores.id
      WHERE grocery_items.name LIKE ?
      AND (stores.city = ? OR ? = '')
      AND (stores.name = ? OR ? = '')
      ORDER BY grocery_items.name ASC";

      $stmt = $pdo->prepare($sql);
      $stmt->execute([$search_query, $selected_city, $selected_city, $selected_store, $selected_store]);
      */

      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

      // Check if there are any results
      if (count($result) > 0) {
        echo "<table>";
        echo "<tr><th>Image</th><th>ID</th><th>Name</th><th>Price</th></tr>";
        // Output data of each row
        foreach ($result as $row) {
          echo "<tr>";
          echo "<td><img src='" . $row["image_url"] . "' alt='" . $row["name"] . "' width='100' height='100'></td>";
          echo "<td>" . $row["id"] . "</td>";
          echo "<td><a href='product_details.php?id=" . $row['id'] . "'>" . $row['name'] . "</a></td>";
          echo "<td>" . $row["price"] . "</td>";
          echo "</tr>";
        }
        echo "</table>";
      } else {
        echo "No results found.";
      }

      // Close statement and connection
      $stmt = null;
      $pdo = null;
      ?>
    </div>
  </main>
</body>

</html>