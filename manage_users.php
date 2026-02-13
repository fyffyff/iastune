<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include "config.php";

if(!isset($_SESSION['admin'])){
    header("Location: admin_login.php");
    exit();
}

/* ===========================
   DELETE USER
=========================== */
if(isset($_GET['delete'])){

    $id = (int)$_GET['delete'];

    // Delete user devices first (important)
    $delDevices = $conn->prepare("DELETE FROM user_devices WHERE user_id=?");
    $delDevices->bind_param("i", $id);
    $delDevices->execute();
    $delDevices->close();

    // Delete user
    $delUser = $conn->prepare("DELETE FROM users WHERE id=?");
    $delUser->bind_param("i", $id);
    $delUser->execute();
    $delUser->close();

    header("Location: manage_users.php");
    exit();
}

/* ===========================
   ADD USER
=========================== */
if(isset($_POST['add_user'])){

    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);
    $device_limit = (int)$_POST['device_limit'];

    if($phone && $password){

        // Check duplicate
        $check = $conn->prepare("SELECT id FROM users WHERE phone=? LIMIT 1");
        $check->bind_param("s", $phone);
        $check->execute();
        $check->store_result();

        if($check->num_rows == 0){

            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users (phone, password, status, device_limit) VALUES (?, ?, 'approved', ?)");
            $stmt->bind_param("ssi", $phone, $hash, $device_limit);
            $stmt->execute();
            $stmt->close();
        }

        $check->close();
    }

    header("Location: manage_users.php");
    exit();
}

/* ===========================
   GET USERS
=========================== */
$users = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>

<html>
<head>
<title>Manage Users</title>
</head>
<body>

<h2>Admin - Manage Users</h2>

<form method="POST">
Phone:
<input type="text" name="phone" required>

Password:
<input type="text" name="password" required>

Device Limit:
<input type="number" name="device_limit" value="2" min="1">

<button type="submit" name="add_user">Add User</button>
</form>

<hr>

<table border="1" cellpadding="5">
<tr>
<th>ID</th>
<th>Phone</th>
<th>Password (Hashed)</th>
<th>Status</th>
<th>Device Limit</th>
<th>Actions</th>
</tr>

<?php while($row = $users->fetch_assoc()) { ?>
<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo $row['phone']; ?></td>
<td style="font-size:10px;"><?php echo $row['password']; ?></td>
<td><?php echo $row['status']; ?></td>
<td><?php echo isset($row['device_limit']) ? $row['device_limit'] : 2; ?></td>
<td>
<a href="manage_users.php?delete=<?php echo $row['id']; ?>" 
   onclick="return confirm('Are you sure you want to delete this user?')">
Delete
</a>
</td>
</tr>
<?php } ?>

</table>

</body>
</html>
