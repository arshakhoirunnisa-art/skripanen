<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Memastikan ada parameter 'no' yang dikirim lewat URL tombol tadi
if (isset($_GET['no'])) {
    $no_hapus = (int)$_GET['no'];
    $panen_file = 'panencsv.json';

    if (file_exists($panen_file)) {
        // 1. Ambil data asli dari JSON
        $database_panen = json_decode(file_get_contents($panen_file), true);
        
        // 2. Cari data mana yang NO-nya cocok, lalu hapus dari array
        foreach ($database_panen as $key => $panen) {
            if ((int)$panen['NO'] === $no_hapus) {
                unset($database_panen[$key]); // Menghapus data terpilih
                break;
            }
        }

        // 3. Susun ulang array-nya biar gak ada indeks yang bolong
        $database_panen = array_values($database_panen);

        // 4. Simpan kembali data terbaru ke panencsv.json
        file_put_contents($panen_file, json_encode($database_panen, JSON_PRETTY_PRINT));

        echo "<script>
                alert('Data panen nomor " . $no_hapus . " berhasil dihapus!');
                window.location.href = 'data_panen.php';
              </script>";
        exit;
    }
} else {
    header("Location: data_panen.php");
    exit;
}