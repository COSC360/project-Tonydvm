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
        <a href="landing.html"><h1 id="logo">PANTRY</h1></a>

        <div id="search">
          <input type="text" placeholder="Search" />
        </div>
        <a href="preferences.html" id="preferences">
          <div class="button">
            <h2>Preferences</h2>
          </div>
        </a>
      </div>
    </header>
    <div class="main">
    <?php
try{
    $connString = "mysql:host=localhost;dbname=db_76865732";
    $user = "76865732";
    $pass = "76865732";

    $pdo = new PDO($connString, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // get userId from session variable
    $userId = $_SESSION['user'];

    // get user info from database
    $sql = "SELECT * FROM users WHERE userId = :userId";
    $statement = $pdo->prepare($sql);
    $statement->execute(['userId' => $userId]);

    // extract user info from the query result
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    // echo whats in the user array
    echo '<pre>';
    print_r($user);
    echo '</pre>';

}
catch (PDOException $e){
    die($e->getMessage());
}
?>

        <!-- log out button ends php session -->
        <a href="landing.html">
          <div class="button">
            <h2>Log Out</h2>
          </div>
        </a>
      </div>
    </div>
  </body>
</html>
