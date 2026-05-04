<?php
session_start();
$conn = new mysqli("localhost", "root", "", "note_db");

$data = json_decode(file_get_contents("php://input"), true);

$note_id = $data['note_id'];
$old = $data['old_password'];
$new = $data['new_password'];

$result = $conn->query("SELECT password FROM notes WHERE id=$note_id");
$row = $result->fetch_assoc();

if (!password_verify($old, $row['password'])) {
    echo json_encode(["success" => false]);
    exit;
}

$hash = password_hash($new, PASSWORD_BCRYPT);

$conn->query("UPDATE notes SET password='$hash' WHERE id=$note_id");

echo json_encode(["success" => true]);