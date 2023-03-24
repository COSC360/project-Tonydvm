<?php
try{
    $connString = "mysql:host=localhost;dbname=db_76865732";
    $user = "76865732";
    $pass = "76865732";

    $pdo = new PDO($connString, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // get the email and password from the form submission and store them in variables 
    $email = $_GET['email'];
    $password = $_GET['password'];

    // create a prepared statement to select the user from the database using mysql's bind parameter functionality
    $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email AND password = :password");
    $statement->bindParam(':email', $email);
    $statement->bindParam(':password', $password);
    $statement->execute();

    // if the user exists, redirect to the home page
    if($statement->rowCount() > 0){
        header("Location: home.html");
    }
    else{
        // if the user does not exist, redirect to the login page
        header("Location: login.html");
        // include a message to the user that the login was unsuccessful in get parameter
        header("Location: login.html?message=Login%20Unsuccessful");
    }
}
catch (PDOException $e){
    die($e->getMessage());
}