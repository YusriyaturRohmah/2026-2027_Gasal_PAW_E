<?php
require 'auth.php';
wajib_level([1]);   // hanya owner

$pesan = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['aksi'] ?? '';
    $id   = (int)($_POST['id_user'] ?? 0);

    if ($aksi === 'hapus') {
        if ($id === (int)$_SESSION['id_user']) { $pesan = 'Tidak bisa menghapus akun sendiri.'; }
        else { $st = $conn->prepare("DELETE FROM user WHERE id_user=?"); $st->bind_param('i', $id); $st->execute(); $pesan = 'User dihapus.'; }
    } else { // tambah / ubah
        $username = trim($_POST['username'] ?? '');
        $nama     = trim($_POST['nama'] ?? '');
        $level    = (int)($_POST['level'] ?? 0);
        $pass     = $_POST['password'] ?? '';
        if ($username === '' || $nama === '' || !in_array($level, [1,2])) {
            $pesan = 'Username, nama, dan level wajib diisi.';
        } elseif ($aksi === 'tambah') {
            if ($pass === '') { $pesan = 'Password wajib diisi.'; }
            else {
                $h = password_hash($pass, PASSWORD_DEFAULT);
                $st = $conn->prepare("INSERT INTO user (username,password,nama,level) VALUES (?,?,?,?)");
                $st->bind_param('sssi', $username, $h, $nama, $level);
                $pesan = $st->execute() ? 'User ditambahkan.' : 'Gagal (username mungkin sudah dipakai).';
            }
        } elseif ($aksi === 'ubah') {
            if ($pass !== '') { // password diganti hanya jika diisi
                $h = password_hash($pass, PASSWORD_DEFAULT);
                $st = $conn->prepare("UPDATE user SET username=?, password=?, nama=?, level=? WHERE id_user=?");
                $st->bind_param('sssii', $username, $h, $nama, $level, $id);
            } else {
                $st = $conn->prepare("UPDATE user SET username=?, nama=?, level=? WHERE id_user=?");
                $st->bind_param('ssii', $username, $nama, $level, $id);
            }
            $pesan = $st->execute() ? 'User diperbarui.' : 'Gagal memperbarui.';
        }
    }
}

// Mode edit: ?edit=ID
$e = ['id_user'=>'', 'username'=>'', 'nama'=>'', 'level'=>2];
if (isset($_GET['edit'])) {
    $st = $conn->prepare("SELECT id_user,username,nama,level FROM user WHERE id_user=?");
    $i = (int)$_GET['edit']; $st->bind_param('i', $i); $st->execute();
    $e = $st->get_result()->fetch_assoc() ?: $e;
}
$rows = $conn->query("SELECT * FROM user ORDER BY id_user");
include 'header.php';
?>
<h3>Data User</h3>
<?php if ($pesan): ?><div class="alert alert-info"><?= htmlspecialchars($pesan) ?></div><?php endif; ?>

<form method="post" class="well form-inline">
  <input type="hidden" name="aksi" value="<?= $e['id_user'] ? 'ubah' : 'tambah' ?>">
  <input type="hidden" name="id_user" value="<?= (int)$e['id_user'] ?>">
  <input class="form-control" name="username" placeholder="Username" value="<?= htmlspecialchars($e['username']) ?>">
  <input class="form-control" name="nama" placeholder="Nama" value="<?= htmlspecialchars($e['nama']) ?>">
  <input class="form-control" type="password" name="password" placeholder="<?= $e['id_user'] ? 'Password (kosongkan jika tidak diganti)' : 'Password' ?>">
  <select class="form-control" name="level">
    <option value="1" <?= $e['level']==1?'selected':'' ?>>1 - Owner</option>
    <option value="2" <?= $e['level']==2?'selected':'' ?>>2 - Kasir</option>
  </select>
  <button class="btn btn-primary"><?= $e['id_user'] ? 'Simpan' : 'Tambah' ?></button>
  <?php if ($e['id_user']): ?><a class="btn btn-default" href="user.php">Batal</a><?php endif; ?>
</form>

<table class="table table-bordered table-striped">
  <tr><th>No</th><th>Username</th><th>Nama</th><th>Level</th><th width="160">Aksi</th></tr>
  <?php $no=1; while ($r = $rows->fetch_assoc()): ?>
  <tr>
    <td><?= $no++ ?></td>
    <td><?= htmlspecialchars($r['username']) ?></td>
    <td><?= htmlspecialchars($r['nama']) ?></td>
    <td><?= $r['level']==1 ? 'Owner' : 'Kasir' ?></td>
    <td>
      <a class="btn btn-warning btn-xs" href="user.php?edit=<?= $r['id_user'] ?>">Edit</a>
      <form method="post" style="display:inline" onsubmit="return confirm('Hapus user ini?')">
        <input type="hidden" name="aksi" value="hapus">
        <input type="hidden" name="id_user" value="<?= $r['id_user'] ?>">
        <button class="btn btn-danger btn-xs">Hapus</button>
      </form>
    </td>
  </tr>
  <?php endwhile; ?>
</table>
<?php include 'footer.php'; ?>