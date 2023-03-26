<!DOCTYPE html>
<html>

<head>
  <title>Search Results</title>
  <link rel="stylesheet" href="css/reset.css" />
  <link rel="stylesheet" href="css/landing.css">
  <script src="js/livesearch.js"></script>

  <script>
    function showHint(str) {
      if (str.length == 0) {
        document.getElementById("txtHint").innerHTML = "";
        return;
      } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
          if (this.readyState == 4 && this.status == 200) {
            document.getElementById("txtHint").innerHTML = this.responseText;
          }
        };
        xmlhttp.open("GET", "gethint.php?q=" + str, true);
        xmlhttp.send();
      }
    }
  </script>
</head>

<body>
  <header>
    <!-- php dynamic header changes based on session user  -->
    <?php
    session_start();
    if (isset($_SESSION['user'])) {
      echo '
        <div class="header-wrapper" id="logged">
        <a href="landing.php"><h1 id="logo">PANTRY</h1></a>
        <a href="preferences.php" id="preferences">
        <div class="button">
        <h2>Preferences</h2>
        </div>
        </a>
        </div>
        ';
    } else {
      echo '
        <div class="header-wrapper" id="guest">
        <a href="landing.php"><h1 id="logo">PANTRY</h1></a>
        <a href="createAccount.php" id="createAccount"> <h2>Create Account</h2></a>
        <a href="login.html" id="login">
        <div class="button">
        <h2>Log In</h2>
        </div>
        </a>
        </div>
        ';
    }
    ?>
  </header>
  <main>
    <h1>Search Results</h1>
    <form method="post" class="search-form" action="">
      <label for="item-name">Search for Item Name:</label>
      <input type="text" id="item-name" name="item-name" placeholder="Search for a product..."
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

      // Get search query and selected store and city from form submission
      $search_query = '%' . $_POST['item-name'] . '%';
      $selected_city = $_POST['location'];
      $selected_store = $_POST['store'];

      // trim whitespace from search query
      $search_query = trim($search_query);

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
        echo "<tr><th>Image</th><th>ID</th><th>Name</th><th>Price</th></tr>";
        // Output data of each row
        while ($row = $result->fetch_assoc()) {
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

      // Close connection
      $stmt->close();
      $conn->close();
      ?>
    </div>
  </main>
</body>

</html>