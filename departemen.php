<?php
require_once 'koneksi.php';

// Proses Tambah
if(isset($_POST['tambah'])) {
    $nama_departemen = $_POST['nama_departemen'];
    $alamat_departemen = $_POST['alamat_departemen'];
    
    $query = "INSERT INTO departemen (nama_departemen, alamat_departemen) VALUES ('$nama_departemen', '$alamat_departemen')";
    if(mysqli_query($koneksi, $query)) {
        $success = "Data berhasil ditambahkan!";
    } else {
        $error = "Error: " . mysqli_error($koneksi);
    }
}

// Proses Edit
if(isset($_POST['edit'])) {
    $id_departemen = $_POST['id_departemen'];
    $nama_departemen = $_POST['nama_departemen'];
    $alamat_departemen = $_POST['alamat_departemen'];
    
    $query = "UPDATE departemen SET nama_departemen='$nama_departemen', alamat_departemen='$alamat_departemen' WHERE id_departemen=$id_departemen";
    if(mysqli_query($koneksi, $query)) {
        $success = "Data berhasil diupdate!";
    } else {
        $error = "Error: " . mysqli_error($koneksi);
    }
}

// Proses Hapus
if(isset($_GET['hapus'])) {
    $id_departemen = $_GET['hapus'];
    $query = "DELETE FROM departemen WHERE id_departemen=$id_departemen";
    if(mysqli_query($koneksi, $query)) {
        $success = "Data berhasil dihapus!";
    } else {
        $error = "Error: " . mysqli_error($koneksi);
    }
}

// Ambil data departemen
$query = "SELECT * FROM departemen ORDER BY id_departemen DESC";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Departemen - Sistem Penilaian Pekerja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #2563eb;
            --sidebar-width: 260px;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: white;
            border-right: 1px solid #e5e7eb;
            padding: 1.5rem;
            overflow-y: auto;
        }
        
        .sidebar-brand {
            margin-bottom: 2rem;
        }
        
        .sidebar-brand h4 {
            color: var(--primary-color);
            margin-bottom: 0.25rem;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
        }
        
        .sidebar-menu li {
            margin-bottom: 0.5rem;
        }
        
        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: #374151;
            text-decoration: none;
            border-radius: 0.5rem;
            transition: all 0.3s;
        }
        
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background-color: #eff6ff;
            color: var(--primary-color);
        }
        
        .sidebar-menu i {
            margin-right: 0.75rem;
            font-size: 1.25rem;
        }
        
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
        }
        
        .table-container {
            background: white;
            border-radius: 0.75rem;
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }
        
        .btn-action {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-brand">
            <h4>Sistem Penilaian Pekerja</h4>
            <small class="text-muted">Berbasis Proyek</small>
        </div>
        
        <ul class="sidebar-menu">
            <li>
                <a href="dashboard.php">
                    <i class="bi bi-speedometer2"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="departemen.php" class="active">
                    <i class="bi bi-building"></i>
                    Departemen
                </a>
            </li>
            <li>
                <a href="karyawan.php">
                    <i class="bi bi-people"></i>
                    Karyawan
                </a>
            </li>
            <li>
                <a href="proyek.php">
                    <i class="bi bi-briefcase"></i>
                    Proyek
                </a>
            </li>
            <li>
                <a href="penugasan.php">
                    <i class="bi bi-clipboard-check"></i>
                    Penugasan
                </a>
            </li>
            <li>
                <a href="penilaian.php">
                    <i class="bi bi-star"></i>
                    Penilaian Kinerja
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2>Daftar Departemen</h2>
                <p class="text-muted">Kelola data departemen perusahaan</p>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="bi bi-plus-circle me-2"></i>Tambah Departemen
            </button>
        </div>

        <?php if(isset($success)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $success; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if(isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <div class="table-container">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nama Departemen</th>
                        <th>Alamat</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['id_departemen']; ?></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 p-2 rounded me-2">
                                    <i class="bi bi-building text-primary"></i>
                                </div>
                                <?php echo $row['nama_departemen']; ?>
                            </div>
                        </td>
                        <td><?php echo $row['alamat_departemen']; ?></td>
                        <td>
                            <button class="btn btn-sm btn-warning btn-action" onclick="editData(<?php echo htmlspecialchars(json_encode($row)); ?>)">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <a href="?hapus=<?php echo $row['id_departemen']; ?>" 
                               class="btn btn-sm btn-danger btn-action" 
                               onclick="return confirm('Yakin ingin menghapus?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="modalTambah" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Departemen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Departemen</label>
                            <input type="text" class="form-control" name="nama_departemen" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat Departemen</label>
                            <textarea class="form-control" name="alamat_departemen" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="tambah" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="modalEdit" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Departemen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <input type="hidden" name="id_departemen" id="edit_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Departemen</label>
                            <input type="text" class="form-control" name="nama_departemen" id="edit_nama" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat Departemen</label>
                            <textarea class="form-control" name="alamat_departemen" id="edit_alamat" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="edit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editData(data) {
            document.getElementById('edit_id').value = data.id_departemen;
            document.getElementById('edit_nama').value = data.nama_departemen;
            document.getElementById('edit_alamat').value = data.alamat_departemen;
            new bootstrap.Modal(document.getElementById('modalEdit')).show();
        }
    </script>
</body>
</html>
