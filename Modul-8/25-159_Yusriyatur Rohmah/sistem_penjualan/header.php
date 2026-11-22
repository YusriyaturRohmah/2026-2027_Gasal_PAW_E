<?php /* Dipakai semua halaman. Wajib: require 'auth.php' sebelum include ini. */
$level = $_SESSION['level'];
$halaman = basename($_SERVER['PHP_SELF']);
function aktif($f) { global $halaman; return $halaman === $f ? 'active' : ''; }
?>
<!DOCTYPE html>
<html lang="id"><head><meta charset="utf-8"><title>Sistem Penjualan</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.4.1/css/bootstrap.min.css">
</head><body>
<nav class="navbar navbar-inverse" style="background:#0b3d82;border:0">
 <div class="container">
  <div class="navbar-header"><a class="navbar-brand" href="index.php">Sistem Penjualan</a></div>
  <ul class="nav navbar-nav">
    <li class="<?= aktif('index.php') ?>"><a href="index.php">Home</a></li>

    <?php if ($level === 1): // hanya OWNER melihat Data Master ?>
    <li class="dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown">Data Master <b class="caret"></b></a>
      <ul class="dropdown-menu">
        <li><a href="barang.php">Data Barang</a></li>
        <li><a href="supplier.php">Data Supplier</a></li>
        <li><a href="pelanggan.php">Data Pelanggan</a></li>
        <li><a href="user.php">Data User</a></li>
      </ul>
    </li>
    <?php endif; ?>

    <li class="<?= aktif('transaksi.php') ?>"><a href="transaksi.php">Transaksi</a></li>
    <li class="<?= aktif('laporan.php') ?>"><a href="laporan.php">Laporan</a></li>
  </ul>
  <ul class="nav navbar-nav navbar-right">
    <li class="dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?= htmlspecialchars($_SESSION['nama']) ?> <b class="caret"></b></a>
      <ul class="dropdown-menu"><li><a href="logout.php">Logout</a></li></ul>
    </li>
  </ul>
 </div>
</nav>
<div class="container">