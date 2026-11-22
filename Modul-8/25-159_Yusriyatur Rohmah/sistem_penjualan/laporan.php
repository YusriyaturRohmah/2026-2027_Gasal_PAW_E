<?php
session_start();
include "koneksi.php";

// Hanya terima request POST
if ($_SERVER['REQUEST_METHOD'] != "POST") {
    header("Location: login.php");
    exit;
}

$username = trim($_POST['username']);
$password = $_POST['password'];

// Validasi: tidak boleh kosong
if ($username == "" || $password == "") {
    header("Location: login.php?pesan=kosong");
    exit;
}

// Ambil data user dengan prepared statement
$stmt = mysqli_prepare($koneksi,
    "SELECT id_user, username, password, nama, level
     FROM user WHERE username = ?");
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$hasil = mysqli_stmt_get_result($stmt);
$user  = mysqli_fetch_assoc($hasil);

// Cek user ada dan password cocok dengan hash
if ($user && password_verify($password, $user['password'])) {

    session_regenerate_id(true);

    // SET SESSION
    $_SESSION['id_user']   = $user['id_user'];
    $_SESSION['username']  = $user['username'];
    $_SESSION['nama_user'] = $user['nama'];
    $_SESSION['level']     = $user['level'];

    header("Location: index.php");
    exit;
} else {
    header("Location: login.php?pesan=gagal");
    exit;
}
?>