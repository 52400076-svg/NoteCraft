<?php
session_start();
$conn = new mysqli("localhost", "root", "", "note_db");

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'];

$conn->query("DELETE FROM labels WHERE id=$id");

echo json_encode(["success" => true]);