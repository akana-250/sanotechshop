<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once "../../config/config.php";
require_once "../../includes/admin_check.php";


if(!isset($_GET['id'])){

    header("Location: index.php");
    exit();

}


$id = $_GET['id'];



try{


    $conn->beginTransaction();



    // Get purchased items first

    $items = $conn->prepare("
        SELECT product_id, quantity
        FROM purchase_items
        WHERE purchase_id = ?
    ");


    $items->execute([$id]);

    $products = $items->fetchAll(PDO::FETCH_ASSOC);



    // Return stock back

    foreach($products as $product){


        $update = $conn->prepare("
            UPDATE products
            SET quantity = quantity - ?
            WHERE id = ?
        ");


        $update->execute([

            $product['quantity'],
            $product['product_id']

        ]);



        // Remove stock movement

        $move = $conn->prepare("
            DELETE FROM stock_movements
            WHERE product_id = ?
            AND movement_type='IN'
            AND quantity = ?
            LIMIT 1
        ");


        $move->execute([

            $product['product_id'],
            $product['quantity']

        ]);

    }



    // Delete items

    $deleteItems = $conn->prepare("
        DELETE FROM purchase_items
        WHERE purchase_id = ?
    ");


    $deleteItems->execute([$id]);




    // Delete purchase

    $delete = $conn->prepare("
        DELETE FROM purchases
        WHERE id = ?
    ");


    $delete->execute([$id]);



    $conn->commit();



    header("Location: index.php");
    exit();



}catch(Exception $e){


    $conn->rollBack();

    echo $e->getMessage();


}

?>