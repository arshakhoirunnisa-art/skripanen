<?php
session_start();

// Pastikan user sudah login sebelum masuk dashboard
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// 1. Membaca data panencsv.json untuk menghitung total statistik ekonomi sawah
$panen_file = 'panencsv.json';
$total_pendapatan = 0;
$total_modal = 0;
$total_berat = 0;

if (file_exists($panen_file)) {
    $database_panen = json_decode(file_get_contents($panen_file), true);
    foreach ($database_panen as $panen) {
        $modal_raw = str_replace('.', '', $panen['TOTAL PENGELUARAN'] ?? '0');
        $pendapatan_raw = str_replace('.', '', $panen['UANG PANEN'] ?? '0');
        
        $total_modal += (int)$modal_raw;
        $total_pendapatan += (int)$pendapatan_raw;
        $total_berat += (int)($panen['BERAT KG'] ?? 0);
    }
}
$total_untung_bersih = $total_pendapatan - $total_modal;


// 2. PROSES BACKEND: TAMBAH & HAPUS TANAMAN AKTIF (tumbuh.json)
$tumbuh_file = 'tumbuh.json';

// Logika Hapus Data
if (isset($_GET['action']) && $_GET['action'] == 'hapus' && isset($_GET['id'])) {
    if (file_exists($tumbuh_file)) {
        $database_tumbuh = json_decode(file_get_contents($tumbuh_file), true);
        $id_hapus = $_GET['id'];
        if (isset($database_tumbuh[$id_hapus])) {
            unset($database_tumbuh[$id_hapus]);
            // Tata ulang index array agar rapi kembali
            $database_tumbuh = array_values($database_tumbuh);
            file_put_contents($tumbuh_file, json_encode($database_tumbuh, JSON_PRETTY_PRINT));
        }
    }
    header("Location: dashboard.php");
    exit;
}

// Logika Tambah Data
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah_tanaman'])) {
    $tanaman = $_POST['tanaman'];
    $lahan = $_POST['lahan'];
    $tahap = $_POST['tahap'];
    $hari_jalan = (int)$_POST['hari_jalan'];
    
    // Tentukan total hari berdasarkan jenis tanaman secara otomatis
    $hari_total = 90; // Default jagung
    $icon = "🌽";
    $warna_border = "#2ecc71";
    
    // LOGIKA IF-ELSE KATEGORI TANAMAN BESERTA TAMBAHAN KACANG-KACANGAN
    if ($tanaman == 'Singkong') {
        $hari_total = 270;
        $icon = "🍠";
        $warna_border = "#3498db"; // Biru
    } elseif ($tanaman == 'Ubi Merah') {
        $hari_total = 120;
        $icon = "🍠";
        $warna_border = "#e74c3c"; // Merah
    } elseif ($tanaman == 'Ubi Ungu') {
        $hari_total = 120;
        $icon = "🍠"; 
        $warna_border = "#9b59b6"; // Ungu
    } elseif ($tanaman == 'Ubi Putih') {
        $hari_total = 120;
        $icon = "🥔";
        $warna_border = "#f1c40f"; // Kuning/Putih kentang
    } elseif ($tanaman == 'Bengkoang') {
        $hari_total = 150;
        $icon = "🌱";
        $warna_border = "#1abc9c"; // Toska
    } elseif ($tanaman == 'Kacang') {
        $hari_total = 90;
        $icon = "🥜";
        $warna_border = "#a3cb38"; // Hijau Muda Segar
    } elseif ($tanaman == 'Kacang Bogor') {
        $hari_total = 120;
        $icon = "🧆";
        $warna_border = "#f39c12"; // Cokelat Oranye Keemasan
    }

    if ($hari_jalan > $hari_total) $hari_jalan = $hari_total;
    $persentase = round(($hari_jalan / $hari_total) * 100);
    $sisa_hari = $hari_total - $hari_jalan;

    $newData = [
        "icon" => $icon,
        "tanaman" => $tanaman,
        "lahan" => $lahan,
        "tahap" => $tahap,
        "hari_jalan" => $hari_jalan,
        "hari_total" => $hari_total,
        "persentase" => $persentase,
        "sisa_hari" => $sisa_hari,
        "warna_border" => $warna_border,
        "tanggal_input" => date('Y-m-d')
    ];

    $database_tumbuh = [];
    if (file_exists($tumbuh_file)) {
        $database_tumbuh = json_decode(file_get_contents($tumbuh_file), true);
    }
    $database_tumbuh[] = $newData;
    file_put_contents($tumbuh_file, json_encode($database_tumbuh, JSON_PRETTY_PRINT));

    header("Location: dashboard.php");
    exit;
}

// Baca data tumbuh untuk ditampilkan
$database_tumbuh = [];
if (file_exists($tumbuh_file)) {
    $database_tumbuh = json_decode(file_get_contents($tumbuh_file), true);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard SISawah</title>
    <style>
        body { background-color: #121212; color: white; font-family: sans-serif; margin: 0; padding: 20px; }
        .container { max-width: 1100px; margin: 0 auto; }
        
        /* Header Style */
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #333; padding-bottom: 15px; margin-bottom: 30px; }
        .header-title-area { display: flex; align-items: center; gap: 15px; }
        .header h1 { color: #2ecc71; margin: 0; font-size: 28px; }
        .welcome-text { color: #f1c40f; font-size: 15px; font-weight: bold; background-color: #1e1e1e; padding: 5px 12px; border-radius: 20px; border: 1px solid #333; }
        .logout-btn { background-color: #e74c3c; color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 14px; }
        .logout-btn:hover { background-color: #c0392b; }
        
        /* Progress Monitoring Card */
        .growth-card { background-color: #1e1e1e; padding: 25px; border-radius: 10px; border: 1px solid #333; margin-bottom: 25px; position: relative; }
        .growth-card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px; }
        .growth-card h2 { margin: 0; color: #2ecc71; font-size: 18px; }
        .growth-card p { margin: 0 0 20px 0; color: #aaa; font-size: 13px; }
        
        /* Tombol Tambah Elegan */
        .btn-tambah { background-color: #2ecc71; color: white; padding: 6px 12px; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; font-size: 13px; }
        .btn-tambah:hover { background-color: #27ae60; }
        .btn-hapus { background-color: #c0392b; color: white; padding: 3px 8px; text-decoration: none; border-radius: 4px; font-size: 11px; font-weight: bold; }
        .btn-hapus:hover { background-color: #962d22; }

        /* Form Input Sembunyi */
        .form-input-tumbuh { background: #151515; border: 1px solid #333; padding: 15px; border-radius: 8px; margin-bottom: 20px; display: none; }
        .form-input-tumbuh select, .form-input-tumbuh input { background: #222; color: white; border: 1px solid #444; padding: 6px; border-radius: 4px; margin-right: 10px; font-size: 13px; }
        
        /* Item Progress Tanam */
        .growth-item { background: #151515; padding: 15px; border-radius: 8px; border: 1px solid #2d2d2d; margin-bottom: 15px; position: relative; }
        .growth-info { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px; padding-right: 60px; }
        .progress-bar-container { background-color: #2a2a2a; border-radius: 10px; overflow: hidden; height: 16px; border: 1px solid #3a3a3a; }
        .progress-fill { background: linear-gradient(90deg, #2ecc71, #3498db); height: 100%; text-align: right; padding-right: 8px; color: white; font-size: 11px; line-height: 16px; font-weight: bold; transition: width 0.5s ease-in-out; }
        .growth-footer { color: #888; display: block; margin-top: 6px; font-size: 12px; }

        /* Stats Grid */
        .stats-grid { display: flex; gap: 15px; margin-bottom: 30px; }
        .stat-box { flex: 1; background-color: #1e1e1e; padding: 15px; border-radius: 8px; border: 1px solid #333; text-align: center; }
        .stat-box h3 { margin: 0 0 10px 0; font-size: 14px; color: #aaa; text-transform: uppercase; }
        .stat-box p { margin: 0; font-size: 20px; font-weight: bold; }
        .color-revenue { color: #3498db; }
        .color-modal { color: #e67e22; }
        .color-profit { color: #2ecc71; }
        .color-weight { color: #9b59b6; }

        /* Menu Grid */
        .menu-grid { display: flex; gap: 20px; }
        .menu-card { flex: 1; background-color: #1e1e1e; padding: 25px; border-radius: 10px; border: 1px solid #333; text-align: center; text-decoration: none; color: white; transition: transform 0.2s, border-color 0.2s; }
        .menu-card:hover { transform: translateY(-5px); border-color: #2ecc71; background-color: #252525; }
        .menu-card icon { display: block; font-size: 40px; margin-bottom: 10px; }
        .menu-card h4 { margin: 0 0 8px 0; font-size: 18px; color: #ffffff; }
        .menu-card p { margin: 0; font-size: 13px; color: #888; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <div class="header-title-area">
            <h1>🌾</h1>
            <span class="welcome-text">Selamat Datang, <?= htmlspecialchars($_SESSION['username']); ?>!</span>
        </div>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>

    <div class="growth-card">
        <div class="growth-card-header">
            <h2>Status Lahan Saat Ini (Proses Tumbuh)</h2>
            <button class="btn-tambah" onclick="toggleForm()">Tambah Pantauan</button>
        </div>
        <p>Sistem Informasi Pemantauan Siklus Hidup & Durasi Tanam Palawija Sawah Keluarga secara Real-Time.</p>
        
        <div id="formTumbuh" class="form-input-tumbuh">
            <form method="POST" action="dashboard.php" style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">
                <select name="tanaman" required>
                    <option value="Jagung">🌽 Jagung (Masa Tumbuh 90 Hari)</option>
                    <option value="Singkong">🍠 Singkong (Masa Tumbuh 270 Hari)</option>
                    <option value="Ubi Merah">🍠 Ubi Merah (Masa Tumbuh 120 Hari)</option>
                    <option value="Ubi Ungu">🍠 Ubi Ungu (Masa Tumbuh 120 Hari)</option>
                    <option value="Ubi Putih">🥔 Ubi Putih (Masa Tumbuh 120 Hari)</option>
                    <option value="Bengkoang">🌱 Bengkoang (Masa Tumbuh 150 Hari)</option>
                    <option value="Kacang">🥜 Kacang Tanah (Masa Tumbuh 90 Hari)</option>
                    <option value="Kacang Bogor">🧆 Kacang Bogor (Masa Tumbuh 120 Hari)</option>
                </select>
                <select name="lahan" required>
                    <option value="Sawah Pribadi (600 m²)">Sawah Pribadi (600 m²)</option>
                    <option value="Sawah Hak Pakai (900 m²)">Sawah Hak Pakai (900 m²)</option>
                    <option value="Sawah Gadai (1500 m²)">Sawah Gadai (1500 m²)</option>
                </select>
                <input type="text" name="tahap" placeholder="Contoh: Pembungaan" required style="width: 150px;">
                <input type="number" name="hari_jalan" placeholder="Hari ke- (Contoh: 10)" min="1" required style="width: 140px;">
                <button type="submit" name="tambah_tanaman" class="btn-tambah">Simpan</button>
            </form>
        </div>

        <?php if (!empty($database_tumbuh)): ?>
            <?php foreach ($database_tumbuh as $index => $tumbuh): ?>
                <div class="growth-item" style="border-left: 4px solid <?= $tumbuh['warna_border']; ?>;">
                    <div class="growth-info">
                        <span><?= $tumbuh['icon']; ?> <strong><?= $tumbuh['tanaman']; ?></strong> - <?= $tumbuh['lahan']; ?></span>
                        <span style="color: #f1c40f; font-weight: bold;">Tahap: <?= $tumbuh['tahap']; ?> (Hari ke-<?= $tumbuh['hari_jalan']; ?>/<?= $tumbuh['hari_total']; ?>)</span>
                    </div>
                    <div class="progress-bar-container">
                        <div class="progress-fill" style="width: <?= $tumbuh['persentase']; ?>%; background: <?= $tumbuh['warna_border']; ?>;"><?= $tumbuh['persentase']; ?>%</div>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 6px;">
                        <small class="growth-footer">⏳ Estimasi panen: <strong style="color: white;" id="countdown-<?= $index; ?>"><?= $tumbuh['sisa_hari']; ?> hari lagi</strong></small>
                        
                        <a href="dashboard.php?action=hapus&id=<?= $index; ?>" class="btn-hapus" onclick="return confirm('Hapus pantauan tanaman ini?')">Hapus</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="color: #888; font-style: italic; text-align: center; padding: 20px 0;">Belum ada tanaman yang sedang ditanam. Silakan klik tombol "Tambah Pantauan" di atas!</p>
        <?php endif; ?>
    </div>

    <div class="stats-grid">
        <div class="stat-box">
            <h3>Total Hasil Panen</h3>
            <p class="color-weight"><?= number_format($total_berat, 0, ',', '.'); ?> Kg</p>
        </div>
        <div class="stat-box">
            <h3>Total Modal Keluar</h3>
            <p class="color-modal">Rp <?= number_format($total_modal, 0, ',', '.'); ?></p>
        </div>
        <div class="stat-box">
            <h3>Total Omset Kotor</h3>
            <p class="color-revenue">Rp <?= number_format($total_pendapatan, 0, ',', '.'); ?></p>
        </div>
        <div class="stat-box">
            <h3>Total Untung Bersih</h3>
            <p class="color-profit">Rp <?= number_format($total_untung_bersih, 0, ',', '.'); ?></p>
        </div>
    </div>

    <div class="menu-grid">
        <a href="data_lahan.php" class="menu-card">
            <icon>🗺️</icon>
            <h4>Data Lahan</h4>
            <p>Kelola profil status, lokasi, & luas 3 sawah keluarga.</p>
        </a>
        <a href="data_panen.php" class="menu-card">
            <icon>📊</icon>
            <h4>Riwayat Panen</h4>
            <p>Lihat catatan panen & tambah data.</p>
        </a>
        <a href="manage_user.php" class="menu-card">
            <icon>👥</icon>
            <h4>Pengaturan Akun</h4>
            <p>Manajemen hak akses akun user admin aplikasi.</p>
        </a>
        <a href="prediksi.php" class="menu-card">
            <icon>🔮</icon>
            <h4>Fitur Prediksi</h4>
            <p>Analisis regresi linier untuk memprediksi hasil panen & keuntungan.</p>
        </a>
        <a href="statistik.php" class="menu-card">
            <icon>📊</icon>
            <h4>Statistik Hasil Panen</h4>
            <p>Lihat visualisasi data statistik hasil panen.</p>
        </a>
    </div>
</div>

<script>
    // Fungsi untuk memunculkan / menyembunyikan form input tanaman tumbuh
    function toggleForm() {
        var form = document.getElementById('formTumbuh');
        if (form.style.display === 'none' || form.style.display === '') {
            form.style.display = 'block';
        } else {
            form.style.display = 'none';
        }
    }

    // KEAJAIBAN JAVASCRIPT: Menghitung perpindahan hari otomatis secara real-time
    document.addEventListener("DOMContentLoaded", function() {
        const dataTumbuh = <?= json_encode($database_tumbuh); ?>;
        
        dataTumbuh.forEach((item, index) => {
            const tanggalInput = new Date(item.tanggal_input);
            const tanggalSekarang = new Date();
            
            const selisihWaktu = tanggalSekarang.getTime() - tanggalInput.getTime();
            const selisihHari = Math.floor(selisihWaktu / (1000 * 3600 * 24));
            
            if (selisihHari > 0) {
                let sisaHariBaru = item.sisa_hari - selisihHari;
                if (sisaHariBaru < 0) sisaHariBaru = 0;
                
                const element = document.getElementById(`countdown-${index}`);
                if (element) {
                    if (sisaHariBaru === 0) {
                        element.innerHTML = "<span style='color: #2ecc71; font-weight: bold;'>🎉 Siap Panen Raya!</span>";
                    } else {
                        element.innerHTML = `${sisaHariBaru} hari lagi`;
                    }
                }
            }
        });
    });
</script>

</body>
</html>