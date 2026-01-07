# Sistem Voting Online (E-Voting)

> **Project Back-End Web Development** > Aplikasi manajemen pemungutan suara berbasis web menggunakan PHP Native.

## Deskripsi Sistem
Aplikasi ini dirancang untuk menangani proses voting digital secara transparan. Sistem memungkinkan pengguna membuat polling dengan batas waktu (`end_date`), melakukan voting satu kali per user, dan memantau hasil suara secara *real-time*. [cite_start]Dikembangkan tanpa framework back-end (PHP Native) sesuai spesifikasi tugas kuliah [cite: 5, 155-156].

## Tim Pengembang & Pembagian Tugas
Seluruh anggota tim terlibat aktif dalam penulisan kode (*full coding*), dengan spesialisasi modul sebagai berikut:

| Nama & NIM | Username GitHub | Tanggung Jawab & Fitur Coding |
| :--- | :--- | :--- |
| **I Kadek Saja Jaya**<br>(240030215) | @kadeksajajaya23 | [cite_start]**Backend Core & Authentication**<br>• Setup koneksi database (`Database.php`).<br>• Logika Register & Enkripsi Password (`password_hash`).<br>• Manajemen Session Login & Logout [cite: 158-163]. |
| **I Gede Meta Darma Putra**<br>(240030207) | @kayuputih | [cite_start]**Polling Management (CRUD)**<br>• Fitur `create.php`: Input judul, deskripsi, & durasi.<br>• Logika penyimpanan Opsi Jawaban dinamis.<br>• Validasi edit/hapus polling (hanya oleh pemilik) [cite: 164-167]. |
| **I Gede Dedik Sarmawan**<br>(240030250) | @dediksarmawan | [cite_start]**Voting Engine & Validation**<br>• Logika transaksi voting (Insert ke tabel `votes`).<br>• Validasi *Double-Voting* (Cek user_id & polling_id).<br>• Pengecekan status aktif/tutup berdasarkan waktu server [cite: 168-170]. |
| **Ida Bagus Putu Wibawa P.**<br>(240030242) | @Wibawa12 | [cite_start]**Data Visualization & UI**<br>• Query Agregasi (`COUNT`, `GROUP BY`) untuk hasil suara.<br>• Kalkulasi persentase dan tampilan grafik.<br>• Styling CSS Bootstrap & Responsivitas Layout [cite: 171-172]. |

## Spesifikasi Teknis
* **Back-End:** PHP 8.x (Native/OOP Style)
* **Database:** MariaDB / MySQL
* **Front-End:** HTML5, Bootstrap 5, JavaScript
* **Tools:** Visual Studio Code, XAMPP, Git

## Implementasi Fitur

### A. Fitur Utama (Wajib)
Implementasi inti sistem berdasarkan persyaratan minimum project:
1.  **Autentikasi & Keamanan Pengguna**
    * [cite_start]Registrasi akun baru, Login, dan Logout sistem[cite: 162].
    * [cite_start]Enkripsi password menggunakan hashing (`password_hash`) dan manajemen sesi aman[cite: 163, 192].
2.  **Manajemen Polling (CRUD)**
    * [cite_start]Pembuatan polling dengan judul, deskripsi, dan opsi pilihan dinamis [cite: 164-165].
    * [cite_start]Pengaturan durasi polling menggunakan batas waktu (`end_date`)[cite: 166].
    * [cite_start]Edit dan Hapus polling (dibatasi hanya untuk pemilik/pembuat polling)[cite: 167].
3.  **Mekanisme Voting**
    * [cite_start]Partisipasi voting khusus pengguna terdaftar[cite: 169].
    * [cite_start]Validasi ketat 1 User = 1 Suara per polling (mencegah *double voting*)[cite: 170].
4.  **Hasil & Laporan**
    * [cite_start]Kalkulasi hasil suara secara *real-time*[cite: 172].
    * [cite_start]Tampilan hasil hanya muncul setelah user melakukan voting atau waktu polling berakhir[cite: 172].

### B. Fitur Opsional (Pengembangan)
Fitur tambahan untuk meningkatkan fungsionalitas sistem:
1.  [cite_start]**Manajemen Komentar:** Kolom diskusi pada setiap polling yang memungkinkan user meninggalkan komentar [cite: 174-176].
2.  [cite_start]**Sistem Notifikasi:** Pemberitahuan pada dashboard terkait status polling (baru/akan berakhir) [cite: 178-180].
3.  [cite_start]**Ekspor Data:** Fitur cetak/unduh hasil rekapitulasi voting (Print/CSV) [cite: 182-183].