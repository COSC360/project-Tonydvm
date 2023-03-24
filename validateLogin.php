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

    // create a query to select the user from the database
    $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
    $statement = $pdo->prepare($sql);
    $statement->execute([$email, $password]);

    // extract user id from the query result
    $userId = $statement->fetchColumn(0);


    // if the user exists
    if($statement->rowCount() > 0){
        header("Location: landing.html");
        // create session state and redirect to landing page
        session_start();
        $_SESSION['user'] = $userId;
        header("Location: landing.php");
    }
    else{
        header("Location: login.php");
    }
}
catch (PDOException $e){
    die($e->getMessage());
}