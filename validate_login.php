<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
try {
    require_once 'connect.php';


    // get the email and password from the form submission and store them in variables
    $email = $_POST['email'];
    $password = $_POST['password'];
 
    //$email = $_GET['email'];
    //$password = $_GET['password'];

    // sanitize the email and password
    // check for empty email and password
    if (empty($email) || empty($password)) {
        header("Location: login.php");
    }
    // use trim 
    $email = trim($email);
    $password = trim($password);
    // check for databse value incompatability
    if (strpos($email, "'") !== false || strpos($password, "'") !== false) {
        header("Location: login.php");
    }


    // create a query to select the user from the database
    $sql = "SELECT * FROM users WHERE email = ?";
    $statement = $pdo->prepare($sql);
    $statement->execute([$email]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);
    
    /*
    $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
    $statement = $pdo->prepare($sql);
    $statement->execute([$email, $password]);
    */

    // extract user id from the query result
    $userId = $statement->fetchColumn(0);

    // // check if user is an admin
    // $sql = "SELECT * FROM users WHERE email = ? AND password = ? AND admin = 1";
    // $statement = $pdo->prepare($sql);
    // $statement->execute([$email, $password]);

    // // if the user is an admin
    // if ($statement->rowCount() > 0) {
    //     header("Location: admin.php");
    //     // create session state and redirect to admin page
    //     session_start();
    //     $_SESSION['user'] = $userId;
    //     header("Location: landing.php");
    // }

    // if the user exists
    if ($user && password_verify($password, $user['password'])) {
        session_start();
        $_SESSION['user'] = $user['id'];
        header("Location: landing.php");
    } else {
        header("Location: login.php");
    }
    /*
    if ($statement->rowCount() > 0) {
        header("Location: landing.php");
        // create session state and redirect to landing page
        session_start();
        $_SESSION['user'] = $userId;
        header("Location: landing.php");
    } else {
        header("Location: login.php");
    }
    */
} catch (PDOException $e) {
    die($e->getMessage());
}