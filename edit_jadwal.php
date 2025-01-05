<?php
session_start();
include 'config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$query = "SELECT * FROM jadwal WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$jadwal = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $matakuliah_id = $_POST['matakuliah_id'];
    $dosen_id = $_POST['dosen_id'];
    $hari = $_POST['hari'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];
    $ruangan = $_POST['ruangan'];

    $query = "UPDATE jadwal SET matakuliah_id = ?, dosen_id = ?, hari = ?, 
              jam_mulai = ?, jam_selesai = ?, ruangan = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iissssi", $matakuliah_id, $dosen_id, $hari, $jam_mulai, 
                      $jam_selesai, $ruangan, $id);

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
    <title>Edit Jadwal</title>
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
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Edit Jadwal</h2>
        <form method="POST">
            <div class="form-group">
                <label>Matakuliah</label>
                <select name="matakuliah_id" required>
                    <?php
                    $query = "SELECT * FROM matakuliah";
                    $result = $conn->query($query);
                    while($row = $result->fetch_assoc()) {
                        $selected = ($row['id'] == $jadwal['matakuliah_id']) ? 'selected' : '';
                        echo "<option value='".$row['id']."' ".$selected.">".$row['nama_mk']."</option>";
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
                        $selected = ($row['id'] == $jadwal['dosen_id']) ? 'selected' : '';
                        echo "<option value='".$row['id']."' ".$selected.">".$row['nama']."</option>";
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
                        $selected = ($h == $jadwal['hari']) ? 'selected' : '';
                        echo "<option value='".$h."' ".$selected.">".$h."</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>Jam Mulai</label>
                <input type="time" name="jam_mulai" value="<?php echo $jadwal['jam_mulai']; ?>" required>
            </div>
            <div class="form-group">
                <label>Jam Selesai</label>
                <input type="time" name="jam_selesai" value="<?php echo $jadwal['jam_selesai']; ?>" required>
            </div>
            <div class="form-group">
                <label>Ruangan</label>
                <input type="text" name="ruangan" value="<?php echo $jadwal['ruangan']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</body>
</html> 