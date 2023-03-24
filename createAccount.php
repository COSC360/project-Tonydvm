<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="css/reset.css" />
    <link rel="stylesheet" href="css/forms.css" />
  </head>
  <header>
    <div class="header-wrapper">
      <a href="landing.html"><h1 id="logo">PANTRY</h1></a>
    </div>
  </header>

  <body>
    <div class="wrap">
      <form
        class="account-form"
        name="signUpForm"
        onsubmit="landing.html"
        method="post"
      >
        <div class="form-header">
          <h2>Create an Account</h2>
        </div>
        <div class="form-group">
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
        </div>
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
        <div class="form-group">
          <input
            type="text"
            name="city"
            class="form-input"
            placeholder="city"
            required
          />
        </div>
        <div class="form-group">
          <input
            type="password"
            name="password"
            class="form-input"
            placeholder="password (minimum 8 characters))"
            required
            minlength="8"
          />
        </div>
        <button class="form-button" type="submit">Create Account</button>

        <div class="form-footer">
          <a href="login.html">Login Instead</a>
        </div>
      </form>
    </div>
    <?php 
    $email = $_POST['email'];
    $password = $_POST['password'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $city = $_POST['city'];
    $statement = mysqli_prepare($conn, "INSERT INTO users (email, password, firstName, lastName, city) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($statement, "sssss", $email, $password, $firstName, $lastName, $city);
    mysqli_stmt_execute($statement);

    $response = array();
    $response["success"] = true;

    echo json_encode($response);
    ?>
  </body>
</html>
