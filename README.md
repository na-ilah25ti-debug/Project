# KosKita

KosKita adalah aplikasi Laravel untuk satu pemilik dengan tiga rumah kos. Data awal dari proyek mencakup Saka Kost (8 kamar putri), Kos Syariah (6 kamar putri), dan Kos Cowok (10 kamar putra).

## Coba versi baru di Laragon

Gunakan PHP 8.4.1 atau lebih baru sesuai dependensi dalam arsip. Pilih PHP 8.4 di Laragon dan jalankan perintah berikut dari Terminal Laragon atau PowerShell di folder yang berisi `artisan`. Dependensi PHP dan aset CSS/JS yang sudah dibangun ada di ZIP.

1. Ekstrak ZIP ke folder baru, misalnya `D:\laragon-6.0-minimal\www\KosKita-Revisi`.
2. Buat konfigurasi dan database SQLite lokal.

   ```powershell
   Copy-Item .env.example .env
   New-Item -ItemType File database\database.sqlite
   php artisan key:generate
   php artisan migrate --seed
   ```

   Aktifkan `extension=pdo_sqlite` pada `php.ini` PHP yang dipilih bila muncul pesan **could not find driver**. Cek lokasi konfigurasi dengan `php --ini`.

3. Buat akun pemilik dengan email khusus. Kata sandi dimasukkan saat diminta oleh terminal.

   ```powershell
   php artisan kos:pemilik pemilik@example.com
   php artisan serve
   ```

4. Buka [beranda](http://127.0.0.1:8000). Khusus pemilik, ketik [alamat login pemilik](http://127.0.0.1:8000/admin/masuk) langsung di browser.

Jika memakai virtual host Laragon, arahkan document root ke folder `public` aplikasi dan sesuaikan `APP_URL`.

## Setel data kos

Isi nilai di `.env` sebelum digunakan oleh penghuni.

| Variabel | Isi |
| --- | --- |
| `KOS_WHATSAPP` | Nomor WhatsApp asli pemilik, format `628…`, tanpa tanda plus atau spasi. Tombol WhatsApp pemilik muncul setelah diisi. |
| `KOS_NAMA_IBU` | Nama yang akan ditampilkan di halaman kontak. |
| `KOS_REKENING` | Bank, nomor rekening, dan nama pemilik rekening yang sudah dikonfirmasi. |
| `KOS_HARGA_GALON` | Harga per galon dalam rupiah. |
| `APP_TIMEZONE` | Zona waktu operasional. Default `Asia/Jakarta`. |
| `MAIL_*` | Setelan SMTP jika tautan reset kata sandi perlu dikirim melalui email. Setelan lokal `log` mencatat tautan ke log. |

Setelah mengubah konfigurasi, jalankan `php artisan config:clear`. Cocokkan harga, fasilitas, dan status kamar pada menu **Kamar** dengan kondisi nyata. Seed memakai nama, harga, dan fasilitas dari proyek awal. Kamar yang baru ditambahkan oleh seed berstatus kosong. Menu **Foto** menerima foto asli untuk banner, rumah, kamar, dan fasilitas. Banner bawaan berlabel ilustrasi.

## Alur penggunaan

- **Pengunjung** bisa menyaring kamar berdasarkan rumah, putri/putra, harga, tipe, dan status. Pengunjung bisa menanyakan kamar lewat WhatsApp atau mengirim formulir. Pesan tamu masuk ke panel pemilik dan bisa dibalas melalui WhatsApp.
- **Penghuni** mendaftar dari halaman masuk. Di panel pemilik, buka **Penghuni → Tambah penghuni**, pilih kamar, dan masukkan email yang sudah didaftarkan. Akun juga bisa dibuat dari formulir itu dengan kata sandi sementara. Penghuni dapat mengubah kata sandi di **Akun saya**.
- **Tagihan** dibuat oleh pemilik per bulan pada menu **Tagihan**. Penghuni transfer sesuai instruksi, lalu mengunggah bukti. Pemilik bisa menerima atau menolak bukti, serta menandai pembayaran langsung sebagai lunas. Peringatan jatuh tempo tampil di dasbor. Transfer berlangsung di luar aplikasi.
- **Laporan dan galon** dikirim penghuni dari dasbornya. Pemilik memperbarui status dari **Laporan** atau **Pesanan galon**. Penghuni dapat melihat perubahan di riwayatnya.

## Pakai database proyek lama

Simpan cadangan database lama dan gunakan folder aplikasi baru. Salin konfigurasi koneksi, `APP_KEY`, dan pengaturan kos yang diperlukan dari `.env` lama. Salin juga foto dari `storage/app/public` dan bukti bayar dari `storage/app/private` jika ada. Untuk SQLite, salin file SQLite lama. Jalankan `php artisan migrate` dan `php artisan optimize:clear`. Gunakan `php artisan kos:pemilik EMAIL_LAMA` untuk mengganti kata sandi akun pemilik lama, termasuk akun contoh dari versi awal.

## Uji lokal

Jalankan `php artisan test` setelah konfigurasi lokal siap dan PDO SQLite aktif. Jika dependensi perlu dipasang ulang, jalankan `composer install` menggunakan lockfile proyek.
