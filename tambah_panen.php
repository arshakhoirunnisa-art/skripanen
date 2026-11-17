<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Membaca data lahan dari JSON untuk dijadikan pilihan di dropdown form
$lahan_file = 'lahan.json';
$database_lahan = [];
if (file_exists($lahan_file)) {
    $database_lahan = json_decode(file_get_contents($lahan_file), true);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Data Panen</title>
    <style>
        body { background-color: #121212; color: white; font-family: sans-serif; margin: 20px; }
        .container { max-width: 500px; margin: 0 auto; background-color: #1e1e1e; padding: 30px; border-radius: 10px; border: 1px solid #333; }
        h2 { color: #2ecc71; border-bottom: 2px solid #333; padding-bottom: 10px; margin-top: 0; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #bbb; font-size: 14px; }
        input[type="text"], input[type="number"], input[type="date"], select { width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #444; background-color: #2a2a2a; color: white; box-sizing: border-box; }
        input:focus, select:focus { border-color: #2ecc71; outline: none; }
        .btn-submit { background-color: #2ecc71; color: black; font-weight: bold; border: none; padding: 12px 20px; width: 100%; border-radius: 5px; cursor: pointer; font-size: 16px; margin-top: 10px; }
        .btn-submit:hover { background-color: #27ae60; }
        .back-btn { display: inline-block; margin-bottom: 20px; color: #aaa; text-decoration: none; font-size: 14px; }
        .back-btn:hover { color: white; }
    </style>
</head>
<body>

<div class="container">
    <a href="data_panen.php" class="back-btn">Kembali ke Riwayat Panen</a>
    <h2>Tambah Catatan Panen Baru</h2>
    
    <form action="tambah_panen_proses.php" method="POST">
        <div class="form-group">
            <label>Lokasi Lahan Sawah:</label>
            <select name="id_lahan" required>
                <option value="">-- Pilih Sawah Kelolaan --</option>
                <?php foreach ($database_lahan as $lahan): ?>
                    <option value="<?= $lahan['id_lahan']; ?>">
                        <?= $lahan['nama_lahan']; ?> (<?= $lahan['luas_lahan']; ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Tanggal Panen / Gagal:</label>
            <input type="date" name="tanggal" required>
        </div>

        <div class="form-group">
            <label>Jenis Tanaman:</label>
            <input type="text" name="jenis_tanaman" placeholder="Contoh: BENGKOANG, JAGUNG, UBI" required style="text-transform: uppercase;">
        </div>

        <div class="form-group">
            <label>Luas Lahan (m²):</label>
            <input type="text" name="luas_lahan" placeholder="Contoh: 900 m" required>
        </div>

        <div class="form-group">
            <label>Berat Hasil (Kg):</label>
            <input type="number" name="berat_kg" placeholder="Ketik angka saja, contoh: 500" required>
        </div>

        <div class="form-group">
            <label>Total Pengeluaran / Modal (Rp):</label>
            <input type="text" name="total_pengeluaran" placeholder="Contoh: 400.000 (Gunakan titik)" required>
        </div>

        <div class="form-group">
            <label>Uang Pendapatan Panen (Rp):</label>
            <input type="text" name="uang_panen" placeholder="Contoh: 1.500.000 (Gunakan titik)" required>
        </div>

        <div class="form-group">
            <label>Status Keterangan:</label>
            <select name="keterangan" required>
                <option value="PANEN">PANEN (Berhasil)</option>
                <option value="GAGAL PANEN">GAGAL PANEN</option>
            </select>
        </div>

        <button type="submit" class="btn-submit">Simpan</button>
    </form>
</div>

</body>
</html>