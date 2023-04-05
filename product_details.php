<!DOCTYPE html>
<html>

<head>
  <title>Product Details</title>
  <link rel="stylesheet" href="css/reset.css" />
  <link rel="stylesheet" href="css/landing.css" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>
  <header>
    <?php
      require_once 'header_min.php';
    ?>
  </header>

  <main>
    <?php
    // Connect to the database
    require_once 'connect.php';

    function getPriceHistory($groceryItemId, $dbConnection) {
      $sql = "SELECT gip.store_id, s.name as store_name, gip.price, gip.price_date
              FROM grocery_item_prices gip
              JOIN stores s ON gip.store_id = s.id
              WHERE gip.grocery_item_id = ? AND gip.price_date >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)
              ORDER BY gip.price_date ASC";

      $stmt = $dbConnection->prepare($sql);
      $stmt->execute([$groceryItemId]);

      $priceHistoryData = $stmt->fetchAll(PDO::FETCH_ASSOC);

      return $priceHistoryData;
  }

  $groceryItemId = $_GET['id']; // The grocery item ID for which you want to fetch the price history
  $priceHistoryData = getPriceHistory($groceryItemId, $pdo);

    // Build query
    $sql = "SELECT grocery_items.name, grocery_items.description, grocery_items.weight, stores.name AS store_name, stores.city, grocery_item_prices.price, grocery_items.image_url
              FROM grocery_items
              INNER JOIN grocery_item_prices ON grocery_items.id = grocery_item_prices.grocery_item_id
              INNER JOIN stores ON grocery_item_prices.store_id = stores.id
              WHERE grocery_items.id = ?";

    // Prepare and execute query
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_GET['id']]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check for results
    if (count($result) > 0) {
      // Display product details
      $row = $result[0];
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
      echo '<p>Please <a href="login.php">log in</a> to add this item to your cart.</p>';
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
      echo '<p>Please <a href="login.php">log in</a> to add a review.</p>';
    }

    // Fetch reviews for the current product
    $sql_reviews = "SELECT user_reviews.rating, user_reviews.comment, users.username
    FROM user_reviews
    INNER JOIN users ON user_reviews.user_id = users.id
    WHERE user_reviews.grocery_item_id = ?";
    $stmt_reviews = $pdo->prepare($sql_reviews);
    $stmt_reviews->execute([$_GET['id']]);
    $result_reviews = $stmt_reviews->fetchAll(PDO::FETCH_ASSOC);


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


    // Close statement and connection
    $stmt = null;
    $stmt_reviews = null;
    $pdo = null;

    ?>

    <div class="back-link">
      <a href="search_results.php">Back to Search Page</a>
    </div>

    <div>
        <canvas id="priceHistoryChart"></canvas>
    </div>

    <script>
    const priceHistoryData = <?php echo json_encode($priceHistoryData); ?>;

    const labels = Array.from(new Set(priceHistoryData.map(record => record.price_date)));
    const datasets = [];

    // Group price data by store
    const groupedData = priceHistoryData.reduce((acc, record) => {
        if (!acc.hasOwnProperty(record.store_id)) {
            acc[record.store_id] = {
                storeName: record.store_name,
                data: []
            };
        }

        acc[record.store_id].data.push({x: record.price_date, y: parseFloat(record.price)});
        return acc;
    }, {});

    // Create datasets for the chart
    Object.entries(groupedData).forEach(([storeId, data]) => {
        datasets.push({
            label: data.storeName,
            data: data.data,
            fill: false,
            borderColor: 'rgb(' + (Math.floor(Math.random() * 256)) + ',' + (Math.floor(Math.random() * 256)) + ',' + (Math.floor(Math.random() * 256)) + ')'
        });
    });

    const ctx = document.getElementById('priceHistoryChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: datasets
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    type: 'time',
                    time: {
                        unit: 'day'
                    }
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    </script>

  </main>
</body>

</html>