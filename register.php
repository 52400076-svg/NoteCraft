<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';
require_once "includes/db.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    // 1. check password giống nhau
    if ($password !== $confirm) {
        die("Mật khẩu không khớp!");
    }

    // 2. check email tồn tại
    $check = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($check->num_rows > 0) {
        die("Email đã tồn tại!");
    }

    // 3. bcrypt password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // 4. tạo activation code
    $code = bin2hex(random_bytes(16));

    // 5. is_activated = 0
    $sql = "INSERT INTO users (name, email, password, is_activated, activation_code)
            VALUES ('$name', '$email', '$hashedPassword', 0, '$code')";

    if ($conn->query($sql)) {
        $user_id = $conn->insert_id;
        
        // PHPMailer
        $mail = new PHPMailer(true);

$mail->SMTPDebug = 2;
$mail->Debugoutput = 'html';

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'tpk06072006@gmail.com';
    $mail->Password = 'vldegiyjhjurhqyk';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('tpk06072006@gmail.com', 'Note App');
    $mail->addAddress($email);

    $link = "http://localhost/activate.php?code=$code";

    $mail->Subject = "Activate your account";
    $mail->Body = "Click here: $link";

    if(!$mail->send()) {
        die("❌ Mailer Error: " . $mail->ErrorInfo);
    }

    echo "✅ Mail sent successfully";

} catch (Exception $e) {
    die("❌ Exception: " . $e->getMessage());
}

        // 6. tự động login (SESSION)
        $_SESSION['user_id'] = $user_id;

        
        header("Location: index.php");
        exit();

    } else {
        echo "Lỗi: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: "Segoe UI", sans-serif;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;

    background: linear-gradient(135deg, #0f172a, #312e81, #0ea5e9, #22c55e);
    background-size: 300% 300%;
    animation: bgMove 12s ease infinite;

    padding: 16px; /*tránh dính sát viền mobile */
}

@keyframes bgMove {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

/* ===== CONTAINER ===== */
.container {
    width: 100%;
    max-width: 380px; /* desktop giữ đẹp */

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

    background: rgba(255,255,255,0.7);

    font-size: 14px;

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

/* ===== BUTTON ===== */
button {
    width: 100%;
    padding: 14px; /* mobile dễ bấm hơn */

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

/* ===== LINK ===== */
.link {
    display: block;
    margin-top: 14px;
    font-size: 13px;
    color: #4f46e5;
    text-decoration: none;
    transition: 0.2s;
}

.link:hover {
    color: #1e1b4b;
    text-decoration: underline;
}

.error {
    color: red;
    font-size: 13px;
    margin-bottom: 10px;
}

/* ============================= */
/* RESPONSIVE MOBILE */
/* ============================= */

/* điện thoại nhỏ */
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

    input {
        font-size: 16px; /* fix iPhone zoom */
        padding: 14px;
    }

    button {
        font-size: 16px;
        padding: 15px;
    }
}

/* tablet */
@media (min-width: 481px) and (max-width: 768px) {
    .container {
        max-width: 420px;
    }
}
</style>
</head>

<body>

<div class="container">

    <div class="title">Create Account</div>
    <div class="subtitle">Start organizing your notes smarter</div>

    <form method="POST">

        <input type="text" name="name" placeholder="Full name" required>

        <input type="email" name="email" placeholder="Email address" required>

        <input type="password" name="password" placeholder="Password" required>

        <input type="password" name="confirm_password" placeholder="Confirm password" required>

        <button type="submit">Register</button>

    </form>

    <a class="link" href="login.php">
        Already have an account? Login
    </a>

</div>

</body>
</html>
