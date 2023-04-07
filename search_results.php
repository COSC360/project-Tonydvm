<!DOCTYPE html>
<html>

<head>
  <title>Search Results</title>
  <link rel="stylesheet" href="css/reset.css" />
  <link rel="stylesheet" href="css/landing.css">
  <script src="js/livesearch.js"></script>

  <script src="showHint.js">
  </script>
</head>

<body>
  <header>
    <?php
    require_once 'header_min.php';
    ?>
  </header>
  <main>
    <div class="body-container">
      <div class="right-container">
        <h1>Search </h1>
        <!-- form to search, filter, and sort -->
        <form action="search_results.php" method="post">
          <label for="item-name">Search:</label>
          <input type="text" id="item-name" name="item-name" onkeyup="showHint(this.value)">
          <p>Suggestions: <span id="txtHint"></span></p>
          <label for="location">Location:</label>
          <select id="location" name="location">
            <option value="">All</option>
            <?php
            // Include the connect.php file to establish a connection using the $pdo variable
            require_once 'connect.php';

            // Build and execute SQL query using prepared statements
            $sql = "SELECT DISTINCT city FROM stores ORDER BY city ASC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Check if there are any results
            if (count($result) > 0) {
              foreach ($result as $row) {
                echo '<option value="' . $row['city'] . '">' . $row['city'] . '</option>';
              }
            }
            ?>
          </select>
          <label for="store">Store:</label>
          <select id="store" name="store">
            <option value="">All</option>
            <?php
            // Include the connect.php file to establish a connection using the $pdo variable
            require_once 'connect.php';

            // Build and execute SQL query using prepared statements
            $sql = "SELECT id, name FROM stores ORDER BY name ASC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Check if there are any results
            if (count($result) > 0) {
              foreach ($result as $row) {
                echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
              }
            }
            ?>
          </select>
          <label for="sort">Sort By:</label>
          <select id="sort" name="sort">
            <option value="name-asc">Name (A-Z)</option>
            <option value="name-desc">Name (Z-A)</option>
            <option value="price-asc">Price (Low to High)</option>
            <option value="price-desc">Price (High to Low)</option>
          </select>
          <input type="submit" value="Search">
        </form>
      </div>

      <div class="left-container">
        <h1>Results </h1>
        <?php
        // Include the connect.php file to establish a connection using the $pdo variable
        require_once 'connect.php';

        // Get search query and selected store and city from form submission
        $search_query = '%' . $_POST['item-name'] . '%';
        $selected_city = $_POST['location'];
        $selected_store = $_POST['store'];

        // if null values are passed, set them to empty strings
        if ($selected_city == null) {
          $selected_city = '';
        }
        if ($selected_store == null) {
          $selected_store = '';
        }

        // trim whitespace from search query
        $search_query = trim($search_query);

        // check for sort
        if (isset($_POST['sort'])) {
          $sort = $_POST['sort'];
          switch ($sort) {
            case 'name-asc':
              $sort = 'grocery_items.name ASC';
              break;
            case 'name-desc':
              $sort = 'grocery_items.name DESC';
              break;
            case 'price-asc':
              $sort = 'grocery_item_prices.price ASC';
              break;
            case 'price-desc':
              $sort = 'grocery_item_prices.price DESC';
              break;
            default:
              $sort = 'grocery_items.name ASC';
              break;
          }
        } else {
          $sort = 'grocery_items.name ASC';
        }


        // Build and execute SQL query using prepared statements
        $sql = "SELECT grocery_items.id, grocery_items.name, grocery_items.description, grocery_items.image_url, stores.name AS store_name, grocery_item_prices.price
      FROM grocery_items
      JOIN grocery_item_prices ON grocery_items.id = grocery_item_prices.grocery_item_id
      JOIN stores ON grocery_item_prices.store_id = stores.id
      WHERE grocery_items.name LIKE ?
      AND (stores.city = ? OR ? = '')
      AND (stores.name = ? OR ? = '')
      ORDER BY " . $sort;

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$search_query, $selected_city, $selected_city, $selected_store, $selected_store]);

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <div class="product-container">
          <?php
          // Check if there are any results
          if (count($result) > 0) {
            foreach ($result as $row) {
              echo '<a href="product_details.php?id=' . $row['id'] . '">';
              echo '<div class="product">';
              echo '<img src="' . $row['image_url'] . '" alt="' . $row['name'] . '" style="max-width: 100px; max-height: 100px;">';
              echo '<h3>' . $row['name'] . '</h3>';
              echo '<p>' . $row['description'] . '</p>';
              echo '<p>' . $row['store_name'] . '</p>';
              echo '<p>$' . $row['price'] . '</p>';
              echo '</div>';

              echo '</a>';
            }
          } else {
            echo '<p>No results found.</p>';
          }
          ?>

        </div>
      </div>
    </div>
  </main>
</body>

</html>