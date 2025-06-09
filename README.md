Aplikasi Inventaris Obat berbasis Web (PHP + MYSQL)
1. Setup Template + Setup Database
2. Login + Logout (tanpa enkripsi)
3. Setup Halaman + Tabel
4. INSERT data obat
5. Transaksi Obat (Obat keluar)
6. Tampilkan Data Obat

DB : 
-Pengguna: id_user(PK), username, password, peran, no_hp
-Obat: id_obat(PK), nama_obat,jenis_obat, stok, tgl_exp, tgl_produksi

-Stok Masuk: id_stok_masuk(PK),id_pengguna(FK),id_obat(FK),tgl_masuk,jml_masuk

-Stok Keluar: id_stok_keluar(PK),id_pengguna(FK),id_obat(FK),id_transaksi(FK),tgl_keluar,jml_keluar

-Transaksi:id_transaksi(PK),tgl_transaksi,jml_transaksi

-Laporan: id_laporan(PK),jenis_laporan,tgl_dibuat

Sitemap :
1. Login/Register page
2. Home/Dashboard
3. List of Medicine
4. Reports
5. Logout
 
Sintaks DB: 
-- Tabel Pengguna
CREATE TABLE pengguna (
    id_user INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(100) NOT NULL,
    peran ENUM('admin', 'staff', 'kepala_klinik') NOT NULL,
    no_hp VARCHAR(15)
) ENGINE=InnoDB;

-- Tabel Obat
CREATE TABLE obat (
    id_obat INT PRIMARY KEY AUTO_INCREMENT,
    nama_obat VARCHAR(100) NOT NULL,
    jenis_obat VARCHAR(50),
    stok INT NOT NULL DEFAULT 0,
    tgl_exp DATE,
    tgl_produksi DATE
) ENGINE=InnoDB;

-- Tabel Stok Masuk
CREATE TABLE stok_masuk (
    id_stok_masuk INT PRIMARY KEY AUTO_INCREMENT,
    id_pengguna INT,
    id_obat INT,
    tgl_masuk DATE NOT NULL,
    jml_masuk INT NOT NULL,
    FOREIGN KEY (id_pengguna) REFERENCES pengguna(id_user)
        ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (id_obat) REFERENCES obat(id_obat)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Tabel Transaksi
CREATE TABLE transaksi (
    id_transaksi INT PRIMARY KEY AUTO_INCREMENT,
    tgl_transaksi DATE NOT NULL,
    jml_transaksi INT NOT NULL
) ENGINE=InnoDB;

-- Tabel Stok Keluar
CREATE TABLE stok_keluar (
    id_stok_keluar INT PRIMARY KEY AUTO_INCREMENT,
    id_pengguna INT,
    id_obat INT,
    id_transaksi INT,
    tgl_keluar DATE NOT NULL,
    jml_keluar INT NOT NULL,
    FOREIGN KEY (id_pengguna) REFERENCES pengguna(id_user)
        ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (id_obat) REFERENCES obat(id_obat)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_transaksi) REFERENCES transaksi(id_transaksi)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Tabel Laporan
CREATE TABLE laporan (
    id_laporan INT PRIMARY KEY AUTO_INCREMENT,
    jenis_laporan ENUM('stok_obat', 'transaksi') NOT NULL,
    tgl_dibuat DATE NOT NULL
) ENGINE=InnoDB;
