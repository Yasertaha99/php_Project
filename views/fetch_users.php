<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
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
// Fetch users and their total order amounts
$user_id =$_GET['user_id'];
$sql = "SELECT u.id, u.name, COALESCE(SUM(o.total_price), 0) as total_amount
FROM user u
JOIN orders o ON u.id = o.user_id
WHERE u.role = 'user' AND o.status = 'done'";
if($user_id!="all") $sql .= " AND u.id = $user_id";
$sql .=" GROUP BY u.id";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result =  $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach($result as $row){
        $users[] = $row;
    
}

echo json_encode($users);
?>