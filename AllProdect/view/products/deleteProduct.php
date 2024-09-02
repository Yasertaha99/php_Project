<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', '1');
include_once('../../../views/templates/adminNav.php');
require("../../controller/product.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $allProd = new ProductController();

    // Store the product ID in session to use it after confirmation
    $_SESSION['delete_product_id'] = $id;
}

if (isset($_POST['confirm_delete'])) {
    if ($_POST['confirm_delete'] === 'yes') {
        $id = $_SESSION['delete_product_id'];
        $allProd = new ProductController();
        $allProd->delete($id);
        unset($_SESSION['delete_product_id']);
        $_SESSION['message'] = ['type' => 'warning', 'text' => 'Product deleted successfully'];
    } else {
        $_SESSION['message'] = ['type' => 'warning', 'text' => 'Product not deleted'];
    }

    // Redirect to the product page
    echo "<script>location.href='allProduct.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Product</title>
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
        <form id="deleteForm" action="#" method="post">
            <input type="hidden" name="confirm_delete" id="confirm_delete" value="">
        </form>
    </div>

    <div id="confirmationModal" class="modal fade" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this product?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="confirmDeleteNo" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteYes">Yes</button>
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
        function showConfirmationModal() {
            var modal = new bootstrap.Modal(document.getElementById('confirmationModal'));
            modal.show();
        }

        // Handle "Yes" button click
        document.getElementById('confirmDeleteYes').addEventListener('click', function() {
            document.getElementById('confirm_delete').value = 'yes';
            document.getElementById('deleteForm').submit();
        });

        // Handle "No" button click
        document.getElementById('confirmDeleteNo').addEventListener('click', function() {
            document.getElementById('confirm_delete').value = 'no';
            document.getElementById('deleteForm').submit();
        });

        // Show the confirmation modal when the page loads
        window.onload = function() {
            showConfirmationModal();
        };
    </script>
</body>

</html>