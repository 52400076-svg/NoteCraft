<?php
require_once "includes/db.php";

$code = $_GET['code'];

$sql = "SELECT * FROM users WHERE activation_code='$code'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {

    $user = $result->fetch_assoc();

    $conn->query("
        UPDATE users 
        SET is_activated = 1, activation_code = NULL
        WHERE id = {$user['id']}
    ");
    $_SESSION['is_activated'] = 1;

    echo "Kích hoạt thành công!";
} else {
    echo "Link không hợp lệ!";
}
?>