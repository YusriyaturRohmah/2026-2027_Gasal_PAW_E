<?php
// DARURAT: jalankan jika login selalu gagal. Mengembalikan akun budi & wati ke password awal.
// Setelah berhasil login, HAPUS file ini.
require 'koneksi.php';
$conn->query("CREATE TABLE IF NOT EXISTS `user` (
  id_user INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  nama VARCHAR(100) NOT NULL,
  level TINYINT NOT NULL)");

$akun = [['budi','budi123','Budi',1], ['wati','wati123','Wati',2]];
$st = $conn->prepare("INSERT INTO `user` (username,password,nama,level) VALUES (?,?,?,?)
                      ON DUPLICATE KEY UPDATE password=VALUES(password), nama=VALUES(nama), level=VALUES(level)");
foreach ($akun as [$u,$p,$n,$l]) {
    $h = password_hash($p, PASSWORD_DEFAULT);
    $st->bind_param('sssi', $u, $h, $n, $l);
    $st->execute();
}
echo '<h3>Akun di tabel user sekarang:</h3><pre>';
$r = $conn->query("SELECT id_user, username, LEFT(password,7) AS awal_hash, LENGTH(password) AS panjang, nama, level FROM user");
while ($row = $r->fetch_assoc()) print_r($row);
echo '</pre><p>Owner: budi / budi123 &nbsp; Kasir: wati / wati123</p><p><a href="login.php">Ke halaman login</a></p>';