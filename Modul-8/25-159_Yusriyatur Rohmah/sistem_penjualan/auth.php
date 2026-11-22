<?php
// Di-include di SETIAP halaman yang butuh proteksi.
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/koneksi.php';

// Belum login -> tendang ke login.php
function wajib_login() {
    if (empty($_SESSION['id_user'])) { header('Location: login.php'); exit; }
}
// Cek level akses, mis. wajib_level([1]) = hanya owner
function wajib_level(array $boleh) {
    wajib_login();
    if (!in_array($_SESSION['level'], $boleh)) {
        http_response_code(403);
        die('<h3>403 - Anda tidak punya hak akses ke halaman ini.</h3><a href="index.php">Kembali</a>');
    }
}
