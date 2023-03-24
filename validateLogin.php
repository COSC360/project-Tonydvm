<?php
try{
    $connString = "mysql:host=localhost;dbname=db_76865732";
    $user = "76865732";
    $pass = "76865732";

    $pdo = new PDO($connString, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // include 'connection.php';
    // $pdo = getConnection();


    // get the email and password from the form submission and store them in variables 
    $email = $_GET['email'];
    $password = $_GET['password'];

    // create a query to select the user from the database
    $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
    $statement = $pdo->prepare($sql);
    $statement->execute([$email, $password]);

    // if the user exists
    if($statement->rowCount() > 0){
        header("Location: landing.html");
        header("Location: landing.html?message=Login%20Successful");
    }
    else{
        header("Location: login.html");
        header("Location: login.html?message=Login%20Unsuccessful");
    }
}
catch (PDOException $e){
    die($e->getMessage());
}