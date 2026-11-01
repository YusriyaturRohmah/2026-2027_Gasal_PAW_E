<?php
include 'koneksi.php';
$id = (int)($_GET['id'] ?? 0);
try {
    $stmt = $conn->prepare("DELETE FROM supplier WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $pesan = "Data berhasil dihapus";
} catch (mysqli_sql_exception $e) {
    $pesan = "Gagal menghapus: supplier masih dipakai di tabel barang";
}
header("Location: index.php?pesan=" . urlencode($pesan));