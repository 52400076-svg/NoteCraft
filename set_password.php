<?php
session_start();
$conn = new mysqli("localhost", "root", "", "note_db");

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "not logged in"]);
    exit;
}

$note_id = (int)$data['note_id'];
$password = $data['password'];

// hash bằng bcrypt
$hash = password_hash($password, PASSWORD_BCRYPT);

$conn->query("UPDATE notes SET password='$hash' WHERE id=$note_id");

echo json_encode(["success" => true]);