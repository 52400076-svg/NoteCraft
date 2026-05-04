<?php
session_start();
$conn = new mysqli("localhost", "root", "", "note_db");

$data = json_decode(file_get_contents("php://input"), true);

$note_id = $data['note_id'];
$email = $data['email'];
$permission = $data['permission'];
$owner_id = $_SESSION['user_id'];

// check user tồn tại
$result = $conn->query("SELECT id FROM users WHERE email='$email'");
$user = $result->fetch_assoc();

if (!$user) {
    echo json_encode([
        "success" => false,
        "message" => "Email chưa đăng ký!"
    ]);
    exit;
}

$receiver_id = $user['id'];

// tránh share trùng
$check = $conn->query("
    SELECT * FROM shared_notes 
    WHERE note_id=$note_id AND receiver_id=$receiver_id
");

if ($check->num_rows > 0) {
    echo json_encode([
        "success" => false,
        "message" => "Đã share cho người này rồi!"
    ]);
    exit;
}

// lưu DB
$conn->query("
    INSERT INTO shared_notes (note_id, owner_id, receiver_id, permission)
    VALUES ($note_id, $owner_id, $receiver_id, '$permission')
");

echo json_encode(["success" => true]);