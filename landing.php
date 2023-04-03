<!DOCTYPE html>
<link rel="stylesheet" href="css/reset.css" />
<link rel="stylesheet" href="css/landing.css" />
<html lang="en">

<head>
  <title>Pantry</title>
  <link rel="stylesheet" href="css/reset.css" />
  <link rel="stylesheet" href="css/landing.css" />
</head>

<body>
  <header>
    <?php
      require_once 'header.php';
    ?>
  </header>
  <main>
    <div class="body-container">
      <div class="left-container">
        <h2>Items</h2>
        <?php
          require_once 'connect.php';
          
          $sql = "SELECT * FROM grocery_items LIMIT 10";
          $statement = $pdo->prepare($sql);
          $statement->execute();
          $products = $statement->fetchAll(PDO::FETCH_ASSOC);
          echo '<ul>';
          foreach ($products as $product) {
            echo '<li><a href="product_details.php?id=' . $product['id'] . '">' . $product['name'] . '</a></li>';
          }
          echo '</ul>';
        ?>
      </div>
      <div class="right-container">
        <h2>Watchlist</h2>
        <ul>
          <?php
          if (isset($_SESSION['user'])) {
            $user_id = $_SESSION['user']['id'];

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

            $sql = "SELECT grocery_items.id, grocery_items.name
                    FROM grocery_items
                    INNER JOIN cart ON grocery_items.id = cart.product_id
                    WHERE cart.user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                echo '<li><a href="product_details.php?id=' . $row["id"] . '">' . $row["name"] . '</a></li>';
              }
            } else {
              echo '<li>No items in watchlist.</li>';
            }

            // Close connection
            $stmt->close();
            $conn->close();

          } else {
            echo '<li>Please <a href="login.html">log in</a> to view your watchlist.</li>';
          }
          ?>
        </ul>
      </div>
    </div>
  </main>
</body>

</html>