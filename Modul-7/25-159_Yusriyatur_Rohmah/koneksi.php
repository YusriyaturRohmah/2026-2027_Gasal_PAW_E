<?php
// ===== koneksi.php =====
// Koneksi database + fungsi pengambil data laporan (dipakai laporan.php & export_excel.php)

// ---- PENGATURAN DATABASE (ubah di sini jika perlu) ----
$db_host = "localhost";
$db_user = "root";     // default XAMPP
$db_pass = "";         // default XAMPP: kosong
$db_name = "penjualan";
// -------------------------------------------------------

mysqli_report(MYSQLI_REPORT_OFF); // matikan exception otomatis, kita tangani sendiri

$koneksi = @mysqli_connect($db_host, $db_user, $db_pass, $db_name);
if (!$koneksi) {
    die("<h3>Koneksi database gagal</h3>
         <p>Pesan: " . htmlspecialchars(mysqli_connect_error()) . "</p>
         <ul>
           <li>Pastikan <b>MySQL</b> sudah di-Start di XAMPP Control Panel.</li>
           <li>Pastikan database <b>$db_name</b> sudah dibuat (import file <b>database_penjualan.sql</b>).</li>
           <li>Cek username/password di file koneksi.php.</li>
         </ul>");
}

/**
 * Mengambil rekap harian + ringkasan total pada rentang tanggal.
 * Memakai prepared statement agar aman dari SQL Injection.
 */
function ambil_laporan($koneksi, $mulai, $selesai)
{
    $hasil = [
        'rekap'            => [],   // baris per hari: tgl, total_harian
        'total_pendapatan' => 0,
        'total_pelanggan'  => 0,
    ];

    // 1) Agregasi pendapatan per hari
    $sql = "SELECT DATE(waktu_transaksi) AS tgl, SUM(total) AS total_harian
            FROM transaksi
            WHERE waktu_transaksi BETWEEN ? AND ?
            GROUP BY DATE(waktu_transaksi)
            ORDER BY tgl ASC";
    $stmt = mysqli_prepare($koneksi, $sql);
    if (!$stmt) {
        die("Query gagal: " . htmlspecialchars(mysqli_error($koneksi)) . " (cek tabel transaksi sudah ada?)");
    }
    mysqli_stmt_bind_param($stmt, "ss", $mulai, $selesai);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $tgl, $total_harian);
    while (mysqli_stmt_fetch($stmt)) {
        $hasil['rekap'][] = ['tgl' => $tgl, 'total_harian' => $total_harian];
        $hasil['total_pendapatan'] += (int) $total_harian;
    }
    mysqli_stmt_close($stmt);

    // 2) Jumlah pelanggan unik (tanpa duplikasi)
    $sql2 = "SELECT COUNT(DISTINCT pelanggan_id)
             FROM transaksi
             WHERE waktu_transaksi BETWEEN ? AND ?";
    $stmt2 = mysqli_prepare($koneksi, $sql2);
    mysqli_stmt_bind_param($stmt2, "ss", $mulai, $selesai);
    mysqli_stmt_execute($stmt2);
    mysqli_stmt_bind_result($stmt2, $jml);
    mysqli_stmt_fetch($stmt2);
    $hasil['total_pelanggan'] = (int) $jml;
    mysqli_stmt_close($stmt2);

    return $hasil;
}
