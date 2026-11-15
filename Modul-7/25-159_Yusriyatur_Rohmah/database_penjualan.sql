-- File ini membuat database "penjualan", tabel transaksi, dan data contoh.

CREATE DATABASE IF NOT EXISTS penjualan;
USE penjualan;

DROP TABLE IF EXISTS transaksi;
CREATE TABLE transaksi (
  id INT AUTO_INCREMENT PRIMARY KEY,
  waktu_transaksi DATE,
  keterangan TEXT,
  total INT,
  pelanggan_id VARCHAR(20)
);

INSERT INTO transaksi (waktu_transaksi, keterangan, total, pelanggan_id) VALUES
('2023-09-01', 'Penjualan', 26000, 'P001'),
('2023-09-01', 'Penjualan', 13000, 'P002'),
('2023-09-02', 'Penjualan', 52000, 'P003'),
('2023-09-03', 'Penjualan', 13000, 'P004'),
('2023-09-04', 'Penjualan', 13000, 'P005'),
('2023-09-05', 'Penjualan', 13000, 'P006'),
('2023-09-05', 'Penjualan', 13000, 'P001'),
('2023-09-06', 'Penjualan', 13000, 'P007'),
('2023-09-09', 'Penjualan', 13000, 'P008');
