<?php
include 'koneksi.php';
include 'header.php';
$result = $conn->query("SELECT * FROM supplier ORDER BY id");
?>
<h3 style="color:#337ab7">Data Master Supplier
  <a href="tambah.php" class="btn btn-success btn-sm pull-right">Tambah Data</a>
</h3>
<hr>
<?php if (isset($_GET['pesan'])): ?>
  <div class="alert alert-info"><?= htmlspecialchars($_GET['pesan']) ?></div>
<?php endif; ?>
<table class="table table-bordered table-hover">
  <thead><tr class="info">
    <th>No</th><th>Nama</th><th>Telp</th><th>Alamat</th><th>Tindakan</th>
  </tr></thead>
  <tbody>
  <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?= $no++ ?></td>
      <td><?= htmlspecialchars($row['nama']) ?></td>
      <td><?= htmlspecialchars($row['telp']) ?></td>
      <td><?= htmlspecialchars($row['alamat']) ?></td>
      <td>
        <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-xs">Edit</a>
        <!-- Soal 6: popup konfirmasi hapus -->
        <a href="hapus.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-xs"
           onclick="return confirm('Anda yakin akan menghapus supplier ini?')">Hapus</a>
      </td>
    </tr>
  <?php endwhile; ?>
  </tbody>
</table>
</div></body></html>