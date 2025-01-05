<?php
session_start();
include 'config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $matakuliah_id = $_POST['matakuliah_id'];
    $dosen_id = $_POST['dosen_id'];
    $hari = $_POST['hari'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];
    $ruangan = $_POST['ruangan'];

    $query = "INSERT INTO jadwal (matakuliah_id, dosen_id, hari, jam_mulai, jam_selesai, ruangan) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iissss", $matakuliah_id, $dosen_id, $hari, $jam_mulai, $jam_selesai, $ruangan);

    if ($stmt->execute()) {
        header("Location: superadmin.php#jadwal");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Jadwal</title>
    <style>
        .form-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .btn {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        .alert-warning {
            color: #856404;
            background-color: #fff3cd;
            border-color: #ffeeba;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Tambah Jadwal Baru</h2>
        
        <?php
        // Cek ketersediaan data matakuliah dan dosen
        $check_mk = $conn->query("SELECT COUNT(*) as count FROM matakuliah");
        $check_dosen = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'dosen'");
        $mk_count = $check_mk->fetch_assoc()['count'];
        $dosen_count = $check_dosen->fetch_assoc()['count'];

        if ($mk_count == 0 || $dosen_count == 0) {
            echo '<div class="alert alert-warning">';
            if ($mk_count == 0) echo "Harap tambahkan matakuliah terlebih dahulu.<br>";
            if ($dosen_count == 0) echo "Harap tambahkan dosen terlebih dahulu.";
            echo '</div>';
        }
        ?>

        <form method="POST">
            <div class="form-group">
                <label>Matakuliah</label>
                <select name="matakuliah_id" required>
                    <?php
                    $query = "SELECT * FROM matakuliah";
                    $result = $conn->query($query);
                    while($row = $result->fetch_assoc()) {
                        echo "<option value='".$row['id']."'>".$row['nama_mk']."</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>Dosen</label>
                <select name="dosen_id" required>
                    <?php
                    $query = "SELECT * FROM users WHERE role = 'dosen'";
                    $result = $conn->query($query);
                    while($row = $result->fetch_assoc()) {
                        echo "<option value='".$row['id']."'>".$row['nama']."</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>Hari</label>
                <select name="hari" required>
                    <?php
                    $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                    foreach($hari as $h) {
                        echo "<option value='".$h."'>".$h."</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>Jam Mulai</label>
                <input type="time" name="jam_mulai" required>
            </div>
            <div class="form-group">
                <label>Jam Selesai</label>
                <input type="time" name="jam_selesai" required>
            </div>
            <div class="form-group">
                <label>Ruangan</label>
                <input type="text" name="ruangan" required>
            </div>
            <button type="submit" class="btn btn-primary" <?php if ($mk_count == 0 || $dosen_count == 0) echo 'disabled'; ?>>
                Simpan
            </button>
        </form>
    </div>
</body>
</html> 