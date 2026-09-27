<?php
include 'connect.php';

$query = $_GET['query'] ?? '';

if($query != ''){
    $select_suggestions = $conn->prepare("SELECT name FROM `products` WHERE name LIKE ? LIMIT 5");
    $select_suggestions->execute(["%$query%"]);
    $result = $select_suggestions->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($result);
}
?>