<?php
require_once "../models/db.php";
session_start();

class UserController
{
    private $db;

    public function __construct()
    {
        $this->db = DB::getInstance();
    }

    private function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    private function isEmailUnique($email)
    {
        $result = $this->db->select('user', ['email'], [$email], true);
        return !$result;
    }
    private function isRoomUnique($id)
    {
        $result = $this->db->select('room', ['id'], [$id], true);
        return !$result;
    }

    private function validateImage($file)
    {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        return in_array($file['type'], $allowedTypes) && $file['size'] <= 2 * 1024 * 1024;
    }

    public function addUser($name, $email, $password, $roomNum, $ext, $profilePicture)
    {
        $errors = [];

        // Validate inputs
        if (empty($name) ||strlen($name) < 3|| !preg_match("/^(?!.*\s{2})[A-Za-z][A-Za-z\s]*$/", $name)) {
            $errors[] = "Please provide a valid name.";
        }

        if (!$this->validateEmail($email)) {
            $errors[] = "Invalid email format.";
        }

        if (!$this->isEmailUnique($email)) {
            $errors[] = "Email already exists.";
        }

        if (strlen($password) < 6 || !preg_match('/[A-Z]/', $password) || !preg_match('/\d/', $password) || !preg_match('/[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]/', $password)) {
            $errors[] = "Password must be at least 6 characters, include at least one uppercase letter, one number, and one special character.";
        }

        if (!empty($profilePicture['name']) && !$this->validateImage($profilePicture)) {
            $errors[] = "Invalid image file.";
        }

        if ($roomNum < 1 ||$roomNum > 500 ) {
            $errors[] = " Please provide a 1 to 500 room number.";
        }
        if (!$this->isRoomUnique($roomNum) ) {
            $errors[] = " room number allrady revrsed.";
        }
        if ($ext < 1 || $ext > 50 ) {
            $errors[] = " Please provide a 1 to 50  extension number.";
        }
        // If there are validation errors, store them in session and redirect
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header("Location: ../views/addUser.php");
            exit;
        }

        // Process image upload if valid
        $fileName = '';
        if (!empty($profilePicture['name'])) {
            $targetDir = "../public/images/";
            $fileName = uniqid() . '_' . basename($profilePicture['name']);
            $targetPath = $targetDir . $fileName;

            if (!move_uploaded_file($profilePicture['tmp_name'], $targetPath)) {
                $_SESSION['errors'] = ["Failed to upload image."];
                header("Location: ../views/addUser.php");
                exit;
            }
        }

        // Store hashed password and user information
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            $this->db->insert('room', ['id' => $roomNum, 'ext' => $ext]);

            // Insert user information into the database
            $this->db->insert('user', [
                'name' => $name,
                'email' => $email,
                'password' => $hashedPassword,
                'image' => $fileName,
                'room_id' => $roomNum,
               
            ]);

            $_SESSION['success'] = "User added successfully!";
            header("Location: ../views/adminUsers.php");
        } catch (Exception $e) {
            $_SESSION['errors'] = ["general" => "An error occurred while adding the user. Please try again."];
            header("Location: ../views/addUser.php");
        }
    }
}

$userController = new UserController();
$userController->addUser($_POST['name'], $_POST['email'], $_POST['password'], $_POST['roomNum'], $_POST['ext'], $_FILES['profilePicture']);
?>
