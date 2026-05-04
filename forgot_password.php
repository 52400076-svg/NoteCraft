<?php
require_once "includes/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];

    $check = $conn->query("SELECT * FROM users WHERE email='$email'");

    if ($check->num_rows == 0) {
        $message = "Email không tồn tại!";
    } else {

        $reset_code = bin2hex(random_bytes(16));

        $conn->query("UPDATE users SET reset_code='$reset_code' WHERE email='$email'");

        $link = "http://localhost/reset_password.php?code=$reset_code";

        $message = "Reset link created:<br><a href='$link' target='_blank'>$link</a>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: "Segoe UI", sans-serif;
    min-height: 100vh; /* fix mobile height */
    display: flex;
    justify-content: center;
    align-items: center;

    background: linear-gradient(135deg, #0f172a, #312e81, #0ea5e9, #22c55e);
    background-size: 300% 300%;
    animation: bgMove 12s ease infinite;

    padding: 16px; /* tránh sát viền mobile */
}

@keyframes bgMove {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

/* ===== CONTAINER ===== */
.container {
    width: 100%;
    max-width: 380px;

    background: rgba(255, 255, 255, 0.78);
    backdrop-filter: blur(16px);

    padding: 32px;
    border-radius: 20px;

    box-shadow: 0 25px 70px rgba(0,0,0,0.25);

    text-align: center;

    animation: fadeUp 0.5s ease;
}

@keyframes fadeUp {
    from { transform: translateY(20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

.title {
    font-size: 22px;
    font-weight: 700;
    color: #111827;
}

.subtitle {
    font-size: 13px;
    color: #6b7280;
    margin-bottom: 20px;
}

/* ===== INPUT ===== */
input {
    width: 100%;
    padding: 12px 14px;
    margin: 8px 0;

    border-radius: 12px;
    border: 1px solid rgba(0,0,0,0.08);

    outline: none;

    font-size: 14px;

    background: rgba(255,255,255,0.7);

    transition: all 0.2s ease;
}

/* FIX iPhone zoom */
@media (max-width: 480px) {
    input {
        font-size: 16px;
        padding: 14px;
    }
}

input:focus {
    border-color: #06b6d4;
    box-shadow: 0 0 0 3px rgba(6,182,212,0.2);
    background: #fff;
}

/* ===== BUTTON ===== */
button {
    width: 100%;
    padding: 14px; /* dễ bấm mobile */

    margin-top: 12px;

    border: none;
    border-radius: 12px;

    background: linear-gradient(135deg, #06b6d4, #3b82f6, #8b5cf6);
    color: white;

    font-size: 15px;
    font-weight: 600;

    cursor: pointer;

    transition: all 0.2s ease;
}

button:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 25px rgba(59,130,246,0.35);
}

button:active {
    transform: scale(0.98);
}

/* ===== MESSAGE ===== */
.message {
    margin-top: 15px;
    font-size: 13px;
    color: #111827;
    word-break: break-word; /* tránh tràn link */
}

.message a {
    color: #2563eb;
}

/* ===== LINK ===== */
.link {
    display: block;
    margin-top: 12px;
    font-size: 13px;
    color: #4f46e5;
    text-decoration: none;
}

.link:hover {
    text-decoration: underline;
}

/* ============================= */
/* RESPONSIVE BREAKPOINTS */
/* ============================= */

/* 📱 điện thoại nhỏ */
@media (max-width: 480px) {

    .container {
        padding: 20px;
        border-radius: 16px;
    }

    .title {
        font-size: 20px;
    }

    .subtitle {
        font-size: 12px;
    }

    button {
        font-size: 16px;
        padding: 15px;
    }
}

/* 📱 tablet */
@media (min-width: 481px) and (max-width: 768px) {
    .container {
        max-width: 420px;
    }
}
</style>
</head>

<body>

<div class="container">

    <div class="title">Forgot Password</div>
    <div class="subtitle">We will send you a reset link</div>

    <form method="POST">
        <input type="email" name="email" placeholder="Enter your email" required>
        <button type="submit">Send Reset Link</button>
    </form>

    <?php if ($message): ?>
        <div class="message">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <a class="link" href="login.php">← Back to login</a>

</div>

</body>
</html>
