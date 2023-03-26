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

      // get userId from session variable
      $userId = $_SESSION['user'];

      // get user info from database
      $sql = "SELECT * FROM users WHERE id = ?";
      $statement = $pdo->prepare($sql);
      $statement->execute([$userId]);

      // extract user info from query result
      $user = $statement->fetch(PDO::FETCH_ASSOC);

      // display user info in form fields 
      $firstName = $user['firstName'];
      $lastName = $user['lastName'];
      $email = $user['email'];
      $password = $user['password'];

      // display user info in form fields
      echo "<form action='updateUser.php' method='post'>";
      echo "<input type='text' name='firstName' value='$firstName' />";
      echo "<input type='text' name='lastName' value='$lastName' />";
      echo "<input type='text' name='email' value='$email' />";
      echo "<input type='text' name='password' value='$password' />";
      echo "<input type='submit' value='Update' />";
      echo "</form>";

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