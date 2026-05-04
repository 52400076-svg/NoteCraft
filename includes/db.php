<?php

$host = "localhost";
$dbname = "note_db";
$username = "root";
$password = "";

// Kết nối MySQL
$conn = new mysqli($host, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Set UTF-8 cho tiếng Việt
$conn->set_charset("utf8");

?>