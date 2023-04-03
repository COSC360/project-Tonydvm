<?php
    try {
        require_once 'connectDetails.php';

        $pdo = new PDO($connString, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    } catch (PDOException $e) {
        die($e->getMessage());
    }
?>
