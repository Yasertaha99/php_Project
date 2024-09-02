<?php
// Assuming you have a MySQL database connection
// $servername = "localhost";
// $username = "username";
// $password = "password";
// $dbname = "database_name";

// // Create connection
// // $conn = new mysqli($servername, $username, $password, $dbname);

// // // Check connection
// // if ($conn->connect_error) {
// //     die("Connection failed: " . $conn->connect_error);
// }
require_once "../models/db.php";

$db = DB::getInstance();
$conn=$db->getConnection();

$order_id = $_GET['order_id'];
//echo($order_id);
// Fetch products for the order
$sql = "SELECT p.image, p.price, op.quantity 
        FROM order_product op 
        JOIN product p ON op.product_id = p.id 
        WHERE op.order_id = $order_id";
$stmt = $conn->prepare($sql);
$stmt->execute();

$result =  $stmt->fetchAll(PDO::FETCH_ASSOC);
//var_dump( $result );
foreach($result as $row){
        $user[] = $row;
    
}

echo json_encode($user)
?>