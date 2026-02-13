![Laravel](https://img.shields.io/badge/Laravel-11-red)
![Status](https://img.shields.io/badge/status-academic-success)

# 🏦 Aplikasi Koperasi Simpan Pinjam

Aplikasi berbasis **Laravel** untuk membantu pengelolaan koperasi simpan pinjam secara digital.

Project ini dibuat sebagai **Tugas Ujian Akhir Semester (UAS)** dengan fokus pada implementasi:

- Authentication & Role-based Access
- Manajemen Anggota & Pengurus
- Pengajuan dan Persetujuan Pinjaman
- Sistem Angsuran
- Pengelolaan Simpanan
- Dashboard Informatif

---

# 🚀 Fitur Utama

## 👤 Authentication
- Login multi-role  
- Role:
  - **Pengurus (Admin)**
  - **Anggota**

---

## 🧑‍💼 Pengurus
Pengurus memiliki akses penuh untuk mengelola sistem koperasi.

### Hak Akses:
✅ Mengelola user  
✅ Menambah & menghapus anggota  
✅ Menyetujui / menolak pinjaman  
✅ Menandai pinjaman lunas  
✅ Mengelola simpanan anggota  
✅ Melihat seluruh data koperasi  

---

## 🧑 Anggota
Anggota hanya dapat mengakses data miliknya sendiri.

### Hak Akses:
✅ Mengajukan pinjaman  
✅ Melihat status pinjaman  
✅ Melihat riwayat angsuran  
✅ Melihat saldo simpanan  

---

# 💰 Sistem Pinjaman

### Alur Pinjaman:
1. Anggota mengajukan pinjaman  
2. Status → **menunggu**  
3. Pengurus menyetujui / menolak  
4. Jika disetujui:
   - Bunga otomatis dihitung
   - Cicilan dihitung oleh sistem
5. Pinjaman dapat ditandai **lunas**

---

# 🪙 Sistem Simpanan

Setiap anggota memiliki:

## ✅ Simpanan Master
Berfungsi sebagai rekening utama anggota.

Field penting:
- kode simpanan
- nomor rekening *(opsional)*
- saldo

## ✅ Simpanan Detail
Mencatat transaksi:

- setor
- tarik

Saldo akan terupdate otomatis.

---

# 📊 Dashboard

Dashboard dirancang agar user dapat melihat data penting dengan cepat.

### Anggota:
- Data diri  
- Pinjaman aktif  
- Total simpanan  

### Pengurus:
- Monitoring pinjaman  
- Data anggota  
- Aktivitas koperasi  

---

# 🛠️ Tech Stack

- **Laravel**
- **MySQL**
- **Bootstrap**
- **Blade Template**
- **Eloquent ORM**

---

# ⚙️ Instalasi

## 1️⃣ Clone Repository
```bash
git clone https://github.com/username/koperasi-app.git
```

## 2️⃣ Masuk Folder
```bash
cd koperasi-app
```

## 3️⃣ Install Dependency
```bash
composer install
```

## 4️⃣ Copy Environment
```bash
cp .env.example .env
```

## 5️⃣ Generate App Key
```bash
php artisan key:generate
```

## 6️⃣ Setup Database

Edit file `.env`

```
DB_DATABASE=koperasi
DB_USERNAME=root
DB_PASSWORD=
```

## 7️⃣ Migrasi Database
```bash
php artisan migrate
```

```bash
php artisan migrate:fresh --seed
```

## 8️⃣ Jalankan Server
```bash
php artisan serve
```

Buka di browser:

```
http://127.0.0.1:8000
```

---

# 🔑 Akun Demo

## 👨‍💼 Pengurus
```
username: admin
password: password
```

## 👤 Anggota
```
username: anggota1
password: password
```

---

# 🔒 Security

Beberapa proteksi yang diterapkan:

- Middleware authentication
- Middleware role
- Validasi request
- Database transaction
- Prevent duplicate data
- Authorization per role

---

# 🧠 Arsitektur Sistem

Project ini menggunakan pendekatan:

✅ MVC Architecture  
✅ Service Layer (PinjamanService)  
✅ Role-Based Middleware  
✅ Relational Database  
✅ Clean UI  

---

# 📚 Tujuan Project

Project ini dibuat untuk:

✔ memenuhi tugas UAS  
✔ memahami Laravel secara praktikal  
✔ membangun sistem keuangan sederhana  
✔ mengimplementasikan role-based access control  

---

# 🔥 Future Improvement

Beberapa fitur yang dapat dikembangkan:

- Export laporan PDF / Excel  
- Grafik keuangan  
- Notifikasi transaksi  
- Approval berlapis  
- Mobile responsive UI  
- Integrasi payment gateway  

---

# 👨‍💻 Author

**Nama:** Bayu Sebastian  
**NIM:** 220320001 
**Program Studi:** Informatika  

---

# 📖 Buku

👉 [Rancangan Sistem Koperasi Simpan Pinjam Sederhana](https://ebook.webiot.id/ebooks/rancangan-sistem-koperasi-simpan-pinjam-sederhana)

# ⭐ Catatan

Aplikasi ini dibuat untuk kebutuhan akademik dan masih dapat dikembangkan menjadi sistem koperasi yang lebih kompleks.
