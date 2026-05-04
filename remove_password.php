<?php
session_start();
$conn = new mysqli("localhost", "root", "", "note_db");

$data = json_decode(file_get_contents("php://input"), true);
$note_id = $data['note_id'];

// set password = NULL
$conn->query("UPDATE notes SET password=NULL WHERE id=$note_id");

echo json_encode(["success" => true]);