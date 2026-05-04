<?php
$conn = new mysqli("localhost", "root", "", "note_db");

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'];

// lấy trạng thái hiện tại
$result = $conn->query("SELECT is_pinned FROM notes WHERE id=$id");
$row = $result->fetch_assoc();

$newPin = $row['is_pinned'] == 1 ? 0 : 1;

// update
$conn->query("
    UPDATE notes 
    SET is_pinned = $newPin,
        pinned_at = IF($newPin=1, NOW(), NULL)
    WHERE id=$id
");

echo json_encode(["success" => true]);