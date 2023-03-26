<!DOCTYPE html>
<html>

<head>
  <title>Product Details</title>
  <link rel="stylesheet" href="css/reset.css" />
  <link rel="stylesheet" href="css/landing.css" />
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
        <div id="search">
        
        </div>
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
        <div id="search">

        </div>
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

    // Build query
    $sql = "SELECT grocery_items.name, grocery_items.description, grocery_items.weight, stores.name AS store_name, stores.city, grocery_item_prices.price, grocery_items.image_url
              FROM grocery_items
              INNER JOIN grocery_item_prices ON grocery_items.id = grocery_item_prices.grocery_item_id
              INNER JOIN stores ON grocery_item_prices.store_id = stores.id
              WHERE grocery_items.id = ?";

    // Prepare and execute query
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check for results
    if ($result->num_rows > 0) {
      // Display product details
      $row = $result->fetch_assoc();
      echo "<div class='product-details'>";
      echo "<img src='" . $row["image_url"] . "' alt='" . $row["name"] . "' width='500'>";
      echo "<h2>" . $row["name"] . "</h2>";
      echo "<p>ID: " . $_GET['id'] . "</p>";
      echo "<p>Description: " . $row["description"] . "</p>";
      echo "<p>Store: " . $row["store_name"] . "</p>";
      echo "<p>Price($CAN): " . $row["price"] . "</p>";
      echo "</div>";

    } else {
      // No results found
      echo "No product details found.";
    }

    if (isset($_SESSION['user'])) {
      echo '<form action="add_to_cart.php" method="post">';
      echo '<input type="hidden" name="product_id" value="' . $_GET['id'] . '">';
      echo '<input type="number" name="quantity" value="1" min="1">';
      echo '<button type="submit">Add to Cart</button>';
      echo '</form>';
    } else {
      echo '<p>Please <a href="login.html">log in</a> to add this item to your cart.</p>';
    }

    // Close connection
    $stmt->close();
    $conn->close();
    ?>
    <div class="back-link">
      <a href="search_results.php">Back to Search Results</a>
    </div>
  </main>
</body>

</html>