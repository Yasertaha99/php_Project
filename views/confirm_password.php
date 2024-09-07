<?php
session_start();
require_once "../models/db.php";
require_once "templates/head.php";
error_reporting(E_ALL);
ini_set('display_errors', '1');
$db = DB::getInstance();
$conn=$db->getConnection();
// try {
//     $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
//     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// } catch (PDOException $e) {
//     die("Database connection failed: " . $e->getMessage());
// }

    // $token = isset($_GET['token']) ? $_GET['token'] : null;

// if ($token === null) {
//     die("Invalid token.");
// }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $token = $_GET['token'] ?? '';
    
    $errors = [];

    // Check if the password is valid
    if (empty($password) || strlen($password) < 6 || 
        !preg_match('/[A-Z]/', $password) ||    // At least one uppercase letter
        !preg_match('/\d/', $password) ||       // At least one digit
        !preg_match('/[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]/', $password)) {  // At least one special character
        $errors['reset'] = "Password must be at least 6 characters, include at least one uppercase letter, one number, and one special character.";
    }
    
    if ($password !== $confirm_password) {
        $errors['reset'] = "Passwords do not match. Please try again.";
    }
     if( $token ===''){ $errors['reset'] = "Empty token. Password reset failed.";}

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        // Find the user by token
        $stmt = $conn->prepare("SELECT * FROM user WHERE reset_token = ?");
        $stmt->execute([hash('sha256', $token)]);

        if ($stmt->rowCount() > 0) {
            $stmt = $conn->prepare("UPDATE user SET password = ?, reset_token = '' WHERE reset_token = ?");
            if ($stmt->execute([$hashed_password, hash('sha256', $token)])) {
                $_SESSION['message'] = "Password has been successfully updated.";
                echo "<script>location.href='login.php';</script>";
            } else {
                $errors['reset'] = "An error occurred while updating the password. Please try again.";
            }
        } else {
            $errors['reset'] = "Invalid token. Password reset failed.";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Password</title>
    <style>
.was-validated .form-control:valid,
.was-validated .form-control.is-valid,
.was-validated .form-control:invalid,
.was-validated .form-control.is-invalid {
  background-image: none; /* Remove the checkmark */
  border-color: inherit; /* Keep the normal border color */
  padding-right: 0.75rem; /* Adjust padding if necessary */}
</style>
</head>
<body style="background: url(../public/images/bg.jpg) center/100%;">
  <div class="container my-5 text-white">
    <h1 class="text-center">Cafeteria-PHP</h1>
    <form action="" method="post" class="col-md-4 m-auto my-5 p-3 rounded" style="border: 1px solid #634322; box-shadow: 10px 5px 20px green;" novalidate>
      <div class="row g-3 align-items-center my-3">
        <h3 class="text-center my-3">Reset Password</h3>
        <div class="mb-3">
          <label for="inputPassword" class="col-form-label">New Password</label>
          <div class="input-group">
            <input type="password" name="new_password" id="inputPassword" class="form-control p-2" pattern="\S{6,}" required>
            <button class="btn btn-outline-light" type="button" id="togglePassword">
              <i class="fa fa-eye-slash"></i>
            </button>
            <div class="invalid-feedback">Please enter a valid password</div>
          </div>
        </div>
        <div class="mb-3">
          <label for="confirmPassword" class="col-form-label">Confirm Password</label>
          <div class="input-group">
            <input type="password" name="confirm_password" id="confirmPassword" class="form-control p-2" pattern="\S{6,}" required>
            <button class="btn btn-outline-light" type="button" id="toggleConfirmPassword">
              <i class="fa fa-eye-slash"></i>
            </button>
            <div class="invalid-feedback">Passwords do not match</div>
          </div>
        </div>
      </div>
      <?php if (isset($errors['reset'])) : ?>
        <p class="fs-5 alert alert-danger rounded text-center p-2 mb-4"><?= $errors['reset'] ?></p>
      <?php endif; ?>
      <div class="row m-auto text-center">
        <button type="submit" name="reset" class="btn btn-primary mb-4 w-50 m-auto">Reset Password</button>
      </div>
    </form>
  </div>

  <script>
document.addEventListener('DOMContentLoaded', () => {
  const form = document.querySelector('form');

  form.addEventListener('submit', (e) => {
    const passwordInput = document.getElementById('inputPassword');
    const confirmPasswordInput = document.getElementById('confirmPassword');
    const password = passwordInput.value;
    const confirmPassword = confirmPasswordInput.value;
    
    let isValid = true;
    const passwordPattern = /^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]).{6,}$/;

    // Validate password pattern
    if (!password.match(passwordPattern)) {
      passwordInput.classList.add('is-invalid');
      isValid = false;
    } else {
      passwordInput.classList.remove('is-invalid');
    }

    // Validate password match
    if (password !== confirmPassword) {
      confirmPasswordInput.classList.add('is-invalid');
      isValid = false;
    } else {
      confirmPasswordInput.classList.remove('is-invalid');
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

  // Add event listeners to toggle password visibility buttons
  const togglePasswordButton = document.getElementById('togglePassword');
  togglePasswordButton.addEventListener('click', () => {
    togglePasswordVisibility('inputPassword', 'togglePassword');
  });

  const toggleConfirmPasswordButton = document.getElementById('toggleConfirmPassword');
  toggleConfirmPasswordButton.addEventListener('click', () => {
    togglePasswordVisibility('confirmPassword', 'toggleConfirmPassword');
  });
});
</script>

</body>

</html>
