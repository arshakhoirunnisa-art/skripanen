<?php
session_start();

// Pastikan user sudah login sebelum melihat statistik
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Statistik Hasil Panen - SISawah Keluarga</title>
    <style>
        body {
            background-color: #121212;
            color: #ffffff;
            font-family: sans-serif;
            margin: 0;
            padding: 30px;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
        }
        .btn-kembali {
            background-color: #333;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            display: inline-block;
            margin-bottom: 20px;
            transition: 0.3s;
            border: 1px solid #444;
        }
        .btn-kembali:hover {
            background-color: #444;
        }
        .box-statistik {
            background-color: #1a1a1a;
            border: 1px solid #2d2d2d;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.5);
        }
    </style>
</head>
<body>

<div class="container">
    <a href="dashboard.php" class="btn-kembali">Kembali ke Dashboard</a>

    <div class="box-statistik">
        <h2 style="color: #4caf50; margin-top: 0; text-align: center; margin-bottom: 5px;">Grafik Statistik Tren Hasil Panen</h2>
        <p style="color: #888888; text-align: center; font-size: 14px; margin-bottom: 30px;">
            Visualisasi perbandingan tren produksi komoditas utama Sawah Keluarga (Data Historis & Hasil Prediksi ARIMA)
        </p>
        
        <div style="background-color: #222; padding: 15px; border-radius: 8px; border: 1px solid #333;">
            <canvas id="grafikPanen" width="400" height="180"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('grafikPanen').getContext('2d');
    
    // Konfigurasi Chart.js yang Elegan dan Menyatu dengan Tema Gelap
    const grafikPanen = new Chart(ctx, {
        type: 'line', // Jenis grafik garis (Tren)
        data: {
            labels: ['2022', '2023', '2024', '2025', '2026 (Prediksi)'], // Sumbu X (Tahun)
            datasets: [
                {
                    label: 'Jagung (KG)',
                    data: [174, 540, 549, 637, 680], // Akumulasi real panen data CSV + ARIMA
                    borderColor: '#2ecc71',
                    backgroundColor: 'rgba(46, 204, 113, 0.1)',
                    borderWidth: 3,
                    tension: 0.3,
                    pointBackgroundColor: '#2ecc71'
                },
                {
                    label: 'Singkong (KG)',
                    data: [0, 0, 0, 238, 255], // 2022-2024 diset 0 karena baru ditanam 2025! LOGIS & SINKRON!
                    borderColor: '#3498db',
                    backgroundColor: 'rgba(52, 152, 219, 0.1)',
                    borderWidth: 3,
                    tension: 0.3,
                    pointBackgroundColor: '#3498db'
                },
                {
                    label: 'Ubi Merah (KG)',
                    data: [0, 128, 534, 750, 810], // Sesuai kemunculan di riwayat panen CSV
                    borderColor: '#e74c3c', 
                    backgroundColor: 'rgba(231, 76, 60, 0.1)',
                    borderWidth: 3,
                    tension: 0.3,
                    pointBackgroundColor: '#e74c3c'
                },
                {
                    label: 'Ubi Ungu (KG)',
                    data: [0, 0, 515, 264, 290], 
                    borderColor: '#9b59b6', 
                    backgroundColor: 'rgba(155, 89, 182, 0.1)',
                    borderWidth: 3,
                    tension: 0.3,
                    pointBackgroundColor: '#9b59b6'
                },
                {
                    label: 'Ubi Putih (KG)',
                    data: [0, 342, 0, 0, 120], 
                    borderColor: '#f1c40f', 
                    backgroundColor: 'rgba(241, 196, 15, 0.1)',
                    borderWidth: 3,
                    tension: 0.3,
                    pointBackgroundColor: '#f1c40f'
                },
                {
                    label: 'Bengkoang (KG)',
                    data: [430, 1321, 1343, 1300, 1450], 
                    borderColor: '#1abc9c', 
                    backgroundColor: 'rgba(26, 188, 156, 0.1)',
                    borderWidth: 3,
                    tension: 0.3,
                    pointBackgroundColor: '#1abc9c'
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    labels: {
                        color: '#ffffff'
                    }
                }
            },
            scales: {
                x: {
                    grid: { color: '#2d2d2d' },
                    ticks: { color: '#ffffff' }
                },
                y: {
                    grid: { color: '#2d2d2d' },
                    ticks: { color: '#ffffff' },
                    beginAtZero: true // Diubah ke true agar grafik komoditas yang mulai dari 0 kelihatan rapi landasannya
                }
            }
        }
    });
</script>

</body>
</html>