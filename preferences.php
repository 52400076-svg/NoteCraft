<?php
session_start();
require_once "includes/db.php";
require_once "includes/auth_check.php";

$user_id = $_SESSION['user_id'];

if (isset($_POST['save_pref'])) {
    $theme = $_POST['theme'];
    $font = $_POST['font_size'];
    $color = $_POST['note_color'];

    // save to DB
    $conn->query("UPDATE users SET theme='$theme', font_size=$font, note_color='$color' WHERE id=$user_id");

    // cập nhật session để index.php dùng ngay
    $_SESSION['theme'] = $theme;
    $_SESSION['font_size'] = $font;
    $_SESSION['note_color'] = $color;

    echo "<div class='toast show'>Saved ✨</div>";
}

$user = $conn->query("SELECT * FROM users WHERE id=$user_id")->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Preferences</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

<style>
* { box-sizing: border-box; }

body {
    font-family: 'Inter', sans-serif;
    background: radial-gradient(circle at top, #eef2ff, #f9fafb);
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

.card {
    width: 460px;
    padding: 30px;
    border-radius: 24px;
    backdrop-filter: blur(20px);
    background: rgba(255,255,255,0.7);
    box-shadow: 0 20px 60px rgba(0,0,0,0.1);
    border: 1px solid rgba(255,255,255,0.4);
}

h2 {
    margin-bottom: 25px;
    font-weight: 700;
    font-size: 22px;
}

label {
    font-weight: 600;
    margin-bottom: 6px;
    display: block;
}

select {
    width: 100%;
    padding: 12px;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    margin-bottom: 18px;
}

.color-grid {
    display: flex;
    gap: 12px;
    margin: 18px 0 28px;
}

.color-box {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    cursor: pointer;
    transition: all 0.25s ease;
    border: 2px solid transparent;
}

.color-box:hover {
    transform: scale(1.15);
}

input[type="radio"]:checked + .color-box {
    border: 2px solid #6366f1;
    box-shadow: 0 0 0 4px rgba(99,102,241,0.25);
}

button {
    width: 100%;
    padding: 14px;
    border-radius: 14px;
    border: none;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: white;
    font-weight: 600;
    font-size: 15px;
    cursor: pointer;
    transition: 0.25s;
    margin-bottom: 10px;
}

button:hover {
    transform: translateY(-2px);
}

.back-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;

    padding: 12px;
    border-radius: 14px;

    /* 🌊 MÀU CÂN BẰNG */
    background: #e0f2fe;
    color: #0369a1;

    font-weight: 600;
    text-decoration: none;

    border: 1px solid #bae6fd;

    box-shadow: 0 4px 10px rgba(3, 105, 161, 0.15);
    transition: all 0.25s ease;
}

.back-btn:hover {
    background: #bae6fd;
    color: #075985;

    transform: translateY(-1px);
    box-shadow: 0 8px 18px rgba(3, 105, 161, 0.25);
}

.back-btn:active {
    transform: scale(0.97);
}

.toast {
    position: fixed;
    top: 20px;
    right: 20px;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: white;
    padding: 12px 18px;
    border-radius: 12px;
}

.preview {
    margin-top: 20px;
    padding: 16px;
    border-radius: 14px;
}

/* ===== MOBILE (<= 768px) ===== */
@media (max-width: 768px) {

    body {
        padding: 20px;
        align-items: flex-start; /* tránh bị ép giữa quá */
    }

    .card {
        width: 100%;
        padding: 22px;
        border-radius: 18px;
    }

    h2 {
        font-size: 20px;
        text-align: center;
    }

    select {
        padding: 11px;
        font-size: 14px;
    }

    button {
        padding: 13px;
        font-size: 14px;
    }

    .back-btn {
        padding: 11px;
        font-size: 14px;
    }

    .preview {
        font-size: 14px;
    }
}

/* ===== SMALL MOBILE (<= 480px) ===== */
@media (max-width: 480px) {

    .card {
        padding: 18px;
        border-radius: 16px;
    }

    h2 {
        font-size: 18px;
    }

    /* 👉 COLOR GRID QUAN TRỌNG */
    .color-grid {
        flex-wrap: wrap;
        justify-content: center;
        gap: 10px;
    }

    .color-box {
        width: 42px;
        height: 42px;
    }

    select {
        font-size: 13px;
    }

    button {
        font-size: 13px;
    }

    .back-btn {
        font-size: 13px;
    }

    .preview {
        padding: 14px;
        font-size: 13px;
    }
}


</style>
</head>

<body>

<div class="card">
<h2>⚙️ Preferences</h2>

<form method="POST">

<label>Theme</label>
<select name="theme">
    <option value="light" <?= $user['theme']=='light'?'selected':'' ?>>Light</option>
    <option value="dark" <?= $user['theme']=='dark'?'selected':'' ?>>Dark</option>
</select>

<label>Font Size</label>
<select name="font_size">
    <option value="14" <?= $user['font_size']==14?'selected':'' ?>>14</option>
    <option value="16" <?= $user['font_size']==16?'selected':'' ?>>16</option>
    <option value="18" <?= $user['font_size']==18?'selected':'' ?>>18</option>
</select>

<label>Note Color</label>
<div class="color-grid">
<?php
$colors = [
    "default" => "#ffffff",
    "purple" => "#eef2ff",
    "blue" => "#eff6ff",
    "green" => "#ecfdf5",
    "yellow" => "#fefce8",
    "pink" => "#fdf2f8"
];

foreach ($colors as $key => $color):
?>
<label>
<input type="radio" name="note_color" value="<?= $key ?>" <?= $user['note_color']==$key?'checked':'' ?> hidden>
<div class="color-box" style="background:<?= $color ?>"></div>
</label>
<?php endforeach; ?>
</div>

<button name="save_pref">Save Preferences</button>

<a href="index.php" class="back-btn">
    ← Back to Notes
</a>

</form>

<div class="preview" id="previewBox">Live preview ✨</div>

</div>

<script>
const colors = {
    default: '#ffffff',
    purple: '#eef2ff',
    blue: '#eff6ff',
    green: '#ecfdf5',
    yellow: '#fefce8',
    pink: '#fdf2f8'
};

const radios = document.querySelectorAll("input[name='note_color']");
const preview = document.getElementById("previewBox");

radios.forEach(r => {
    r.addEventListener('change', () => {
        preview.style.background = colors[r.value];
    });
});

const checked = document.querySelector("input[name='note_color']:checked");
if (checked) preview.style.background = colors[checked.value];
</script>

</body>
</html>