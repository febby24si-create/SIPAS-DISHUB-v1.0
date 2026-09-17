# SIPAS-DISHUB

**Sistem Informasi Persuratan dan Administrasi Dinas Perhubungan**

SIPAS-DISHUB adalah sistem informasi berbasis web yang dirancang khusus untuk mendigitalisasi dan menertibkan tata kelola persuratan serta administrasi kepegawaian pada instansi Dinas Perhubungan. Sistem ini berfokus pada efisiensi operasional dengan mengintegrasikan pengelolaan dokumen masuk dan keluar beserta layanan mandiri kepegawaian dalam satu pintu (satu gerbang utama).

Melalui SIPAS-DISHUB, proses persuratan dan administrasi yang sebelumnya dilakukan secara manual kini dapat ditransformasi menjadi alur digital (*paperless*). Hal ini meminimalkan risiko kehilangan berkas, mencegah terjadinya duplikasi nomor surat, dan mempercepat proses distribusi informasi maupun pengambilan keputusan oleh pimpinan.

![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=for-the-badge&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql)
![Status](https://img.shields.io/badge/Status-On_Development-yellow?style=for-the-badge)

---

## Daftar Isi
- [Tentang Project](#tentang-project)
- [Latar Belakang](#latar-belakang)
- [Tujuan](#tujuan)
- [Ruang Lingkup](#ruang-lingkup)
- [Fitur Sistem](#fitur-sistem)
- [Role Pengguna](#role-pengguna)
- [Alur Sistem](#alur-sistem)
- [Modul Persuratan](#modul-persuratan)
- [Modul Kepegawaian](#modul-kepegawaian)
- [Database](#database)
- [Tech Stack](#tech-stack)
- [Struktur Folder](#struktur-folder)
- [Instalasi](#instalasi)
- [Konfigurasi Database](#konfigurasi-database)
- [Development Workflow](#development-workflow)
- [Status Project](#status-project)
- [Roadmap](#roadmap)
- [Catatan Pengembangan](#catatan-pengembangan)
- [Author](#author)

---

## Tentang Project

SIPAS-DISHUB merupakan platform sentral untuk menangani dua aspek krusial dalam lingkup instansi pemerintahan: **Persuratan Dinas** dan **Administrasi Kepegawaian**. Digunakan di lingkungan operasional Dinas Perhubungan, sistem ini berfokus pada tata naskah dinas yang terstandarisasi, pengelolaan data pegawai, dan integrasi antar keduanya. Sebagai contoh, saat seorang pegawai mengajukan cuti, sistem secara otomatis akan menghasilkan dokumen persuratan yang terintegrasi langsung dengan nomor arsip dinas secara *real-time*.

---

## Latar Belakang

Pengembangan sistem ini dilatarbelakangi oleh beberapa permasalahan administrasi umum di instansi pemerintahan:
* **Pencatatan dokumen yang masih manual:** Buku agenda fisik rentan rusak dan mempersulit pelacakan riwayat dokumen.
* **Pencarian surat yang membutuhkan waktu:** Mencari arsip fisik dari tumpukan berkas memakan waktu yang lama dan tidak efisien.
* **Pengelolaan arsip yang belum terpusat:** Dokumen lampiran atau fisik surat seringkali terpisah dengan data informasinya, sehingga rentan hilang.
* **Administrasi yang membutuhkan pencatatan berulang:** Pembuatan nomor surat untuk persetujuan pegawai seringkali dilakukan secara tumpang-tindih (nomor ganda).

---

## Tujuan

Tujuan utama dikembangkannya SIPAS-DISHUB antara lain:
* Membantu digitalisasi pengelolaan persuratan dinas.
* Mempermudah pencatatan surat masuk maupun surat keluar dengan sistem draf.
* Mempermudah pencarian dokumen (arsip) berbasis kata kunci atau rentang waktu.
* Membantu pengarsipan dokumen fisik secara digital menggunakan sistem lampiran.
* Mendukung otomatisasi administrasi kepegawaian (seperti pengajuan cuti, kenaikan pangkat, dan gaji berkala).
* Menyediakan sumber data yang lebih terstruktur dan terpusat untuk pelaporan instansi.

---

## Ruang Lingkup

Sistem membatasi area implementasi pada modul-modul berikut:
* **Persuratan:** Pengelolaan masuk, keluar, dan penomoran surat.
* **Master Data:** Standarisasi jenis surat, klasifikasi surat, unit kerja, dan pegawai.
* **Kepegawaian:** Digitalisasi pengajuan khusus untuk pegawai (Cuti, Kenaikan Pangkat, KGB).
* **Arsip:** Gudang dokumen terpadu untuk pencarian.
* **Laporan:** Rekapitulasi sederhana tentang pergerakan surat.
* **Manajemen Pengguna:** Pengelolaan kredensial (akun pengguna).

---

## Fitur Sistem

### Authentication
Aplikasi menggunakan **Laravel Breeze** sebagai fondasi keamanan sistem. Menyediakan fitur login yang aman dan terintegrasi dengan validasi kredensial pengguna yang terdaftar di database.

### Dashboard
Halaman antarmuka utama yang menyuguhkan ringkasan informasi metrik dan status persuratan harian untuk memberikan *overview* langsung bagi operator.

### Surat Masuk
Sistem menyediakan antarmuka pencatatan untuk surat yang diterima, mencakup informasi pengirim, tanggal surat, tanggal diterima, perihal, hingga pengunggahan berkas digital (lampiran) untuk pelacakan.

### Surat Keluar
Sistem pengelolaan dari hulu ke hilir untuk membuat surat dinas. Dimulai dengan pembuatan *draf*, integrasi pemrosesan *template document*, manajemen nomor surat (*sequence*), hingga pratinjau dan konversi file dokumen.

### Disposisi
Terdapat modul disposisi yang berfungsi sebagai sarana untuk mendistribusikan surat beserta instruksi atau arahan dari pimpinan ke unit kerja terkait di dalam instansi.

### Arsip Digital
Penyimpanan seluruh histori persuratan secara sentral. Berkas fisik dari dokumen surat disimpan di lingkungan aplikasi (`storage`) dan diikat menggunakan struktur relasional khusus untuk pengarsipan.

### Pencarian
Memfasilitasi pengguna untuk menelusuri data arsip surat yang sudah diklasifikasikan menggunakan kata kunci sehingga dokumen spesifik dapat ditemukan dengan cepat.

### Master Data
Modul fundamental untuk mengatur preferensi dan referensi struktural:
* **Jenis Surat:** Pengelompokkan format dokumen.
* **Klasifikasi Surat:** Pengelompokkan penomoran surat dinas.
* **Unit Kerja:** Hirarki organisasi atau divisi yang ada.
* **Pegawai:** Direktori data pegawai lengkap beserta profil jabatannya.

### Kepegawaian
Sub-sistem operasional kepegawaian:
* **Cuti:** Proses pengajuan hari libur/cuti pegawai secara terstruktur.
* **Kenaikan Pangkat:** Penanganan data administratif pengajuan jabatan.
* **Gaji Berkala (KGB):** Penanganan histori dan pemberitahuan TMT (Terhitung Mulai Tanggal) KGB.

### Laporan
Fitur pelaporan fungsional yang merekapitulasi dokumen persuratan dalam kurun waktu tertentu, siap diunduh atau dipresentasikan.

### Manajemen Pengguna
Antarmuka manajemen khusus admin untuk menonaktifkan pengguna, melakukan reset password dasar, dan mengelola profil setiap *user* terdaftar yang merujuk pada pegawai instansi.

---

## Role Pengguna

Akses diatur secara spesifik menggunakan *middleware* dan peran (*roles*). Saat ini sistem membagi otorisasi kepada peran berikut:

* **Admin:** Memiliki akses ke sebagian besar konfigurasi aplikasi, manajemen pengguna, serta operasional teknis sistem utama.
* **Pimpinan:** Diarahkan pada kapabilitas *monitoring*, membaca laporan, dan memberikan persetujuan/disposisi strategis.
* **Verifikator:** Menjalankan verifikasi dokumen dan pengajuan agar terstandarisasi sebelum diproses lebih lanjut ke tingkat pimpinan.
* **Operator / Staff:** Menjalankan operasional harian seperti pendataan surat masuk, pembuatan draf surat keluar, dan melengkapi data pengajuan kepegawaian.

---

## Alur Sistem

Berikut adalah representasi alur penggunaan utama pada sistem:

```text
Login User
  ↓
Dashboard (Overview Status)
  ↓
Pilih Modul:
 ├── [Persuratan] ────────→ Input Surat Masuk / Draf Surat Keluar
 │                            ↓
 │                          Penomoran Otomatis & Pemrosesan File
 │                            ↓
 └── [Kepegawaian] ───────→ Input Pengajuan (Cuti, Pangkat, KGB)
                              ↓
                            Verifikasi Data & Persetujuan
                              ↓
                          [Arsip & Dokumen Disimpan]
                              ↓
                          Pencarian / Cetak Laporan
```

---

## Modul Persuratan

Sebagai *core system*, modul persuratan berpusat pada satu tabel tunggal secara teknikal (`surat`), di mana pembeda `arah` (masuk / keluar) digunakan untuk menavigasi logikanya. 
* **Penomoran:** Aplikasi memelihara nomor dokumen secara otomasi dan terkunci dari konflik *concurrent* sehingga tidak akan ada dua surat yang mendapat nomor yang sama di saat yang bersamaan.
* **Jenis dan Klasifikasi:** Setiap surat mengacu pada tata cara pengkodean klasifikasi resmi persuratan instansi.
* **Polimorfisme Lampiran:** Surat mendukung pengikatan berbagai jenis dokumen (gambar/PDF) yang dapat diproses secara jamak.

---

## Modul Kepegawaian

Sistem secara aktif memfasilitasi pendataan dan pengajuan pegawai:
* **Cuti:** Pegawai atau staf terkait dapat membuat pengajuan cuti secara langsung beserta pencatatan durasi.
* **Kenaikan Pangkat:** Pengajuan kepangkatan tercatat di sistem sebagai rekaman administratif terstruktur.
* **Gaji Berkala (KGB):** Administrasi riwayat Kenaikan Gaji Berkala.

---

## Database

Seluruh data persuratan dan master instansi bertumpu pada arsitektur relational database.
* **Database Engine:** **MySQL** (Utama)
* **Tool Pengelolaan Database:** **HeidiSQL**
* **Infrastruktur Skema:** Sistem memanfaatkan *migration* Laravel sepenuhnya, sehingga skema terbentuk secara otonom saat sistem diinstal tanpa mengimpor berkas SQL secara manual.

**Relasi Utama (Konseptual):**
```text
Roles
 └── Users

Unit Kerja
 └── Pegawai
      └── Users (1 to 1)

Jenis Surat & Klasifikasi Surat
 └── Surat

Pengajuan Cuti / Kenaikan Pangkat / Gaji Berkala
 └── Surat (Integrasi Dokumen SK / Referensi Surat)
```

---

## Tech Stack

| Teknologi      | Keterangan          |
| -------------- | ------------------- |
| **Laravel**    | Framework aplikasi backend (v13.x) |
| **PHP**        | Bahasa pemrograman backend (v8.x) |
| **MySQL**      | Database Engine utama sistem |
| **Blade**      | Template engine untuk antarmuka pengguna |
| **Laravel Breeze** | Implementasi Authentication *scaffolding* |
| **HeidiSQL**   | Aplikasi *Database management tool* |

---

## Struktur Folder

Berikut adalah ilustrasi tata letak berkas inti pada repositori aplikasi:

```text
SIPAS-DISHUB/
├── app/
│   ├── Http/Controllers/   # Berisi logic utama antar-muka dan request
│   ├── Models/             # Skema representasi database (Surat, Pegawai, dll)
│   └── Services/           # Layanan terpisah (seperti penghasil Nomor Surat)
├── database/
│   ├── migrations/         # Instruksi otomatisasi pembuat tabel database
│   └── seeders/            # Pembuat data dummy/initial (Roles, Template)
├── resources/
│   └── views/              # Direktori desain antarmuka sistem (Blade)
├── routes/                 # Konfigurasi alamat web dan sistem routing
├── public/                 # Publikasi statis aset (CSS, JS, Gambar)
├── storage/                # Penyimpanan *attachments* dan berkas yang terunggah
└── tests/                  # Script uji kualitas/fungsionalitas kode (Testing)
```

---

## Instalasi

Ikuti langkah-langkah di bawah ini untuk mempersiapkan dan menjalankan SIPAS-DISHUB di lokal Anda.

### 1. Requirements Minimum
Pastikan lingkungan komputer Anda sudah terinstal:
* PHP (^8.3)
* Composer
* Node.js & npm
* MySQL Server (bisa via XAMPP atau instalasi *standalone*)

### 2. Clone Repository
```bash
git clone https://github.com/febby24si-create/SIPAS-DISHUB.git
cd SIPAS-DISHUB
```

### 3. Install Dependencies
Unduh dan muat pustaka dependensi bagi *backend* maupun *frontend*.
```bash
composer install
npm install
npm run build
```

### 4. Konfigurasi Environment
Pada Windows:
```cmd
copy .env.example .env
php artisan key:generate
```
Pada Linux/Mac:
```bash
cp .env.example .env
php artisan key:generate
```

### 5. Setup Database
Buat sebuah database baru di server MySQL lokal Anda dengan nama:
`sipas_dishub`
*(Anda disarankan membuka HeidiSQL untuk membuat dan mengecek database baru ini dengan praktis).*

### 6. Migration dan Seeding
Jalankan pembuatan tabel dan pengisian master-data awal (role & pengaturan standar):
```bash
php artisan migrate --seed
```

### 7. Storage
Buat tautan simbolik agar aplikasi dapat mengakses file unggahan/lampiran yang ada di `storage`:
```bash
php artisan storage:link
```

### 8. Run Application
Nyalakan *development server*:
```bash
php artisan serve
```
Buka browser Anda dan akses sistem pada:
`http://localhost:8000`

---

## Konfigurasi Database

Buka file `.env` di *code editor* Anda. Sesuaikan blok konfigurasi koneksi ini agar terhubung dengan MySQL lokal Anda:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sipas_dishub
DB_USERNAME=root
DB_PASSWORD=
```
*(Catatan: Jangan mencantumkan kata sandi server production ke repository atau file sumber).*

---

## Development Workflow

Pengembangan perangkat lunak menggunakan paradigma terpusat Laravel:
```text
Laravel (Application Logic)
   ↓
Migration & Models (Definisi Struktur Data)
   ↓
MySQL (Pusat Penyimpanan Data Aktual)
   ↕
HeidiSQL (Eksplorasi/Pengelolaan Data Eksternal)
```
Sebagai standar operasional pengembangan:
* Segala perubahan logika dan desain antarmuka diselesaikan pada platform **Laravel**.
* Skema database dan relasi selalu dieksekusi melalui **Migration**, bukan dibuat manual.
* **MySQL** menyimpan seluruh integritas relasional data.
* **HeidiSQL** hanya dimanfaatkan bagi pengembang untuk menelusuri isi data atau melakukan *troubleshooting* manual.

---

## Status Project

> 🚧 **On Development**

Proyek ini masih dikembangkan secara aktif dan diimplementasikan sebagai bagian dari kegiatan Kerja Praktik / Magang.

---

## Roadmap

Berikut adalah pemetaan implementasi sistem berdasarkan kondisi aplikasi saat ini:
- [x] Authentication terpusat
- [x] Dashboard rekapitulasi data
- [x] Surat Masuk (Pencatatan & Lampiran)
- [x] Surat Keluar (Konversi Template)
- [x] Disposisi
- [x] Arsip dan Sistem Pencarian
- [x] Modul Master Data Utama
- [x] Manajemen Pengguna
- [x] Sistem Kepegawaian Dasar (Cuti, Pangkat, KGB)
- [x] Laporan fungsional
- [ ] Penyempurnaan UI Laporan Lanjutan
- [ ] Implementasi verifikasi dan persetujuan multi-pimpinan berjenjang (Multi-level workflow)

---

## Catatan Pengembangan

Sistem SIPAS-DISHUB masih berstatus pra-rilis. Skema struktur *database* maupun fungsionalitas lanjutan dapat terus bertumbuh dan berubah secara drastis selama proses pengujian *workflow* instansi berlangsung. Sistem ini belum diklaim sebagai perangkat *production-ready* yang siap disebarkan tanpa adanya optimasi tambahan dan *stress-testing*.

---

## Author

**Febby Fahrezy**

**Politeknik Caltex Riau**

---

*SIPAS-DISHUB dikembangkan sebagai bagian dari proses pembelajaran dan Kerja Praktik untuk menerapkan pengembangan aplikasi web dalam kebutuhan administrasi dan persuratan.*
