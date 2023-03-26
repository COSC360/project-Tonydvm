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
    <!-- php dynamic header changes based on session user  -->
    <?php
    session_start();
    if (isset($_SESSION['user'])) {
      echo '
        <div class="header-wrapper" id="logged">
        <a href="landing.php"><h1 id="logo">PANTRY</h1></a>
        <div id="search">

        <form action="search_results.php" method="post">
        <input type="text" placeholder="Search"  />
        </form>
        
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

        <form action="search_results.php" method="post">
        <input type="text" placeholder="Search" />
        </form>

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
    <div class="body-container">
      <div class="left-container">
        <h2>Items</h2>
        <!-- collect first 10 products from databse under products and display links as a list -->
        <?php
        try {
          $connString = "mysql:host=localhost;dbname=db_76865732";
          $user = "76865732";
          $pass = "76865732";

          $pdo = new PDO($connString, $user, $pass);
          $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

          // get first 10 products from database and display as a list of links 
          $sql = "SELECT * FROM grocery_items LIMIT 10";
          $statement = $pdo->prepare($sql);
          $statement->execute();

          // extract product info from query result
          $products = $statement->fetchAll(PDO::FETCH_ASSOC);

          // display product info          
          echo '<ul>';
          foreach ($products as $product) {
            echo '<li><a href="product_details.php?id=' . $product['id'] . '">' . $product['name'] . '</a></li>';
          }
          echo '</ul>';

        } catch (PDOException $e) {
          die($e->getMessage());
        }
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