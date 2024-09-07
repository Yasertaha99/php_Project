<?php
require_once "templates/head.php";

// Check if session is not already started
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

// Get errors from session or initialize empty array
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];

// Check for session message
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';

// Unset session errors and message to prevent them from being displayed again
unset($_SESSION['errors']);
unset($_SESSION['message']);
?>
<!DOCTYPE html>
<html lang="en">
<style>
.was-validated .form-control:valid,
.was-validated .form-control.is-valid,
.was-validated .form-control:invalid,
.was-validated .form-control.is-invalid {
  background-image: none; /* Remove the checkmark */
  border-color: inherit; /* Keep the normal border color */
  padding-right: 0.75rem; /* Adjust padding if necessary */}
</style>
<body style="background: url(../public/images/bg.jpg) center/100%;">
  <div class="container my-5 text-white">
    <h1 class="text-center">Cafeteria-PHP</h1>
    <form action="../controllers/authenticateController.php" method="post" class="col-md-4 m-auto my-5 p-3 rounded" style="border: 1px solid #634322; box-shadow: 10px 5px 20px green; " novalidate>
      <div class="row g-3 align-items-center my-3">
        <h3 class="text-center my-3">Log in</h3>
        <div class="mb-3">
          <label for="inputEmail" class="col-form-label d-block">Email</label>
          <input type="email" name="email" id="inputEmail" class="form-control p-2"  pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" >
          <div class="invalid-feedback">Please enter a valid email.</div>
        </div>
      </div>
      <div class="row g-3 align-items-center mb-3">
        <div class="mb-3">
          <label for="inputPassword" class="col-form-label">Password</label>
          <div class="input-group">
            <input type="password" name="password" id="inputPassword" class="form-control p-2" pattern="\S{6,}"  >
            <button class="btn btn-outline-light" type="button" id="togglePassword">
              <i class="fa fa-eye-slash"></i>
            </button>
            <div class="invalid-feedback">Please enter a valid password</div>
          </div>
        </div>
      </div>
      <?php if (isset($errors['login'])) : ?>
        <p class="fs-5 alert alert-danger rounded text-center p-2 mb-4"><?= $errors['login'] ?></p>
      <?php endif; ?>
      <div class="row m-auto text-center">
        <button type="submit" name="login" class="btn btn-primary mb-4 w-50 m-auto">Log In</button>
        <a href="send_rest_password.php" class="link-outline-primary mb-4">Forgot Password?</a>
      </div>
    </form>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const form = document.querySelector('form');

      // Show session message in a popup if it exists
      const message = "<?= $message ?>";
      if (message) {
        alert(message);
      }

      // Form validation on submit
      form.addEventListener('submit', (e) => {
        let isValid = true;
        const emailInput = document.getElementById('inputEmail');
        const passwordInput = document.getElementById('inputPassword');

        // Basic email and password validation
        if (!emailInput.value.match(/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/)) {
          emailInput.classList.add('is-invalid');
          isValid = false;
        } else {
          emailInput.classList.remove('is-invalid');
        }

        if (!passwordInput.value.match(/\S{6,}/)) {
          passwordInput.classList.add('is-invalid');
          isValid = false;
        } else {
          passwordInput.classList.remove('is-invalid');
        }

        if (!isValid) {
          e.preventDefault();
          e.stopPropagation();
        }
        form.classList.add('was-validated');
      });

      // Toggle password visibility
      function togglePasswordVisibility(inputId, buttonId) {
        const passwordInput = document.getElementById(inputId);
        const button = document.getElementById(buttonId);
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        if (type === 'password') {
          button.innerHTML = '<i class="fa fa-eye-slash"></i>';
        } else {
          button.innerHTML = '<i class="fa fa-eye"></i>';
        }
      }

      // Add event listener to toggle password visibility button
      const togglePasswordButton = document.getElementById('togglePassword');
      togglePasswordButton.addEventListener('click', () => {
        togglePasswordVisibility('inputPassword', 'togglePassword');
      });
    });
  </script>
</body>
</html>
