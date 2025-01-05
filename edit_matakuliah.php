<?php
session_start();
include 'config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$query = "SELECT * FROM matakuliah WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$matakuliah = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode_mk = $_POST['kode_mk'];
    $nama_mk = $_POST['nama_mk'];
    $sks = $_POST['sks'];

    $query = "UPDATE matakuliah SET kode_mk = ?, nama_mk = ?, sks = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssii", $kode_mk, $nama_mk, $sks, $id);

    if ($stmt->execute()) {
        header("Location: superadmin.php#matakuliah");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Matakuliah</title>
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
        .form-group input {
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
        <h2>Edit Matakuliah</h2>
        <form method="POST">
            <div class="form-group">
                <label>Kode Matakuliah</label>
                <input type="text" name="kode_mk" value="<?php echo $matakuliah['kode_mk']; ?>" required>
            </div>
            <div class="form-group">
                <label>Nama Matakuliah</label>
                <input type="text" name="nama_mk" value="<?php echo $matakuliah['nama_mk']; ?>" required>
            </div>
            <div class="form-group">
                <label>SKS</label>
                <input type="number" name="sks" value="<?php echo $matakuliah['sks']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</body>
</html> 