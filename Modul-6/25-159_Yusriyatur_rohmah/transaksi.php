<?php
// Halaman MASTER: daftar transaksi + form tambah transaksi
require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tgl  = $_POST['waktu_transaksi'];
    $ket  = trim($_POST['keterangan']);
    $plg  = $_POST['pelanggan_id'];
    // total awal 0, akan dihitung otomatis saat detail disimpan
    $st = $koneksi->prepare("INSERT INTO transaksi (waktu_transaksi, keterangan, total, pelanggan_id) VALUES (?, ?, 0, ?)");
    $st->bind_param("sss", $tgl, $ket, $plg);
    $st->execute();
    // langsung arahkan ke halaman detail transaksi baru
    header("Location: detail_transaksi.php?id=" . $koneksi->insert_id);
    exit;
}

$transaksi = $koneksi->query(
    "SELECT t.*, p.nama AS pelanggan
     FROM transaksi t LEFT JOIN pelanggan p ON p.id = t.pelanggan_id
     ORDER BY t.id DESC"
);
$pelanggan = $koneksi->query("SELECT id, nama FROM pelanggan ORDER BY nama");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Transaksi (Master)</title>
<style>
 body{font-family:Arial,sans-serif;margin:30px}
 table{border-collapse:collapse;width:100%;margin-bottom:25px}
 th,td{border:1px solid #999;padding:6px 10px}
 th{background:#eee}
 fieldset{margin-bottom:25px;max-width:480px}
 label{display:block;margin:6px 0}
</style>
</head>
<body>
<h1>Data Transaksi (Master)</h1>
<p><a href="barang.php">&raquo; Ke halaman Barang</a></p>

<fieldset>
<legend>Tambah Transaksi Baru</legend>
<form method="post">
  <label>Tanggal <input type="date" name="waktu_transaksi" value="<?= date('Y-m-d') ?>" required></label>
  <label>Pelanggan
    <select name="pelanggan_id" required>
      <option value="">-- pilih --</option>
      <?php while ($p = $pelanggan->fetch_assoc()): ?>
        <option value="<?= e($p['id']) ?>"><?= e($p['id'] . ' - ' . $p['nama']) ?></option>
      <?php endwhile; ?>
    </select>
  </label>
  <label>Keterangan<br><textarea name="keterangan" rows="2" cols="40"></textarea></label>
  <button type="submit">Simpan &amp; Isi Detail</button>
</form>
</fieldset>

<table>
<tr><th>ID</th><th>Tanggal</th><th>Pelanggan</th><th>Keterangan</th><th>Total</th><th>Aksi</th></tr>
<?php while ($r = $transaksi->fetch_assoc()): ?>
<tr>
  <td><?= $r['id'] ?></td>
  <td><?= e($r['waktu_transaksi']) ?></td>
  <td><?= e($r['pelanggan']) ?></td>
  <td><?= e($r['keterangan']) ?></td>
  <td><?= rupiah($r['total']) ?></td>
  <td><a href="detail_transaksi.php?id=<?= $r['id'] ?>">Lihat / Input Detail</a></td>
</tr>
<?php endwhile; ?>
</table>
</body>
</html>
