<?php
session_start();
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "note_db");

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

$user_id = $_SESSION['user_id'];

$result = $conn->query("
    SELECT notes.*, users.email AS owner_email, shared_notes.permission
    FROM shared_notes
    JOIN notes ON notes.id = shared_notes.note_id
    JOIN users ON users.id = shared_notes.owner_id
    WHERE shared_notes.receiver_id = $user_id
");

if (!$result) {
    echo json_encode([
        "error" => $conn->error
    ]);
    exit;
}

$data = [];

while ($row = $result->fetch_assoc()) {

    // images
    $imgs = [];
    $imgRes = $conn->query("SELECT image_path FROM note_images WHERE note_id=".$row['id']);

if ($imgRes) {
    while ($i = $imgRes->fetch_assoc()) {
        $imgs[] = $i['image_path'];
    }
}
    $row['images'] = $imgs;

    // labels
    $labels = [];
    $labelRes = $conn->query("
        SELECT labels.name 
        FROM note_labels 
        JOIN labels ON labels.id = note_labels.label_id
        WHERE note_labels.note_id=".$row['id']
    );

    if ($labelRes) {
        while ($l = $labelRes->fetch_assoc()) {
            $labels[] = $l['name'];
        }
    }

    $row['labels'] = implode(",", $labels);

    $labelIds = [];
$idRes = $conn->query("
    SELECT label_id FROM note_labels 
    WHERE note_id=".$row['id']
);

if ($idRes) {
    while ($i = $idRes->fetch_assoc()) {
        $labelIds[] = $i['label_id'];
    }
}

$row['label_ids'] = $labelIds;

    $data[] = $row;
}

echo json_encode($data);