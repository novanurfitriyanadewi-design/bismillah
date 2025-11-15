<?php
// ===============================
// Bagian 1: Penentuan Grade
// ===============================

$nilai = 85; // bisa diubah sesuai kebutuhan

if ($nilai >= 90 && $nilai <= 100) {
    $grade = "A";
} elseif ($nilai >= 80) {
    $grade = "B";
} elseif ($nilai >= 70) {
    $grade = "C";
} elseif ($nilai >= 60) {
    $grade = "D";
} elseif ($nilai >= 0) {
    $grade = "E";
} else {
    $grade = "Nilai tidak valid";
}

echo "<p> Nilai Anda adalah $nilai, Grade Anda adalah $grade.</p>";



// ===============================
// Bagian 2: Keterangan Kelulusan (Switch)
// ===============================

switch ($grade) {
    case "A":
    case "B":
        $keterangan = "Sangat Memuaskan, Anda Lulus!";
        break;

    case "C":
        $keterangan = "Cukup Memuaskan, Anda Lulus dengan Syarat.";
        break;

    case "D":
    case "E":
        $keterangan = "Mohon Maaf, Anda Tidak Lulus.";
        break;

    default:
        $keterangan = "Grade Tidak Dikenali.";
}

echo "<p> Keterangan Kelulusan: $keterangan.</p>";


// ===============================
// Bagian 3: Operator Ternary
// ===============================

$prioritas_bimbingan = ($grade == "D" || $grade == "E") 
                        ? "Wajib Bimbingan" 
                        : "Tidak Wajib Bimbingan";

echo "<p> Status Bimbingan: $prioritas_bimbingan.</p>";
?>