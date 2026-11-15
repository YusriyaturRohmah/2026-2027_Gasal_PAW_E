<?php
// ===== laporan.php =====
require 'koneksi.php';

$mulai   = $_GET['mulai']   ?? '';
$selesai = $_GET['selesai'] ?? '';
$tampil  = ($mulai !== '' && $selesai !== '');

$labels = [];
$data_total = [];
$rekap = [];
$total_pendapatan = 0;
$total_pelanggan = 0;

if ($tampil) {
    $lap = ambil_laporan($koneksi, $mulai, $selesai);
    $rekap = $lap['rekap'];
    $total_pendapatan = $lap['total_pendapatan'];
    $total_pelanggan = $lap['total_pelanggan'];
    foreach ($rekap as $r) {
        $labels[] = $r['tgl'];
        $data_total[] = (int) $r['total_harian'];
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan dan Grafik</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; color: #333; }
        h1 small, h2 small { font-size: 14px; color: #666; font-weight: normal; }
        .filter-box { margin-bottom: 15px; }
        .filter-box input { padding: 5px; }
        .btn { padding: 7px 14px; border: 0; border-radius: 3px; color: #fff; cursor: pointer; text-decoration: none; font-size: 14px; }
        .btn-tampil { background: #0d6efd; }
        .btn-cetak  { background: #e8590c; }
        .btn-excel  { background: #198754; }
        .chart-wrap { width: 700px; max-width: 100%; height: 320px; }
        table { border-collapse: collapse; width: 100%; margin-top: 15px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #d9edf7; }
        .card { display: inline-block; margin-top: 15px; border: 1px solid #ccc; }
        .card .head { display: flex; background: #d9edf7; font-weight: bold; }
        .card .body { display: flex; }
        .card .head div, .card .body div { padding: 8px 25px; min-width: 150px; text-align: right; }

        /* Saat dicetak: sembunyikan form filter & tombol */
        @media print {
            .no-print { display: none !important; }
            body { margin: 0; }
            th { background: #d9edf7 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>

<!-- Bagian ini TIDAK ikut tercetak -->
<div class="no-print">
    <h1>Filter Laporan Penjualan</h1>
    <form method="GET" class="filter-box">
        Dari: <input type="date" name="mulai" value="<?= htmlspecialchars($mulai) ?>" required>
        Sampai: <input type="date" name="selesai" value="<?= htmlspecialchars($selesai) ?>" required>
        <button type="submit" class="btn btn-tampil">Tampilkan</button>
    </form>

    <?php if ($tampil && !empty($rekap)): ?>
        <button class="btn btn-cetak" onclick="window.print()">&#128424; Cetak</button>
        <a class="btn btn-excel"
           href="export_excel.php?mulai=<?= urlencode($mulai) ?>&selesai=<?= urlencode($selesai) ?>">Excel</a>
    <?php endif; ?>
</div>

<?php if ($tampil && !empty($rekap)): ?>

    <h2>Rekap Laporan Penjualan <small><?= htmlspecialchars($mulai) ?> sampai <?= htmlspecialchars($selesai) ?></small></h2>

    <!-- Grafik batang -->
    <div class="chart-wrap"><canvas id="myChart"></canvas></div>
    <script>
        const ctx = document.getElementById('myChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($labels); ?>,
                datasets: [{
                    label: 'Total Pendapatan',
                    data: <?= json_encode($data_total); ?>,
                    backgroundColor: 'rgba(13, 110, 253, 0.6)',
                    borderColor: 'rgba(13, 110, 253, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: false,              // supaya grafik langsung tampil penuh saat dicetak
                scales: {
                    x: { title: { display: true, text: 'Tanggal Transaksi' } },
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Total Pendapatan (Rp)' }
                    }
                }
            }
        });
    </script>

    <!-- Tabel rekapitulasi -->
    <table>
        <tr><th>No</th><th>Total Pendapatan</th><th>Tanggal Transaksi</th></tr>
        <?php $no = 1; foreach ($rekap as $row): ?>
        <tr>
            <td><?= $no++; ?></td>
            <td>Rp. <?= number_format($row['total_harian'], 0, ',', '.'); ?></td>
            <td><?= date('d M Y', strtotime($row['tgl'])); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <!-- Card ringkasan total kumulatif -->
    <div class="card">
        <div class="head"><div>Jumlah Pelanggan</div><div>Jumlah Pendapatan</div></div>
        <div class="body">
            <div><?= $total_pelanggan; ?> Orang</div>
            <div>Rp. <?= number_format($total_pendapatan, 0, ',', '.'); ?></div>
        </div>
    </div>

<?php elseif ($tampil): ?>
    <p>Tidak ada transaksi pada rentang tanggal tersebut.</p>
<?php endif; ?>

</body>
</html>
