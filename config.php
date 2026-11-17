<?php
// Mengambil data user dari file JSON sebagai pengganti SQLite
$json_file = 'users.json';

if (!file_exists($json_file)) {
    die("Koneksi gagal: File database users.json tidak ditemukan!");
}

$database_users = json_decode(file_get_contents($json_file), true);
?>