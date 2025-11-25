<?php
// Koneksi ke database
$host = "localhost";
$user = "root";
$pass = "";
$db   = "universitas 2"; // ganti dengan nama databasenya
 
$conn = mysqli_connect($host, $user, $pass, $db);
 
// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
 
// Query data
$query = "SELECT * FROM mahasiswa";
$result = mysqli_query($conn, $query);
?>
 
<!DOCTYPE html>
<html>
<head>
    <title>Data Mahasiswa</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #333;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background: #f0f0f0;
        }
    </style>
</head>
<body>
 
<h2>Data Mahasiswa</h2>
 
<table>
    <thead>
        <tr>
            <th>NIM</th>
            <th>Nama Mahasiswa</th>
            <th>Email</th>
            <th>No Telepon</th>
            <th>Jurusan</th>
            <th>Tahun Masuk</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['nim']; ?></td>
                    <td><?php echo $row['nama_mahasiswa']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['no_telepon']; ?></td>
                    <td><?php echo $row['jurusan']; ?></td>
                    <td><?php echo $row['tahun_masuk']; ?></td>
                </tr>
        <?php
            }
        } else {
            echo "<tr><td colspan='6'>Tidak ada data.</td></tr>";
        }
        ?>
    </tbody>
</table>
 
</body>
</html>
 
 
[html]
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
 
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Mahasiswa</title>
 
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
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
 
        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            max-width: 600px;
            width: 100%;
            overflow: hidden;
        }
 
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
 
        .header h1 {
            font-size: 24px;
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
 
        /* Form */
        .form-group {
            margin-bottom: 20px;
        }
 
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
            font-size: 14px;
        }
 
        .required {
            color: #dc3545;
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
 
        .form-buttons {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }
 
        button, .btn-back {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }
 
        .btn-add {
            background: #28a745;
            color: white;
        }
 
        .btn-add:hover {
            background: #218838;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }
 
        .btn-back {
            background: #999;
            color: white;
        }
 
        .btn-back:hover {
            background: #777;
        }
 
        /* Info Box */
        .info-box {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            color: #1565c0;
            font-size: 13px;
        }
 
        /* Responsive */
        @media (max-width: 600px) {
            .container {
                border-radius: 0;
            }
 
            .content {
                padding: 20px;
            }
 
            .form-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
 
<body>
 
<div class="container">
    <!-- Header -->
    <div class="header">
        <h1>➕ Tambah Data Mahasiswa</h1>
        <p>Masukkan data mahasiswa baru ke dalam sistem</p>
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
 
        <!-- Info Box -->
        <div class="info-box">
            ℹ️ Semua field yang bertanda <span class="required">*</span> wajib diisi
        </div>
 
        <!-- FORM TAMBAH DATA -->
        <form method="POST">
            <div class="form-group">
                <label for="nim">NIM <span class="required">*</span></label>
                <input type="text" id="nim" name="nim" placeholder="Contoh: A001" required 
                       value="<?php echo isset($_POST['nim']) ? htmlspecialchars($_POST['nim']) : ''; ?>">
            </div>
 
            <div class="form-group">
                <label for="nama_mahasiswa">Nama Mahasiswa <span class="required">*</span></label>
                <input type="text" id="nama_mahasiswa" name="nama_mahasiswa" placeholder="Nama lengkap" required
                       value="<?php echo isset($_POST['nama_mahasiswa']) ? htmlspecialchars($_POST['nama_mahasiswa']) : ''; ?>">
            </div>
 
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="email@example.com"
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>
 
            <div class="form-group">
                <label for="no_telepon">No Telepon</label>
                <input type="text" id="no_telepon" name="no_telepon" placeholder="08xxxxxxxxx"
                       value="<?php echo isset($_POST['no_telepon']) ? htmlspecialchars($_POST['no_telepon']) : ''; ?>">
            </div>
 
            <div class="form-group">
                <label for="jurusan">Jurusan <span class="required">*</span></label>
                <select id="jurusan" name="jurusan" required>
                    <option value="">-- Pilih Jurusan --</option>
                    <option value="Teknik Informatika" <?php echo (isset($_POST['jurusan']) && $_POST['jurusan'] === 'Teknik Informatika') ? 'selected' : ''; ?>>Teknik Informatika</option>
                    <option value="Sistem Informasi" <?php echo (isset($_POST['jurusan']) && $_POST['jurusan'] === 'Sistem Informasi') ? 'selected' : ''; ?>>Sistem Informasi</option>
                    <option value="Desain Grafis" <?php echo (isset($_POST['jurusan']) && $_POST['jurusan'] === 'Desain Grafis') ? 'selected' : ''; ?>>Desain Grafis</option>
                    <option value="Manajemen Informatika" <?php echo (isset($_POST['jurusan']) && $_POST['jurusan'] === 'Manajemen Informatika') ? 'selected' : ''; ?>>Manajemen Informatika</option>
                </select>
            </div>
 
            <div class="form-group">
                <label for="tahun_masuk">Tahun Masuk <span class="required">*</span></label>
                <input type="number" id="tahun_masuk" name="tahun_masuk" placeholder="2023"
                       min="2000" max="<?php echo date('Y'); ?>" required
                       value="<?php echo isset($_POST['tahun_masuk']) ? htmlspecialchars($_POST['tahun_masuk']) : ''; ?>">
            </div>
 
            <div class="form-buttons">
                <button type="submit" name="add" class="btn-add">💾 Simpan Data</button>
                <a href="javascript:history.back()" class="btn-back">🔙 Kembali</a>
            </div>
        </form>
    </div>
</div>
 
<script>
    // Hide alert setelah 5 detik
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            alert.style.display = 'none';
        });
    }, 5000);
 
    // Optional: Redirect ke halaman sebelumnya jika sukses
    <?php if ($success_msg): ?>
        setTimeout(function() {
            window.location.href = 'mahasiswa.php';
        }, 2000);
    <?php endif; ?>
</script>
 
</body>
</html>