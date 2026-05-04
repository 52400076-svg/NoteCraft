<?php
session_start();
$conn = new mysqli("localhost", "root", "", "note_db");

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'];
$name = $data['name'];

$conn->query("UPDATE labels SET name='$name' WHERE id=$id");

echo json_encode(["success" => true]);