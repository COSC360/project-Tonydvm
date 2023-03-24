<!-- file to encpsulate databse connection that can be called by other files -->
<?php
function getConnection(){
    try{
        $connString = "mysql:host=localhost;dbname=db_76865732";
        $user = "76865732";
        $pass = "76865732";
    
        $pdo = new PDO($connString, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }
    catch (PDOException $e){
        die($e->getMessage());
    }
}
?>