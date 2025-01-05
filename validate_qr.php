<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

// Aktifkan error logging
ini_set('display_errors', 1);
ini_set('log_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

try {
    if (!isset($_POST['qr_data']) || !isset($_POST['jadwal_id'])) {
        throw new Exception('Data tidak lengkap');
    }

    $qr_data = $_POST['qr_data'];
    $jadwal_id = intval($_POST['jadwal_id']);
    
    // Debug log untuk melihat data yang diterima
    error_log("Raw QR Data: " . print_r($_POST, true));
    
    // Decode QR data
    $decoded_data = json_decode($qr_data, true);
    error_log("Decoded QR Data: " . print_r($decoded_data, true));
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Format QR tidak valid: ' . json_last_error_msg());
    }
    
    // Validasi komponen QR
    if (!isset($decoded_data['jadwal_id']) || 
        !isset($decoded_data['timestamp']) || 
        !isset($decoded_data['hash'])) {
        throw new Exception('Komponen QR tidak lengkap');
    }
    
    // Validasi jadwal ID
    $qr_jadwal_id = intval($decoded_data['jadwal_id']);
    if ($qr_jadwal_id !== $jadwal_id) {
        throw new Exception('Jadwal tidak sesuai');
    }
    
    // Ambil data jadwal
    $stmt = $conn->prepare("
        SELECT j.last_qr_timestamp
        FROM jadwal j 
        JOIN krs k ON j.id = k.jadwal_id 
        WHERE j.id = ? 
        AND k.mahasiswa_id = ?
    ");
    
    if (!$stmt) {
        throw new Exception('Database error: ' . $conn->error);
    }
    
    $stmt->bind_param("ii", $jadwal_id, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception('Anda tidak terdaftar dalam mata kuliah ini');
    }
    
    $jadwal = $result->fetch_assoc();
    
    // Debug log untuk timestamp
    error_log("QR timestamp: " . $decoded_data['timestamp']);
    error_log("DB timestamp: " . $jadwal['last_qr_timestamp']);
    
    // Validasi timestamp QR dengan last_qr_timestamp
    if ($decoded_data['timestamp'] != $jadwal['last_qr_timestamp']) {
        throw new Exception('QR Code tidak valid atau sudah kadaluarsa');
    }
    
    // Validasi hash
    $expected_hash = hash('sha256', $qr_jadwal_id . $decoded_data['timestamp'] . 'secret_key');
    if ($decoded_data['hash'] !== $expected_hash) {
        throw new Exception('QR signature tidak valid');
    }
    
    // Cek duplikasi absensi
    $today = date('Y-m-d');
    $stmt = $conn->prepare("
        SELECT id FROM absensi 
        WHERE jadwal_id = ? 
        AND mahasiswa_id = ? 
        AND DATE(waktu) = ?
    ");
    
    $stmt->bind_param("iis", $jadwal_id, $_SESSION['user_id'], $today);
    $stmt->execute();
    
    if ($stmt->get_result()->num_rows > 0) {
        throw new Exception('Anda sudah melakukan absensi hari ini');
    }
    
    // Catat absensi
    $stmt = $conn->prepare("
        INSERT INTO absensi (jadwal_id, mahasiswa_id, waktu) 
        VALUES (?, ?, NOW())
    ");
    
    if (!$stmt) {
        throw new Exception('Gagal mencatat absensi');
    }
    
    $stmt->bind_param("ii", $jadwal_id, $_SESSION['user_id']);
    
    if (!$stmt->execute()) {
        throw new Exception('Gagal menyimpan absensi');
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Absensi berhasil dicatat'
    ]);
    
} catch (Exception $e) {
    error_log("Validation error: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>