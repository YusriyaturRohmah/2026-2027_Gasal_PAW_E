<?php
// Koneksi ke database MySQL (sesuaikan username/password dengan XAMPP Anda)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$koneksi = new mysqli("localhost", "root", "", "penjualan");
$koneksi->set_charset("utf8mb4");

function e($teks) { return htmlspecialchars((string)$teks, ENT_QUOTES, 'UTF-8'); }
function rupiah($angka) { return "Rp " . number_format((int)$angka, 0, ',', '.'); }
