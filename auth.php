<?php
session_start();
include "config.php";

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$device_token = $_COOKIE['device_token'] ?? '';

$stmt = $conn->prepare("
SELECT id FROM user_devices 
WHERE user_id=? AND device_token=?
");

$stmt->bind_param("is", $_SESSION['user'], $device_token);
$stmt->execute();
$stmt->store_result();

if($stmt->num_rows == 0){
    session_destroy();
    die("Unauthorized device.");
}
?>
