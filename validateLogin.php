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

    $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
    $statement = $pdo->prepare($sql);
    $statement->execute([$email, $password]);

    // if ($statement = mysqli_prepare($connection,$sql)){
    //     // bind the parameters
    //     mysqli_stmt_bind_param($statement, "ss", $email, $password);
    //     // execute the statement
    //     mysqli_stmt_execute($statement);
    //     // store the result
    //     mysqli_stmt_store_result($statement);
    // }

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