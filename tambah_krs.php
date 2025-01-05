<?php
session_start();
include 'config.php';

// Cek apakah user sudah login sebagai admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Proses form jika ada POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mahasiswa_id = $_POST['mahasiswa_id'];
    $jadwal_id = $_POST['jadwal_id'];
    $semester = $_POST['semester'];
    $tahun_ajaran = $_POST['tahun_ajaran'];

    $query = "INSERT INTO krs (mahasiswa_id, jadwal_id, semester, tahun_ajaran) 
              VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiss", $mahasiswa_id, $jadwal_id, $semester, $tahun_ajaran);

    if ($stmt->execute()) {
        header("Location: superadmin.php#krs");
        exit();
    } else {
        $error = "Gagal menambahkan data KRS";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah KRS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        select, input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
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

        .btn-secondary {
            background-color: #6c757d;
            color: white;
            text-decoration: none;
            display: inline-block;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Tambah KRS</h2>
        <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="mahasiswa_id">Mahasiswa</label>
                <select name="mahasiswa_id" required>
                    <option value="">Pilih Mahasiswa</option>
                    <?php
                    $query = "SELECT id, nama, nim FROM users WHERE role = 'mahasiswa'";
                    $result = $conn->query($query);
                    while($row = $result->fetch_assoc()) {
                        echo "<option value='".$row['id']."'>".$row['nim']." - ".$row['nama']."</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="jadwal_id">Jadwal Matakuliah</label>
                <select name="jadwal_id" required>
                    <option value="">Pilih Jadwal</option>
                    <?php
                    $query = "SELECT j.id, m.nama_mk, u.nama as nama_dosen, j.hari, j.jam_mulai 
                             FROM jadwal j 
                             JOIN matakuliah m ON j.matakuliah_id = m.id 
                             JOIN users u ON j.dosen_id = u.id";
                    $result = $conn->query($query);
                    while($row = $result->fetch_assoc()) {
                        echo "<option value='".$row['id']."'>".$row['nama_mk']." - ".$row['nama_dosen']." (".$row['hari']." ".$row['jam_mulai'].")</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="semester">Semester</label>
                <select name="semester" required>
                    <option value="">Pilih Semester</option>
                    <?php
                    for($i = 1; $i <= 8; $i++) {
                        echo "<option value='$i'>Semester $i</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="tahun_ajaran">Tahun Ajaran</label>
                <input type="text" name="tahun_ajaran" placeholder="Contoh: 2023/2024" required>
            </div>

            <a href="superadmin.php#krs" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</body>
</html> 