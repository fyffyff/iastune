<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request.");
}

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

/* CHANGE THESE */
$admin_user = "admin";
$admin_pass = "IAS@2026";

if ($username === $admin_user && $password === $admin_pass) {

    $_SESSION['admin'] = true;
    header("Location: admin.php");
    exit();

} else {
    die("Invalid admin credentials.");
}
?>
