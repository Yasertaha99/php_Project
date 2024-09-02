<!-- <?php 
// error_reporting(E_ALL);
// ini_set('display_errors', '1');
// include_once('../layouts/app.php');
// require("/var/www/html/cafeteria/controller/product.php");
// $allProd = new ProductController();
// // $methods = get_class_methods('ProductController');
// // echo '<pre>';
// // print_r($methods);
// // echo '</pre>';
// $products = $allProd->index();
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
    <h2>All Products</h2>
    <a href="addProduct.php" class='btn btn-primary'> Add Product </a> 
    <a href="availableProducts.php" class='btn btn-primary'> Available Products</a> 
    <a href="unAvailableProducts.php" class='btn btn-primary'> Unavailable Products </a> 
      <table class="table table-bordered">
        <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Image</th>
            <th>available</th>
            <th>Action</th>
        </tr>
         
            <?php
            // foreach($products as $product)
            // {
            //     echo "<tr>
            //     <td>{$product['name']}</td> 
            //     <td>{$product['price']}</td> 
            //     <td> <img src='{$product['image']}' width='100' height='100'></td>
                
            //     <td><a href='editProduct.php?id={$product['id']}' class='btn btn-info'> Edit </a> 
            //     <a href='deleteProduct.php?id={$product['id']}' class='btn btn-danger'> Delete </a> </td>
            //     <td>{$product['available']}</td> 
            //     </tr>"; 
            // }?>
      </table>
      </div>

      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html> -->




<!-- new -->


<?php 
session_start();

error_reporting(E_ALL);
ini_set('display_errors', '1');

// include_once('../layouts/app.php');
include_once('../../../views/templates/adminNav.php');
require("../../controller/product.php");

$allProd = new ProductController(); 
$productsPerPage = 3;

// Get current page number from the URL, default to 1 if not set
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $productsPerPage;

// Fetch total number of products
$totalProducts = count($allProd->index()); // Implement this method in ProductController
$totalPages = ceil($totalProducts / $productsPerPage);

// Fetch products for the current page
$products = $allProd->getProducts($offset, $productsPerPage); // Modify getProducts method to accept parameters
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
    <div class="container ">
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
        foreach($products as $product) {
            echo "<tr>
                <td>{$product['name']}</td> 
                <td>{$product['price']}</td> 
                <td> <img src='uploads/{$product['image']}' width='100' height='100'></td>
                <td>{$product['available']}</td> 
                <td>
                    <a href='editProduct.php?id={$product['id']}' class='btn btn-info'> Edit </a> 
                    <a href='deleteProduct.php?id={$product['id']}' class='btn btn-danger'> Delete </a>
                </td>
            </tr>"; 
        }
        ?>
    </table>

    <!-- Pagination Links -->
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
    </div>
    <div id="errorModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Notification</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="modalErrorMessage" class="text-success"></p>
            </div>
        </div>
    </div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        // Hide success or error message after 2 seconds
        function showErrorModal(message) {
    var modal = new bootstrap.Modal(document.getElementById('errorModal'));
    var modalMessage = document.getElementById('modalErrorMessage');
    modalMessage.innerText = message;
    modal.show();

    setTimeout(function() {
        modal.hide();
    }, 2000);
}
        
    </script>
    <?php 
if (isset($_SESSION['message'])) {
    $message=[];
    $message['text'] = $_SESSION['message']['text'];
    //$message=$message ; 
    echo "<script>showErrorModal('";
        echo implode($message);
        echo "');</script>";
    unset($_SESSION['message']);
} ?>
</body>
</html>
