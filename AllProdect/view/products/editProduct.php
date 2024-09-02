<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', '1');
include_once('../../../views/templates/adminNav.php');
require("../../controller/product.php");
$allProd = new ProductController();




$id = $_GET['id'];
$errors = [];
$product = $allProd->getProduct($id);
$category = $allProd->getCategory($product[0]['category_id']);
$allCategories = $allProd->getAllCategories();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- for popup -->
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.min.css" rel="stylesheet" />
</head>

<body>
    <div class="container">
        <form action="#" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
            <fieldset>
                <legend> Edit Product</legend>
                <div class="mb-3">
                    <label for="name" class="form-label"> Product</label>
                    <input type="text" id="name" class="form-control" value="<?php echo $product[0]['name'] ?>"
                        name="prod_name">
                    <div id="nameError" class="text-danger"><?php echo $errors['name'] ?? ''; ?></div>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label"> Price</label>
                    <input type="number" id="price" class="form-control" value="<?php echo $product[0]['price'] ?>"
                        name="price">
                    <div id="priceError" class="text-danger"><?php echo $errors['price'] ?? ''; ?></div>
                </div>

                <div class="mb-3">
                    <label for="cate_name" class="form-label"> Category</label>
                    <select id="cate_name" class="form-select" name="cate_name">
                        <?php
                        echo "<option selected>{$category[0]['name']}</option>";
                        foreach ($allCategories as $cate) {
                            echo "<option>{$cate['name']}</option>";
                        }
                        ?>
                    </select>
                    <div id="catError" class="text-danger"><?php echo $errors['cate_name'] ?? ''; ?></div>
                </div>

                <div class="mb-3">
                    <label for="available" class="form-label"> Available</label>
                    <select id="available" class="form-select" name="available">
                        <option value="available" <?php echo $product[0]['available'] == 'available' ? 'selected' : ''; ?>>Yes</option>
                        <option value="unavailable" <?php echo $product[0]['available'] == 'unavailable' ? 'selected' : ''; ?>>No</option>
                    </select>
                    <div id="availableError" class="text-danger"><?php echo $errors['available'] ?? ''; ?></div>
                </div>

                <!-- Add Category -->
                <a href="../categories/addCategory.php?source=editProduct&id=<?php echo $id ?>" class='btn btn-primary'> Add Category </a>

                <div class="mb-3">
                    <label for="image" class="form-label">Product Picture</label>
                    <input type="file" class="form-control" name="image" id="image">
                    <div id="imageError" class="text-danger"><?php echo $errors['image'] ?? ''; ?></div>
                </div>
                <input type="hidden" name="prod_id" value="<?php echo $product[0]['id']; ?>">

                <input type="submit" class="btn btn-primary" value="Save">
                <input type="reset" class="btn btn-warning" value="Reset">

            </fieldset>
        </form>
    </div>

    <div id="notificationModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Notification</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="modalNotificationMessage" class="text-success"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- for popup -->
    <!-- MDB -->
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <script>
        function showNotificationModal(message, isError) {
            var modal = new bootstrap.Modal(document.getElementById('notificationModal'));
            var modalTitle = document.getElementById('modalTitle');
            var modalMessage = document.getElementById('modalNotificationMessage');
            
            if (isError) {
                modalTitle.innerText = 'Error';
                modalMessage.className = 'text-danger';
            } else {
                modalTitle.innerText = 'Notification';
                modalMessage.className = 'text-success';
            }
            
            modalMessage.innerText = message;
            modal.show();
            setTimeout(function() {
                modal.hide();
            }, 3000);
        }

        function validateForm() {
            var name = document.getElementById('name').value;
            var price = document.getElementById('price').value;
            var cate_name = document.getElementById('cate_name').value;
            var image = document.getElementById('image').value;
            var available = document.getElementById('available').value;
            var isValid = true;
            var regex = /^(?!.*\s{2})[A-Za-z][A-Za-z\s]*$/;

            document.getElementById('nameError').innerText = '';
            document.getElementById('priceError').innerText = '';
            document.getElementById('catError').innerText = '';
            document.getElementById('imageError').innerText = '';
            document.getElementById('availableError').innerText = '';

            if (name.trim() === "") {
                document.getElementById('nameError').innerText = "Product name must be filled out";
                isValid = false;
            }
            if (name.length < 3) {
                document.getElementById('nameError').innerText = "Product name must be at least 3 characters long";
                isValid = false;
            }
            if (!regex.test(name)) {
                document.getElementById('nameError').innerText = "Product name must only contain letters and not start space and does not have two consecutive spaces";
                isValid = false;
            }

            if (price === "" || isNaN(price) || price <= 0) {
                document.getElementById('priceError').innerText = "Price must be a number greater than 0";
                isValid = false;
            }

            if (cate_name === "Select Category") {
                document.getElementById('catError').innerText = "Please select a category";
                isValid = false;
            }

            if (image !== "") {
                var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
                if (!allowedExtensions.exec(image)) {
                    document.getElementById('imageError').innerText = "Please upload a valid image file (jpg, jpeg, png, gif)";
                    isValid = false;
                }
            }

            if (available !== "available" && available !== "unavailable") {
                document.getElementById('availableError').innerText = "Available must be either 'yes' or 'no'";
                isValid = false;
            }

            return isValid;
        }
    </script>
</body>

</html>
<?php

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message']['text'];
    $isError = $_SESSION['message']['type'] === 'error';
    echo "<script>showNotificationModal('";
    echo $message;
    echo "', ";
    echo $isError ? 'true' : 'false';
    echo ");</script>";
    unset($_SESSION['message']);
}

if (!empty($_POST)) {
    $prod_id = $_POST['prod_id'];
    $prod_name = $_POST['prod_name'];
    $price = $_POST['price'];
    $cate_name = $_POST['cate_name'];
    $available = $_POST['available'];

    $product = $allProd->getProduct($id);
    $changed = [];

    if ($prod_name !== $product[0]['name']) {
        if (empty($prod_name)) {
            $errors['name'] = "Product name is required";
        } elseif (strlen($prod_name) < 3) {
            $errors['name'] = "Product name must be at least 3 characters long.";
        } elseif (!preg_match('/^[A-Za-z\s]+$/', $prod_name)) {
            $errors['name'] = "Product name must only contain letters and spaces.";
        } elseif ($allProd->getNameProduct($prod_name)) {
            $errors['name'] = "Product name already exists.";
        } else {
            $changed['name'] = $prod_name;
        }
    }

    if ($price !== strval($product[0]['price'])) {
       // echo gettype($price) . " dd ". gettype(strval($product[0]['price']));
        if (empty($price) || !is_numeric($price) || $price <= 0) {
            $errors['price'] = "Price must be a number greater than 0";
        } else {
            $changed['price'] = $price;
        }
    }

    if ($cate_name !== $category[0]['name']) {
        if ($cate_name === "Select Category") {
            $errors['cate_name'] = "Please select a category";
        } else {
            $changed['category_id'] = Category::getOneAsObject($cate_name);
        }
    }

    if (!empty($_FILES['image']['name'])) {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (!in_array($fileExtension, $allowedExtensions)) {
            $errors['image'] = "Please upload a valid image file (jpg, jpeg, png, gif)";
        } else {
            $changed['image'] = $_FILES['image'];
        }
    }

    if ($available !== $product[0]['available']) {
        if ($available !== "available" && $available !== "unavailable") {
            $errors['available'] = "Available must be either 'yes' or 'no'";
        } else {
            $changed['available'] = $available;
        }
    }

 var_dump( $changed);
    if (empty($errors)) {
        if (empty($changed)) {
            echo "<script>location.href='allProduct.php';</script>";
            exit();
        }
        //$one = Category::getOneAsObject($cate_name);
       // echo  $_FILES['image']['name'];

        $allProd->updateProd($prod_id, $changed);
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Product updated successfully'];
        echo "<script>location.href='allProduct.php';</script>";
        exit();
    } else {
        echo "<script>showNotificationModal('";
        echo implode("<br>", $errors);
        echo "', true);</script>";
    }
}


?>