# 🗳️ Sistem Voting Online (E-Voting)

> **Project Back-End Web Development**
> Aplikasi manajemen pemungutan suara berbasis web yang transparan, aman, dan *real-time* menggunakan PHP Native.


## 📖 Deskripsi Sistem
Aplikasi ini dirancang untuk menangani proses voting digital dengan integritas tinggi. Sistem memungkinkan pengguna membuat polling dengan batas waktu tertentu (`end_date`), melakukan voting (satu akun satu suara), dan memantau hasil secara langsung.

Proyek ini dikembangkan **tanpa framework back-end** (PHP Native) untuk mendemonstrasikan pemahaman logika dasar pemrograman web, mulai dari manajemen database, sesi, hingga algoritma validasi.

---

## 🌟 Analisis Fitur & Implementasi

Berikut adalah rincian mendalam mengenai fitur sistem, cara kerja di balik layar (*backend logic*), dan urgensinya:

### 1. Autentikasi & Keamanan Akun (Security)
* **Fitur:** Registrasi, Login, dan Logout dengan enkripsi.
* **Cara Kerja:**
    * Saat registrasi, password diubah menjadi string acak menggunakan algoritma **`password_hash()`** (Bcrypt).
    * Sistem menggunakan **Session Management** untuk membatasi hak akses halaman admin/user.
* **Pentingnya Fitur:** Memastikan setiap suara berasal dari pengguna valid dan mencegah pembajakan akun.

### 2. Manajemen Polling (CRUD & Logic)
* **Fitur:** Pembuatan polling dengan opsi jawaban dinamis dan pengaturan durasi.
* **Cara Kerja:**
    * User menginput Judul, Deskripsi, dan Batas Waktu (`end_date`).
    * Validasi Backend: Tombol *Edit* dan *Hapus* hanya berfungsi jika user yang login adalah pembuat polling tersebut.
* **Pentingnya Fitur:** Memberikan fleksibilitas penuh kepada pengguna untuk membuat survei sekaligus menjaga otorisasi data.

### 3. Mesin Voting (Voting Engine)
* **Fitur:** Validasi *One Man One Vote* dan *Time-Limiting*.
* **Cara Kerja:**
    * **Cek Duplikasi:** Sistem mengecek tabel `votes` (kombinasi `user_id` & `polling_id`) sebelum menyimpan suara.
    * **Cek Waktu:** Sistem membandingkan waktu saat ini dengan `end_date`. Jika terlewat, voting ditolak.
* **Pentingnya Fitur:** Menjamin keadilan pemilihan (mencegah kecurangan) dan kepastian waktu voting.

### 4. Visualisasi Hasil (Real-time Reporting)
* **Fitur:** Perhitungan suara otomatis dan grafik persentase.
* **Cara Kerja:** Menggunakan query SQL Agregasi (`SELECT COUNT... GROUP BY...`) dan merender data ke dalam grafik batang/lingkaran.
* **Pentingnya Fitur:** Transparansi hasil pemilihan secara instan tanpa penghitungan manual.

---

## 👥 Tim Pengembang
Pengembangan dilakukan dengan metode *Full Coding* kolaboratif:

| Nama & NIM | Username GitHub | Modul & Tanggung Jawab |
| :--- | :--- | :--- |
| **I Kadek Saja Jaya**<br>(240030215) | `@kadeksajajaya23` | **Backend Core & Authentication**<br>Setup koneksi database, Algoritma Enkripsi Password, Manajemen Session Login/Logout. |
| **I Gede Meta Darma Putra**<br>(240030207) | `@kayuputih` | **Polling Management**<br>Fitur create polling, Logika penyimpanan opsi jawaban, Validasi kepemilikan polling. |
| **I Gede Dedik Sarmawan**<br>(240030250) | `@dediksarmawan` | **Voting Engine**<br>Logika transaksi voting, Validasi anti-double-vote, Pengecekan status waktu polling. |
| **Ida Bagus Putu Wibawa P.**<br>(240030242) | `@Wibawa12` | **Data Visualization**<br>Query Agregasi hasil suara, Kalkulasi persentase & grafik, Styling Interface. |

---

## 🛠️ Tools & Teknologi
* **Bahasa:** PHP 8.x (Native)
* **Database:** MySQL
* **Editor:** Visual Studio Code
* **Server:** PHP Built-in Server

---

## ⚙️ Cara Instalasi & Menjalankan (VS Code)

### Prasyarat
1.  **PHP** 
2.  **MySQL Server** 
3.  **Visual Studio Code**


### Langkah-langkah
1.  **Persiapan Database**
    * Nyalakan modul **MySQL** pada XAMPP Control Panel.
    * Buka aplikasi database client (phpMyAdmin / DBeaver / HeidiSQL).
    * Buat database baru bernama `db_evoting`.
    * Import file `.sql` yang ada di folder proyek ini ke database tersebut.

2.  **Konfigurasi Koneksi**
    * Buka folder proyek di **Visual Studio Code**.
    * Buka file `config/Database.php`.
    * Pastikan konfigurasi user & password database sesuai (Default XAMPP: User `root`, Password kosong).

3.  **Menjalankan Server (Terminal)**
    * Di dalam VS Code, buka Terminal (`Ctrl + ` `).
    * Ketik perintah berikut untuk menjalankan server PHP lokal:
      ```bash
      php -S localhost:8000
      ```

4.  **Akses Aplikasi**
    * Buka browser (Chrome/Edge).
    * Kunjungi alamat berikut:
      ```text
      http://localhost:8000
      ```
    * Aplikasi siap digunakan.

##  📂 Struktur Folder
Berikut adalah arsitektur direktori proyek ini:

```text
Project-Voting-Online/
│
├── admin/                  # Modul Admin
│   └── moderasi_komentar.php   # Halaman untuk moderasi komentar oleh pemilik polling
│
├── assets/                 # Aset Statis
│   ├── script.js               # JavaScript (show password, local time logic)
│   └── style.css               # Styling CSS kustom aplikasi
│
├── auth/                   # Halaman Autentikasi
│   ├── login.php               # Formulir masuk
│   ├── logout.php              # Script destroy session
│   └── register.php            # Formulir pendaftaran user baru
│
├── classes/                # Logic Layer (OOP Classes)
│   ├── comment.php             # Logika pengelolaan komentar
│   ├── notification.php        # Logika notifikasi sistem
│   ├── option.php              # Logika opsi jawaban polling
│   ├── polling.php             # Logika utama manajemen polling
│   ├── user.php                # Logika manajemen user
│   └── vote.php                # Logika transaksi voting
│
├── config/                 # Konfigurasi Sistem
│   └── database.php            # Koneksi database MySQL menggunakan PDO
│
├── layouts/                # Komponen UI Reusable
│   ├── footer.php              # Bagian bawah halaman
│   └── header.php              # Bagian atas (navbar & meta tags)
│
├── models/                 # Data Layer
│   └── user.php                # Model representasi data user
│
├── polling/                # Modul Halaman Polling
│   ├── export/                 # Fitur Ekspor Data
│   │   ├── hasil_csv.php           # Download hasil sebagai CSV
│   │   └── print_hasil_pdf.php     # Cetak hasil ke PDF
│   ├── create.php              # Form buat polling baru
│   ├── detail.php              # Halaman voting & hasil sementara
│   ├── edit.php                # Form edit polling
│   ├── delete.php              # Proses hapus polling
│   └── hasil.php               # Halaman rekap hasil akhir
│
├── vote/                   # Modul Pemrosesan Suara
│   └── store.php               # (Legacy/Backend) Proses simpan vote
│
├── index.php               # Halaman Utama (Dashboard/Landing)
├── voting_db.sql           # File skema database untuk di-import
└── Readme.md               # Dokumentasi proyek