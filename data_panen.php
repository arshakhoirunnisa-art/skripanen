<?php
session_start();

// Pastikan user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// 1. Membaca Data Lahan (untuk mengambil nama sawah berdasarkan ID Lahan)
$lahan_file = 'lahan.json';
$lahan_map = [];
if (file_exists($lahan_file)) {
    $lahan_data = json_decode(file_get_contents($lahan_file), true);
    foreach ($lahan_data as $l) {
        $lahan_map[$l['id_lahan']] = $l['nama_lahan'];
    }
}

// 2. Membaca Data Panen dari file 'panencsv.json'
$panen_file = 'panencsv.json';
$database_panen = [];
if (file_exists($panen_file)) {
    $database_panen = json_decode(file_get_contents($panen_file), true);
}

// =========================================================================
// KEAJAIBAN BACKEND LOGIKA DOWNLOAD EXCEL NATIVE (SAT-SET ANTI GAGAL)
// =========================================================================
if (isset($_GET['action']) && $_GET['action'] == 'download_excel') {
    // Tentukan nama file Excel-nya saat diunduh nanti
    $filename = "Laporan_Riwayat_Panen_SISawah_" . date('Y-m-d') . ".xls";
    
    // Header sakti PHP agar browser mendeteksi file sebagai aplikasi Excel dokumen text
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=$filename");
    header("Pragma: no-cache");
    header("Expires: 0");

    // Membuat kerangka tabel di dalam file Excel-nya nanti
    echo "<table border='1'>";
    echo "<thead>
            <tr style='background-color: #2ecc71; color: black; font-weight: bold;'>
                <th>No</th>
                <th>Lokasi Lahan Sawah</th>
                <th>Tanggal</th>
                <th>Jenis Tanaman</th>
                <th>Luas Lahan</th>
                <th>Berat Hasil (Kg)</th>
                <th>Total Pengeluaran (Modal)</th>
                <th>Uang Pendapatan Panen</th>
                <th>Uang Untung Bersih</th>
                <th>Keterangan</th>
            </tr>
          </thead>";
    echo "<tbody>";

    if (!empty($database_panen)) {
        $no = 1;
        foreach ($database_panen as $panen) {
            $id_lahan = $panen['ID_LAHAN'] ?? '';
            $nama_lahan = $lahan_map[$id_lahan] ?? $id_lahan;
            $tanggal = $panen['TANGGAL'] ?? '-';
            $jenis_tanaman = $panen['JENIS TANAMAN'] ?? '-';
            $luas_lahan = $panen['LUAS LAHAN'] ?? '-';
            $berat_kg = (int)($panen['BERAT KG'] ?? 0);
            $keterangan = $panen['KETERANGAN'] ?? '-';

            $modal_raw = str_replace('.', '', $panen['TOTAL PENGELUARAN'] ?? '0');
            $pendapatan_raw = str_replace('.', '', $panen['UANG PANEN'] ?? '0');
            $modal = (int)$modal_raw;
            $pendapatan = (int)$pendapatan_raw;
            $untung_bersih = $pendapatan - $modal;

            echo "<tr>";
            echo "<td>" . $no++ . "</td>";
            echo "<td>" . $nama_lahan . "</td>";
            // Trik x-macro mso-number-format agar karakter dibaca teks dan tidak acak-acakan di Excel
            echo "<td style='mso-number-format:\"\@\";'>" . $tanggal . "</td>";
            echo "<td>" . $jenis_tanaman . "</td>";
            echo "<td>" . $luas_lahan . "</td>";
            echo "<td>" . $berat_kg . " Kg</td>";
            echo "<td>Rp " . number_format($modal, 0, ',', '.') . "</td>";
            echo "<td>Rp " . number_format($pendapatan, 0, ',', '.') . "</td>";
            echo "<td>Rp " . number_format($untung_bersih, 0, ',', '.') . "</td>";
            echo "<td>" . $keterangan . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='10'>Belum ada data panen.</td></tr>";
    }
    echo "</tbody>";
    echo "</table>";
    exit; // Wajib di-exit biar kode HTML halaman di bawahnya gak ikut masuk ke dalam file Excel
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Hasil Panen</title>
    <style>
        body { background-color: #121212; color: white; font-family: sans-serif; margin: 20px; }
        .container { max-width: 1250px; margin: 0 auto; background-color: #1e1e1e; padding: 20px; border-radius: 10px; border: 1px solid #333; }
        h2 { color: #2ecc71; border-bottom: 2px solid #333; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 14px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #444; }
        th { background-color: #2a2a2a; color: #2ecc71; }
        tr:hover { background-color: #252525; }
        .back-btn { display: inline-block; margin-bottom: 20px; padding: 10px 15px; background-color: #555; color: white; text-decoration: none; border-radius: 5px; font-size: 14px; margin-right: 5px; }
        .back-btn:hover { background-color: #777; }
        /* Tombol Excel Eksklusif buatan Sengmu */
        .excel-btn { background-color: #27ae60; font-weight: bold; }
        .excel-btn:hover { background-color: #1e8449; }
        .text-profit { color: #2ecc71; font-weight: bold; }
        .text-loss { color: #e74c3c; font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <a href="dashboard.php" class="back-btn">Kembali ke Dashboard</a>
    <a href="tambah_panen.php" class="back-btn">Tambah Panen</a>
    <a href="data_panen.php?action=download_excel" class="back-btn excel-btn">Download Laporan Excel</a>
    
    <h2>Catatan Riwayat Hasil & Keuntungan Panen Sawah</h2>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Lokasi Lahan Sawah</th>
                <th>Tanggal</th>
                <th>Jenis Tanaman</th>
                <th>Luas Lahan</th>
                <th>Berat Hasil (Kg)</th>
                <th>Total Pengeluaran (Modal)</th>
                <th>Uang Pendapatan Panen</th>
                <th>Uang Untung Bersih</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if (empty($database_panen)) {
                echo "<tr><td colspan='11' style='text-align:center;'>Belum ada data panen di dalam file panencsv.json.</td></tr>";
            } else {
                $no = 1;
                foreach ($database_panen as $panen) {
                    // Ambil nama lahan berdasarkan ID_LAHAN
                    $id_lahan = $panen['ID_LAHAN'] ?? '';
                    $nama_lahan = $lahan_map[$id_lahan] ?? $id_lahan;
                    
                    // Ambil data teks mentah dari JSON
                    $tanggal = $panen['TANGGAL'] ?? '-';
                    $jenis_tanaman = $panen['JENIS TANAMAN'] ?? '-';
                    $luas_lahan = $panen['LUAS LAHAN'] ?? '-';
                    $berat_kg = (int)($panen['BERAT KG'] ?? 0);
                    $keterangan = $panen['KETERANGAN'] ?? '-';
                    
                    // TRIK SAKTI: Hapus tanda titik pada string uang agar bisa dihitung matematika oleh PHP
                    $modal_raw = str_replace('.', '', $panen['TOTAL PENGELUARAN'] ?? '0');
                    $pendapatan_raw = str_replace('.', '', $panen['UANG PANEN'] ?? '0');
                    
                    // Ubah jadi angka integer murni
                    $modal = (int)$modal_raw;
                    $pendapatan = (int)$pendapatan_raw;
                    
                    // Hitung Untung Bersih otomatis
                    $untung_bersih = $pendapatan - $modal;
                    
                    // Set warna untung (hijau) atau rugi (merah)
                    $kelas_untung = $untung_bersih >= 0 ? 'text-profit' : 'text-loss';
                    $simbol_rp = $untung_bersih < 0 ? "-Rp " : "Rp ";
                    $abs_untung = abs($untung_bersih);

                    echo "<tr>";
                    echo "<td>" . $no++ . "</td>";
                    echo "<td>" . htmlspecialchars($nama_lahan) . "</td>";
                    echo "<td>" . htmlspecialchars($tanggal) . "</td>";
                    echo "<td>" . htmlspecialchars($jenis_tanaman) . "</td>";
                    echo "<td>" . htmlspecialchars($luas_lahan) . "</td>";
                    echo "<td>" . number_format($berat_kg, 0, ',', '.') . " Kg</td>";
                    echo "<td>Rp " . number_format($modal, 0, ',', '.') . "</td>";
                    echo "<td>Rp " . number_format($pendapatan, 0, ',', '.') . "</td>";
                    echo "<td><span class='" . $kelas_untung . "'>" . $simbol_rp . number_format($abs_untung, 0, ',', '.') . "</span></td>";
                    echo "<td>" . htmlspecialchars($keterangan) . "</td>";
                    echo "<td> <a href='hapus_panen.php?no=" . $panen['NO'] . "' onclick='return confirm(\"Yakin mau hapus data nomor " . $panen['NO'] . "?\")' style='color: #e74c3c; text-decoration: none; font-weight: bold;'>Hapus</a></td>";
                    echo "</tr>";
                }
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>