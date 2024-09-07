<?php
require_once "templates/adminNav.php";

$errorMessages = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
$successMessage = isset($_SESSION['success']) ? $_SESSION['success'] : '';

unset($_SESSION['errors']);
unset($_SESSION['success']);
?>
<style>
.was-validated .form-control:valid,
.was-validated .form-control.is-valid,
.was-validated .form-control:invalid,
.was-validated .form-control.is-invalid {
  background-image: none; /* Remove the checkmark */
  border-color: inherit; /* Keep the normal border color */
  padding-right: 0.75rem; /* Adjust padding if necessary */}
</style>
<div class="container my-4 col-md-6">
  <h1 class="mb-1">Add User</h1>
  <?php if (!empty($errorMessages)) : ?>
    <div class="fs-5 alert alert-danger rounded text-center p-2 mb-4" role="alert">
      <?php foreach ($errorMessages as $error) : ?>
        <p><?= htmlspecialchars($error) ?></p>
      <?php endforeach; ?>
    </div>  
  <?php endif; ?>
 
  <form class="my-4 needs-validation row" action="../controllers/addUserController.php" method="post" enctype="multipart/form-data" novalidate>
    <div class="row">
      <div class="col-md-12">
        <div class="mb-3">
          <label for="name" class="form-label h6">Name</label>
          <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" pattern="^[A-Za-z]+(?:\s[A-Za-z]+)*$" title="Enter a valid name" required>
          <div class="invalid-feedback">
            Please provide a valid name.name must only contain letters and not start space and does not have two consecutive spaces
          </div>
        </div>
        <div class="mb-3">
          <label for="email" class="form-label h6">Email</label>
          <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" title="Please enter a valid email address" required>
          <div class="invalid-feedback">
            Please provide a valid email address.
          </div>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label h6">Password</label>
          <div class="input-group">
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" pattern="\S{6,}" required>
            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
              <i class="fa fa-eye-slash"></i>
            </button>
            <div class="invalid-feedback">Please provide a valid password. Password must be at least 6 characters, include at least one uppercase letter, one number, and one special character.</div>
          </div>
        </div>
        <div class="mb-3">
          <label for="confirmPassword" class="form-label h6">Confirm Password</label>
          <div class="input-group">
            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm password" required>
            <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
              <i class="fa fa-eye-slash"></i>
            </button>
            <div class="invalid-feedback">
              Passwords do not match.
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="mb-3">
          <label for="roomNum" class="form-label h6">Room Number</label>
          <input type="number" class="form-control" id="roomNum" name="roomNum" placeholder="Enter room number" min="1" max="50" required>
          <div class="invalid-feedback">
            Please provide a 1 to 500 room number.
          </div>
        </div>
        <div class="mb-3">
          <label for="ext" class="form-label h6">Extension</label>
          <input type="number" class="form-control" id="ext" name="ext" placeholder="Enter Ext number" min="1" max="50" required>
          <div class="invalid-feedback">
            Please provide a 1 to 50  extension number.
          </div>
        </div>
        <div class="mb-3">
          <label for="profilePicture" class="form-label h6">Profile Picture</label>
          <input type="file" class="form-control" id="profilePicture" name="profilePicture" required>
          <div class="invalid-feedback">
          Only JPEG, JPG, and PNG formats are allowed and File size must be less than 2MB.
          </div>
        </div>
      </div>
    </div>
    <div class="mb-3">
      <button type="submit" class="btn button">Save</button>
      <button type="reset" class="btn btn-secondary">Reset</button>
    </div>
  </form>
</div>
<script >document.addEventListener('DOMContentLoaded', () => {
    let isValid = false;

        const form = document.querySelector('form');
        var regex = /^(?!.*\s{2})[A-Za-z][A-Za-z\s]*$/;
        const nameInput = document.getElementById('name');

  const passwordInput = document.getElementById('password');
  const confirmPasswordInput = document.getElementById('confirmPassword');
  const emailInput = document.getElementById('email');
  const profilePictureInput = document.getElementById('profilePicture');
  const roomNumInput = document.getElementById('roomNum');
  const extInput = document.getElementById('ext');

  //const form = document.querySelector("form.needs-validation");

  // Password toggle
  document.getElementById('togglePassword').addEventListener('click', function () {
    togglePasswordVisibility(passwordInput, this);
  });

  document.getElementById('toggleConfirmPassword').addEventListener('click', function () {
    togglePasswordVisibility(confirmPasswordInput, this);
  });

  function togglePasswordVisibility(input, button) {
    if (input.type === 'password') {
      input.type = 'text';
      button.innerHTML = '<i class="fa fa-eye"></i>';
    } else {
      input.type = 'password';
      button.innerHTML = '<i class="fa fa-eye-slash"></i>';
    }
  }
    const passwordPattern = /^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]).{6,}$/;
    
    const validateName = () => {
    if (!regex.test(nameInput.value) || nameInput.value.length < 3) {
      nameInput.classList.add('is-invalid');
      return false;
    } else {
      nameInput.classList.remove('is-invalid');
      return true;
    }
  };

   const validatePassword = () => {
    if (!passwordInput.value.match(passwordPattern)) {
      passwordInput.classList.add('is-invalid');
      return false;
    } else {
      passwordInput.classList.remove('is-invalid');
      return true;
    }
  };

  const validateConfirmPassword = () => {
    if (confirmPasswordInput.value !== passwordInput.value) {
      confirmPasswordInput.classList.add('is-invalid');
      return false;
    } else {
      confirmPasswordInput.classList.remove('is-invalid');
      return true;
    }
  };

  // Email validation
  const validateEmail = () => {
    if (!/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(emailInput.value)) {
      emailInput.classList.add('is-invalid');
      return false;
    } else {
      emailInput.classList.remove('is-invalid');
      return true;
    }
  };

  // Image validation
  const validateProfilePicture = () => {
    const file = profilePictureInput.files[0];
    if (!file || !['image/jpeg', 'image/png', 'image/jpg'].includes(file.type) || (file && file.size > 2 * 1024 * 1024)) {
      profilePictureInput.classList.add('is-invalid');
      return false;
    } else {
      profilePictureInput.classList.remove('is-invalid');
      return true;
    }
  };

  const validateroomNum = () => {
    if (roomNumInput.value < 1 ||roomNumInput.value > 500 ) {
      roomNumInput.classList.add('is-invalid');
      return false;
    } else {
      roomNumInput.classList.remove('is-invalid');
      return true;
    }
  };

  const validateextInput = () => {
    if ( extInput.value < 1 || extInput.value> 50) {
      extInput.classList.add('is-invalid');
      return false;
    } else {
      extInput.classList.remove('is-invalid');
      return true;
    }
  };
  // Form submission validation
  // validateName();
  // validatePassword();
  // validateConfirmPassword();
  // validateEmail();
  // validateProfilePicture();

  // Add event listeners for dynamic validation
  nameInput.addEventListener('input', validateName);
  passwordInput.addEventListener('input', validatePassword);
  confirmPasswordInput.addEventListener('input', validateConfirmPassword);
  emailInput.addEventListener('input', validateEmail);
  profilePictureInput.addEventListener('change', validateProfilePicture);
  roomNumInput.addEventListener('input', validateroomNum);
  extInput.addEventListener('input', validateextInput);



  // Form submission validation
  form.addEventListener('submit', (event) => {
    let isValid = validateName() && validatePassword() && validateConfirmPassword() && validateEmail() && validateProfilePicture() &&  validateroomNum() &&  validateextInput() ;
    if (!isValid) {
      event.preventDefault();
      event.stopPropagation();
    }
    form.classList.add('was-validated');
  });


  
});
</script>
