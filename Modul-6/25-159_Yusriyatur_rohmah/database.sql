-- Database penjualan (sesuai diagram Modul 6)
CREATE DATABASE IF NOT EXISTS penjualan CHARACTER SET utf8mb4;
USE penjualan;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS pembayaran, transaksi_detail, transaksi, barang, supplier, pelanggan, user;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE supplier (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  telp VARCHAR(12),
  alamat TEXT
) ENGINE=InnoDB;

CREATE TABLE pelanggan (
  id VARCHAR(20) PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  jenis_kelamin ENUM('L','P') NOT NULL,
  telp VARCHAR(12),
  alamat TEXT
) ENGINE=InnoDB;

CREATE TABLE user (
  id_user TINYINT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(30) NOT NULL UNIQUE,
  password VARCHAR(35) NOT NULL,
  nama VARCHAR(50),
  alamat VARCHAR(150),
  hp VARCHAR(20),
  level TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB;

CREATE TABLE barang (
  id INT AUTO_INCREMENT PRIMARY KEY,
  kode_barang VARCHAR(10) NOT NULL UNIQUE,
  nama_barang VARCHAR(100) NOT NULL,
  harga INT NOT NULL,
  stok INT NOT NULL DEFAULT 0,
  supplier_id INT,
  -- SET NULL: jika supplier dihapus, barang tetap ada
  CONSTRAINT fk_barang_supplier FOREIGN KEY (supplier_id)
    REFERENCES supplier(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE transaksi (
  id INT AUTO_INCREMENT PRIMARY KEY,
  waktu_transaksi DATE NOT NULL,
  keterangan TEXT,
  total INT NOT NULL DEFAULT 0,
  pelanggan_id VARCHAR(20),
  CONSTRAINT fk_transaksi_pelanggan FOREIGN KEY (pelanggan_id)
    REFERENCES pelanggan(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Tabel perantara (junction) transaksi <-> barang
CREATE TABLE transaksi_detail (
  transaksi_id INT NOT NULL,
  barang_id INT NOT NULL,
  harga INT NOT NULL,
  qty INT NOT NULL,
  PRIMARY KEY (transaksi_id, barang_id),  -- barang sama tidak bisa 2x dalam 1 transaksi
  -- CASCADE: hapus transaksi -> detailnya ikut terhapus
  CONSTRAINT fk_detail_transaksi FOREIGN KEY (transaksi_id)
    REFERENCES transaksi(id) ON DELETE CASCADE ON UPDATE CASCADE,
  -- RESTRICT: barang yang sudah dipakai tidak boleh dihapus
  CONSTRAINT fk_detail_barang FOREIGN KEY (barang_id)
    REFERENCES barang(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE pembayaran (
  id INT AUTO_INCREMENT PRIMARY KEY,
  waktu_bayar DATETIME NOT NULL,
  total INT NOT NULL,
  metode ENUM('TUNAI','TRANSFER','EDC') NOT NULL,
  transaksi_id INT NOT NULL,
  CONSTRAINT fk_bayar_transaksi FOREIGN KEY (transaksi_id)
    REFERENCES transaksi(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ===================== DATA CONTOH =====================
INSERT INTO supplier (nama, telp, alamat) VALUES
('PT Sumber Pangan',   '031555001', 'Jl. Rungkut No. 10, Surabaya'),
('CV Maju Jaya',       '031555002', 'Jl. Darmo No. 25, Surabaya'),
('UD Berkah Tani',     '031555003', 'Jl. Gubeng No. 7, Surabaya'),
('PT Nusantara Susu',  '031555004', 'Jl. Ahmad Yani No. 88, Sidoarjo'),
('CV Indo Mi',         '031555005', 'Jl. Raya Waru No. 3, Sidoarjo');

INSERT INTO pelanggan (id, nama, jenis_kelamin, telp, alamat) VALUES
('P001', 'Budi Santoso',  'L', '081200000001', 'Jl. Mawar 1, Surabaya'),
('P002', 'Siti Aminah',   'P', '081200000002', 'Jl. Melati 2, Surabaya'),
('P003', 'Agus Pratama',  'L', '081200000003', 'Jl. Kenanga 3, Surabaya'),
('P004', 'Dewi Lestari',  'P', '081200000004', 'Jl. Anggrek 4, Surabaya'),
('P005', 'Rudi Hartono',  'L', '081200000005', 'Jl. Dahlia 5, Surabaya');

INSERT INTO user (username, password, nama, alamat, hp, level) VALUES
('admin',  MD5('admin123'), 'Administrator', 'Surabaya', '081300000001', 1),
('kasir1', MD5('kasir123'), 'Kasir Satu',    'Surabaya', '081300000002', 2),
('kasir2', MD5('kasir123'), 'Kasir Dua',     'Surabaya', '081300000003', 2),
('gudang', MD5('gudang123'),'Staf Gudang',   'Sidoarjo', '081300000004', 3),
('owner',  MD5('owner123'), 'Pemilik Toko',  'Surabaya', '081300000005', 1);

INSERT INTO barang (id, kode_barang, nama_barang, harga, stok, supplier_id) VALUES
(1, 'BRG001', 'Beras 5kg',          65000, 100, 1),
(2, 'BRG002', 'Minyak Goreng 2L',   36000,  80, 1),
(3, 'BRG003', 'Gula Pasir 1kg',     17000, 120, 2),
(4, 'BRG004', 'Telur Ayam 1kg',     28000,  60, 2),
(5, 'BRG005', 'Kopi Bubuk 200g',    22000,  90, 3),
(6, 'BRG006', 'Teh Celup 25s',       9000, 150, 3),
(7, 'BRG007', 'Susu UHT 1L',        18000,  70, 4),
(8, 'BRG008', 'Mie Instan',          3500, 300, 5);  -- belum dipakai transaksi (untuk uji hapus)

INSERT INTO transaksi (id, waktu_transaksi, keterangan, total, pelanggan_id) VALUES
(1,  '2026-11-01', 'Belanja bulanan',    0, 'P001'),
(2,  '2026-11-01', 'Belanja harian',     0, 'P002'),
(3,  '2026-11-02', 'Stok warung',        0, 'P003'),
(4,  '2026-11-02', 'Beli beras',         0, 'P004'),
(5,  '2026-11-03', 'Kebutuhan sarapan',  0, 'P005'),
(6,  '2026-11-04', 'Belanja mingguan',   0, 'P001'),
(7,  '2026-11-05', 'Belanja kecil',      0, 'P002'),
(8,  '2026-11-06', 'Belanja besar',      0, 'P003'),
(9,  '2026-11-07', 'Kebutuhan dapur',    0, 'P004'),
(10, '2026-11-08', 'Belanja akhir pekan',0, 'P005');

INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty) VALUES
(1,1,65000,2),(1,3,17000,1),
(2,2,36000,1),(2,4,28000,2),
(3,5,22000,2),(3,6, 9000,3),
(4,1,65000,1),
(5,7,18000,4),(5,3,17000,2),
(6,2,36000,2),(6,5,22000,1),(6,6,9000,2),
(7,4,28000,1),(7,7,18000,1),
(8,1,65000,3),(8,2,36000,1),
(9,3,17000,5),(9,6, 9000,1),
(10,5,22000,3),(10,7,18000,2),(10,4,28000,1);

-- Hitung total tiap transaksi dari detailnya
UPDATE transaksi t
SET t.total = (SELECT COALESCE(SUM(d.harga * d.qty),0)
               FROM transaksi_detail d WHERE d.transaksi_id = t.id);

INSERT INTO pembayaran (waktu_bayar, total, metode, transaksi_id)
SELECT CONCAT(waktu_transaksi,' 10:00:00'), total, 'TUNAI', id FROM transaksi WHERE id <= 5;
