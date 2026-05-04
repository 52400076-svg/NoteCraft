<?php
session_start();
$conn = new mysqli("localhost", "root", "", "note_db");

header('Content-Type: application/json');

// ===== CHECK LOGIN =====
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "not logged in"]);
    exit;
}

$user_id = $_SESSION['user_id'];

// ===== GET DATA =====
$data = json_decode(file_get_contents("php://input"), true);
$offline_id = $data['offline_id'] ?? null;
$note_id = isset($data['note_id']) && $data['note_id'] !== "" ? (int)$data['note_id'] : null;
$title = $data['title'] ?? '';
$content = $data['content'] ?? '';
$labels = $data['labels'] ?? [];

// ===== UPDATE / CREATE =====
if ($note_id) {

    // ===== CHECK OWNER =====
    $check = $conn->query("
        SELECT id FROM notes 
        WHERE id=$note_id AND user_id=$user_id
    ");

    $isOwner = $check && $check->num_rows > 0;

    // ===== NẾU KHÔNG PHẢI OWNER → CHECK SHARE =====
    if (!$isOwner) {

        $checkShared = $conn->query("
            SELECT id FROM shared_notes 
            WHERE note_id=$note_id 
            AND receiver_id=$user_id 
            AND permission='edit'
        ");

        if (!$checkShared || $checkShared->num_rows == 0) {
            echo json_encode([
                "error" => "no permission"
            ]);
            exit;
        }
    }

    // ===== UPDATE NOTE =====
    $stmt = $conn->prepare("
        UPDATE notes 
        SET title=?, content=?, updated_at=NOW() 
        WHERE id=?
    ");
    $stmt->bind_param("ssi", $title, $content, $note_id);
    $stmt->execute();

} else {

    // ===== CHECK DUPLICATE OFFLINE NOTE (FIXED) =====
if (!empty($offline_id)) {

    $check = $conn->prepare("
        SELECT id FROM notes 
        WHERE offline_id = ? AND user_id = ?
        LIMIT 1
    ");

    $check->bind_param("si", $offline_id, $user_id);
    $check->execute();
    $result = $check->get_result();

    if ($row = $result->fetch_assoc()) {

        echo json_encode([
            "id" => $row['id'],
            "success" => true,
            "duplicate" => true
        ]);
        exit;
    }
}

// ===== INSERT NEW NOTE =====
$stmt = $conn->prepare("
    INSERT INTO notes (user_id, title, content, offline_id) 
    VALUES (?, ?, ?, ?)
");
$stmt->bind_param("isss", $user_id, $title, $content, $offline_id);
$stmt->execute();

$note_id = $conn->insert_id;
}

// ===== LABELS (APPLY BOTH CREATE + UPDATE) =====

// xóa label cũ
$stmt = $conn->prepare("DELETE FROM note_labels WHERE note_id=?");
$stmt->bind_param("i", $note_id);
$stmt->execute();

// insert label mới
$stmt = $conn->prepare("INSERT INTO note_labels (note_id, label_id) VALUES (?, ?)");

foreach ($labels as $label_id) {
    $label_id = (int)$label_id;
    $stmt->bind_param("ii", $note_id, $label_id);
    $stmt->execute();
}

// ===== RESPONSE =====
echo json_encode([
    "id" => $note_id,
    "success" => true,
    "offline_id" => $offline_id
]);