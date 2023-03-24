<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="css/reset.css" />
    <link rel="stylesheet" href="css/forms.css" />
  </head>
  <header>
    <div class="header-wrapper">
      <a href="landing.php"><h1 id="logo">PANTRY</h1></a>
    </div>
  </header>

  <body>
    <div class="wrap">
      <form
        class="account-form"
        name="signUpForm"
        method="post"
        action="createAccount.php"

      >
        <div class="form-header">
          <h2>Create an Account</h2>
        </div>
        <!-- <div class="form-group">
          <input
            type="text"
            name="firstName"
            class="form-input"
            placeholder="first name"
            required
          />
        </div>
        <div class="form-group">
          <input
            type="text"
            name="lastName"
            class="form-input"
            placeholder="last name"
            required
          />
        </div> -->
        <div class="form-group">
          <input
            type="text"
            name="username"
            class="form-input"
            placeholder="username"
            required
          />
        </div>
        <div class="form-group">
          <input
            type="text"
            name="email"
            class="form-input"
            placeholder="email@example.com"
            required
            pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
            oninvalid="this.setCustomValidity('Please enter a valid email address')"
          />
        </div>
        <!-- <div class="form-group">
          <input
            type="text"
            name="city"
            class="form-input"
            placeholder="city"
            required
          />
        </div> -->
        <div class="form-group">
          <input
            type="password"
            name="password"
            class="form-input"
            placeholder="password (minimum 8 characters)"
            required
            minlength="8"
          />
        </div>
        <!-- image upload -->
        <div class="form-group">
          <input
            type="file"
            name="image"
            class="form-input"
            placeholder="image"
            required
          />
        </div>
        <button class="form-button" type="submit">Create Account</button>
        <div class="form-footer">
          <a href="login.html">Login Instead</a>
        </div>
      </form>
    </div>
    <script> 
    // javascript validation
    // check that file size is less than 1MB
    var fileInput = document.querySelector('input[type="file"]');
    fileInput.onchange = function () {
      if (fileInput.files.length > 0) {
        var file = fileInput.files[0];
        if (file.size > 1024 * 1024) {
          alert("File is too big!");
          fileInput.value = "";
        }
      }
    };

    // check that password is at least 8 characters
    var password = document.getElementById("password");
    var confirm_password = document.getElementById("confirm_password");

    function validatePassword(){
      if(password.value != confirm_password.value) {
        confirm_password.setCustomValidity("Passwords Don't Match");
      } else {
        confirm_password.setCustomValidity('');
      }
    }

    password.onchange = validatePassword;
    confirm_password.onkeyup = validatePassword;

    // check that email is valid
    var email = document.getElementById("email");
    var confirm_email = document.getElementById("confirm_email");

    function validateEmail(){
      if(email.value != confirm_email.value) {
        confirm_email.setCustomValidity("Emails Don't Match");
      } else {
        confirm_email.setCustomValidity('');
      }
    }

    email.onchange = validateEmail;
    confirm_email.onkeyup = validateEmail;
    </script>
    <?php 
    try{
      $connString = "mysql:host=localhost;dbname=db_76865732";
      $user = "76865732";
      $pass = "76865732";
  
      $pdo = new PDO($connString, $user, $pass);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      
      // get form data 
      $username = $_POST['username'];
      $email = $_POST['email'];
      $password = $_POST['password'];
      $image = $_POST['image'];

      // create a query to insert the user into the database
      $sql = "INSERT INTO users (username, email, password, image) VALUES (?, ?, ?, ?)";
      $statement = $pdo->prepare($sql);
      $statement->execute([$username, $email, $password, $image]);

      // return a response to the app
      $response = array();
      $response["success"] = true;

      if ($statement->rowCount() > 0){
        session_start();
        $_SESSION['user'] = $userId;
        header("Location: landing.php");

      }
      else{
        
      }

      echo json_encode($response);
    }
    catch (PDOException $e){
      die($e->getMessage());
    }
    ?>

  </body>
</html>
