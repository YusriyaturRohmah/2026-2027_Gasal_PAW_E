-- 1. Buat Database
CREATE DATABASE IF NOT EXISTS db_penjualan;
USE db_penjualan;

-- 2. Buat Tabel-Tabel
CREATE TABLE pelanggan (
    id VARCHAR(20) PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    jenis_kelamin ENUM('L', 'P') NOT NULL,
    telp VARCHAR(12),
    alamat TEXT
);

CREATE TABLE supplier (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    telp VARCHAR(12),
    alamat TEXT
);

CREATE TABLE barang (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    kode_barang VARCHAR(10) NOT NULL,
    nama_barang VARCHAR(100) NOT NULL,
    harga INT(11) NOT NULL,
    stok INT(11) NOT NULL,
    supplier_id INT(11),
    FOREIGN KEY (supplier_id) REFERENCES supplier(id) ON DELETE SET NULL ON UPDATE CASCADE
);

CREATE TABLE transaksi (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    waktu_transaksi DATE NOT NULL,
    keterangan TEXT,
    total INT(11) NOT NULL,
    pelanggan_id VARCHAR(20),
    FOREIGN KEY (pelanggan_id) REFERENCES pelanggan(id) ON DELETE SET NULL ON UPDATE CASCADE
);

CREATE TABLE transaksi_detail (
    transaksi_id INT(11),
    barang_id INT(11),
    harga INT(11) NOT NULL,
    qty INT(11) NOT NULL,
    PRIMARY KEY (transaksi_id, barang_id),
    FOREIGN KEY (transaksi_id) REFERENCES transaksi(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (barang_id) REFERENCES barang(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE pembayaran (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    waktu_bayar DATETIME NOT NULL,
    total INT(11) NOT NULL,
    metode ENUM('TUNAI', 'TRANSFER', 'EDC') NOT NULL,
    transaksi_id INT(11),
    FOREIGN KEY (transaksi_id) REFERENCES transaksi(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE user (
    id_user TINYINT(2) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) NOT NULL,
    password VARCHAR(35) NOT NULL,
    nama VARCHAR(50) NOT NULL,
    alamat VARCHAR(150),
    hp VARCHAR(20),
    level TINYINT(1) NOT NULL
);

-- 3. Isi Dummy Data (Minimal 10 Data Per Tabel)
INSERT INTO supplier (nama, telp, alamat) VALUES
('PT. Maju Bersama','08123456','Surabaya'),
('PT. Senang Sekali','081515563','Bangkalan'),
('PT. Segar Segar','0845454663','Surabaya'),
('CV. Jaya Abadi','081234500','Gresik'),
('PT. Sumber Rejeki','082111222','Sidoarjo'),
('CV. Mitra Niaga','083222333','Malang'),
('PT. Berkah Makmur','084333444','Lamongan'),
('CV. Sentosa','085444555','Mojokerto'),
('PT. Cahaya Baru','086555666','Surabaya'),
('CV. Tanpa Barang','087666777','Madura');

INSERT INTO barang (kode_barang, nama_barang, harga, stok, supplier_id) VALUES
('BRG001','Beras 5kg',65000,100,1),
('BRG002','Minyak Goreng 2L',38000,80,2),
('BRG003','Gula Pasir 1kg',16000,120,3),
('BRG004','Tepung Terigu 1kg',12000,90,4),
('BRG005','Telur 1kg',28000,60,5),
('BRG006','Kopi Bubuk 200g',22000,70,6),
('BRG007','Teh Celup 25s',8000,150,7),
('BRG008','Susu UHT 1L',18000,85,8),
('BRG009','Mie Instan',3500,500,9),
('BRG010','Sabun Mandi',4500,200,1);

INSERT INTO pelanggan (id, nama, jenis_kelamin, telp, alamat) VALUES
('PLG001','Andi Saputra','L','081300001','Gresik'),
('PLG002','Budi Santoso','L','081300002','Surabaya'),
('PLG003','Citra Dewi','P','081300003','Sidoarjo'),
('PLG004','Dewi Lestari','P','081300004','Malang'),
('PLG005','Eko Prasetyo','L','081300005','Lamongan'),
('PLG006','Fitri Handayani','P','081300006','Gresik'),
('PLG007','Gita Permata','P','081300007','Surabaya'),
('PLG008','Hendra Wijaya','L','081300008','Mojokerto'),
('PLG009','Indah Sari','P','081300009','Bangkalan'),
('PLG010','Joko Susilo','L','081300010','Madura');

INSERT INTO transaksi (waktu_transaksi, keterangan, total, pelanggan_id) VALUES
('2026-10-01','Belanja bulanan',65000,'PLG001'),
('2026-10-02','Pembelian rutin',76000,'PLG002'),
('2026-10-03','Belanja dapur',48000,'PLG003'),
('2026-10-04','Stok warung',60000,'PLG004'),
('2026-10-05','Belanja mingguan',56000,'PLG005'),
('2026-10-06','Kebutuhan kantor',44000,'PLG006'),
('2026-10-07','Belanja harian',16000,'PLG007'),
('2026-10-08','Pembelian susu',36000,'PLG008'),
('2026-10-09','Stok mie',35000,'PLG009'),
('2026-10-10','Kebutuhan mandi',9000,'PLG010');

INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty) VALUES
(1,1,65000,1),(2,2,38000,2),(3,3,16000,3),(4,4,12000,5),(5,5,28000,2),
(6,6,22000,2),(7,7,8000,2),(8,8,18000,2),(9,9,3500,10),(10,10,4500,2);

INSERT INTO pembayaran (waktu_bayar, total, metode, transaksi_id) VALUES
('2026-10-01 10:00:00',65000,'TUNAI',1),
('2026-10-02 11:00:00',76000,'TRANSFER',2),
('2026-10-03 12:00:00',48000,'EDC',3),
('2026-10-04 09:30:00',60000,'TUNAI',4),
('2026-10-05 13:15:00',56000,'TRANSFER',5),
('2026-10-06 14:00:00',44000,'EDC',6),
('2026-10-07 15:20:00',16000,'TUNAI',7),
('2026-10-08 16:45:00',36000,'TRANSFER',8),
('2026-10-09 08:10:00',35000,'EDC',9),
('2026-10-10 17:00:00',9000,'TUNAI',10);

INSERT INTO user (username, password, nama, alamat, hp, level) VALUES
('admin',MD5('admin123'),'Administrator','Gresik','089898764536',1),
('kasir1',MD5('kasir123'),'Kasir Satu','Gresik','082765463355',2),
('kasir2',MD5('kasir123'),'Kasir Dua','Surabaya','081122657684',2),
('gudang1',MD5('gudang123'),'Staf Gudang','Sidoarjo','0823887655',3),
('gudang2',MD5('gudang123'),'Staf Gudang Dua','Malang','081399800959',3),
('owner',MD5('owner123'),'Pemilik Toko','Gresik','085366122322',1),
('user1',MD5('user123'),'User satu','Lamongan','082189768900',2),
('user2',MD5('user123'),'User Dua','Mojokerto','089354442133',2),
('user3',MD5('user123'),'User tiga','Bangkalan','081394321155',2),
('user4',MD5('user123'),'User empat','Madura','082366980098',2);