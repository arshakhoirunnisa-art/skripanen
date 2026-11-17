<?php
session_start();

// Membaca data lahan dari JSON
$lahan_file = 'lahan.json';
$database_lahan = [];
if (file_exists($lahan_file)) {
    $database_lahan = json_decode(file_get_contents($lahan_file), true);
}

// Pastikan user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manajemen Lahan Sawah</title>
    <style>
        body { background-color: #121212; color: white; font-family: sans-serif; margin: 20px; }
        .container { max-width: 800px; margin: 0 auto; background-color: #1e1e1e; padding: 20px; border-radius: 10px; border: 1px solid #333; }
        h2 { color: #2ecc71; border-bottom: 2px solid #333; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #444; }
        th { background-color: #2a2a2a; color: #2ecc71; }
        tr:hover { background-color: #252525; }
        .back-btn { display: inline-block; margin-bottom: 20px; padding: 10px 15px; background-color: #555; color: white; text-decoration: none; border-radius: 5px; font-size: 14px; }
        .back-btn:hover { background-color: #777; }
        .badge { padding: 5px 10px; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .badge-pribadi { background-color: #27ae60; color: white; }
        .badge-gadai { background-color: #d35400; color: white; }
        .badge-titipan { background-color: #2980b9; color: white; }
    </style>
</head>
<body>

<div class="container">
    <a href="dashboard.php" class="back-btn">⬅ Kembali ke Dashboard</a>
    <h2>🌾 Daftar Aset Lahan Sawah Kelolaan</h2>
    
    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama Lahan Sawah</th>
                <th>Status Kepemilikan</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if (empty($database_lahan)) {
                echo "<tr><td colspan='4' style='text-align:center;'>Belum ada data lahan.</td></tr>";
            } else {
                foreach ($database_lahan as $lahan) {
                    // Membuat warna badge sesuai status kepemilikan
                    $badge_class = 'badge-pribadi';
                    if ($lahan['status'] == 'Gadai') $badge_class = 'badge-gadai';
                    if ($lahan['status'] == 'Hak Pakai / Titipan') $badge_class = 'badge-titipan';

                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($lahan['id_lahan']) . "</td>";
                    echo "<td>" . htmlspecialchars($lahan['nama_lahan']) . "</td>";
                    echo "<td><span class='badge " . $badge_class . "'>" . htmlspecialchars($lahan['status']) . "</span></td>";
                    echo "<td>" . htmlspecialchars($lahan['keterangan']) . "</td>";
                    echo "</tr>";
                }
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>