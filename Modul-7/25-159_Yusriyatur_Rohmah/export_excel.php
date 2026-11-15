<?php
// ===== export_excel.php =====
require 'koneksi.php';

$mulai   = $_GET['mulai']   ?? '';
$selesai = $_GET['selesai'] ?? '';

if ($mulai === '' || $selesai === '') {
    die("Rentang tanggal belum dipilih.");
}

$lap = ambil_laporan($koneksi, $mulai, $selesai);

// Header HTTP: beri tahu browser bahwa ini file Excel yang harus diunduh
$namaFile = "Rekap_Penjualan_{$mulai}_sd_{$selesai}.xls";
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$namaFile\"");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">
    <tr>
        <th colspan="3">Rekap Laporan Penjualan <?= htmlspecialchars($mulai) ?> sampai <?= htmlspecialchars($selesai) ?></th>
    </tr>
    <tr>
        <th>No</th>
        <th>Total Pendapatan</th>
        <th>Tanggal Transaksi</th>
    </tr>
    <?php $no = 1; foreach ($lap['rekap'] as $row): ?>
    <tr>
        <td><?= $no++; ?></td>
        <td><?= (int) $row['total_harian']; ?></td>
        <td><?= date('d M Y', strtotime($row['tgl'])); ?></td>
    </tr>
    <?php endforeach; ?>
    <tr>
        <td colspan="2"><b>Jumlah Pelanggan Unik</b></td>
        <td><?= $lap['total_pelanggan']; ?> Orang</td>
    </tr>
    <tr>
        <td colspan="2"><b>Total Pendapatan Keseluruhan</b></td>
        <td><?= $lap['total_pendapatan']; ?></td>
    </tr>
</table>
