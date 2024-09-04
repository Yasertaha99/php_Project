
<?php
session_start();

require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$host = 'localhost';
$db = 'p1';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
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

// إنشاء توكن عشوائي
$token = bin2hex(random_bytes(16));

// تخزين التوكين في قاعدة البيانات مع عنوان البريد الإلكتروني
$stmt = $pdo->prepare("UPDATE user SET reset_token = ? WHERE email = ?");
$stmt->execute([$token, $_POST['email']]);

// بناء الرابط مع التوكين
$mailHtml = "Your link for reset password <a href='http://localhost/phpproject/views/confirm_password.php?token=" . urlencode($token) . "'>Here</a>";

// إرسال البريد الإلكتروني
send_mail($_POST['email'], "Password reset", $mailHtml);


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-image: url('../public/images/bg.jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.9); /* Semi-transparent box */
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }
        h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #333;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #555;
        }
        input[type="email"] {
            width: 100%;
            padding: 0.75rem;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 0.75rem;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
        }
        button:hover {
            background-color: #0056b3;
        }
        .footer {
            text-align: center;
            margin-top: 1rem;
            font-size: 0.875rem;
            color: #777;
        }
      
            
        a {
    text-decoration: none; 
    color: #fff; 
}

a:hover {
    color: #ccc; 
}
    </style>
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
        <form action="" method="POST">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" required placeholder="Enter your email address">
            <button type="submit"> Send Rest link</button>
        </form>
        <div class="footer">
            <p>We will send a link to reset your password to your email address.</p>
        </div>
    </div>
</body>
</html>
