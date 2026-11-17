<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?> 
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Fitur Prediksi</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
            background-color: #121212; 
            color: #fdfdfd;
            min-height: 100vh;
            display: flex;
            flex-direction: column; 
        }

        h2, h3, label {
        color: #ffffff !important; /* Memaksa judul form dan label input jadi PUTIH CLEAR */
        }

        /* BAR ATAS TANPA GARIS PEMBATAS */
        .top-bar {
            width: 100%;
            background-color: #121212; /* Warnanya disamakan dengan body biar menyatu tanpa sekat */
            padding: 20px 30px;
            box-sizing: border-box;
            display: flex;
            align-items: center;
        }

        /* TOMBOL KEMBALI TIDAK BOLD & LEBIH CLEAN */
        .back-btn {
            color: #a0a0a0;
            text-decoration: none;
            font-size: 15px;
            font-weight: normal; /* Font-nya tidak bold lagi Seng */
            font-family: sans-serif;
            padding: 8px 16px;
            border-radius: 6px;
            background-color: #1a1a1a;
            transition: 0.3s;
            border: 1px solid #252525;
        }

        .back-btn:hover {
            color: #ffffff;
            background-color: #252525;
            border-color: #333;
        }

        .area-konten {
            flex-grow: 1;
            display: flex;
            justify-content: center; 
            align-items: center;    
            padding: 20px;
            box-sizing: border-box;
        }

        .card {
            background: #1c1c1c; 
            padding: 40px; 
            border-radius: 12px; 
            box-shadow: 0 8px 25px rgba(0,0,0,0.7);
            width: 100%;
            max-width: 800px; 
            box-sizing: border-box;
            border: 1px solid #2d2d2d;
        }
    </style>
</head>
<body>

    <div class="top-bar">
        <a href="dashboard.php" class="back-btn">Kembali ke Dashboard</a>
    </div>

    <div class="area-konten">
        <div class="card">
            <h2 style="color: #ffffff; margin-top: 0; margin-bottom: 5px; font-family: sans-serif;">Fitur Prediksi Hasil Panen & Finansial</h2>
            <p style="color: #888888; margin-bottom: 25px; font-size: 14px; font-family: sans-serif;">Sistem Analisis Regresi Linier (Python Flask Integrated)</p>
            
            <iframe 
                src="http://127.0.0.1:5000/" 
                style="width: 100% !important; height: 500px !important; border: none; display: block; overflow: hidden;"
                scrolling="no">
            </iframe>
        </div>
    </div>

</body>
</html>