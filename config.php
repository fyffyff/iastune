<?php
$conn = new mysqli(
    "sql306.infinityfree.com",
    "if0_41147771",
    "MewAVQiYZBUDE",
    "if0_41147771_users"
);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
