<?php
session_start();
require_once 'config.php';
require_once 'vendor/autoload.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

header('Content-Type: application/json');

// Validasi session dan role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'dosen') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

// Validasi parameter yang diterima
if (!isset($_POST['jadwal_id']) || !isset($_POST['timestamp'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
    exit();
}

try {
    $jadwal_id = intval($_POST['jadwal_id']);
    $timestamp = intval($_POST['timestamp']);
    $user_id = $_SESSION['user_id'];

    // Validasi jadwal dosen
    $stmt = $conn->prepare("SELECT id FROM jadwal WHERE id = ? AND dosen_id = ?");
    $stmt->bind_param("ii", $jadwal_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid schedule']);
        exit();
    }

    // Generate data QR dengan format yang sama dengan yang diharapkan di validate_qr.php
    $qrData = [
        'jadwal_id' => $jadwal_id,
        'timestamp' => $timestamp,
        'hash' => hash('sha256', $jadwal_id . $timestamp . 'secret_key')
    ];

    // Buat QR Code dengan data JSON yang sudah di-encode
    $qrCode = new QrCode(json_encode($qrData));
    $writer = new PngWriter();
    $result = $writer->write($qrCode);
    
    // Update database dengan timestamp terbaru
    $stmt = $conn->prepare("UPDATE jadwal SET last_qr_timestamp = ? WHERE id = ?");
    $stmt->bind_param("ii", $timestamp, $jadwal_id);
    $stmt->execute();

    echo json_encode([
        'success' => true,
        'qr_image' => $result->getDataUri(),
        'message' => 'QR Code berhasil dibuat'
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?> 