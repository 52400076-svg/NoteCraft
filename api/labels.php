<?php
session_start();
$conn = new mysqli("localhost", "root", "", "note_db");

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

$user_id = $_SESSION['user_id'];

$result = $conn->query("SELECT * FROM labels WHERE user_id=$user_id");

$labels = [];

while ($row = $result->fetch_assoc()) {
    $labels[] = $row;
}

echo json_encode($labels);