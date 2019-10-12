![Aplikasi Grosir Obat](public/imgs/readme-logo.png "Aplikasi Grosir Obat")

[![Build Status](https://travis-ci.org/nafiesl/grosir-obat.svg?branch=master)](https://travis-ci.org/nafiesl/grosir-obat)
[![StyleCI](https://styleci.io/repos/87638082/shield?branch=master)](https://styleci.io/repos/87638082)

Aplikasi Grosir Obat adalah sebuah sistem kasir (Point of Sales) dan manajemen produk obat yang dibuat menggunakan framework Laravel, dibangun dengan Test-Driven Development.

<hr>

## Daftar Isi
1. [Fitur](#fitur)
2. [Demo Aplikasi](#demo-aplikasi)
3. [Instalasi](#instalasi)
    - [Spesifikasi yang Dibutuhkan](#spesifikasi)
    - [Cara Install](#cara-install)
    - [Login Admin](#cara-install)
    - [Testing](#automated-testing)
4. [Input Produk dan Satuan](#input-produk-dan-satuan)
5. [Proses Transaksi](#proses-transksi)
    - [Keranjang Belanja](#keranjang-belanja)
    - [Proses Entry Transaksi](#proses-entry-transaksi)
6. [Screenshots](#screenshots)
7. [Lisensi](#license)

<hr>

## Fitur

Fitur pada Aplikasi ini meliputi:

1. Akun Login
    - Login dan Logout User
    - Ganti Password User
2. Entry Transaksi
    - Entry Transaksi Tunai
    - Entry Transaksi Kredit
    - Pencarian Produk
    - Konfirmasi Transaksi
    - Cetak Nota (PDF; Format A4)
3. Manajemen Produk
    - List Produk
    - Cetak Daftar Harga
    - Input Produk Baru
    - Edit Produk (Harga Tunai dan Kredit)
    - Hapus Produk
4. Manajemen Satuan Produk
    - List Satuan Produk
    - Input Produk Baru
    - Edit Satuan Produk
    - Hapus Satuan Produk
5. Manajemen Transaksi
    - List Transaksi Hari ini
    - List Transaksi
    - Detail Transaksi
    - Cetak Nota (PDF; Format A4)
6. Laporan Penjualan
    - Penjualan Per Tahun
    - Penjualan Per Bulan
    - Penjualan Per Tanggal
6. Manajemen User
    - List User
    - Input User Baru
    - Edit User
    - Hapus User (jika tidak ada transaksi)

Catatan:
- **Transaksi Kredit** saat ini hanya ditujukan untuk membedakan harga produk, bukan input pembayaran yang diangsur oleh customer. Dipersilakan jika ada yang berkenan mengembangkan fitur tersebut.

<hr>

## Demo Aplikasi

| URL | http://grosir-obat.nafies.id/login |
| --- | --- |
| username | admin |
| password | secret |

<hr>

## Instalasi

### Spesifikasi
- PHP ^7.2
- Laravel 6.x
- Database MySQL atau MariaDB
- SQlite (untuk `automated testing`)

### Cara Install

1. Clone atau download source code
    - Para terminal, clone repo `git clone git@github.com:nafiesl/grosir-obat.git`
    - atau `git clone https://github.com/nafiesl/grosir-obat.git`
    - Jika tidak menggunakan Git, silakan **Download Zip** dan *extract* pada direktori web server (misal: xampp/htdocs)
2. `cd grosir-obat`
3. `composer install`
4. `cp .env.example .env`
    - Jika tidak menggunakan Git, bisa rename file `.env.example` menjadi `.env`
5. Pada terminal `php artisan key:generate`
6. Buat **database pada mysql** untuk aplikasi ini
7. **Setting database** pada file `.env`
8. Masukkan **Nama Aplikasi**, **Nama Toko**, **Alamat Toko** dan **Telp Toko** pada pada file `.env`
    ```
    APP_NAME="Apotek Sejahtera"
    STORE_NAME="Apotek Sejahtera"
    STORE_ADDRESS="Jln. Pramuka, No. 60, Banjarmasin"
    STORE_PHONE="081234567890"
    ```
8. `php artisan migrate --seed`
9. `php artisan serve`
10. Selesai

### Login Admin
```
Username: admin
Password: secret
```

### Automated Testing
Aplikasi ini dilengkapi dengan **Testing Laravel**, ingin mencoba? Silakan:
```
vendor/bin/phpunit
```
<hr>

## Input Produk dan Satuan
Untuk mulai menggunakan aplikasi, silakan inputkan **Satuan Produk** terlebih dahulu, misal:
`Box, Botol, Kaleng, Keping, Pak, Pcs`

Kemudian inputkan **Produk** agar dapat dimasukkan ke dalam transaksi belanja (Cart)

<hr>

## Proses Transksi

### Keranjang Belanja

**Keranjang Belanja** didesain agar mengikuti hirarki berikut:
- Kelas **CartCollection** memiliki beberapa kelas **CashDraft** dan atau **CreditDraft**
- Kelas **CashDraft** memiliki beberapa **Item** dengan harga **Tunai**
- Kelas **CreditDraft** memiliki beberapa **Item** dengan harga **Kredit**
- Kelas **CashDraft** akan disimpan sebagai transaksi dengan model **Transaction** tipe **Tunai**
- Kelas **CreditDraft** akan disimpan sebagai transaksi dengan model **Transaction** tipe **Kredit**

### Proses Entry Transaksi

Proses Entry transaksi baru:

1. User login
2. Klik tombol **Transaksi Tunai** (atau **Transaksi Kredit**)
3. Cari produk (minimal 3 huruf)
4. Muncul list produk dengan harga sesuai jenis transaksi
5. Isi **Jumlah Item**, klik **Tambah**
6. Item produk yang bersangkutan akan muncul pada List Item
7. Isi data pembeli
8. Klik **Proses Transaksi**
9. Muncul halaman **Konfirmasi Transaksi** (cek list item, harga dan total)
10. Klik **Simpan Transaksi**
11. Muncul halaman **Detail Transaksi**
12. **Cetak Nota** format PDF ukuran A4.

<hr>

## Screenshots

#### Shopping Cart
![Shopping Cart](public/imgs/01-shopping-cart.jpg "Shopping Cart")

#### Konfirmasi Entry Transaksi
![Konfirmasi Entry Transaksi](public/imgs/02-konfirmasi-entry-transaksi.jpg "Konfirmasi Entry Transaksi")

#### List Transaksi
![List Transaksi](public/imgs/03-list-transaksi.jpg "List Transaksi")

#### Daftar Produk
![Daftar Produk](public/imgs/04-daftar-produk.jpg "Daftar Produk")

#### Laporan Penjualan Pertahun
![Laporan Tahunan](public/imgs/06-yearly-sales.jpg "Laporan Tahunan")

#### Laporan Penjualan Perbulan
![Laporan Bulanan](public/imgs/07-monthly-sales.jpg "Laporan Bulanan")

#### Testing
![Testing](public/imgs/05-testing.jpg "Testing")

## Lisensi

Project Grosir Obat merupakan software Point of Sales yang free dan open source di bawah [lisensi MIT](LICENSE).
