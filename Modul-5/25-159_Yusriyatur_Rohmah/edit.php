<?php
include 'koneksi.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("UPDATE supplier SET nama=?, telp=?, alamat=? WHERE id=?");
    $stmt->bind_param("sssi", $_POST['nama'], $_POST['telp'], $_POST['alamat'], $_POST['id']);
    $stmt->execute();
    header("Location: index.php?pesan=Data berhasil diupdate");
    exit;
}
$id = (int)($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM supplier WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$d = $stmt->get_result()->fetch_assoc();
if (!$d) { header("Location: index.php?pesan=Data tidak ditemukan"); exit; }
include 'header.php';
?>
<h2 style="color:#337ab7">Edit Data Master Supplier</h2><hr>
<form method="post" class="form-horizontal">
  <input type="hidden" name="id" value="<?= $d['id'] ?>">
  <div class="form-group"><label class="col-sm-3 control-label">Nama</label>
    <div class="col-sm-6"><input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($d['nama']) ?>" maxlength="100" required></div></div>
  <div class="form-group"><label class="col-sm-3 control-label">Telp</label>
    <div class="col-sm-4"><input type="text" name="telp" class="form-control" value="<?= htmlspecialchars($d['telp']) ?>" maxlength="12" required></div></div>
  <div class="form-group"><label class="col-sm-3 control-label">Alamat</label>
    <div class="col-sm-4"><input type="text" name="alamat" class="form-control" value="<?= htmlspecialchars($d['alamat']) ?>" required></div></div>
  <div class="form-group"><div class="col-sm-offset-3 col-sm-6">
    <button type="submit" class="btn btn-success">Update</button>
    <a href="index.php" class="btn btn-danger">Batal</a></div></div>
</form>
</div></body></html>
