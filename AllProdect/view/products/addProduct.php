<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', '1');
include_once('../../../views/templates/adminNav.php');
require("../../controller/product.php");
$allProd = new ProductController();
$allCategories = Category::getAllAsObject();
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
                <legend> Add New Product</legend>
               
                <div class="mb-3">
                    <label for="name" class="form-label"> Product</label>
                    <input type="text" id="name" class="form-control" placeholder="Put your product name"
                        name="name">
                    <div id="nameError" class="text-danger"></div>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label"> Price</label>
                    <input type="number" id="price" class="form-control" name="price">
                    <div id="priceError" class="text-danger"></div>
                </div>

                <div class="mb-3">
                    <label for="cat_name" class="form-label"> Category</label>
                    <select id="cat_name" class="form-select" name="cat_name">
                        <option selected>Select Category</option>
                        <?php
                        for($i=0;$i<count($allCategories);$i++)
                            {
                                echo "<option>{$allCategories[$i]['name']}</option>";
                            }
                        ?>
                    </select>
                    <div id="catError" class="text-danger"></div>
                </div>
                <a href="../categories/addCategory.php?source=addProduct" class='btn btn-primary'> Add Category </a>
                <div class="mb-3">
                    <label for="image" class="form-label">Product Picture</label>
                    <input type="file" class="form-control" name="image" id="image">
                    <div id="imageError" class="text-danger"></div>
                </div>
                <div class="mb-3">
                    <label for="available" class="form-label">Available</label>
                    <select id="available" class="form-select" name="available">
                        <option value="available">Yes</option>
                        <option value="unavailable">No</option>
                    </select>
                    <div id="availableError" class="text-danger"></div>
                </div>
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
            var cat_name = document.getElementById('cat_name').value;
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

            if (cat_name === "Select Category") {
                document.getElementById('catError').innerText = "Please select a category";
                isValid = false;
            }

            if (image === "") {
                document.getElementById('imageError').innerText = "Please upload a product picture";
                isValid = false;
            } else {
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
    $errors = [];
    if (empty($_POST['name'])) {
        $errors['name'] = "Product name is required";
    } 
    if (strlen($_POST['name']) < 3) {
        $errors['name'] = "Product name must be at least 3 characters long.";
    }
    else {
        $existingProduct = $allProd->getNameProduct($_POST['name']);
        if ($existingProduct) {
            $errors['name'] = "Product name already exists";
        }
    }

    if (empty($_POST['price']) || !is_numeric($_POST['price']) || $_POST['price'] <= 0) {
        $errors['price'] = "Price must be a number greater than 0";
    }

    if ($_POST['cat_name'] === "Select Category") {
        $errors['cat_name'] = "Please select a category";
    }

    if (empty($_FILES['image']['name'])) {
        $errors['image'] = "Please upload a product picture";
    } else {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (!in_array($fileExtension, $allowedExtensions)) {
            $errors['image'] = "Please upload a valid image file (jpg, jpeg, png, gif)";
        }
    }

    if ($_POST['available'] !== "available" && $_POST['available'] !== "unavailable") {
        $errors['available'] = "Available must be either 'yes' or 'no'";
    }

    if (empty($errors)) {
        $one = Category::getOneAsObject($_POST['cat_name']);

        $allProd->insertProd($_POST['name'], $_POST['price'], $_FILES['image'], $_POST['available'], $one[0]['id']);
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Product added successfully'];
       
        echo "<script>location.href='allProduct.php';</script>";
        exit();
    
    } else {
        echo "<script>showNotificationModal('";
        echo implode("<br>", $errors);
        echo "', true);</script>";
    }
}
?>