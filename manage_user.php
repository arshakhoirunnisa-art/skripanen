<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$user_file = 'users.json';
$database_user = [];
if (file_exists($user_file)) {
    $database_user = json_decode(file_get_contents($user_file), true);
}

// PROSES TAMBAH USER BARU
if (isset($_POST['tambah_user'])) {
    $username_baru = trim($_POST['username']);
    $password_baru = trim($_POST['password']);

    // Cek apakah username sudah terpakai
    $ada = false;
    foreach ($database_user as $u) {
        if ($u['username'] === $username_baru) {
            $ada = true;
            break;
        }
    }

    if ($ada) {
        echo "<script>alert('Username sudah terdaftar!');</script>";
    } else {
        $database_user[] = [
            "username" => $username_baru,
            "password" => $password_baru
        ];
        file_put_contents($user_file, json_encode($database_user, JSON_PRETTY_PRINT));
        echo "<script>alert('User baru berhasil ditambahkan!'); window.location.href='manage_user.php';</script>";
        exit;
    }
}

// PROSES HAPUS USER
if (isset($_GET['hapus'])) {
    $username_hapus = $_GET['hapus'];
    
    // Jangan biarkan admin yang sedang login menghapus dirinya sendiri
    if ($username_hapus === $_SESSION['username']) {
        echo "<script>alert('Kamu tidak bisa menghapus akunmu sendiri yang sedang aktif!'); window.location.href='manage_user.php';</script>";
        exit;
    }

    foreach ($database_user as $key => $u) {
        if ($u['username'] === $username_hapus) {
            unset($database_user[$key]);
            break;
        }
    }
    $database_user = array_values($database_user);
    file_put_contents($user_file, json_encode($database_user, JSON_PRETTY_PRINT));
    echo "<script>alert('User berhasil dihapus!'); window.location.href='manage_user.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pengaturan Pengguna</title>
    <style>
        body { background-color: #121212; color: white; font-family: sans-serif; margin: 20px; }
        .wrapper { max-width: 900px; margin: 0 auto; display: flex; gap: 20px; }
        .box { background-color: #1e1e1e; padding: 20px; border-radius: 10px; border: 1px solid #333; }
        .form-box { flex: 1; }
        .table-box { flex: 1.5; }
        h2, h3 { color: #3498db; border-bottom: 2px solid #333; padding-bottom: 10px; margin-top: 0; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #bbb; font-size: 14px; }
        input[type="text"], input[type="password"] { width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #444; background-color: #2a2a2a; color: white; box-sizing: border-box; }
        input:focus { border-color: #3498db; outline: none; }
        .btn { background-color: #3498db; color: white; font-weight: bold; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer; width: 100%; }
        .btn:hover { background-color: #2980b9; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 14px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #444; }
        th { background-color: #2a2a2a; color: #3498db; }
        .back-btn { display: inline-block; margin-bottom: 20px; padding: 10px 15px; background-color: #555; color: white; text-decoration: none; border-radius: 5px; font-size: 14px; }
        .back-btn:hover { background-color: #777; }
    </style>
</head>
<body>

<div style="max-width: 900px; margin: 0 auto;">
    <a href="dashboard.php" class="back-btn">Kembali ke Dashboard</a>
</div>

<div class="wrapper">
    <div class="box form-box">
        <h3>Tambah Akun</h3>
        <form action="" method="POST">
            <div class="form-group">
                <label>Username Login:</label>
                <input type="text" name="username" required placeholder="Contoh: ayah_sawah">
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" required placeholder="******">
            </div>
            <button type="submit" name="tambah_user" class="btn">Simpan Pengguna</button>
        </form>
    </div>

    <div class="box table-box">
        <h2>Daftar Pengguna Sistem</h2>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Username</th>
                    <th>Password (Plain)</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                foreach ($database_user as $user) {
                    echo "<tr>";
                    echo "<td>" . $no++ . "</td>";
                    echo "<td>" . htmlspecialchars($user['username'] ?? '') . "</td>";
                    echo "<td><code>" . htmlspecialchars($user['password'] ?? '') . "</code></td>";
                    
                    // Tombol hapus aktif jika bukan user yang sedang login
                    if (($user['username'] ?? '') !== $_SESSION['username']) {
                        echo "<td><a href='manage_user.php?hapus=" . urlencode($user['username'] ?? '') . "' onclick='return confirm(\"Yakin hapus pengguna ini?\")' style='color: #e74c3c; text-decoration: none;'>Hapus</a></td>";
                    } else {
                        echo "<td><span style='color: #888; font-style: italic;'>Sedang Aktif</span></td>";
                    }
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>