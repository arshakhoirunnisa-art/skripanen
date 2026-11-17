<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $panen_file = 'panencsv.json';
    
    // 1. Ambil data lama yang sudah ada di panencsv.json
    $database_panen = [];
    if (file_exists($panen_file)) {
        $database_panen = json_decode(file_get_contents($panen_file), true);
    }

    // 2. Hitung nomor urut NO otomatis (meneruskan nomor terakhir)
    $next_no = 1;
    if (!empty($database_panen)) {
        $last_item = end($database_panen);
        $next_no = (int)($last_item['NO'] ?? 0) + 1;
    }

    // 3. Tangkap data dari form input dan rapikan formatnya
    // Untuk tanggal, kita ubah format YYYY-MM-DD bawaan HTML menjadi DD/MM/YYYY biar seragam sama data Excel-mu
    $tanggal_input = $_POST['tanggal'];
    $tanggal_formatted = date('d/m/Y', strtotime($tanggal_input));

    $data_baru = [
        "NO" => $next_no,
        "ID_LAHAN" => $_POST['id_lahan'],
        "TANGGAL" => $tanggal_formatted,
        "JENIS TANAMAN" => strtoupper($_POST['jenis_tanaman']), // Otomatis jadi huruf kapital
        "LUAS LAHAN" => $_POST['luas_lahan'],
        "BERAT KG" => (int)$_POST['berat_kg'],
        "TOTAL PENGELUARAN" => $_POST['total_pengeluaran'],
        "UANG PANEN" => $_POST['uang_panen'],
        "KETERANGAN" => $_POST['keterangan']
    ];

    // 4. Masukkan data baru ke baris paling bawah array
    $database_panen[] = $data_baru;

    // 5. Simpan kembali seluruh data ke dalam file panencsv.json
    file_put_contents($panen_file, json_encode($database_panen, JSON_PRETTY_PRINT));

    // 6. Alihkan halaman kembali ke riwayat panen dengan sukses
    echo "<script>
            alert('Data panen nomor " . $next_no . " berhasil ditambahkan!');
            window.location.href = 'data_panen.php';
          </script>";
    exit;
} else {
    header("Location: data_panen.php");
    exit;
}