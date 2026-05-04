<?php
require_once "includes/db.php";

$code = $_GET['code'] ?? '';
$message = "";

// check code
$check = $conn->query("SELECT * FROM users WHERE reset_code='$code'");

if (!$code || $check->num_rows == 0) {
    die("Invalid or expired link");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($password !== $confirm) {
        $message = "❌ Passwords do not match!";
    } else {

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $conn->query("
            UPDATE users 
            SET password='$hashedPassword', reset_code=NULL 
            WHERE reset_code='$code'
        ");

        $message = "✅ Password reset successfully! You can now login.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Segoe UI", sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;

            /* 🔥 SAME APP THEME */
            background: linear-gradient(135deg, #0f172a, #312e81, #0ea5e9, #22c55e);
            background-size: 300% 300%;
            animation: bgMove 12s ease infinite;
        }

        @keyframes bgMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .container {
            width: 380px;

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

        input:focus {
            border-color: #06b6d4;
            box-shadow: 0 0 0 3px rgba(6,182,212,0.2);
            background: #fff;
        }

        button {
            width: 100%;
            padding: 12px;

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

        .message {
            margin-top: 12px;
            font-size: 13px;
            color: #111827;
        }

        .message.success {
            color: #16a34a;
        }

        .message.error {
            color: #dc2626;
        }

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
    </style>
</head>

<body>

<div class="container">

    <div class="title">Reset Password</div>
    <div class="subtitle">Enter your new password</div>

    <form method="POST">

        <input type="password" name="password" placeholder="New password" required>

        <input type="password" name="confirm_password" placeholder="Confirm password" required>

        <button type="submit">Reset Password</button>

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
