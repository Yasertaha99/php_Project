<?php 
error_reporting(E_ALL);
ini_set('display_errors', '1');
include_once('../../../views/templates/adminNav.php');

require("../../controller/categoryController.php");
$cate = new categoryController(); ?>
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
    <h2>Add Category</h2>
    <form action="#" method="post" onsubmit="return validateForm()">    
        <div class="form-group">
            <label for="catename">Category Name</label>
            <input type="text" class="form-control" name="cate_name" id="catename" aria-describedby="emailHelp"
                placeholder="Enter Category Name" required>
        </div>
        <div id="nameError" class="text-danger"></div>

        <div class="form-group m-10">
            <input type="submit" class="btn btn-primary" value="Add Category">
        </div>
    </form>
</div>
<div id="errorModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Error</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="modalErrorMessage" class="text-danger"></p>
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
    function showErrorModal(message) {
        var modal = new bootstrap.Modal(document.getElementById('errorModal'));
        var modalMessage = document.getElementById('modalErrorMessage');
        modalMessage.innerText = message;
        modal.show();
    }

    function validateForm() {
       
        var cateName = document.getElementById("catename").value;
        regex = /^[A-Za-z\s]+$/;

        if (cateName.trim() === "") {
          //  showErrorModal("Category name must be filled out");
            document.getElementById('nameError').innerText = "Category name must be filled out";

            return false;
        }
        if (cateName.length < 3) {
            document.getElementById('nameError').innerText = "Category name must be at least 3 characters long";

          //  showErrorModal("Category name must be at least 3 characters long");
            return false;
        }

        if (!regex.test( cateName)) {
            document.getElementById('nameError').innerText  = "Category name must only contain letters and spaces.";
        // Additional front-end validation can be added here
        return false;
    }
}

</script>
</body>

</html>
<?php
 $source = $_GET['source'];
 if(isset($_GET['id']) )
    $id=$_GET['id'];
 if (!empty($_POST)) {
    $errors = [];

    $cate_name = $_POST["cate_name"];
    $regex = '/^[A-Za-z\s]+$/';

    if (strlen($cate_name) < 3) {
        $errors['name'] = "Category name must be at least 3 characters long.";
    }
    if (!preg_match($regex, $cate_name)) {
        $errors['name'] = "Category name must only contain letters and spaces.";
    }
    if ($cate->isCategoryNameUnique($cate_name)) {
        $errors['name'] = "Category name already exists.";
    } 
    if (empty($errors)) {
        $cate->store($cate_name);
        $massage=[];
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Category added successfully'];

        echo "<script>location.href = '../products/{$source}.php" . (isset($id) ? "?id={$id}" : "") . "';</script>";
        exit();
    } else {
        echo "<script>showErrorModal('";
        echo implode("<br>", $errors);
        echo "');</script>";
    }
}
?>