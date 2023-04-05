<?php
    // error_reporting(E_ALL);
    // ini_set('display_errors', 1);
    session_start();
    if (isset($_SESSION['user'])) {
      echo '
        <div class="header-wrapper" id="logged">
        <a href="landing.php"><h1 id="logo">PANTRY</h1></a>
        <div id="search">

        <form action="search_results.php" method="post">
        <input type="text" placeholder="Search for a product..." name="item-name" id="item-name" autocomplete="off" />
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