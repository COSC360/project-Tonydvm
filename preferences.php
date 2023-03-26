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
      $user = $statement->fetch();

      // each user has id, username, password, and role
      $username = $user['username'];
      $password = $user['password'];
      $role = $user['role'];

      // display user info in a table 
      echo "
      <div class='user-info'>
        <h2>User Info</h2>
        <table>
          <tr>
            <th>Username</th>
            <th>Password</th>
            <th>Role</th>
          </tr>
          <tr>
            <td>$username</td>
            <td>$password</td>
            <td>$role</td>
          </tr>
        </table>
      </div>
      ";



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