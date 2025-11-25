<?php
// ======================
// KONEKSI DATABASE
// ======================
$host = "localhost";
$user = "root";
$pass = "";
$db   = "universitas 2";
 
$conn = mysqli_connect($host, $user, $pass, $db);
 
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
 
// Set charset untuk keamanan
mysqli_set_charset($conn, "utf8mb4");
 
// ======================
// FUNGSI VALIDASI INPUT
// ======================
function validateInput($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}
 
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}
 
function validateYear($year) {
    $year = (int)$year;
    return ($year >= 2000 && $year <= date('Y')) ? $year : false;
}
 
// ======================
// ACTION: INSERT (DENGAN PREPARED STATEMENTS)
// ======================
$success_msg = '';
$error_msg = '';
 
if (isset($_POST['add'])) {
    // Validasi input
    $nim = validateInput($_POST['nim'] ?? '');
    $nama = validateInput($_POST['nama_mahasiswa'] ?? '');
    $email = validateInput($_POST['email'] ?? '');
    $telp = validateInput($_POST['no_telepon'] ?? '');
    $jur = validateInput($_POST['jurusan'] ?? '');
    $thn = validateYear($_POST['tahun_masuk'] ?? 0);
 
    // Validasi email
    if (!validateEmail($email)) {
        $error_msg = "❌ Format email tidak valid!";
    }
    // Validasi tahun
    elseif ($thn === false) {
        $error_msg = "❌ Tahun masuk tidak valid!";
    }
    // Validasi field kosong
    elseif (empty($nim) || empty($nama) || empty($jur)) {
        $error_msg = "❌ NIM, Nama, dan Jurusan tidak boleh kosong!";
    }
    else {
        // Cek apakah NIM sudah ada
        $check_sql = "SELECT nim FROM mahasiswa WHERE nim = ?";
        $check_stmt = mysqli_prepare($conn, $check_sql);
        mysqli_stmt_bind_param($check_stmt, "s", $nim);
        mysqli_stmt_execute($check_stmt);
        $check_result = mysqli_stmt_get_result($check_stmt);
 
        if (mysqli_num_rows($check_result) > 0) {
            $error_msg = "❌ NIM sudah terdaftar!";
        } else {
            // Insert dengan prepared statement
            $sql = "INSERT INTO mahasiswa (nim, nama_mahasiswa, email, no_telepon, jurusan, tahun_masuk) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
 
            if ($stmt) {
                // PENTING: Type parameter yang benar (sssssi = 6 tipe untuk 6 variabel)
                mysqli_stmt_bind_param($stmt, "sssssi", $nim, $nama, $email, $telp, $jur, $thn);
                 
                if (mysqli_stmt_execute($stmt)) {
                    $success_msg = "✅ Data berhasil ditambahkan!";
                    $_POST = array();
                } else {
                    $error_msg = "❌ Gagal menambahkan data: " . mysqli_error($conn);
                }
                mysqli_stmt_close($stmt);
            } else {
                $error_msg = "❌ Error prepare statement: " . mysqli_error($conn);
            }
        }
        mysqli_stmt_close($check_stmt);
    }
}
 
// ======================
// ACTION: DELETE (DENGAN PREPARED STATEMENTS)
// ======================
if (isset($_GET['delete'])) {
    $nim = validateInput($_GET['delete']);
     
    $sql = "DELETE FROM mahasiswa WHERE nim = ?";
    $stmt = mysqli_prepare($conn, $sql);
     
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $nim);
         
        if (mysqli_stmt_execute($stmt)) {
            $success_msg = "✅ Data berhasil dihapus!";
        } else {
            $error_msg = "❌ Gagal menghapus data: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    }
}
 
// ======================
// ACTION: UPDATE (DENGAN PREPARED STATEMENTS)
// ======================
if (isset($_POST['update'])) {
    // Validasi input
    $nim = validateInput($_POST['nim'] ?? '');
    $nama = validateInput($_POST['nama_mahasiswa'] ?? '');
    $email = validateInput($_POST['email'] ?? '');
    $telp = validateInput($_POST['no_telepon'] ?? '');
    $jur = validateInput($_POST['jurusan'] ?? '');
    $thn = validateYear($_POST['tahun_masuk'] ?? 0);
 
    // Validasi email
    if (!validateEmail($email)) {
        $error_msg = "❌ Format email tidak valid!";
    }
    // Validasi tahun
    elseif ($thn === false) {
        $error_msg = "❌ Tahun masuk tidak valid!";
    }
    // Validasi field kosong
    elseif (empty($nim) || empty($nama) || empty($jur)) {
        $error_msg = "❌ NIM, Nama, dan Jurusan tidak boleh kosong!";
    }
    else {
        // Update dengan prepared statement
        $sql = "UPDATE mahasiswa 
                SET nama_mahasiswa = ?, 
                    email = ?, 
                    no_telepon = ?, 
                    jurusan = ?, 
                    tahun_masuk = ? 
                WHERE nim = ?";
        $stmt = mysqli_prepare($conn, $sql);
 
        if ($stmt) {
            // PENTING: Type parameter yang benar (ssssis = 6 tipe untuk 6 variabel)
            mysqli_stmt_bind_param($stmt, "ssssis", $nama, $email, $telp, $jur, $thn, $nim);
             
            if (mysqli_stmt_execute($stmt)) {
                $success_msg = "✅ Data berhasil diupdate!";
                $_POST = array();
            } else {
                $error_msg = "❌ Gagal mengupdate data: " . mysqli_error($conn);
            }
            mysqli_stmt_close($stmt);
        } else {
            $error_msg = "❌ Error prepare statement: " . mysqli_error($conn);
        }
    }
}
 
// ======================
// QUERY DATA
// ======================
$result = mysqli_query($conn, "SELECT * FROM mahasiswa ORDER BY nim ASC");
 
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Mahasiswa - Sistem Informasi Akademik</title>
 
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
 
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
 
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 30px 20px;
        }
 
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            overflow: hidden;
        }
 
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
 
        .header h1 {
            font-size: 28px;
            margin-bottom: 5px;
        }
 
        .header p {
            font-size: 14px;
            opacity: 0.9;
        }
 
        .content {
            padding: 30px;
        }
 
        /* Alert Messages */
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            display: none;
            animation: slideDown 0.3s ease-in;
        }
 
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
 
        .alert.show {
            display: block;
        }
 
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
 
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
 
        /* Form Section */
        .form-section {
            background: #f9f9f9;
            padding: 25px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid #667eea;
        }
 
        .form-section h3 {
            margin-bottom: 20px;
            color: #333;
            font-size: 18px;
        }
 
        .form-group {
            margin-bottom: 15px;
        }
 
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
            font-size: 14px;
        }
 
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
 
        input[type="text"],
        input[type="email"],
        input[type="number"],
        select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            font-family: inherit;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
 
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="number"]:focus,
        select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
 
        input[type="text"]:disabled,
        input[type="email"]:disabled,
        input[type="number"]:disabled {
            background-color: #f5f5f5;
            cursor: not-allowed;
        }
 
        .form-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
 
        button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
 
        .btn-add {
            background: #28a745;
            color: white;
            flex: 1;
        }
 
        .btn-add:hover {
            background: #218838;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }
 
        .btn-cancel {
            background: #999;
            color: white;
        }
 
        .btn-cancel:hover {
            background: #777;
        }
 
        /* Table Section */
        .table-section {
            margin-top: 30px;
        }
 
        .table-section h3 {
            margin-bottom: 20px;
            color: #333;
            font-size: 18px;
        }
 
        table {
            width: 100%;
            border-collapse: collapse;
        }
 
        table thead tr {
            background: #667eea;
            color: white;
        }
 
        table th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }
 
        table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
        }
 
        table tbody tr:hover {
            background: #f5f5f5;
        }
 
        .btn-edit {
            background: #ffc107;
            color: #333;
            padding: 6px 12px;
            margin-right: 5px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
        }
 
        .btn-edit:hover {
            background: #e0a800;
        }
 
        .btn-delete {
            background: #dc3545;
            color: white;
            padding: 6px 12px;
            text-decoration: none;
            display: inline-block;
            border-radius: 3px;
            border: none;
            cursor: pointer;
            font-size: 12px;
        }
 
        .btn-delete:hover {
            background: #c82333;
        }
 
        .action-column {
            width: 150px;
        }
 
        /* Edit Form (Popup) */
        #formEdit {
            display: none;
            padding: 25px;
            background: #f9f9f9;
            border: 2px solid #667eea;
            border-radius: 8px;
            margin-top: 20px;
            animation: slideDown 0.3s ease-in;
        }
 
        #formEdit h3 {
            margin-bottom: 20px;
            color: #333;
        }
 
        .btn-close {
            background: #999;
            color: white;
            padding: 8px 15px;
            margin-left: 10px;
        }
 
        .btn-close:hover {
            background: #777;
        }
 
        /* DataTables Customization */
        .dataTables_wrapper {
            padding: 0 !important;
        }
 
        .dataTables_length,
        .dataTables_filter {
            margin-bottom: 15px;
        }
 
        .dataTables_info {
            margin-top: 15px;
        }
 
        /* Responsive */
        @media (max-width: 768px) {
            .container {
                margin: 0;
                border-radius: 0;
            }
 
            .content {
                padding: 15px;
            }
 
            .form-row {
                grid-template-columns: 1fr;
            }
 
            .form-buttons {
                flex-direction: column;
            }
 
            table {
                font-size: 13px;
            }
 
            table th, table td {
                padding: 8px 10px;
            }
        }
    </style>
</head>
 
<body>
 
<div class="container">
    <!-- Header -->
    <div class="header">
        <h1>📚 Manajemen Data Mahasiswa</h1>
        <p>Sistem Informasi Akademik - CRUD dengan Keamanan Prepared Statements</p>
    </div>
 
    <div class="content">
        <!-- Alert Messages -->
        <?php if ($success_msg): ?>
            <div class="alert alert-success show">
                <?php echo $success_msg; ?>
            </div>
        <?php endif; ?>
 
        <?php if ($error_msg): ?>
            <div class="alert alert-error show">
                <?php echo $error_msg; ?>
            </div>
        <?php endif; ?>
 
        <!-- FORM TAMBAH DATA -->
        <div class="form-section">
            <h3>➕ Tambah Data Mahasiswa Baru</h3>
            <form method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="nim">NIM *</label>
                        <input type="text" id="nim" name="nim" placeholder="Contoh: A001" required 
                               value="<?php echo isset($_POST['nim']) ? htmlspecialchars($_POST['nim']) : ''; ?>">
                    </div>
 
                    <div class="form-group">
                        <label for="nama_mahasiswa">Nama Mahasiswa *</label>
                        <input type="text" id="nama_mahasiswa" name="nama_mahasiswa" placeholder="Nama lengkap" required
                               value="<?php echo isset($_POST['nama_mahasiswa']) ? htmlspecialchars($_POST['nama_mahasiswa']) : ''; ?>">
                    </div>
 
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="email@example.com"
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>
                </div>
 
                <div class="form-row">
                    <div class="form-group">
                        <label for="no_telepon">No Telepon</label>
                        <input type="text" id="no_telepon" name="no_telepon" placeholder="08xxxxxxxxx"
                               value="<?php echo isset($_POST['no_telepon']) ? htmlspecialchars($_POST['no_telepon']) : ''; ?>">
                    </div>
 
                    <div class="form-group">
                        <label for="jurusan">Jurusan *</label>
                        <select id="jurusan" name="jurusan" required>
                            <option value="">-- Pilih Jurusan --</option>
                            <option value="Teknik Informatika" <?php echo (isset($_POST['jurusan']) && $_POST['jurusan'] === 'Teknik Informatika') ? 'selected' : ''; ?>>Teknik Informatika</option>
                            <option value="Sistem Informasi" <?php echo (isset($_POST['jurusan']) && $_POST['jurusan'] === 'Sistem Informasi') ? 'selected' : ''; ?>>Sistem Informasi</option>
                            <option value="Desain Grafis" <?php echo (isset($_POST['jurusan']) && $_POST['jurusan'] === 'Desain Grafis') ? 'selected' : ''; ?>>Desain Grafis</option>
                            <option value="Manajemen Informatika" <?php echo (isset($_POST['jurusan']) && $_POST['jurusan'] === 'Manajemen Informatika') ? 'selected' : ''; ?>>Manajemen Informatika</option>
                        </select>
                    </div>
 
                    <div class="form-group">
                        <label for="tahun_masuk">Tahun Masuk *</label>
                        <input type="number" id="tahun_masuk" name="tahun_masuk" placeholder="2023"
                               min="2000" max="<?php echo date('Y'); ?>" required
                               value="<?php echo isset($_POST['tahun_masuk']) ? htmlspecialchars($_POST['tahun_masuk']) : ''; ?>">
                    </div>
                </div>
 
                <div class="form-buttons">
                    <button type="submit" name="add" class="btn-add">💾 Simpan Data</button>
                </div>
            </form>
        </div>
 
        <!-- TABEL MAHASISWA -->
        <div class="table-section">
            <h3>📊 Daftar Mahasiswa</h3>
            <table id="tabelMahasiswa" class="display">
                <thead>
                    <tr>
                        <th>NIM</th>
                        <th>Nama Mahasiswa</th>
                        <th>Email</th>
                        <th>No Telepon</th>
                        <th>Jurusan</th>
                        <th>Tahun</th>
                        <th class="action-column">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) { 
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['nim']); ?></td>
                        <td><?php echo htmlspecialchars($row['nama_mahasiswa']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['no_telepon']); ?></td>
                        <td><?php echo htmlspecialchars($row['jurusan']); ?></td>
                        <td><?php echo $row['tahun_masuk']; ?></td>
                        <td>
                            <button class="btn-edit" onclick="editData(
                                '<?php echo htmlspecialchars($row['nim'], ENT_QUOTES); ?>',
                                '<?php echo htmlspecialchars($row['nama_mahasiswa'], ENT_QUOTES); ?>',
                                '<?php echo htmlspecialchars($row['email'], ENT_QUOTES); ?>',
                                '<?php echo htmlspecialchars($row['no_telepon'], ENT_QUOTES); ?>',
                                '<?php echo htmlspecialchars($row['jurusan'], ENT_QUOTES); ?>',
                                '<?php echo $row['tahun_masuk']; ?>'
                            )">✏️ Edit</button>
 
                            <a href="mahasiswa.php?delete=<?php echo urlencode($row['nim']); ?>" 
                                onclick="return confirm('Yakin hapus data ini?')" 
                                class="btn-delete">🗑️ Hapus</a>
                        </td>
                    </tr>
                <?php
                    }
                } else {
                    echo '<tr><td colspan="7" style="text-align:center; padding:20px;">📭 Tidak ada data mahasiswa</td></tr>';
                }
                ?>
                </tbody>
            </table>
        </div>
 
        <!-- FORM UPDATE (POPUP MODE) -->
        <div id="formEdit">
            <h3>✏️ Edit Data Mahasiswa</h3>
            <form method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="u_nim">NIM (Tidak bisa diubah)</label>
                        <input type="text" name="nim" id="u_nim" readonly>
                    </div>
 
                    <div class="form-group">
                        <label for="u_nama">Nama Mahasiswa *</label>
                        <input type="text" name="nama_mahasiswa" id="u_nama" required>
                    </div>
 
                    <div class="form-group">
                        <label for="u_email">Email</label>
                        <input type="email" name="email" id="u_email">
                    </div>
                </div>
 
                <div class="form-row">
                    <div class="form-group">
                        <label for="u_telepon">No Telepon</label>
                        <input type="text" name="no_telepon" id="u_telepon">
                    </div>
 
                    <div class="form-group">
                        <label for="u_jurusan">Jurusan *</label>
                        <select name="jurusan" id="u_jurusan" required>
                            <option value="">-- Pilih Jurusan --</option>
                            <option value="Teknik Informatika">Teknik Informatika</option>
                            <option value="Sistem Informasi">Sistem Informasi</option>
                            <option value="Desain Grafis">Desain Grafis</option>
                            <option value="Manajemen Informatika">Manajemen Informatika</option>
                        </select>
                    </div>
 
                    <div class="form-group">
                        <label for="u_tahun">Tahun Masuk *</label>
                        <input type="number" name="tahun_masuk" id="u_tahun"
                               min="2000" max="<?php echo date('Y'); ?>" required>
                    </div>
                </div>
 
                <div class="form-buttons">
                    <button type="submit" name="update" class="btn-add">💾 Update</button>
                    <button type="button" class="btn-close" onclick="closeEdit()">❌ Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
 
<!-- JQuery + DataTables -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
 
<script>
$(document).ready(function() {
    $('#tabelMahasiswa').DataTable({
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.13.4/i18n/id.json"
        },
        "pageLength": 10,
        "responsive": true
    });
 
    // Hide alert setelah 5 detik
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);
});
 
// Menampilkan form edit
function editData(nim, nama, email, telp, jurusan, tahun) {
    document.getElementById('formEdit').style.display = 'block';
    document.getElementById('u_nim').value = nim;
    document.getElementById('u_nama').value = nama;
    document.getElementById('u_email').value = email;
    document.getElementById('u_telepon').value = telp;
    document.getElementById('u_jurusan').value = jurusan;
    document.getElementById('u_tahun').value = tahun;
 
    // Scroll ke form edit
    document.getElementById('formEdit').scrollIntoView({ behavior: 'smooth' });
}
 
// Menutup form edit
function closeEdit() {
    document.getElementById('formEdit').style.display = 'none';
}
</script>
 
</body>
</html>