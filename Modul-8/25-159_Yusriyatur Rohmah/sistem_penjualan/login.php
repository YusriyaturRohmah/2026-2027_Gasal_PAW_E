<?php
session_start();
require 'koneksi.php';

// Sudah login -> tidak boleh buka halaman login
if (!empty($_SESSION['id_user'])) { header('Location: index.php'); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Username dan password wajib diisi.';
    } else {
        $st = $conn->prepare("SELECT id_user, username, password, nama, level FROM user WHERE username = ?");
        $st->bind_param('s', $username);
        $st->execute();
        $u = $st->get_result()->fetch_assoc();

        if ($u && password_verify($password, $u['password'])) {
            session_regenerate_id(true);           // cegah session fixation
            $_SESSION['id_user'] = $u['id_user'];  // SET SESSION
            $_SESSION['nama']    = $u['nama'];
            $_SESSION['level']   = (int)$u['level'];
            header('Location: index.php'); exit;
        }
        $error = 'Username atau password salah.';
    }
}
?>
<!DOCTYPE html>
<html lang="id"><head><meta charset="utf-8"><title>Login</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.4.1/css/bootstrap.min.css">
</head><body style="background:#eef1f5">
<div class="container" style="max-width:380px;margin-top:100px">
  <div class="panel panel-primary"><div class="panel-heading"><b>Sistem Penjualan - Login</b></div>
  <div class="panel-body">
    <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <form method="post" autocomplete="off">
      <div class="form-group"><label>Username</label>
        <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" autofocus></div>
      <div class="form-group"><label>Password</label>
        <input type="password" name="password" class="form-control"></div>
      <button class="btn btn-primary btn-block">Login</button>
    </form>
  </div></div>
</div></body></html>