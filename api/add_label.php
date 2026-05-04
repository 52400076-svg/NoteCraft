<?php
session_start();
$conn = new mysqli("localhost", "root", "", "note_db");

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "not logged in"]);
    exit;
}

$name = $data['name'];
$user_id = $_SESSION['user_id'];

$conn->query("INSERT INTO labels (user_id, name) VALUES ($user_id, '$name')");

echo json_encode(["success" => true]);