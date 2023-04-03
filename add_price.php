<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
try {
    require_once 'connect.php';

    //    get post data (product s, store s, price decimal 10,2, date mysql date)
    $product = $_POST['product'];
    $store = $_POST['store'];
    $price = $_POST['price'];
    $date = $_POST['date'];

    //    sanitize data
    $price = trim($price);
    $date = trim($date);

    //   add price to grocery_item_prices table (id, grocery_item_id, store_id, price, price_date)
    $sql = "INSERT INTO grocery_item_prices (grocery_item_id, store_id, price, price_date) VALUES (:product, :store, :price, :date)";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':product', $product);
    $statement->bindValue(':store', $store);
    $statement->bindValue(':price', $price);
    $statement->bindValue(':date', $date);
    $statement->execute();

    //    redirect to preferences.php with success message
    header("Location: preferences.php?success=1");


} catch (PDOException $e) {
    die($e->getMessage());
}