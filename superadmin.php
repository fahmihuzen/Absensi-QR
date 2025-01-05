<?php
session_start();
include 'config.php';

// Cek apakah user sudah login sebagai admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Superadmin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #343a40;
            position: fixed;
            left: 0;
            top: 0;
            padding: 20px;
        }

        .sidebar h2 {
            color: white;
            margin-bottom: 30px;
            text-align: center;
        }

        .menu-item {
            padding: 15px;
            color: white;
            text-decoration: none;
            display: block;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .menu-item:hover {
            background-color: #4b545c;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
        }

        .card {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f4f4f4;
        }

        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 2px;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-warning {
            background-color: #ffc107;
            color: black;
        }

        .filter-section {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .form-control {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .active {
            background-color: #4b545c;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .btn-warning:hover {
            background-color: #e0a800;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            
            .content {
                margin-left: 0;
            }
            
            table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Dashboard Admin</h2>
        <a href="#jadwal" class="menu-item">Jadwal</a>
        <a href="#krs" class="menu-item">KRS</a>
        <a href="#matakuliah" class="menu-item">Matakuliah</a>
        <a href="#users" class="menu-item">Users</a>
        <a href="#absensi" class="menu-item">Absensi</a>
        <a href="logout.php" class="menu-item">Logout</a>
    </div>

    <div class="content">
        <!-- Jadwal Section -->
        <div class="card" id="jadwal">
            <h2>Manajemen Jadwal</h2>
            <a href="tambah_jadwal.php" class="btn btn-primary">Tambah Jadwal</a>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Matakuliah</th>
                    <th>Dosen</th>
                    <th>Hari</th>
                    <th>Jam Mulai</th>
                    <th>Jam Selesai</th>
                    <th>Ruangan</th>
                    <th>Aksi</th>
                </tr>
                <?php
                $query = "SELECT j.*, m.nama_mk, u.nama as nama_dosen 
                         FROM jadwal j 
                         JOIN matakuliah m ON j.matakuliah_id = m.id 
                         JOIN users u ON j.dosen_id = u.id";
                $result = $conn->query($query);
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>".$row['id']."</td>";
                    echo "<td>".$row['nama_mk']."</td>";
                    echo "<td>".$row['nama_dosen']."</td>";
                    echo "<td>".$row['hari']."</td>";
                    echo "<td>".$row['jam_mulai']."</td>";
                    echo "<td>".$row['jam_selesai']."</td>";
                    echo "<td>".$row['ruangan']."</td>";
                    echo "<td>
                            <a href='edit_jadwal.php?id=".$row['id']."' class='btn btn-warning'>Edit</a>
                            <a href='hapus_jadwal.php?id=".$row['id']."' class='btn btn-danger' onclick='return confirm(\"Yakin ingin menghapus?\")'>Hapus</a>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>

        <!-- KRS Section -->
        <div class="card" id="krs">
            <h2>Manajemen KRS</h2>
            <a href="tambah_krs.php" class="btn btn-primary">Tambah KRS</a>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Mahasiswa</th>
                    <th>Jadwal</th>
                    <th>Semester</th>
                    <th>Tahun Ajaran</th>
                    <th>Aksi</th>
                </tr>
                <?php
                $query = "SELECT k.*, u.nama as nama_mahasiswa, m.nama_mk 
                         FROM krs k 
                         JOIN users u ON k.mahasiswa_id = u.id 
                         JOIN jadwal j ON k.jadwal_id = j.id 
                         JOIN matakuliah m ON j.matakuliah_id = m.id";
                $result = $conn->query($query);
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>".$row['id']."</td>";
                    echo "<td>".$row['nama_mahasiswa']."</td>";
                    echo "<td>".$row['nama_mk']."</td>";
                    echo "<td>".$row['semester']."</td>";
                    echo "<td>".$row['tahun_ajaran']."</td>";
                    echo "<td>
                            <a href='edit_krs.php?id=".$row['id']."' class='btn btn-warning'>Edit</a>
                            <a href='hapus_krs.php?id=".$row['id']."' class='btn btn-danger' onclick='return confirm(\"Yakin ingin menghapus?\")'>Hapus</a>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>

        <!-- Matakuliah Section -->
        <div class="card" id="matakuliah">
            <h2>Manajemen Matakuliah</h2>
            <a href="tambah_matakuliah.php" class="btn btn-primary">Tambah Matakuliah</a>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Kode MK</th>
                    <th>Nama Matakuliah</th>
                    <th>SKS</th>
                    <th>Aksi</th>
                </tr>
                <?php
                $query = "SELECT * FROM matakuliah";
                $result = $conn->query($query);
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>".$row['id']."</td>";
                    echo "<td>".$row['kode_mk']."</td>";
                    echo "<td>".$row['nama_mk']."</td>";
                    echo "<td>".$row['sks']."</td>";
                    echo "<td>
                            <a href='edit_matakuliah.php?id=".$row['id']."' class='btn btn-warning'>Edit</a>
                            <a href='hapus_matakuliah.php?id=".$row['id']."' class='btn btn-danger' onclick='return confirm(\"Yakin ingin menghapus?\")'>Hapus</a>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>

        <!-- Users Section -->
        <div class="card" id="users">
            <h2>Manajemen Users</h2>
            <a href="tambah_user.php" class="btn btn-primary">Tambah User</a>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>NIM</th>
                    <th>Aksi</th>
                </tr>
                <?php
                $query = "SELECT * FROM users";
                $result = $conn->query($query);
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>".$row['id']."</td>";
                    echo "<td>".$row['nama']."</td>";
                    echo "<td>".$row['username']."</td>";
                    echo "<td>".$row['role']."</td>";
                    echo "<td>".$row['nim']."</td>";
                    echo "<td>
                            <a href='edit_user.php?id=".$row['id']."' class='btn btn-warning'>Edit</a>
                            <a href='hapus_user.php?id=".$row['id']."' class='btn btn-danger' onclick='return confirm(\"Yakin ingin menghapus?\")'>Hapus</a>
                            <a href='reset_password.php?id=".$row['id']."' class='btn btn-primary'>Reset Password</a>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>

        <!-- Absensi Section -->
        <div class="card" id="absensi">
            <h2>Manajemen Absensi</h2>
            <div class="filter-section" style="margin-bottom: 20px;">
                <form method="GET" action="" style="display: flex; gap: 10px;">
                    <select name="jadwal_filter" class="form-control">
                        <option value="">Pilih Jadwal</option>
                        <?php
                        $query = "SELECT j.id, m.nama_mk, j.hari, j.jam_mulai 
                                 FROM jadwal j 
                                 JOIN matakuliah m ON j.matakuliah_id = m.id";
                        $result = $conn->query($query);
                        while($row = $result->fetch_assoc()) {
                            echo "<option value='".$row['id']."'>".$row['nama_mk']." - ".$row['hari']." ".$row['jam_mulai']."</option>";
                        }
                        ?>
                    </select>
                    <input type="date" name="tanggal_filter" class="form-control">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </form>
            </div>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Jadwal</th>
                    <th>Mahasiswa</th>
                    <th>Waktu</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
                <?php
                $query = "SELECT a.*, u.nama as nama_mahasiswa, m.nama_mk, j.hari, j.jam_mulai 
                         FROM absensi a 
                         JOIN users u ON a.mahasiswa_id = u.id 
                         JOIN jadwal j ON a.jadwal_id = j.id 
                         JOIN matakuliah m ON j.matakuliah_id = m.id";
                
                // Tambahkan filter jika ada
                if(isset($_GET['jadwal_filter']) && !empty($_GET['jadwal_filter'])) {
                    $jadwal_id = $_GET['jadwal_filter'];
                    $query .= " WHERE a.jadwal_id = '$jadwal_id'";
                }
                if(isset($_GET['tanggal_filter']) && !empty($_GET['tanggal_filter'])) {
                    $tanggal = $_GET['tanggal_filter'];
                    $query .= isset($_GET['jadwal_filter']) ? " AND" : " WHERE";
                    $query .= " DATE(a.waktu) = '$tanggal'";
                }

                $result = $conn->query($query);
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>".$row['id']."</td>";
                    echo "<td>".$row['nama_mk']." - ".$row['hari']." ".$row['jam_mulai']."</td>";
                    echo "<td>".$row['nama_mahasiswa']."</td>";
                    echo "<td>".$row['waktu']."</td>";
                    echo "<td>".$row['status']."</td>";
                    echo "<td>
                            <a href='edit_absensi.php?id=".$row['id']."' class='btn btn-warning'>Edit</a>
                            <a href='hapus_absensi.php?id=".$row['id']."' class='btn btn-danger' onclick='return confirm(\"Yakin ingin menghapus?\")'>Hapus</a>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>

        <script>
            // Smooth scroll untuk menu
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });

            // Highlight active menu
            const menuItems = document.querySelectorAll('.menu-item');
            const sections = document.querySelectorAll('.card');

            window.addEventListener('scroll', () => {
                let current = '';
                sections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    if (pageYOffset >= sectionTop - 60) {
                        current = section.getAttribute('id');
                    }
                });

                menuItems.forEach(item => {
                    item.classList.remove('active');
                    if (item.getAttribute('href').substring(1) === current) {
                        item.classList.add('active');
                    }
                });
            });
        </script>
    </div>
</body>
</html>