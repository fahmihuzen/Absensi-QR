<?php
session_start();
include 'config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $default_password = "password123"; // Password default untuk reset

    $query = "UPDATE users SET password = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $default_password, $id);
    
    if ($stmt->execute()) {
        header("Location: superadmin.php#users");
        exit();
    }
}

header("Location: superadmin.php#users");
?> 