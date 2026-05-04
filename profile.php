<?php
session_start();
require_once "includes/db.php";
require_once "includes/auth_check.php";

$user_id = $_SESSION['user_id'];
$user = $conn->query("SELECT * FROM users WHERE id=$user_id")->fetch_assoc();

if (isset($_POST['update_profile'])) {

    $display_name = $_POST['display_name'];
    $avatar = $user['avatar'];

    if (!empty($_FILES['avatar']['name'])) {
        $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
        $avatar = "avatar_" . $user_id . "_" . time() . "." . $ext;
        move_uploaded_file($_FILES['avatar']['tmp_name'], "uploads/" . $avatar);
    }

    $conn->query("
        UPDATE users 
        SET display_name='$display_name', avatar='$avatar'
        WHERE id=$user_id
    ");

    header("Location: index.php");
    exit();
}

if (isset($_POST['change_password'])) {

    $old = $_POST['old_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_new_password'];

    if ($new != $confirm) {
        die("Mật khẩu mới không khớp!");
    }

    $check = $conn->query("SELECT * FROM users WHERE id=$user_id");
    $u = $check->fetch_assoc();

    if (!password_verify($old, $u['password'])) {
        die("Sai mật khẩu cũ!");
    }

    $hashed = password_hash($new, PASSWORD_BCRYPT);
    $conn->query("UPDATE users SET password='$hashed' WHERE id=$user_id");

    echo "Đổi mật khẩu thành công!";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Profile</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
:root {
    --bg: linear-gradient(135deg, #eef2ff, #f8fafc);
    --surface: #ffffff;
    --text: #0f172a;
    --muted: #6b7280;
    --border: #e5e7eb;

    --primary: #6366f1;
    --primary-soft: rgba(99,102,241,0.15);
}

/* BACKGROUND */
body {
    background: var(--bg);
    font-family: system-ui;
}

/* CARD - FLAT (KHÔNG NỔI) */
.card-modern {
    background: var(--surface);
    border-radius: 18px;
    padding: 25px;
    border: 1px solid var(--border);

    box-shadow: none;   /* ❌ bỏ shadow */
    transition: none;   /* ❌ bỏ animation */
}

/* AVATAR */
.avatar-wrapper {
    text-align: center;
    margin-bottom: 20px;
}

.avatar-wrapper img {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    object-fit: cover;

    border: 4px solid white;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);

    transition: all 0.3s ease;
}

.avatar-wrapper img:hover {
    transform: scale(1.05);
}

/* INPUT */
.form-control {
    border-radius: 12px;
    padding: 12px;

    border: 1px solid var(--border);
    background: #f9fafb;

    transition: all 0.2s ease;
}

.form-control:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 4px var(--primary-soft);
    background: white;
}

/* BUTTON BASE */
.btn {
    border-radius: 12px !important;
    font-weight: 500;
    transition: all 0.2s ease;
}

/* PRIMARY BUTTON */
.btn-primary {
    background: linear-gradient(135deg, #6366f1, #4f46e5);
    border: none;

    box-shadow: 0 6px 15px rgba(99,102,241,0.3);
}

/* HOVER */
.btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 14px 30px rgba(99,102,241,0.45);
}

/* CLICK */
.btn-primary:active {
    transform: scale(0.95);
}

/* OUTLINE BUTTON */
.btn-outline-primary {
    border: 2px solid var(--primary);
    color: var(--primary);
    background: transparent;
}

/* HOVER */
.btn-outline-primary:hover {
    background: var(--primary);
    color: white;

    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(99,102,241,0.35);
}

/* BACK BUTTON */
.back-btn {
    text-decoration: none;
    font-weight: 500;

    border-radius: 12px;
    padding: 8px 14px;

    background: white;
    border: 1px solid var(--border);

    transition: all 0.2s ease;
}

/* HOVER BACK */
.back-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

/* HEADER */
.profile-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

/* TITLE */
.section-title {
    font-weight: 600;
    margin-bottom: 15px;
}

/* TEXT */
.text-muted {
    color: var(--muted) !important;
}

/* ===== RESPONSIVE ===== */

/* MOBILE (<=576px) */
@media (max-width: 576px) {

    .container {
        margin-top: 20px !important;
        padding: 0 12px;
    }

    .card-modern {
        padding: 18px;
        border-radius: 14px;
    }

    .avatar-wrapper img {
        width: 80px;
        height: 80px;
    }

    .profile-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    .back-btn {
        width: 100%;
        text-align: center;
    }

    .btn {
        font-size: 14px;
        padding: 10px;
    }
}

/* TABLET (577px → 991px) */
@media (min-width: 577px) and (max-width: 991px) {

    .container {
        max-width: 600px;
    }

    .avatar-wrapper img {
        width: 95px;
        height: 95px;
    }
}

/* LARGE SCREEN (>1200px) – cho đẹp hơn chút */
@media (min-width: 1200px) {

    .container {
        max-width: 720px;
    }
}
</style>
</head>

<body>

<div class="container mt-5" style="max-width:700px">

    <!-- HEADER -->
    <div class="profile-header">
        <h3>Profile</h3>
        <a href="index.php" class="back-btn">← Back</a>
    </div>

    <!-- PROFILE -->
    <div class="card-modern mb-4">

        <?php
        $avatarPath = "uploads/" . ($user['avatar'] ?: "default.png");
        $version = file_exists($avatarPath) ? filemtime($avatarPath) : time();
        ?>

        <div class="avatar-wrapper">
            <img src="<?= $avatarPath ?>?v=<?= $version ?>">
            <p class="mt-2 text-muted"><?= htmlspecialchars($user['display_name']) ?></p>
        </div>

        <form method="POST" enctype="multipart/form-data">

            <div class="mb-3">
                <label>Display Name</label>
                <input type="text" name="display_name"
                       value="<?= htmlspecialchars($user['display_name']) ?>"
                       class="form-control">
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email"
                       value="<?= $user['email'] ?>"
                       class="form-control" disabled>
            </div>

            <div class="mb-3">
                <label>Avatar</label>
                <input type="file" name="avatar" class="form-control">
            </div>

            <button class="btn btn-primary w-100" name="update_profile">
                Save Profile
            </button>

        </form>
    </div>

    <!-- PASSWORD -->
    <div class="card-modern">

        <h5 class="section-title">Change Password</h5>

        <form method="POST">

            <div class="mb-3">
                <input type="password" name="old_password"
                       class="form-control"
                       placeholder="Current password">
            </div>

            <div class="mb-3">
                <input type="password" name="new_password"
                       class="form-control"
                       placeholder="New password">
            </div>

            <div class="mb-3">
                <input type="password" name="confirm_new_password"
                       class="form-control"
                       placeholder="Confirm new password">
            </div>

            <button class="btn btn-outline-primary w-100" name="change_password">
                Change Password
            </button>

        </form>

    </div>

</div>

</body>
</html>