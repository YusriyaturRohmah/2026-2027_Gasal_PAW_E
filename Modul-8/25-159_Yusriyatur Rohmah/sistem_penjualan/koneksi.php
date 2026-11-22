<?php
// Tampilkan error MySQL dengan jelas (membantu saat debugging)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = 'localhost';
$user = 'root';
$pass = '';              // XAMPP default: kosong
$db   = 'db_penjualan';

try {
    $conn = new mysqli($host, $user, $pass, $db);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    die('Koneksi database gagal: ' . htmlspecialchars($e->getMessage()) .
        '<br>Cek nama database / password di koneksi.php dan pastikan db.sql sudah diimport.');
}