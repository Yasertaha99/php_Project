<?php
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

$token = isset($_GET['token']) ? $_GET['token'] : null;

if ($token === null) {
    die("Invalid token.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password === $confirm_password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // البحث عن المستخدم باستخدام التوكين
        $stmt = $pdo->prepare("SELECT * FROM user WHERE reset_token = ?");
        $stmt->execute([$token]);

        if ($stmt->rowCount() > 0) {
            $stmt = $pdo->prepare("UPDATE user SET password = ?, reset_token = NULL WHERE reset_token = ?");
            if ($stmt->execute([$hashed_password, $token])) {
                $message = "Password has been successfully updated.";
            } else {
                $message = "An error occurred while updating the password. Please try again.";
            }
        } else {
            $message = "Invalid token. Password reset failed.";
        }
    } else {
        $message = "Passwords do not match. Please try again.";
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
        input[type="password"] {
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
        <h2>Confirm Password</h2>
        <form action="" method="POST">
            <label for="password">New Password</label>
            <input type="password" id="password" name="password" required placeholder="Enter your new password">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" required placeholder="Confirm your new password">
            <button type="submit">Update Password</button>
        </form>
        <div class="footer">
            <p><?php if (isset($message)) echo $message; ?></p>
        </div>
    </div>
</body>
</html>
