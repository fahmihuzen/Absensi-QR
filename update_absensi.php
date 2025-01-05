<?php
session_start();
include 'config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mahasiswa_id = $_POST['mahasiswa_id'];
    $jadwal_id = $_POST['jadwal_id'];
    $tanggal = $_POST['tanggal'];
    $status = $_POST['status'];
    
    $query = "INSERT INTO absensi (mahasiswa_id, jadwal_id, waktu, status) 
              VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $waktu = $tanggal . ' ' . date('H:i:s');
    $stmt->bind_param("iiss", $mahasiswa_id, $jadwal_id, $waktu, $status);
    
    if ($stmt->execute()) {
        header("Location: superadmin.php?jadwal_filter=$jadwal_id&tanggal_filter=$tanggal#absensi");
    } else {
        echo "Error: " . $stmt->error;
    }
}
?> 