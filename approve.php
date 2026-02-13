<?php
include "config.php";

$id = $_GET['id'];

$conn->query("UPDATE users SET status='approved' WHERE id=$id");

echo "User Approved Successfully.";
?>
