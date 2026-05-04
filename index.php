<?php
session_start();
require_once "includes/auth_check.php";
require_once "includes/db.php";
$user_id = $_SESSION['user_id'];
$user = $conn->query("SELECT * FROM users WHERE id=$user_id")->fetch_assoc();
$font_size = $_SESSION['font_size'] ?? $user['font_size'] ?? 14;
$theme = $_SESSION['theme'] ?? $user['theme'] ?? 'light';
$noteColor = $_SESSION['note_color'] ?? $user['note_color'] ?? 'default';
?>


<!DOCTYPE html>
<html>
<head>
    <title>NoteCraft - Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#6366f1">
     <style>
:root {
    /* LIGHT - MODERN DASHBOARD */
    --bg: #f3f4f6;
    --surface: #ffffff;
    --surface-2: #f9fafb;

    --text: #0f172a;
    --muted: #6b7280;

    --border:rgb(201, 207, 219);

    --primary: #4f46e5;
    --primary-soft: rgba(79,70,229,0.1);

    --shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
    --shadow-md: 0 8px 20px rgba(0,0,0,0.08);

    --radius: 14px;
}

/* DARK - MODERN PRODUCT STYLE */
body.dark {
    /* BACKGROUND sâu hơn */
    --bg: #070b14;

    /* SURFACE tách lớp rõ ràng */
    --surface: #111a2e;     /* card chính */
    --surface-2: #0b1224;   /* input / inner box */

    /* TEXT tăng độ sáng */
    --text: #f1f5f9;        /* trắng rõ */
    --muted: #aab4c3;       /* xám sáng hơn */

    /* BORDER rõ hơn để không bị “dính nền” */
    --border: rgba(209, 219, 233, 0.35);

    /* PRIMARY giữ ổn */
    --primary: #6366f1;
    --primary-soft: rgba(99,102,241,0.18);

    /* SHADOW tạo chiều sâu rõ */
    --shadow-sm: 0 2px 8px rgba(0,0,0,0.35);
    --shadow-md: 0 12px 35px rgba(0,0,0,0.55);
}

/* GLOBAL */
body {
    background: var(--bg);
    color: var(--text);
    font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI;
}

/* DASHBOARD FEEL */
.container {
    max-width: 1100px;
}

/* SECTION CARD (IMPORTANT DIFFERENCE LEVELS) */
.card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow-sm);
    padding: 14px;
    transition: all 0.2s ease;
}

/* chỉ apply hover cho NOTE CARD */
#noteList .card:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
}

/* các card khác KHÔNG hover */
.card:hover {
    box-shadow: var(--shadow-sm);
    transform: none;
}

/* INPUT MODERN */
input, textarea, select {
    background: var(--surface-2);
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 10px 12px;
    color: var(--text);
    outline: none;
}

input:focus, textarea:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px var(--primary-soft);
}

/* BUTTON PRIMARY (SaaS style) */
.btn-primary {
    background: var(--primary);
    border: none;
    border-radius: 10px;
    font-weight: 500;
}

.btn-primary:hover {
    filter: brightness(1.05);
}

/* SECONDARY BUTTON */
.btn {
    border-radius: 10px !important;
}

/* BADGE CLEAN */
.badge {
    border-radius: 999px;
    font-weight: 500;
}

/* NOTE GRID IMPROVEMENT */
#noteList .card {
    cursor: pointer;
}

/* TOP PROFILE BUTTON */
a[href="profile.php"] {
    position: fixed;
    top: 14px;
    right: 14px;
    background: var(--surface);
    border: 1px solid var(--border);
    color: var(--text);
    padding: 10px 14px;
    border-radius: 12px;
    box-shadow: var(--shadow-sm);
}

/* BUTTON GROUP SPACING FEEL */
button {
    font-weight: 500;
}

/* TEXT */
h1 {
    font-weight: 700;
    letter-spacing: -0.4px;
}

/* SMOOTH SYSTEM */
* {
    transition: all 0.15s ease;
}

/* INPUT - DARK MODE TONE LẠNH */
body.dark input,
body.dark textarea,
body.dark select {
    background: linear-gradient(145deg, #0b1224, #0f172a) !important;
    border: 1px solid rgba(148,163,184,0.25) !important;
    color: #f1f5f9 !important;
}

/* PLACEHOLDER - TRẮNG NHƯNG KHÔNG GẮT */
body.dark input::placeholder,
body.dark textarea::placeholder {
    color: rgba(255,255,255,0.65) !important;
}

/* FOCUS - GLOW NHẸ (rất quan trọng để “hiện đại”) */
body.dark input:focus,
body.dark textarea:focus {
    border-color: #6366f1 !important;
    box-shadow: 0 0 0 3px rgba(99,102,241,0.25);
    background: #0b1224 !important;
}

body.dark .card {
    background: linear-gradient(160deg, #111a2e, #0f172a);
    border: 1px solid rgba(148,163,184,0.15);
}

body.dark #labelSelect label {
    background: linear-gradient(145deg, #0f172a, #111a2e);
    padding: 8px 12px;
    border-radius: 10px;

    border: 1px solid rgba(148,163,184,0.35); /* tăng độ rõ */
    color: #e2e8f0;

    display: inline-flex;
    align-items: center;
    gap: 6px;

    cursor: pointer;
}

/* ===== FIX TEXT BỊ CHÌM TRONG DARK MODE ===== */

/* title, content trong note */
body.dark .card h5,
body.dark .card p {
    color: #f1f5f9 !important;
}

/* label "Labels" */
body.dark .card h5,
body.dark h5 {
    color: #ffffff !important;
}

/* text phụ (shared by...) */
body.dark .card small {
    color: #cbd5e1 !important;
}

/* badge label cho dễ nhìn hơn */
body.dark .badge.bg-info {
    background: #1e293b !important;
    color: #e2e8f0 !important;
}

/* label section title rõ hơn */
body.dark .card h5 {
    font-weight: 600;
    letter-spacing: 0.3px;
}

/* ACTION BUTTONS (NOTE CARD) */
.note-actions {
    display: flex;
    gap: 6px;
    margin-bottom: 6px;
}

/* base */
.note-actions button {
    border: none;
    background: var(--surface-2);
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 6px 8px;
    font-size: 14px;
    cursor: pointer;

    display: flex;
    align-items: center;
    justify-content: center;

    transition: all 0.2s ease;
}

/* hover chung */
/* ACTION BUTTONS (CENTER + BIGGER + CLEAN) */
.note-actions {
    display: flex;
    justify-content: center;   /* 👉 nằm giữa */
    gap: 10px;
    margin-bottom: 10px;
}

/* BUTTON STYLE */
.note-actions button {
    border: none;
    background: var(--surface-2);
    border: 1px solid var(--border);
    border-radius: 12px;

    width: 40px;     /* 👉 rộng ra */
    height: 40px;    /* 👉 cao đều */

    font-size: 16px;
    cursor: pointer;

    display: flex;
    align-items: center;
    justify-content: center;

    transition: all 0.2s ease;
}

/* HOVER - nổi lên rõ hơn */
.note-actions button:hover {
    transform: translateY(-3px) scale(1.05);
    box-shadow: var(--shadow-md);
}

/* ACTIVE (click feel thật hơn) */
.note-actions button:active {
    transform: scale(0.95);
}

/* từng loại nút (color subtle) */
.btn-pin:hover {
    background: rgba(250, 204, 21, 0.15);
    border-color: #facc15;
}

.btn-delete:hover {
    background: rgba(239, 68, 68, 0.15);
    border-color: #ef4444;
}

.btn-lock:hover {
    background: rgba(99, 102, 241, 0.15);
    border-color: #6366f1;
}

.btn-share:hover {
    background: rgba(34, 197, 94, 0.15);
    border-color: #22c55e;
}

body.dark .note-actions button {
    background: #0f172a; /* sáng hơn chút */
    border: 1px solid rgba(186, 196, 210, 0.35); /* rõ hơn */
    color: #e2e8f0;
}

body.dark .note-actions button:hover {
    box-shadow: 0 0 0 2px rgba(255,255,255,0.05),
                0 8px 25px rgba(0,0,0,0.6);
}

/* ===== DARK MODE HOVER FIX ===== */
body.dark .btn-pin:hover {
    background: rgba(250, 204, 21, 0.25);
    border-color: #facc15;
    color: #facc15;
}

body.dark .btn-delete:hover {
    background: rgba(239, 68, 68, 0.25);
    border-color: #ef4444;
    color: #ef4444;
}

body.dark .btn-lock:hover {
    background: rgba(99, 102, 241, 0.25);
    border-color: #6366f1;
    color: #6366f1;
}

body.dark .btn-share:hover {
    background: rgba(34, 197, 94, 0.25);
    border-color: #22c55e;
    color: #22c55e;
}

.btn-outline-danger {
    border-radius: 10px;
    font-weight: 500;
}

/* ===== PREFERENCES BUTTON (GIỐNG LOGOUT NHƯNG MÀU XÁM) ===== */
.btn-pref {
    background: transparent;
    border: 1px solid var(--border);
    color: var(--text);
    border-radius: 10px;
    font-weight: 500;
}

/* hover giống logout nhưng neutral */
.btn-pref:hover {
    background: rgba(68, 74, 84, 0.12); /* xám nhẹ */
    border-color: #6b7280;
    color: #374151;
}


/* ===== DARK MODE FIX ===== */
body.dark .btn-pref {
    background: #0f172a;
    border: 1px solid rgba(136, 146, 157, 0.4);
    color: #e2e8f0;
}

body.dark .btn-pref:hover {
    background: rgba(148,163,184,0.15);
    border-color: #94a3b8;
    color: #f1f5f9;
}

/* ===== PROFILE BUTTON (TOP RIGHT - MODERN) ===== */
.btn-profile {
    position: fixed;
    top: 14px;
    right: 14px;

    background: var(--surface);
    border: 1px solid var(--border);
    color: var(--text);

    padding: 10px 14px;
    border-radius: 12px;

    font-weight: 500;
    text-decoration: none;

    box-shadow: var(--shadow-sm);
    transition: all 0.2s ease;
    z-index: 999;
}

/* HOVER - nổi + rõ viền */
/* ===== PROFILE BUTTON (LIGHT MODE FIX) ===== */
.btn-profile:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);

    
    border-color: #9ca3af; /* xám đậm hơn */
    background: #f9fafb;   /* nhẹ hơn surface */
    color: var(--text);
}

/* CLICK feel */
.btn-profile:active {
    transform: scale(0.96);
}

/* ===== DARK MODE ===== */
body.dark .btn-profile {
    background: #0f172a;
    border: 1px solid rgba(148,163,184,0.35);
    color: #e2e8f0;
}

body.dark .btn-profile:hover {
    border-color: #6366f1;
    color: #818cf8;
    box-shadow: 0 0 0 2px rgba(255,255,255,0.05),
                0 8px 25px rgba(0,0,0,0.6);
}

/* ===== NOTE COLOR SYSTEM ===== */
.note-color-default { background: var(--surface); }
.note-color-purple { background: #eef2ff; }
.note-color-blue { background: #eff6ff; }
.note-color-green { background: #ecfdf5; }
.note-color-yellow { background: #fefce8; }
.note-color-pink { background: #fdf2f8; }

/* DARK MODE AUTO FIX */
body.dark .note-color-purple { background: #1e1b4b; }
body.dark .note-color-blue { background: #0c4a6e; }
body.dark .note-color-green { background: #064e3b; }
body.dark .note-color-yellow { background: #713f12; }
body.dark .note-color-pink { background: #831843; }

html {
    font-size: <?= $font_size ?>px;
}
</style>
</head>
<body style="font-size: <?= $font_size ?>px;"
      class="<?= $theme == 'dark' ? 'dark' : '' ?>">
      <!-- PROFILE BUTTON (TOP RIGHT FIXED) -->
<?php
$avatarPath = "uploads/" . ($user['avatar'] ?: "default.png");
$version = file_exists($avatarPath) ? filemtime($avatarPath) : time();
?>

<a href="profile.php" class="btn-profile d-flex align-items-center gap-2">
    <img src="<?= $avatarPath ?>?v=<?= $version ?>"
         style="width:36px;height:36px;border-radius:50%;object-fit:cover;">
    <?= htmlspecialchars($user['display_name']) ?>
</a>

<style>
a[href="profile.php"]:hover {
}
</style>

<div class="container mt-4">

<!-- BANNER KÍCH HOẠT -->
<?php if (isset($_SESSION['is_activated']) && $_SESSION['is_activated'] == 0): ?>
    <div style="
        background: #ffcc00;
        color: #000;
        padding: 15px;
        text-align: center;
        font-weight: bold;
        border-radius: 5px;
        margin-bottom: 20px;
    ">
        ⚠️ Tài khoản của bạn chưa được kích hoạt! 
        Vui lòng kiểm tra email để xác minh.
    </div>
<?php endif; ?>

<h1>Welcome to NoteCraft</h1>


<div class="d-flex gap-2 align-items-center">
    <a href="logout.php" class="btn btn-outline-danger btn-sm">
    Logout
</a>

    <a href="preferences.php" class="btn btn-pref btn-sm">
        ⚙️ Preferences
    </a>
</div>
<hr>

<input type="text" id="search" class="form-control mb-3" placeholder="🔍 Search notes...">

<!-- Nút chuyển Grid/List -->
<button id="toggleView" class="btn btn-primary mb-3">Switch Grid/List</button>

<!-- tạo nút xem note được share (Shared with me) và nút quay lại note
 của mình (My Notes) -->
<button onclick="loadShared()" class="btn btn-success mb-3">📥 Shared with me</button>
<button onclick="loadNotes()" class="btn btn-secondary mb-3">📄 My Notes</button>

<!-- LABEL SECTION -->
<div class="card p-3 mb-3">
    <h5>Labels</h5>

    <div class="d-flex gap-2">
        <input type="text" id="labelName" class="form-control" placeholder="New label">
        <button class="btn btn-primary" onclick="addLabel()">Add</button>
    </div>

    <div id="labelList" class="mt-3 d-flex flex-wrap gap-2"></div>
</div>

<!-- Form Note -->
<div class="card p-3 mb-4">
    <input type="hidden" id="note_id">

    <input type="text" id="title" class="form-control mb-2" placeholder="Title">
    <textarea id="content" class="form-control" placeholder="Content"></textarea>
    <div id="labelSelect" class="mt-2"></div>
    <!-- nút chọn ảnh -->
    <button type="button" class="btn btn-outline-secondary mt-2" onclick="document.getElementById('images').click()">
    📷 Thêm ảnh
    </button>
    <small id="imageCount" class="text-muted"></small>

<!-- input file ẩn -->
<input type="file" id="images" multiple accept="image/*" style="display:none;">
</div>

<!-- Danh sách note -->
<div id="noteList" class="row row-cols-2 row-cols-lg-3 g-3">
    <!-- Notes sẽ render ở đây -->
</div>
</div>

<script src="https://cdn.socket.io/4.5.4/socket.io.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    let pendingActions = [];
    let currentOfflineId = null;

    let currentView = "my";

    const socket = io("http://localhost:3000");

    socket.on("connect", () => {
        console.log("Connected:", socket.id);
    });

    socket.on("connect_error", err => {
        console.error("Socket error:", err.message);
    });

    let selectedImages = [];
    let searchTimeout = null;

    // ===== GRID / LIST =====
    let isGrid = true;

    document.getElementById("toggleView").onclick = () => {
        const list = document.getElementById("noteList");

        if (isGrid) {
    // chuyển sang LIST (1 cột)
    list.classList.remove("row-cols-2", "row-cols-lg-3");
    list.classList.add("row-cols-1");
} else {
    // chuyển lại GRID
    list.classList.remove("row-cols-1");
    list.classList.add("row-cols-2", "row-cols-lg-3");
}

        isGrid = !isGrid;
    };

    // ===== AUTO SAVE (DEBOUNCE) =====
    let timeout = null;

    function autoSave() {
        clearTimeout(timeout);

        timeout = setTimeout(() => {
            saveNote(); // gọi hàm lưu (chưa viết ở bước này)
        }, 1000); // 1 giây
    }

    // GẮN SỰ KIỆN VÀO INPUT
    document.getElementById("title").addEventListener("input", function () {
    autoSave();

    const noteId = document.getElementById("note_id").value;
    if (!noteId) return;

    socket.emit("edit-note", {
        noteId: noteId,
        content: document.getElementById("content").value,
        title: this.value
    });
});
    document.getElementById("content").addEventListener("input", autoSave);
    document.getElementById("content").addEventListener("input", function () {
    const noteId = document.getElementById("note_id").value;

    
    if (!noteId) return;

    socket.emit("edit-note", {
        noteId: noteId,
        content: this.value,
        title: document.getElementById("title").value
    });

});
    document.getElementById("search").addEventListener("input", function () {
        clearTimeout(searchTimeout);

        searchTimeout = setTimeout(() => {
            loadNotes(this.value);
        }, 300);
    });
    document.getElementById("images").addEventListener("change", function () {

        for (let i = 0; i < this.files.length; i++) {
            selectedImages.push(this.files[i]);
        }

        document.getElementById("imageCount").innerText =
            selectedImages.length > 0
            ? `📸 Đã chọn ${selectedImages.length} ảnh`
            : "";
        });

    function saveNoteOffline(note) {
    let pending = JSON.parse(localStorage.getItem("pending") || "[]");

    // tránh duplicate theo offline_id
    if (pending.find(n => n.offline_id === note.offline_id)) {
        return;
    }

    pending.push(note);

    localStorage.setItem("pending", JSON.stringify(pending));
}

    function saveNote() {
    const note_id = document.getElementById("note_id").value;
    const title = document.getElementById("title").value;
    const content = document.getElementById("content").value;

    if (!title && !content) return;

    let selectedLabels = [];

    document.querySelectorAll("#labelSelect input:checked").forEach(cb => {
        selectedLabels.push(cb.value);
    });

    if (!currentOfflineId) {
    currentOfflineId = document.getElementById("note_id").value 
        ? document.getElementById("note_id").value 
        : "temp_" + Date.now();
}

    const noteData = {
    note_id,
    offline_id: note_id ? null : currentOfflineId,
    title,
    content,
    labels: selectedLabels
};

    // =========================
    // OFFLINE MODE
    // =========================
    if (!navigator.onLine) {

        saveNoteOffline(noteData);

        console.log("Offline → lưu tạm");

        return;
    }

    // =========================
    // ONLINE MODE (GIỮ NGUYÊN fetch)
    // =========================
    fetch("save_note.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(noteData)
    })
        .then(res => res.json())
        .then(data => {

    console.log("SAVE RESPONSE:", data);

    if (data.duplicate) {
        console.log("Duplicate detected → ignore");
        document.getElementById("note_id").value = data.id;
        currentOfflineId = null;
        return;
    }

    const noteId = data.id;

    if (!noteId) {
        console.error("NO NOTE ID RETURNED!");
        return;
    }

    document.getElementById("note_id").value = noteId;
    currentOfflineId = null;
            
            if (selectedImages.length > 0) {
        
                const formData = new FormData();
                selectedImages.forEach(file => {
                    formData.append("images[]", file);
                });
                
                formData.append("note_id", noteId);
                
                fetch("upload_image.php", {
                    method: "POST",
                    body: formData
                })
                
                .then(res => res.json())
                .then(res => console.log("UPLOAD RESPONSE:", res));
                
                // reset sau khi upload
                selectedImages = [];
                document.getElementById("imageCount").innerText = "";
                document.getElementById("images").value = "";
            }
            if (currentView === "my") {
    loadNotes();
} else {
    loadShared();
}
        })
        .catch(err => console.error(err));
    }
    
    function renderNotes(data) {
        const list = document.getElementById("noteList");
        list.innerHTML = "";
        
        data.forEach(note => {
            let pinIcon = note.is_pinned == 1
                ? `<span class="badge bg-warning text-dark">PIN</span>`
                : "";
                
            const col = document.createElement("div");
            col.className = "col";

            const card = document.createElement("div");
            const noteColor = "note-color-<?= $noteColor ?>";

            card.className = note.is_pinned == 1
                ? `card p-2 border-warning shadow-sm ${noteColor}`
                : `card p-2 ${noteColor}`;
                card.setAttribute("data-note-id", note.id);

            let imgs = "";
            if (note.images) {
                note.images.forEach(img => {
                    imgs += `<img src="uploads/<?= $user['avatar'] ?>?v=<?= time() ?>">`;
                });
            }

            let lockIcon = note.password ? "🔒" : "";
            let shareIcon = note.owner_email ? "👥" : "";

            let labelHtml = "";

            if (note.labels) {
                note.labels.split(",").forEach(l => {
                    labelHtml += `<span class="badge bg-info text-dark me-1">${l}</span>`;
                });
            }

            card.innerHTML = `
                <div class="note-actions">
    <button class="btn-pin" onclick="togglePin(${note.id}); event.stopPropagation();" title="Pin">
        📌
    </button>

    <button class="btn-delete" onclick="deleteNote(${note.id}, ${note.password ? true : false}); event.stopPropagation();" title="Delete">
        🗑️
    </button>

    <button class="btn-lock" onclick="handlePassword(${note.id}, ${note.password ? true : false}); event.stopPropagation();" title="Password">
        🔑
    </button>

    <button class="btn-share" onclick="shareNote(${note.id}); event.stopPropagation();" title="Share">
        📤
    </button>
</div>

                <h5 class="d-flex align-items-center gap-2">
                    ${pinIcon}
                    <span>${note.title}</span>
                    <span>${lockIcon}</span>
                    <span>${shareIcon}</span>
                </h5>

                <p>${note.content}</p>

                ${note.owner_email ? 
                    `<small class="text-muted">
                        Shared by: ${note.owner_email} (${note.permission})
                    </small>` 
                : ""}

                <div>${labelHtml}</div>

                <div>${imgs}</div>
            `;

            card.onclick = function (e) {
                if (e.target.tagName !== "BUTTON") {
                    editNote(note);
                }
            };

            col.appendChild(card);
            list.appendChild(col);
        });
    }

    window.loadNotes = function(keyword = "", labelId = null) {
    currentView = "my";

    let url = "get_notes.php?search=" + encodeURIComponent(keyword);

    if (labelId) {
        url = "get_notes.php?label_id=" + labelId;
    }

    // =========================
    // ONLINE MODE
    // =========================
    if (navigator.onLine) {

        fetch(url)
            .then(res => res.json())
            .then(data => {

                renderNotes(data);

                // 💾 LƯU OFFLINE (IndexedDB)
                saveNotesOffline(data);

            })
            .catch(err => {
                console.log("Online fetch error → fallback offline", err);

                getOfflineNotes(notes => {
                    renderNotes(notes);
                });
            });

    } 
    // =========================
    // OFFLINE MODE
    // =========================
    else {

        getOfflineNotes(notes => {
            renderNotes(notes);
        });

    }
}

    function saveNotesOffline(notes) {

    let request = indexedDB.open("noteDB", 1);

    request.onupgradeneeded = function (event) {
        let db = event.target.result;
        db.createObjectStore("notes", { keyPath: "id" });
    };

    request.onsuccess = function (event) {
        let db = event.target.result;
        let tx = db.transaction("notes", "readwrite");
        let store = tx.objectStore("notes");

        notes.forEach(note => {
            store.put(note);
        });
    };
}

    function getOfflineNotes(callback) {

    let request = indexedDB.open("noteDB", 1);

    request.onsuccess = function (event) {
        let db = event.target.result;
        let tx = db.transaction("notes", "readonly");
        let store = tx.objectStore("notes");

        let getAll = store.getAll();

        getAll.onsuccess = function () {
            callback(getAll.result || []);
        };
    };
}



    window.editNote = function(note) {

    // nếu có password → bắt nhập
        if (note.password) {
            const inputPass = prompt("Nhập mật khẩu:");

            if (!inputPass) return;

            fetch("verify_password.php", {
                method: "POST",
                headers: {"Content-Type": "application/json"},
                body: JSON.stringify({
                    note_id: note.id,
                    password: inputPass
                })
            })
            .then(res => res.json())
            .then(res => {
                if (!res.success) {
                    alert("Sai mật khẩu!");
                    return;
                }

                openNote(note); // đúng thì mở
            });

        } else {
            openNote(note); // không có password thì mở luôn
        }
    }

    function openNote(note) {
    document.getElementById("note_id").value = note.id;
    document.getElementById("title").value = note.title;
    document.getElementById("content").value = note.content;

    // 🔒 FIX CHUẨN: phân quyền
    if (note.owner_email) {
        // note được share
        if (note.permission === "read") {
            document.getElementById("title").disabled = true;
            document.getElementById("content").disabled = true;
        } else {
            document.getElementById("title").disabled = false;
            document.getElementById("content").disabled = false;
        }
    } else {
        // note của mình → luôn edit được
        document.getElementById("title").disabled = false;
        document.getElementById("content").disabled = false;
    }

    // JOIN ROOM REALTIME
    socket.emit("join-note", note.id);

    document.querySelectorAll("#labelSelect input").forEach(cb => {
        cb.checked = false;
    });

    note.label_ids.forEach(id => {
        const cb = document.querySelector(`#labelSelect input[value="${id}"]`);
        if (cb) cb.checked = true;
    });
}
 

    function loadLabels() {
        fetch("api/labels.php")
        .then(res => res.json())
        .then(data => {
            let html = "";
            
            data.forEach(l => {
                html += `
                    <span class="badge bg-secondary me-2 mb-2"
                          style="cursor:pointer"
                          onclick="filterByLabel(${l.id})">
                        ${l.name}

                        <button onclick="editLabel(${l.id}, '${l.name}'); event.stopPropagation();"
                            style="border:none;background:transparent;color:white;margin-left:5px;">✏️</button>

                        <button onclick="deleteLabel(${l.id}); event.stopPropagation();"
                            style="border:none;background:transparent;color:white;margin-left:5px;">x</button>
                    </span>
                `;
            });

            document.getElementById("labelList").innerHTML = html;
        });
    }

    window.addLabel = function () {
        const name = document.getElementById("labelName").value;
        
        if (!name) return;
        
        fetch("api/add_label.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ name })
        })
        .then(() => {
            document.getElementById("labelName").value = "";
            loadLabels();            
            loadLabelCheckbox();     
        });
    }

    window.editLabel = function (id, oldName) {
        const newName = prompt("Đổi tên label:", oldName);
        
        if (!newName || newName === oldName) return;

        fetch("api/update_label.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ id, name: newName })
        })
        .then(() => {
            loadLabels();
            loadLabelCheckbox();
        });
    }

    window.deleteLabel = function (id) {
        fetch("api/delete_label.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ id })
        })
        .then(() => loadLabels());
    }

    window.filterByLabel = function (labelId) {
        fetch("get_notes.php?label_id=" + labelId)
        .then(res => res.json())
        .then(data => {
            renderNotes(data); // tách render ra để tái sử dụng
        });
    }
    

    function loadLabelCheckbox() {
        fetch("api/labels.php")
        .then(res => res.json())
        .then(data => {
            let html = "";

            data.forEach(l => {
                html += `
                    <label class="me-2">
                        <input type="checkbox" value="${l.id}">
                        ${l.name}
                    </label>
                `;
            });

            document.getElementById("labelSelect").innerHTML = html;
        });
    }
    loadLabels();
    loadLabelCheckbox();
    loadNotes();

    window.loadShared = function() {
    currentView = "shared";

    fetch("get_shared_notes.php")
    .then(res => res.json())
    .then(data => {
        renderNotes(data);
    });
}

    socket.on("update-note", data => {

    const currentId = document.getElementById("note_id").value;

    // 1. update form nếu đang mở note đó
    if (currentId == data.noteId) {
        document.getElementById("content").value = data.content;
        document.getElementById("title").value = data.title;
    }

    // 2. update card trong list (CHỈ 1 NOTE)
    const card = document.querySelector(`[data-note-id='${data.noteId}']`);

    if (card) {
        const titleEl = card.querySelector("h5 span");
        const contentEl = card.querySelector("p");

        if (titleEl) titleEl.innerText = data.title;
        if (contentEl) contentEl.innerText = data.content;
    }
});

async function syncPending() {
    let pending = JSON.parse(localStorage.getItem("pending") || "[]");

    if (pending.length === 0) return;

    let newPending = [];

    for (let note of pending) {
        try {
            let res = await fetch("save_note.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(note)
            });

            let data = await res.json();

            // 1. duplicate → đã tồn tại trên server → bỏ luôn
            if (data.duplicate) {
                console.log("Duplicate → remove:", note);
                continue;
            }

            // 2. success → sync OK → không giữ lại
            if (data.id) {
                continue;
            }

            // 3. lỗi thật → giữ lại để retry
            newPending.push(note);

        } catch (err) {
            console.log("Sync error:", err);
            newPending.push(note);
        }
    }

    localStorage.setItem("pending", JSON.stringify(newPending));
}

// =========================
// AUTO SYNC WHEN BACK ONLINE
// =========================
window.addEventListener("online", async () => {
    console.log("Back online");

    await syncPending();

    fetch("get_notes.php")
        .then(res => res.json())
        .then(data => {
            renderNotes(data);
            saveNotesOffline(data);
        })
        .catch(err => console.error(err));
});

});

    window.togglePin = function (id) {
        fetch("pin_note.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ id })
        })
        .then(res => res.json())
        .then(() => {
            window.loadNotes();
        });
    }
    
    window.deleteNote = function(id, hasPassword) {

    if (hasPassword) {
        const pass = prompt("Nhập mật khẩu để xóa:");

        if (!pass) return;

        fetch("verify_password.php", {
            method: "POST",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify({ note_id: id, password: pass })
        })
        .then(res => res.json())
        .then(res => {
            if (!res.success) {
                alert("Sai mật khẩu!");
                return;
            }

            deleteNow(id); // đúng thì xóa
        });

    } else {
        deleteNow(id); // không có pass thì xóa luôn
    }
}
    function deleteNow(id) {
        if (!confirm("Bạn có chắc muốn xóa không?")) return;

        fetch("delete_note.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ id })
        })
        .then(res => res.json())
        .then(data => {
            console.log(data);
            loadNotes();
        });
    }

    window.setPassword = function(noteId) {
        const pass = prompt("Nhập mật khẩu:");
        const confirmPass = prompt("Xác nhận mật khẩu:");

        if (!pass || pass !== confirmPass) {
            alert("Mật khẩu không khớp!");
            return;
        }

        fetch("set_password.php", {
            method: "POST",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify({
                note_id: noteId,
                password: pass
            })
        })
        .then(res => res.json())
        .then(() => {
            alert("Đã đặt mật khẩu!");
            loadNotes();
        });
    }

    window.handlePassword = function(noteId, hasPassword) {

        // chưa có password → set mới
        if (!hasPassword) {
            setPassword(noteId);
            return;
        }

        // đã có password → chọn hành động
        const action = prompt("1: Đổi mật khẩu\n2: Xóa mật khẩu");

        if (action == "1") {
            changePassword(noteId);
        } else if (action == "2") {
            removePassword(noteId);
        }
    }

    window.shareNote = function(noteId) {
        const email = prompt("Nhập email người nhận:");
        if (!email) return;

        const permission = prompt("Quyền: read / edit");
        if (!permission) return;

        fetch("share_note.php", {
            method: "POST",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify({
                note_id: noteId,
                email: email,
                permission: permission
            })
        })
        .then(res => res.json())
        .then(res => {
            if (!res.success) {
                alert(res.message);
                return;
            }

            alert("Đã chia sẻ!");
        });
    }

    
    function changePassword(noteId) {
        const oldPass = prompt("Nhập mật khẩu cũ:");
        if (!oldPass) return;

        const newPass = prompt("Nhập mật khẩu mới:");
        const confirmPass = prompt("Xác nhận mật khẩu mới:");

        if (!newPass || newPass !== confirmPass) {
            alert("Mật khẩu không khớp!");
            return;
        }
        
        fetch("update_password.php", {
            method: "POST",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify({
                note_id: noteId,
                old_password: oldPass,
                new_password: newPass
            })
        })
        .then(res => res.json())
        .then(res => {
            if (!res.success) {
                alert("Sai mật khẩu cũ!");
                return;
            }

            alert("Đổi mật khẩu thành công!");
            loadNotes();
        });
    }

    function removePassword(noteId) {
        const pass = prompt("Nhập mật khẩu hiện tại để xóa:");
        if (!pass) return;

        fetch("verify_password.php", {
            method: "POST",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify({
                note_id: noteId,
                password: pass
            })
        })
        .then(res => res.json())
        .then(res => {
            if (!res.success) {
                alert("Sai mật khẩu!");
                return;
            }

            // gọi API xóa password
            fetch("remove_password.php", {
                method: "POST",
                headers: {"Content-Type": "application/json"},
                body: JSON.stringify({ note_id: noteId })
            })
            .then(res => res.json())
            .then(() => {
                alert("Đã xóa mật khẩu!");
                loadNotes();
            });
        });
    }

</script>
<script>
if ("serviceWorker" in navigator) {
    window.addEventListener("load", function () {
        navigator.serviceWorker.register("sw.js")
            .then(function (registration) {
                console.log("✅ Service Worker registered:", registration.scope);
            })
            .catch(function (error) {
                console.log("❌ Service Worker failed:", error);
            });
    });
}
</script>

</body>
</html>