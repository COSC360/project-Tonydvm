<!DOCTYPE html>
<html>
<head>
  <title>Product Details</title>
  <link rel="stylesheet" href="css/reset.css" />
    <link rel="stylesheet" href="css/landing.css" />
</head>
<body>
    <header>
          <div class="header-wrapper">
            <a href="landing.html"><h1 id="logo">PANTRY</h1></a>
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
    <?php
      // Connect to the database
      $host = 'localhost';
      $user = 'username';
      $password = 'password';
      $database = 'grocery_tracker';
      $conn = mysqli_connect($host, $user, $password, $database);

      // Check connection
      if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
      }

      // Build query
      $sql = "SELECT grocery_items.name, grocery_items.description, grocery_items.weight, stores.name AS store_name, stores.city, grocery_item_prices.price, grocery_items.image_url
              FROM grocery_items
              INNER JOIN grocery_item_prices ON grocery_items.id = grocery_item_prices.grocery_item_id
              INNER JOIN stores ON grocery_item_prices.store_id = stores.id
              WHERE grocery_items.id = {$_GET['id']}";
      
      // Execute query
      $result = mysqli_query($conn, $sql);

      // Check for results
      if (mysqli_num_rows($result) > 0) {
        // Display product details
        $row = mysqli_fetch_assoc($result);
        echo "<div class='product-details'>";
        echo "<img src='" . $row["image_url"] . "' alt='" . $row["name"] . "'>";
        echo "<h2>" . $row["name"] . "</h2>";
        echo "<p>ID: " . $row["id"] . "</p>";
        echo "<p>Description: " . $row["description"] . "</p>";
        echo "<p>Store: " . $row["store_name"] . "</p>";
        echo "<p>Price($CAN): " . $row["price"] . "</p>";
        echo "</div>";
    
        

      } else {
        // No results found
        echo "No product details found.";
      }

      // Close connection
      mysqli_close($conn);
    ?>
    <div class="back-link">
      <a href="search_results.php">Back to Search Results</a>
    </div>
  </main>
</body>
</html>
