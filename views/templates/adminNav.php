<?php
require_once "head.php";
// Check if session is not already started
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
// Check if the user is logged in and his role is admin or not
if (!isset($_SESSION['logged_in'])) {
  header('Location:http://localhost/phpproject/views/login.php');
} else if ($_SESSION['user']['role'] !== 'admin') {
  header('Location: userHome.php');
}
$base_url = "http://localhost/phpproject";

?>

<!-- Navbar start -->

<div class="container-fluid p-0 nav-bar" style="background-color: #343a40;">
  <nav class="navbar navbar-expand-lg navbar-dark py-1 fs-5">
    <a class="navbar-brand px-lg-4 me-0">
      <h1 class="m-0 fs-2 fs-md-1 display-4 text-white">ITI Cafeteria</h1>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
      <div class="navbar-nav px-3 px-lg-5">
        <a href="<?php echo $base_url; ?>/views/adminHome.php" class="nav-item nav-link mx-2 mx-lg-3 small">Home</a>
        
        <a href="<?php echo $base_url; ?>/AllProdect/view/products/allProduct.php" class="nav-item nav-link mx-2 mx-lg-3 small">Products</a>
        <a href="<?php echo $base_url; ?>/views/adduser.php" class="nav-item nav-link mx-2 mx-lg-3 small">Users</a>
        <a href="<?php echo $base_url; ?>/views/checks.php" class="nav-item nav-link mx-2 mx-lg-3 small">Checks</a>
        <a href="<?php echo $base_url; ?>/views/orders.php" class="nav-item nav-link mx-2 mx-lg-3 small">Orders</a>
      </div>
      <ul class="navbar-nav d-flex align-items-center">
        <li class="nav-item d-flex align-items-center">
          <a class="nav-link small d-flex align-items-center" href="#">
          <img class="nav-img rounded-circle me-2 me-lg-3" src="<?= $base_url; ?><?= $_SESSION['user']['image']; ?>" width="40px" width-lg="60px" />            <span class="nav-user"><?= $_SESSION['user']['name'] ?></span>
          </a>
        </li>
        <li class="nav-item d-none d-lg-block">
          <span class="text-white fw-bold fs-3 mx-2">|</span>
        </li>
        <li class="nav-item">
          <a href="../controllers/authenticateController.php" class="nav-link small">Logout</a>
        </li>
      </ul>
    </div>
  </nav>
</div>

<!-- Navbar End -->
 
