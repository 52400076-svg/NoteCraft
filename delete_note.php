<?php
$conn = new mysqli("localhost", "root", "", "note_db");

$data = json_decode(file_get_contents("php://input"), true);
$id = (int)$data['id'];

// ==============================
// 🧹 1. XÓA ẢNH TRONG DB
// ==============================
$result = $conn->query("SELECT image_path FROM note_images WHERE note_id=$id");

while ($row = $result->fetch_assoc()) {
    $file = "uploads/" . $row['image_path'];

    if (file_exists($file)) {
        unlink($file); // xóa file vật lý
    }
}

// xóa record ảnh
$conn->query("DELETE FROM note_images WHERE note_id=$id");

// ==============================
// 🗑️ 2. XÓA NOTE
// ==============================
$conn->query("DELETE FROM notes WHERE id=$id");

echo json_encode([
    "success" => true,
    "message" => "Deleted successfully"
]);