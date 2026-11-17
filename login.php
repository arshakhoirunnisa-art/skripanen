<?php
session_start();
include "config.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $login_sukses = false;

    // Mencari kecocokan username dan password di dalam data JSON
    if (isset($database_users)) {
        foreach ($database_users as $user) {
            if ($user['username'] === $username && $user['password'] === $password) {
                $login_sukses = true;
                $_SESSION['username'] = $user['username'];
                break;
            }
        }
    }

    if ($login_sukses) {
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "❌ Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - SIsawah</title>
    <style>
        body { 
            background-color: #121212; 
            color: white; 
            font-family: sans-serif; 
            margin: 0; 
            height: 100vh; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
        }
        .login-box { 
            background-color: #1e1e1e; 
            padding: 40px; 
            border-radius: 12px; 
            box-shadow: 0px 8px 25px rgba(0, 0, 0, 0.6); 
            width: 320px; 
            text-align: center; 
            border: 1px solid #2d2d2d; 
        }
        .login-box h2 {
            margin-top: 0;
            margin-bottom: 5px;
            color: #ffffff;
            font-size: 24px;
        }
        .login-box p.subtitle {
            color: #888888;
            font-size: 13px;
            margin-top: 0;
            margin-bottom: 25px;
        }
        input[type="text"], input[type="password"] { 
            width: 100%; 
            padding: 12px; 
            margin-top: 5px; 
            margin-bottom: 20px; 
            border: 1px solid #333; 
            background-color: #151515; 
            color: white; 
            border-radius: 6px; 
            box-sizing: border-box; 
            font-size: 14px;
            transition: border-color 0.2s;
        }
        input[type="text"]:focus, input[type="password"]:focus {
            border-color: #2ecc71;
            outline: none;
        }
        button { 
            width: 100%; 
            padding: 12px; 
            background-color: #2ecc71; /* Diubah jadi hijau estetik senada dashboard Seng! */
            border: none; 
            border-radius: 6px; 
            color: white; 
            font-weight: bold; 
            font-size: 15px;
            cursor: pointer; 
            transition: background 0.2s;
            margin-top: 5px;
        }
        button:hover { 
            background-color: #27ae60; 
        }
        .error-msg {
            background-color: rgba(231, 76, 60, 0.1);
            border: 1px solid #e74c3c;
            color: #ff4c4c;
            padding: 10px;
            border-radius: 6px;
            font-size: 13px;
            margin-bottom: 15px;
            text-align: left;
        }
    </style>
</head>
<body>

    <div class="login-box">
        <h2>Login</h2>
        <p class="subtitle">Sistem Informasi Sawah</p>
        
        <?php if (isset($error)): ?>
            <div class="error-msg"><?= $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div style="text-align: left; margin-bottom: 15px;">
                <label style="font-size: 13px; color: #aaa;">Username</label>
                <input type="text" name="username" placeholder="Masukkan username admin" required autocomplete="off">
            </div>
            
            <div style="text-align: left; margin-bottom: 15px;">
                <label style="font-size: 13px; color: #aaa;">Password</label>
                <input type="password" name="password" placeholder="Masukkan password" required>
            </div>
            
            <button type="submit">Masuk</button>
        </form>
    </div>

</body>
</html>