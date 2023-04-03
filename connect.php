<?php
    try {
        // require_once($_SERVER['DOCUMENT_ROOT']."/connectDetails.php");
        require_once($_SERVER['DOCUMENT_ROOT']."/home/tonydvm/public_html/connectDetails.php");

        echo $connString;

        $pdo = new PDO($connString, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    } catch (PDOException $e) {
        die($e->getMessage());
    }
?>
