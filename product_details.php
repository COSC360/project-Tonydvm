<!DOCTYPE html>
<html>

<head>
  <title>Product Details</title>
  <link rel="stylesheet" href="css/reset.css" />
  <link rel="stylesheet" href="css/landing.css" />
</head>

<body>
  <header>
    <?php
      require_once 'minHeader.php';
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
      echo "<p>Price: " . $row["price"] . "</p>";
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

    if (isset($_SESSION['user'])) {
      echo '<h3>Add a Review</h3>';
      echo '<form action="add_review.php" method="post">';
      echo '<input type="hidden" name="product_id" value="' . $_GET['id'] . '">';
      echo '<label for="rating">Rating (1-5):</label>';
      echo '<input type="number" name="rating" value="5" min="1" max="5">';
      echo '<label for="comment">Comment:</label>';
      echo '<textarea name="comment" rows="4" cols="50" required></textarea>';
      echo '<button type="submit">Submit Review</button>';
      echo '</form>';
    } else {
      echo '<p>Please <a href="login.html">log in</a> to add a review.</p>';
    }

    // Fetch reviews for the current product
    $sql_reviews = "SELECT user_reviews.rating, user_reviews.comment, users.username
    FROM user_reviews
    INNER JOIN users ON user_reviews.user_id = users.id
    WHERE user_reviews.grocery_item_id = ?";
    $stmt_reviews = $conn->prepare($sql_reviews);
    $stmt_reviews->bind_param('i', $_GET['id']);
    $stmt_reviews->execute();
    $result_reviews = $stmt_reviews->get_result();


    if ($result_reviews->num_rows > 0) {
      echo "<h3>User Reviews:</h3>";
      while ($row_review = $result_reviews->fetch_assoc()) {
        echo '<div class="review">';
        echo '<p><strong>Rating:</strong> ' . $row_review["rating"] . '/5</p>';
        echo '<p><strong>Comment:</strong> ' . $row_review["comment"] . '</p>';
        echo '<p><strong>User:</strong> ' . $row_review["username"] . '</p>';
        echo '</div>';
      }
    } else {
      echo '<p>No reviews yet. Be the first to add one!</p>';
    }


    // Close connection
    $stmt->close();
    $conn->close();

    ?>

    <div class="back-link">
      <a href="search_results.php">Back to Search Page</a>
    </div>
  </main>
</body>

</html>