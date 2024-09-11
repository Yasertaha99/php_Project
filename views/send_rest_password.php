
<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');
require '../vendor/autoload.php';
require_once "../models/db.php";
require_once "templates/head.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$db = DB::getInstance();
$conn=$db->getConnection();


// try {
//     $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
//     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// } catch (PDOException $e) {
//     die("Database connnection failed: " . $e->getMessage());
// }
function send_mail($recipient, $subject, $message)
{
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPDebug  = 2;  
    $mail->SMTPAuth   = TRUE;
    $mail->SMTPSecure = "tls";
    $mail->Port       = 587;
    $mail->Host       = "smtp.gmail.com";

    $mail->Username   = "msaidhassan2015@gmail.com";
    $mail->Password   = "mfcxmtvexhmysxxx";

    $mail->IsHTML(true);
    $mail->AddAddress($recipient, "Esteemed Customer Cafeteria 2");
    $mail->SetFrom("msaidhassan2015@gmail.com", "Cafeteria");
    $mail->Subject = $subject;
    $mail->MsgHTML($message);

    if (!$mail->Send()) {
        echo "Error while sending Email: " . $mail->ErrorInfo;
        return false;
    } else {
        return true;
    }
}
if(isset($_POST['email'])){
// إنشاء توكن عشوائي

    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

    if (!$email) {
        $errors['forgot_password'] = "Please enter a valid email.";
    } 
else {
    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->execute([$email ]);
    
    if ($stmt->rowCount() > 0) {
$token = bin2hex(random_bytes(16));
// تخزين التوكين في قاعدة البيانات مع عنوان البريد الإلكتروني
$stmt = $conn->prepare("UPDATE user SET reset_token = ? WHERE email = ?");
$stmt->execute([hash('sha256', $token), $email]);

// بناء الرابط مع التوكين
$mailHtml = "Your link for reset password <a href='http://localhost/phpproject/views/confirm_password.php?token=" . $token . "'>Here</a>";

// إرسال البريد الإلكتروني
send_mail($_POST['email'], "Password reset", $mailHtml);

$_SESSION['message'] = "A password reset link has been sent to your email.";
echo "<script>location.href='login.php';</script>";
exit();
}
else {             $errors['forgot_password'] = "No account found with this email.";
}


}
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" conntent="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
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
        <h3 class="text-center my-3">Forgot Password</h3>
        <div class="mb-3">
          <label for="inputEmail" class="col-form-label d-block">Email</label>
          <input type="email" name="email" id="inputEmail" class="form-control p-2" aria-describedby="emailHelpInline" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" required>
          <div class="invalid-feedback">Please enter a valid email.</div>
        </div>
      </div>
      <?php if (isset($errors['forgot_password'])) : ?>
        <p class="fs-5 alert alert-danger rounded text-center p-2 mb-4"><?= $errors['forgot_password'] ?></p>
      <?php endif; ?>
      <div class="row m-auto text-center">
        <button type="submit" name="forgot" class="btn btn-primary mb-4 w-50 m-auto">Send Reset Link</button>
        <a href="login.php" class="link-outline-primary mb-4">Back to Login</a>
      </div>
    </form>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const form = document.querySelector('form');

      form.addEventListener('submit', (e) => {
        let isValid = true;
        const emailInput = document.getElementById('inputEmail');
        //const passwordInput = document.getElementById('inputPassword');

        // Basic email and password validation
        if (!emailInput.value.match(/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/)) {
          emailInput.classList.add('is-invalid');
          isValid = false;
        } else {
          emailInput.classList.remove('is-invalid');
        }
        if (!isValid) {
          e.preventDefault();
          e.stopPropagation();
        }
        form.classList.add('was-validated');
      });
    });
  </script>
</body>


</html>
