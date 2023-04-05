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
  <div class="container">
      <h1>Browse by category</h1>
      <div class="category-container">
        <?php
          require_once 'connect.php';
          $sql = "SELECT * FROM categories";
          $statement = $pdo->prepare($sql);
          $statement->execute();
          $categories = $statement->fetchAll(PDO::FETCH_ASSOC);
          // display list with each category as a button class that links to category.php
          foreach ($categories as $category) {
            echo '<a href="search_results.php?category=' . $category['name'] . '">';
            echo '<div class="category">';
            echo '<h2>' . $category['name'] . '</h2>';
            echo '</div>';
            echo '</a>';
          }
        ?>
      </div>
    </div>
    <div class="body-container">
      <div class="left-container">
        <h1>Items currently at great prices</h1>
        <!-- <?php
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
        ?> -->

        <?php
        require_once 'connect.php';
        // table grocery_items( id 	name 	brand 	category_name 	description 	image_url 	weight) 
        // table grocery_item_prices( id 	grocery_item_id 	store_id 	price 	price_date)
        // table stores(id 	name 	address 	city 	state 	zip 	)
        // table price_history( price_hitsory_id 	price_id price 	price_date)
        // get average price of each product from price_history, then display products sorted by biggest discount from average price
        $sql = "SELECT grocery_items.id, grocery_items.name, grocery_items.brand, grocery_items.category_name, grocery_items.description, grocery_items.image_url, grocery_items.weight, grocery_item_prices.price, grocery_item_prices.price_date, stores.name AS store_name
                FROM grocery_items
                INNER JOIN grocery_item_prices ON grocery_items.id = grocery_item_prices.grocery_item_id
                INNER JOIN stores ON grocery_item_prices.store_id = stores.id
                ORDER BY grocery_item_prices.price DESC
                LIMIT 5";
        $statement = $pdo->prepare($sql);
        $statement->execute();
        $products = $statement->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <!-- create div class for each product, with name of product, current price, 52week low, 52week high,  -->
        <div class="product-container">
          <?php
          foreach ($products as $product) {
            echo '<a href="product_details.php?id=' . $product['id'] . '">';
            echo '<div class="product">';      
            echo '<h2>' . $product['name'] . '</h2>';
            echo '<p>' . $product['brand'] . '</p>';            
            echo '<p> $' . $product['price'] . '</p>';
            echo '<p>' . $product['store_name'] . '</p>';
            echo '</div>';
            echo '</a>';
          }
          ?>
        </div>
      </div>
      <div class="right-container">
        <h1>Watchlist</h1>
        <ul>
          <?php
          require_once 'connect.php';
          if (isset($_SESSION['user'])) {
            $sql = "SELECT grocery_items.id, grocery_items.name
                    FROM grocery_items
                    INNER JOIN cart ON grocery_items.id = cart.product_id
                    WHERE cart.user_id = ?";
            $statement = $pdo->prepare($sql);
            $statement->execute([$_SESSION['user_id']]);
            $products = $statement->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <div class="product-container">
              <?php
              foreach ($products as $product) {
                echo '<div class="product">';
                // link to product details page
                echo '<a href="product_details.php?id=' . $product['id'] . '">';
                // show product name
                echo '<h2>' . $product['name'] . '</h2>';
                echo '</div>';
              }
              ?>
            </div>
            <?php
          } else {
            echo '<li>Please <a href="login.php">log in</a> to view your watchlist.</li>';
          }
          ?>
        </ul>
      </div>
    </div>
  </main>
</body>

</html>