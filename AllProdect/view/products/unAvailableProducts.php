<?php 
error_reporting(E_ALL);
ini_set('display_errors', '1');
include_once('../../../views/templates/adminNav.php');
require("../../controller/product.php");
$allProd = new ProductController();
$methods = get_class_methods('ProductController');
// echo '<pre>';
// print_r($methods);
// echo '</pre>';
$products = $allProd->getUnAvailables();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container">
    <h2 class="mb-5" >All Products</h2>
    <div class="d-flex justify-content-around">
      
    <a href="addProduct.php" class='btn btn-primary'> Add Product </a> 
    <a href="allProduct.php" class='btn btn-primary'> All Products</a> 
    <a href="availableProducts.php" class='btn btn-primary'> Available Products</a> 
    <a href="unAvailableProducts.php" class='btn btn-primary'> Unavailable Products </a>   ...</div>
    <table class="table table-bordered mt-5">
        <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Image</th>
            <th>available</th>
            <th>Action</th>
        </tr>
        <?php
            foreach($products as $product)
            {
                // echo $product['image'];
                echo "<tr>
                <td>{$product['name']}</td> 
                <td>{$product['price']}</td> 
                <td> <img src='./uploads/{$product['image']}' width='100' height='100'></td>
                <td>{$product['available']}</td> 
                
                <td><a href='editProduct.php?id={$product['id']}' class='btn btn-info'> Edit </a> 
                <a href='deleteProduct.php?id={$product['id']}' class='btn btn-danger'> Delete </a> </td>
                </tr>"; 
            }?>
      </table>
      </div>
</body>
</html>