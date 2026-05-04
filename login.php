<?php
session_start();
require_once "includes/db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        $error = "Email không tồn tại!";
    } else {
        $user = $result->fetch_assoc();

        if (!password_verify($password, $user['password'])) {
            $error = "Sai mật khẩu!";
        } elseif ($user['is_activated'] == 0) {
            $error = "Tài khoản chưa được kích hoạt!";
        } else {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['is_activated'] = $user['is_activated'];

            header("Location: index.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: 'Inter', sans-serif;

    min-height: 100vh;

    display: flex;
    justify-content: center;
    align-items: center;

    padding: 16px; /* ✅ tránh dính viền mobile */

    /* background animation */
    background: linear-gradient(135deg, #0f172a, #312e81, #0ea5e9, #22c55e);
    background-size: 300% 300%;
    animation: bgMove 12s ease infinite;
}

/* animation */
@keyframes bgMove {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

/* CARD */
.container {
    width: 100%;
    max-width: 380px;

    background: rgba(255, 255, 255, 0.82);
    backdrop-filter: blur(16px);

    padding: 28px;
    border-radius: 20px;

    box-shadow: 0 25px 70px rgba(0,0,0,0.25);

    text-align: center;

    animation: fadeUp 0.5s ease;
}

@keyframes fadeUp {
    from { transform: translateY(20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

/* TEXT */
.title {
    font-size: 24px;
    font-weight: 700;
    color: #111827;
}

.subtitle {
    font-size: 14px;
    color: #6b7280;
    margin-bottom: 20px;
}

/* INPUT */
input {
    width: 100%;
    padding: 14px 16px; /* to hơn cho mobile */
    margin: 10px 0;

    border-radius: 12px;
    border: 1px solid rgba(0,0,0,0.08);

    outline: none;

    background: rgba(255,255,255,0.75);

    font-size: 16px; /* FIX iOS không zoom */

    transition: all 0.2s ease;
}

input::placeholder {
    color: #9ca3af;
}

input:focus {
    border-color: #06b6d4;
    box-shadow: 0 0 0 3px rgba(6,182,212,0.2);
    background: #fff;
}

/* BUTTON */
button {
    width: 100%;
    padding: 14px;

    margin-top: 14px;

    border: none;
    border-radius: 12px;

    background: linear-gradient(135deg, #06b6d4, #3b82f6, #8b5cf6);
    color: white;

    font-size: 16px;
    font-weight: 600;

    cursor: pointer;

    transition: all 0.2s ease;
}

button:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 25px rgba(59,130,246,0.35);
}

button:active {
    transform: scale(0.97);
}

/* LINKS */
.link-btn {
    display: block;
    margin-top: 14px;

    font-size: 14px;
    color: #4f46e5;

    text-decoration: none;

    transition: 0.2s;
}

.link-btn:hover {
    color: #1e1b4b;
    text-decoration: underline;
}

/* RESPONSIVE CHUẨN */
@media (max-width: 480px) {

    .container {
        padding: 22px;
        border-radius: 16px;
    }

    .title {
        font-size: 20px;
    }

    .subtitle {
        font-size: 13px;
    }

    input {
        padding: 13px;
    }

    button {
        padding: 13px;
    }
}

/* 📱 VERY SMALL DEVICES */
@media (max-width: 350px) {
    .container {
        padding: 18px;
    }
}

/* ERROR BOX */
.error-box {
    background: rgba(239, 68, 68, 0.12);
    color: #b91c1c;

    padding: 12px;
    margin-bottom: 12px;

    border-radius: 10px;
    border: 1px solid rgba(239,68,68,0.3);

    font-size: 14px;
    text-align: left;

    animation: shake 0.3s ease;
}

/* animation nhẹ */
@keyframes shake {
    0% { transform: translateX(-4px); }
    50% { transform: translateX(4px); }
    100% { transform: translateX(0); }
}

</style>
</head>

<body>

<div class="container">

    <div class="title">NoteCraft</div>
    <div class="subtitle">Ghi chú an toàn - Chia sẻ dễ dàng</div>

    <?php if (!empty($error)): ?>
        <div class="error-box">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <input type="email" name="email" placeholder="Email address" required>

        <input type="password" name="password" placeholder="Password" required>

        <button type="submit">Login</button>

    </form>

    <a class="link-btn" href="register.php">
        Don’t have an account? Create one
    </a>

    <a class="link-btn" href="forgot_password.php">
        Forgot password?
    </a>

</div>

</body>
</html>