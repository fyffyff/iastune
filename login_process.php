<?php
session_start();
include "config.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request.");
}

$phone = trim($_POST['phone'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($phone) || empty($password)) {
    die("All fields are required.");
}

/* ================================
   1️⃣ Check Approved User
================================ */

$stmt = $conn->prepare("
    SELECT id, password, device_limit 
    FROM users 
    WHERE phone=? AND status='approved' 
    LIMIT 1
");

$stmt->bind_param("s", $phone);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user || !password_verify($password, $user['password'])) {
    die("Invalid login details.");
}

$user_id = $user['id'];
$device_limit = intval($user['device_limit'] ?? 2); // default 2 safety

/* ================================
   2️⃣ Device Token Logic
================================ */

$device_token = $_COOKIE['device_token'] ?? null;

if (!$device_token) {
    $device_token = bin2hex(random_bytes(64));

    setcookie(
        "device_token",
        $device_token,
        time() + 31536000,  // 1 year
        "/",
        "",
        false,  // IMPORTANT: false for HTTP hosting
        true
    );
}

/* ================================
   3️⃣ Check If Device Exists
================================ */

$stmt2 = $conn->prepare("
    SELECT id 
    FROM user_devices 
    WHERE user_id=? AND device_token=? 
    LIMIT 1
");

$stmt2->bind_param("is", $user_id, $device_token);
$stmt2->execute();
$stmt2->store_result();

if ($stmt2->num_rows == 0) {

    /* Count existing devices */
    $stmt3 = $conn->prepare("
        SELECT COUNT(*) 
        FROM user_devices 
        WHERE user_id=?
    ");

    $stmt3->bind_param("i", $user_id);
    $stmt3->execute();
    $stmt3->bind_result($device_count);
    $stmt3->fetch();
    $stmt3->close();

    /* 🔥 Dynamic Device Limit Check */
    if ($device_count >= $device_limit) {
        die("Maximum device limit reached.");
    }

    /* Register new device */
    $stmt4 = $conn->prepare("
        INSERT INTO user_devices 
        (user_id, device_token, user_agent, first_ip, last_ip, last_login)
        VALUES (?, ?, ?, ?, ?, NOW())
    ");

    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';

    $stmt4->bind_param(
        "issss",
        $user_id,
        $device_token,
        $user_agent,
        $ip_address,
        $ip_address
    );

    $stmt4->execute();
    $stmt4->close();

} else {

    /* Update existing device login info */
    $stmt5 = $conn->prepare("
        UPDATE user_devices 
        SET last_ip=?, last_login=NOW() 
        WHERE user_id=? AND device_token=?
    ");

    $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
    $stmt5->bind_param("sis", $ip_address, $user_id, $device_token);
    $stmt5->execute();
    $stmt5->close();
}

$stmt2->close();

/* ================================
   4️⃣ Secure Session
================================ */

session_regenerate_id(true);
$_SESSION['user'] = $user_id;

header("Location: course.php");
exit();
?>
