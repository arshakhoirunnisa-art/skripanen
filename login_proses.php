<?php
session_start();

$valid_username = "admin";
$valid_password = "1234";

$username = $_POST['username'];
$password = $_POST['password'];

if ($username === $valid_username && $password === $valid_password) {
    $_SESSION['username'] = $username;
    header("Location: dashboard.php");
    exit;
} else {
    echo "<p style='color:red;'>Login gagal. Username atau password salah.</p>";
}
?>

