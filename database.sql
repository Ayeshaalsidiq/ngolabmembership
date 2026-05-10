CREATE DATABASE IF NOT EXISTS ngolab_db;
USE ngolab_db;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `whatsapp` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `poin` int(11) DEFAULT 0,
  `tier` varchar(20) DEFAULT 'Silver',
  `role` varchar(20) DEFAULT 'Pengunjung',
  `nim` varchar(30) DEFAULT NULL,
  `ktm_path` varchar(255) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
);

CREATE TABLE `menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kategori` varchar(50) NOT NULL,
  `nama_menu` varchar(100) NOT NULL,
  `deskripsi` text,
  `harga` int(11) NOT NULL,
  `poin_didapat` int(11) DEFAULT 0,
  `gambar` varchar(255) DEFAULT NULL,
  `is_promo` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
);

CREATE TABLE `rewards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_reward` varchar(100) NOT NULL,
  `poin_dibutuhkan` int(11) NOT NULL,
  `tier_minimal` varchar(20) DEFAULT 'Silver',
  `gambar` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `activities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `jenis_aktivitas` varchar(100) NOT NULL,
  `jumlah_poin` int(11) NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
);

-- Insert Dummy Data for menus and rewards
INSERT INTO `menus` (`kategori`, `nama_menu`, `deskripsi`, `harga`, `poin_didapat`, `gambar`, `is_promo`) VALUES
('BAKSO', 'Bakso Urat Spesial', 'Kuah kaldu gurih, urat sapi pilihan.', 20000, 20, 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80', 0),
('MIE YAMIN', 'Mie Yamin Manis', 'Ayam cincang, pangsit rebus.', 18000, 18, 'https://images.unsplash.com/photo-1626804475297-41609ea064eb?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80', 0);

INSERT INTO `rewards` (`nama_reward`, `poin_dibutuhkan`, `tier_minimal`, `gambar`) VALUES
('Es Teh Manis', 500, 'Silver', 'https://images.unsplash.com/photo-1622483767028-3f66f32aef97?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'),
('Diskon 20% Menu Spesial', 800, 'Gold', 'https://images.unsplash.com/photo-1612929633738-8fe44f7ec841?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80');

-- Insert Dummy User (ID 1) untuk testing tanpa login
INSERT INTO `users` (`id`, `nama`, `whatsapp`, `email`, `password`, `poin`, `tier`) VALUES
(1, 'Rusdi Mahasiswa', '081234567890', 'rusdi@student.ac.id', 'dummy_hash', 1250, 'Gold');

-- Insert Dummy Activities untuk User ID 1
INSERT INTO `activities` (`user_id`, `jenis_aktivitas`, `jumlah_poin`) VALUES
(1, 'Penambahan Poin Pembelian', 50),
(1, 'Redeem Hadiah (Es Teh)', -500),
(1, 'Penambahan Poin Pembelian', 120);
