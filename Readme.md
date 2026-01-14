# 🗳️ Sistem Voting Online (E-Voting)

> **Project Back-End Web Development**
> Aplikasi manajemen pemungutan suara berbasis web yang transparan, aman, dan *real-time* menggunakan PHP Native.

![PHP Version](https://img.shields.io/badge/PHP-8.0%2B-777BB4?style=flat&logo=php&logoColor=white)
![Database](https://img.shields.io/badge/Database-MariaDB%20%2F%20MySQL-003545?style=flat&logo=mariadb&logoColor=white)
![Frontend](https://img.shields.io/badge/Frontend-Bootstrap%205-7952B3?style=flat&logo=bootstrap&logoColor=white)

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
* **Database:** MariaDB / MySQL
* **Editor:** Visual Studio Code
* **Server:** PHP Built-in Server

---

## ⚙️ Cara Instalasi & Menjalankan (VS Code)

### Prasyarat
1.  **PHP** telah terinstall dan terdaftar di Environment Variable (Path).
2.  **MySQL Server** (bisa menggunakan XAMPP hanya untuk menyalakan modul MySQL).
3.  **Visual Studio Code**.

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

---
