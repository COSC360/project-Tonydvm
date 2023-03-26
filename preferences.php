<!DOCTYPE html>
<html>

<head>
  <link rel="stylesheet" href="css/reset.css" />
  <link rel="stylesheet" href="css/landing.css" />
  <title>Pantry</title>
</head>

<body>
  <header>
    <div class="header-wrapper">
      <a href="landing.php">
        <h1 id="logo">PANTRY</h1>
      </a>

      <div id="search">
        <input type="text" placeholder="Search" />
      </div>
    </div>
  </header>
  <div class="main">
    <?php
    try {
      $connString = "mysql:host=localhost;dbname=db_76865732";
      $user = "76865732";
      $pass = "76865732";

      $pdo = new PDO($connString, $user, $pass);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      // get user id from session
      session_start();
      $user_id = $_SESSION['user'];

      // get username, email, and password from database users table (id,username,email,password,role)
      $sql = "SELECT username, email, password FROM users WHERE id = $user_id";
      $statement = $pdo->prepare($sql);
      $statement->execute();

      // get image from database user_images table (id, user_id, image_url)
      $sql = "SELECT image_url FROM user_images WHERE user_id = $user_id";
      $statement2 = $pdo->prepare($sql);
      $statement2->execute();

      // show image and user info
      while ($row = $statement->fetch()) {
        echo '
          <div class="user-info">
            <div class="user-image">
              <img src="' . $statement2->fetchColumn() . '" alt="user image" />
            </div>
            <div class="user-info-text">
              <h2>' . $row['username'] . '</h2>
              <h3>' . $row['email'] . '</h3>
              <h3>' . $row['password'] . '</h3>
            </div>
          </div>
          ';
      }
    } catch (PDOException $e) {
      die($e->getMessage());
    }
    ?>

    <!-- log out button ends php session -->
    <a href="logout.php">
      <div class="button" style="width:20em;">
        <h2>Log Out</h2>
      </div>
    </a>
  </div>
  </div>
</body>

</html>