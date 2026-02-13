<?php
include "config.php";

$name = $_POST['name'];
$phone = $_POST['phone'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

if(!preg_match('/^[6-9]\d{9}$/', $phone)){
    die("Invalid Indian phone number");
}

$stmt = $conn->prepare("INSERT INTO users (name, phone, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $phone, $password);
$stmt->execute();

echo "Signup successful. Wait for admin approval.";
?>
