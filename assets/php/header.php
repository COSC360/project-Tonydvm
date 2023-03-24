<!-- dynamic header using php will change based on logged in status -->

<?php
  session_start();
  if(isset($_SESSION['user'])){
    echo '<div class="header-wrapper" id="logged">';
  }else{
    echo '<div class="header-wrapper" id="guest">';
  }
?>

<div class="header-wrapper" id="guest">
  <a href="landing.html"><h1 id="logo">PANTRY</h1></a>

  <a href="search.html" id="searchPage">
          <div class="button">
            <h2>Search</h2>
          </div>
  </a>

  <a href="createAccount.html" id="createAccount"> <h2>Create Account</h2></a>
  <a href="login.html" id="login">
    <div class="button">
      <h2>Log In</h2>
    </div>
  </a>
</div>

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
