<?php
include "config.php";
$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$sql = "INSERT INTO Users (username, password) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $password);

if ($stmt->execute()) {
    echo "User berhasil ditambahkan. <a href='manage_user.php'>Kembali</a>";
} else {
    echo "Gagal tambah user: " . $conn->error;
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background-color: #1e1e1e;
            color: white;
            font-family: Arial, sans-serif;
        }

        table {
            border-collapse: collapse;
            width: 80%;
            margin: 20px auto;
            background-color: #2e2e2e;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            color: #ffe97f;
        }

        a {
            color: #4da6ff;
        }

        .centered-box {
            width: 300px;
            margin: 40px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
        }

        input, button {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border-radius: 5px;
            border: none;
        }

        button {
            background-color: #4da6ff;
            color: white;
            cursor: pointer;
        }

        h1, h2 {
            text-align: center;
        }

        .dashboard-link {
            text-align: center;
            margin-top: 10px;
        }

        .dashboard-link small {
            font-size: 0.9em;
        }
    </style>
</head>
<body>