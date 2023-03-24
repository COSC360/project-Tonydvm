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
        if(isset($_SESSION['user'])){
          echo '
          <div class="header-wrapper" id="logged">
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

          ';
        }else{
          echo '
          <div class="header-wrapper" id="guest">
  <a href="landing.html"><h1 id="logo">PANTRY</h1></a>

  <div id="search"><input type="text" placeholder="Search" /></div>

  <a href="createAccount.html" id="createAccount"> <h2>Create Account</h2></a>
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
          <ul>
            <li>Item 1</li>
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
        <div class="right-container">
          <h2>Watchlist</h2>
          <ul>
            <li>Item 1</li>
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
