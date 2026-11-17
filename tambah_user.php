<?php
include '../db/config.php';

$username = $_POST['username'];
$password = $_POST['password'];

mysqli_query($conn, "INSERT INTO users (username, password) VALUES ('$username', '$password')");

header("Location: ../pages/manage_users.php");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah User</title>
    <link rel="stylesheet" href="style.css"> <!-- Tambahkan ini -->
</head>
<body>
    <h2>Form Tambah User</h2>
    <!-- Form tambah user -->
</body>
</html>
