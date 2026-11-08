<?php
require 'koneksi.php';

$pesan = ''; $tipe = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus_id'])) {
    $id = (int)$_POST['hapus_id'];

    $cek = $koneksi->prepare("SELECT COUNT(*) AS jml FROM transaksi_detail WHERE barang_id = ?");
    $cek->bind_param("i", $id);
    $cek->execute();
    $jml = $cek->get_result()->fetch_assoc()['jml'];

    if ($jml > 0) {
        $pesan = "Barang tidak dapat dihapus karena masih digunakan pada $jml transaksi.";
        $tipe  = 'error';
    } else {
        $hapus = $koneksi->prepare("DELETE FROM barang WHERE id = ?");
        $hapus->bind_param("i", $id);
        $hapus->execute();
        $pesan = "Data barang berhasil dihapus.";
        $tipe  = 'sukses';
    }
}

$data = $koneksi->query(
    "SELECT b.*, s.nama AS supplier,
            (SELECT COUNT(*) FROM transaksi_detail d WHERE d.barang_id = b.id) AS dipakai
     FROM barang b LEFT JOIN supplier s ON s.id = b.supplier_id
     ORDER BY b.id"
);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Barang</title>
<style>
 body{font-family:Arial,sans-serif;margin:30px}
 table{border-collapse:collapse;width:100%}
 th,td{border:1px solid #999;padding:6px 10px}
 th{background:#eee}
 .error{background:#fdd;border:1px solid #c00;padding:10px;margin-bottom:15px}
 .sukses{background:#dfd;border:1px solid #070;padding:10px;margin-bottom:15px}
</style>
</head>
<body>
<h1>Data Barang</h1>
<p><a href="transaksi.php">&raquo; Ke halaman Transaksi</a></p>

<?php if ($pesan): ?><div class="<?= $tipe ?>"><?= e($pesan) ?></div><?php endif; ?>

<table>
<tr><th>ID</th><th>Kode</th><th>Nama Barang</th><th>Harga</th><th>Stok</th><th>Supplier</th><th>Dipakai di Transaksi</th><th>Aksi</th></tr>
<?php while ($r = $data->fetch_assoc()): ?>
<tr>
  <td><?= $r['id'] ?></td>
  <td><?= e($r['kode_barang']) ?></td>
  <td><?= e($r['nama_barang']) ?></td>
  <td><?= rupiah($r['harga']) ?></td>
  <td><?= $r['stok'] ?></td>
  <td><?= e($r['supplier']) ?></td>
  <td><?= $r['dipakai'] ?>x</td>
  <td>
    <form method="post" onsubmit="return confirm('Yakin hapus barang ini?')">
      <input type="hidden" name="hapus_id" value="<?= $r['id'] ?>">
      <button type="submit">Hapus</button>
    </form>
  </td>
</tr>
<?php endwhile; ?>
</table>
</body>
</html>
