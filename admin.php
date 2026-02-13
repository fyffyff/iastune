<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: admin_login.php");
    exit();
}
?>

<?php
include "config.php";

$result = $conn->query("SELECT * FROM users WHERE status='pending'");

echo "<h2>Pending Users</h2>";

while($row = $result->fetch_assoc()){
    echo "Name: " . $row['name'] . " | Phone: " . $row['phone'];
    echo " <a href='approve.php?id=".$row['id']."'>Approve</a><br><br>";
}
?>
