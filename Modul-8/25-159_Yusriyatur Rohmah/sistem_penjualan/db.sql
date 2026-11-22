-- Import file ini lewat phpMyAdmin (tab Import) atau tempel di tab SQL.
-- PERHATIAN: tabel `user` lama akan DIHAPUS lalu dibuat ulang.
CREATE DATABASE IF NOT EXISTS db_penjualan;
USE db_penjualan;

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  id_user  INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50)  NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  nama     VARCHAR(100) NOT NULL,
  level    TINYINT      NOT NULL COMMENT '1=owner, 2=kasir'
);

-- Akun contoh (password sudah di-hash): budi / budi123 (owner), wati / wati123 (kasir)
INSERT INTO `user` (username, password, nama, level) VALUES
('budi', '$2y$10$BB/26FKq7M42ZmD20GgKH.bqt/B6bFYzEatFwPagyWchJhQa2L5Ei', 'Budi', 1),
('wati', '$2y$10$67pgB/q4fhcK9kF.93PIbeQonxG0139I3DTOEJutMRQbFBrwdNfqW', 'Wati', 2);