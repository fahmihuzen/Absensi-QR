<?php
session_start();
include 'config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$query = "SELECT a.*, u.nama as nama_mahasiswa, m.nama_mk, j.hari, j.jam_mulai 
          FROM absensi a 
          JOIN users u ON a.mahasiswa_id = u.id 
          JOIN jadwal j ON a.jadwal_id = j.id 
          JOIN matakuliah m ON j.matakuliah_id = m.id 
          WHERE a.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$absensi = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = $_POST['status'];
    $waktu = $_POST['waktu'];

    $query = "UPDATE absensi SET status = ?, waktu = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssi", $status, $waktu, $id);

    if ($stmt->execute()) {
        header("Location: superadmin.php#absensi");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Absensi</title>
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
        .info-group {
            margin-bottom: 15px;
            padding: 10px;
            background: #f8f9fa;
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
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Edit Absensi</h2>
        
        <div class="info-group">
            <p><strong>Mahasiswa:</strong> <?php echo $absensi['nama_mahasiswa']; ?></p>
            <p><strong>Matakuliah:</strong> <?php echo $absensi['nama_mk']; ?></p>
            <p><strong>Jadwal:</strong> <?php echo $absensi['hari'] . ' ' . $absensi['jam_mulai']; ?></p>
        </div>

        <form method="POST">
            <div class="form-group">
                <label>Status</label>
                <select name="status" required>
                    <option value="Hadir" <?php echo ($absensi['status'] == 'Hadir') ? 'selected' : ''; ?>>Hadir</option>
                    <option value="Sakit" <?php echo ($absensi['status'] == 'Sakit') ? 'selected' : ''; ?>>Sakit</option>
                    <option value="Alfa" <?php echo ($absensi['status'] == 'Alfa') ? 'selected' : ''; ?>>Alfa</option>
                    <option value="Dispensasi" <?php echo ($absensi['status'] == 'Dispensasi') ? 'selected' : ''; ?>>Dispensasi</option>
                </select>
            </div>
            <div class="form-group">
                <label>Waktu</label>
                <input type="datetime-local" name="waktu" 
                       value="<?php echo date('Y-m-d\TH:i', strtotime($absensi['waktu'])); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</body>
</html> 