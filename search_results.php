<!DOCTYPE html>
<html>
<head>
  <title>Search Results</title>
  <link rel="stylesheet" href="css/reset.css" />
  <link rel="stylesheet" href="css/landing.css">
  <script src="js/livesearch.js"></script>
  
</head>
  <body>
    <header>
          <div class="header-wrapper">
            <a href="landing.php"><h1 id="logo">PANTRY</h1></a>
            <a href="createAccount.php" id="createAccount"
              ><h2>Create Account</h2></a
            >
            <a href="login.html" id="logIn">
              <div class="button">
                <h2>Log In</h2>
              </div>
            </a>
          </div>
    </header>

    <main>
      <h1>Search Results</h1>
      <form>
        <label for="item-name">Search for Item Name:</label>
        <input type="text" id="item-name" name="item-name" placeholder="Search for a product...">
        <div id="search-suggestions"></div>

        <label for="store">Store:</label>
        <select id="store" name="store">
          <option value="">Any Store</option>
          <option value="1">Save-On Foods</option>
          <option value="2">Canadian Supermarket</option>
          <option value="3">Coscto</option>
          <option value="3">Walmart</option>
          <option value="3">Independent Grocer</option>
        </select>

        <label for="location">Location:</label>
        <select id="location" name="location">
          <option value="">Any Location</option>
          <option value="Toronto">Toronto</option>
          <option value="Vancouver">Vancouver</option>
          <option value="Montreal">Montreal</option>
          <option value="Montreal">Calgary</option>
          <option value="Montreal">Kelowna</option>
          <option value="Montreal">Edmonton</option>
          <option value="Montreal">Ottawa</option>
          <option value="Montreal">Quebec City</option>
        </select>

        <button type="submit">Search</button>

        <div id="search-results">
        <!-- Search results will be displayed here -->
        </div>
      </form>

      <?php
        // Connect to the database
        $host = 'localhost';
        $user = 'username';
        $password = '76865732';
        $database = 'db_76865732';
        $conn = mysqli_connect($host, $user, $password, $database);

        // Check connection
        if (!$conn) {
          die("Connection failed: " . mysqli_connect_error());
        }

        // Get search query and selected store and city from form submission
        $search_query = $_POST['item-name'];
        $selected_store = $_POST['store'];
        $selected_city = $_POST['location'];

        // Build and execute SQL query
        $sql = "SELECT grocery_items.id, grocery_items.name, grocery_items.description, grocery_items.image_url, stores.name AS store_name, grocery_item_prices.price
        FROM grocery_items
        JOIN grocery_item_prices ON grocery_items.id = grocery_item_prices.grocery_item_id
        JOIN stores ON grocery_item_prices.store_id = stores.id
        WHERE grocery_items.name LIKE '%$search_query%'
        AND stores.city = '$selected_city'
        AND stores.name = '$selected_store'
        ORDER BY grocery_items.name ASC";
        $result = $conn->query($sql);

        // Check if there are any results
        if ($result->num_rows > 0) {
          echo "<table>";
          echo "<tr><th>Image</th><th>ID</th><th>Name</th><th>Price($CAN)</th></tr>";
          // Output data of each row
          while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["image_url"]. "</td>";
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
        mysqli_close($conn);
      ?>
    </main>
  </body>
</html>