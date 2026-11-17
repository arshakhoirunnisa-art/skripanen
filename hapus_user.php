<?php
include "config.php";
$id = $_GET['id'];
$conn->query("DELETE FROM Users WHERE id_user = $id");
header("Location: manage_user.php");