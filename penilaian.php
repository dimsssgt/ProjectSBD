<?php
require_once 'koneksi.php';

// Proses Tambah
if(isset($_POST['tambah'])) {
    $id_penugasan = $_POST['id_penugasan'];
    $id_manager = $_POST['id_manager'];
    $kualitas = $_POST['kualitas'];
    $ketepatan_waktu = $_POST['ketepatan_waktu'];
    $keaktifan = $_POST['keaktifan'];
    $kontribusi = $_POST['kontribusi'];
    $total_nilai = ($kualitas + $ketepatan_waktu + $keaktifan + $kontribusi) / 4;
    $catatan = $_POST['catatan'];
    $tanggal_penilaian = $_POST['tanggal_penilaian'];
    
    $query = "INSERT INTO nilai_kinerja_proyek (id_penugasan, id_manager, kualitas, ketepatan_waktu, keaktifan, kontribusi, total_nilai, catatan, tanggal_penilaian) 
              VALUES ('$id_penugasan', '$id_manager', '$kualitas', '$ketepatan_waktu', '$keaktifan', '$kontribusi', '$total_nilai', '$catatan', '$tanggal_penilaian')";
    if(mysqli_query($koneksi, $query)) {
        $success = "Data berhasil ditambahkan!";
    } else {
        $error = "Error: " . mysqli_error($koneksi);
    }
}

// Proses Edit
if(isset($_POST['edit'])) {
    $id_nilai = $_POST['id_nilai'];
    $id_penugasan = $_POST['id_penugasan'];
    $id_manager = $_POST['id_manager'];
    $kualitas = $_POST['kualitas'];
    $ketepatan_waktu = $_POST['ketepatan_waktu'];
    $keaktifan = $_POST['keaktifan'];
    $kontribusi = $_POST['kontribusi'];
    $total_nilai = ($kualitas + $ketepatan_waktu + $keaktifan + $kontribusi) / 4;
    $catatan = $_POST['catatan'];
    $tanggal_penilaian = $_POST['tanggal_penilaian'];
    
    $query = "UPDATE nilai_kinerja_proyek SET id_penugasan='$id_penugasan', id_manager='$id_manager', 
              kualitas='$kualitas', ketepatan_waktu='$ketepatan_waktu', keaktifan='$keaktifan', 
              kontribusi='$kontribusi', total_nilai='$total_nilai', catatan='$catatan', 
              tanggal_penilaian='$tanggal_penilaian' WHERE id_nilai=$id_nilai";
    if(mysqli_query($koneksi, $query)) {
        $success = "Data berhasil diupdate!";
    } else {
        $error = "Error: " . mysqli_error($koneksi);
    }
}

// Proses Hapus
if(isset($_GET['hapus'])) {
    $id_nilai = $_GET['hapus'];
    $query = "DELETE FROM nilai_kinerja_proyek WHERE id_nilai=$id_nilai";
    if(mysqli_query($koneksi, $query)) {
        $success = "Data berhasil dihapus!";
    } else {
        $error = "Error: " . mysqli_error($koneksi);
    }
}

// Ambil data penilaian dengan join
$query = "SELECT n.*, 
          k.nama as nama_karyawan, 
          pr.nama_proyek,
          pg.peran,
          m.nama as nama_manager
          FROM nilai_kinerja_proyek n
          LEFT JOIN penugasan pg ON n.id_penugasan = pg.id_penugasan
          LEFT JOIN karyawan k ON pg.id_karyawan = k.id_karyawan
          LEFT JOIN proyek pr ON pg.id_proyek = pr.id_proyek
          LEFT JOIN manager m ON n.id_manager = m.id_manager
          ORDER BY n.tanggal_penilaian DESC";
$result = mysqli_query($koneksi, $query);

// Ambil data penugasan untuk dropdown
$query_penugasan = "SELECT pg.*, k.nama as nama_karyawan, pr.nama_proyek 
                    FROM penugasan pg
                    LEFT JOIN karyawan k ON pg.id_karyawan = k.id_karyawan
                    LEFT JOIN proyek pr ON pg.id_proyek = pr.id_proyek";
$result_penugasan = mysqli_query($koneksi, $query_penugasan);

// Ambil data manager untuk dropdown
$query_manager = "SELECT * FROM manager";
$result_manager = mysqli_query($koneksi, $query_manager);

// Function untuk kategori nilai
function getKategoriNilai($nilai) {
    if($nilai >= 90) return ['label' => 'Excellent', 'class' => 'success'];
    if($nilai >= 80) return ['label' => 'Baik', 'class' => 'primary'];
    if($nilai >= 70) return ['label' => 'Cukup', 'class' => 'warning'];
    return ['label' => 'Perlu Perbaikan', 'class' => 'danger'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penilaian Kinerja - Sistem Penilaian Pekerja</title>
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
        
        .nilai-card {
            background: white;
            border-radius: 0.75rem;
            border: 1px solid #e5e7eb;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .score-display {
            font-size: 2rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
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
                <a href="departemen.php">
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
                <a href="penilaian.php" class="active">
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
                <h2>Daftar Penilaian Kinerja</h2>
                <p class="text-muted">Kelola penilaian kinerja karyawan pada proyek</p>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="bi bi-plus-circle me-2"></i>Tambah Penilaian
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

        <?php while($row = mysqli_fetch_assoc($result)): 
            $kategori = getKategoriNilai($row['total_nilai']);
        ?>
        <div class="nilai-card">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="d-flex align-items-start flex-grow-1">
                    <div class="bg-warning bg-opacity-10 p-3 rounded me-3">
                        <i class="bi bi-star-fill text-warning fs-5"></i>
                    </div>
                    <div>
                        <h5 class="mb-1"><?php echo $row['nama_karyawan']; ?></h5>
                        <p class="text-muted mb-0"><?php echo $row['nama_proyek']; ?></p>
                        <span class="badge bg-primary mt-1"><?php echo $row['peran']; ?></span>
                    </div>
                </div>
                <div class="text-end">
                    <div class="score-display text-<?php echo $kategori['class']; ?>">
                        <?php echo number_format($row['total_nilai'], 2); ?>
                    </div>
                    <small class="text-<?php echo $kategori['class']; ?>"><?php echo $kategori['label']; ?></small>
                    <div class="mt-2">
                        <button class="btn btn-sm btn-warning me-1" onclick="editData(<?php echo htmlspecialchars(json_encode($row)); ?>)">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <a href="?hapus=<?php echo $row['id_nilai']; ?>" 
                           class="btn btn-sm btn-danger" 
                           onclick="return confirm('Yakin ingin menghapus?')">
                            <i class="bi bi-trash"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <div class="bg-light p-3 rounded">
                        <small class="text-muted d-block mb-1">Kualitas</small>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-graph-up text-primary me-2"></i>
                            <strong><?php echo $row['kualitas']; ?></strong>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="bg-light p-3 rounded">
                        <small class="text-muted d-block mb-1">Ketepatan Waktu</small>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-clock text-success me-2"></i>
                            <strong><?php echo $row['ketepatan_waktu']; ?></strong>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="bg-light p-3 rounded">
                        <small class="text-muted d-block mb-1">Keaktifan</small>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-lightning text-warning me-2"></i>
                            <strong><?php echo $row['keaktifan']; ?></strong>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="bg-light p-3 rounded">
                        <small class="text-muted d-block mb-1">Kontribusi</small>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-trophy text-info me-2"></i>
                            <strong><?php echo $row['kontribusi']; ?></strong>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="border-top pt-3">
                <p class="mb-2"><strong>Catatan:</strong> <?php echo $row['catatan']; ?></p>
                <div class="d-flex justify-content-between text-muted">
                    <small><i class="bi bi-person me-1"></i>Dinilai oleh: <?php echo $row['nama_manager']; ?></small>
                    <small><i class="bi bi-calendar me-1"></i><?php echo date('d/m/Y', strtotime($row['tanggal_penilaian'])); ?></small>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="modalTambah" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Penilaian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Penugasan</label>
                                <select class="form-select" name="id_penugasan" required>
                                    <?php 
                                    mysqli_data_seek($result_penugasan, 0);
                                    while($penugasan = mysqli_fetch_assoc($result_penugasan)): 
                                    ?>
                                    <option value="<?php echo $penugasan['id_penugasan']; ?>">
                                        <?php echo $penugasan['nama_karyawan']; ?> - <?php echo $penugasan['nama_proyek']; ?> (<?php echo $penugasan['peran']; ?>)
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Manager Penilai</label>
                                <select class="form-select" name="id_manager" required>
                                    <?php 
                                    mysqli_data_seek($result_manager, 0);
                                    while($manager = mysqli_fetch_assoc($result_manager)): 
                                    ?>
                                    <option value="<?php echo $manager['id_manager']; ?>">
                                        <?php echo $manager['nama']; ?>
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label">Kualitas (0-100)</label>
                                <input type="number" class="form-control" name="kualitas" min="0" max="100" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Ketepatan Waktu (0-100)</label>
                                <input type="number" class="form-control" name="ketepatan_waktu" min="0" max="100" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Keaktifan (0-100)</label>
                                <input type="number" class="form-control" name="keaktifan" min="0" max="100" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Kontribusi (0-100)</label>
                                <input type="number" class="form-control" name="kontribusi" min="0" max="100" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Catatan</label>
                            <textarea class="form-control" name="catatan" rows="3" 
                                      placeholder="Berikan catatan atau feedback untuk karyawan..." required></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Tanggal Penilaian</label>
                            <input type="date" class="form-control" name="tanggal_penilaian" required>
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
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Penilaian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <input type="hidden" name="id_nilai" id="edit_id">
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Penugasan</label>
                                <select class="form-select" name="id_penugasan" id="edit_penugasan" required>
                                    <?php 
                                    mysqli_data_seek($result_penugasan, 0);
                                    while($penugasan = mysqli_fetch_assoc($result_penugasan)): 
                                    ?>
                                    <option value="<?php echo $penugasan['id_penugasan']; ?>">
                                        <?php echo $penugasan['nama_karyawan']; ?> - <?php echo $penugasan['nama_proyek']; ?> (<?php echo $penugasan['peran']; ?>)
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Manager Penilai</label>
                                <select class="form-select" name="id_manager" id="edit_manager" required>
                                    <?php 
                                    mysqli_data_seek($result_manager, 0);
                                    while($manager = mysqli_fetch_assoc($result_manager)): 
                                    ?>
                                    <option value="<?php echo $manager['id_manager']; ?>">
                                        <?php echo $manager['nama']; ?>
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label">Kualitas (0-100)</label>
                                <input type="number" class="form-control" name="kualitas" id="edit_kualitas" min="0" max="100" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Ketepatan Waktu (0-100)</label>
                                <input type="number" class="form-control" name="ketepatan_waktu" id="edit_ketepatan" min="0" max="100" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Keaktifan (0-100)</label>
                                <input type="number" class="form-control" name="keaktifan" id="edit_keaktifan" min="0" max="100" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Kontribusi (0-100)</label>
                                <input type="number" class="form-control" name="kontribusi" id="edit_kontribusi" min="0" max="100" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Catatan</label>
                            <textarea class="form-control" name="catatan" id="edit_catatan" rows="3" required></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Tanggal Penilaian</label>
                            <input type="date" class="form-control" name="tanggal_penilaian" id="edit_tanggal" required>
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
            document.getElementById('edit_id').value = data.id_nilai;
            document.getElementById('edit_penugasan').value = data.id_penugasan;
            document.getElementById('edit_manager').value = data.id_manager;
            document.getElementById('edit_kualitas').value = data.kualitas;
            document.getElementById('edit_ketepatan').value = data.ketepatan_waktu;
            document.getElementById('edit_keaktifan').value = data.keaktifan;
            document.getElementById('edit_kontribusi').value = data.kontribusi;
            document.getElementById('edit_catatan').value = data.catatan;
            document.getElementById('edit_tanggal').value = data.tanggal_penilaian;
            new bootstrap.Modal(document.getElementById('modalEdit')).show();
        }
    </script>
</body>
</html>
