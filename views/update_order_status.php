<?php
// Assuming you have a MySQL database connection
// $servername = "localhost";
// $username = "username";
// $password = "password";
// $dbname = "database_name";

// // Create connection
// $conn = new mysqli($servername, $username, $password, $dbname);

// // Check connection
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }
require_once "../models/db.php";

$db = DB::getInstance();
$conn=$db->getConnection();

$data = json_decode(file_get_contents("php://input"), true);
$orderId = $data['orderId'];
$newStatus = $data['newStatus'];

// Update order status
$sql = "UPDATE orders SET status = '$newStatus' WHERE id = $orderId";
if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $conn->error]);
}

?>