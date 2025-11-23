# Aplikasi Pemesanan Travel

Aplikasi pemesanan travel lengkap dengan backend Laravel dan frontend VueJS. Aplikasi ini mendukung manajemen jadwal travel, pemesanan tiket, pembayaran, dan laporan penumpang.

## 🚀 Fitur Utama

### Admin
- ✅ Login Admin
- ✅ CRUD Jadwal Travel (tujuan, tanggal, waktu, kuota, harga)
- ✅ Melihat riwayat penumpang per jadwal (detail pemesanan)
- ✅ Laporan jumlah penumpang dan pendapatan
- ✅ Filter laporan berdasarkan tanggal

### Penumpang
- ✅ **Login & Register Penumpang** (dalam 1 halaman dengan tab)
- ✅ Melihat jadwal travel tersedia
- ✅ Filter jadwal (tujuan, tanggal, ketersediaan)
- ✅ Pemesanan tiket dengan validasi kuota
- ✅ Konfirmasi pembayaran
- ✅ Download invoice PDF
- ✅ Riwayat pemesanan

## 📋 Teknologi yang Digunakan

### Backend
- Laravel 12
- PostgreSQL
- Laravel Sanctum (Authentication)
- DomPDF (Invoice Generator)

### Frontend
- Vue.js 3
- Vue Router
- Axios
- Tailwind CSS

## 🛠️ Setup Backend (Laravel)

### Prasyarat
- PHP >= 8.1
- Composer
- PostgreSQL
- Git

### Langkah Instalasi

1. **Clone repository dan masuk ke folder backend**
```bash
git clone <repository-url>
cd travel-booking/backend
```

2. **Install dependencies**
```bash
composer install
```

3. **Copy file environment**
```bash
cp .env.example .env
```

4. **Konfigurasi database di file `.env`**
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=travel_booking
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

5. **Generate application key**
```bash
php artisan key:generate
```

6. **Buat database PostgreSQL**
```sql
CREATE DATABASE travel_booking;
```

7. **Jalankan migrasi**
```bash
php artisan migrate
```

8. **Seed data (optional - untuk data dummy)**
```bash
php artisan db:seed
```

9. **Install package DomPDF**
```bash
composer require barryvdh/laravel-dompdf
```

10. **Publish Sanctum config**
```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

11. **Jalankan server**
```bash
php artisan serve
```

Backend akan berjalan di `http://localhost:8000`


## 🎨 Setup Frontend (Vue.js)

### Prasyarat
- Node.js >= 20
- npm atau yarn

### Langkah Instalasi

1. **Masuk ke folder frontend**
```bash
cd travel-booking/frontend
```

2. **Install dependencies**
```bash
npm install
```

3. **Buat file `.env`**
```env
VUE_APP_API_URL=http://localhost:8000/api
```

Frontend akan berjalan di `http://localhost:8080`

### Build untuk Production

```bash
npm run build
```

File production akan ada di folder `dist/`

## 📁 Struktur Database

### Tabel: users
- id (PK)
- name
- email (unique)
- password
- role (enum: 'admin', 'passenger')
- phone
- timestamps

### Tabel: travel_schedules
- id (PK)
- destination
- departure_date
- departure_time
- quota
- available_quota
- price
- timestamps

### Tabel: ticket_orders
- id (PK)
- user_id (FK)
- travel_schedule_id (FK)
- passenger_name
- passenger_phone
- passenger_email
- quantity
- total_price
- status (enum: 'pending', 'confirmed', 'cancelled')
- timestamps

### Tabel: payments
- id (PK)
- ticket_order_id (FK)
- payment_method
- amount
- payment_status (enum: 'pending', 'paid', 'failed')
- payment_date
- invoice_path
- timestamps

## 🔐 Kredensial Login Default

### Admin
- Email: `admin@travel.com`
- Password: `password`

### Penumpang
- Email: `passenger@travel.com`
- Password: `password`

## 📝 API Endpoints

### Authentication
```
POST   /api/register          - Register penumpang
POST   /api/login             - Login
POST   /api/logout            - Logout
GET    /api/user              - Get user info
```

### Travel Schedules (Admin)
```
GET    /api/admin/travel-schedules              - List semua jadwal
POST   /api/admin/travel-schedules              - Tambah jadwal
GET    /api/admin/travel-schedules/{id}         - Detail jadwal
PUT    /api/admin/travel-schedules/{id}         - Update jadwal
DELETE /api/admin/travel-schedules/{id}         - Hapus jadwal
GET    /api/admin/travel-schedules/{id}/orders  - Riwayat penumpang
```

### Travel Schedules (Passenger)
```
GET    /api/travel-schedules                    - List jadwal tersedia
GET    /api/travel-schedules/{id}               - Detail jadwal
```

### Ticket Orders
```
POST   /api/ticket-orders                       - Buat pemesanan
GET    /api/ticket-orders                       - Riwayat pemesanan
GET    /api/ticket-orders/{id}                  - Detail pemesanan
POST   /api/ticket-orders/{id}/cancel           - Cancel pemesanan
```

### Payments
```
POST   /api/payments/{orderId}/confirm          - Konfirmasi pembayaran
GET    /api/payments/{orderId}/invoice          - Download invoice
```

### Reports (Admin)
```
GET    /api/admin/reports/passengers            - Laporan penumpang
GET    /api/admin/reports/by-period             - Laporan per periode
```

## 🧪 Testing

### Backend Testing
```bash
php artisan test
```

### Frontend Testing
```bash
npm run test:unit
```

### Database Connection Error
- Pastikan PostgreSQL berjalan
- Cek kredensial di `.env`
- Pastikan database sudah dibuat

### Invoice tidak ter-generate
- Pastikan folder `storage/app/public/invoices` ada dan writable
- Install DomPDF: `composer require barryvdh/laravel-dompdf`

## 📞 Support

Untuk pertanyaan dan support, silakan buka issue di repository ini.

## 📄 License

MIT License

---
