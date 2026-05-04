<?php
session_start();
$conn = new mysqli("localhost", "root", "", "note_db");

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "not logged in"]);
    exit;
}

if (!isset($_POST['note_id'])) {
    echo json_encode(["error" => "missing note_id"]);
    exit;
}

if (!isset($_FILES['images'])) {
    echo json_encode(["error" => "no files"]);
    exit;
}

$note_id = (int)$_POST['note_id'];

foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {

    $name = time() . "_" . $_FILES['images']['name'][$key];

    if (!move_uploaded_file($tmp_name, "uploads/" . $name)) {
        echo json_encode(["error" => "upload failed"]);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO note_images (note_id, image_path) VALUES (?, ?)");
    $stmt->bind_param("is", $note_id, $name);
    $stmt->execute();
}

echo json_encode(["success" => true]);
exit;
