![Laravel](https://laravel.com/assets/img/components/logo-laravel.svg)

<h1 align="center">Aplikasi Grosir Obat</h1>

Aplikasi Grosir Obat adalah sebuah sistem kasir dan manajemen produk obat yang dibuat menggunakan framework Laravel, dibangun dengan Test-Driven Development.

<hr>

## Daftar Isi
1. [Fitur](#fitur)
2. [Instalasi](#instalasi)
    - [Spesifikasi yang Dibutuhkan](#spesifikasi)
    - [Cara Install](#cara-install)
    - [Login Admin](#cara-install)
    - [Testing](#automated-testing)
3. [Input Produk dan Satuan](#input-produk-dan-satuan)
4. [Proses Transaksi](#proses-transksi)
    - [Keranjang Belanja](#keranjang-belanja)
    - [Proses Entry Transaksi](#proses-entry-transaksi)
5. [Lisensi](#license)

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
6. Manajemen User
    - List User
    - Input User Baru
    - Edit User
    - Hapus User (jika tidak ada transaksi)

<hr>

## Instalasi
### Spesifikasi
- PHP 7.0
- Laravel 5.4
- MySQL
- SQlite (untuk `automated testing`)

### Cara Install

1. Para terminal, clone repo `git clone git@github.com:nafiesl/grosir-obat.git`
2. `cd grosir-obat`
3. `composer install`
4. `cp .env.example .env`
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

## License

The Laravel framework is open-sourced software licensed under the [MIT license](LICENSE).
