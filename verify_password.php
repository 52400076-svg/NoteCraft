<?php
session_start();
$conn = new mysqli("localhost", "root", "", "note_db");

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$note_id = $data['note_id'];
$password = $data['password'];

// lấy password đã hash
$result = $conn->query("SELECT password FROM notes WHERE id=$note_id");
$row = $result->fetch_assoc();

if (!$row || !password_verify($password, $row['password'])) {
    echo json_encode(["success" => false]);
    exit;
}

echo json_encode(["success" => true]);