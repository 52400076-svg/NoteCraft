<?php
session_start();
$conn = new mysqli("localhost", "root", "", "note_db");

header('Content-Type: application/json');

$search = $_GET['search'] ?? '';
$label_id = $_GET['label_id'] ?? null;
$user_id = $_SESSION['user_id'];

// ==============================
// QUERY NOTE + LABEL + LABEL_ID
// ==============================
$sql = "
SELECT 
    n.*, 
    GROUP_CONCAT(DISTINCT l.name) AS labels,
    GROUP_CONCAT(DISTINCT l.id) AS label_ids
FROM notes n
LEFT JOIN note_labels nl ON n.id = nl.note_id
LEFT JOIN labels l ON nl.label_id = l.id
WHERE n.user_id = $user_id
";

if ($search != "") {
    $sql .= " AND (n.title LIKE '%$search%' OR n.content LIKE '%$search%')";
}
// FILTER THEO LABEL
if ($label_id) {
    $sql .= " AND n.id IN (
        SELECT note_id FROM note_labels WHERE label_id = $label_id
    )";
}

$sql .= "
GROUP BY n.id
ORDER BY 
    n.is_pinned DESC, 
    n.pinned_at DESC, 
    n.updated_at DESC
";

$result = $conn->query($sql);

$notes = [];

while ($row = $result->fetch_assoc()) {

    // ===== LẤY ẢNH =====
    $images = [];

    $img_result = $conn->query("
        SELECT image_path 
        FROM note_images 
        WHERE note_id = " . $row['id']
    );

    while ($img = $img_result->fetch_assoc()) {
        $images[] = $img['image_path'];
    }

    $row['images'] = $images;

    // ===== QUAN TRỌNG: CHUYỂN label_ids thành array =====
    $row['label_ids'] = $row['label_ids'] 
        ? explode(",", $row['label_ids']) 
        : [];

    $notes[] = $row;
}

echo json_encode($notes);