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
          <option value="4">Walmart</option>
          <option value="5">Independent Grocer</option>
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
      </form>

      <div class="body-container">
        <h2>Search Results</h2>
        <?php
        try {
          $connString = "mysql:host=localhost;dbname=db_76865732";
          $user = "76865732";
          $pass = "76865732";

          $pdo = new PDO($connString, $user, $pass);
          $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


          // Get search query and selected store and city from form submission
          $search_query = '%' . $_POST['item-name'] . '%';
          $selected_store = $_POST['store'];
          $selected_city = $_POST['location'];

          // Build and execute SQL query using prepared statements
          $sql = "SELECT grocery_items.id, grocery_items.name, grocery_items.description, grocery_items.image_url, stores.name AS store_name, grocery_item_prices.price
          FROM grocery_items
          JOIN grocery_item_prices ON grocery_items.id = grocery_item_prices.grocery_item_id
          JOIN stores ON grocery_item_prices.store_id = stores.id
          WHERE grocery_items.name LIKE ?
          AND stores.city = ?
          AND stores.name = ?
          ORDER BY grocery_items.name ASC";

          $stmt = $pdo->prepare($sql);
          $stmt->bind_param('sss', $search_query, $selected_city, $selected_store);
          $stmt->execute();

          $result = $stmt->fetchAll(PDO::FETCH_ASSOC);


          // Check if there are any results
          if ($result->rowCount() > 0) {
            echo "<table>";
            echo "<tr><th>Image</th><th>ID</th><th>Name</th><th>Price($CAN)</th></tr>";
            // Output data of each row
            while($row = $result) {
              echo "<tr>";
              echo "<td>" . $row["image_url"]. "</td>";
              echo "<td>" . $row["id"]. "</td>";
              echo "<td><a href='product_details.php?id=" . $row['id'] . "'>" . $row['name'] . "</a></td>";
              echo "<td>" . $row["price"]. "</td>";
              echo "</tr>";
            }
            echo "</table>";
          }
        } catch (PDOException $e) {
            die($e->getMessage());
        }

        ?>
      </div>
    </main>
  </body>
</html>