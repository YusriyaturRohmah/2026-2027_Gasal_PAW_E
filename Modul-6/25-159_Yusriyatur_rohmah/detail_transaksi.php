<?php
// Halaman MASTER-DETAIL
// - Atas  : data master (tabel transaksi) yang dipilih lewat ?id=
// - Bawah : data detail (transaksi_detail) milik transaksi tsb + form input detail
// Tugas No. 2 (dropdown tanpa barang yang sudah diinput) dan No. 4 (update total otomatis)
require 'koneksi.php';

$transaksi_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// ---- Ambil data MASTER ----
$st = $koneksi->prepare(
    "SELECT t.*, p.nama AS pelanggan
     FROM transaksi t LEFT JOIN pelanggan p ON p.id = t.pelanggan_id
     WHERE t.id = ?"
);
$st->bind_param("i", $transaksi_id);
$st->execute();
$master = $st->get_result()->fetch_assoc();
if (!$master) { die("Transaksi tidak ditemukan. <a href='transaksi.php'>Kembali</a>"); }

$pesan = ''; $tipe = '';

// ---- Simpan DETAIL baru ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $barang_id = (int)$_POST['barang_id'];
    $qty       = (int)$_POST['qty'];

    if ($barang_id <= 0 || $qty <= 0) {
        $pesan = "Pilih barang dan isi qty minimal 1."; $tipe = 'error';
    } else {
        // Validasi sisi server: barang tidak boleh sudah ada di transaksi ini
        $cek = $koneksi->prepare("SELECT COUNT(*) AS jml FROM transaksi_detail WHERE transaksi_id=? AND barang_id=?");
        $cek->bind_param("ii", $transaksi_id, $barang_id);
        $cek->execute();
        if ($cek->get_result()->fetch_assoc()['jml'] > 0) {
            $pesan = "Barang tersebut sudah ada pada transaksi ini."; $tipe = 'error';
        } else {
            // Harga diambil dari tabel barang (bukan dari input user) agar tidak bisa dimanipulasi
            $hb = $koneksi->prepare("SELECT harga FROM barang WHERE id = ?");
            $hb->bind_param("i", $barang_id);
            $hb->execute();
            $brg = $hb->get_result()->fetch_assoc();

            if (!$brg) {
                $pesan = "Barang tidak ditemukan."; $tipe = 'error';
            } else {
                // Transaksi database: simpan detail + update total harus berhasil bersama-sama
                $koneksi->begin_transaction();
                try {
                    $ins = $koneksi->prepare("INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty) VALUES (?,?,?,?)");
                    $ins->bind_param("iiii", $transaksi_id, $barang_id, $brg['harga'], $qty);
                    $ins->execute();

                    // Tugas No. 4: hitung ulang total = SUM(harga * qty) semua detail transaksi ini
                    $upd = $koneksi->prepare(
                        "UPDATE transaksi
                         SET total = (SELECT COALESCE(SUM(harga * qty), 0)
                                      FROM transaksi_detail WHERE transaksi_id = ?)
                         WHERE id = ?"
                    );
                    $upd->bind_param("ii", $transaksi_id, $transaksi_id);
                    $upd->execute();

                    $koneksi->commit();
                    // Redirect agar refresh halaman tidak menyimpan data dua kali
                    header("Location: detail_transaksi.php?id=$transaksi_id&ok=1");
                    exit;
                } catch (Throwable $ex) {
                    $koneksi->rollback();
                    $pesan = "Gagal menyimpan detail: " . $ex->getMessage(); $tipe = 'error';
                }
            }
        }
    }
}
if (isset($_GET['ok'])) { $pesan = "Detail transaksi berhasil disimpan dan total diperbarui."; $tipe = 'sukses'; }

// ---- Ambil ulang MASTER (total terbaru) ----
$st->execute();
$master = $st->get_result()->fetch_assoc();

// ---- Ambil DETAIL ----
$dt = $koneksi->prepare(
    "SELECT d.*, b.kode_barang, b.nama_barang
     FROM transaksi_detail d JOIN barang b ON b.id = d.barang_id
     WHERE d.transaksi_id = ? ORDER BY b.nama_barang"
);
$dt->bind_param("i", $transaksi_id);
$dt->execute();
$detail = $dt->get_result();

// ---- Dropdown barang: hanya barang yang BELUM ada di transaksi ini (Tugas No. 2) ----
$db = $koneksi->prepare(
    "SELECT id, kode_barang, nama_barang, harga, stok FROM barang
     WHERE id NOT IN (SELECT barang_id FROM transaksi_detail WHERE transaksi_id = ?)
     ORDER BY nama_barang"
);
$db->bind_param("i", $transaksi_id);
$db->execute();
$pilihan = $db->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Detail Transaksi #<?= $transaksi_id ?></title>
<style>
 body{font-family:Arial,sans-serif;margin:30px}
 table{border-collapse:collapse;width:100%;margin-bottom:20px}
 th,td{border:1px solid #999;padding:6px 10px}
 th{background:#eee}
 .master td{border:none;padding:3px 10px 3px 0}
 .error{background:#fdd;border:1px solid #c00;padding:10px;margin-bottom:15px}
 .sukses{background:#dfd;border:1px solid #070;padding:10px;margin-bottom:15px}
 .kanan{text-align:right}
</style>
</head>
<body>
<p><a href="transaksi.php">&laquo; Kembali ke daftar transaksi</a></p>
<h1>Detail Transaksi #<?= $transaksi_id ?></h1>

<?php if ($pesan): ?><div class="<?= $tipe ?>"><?= e($pesan) ?></div><?php endif; ?>

<!-- ===== MASTER ===== -->
<h3>Data Master (Transaksi)</h3>
<table class="master" style="width:auto">
<tr><td>No. Transaksi</td><td>: <?= $master['id'] ?></td></tr>
<tr><td>Tanggal</td><td>: <?= e($master['waktu_transaksi']) ?></td></tr>
<tr><td>Pelanggan</td><td>: <?= e($master['pelanggan']) ?></td></tr>
<tr><td>Keterangan</td><td>: <?= e($master['keterangan']) ?></td></tr>
<tr><td><b>Total</b></td><td>: <b><?= rupiah($master['total']) ?></b></td></tr>
</table>

<!-- ===== DETAIL ===== -->
<h3>Data Detail (Barang dalam Transaksi)</h3>
<table>
<tr><th>No</th><th>Kode</th><th>Barang</th><th>Harga</th><th>Qty</th><th>Subtotal</th></tr>
<?php $no = 1; while ($d = $detail->fetch_assoc()): ?>
<tr>
  <td><?= $no++ ?></td>
  <td><?= e($d['kode_barang']) ?></td>
  <td><?= e($d['nama_barang']) ?></td>
  <td class="kanan"><?= rupiah($d['harga']) ?></td>
  <td class="kanan"><?= $d['qty'] ?></td>
  <td class="kanan"><?= rupiah($d['harga'] * $d['qty']) ?></td>
</tr>
<?php endwhile; ?>
<?php if ($no === 1): ?><tr><td colspan="6">Belum ada detail.</td></tr><?php endif; ?>
<tr><th colspan="5" class="kanan">Total</th><th class="kanan"><?= rupiah($master['total']) ?></th></tr>
</table>

<!-- ===== FORM INPUT DETAIL ===== -->
<fieldset style="max-width:480px">
<legend>Tambah Barang ke Transaksi</legend>
<?php if ($pilihan->num_rows === 0): ?>
  <p>Semua barang sudah dimasukkan ke transaksi ini.</p>
<?php else: ?>
<form method="post">
  <p>Barang<br>
    <select name="barang_id" required>
      <option value="">-- pilih barang --</option>
      <?php while ($b = $pilihan->fetch_assoc()): ?>
        <option value="<?= $b['id'] ?>">
          <?= e($b['kode_barang'] . ' - ' . $b['nama_barang'] . ' (' . rupiah($b['harga']) . ')') ?>
        </option>
      <?php endwhile; ?>
    </select>
  </p>
  <p>Qty<br><input type="number" name="qty" min="1" value="1" required></p>
  <button type="submit">Simpan Detail</button>
</form>
<?php endif; ?>
</fieldset>
</body>
</html>
