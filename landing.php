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
        <h2>Low Priced Items</h2>
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
          <a href="landing.php"></a>
          <li>Item 2</li>
          <li>Item 3</li>
          <li>Item 4</li>
          <li>Item 5</li>
          <li>Item 6</li>
          <li>Item 7</li>
          <li>Item 8</li>
          <li>Item 9</li>
          <li>Item 10</li>
        </ul>
      </div>

  </main>
</body>

</html>