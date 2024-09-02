<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
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

$user_id = isset($_GET['user_id'])?$_GET['user_id']:"";
$from_date = isset($_GET['from_date'])?$_GET['from_date']:"";
$to_date = isset($_GET['to_date']) ? $_GET['to_date']:"";
$status = isset( $_GET['status']) ?$_GET['status']: "";
$page = $_GET['page'] ?? 1;
$limit = $_GET['limit'] ?? 10;
$offset = ($page - 1) * $limit;

// Fetch orders for the user within the date range
if($status==""){
    $sql = "SELECT id, order_date, total_price 
    FROM orders WHERE
    user_id = $user_id AND
    status = 'done'";
if ($from_date != "" && $to_date != "") {
    $sql .= " AND order_date BETWEEN '$from_date' AND '$to_date'";
} elseif ($from_date != "") {
    $sql .= " AND order_date >= '$from_date'";
} elseif ($to_date != "") {
    $sql .= " AND order_date <= '$to_date'";
}
    }
else if ($status == "all") {$sql = "SELECT o.id, o.order_date, o.total_price, o.status, u.name as user_name, o.room_id 
FROM orders o 
JOIN user u ON o.user_id = u.id LIMIT $limit OFFSET $offset";
}

else
$sql = "SELECT o.id, o.order_date, o.total_price, o.status, u.name as user_name, o.room_id 
FROM orders o 
JOIN user u ON o.user_id = u.id WHERE o.status = '$status' LIMIT $limit OFFSET $offset";

$stmt = $conn->prepare($sql);
$stmt->execute();

$result =  $stmt->fetchAll(PDO::FETCH_ASSOC);
$orders=[];
foreach($result as $row){
    $orders[]= $row;
    
}
if($status=="") { 
     echo json_encode($orders); 
    }
else {$sqlCount = "SELECT COUNT(*) as total FROM orders";
if ($status !== "all") {
    $sqlCount .= " WHERE status = '$status'";
}
$stmt = $conn->prepare($sqlCount );
$stmt->execute();
//$resultCount = $conn->query($sqlCount);
$totalCount = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
$totalPages = ceil($totalCount / $limit);
echo json_encode(['orders' => $orders, 'totalPages' => $totalPages]);}
?>