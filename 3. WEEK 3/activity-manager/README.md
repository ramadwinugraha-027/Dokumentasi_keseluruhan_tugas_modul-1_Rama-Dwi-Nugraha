# Laravel Activity App Modul 3 (Clean Architecture & Static Code Analysis)

Aplikasi manajemen kegiatan (*Activity Manager*) berbasis **Laravel 13** yang dibangun dengan menerapkan prinsip *Clean Architecture* (*Thin Controller*, *Form Request*, dan *Service Layer*) serta telah diperiksa kualitas kodenya menggunakan **SonarQube**.

---

## Fitur Utama

* **Manajemen Kegiatan (CRUD):** Tambah, lihat, edit, dan hapus kegiatan.
* **Transisi Status Kegiatan:** Aturan bisnis untuk memastikan status kegiatan berjalan sesuai alur, yaitu `Planned`, `Ongoing`, dan `Done`.
* **Filter Kegiatan (Independent Challenge):** Menyaring kegiatan berdasarkan status melalui query string (`?status=Planned`) menggunakan Query Builder `when()`.
* **Clean Architecture:**

  * **Form Request (`StoreActivityRequest`, `UpdateActivityRequest`):** Memisahkan logika validasi input.
  * **Service Layer (`ActivityService`):** Memisahkan logika bisnis dan aturan transisi status.
  * **Thin Controller (`ActivityController`):** Mengatur alur *request* dan *response*.
* **Static Code Analysis:** Terintegrasi dengan **SonarQube Community Build** untuk memeriksa kualitas dan keamanan kode.

---

## Persyaratan Sistem

* PHP >= 8.2
* Composer
* Node.js & NPM
* MySQL / SQLite
* Java Runtime Environment (JRE) 17+ untuk menjalankan `sonar-scanner`
* SonarQube Server lokal pada port 9000

---

## Cara Instalasi dan Menjalankan Proyek

### 1. Clone Repositori dan Masuk ke Direktori Proyek

```bash
git clone <URL_REPOSITORY_KAMU>
cd laravel-activity-app
```

### 2. Install Dependensi PHP

```bash
composer install
```

### 3. Salin File Lingkungan (`.env`)

```bash
cp .env.example .env
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Konfigurasi Database dan Jalankan Migrasi serta Seeder

Pastikan pengaturan database di `.env` sudah sesuai, lalu jalankan:

```bash
php artisan migrate:fresh --seed
```

### 6. Jalankan Server Lokal Laravel

```bash
php artisan serve
```

Aplikasi dapat diakses melalui browser di:

`http://127.0.0.1:8000/activities`

---

## Static Code Analysis dengan SonarQube

### 1. Konfigurasi `sonar-project.properties`

File konfigurasi SonarQube disediakan di direktori utama proyek:

```properties
sonar.projectKey=Laravel-Activity-App
sonar.projectName=Laravel Activity App
sonar.projectVersion=1.0

sonar.sources=app,routes,resources/views
sonar.exclusions=vendor/**,storage/**,bootstrap/cache/**,tests/**,public/**

sonar.sourceEncoding=UTF-8
```

### 2. Menjalankan Pemindaian

Pastikan server lokal SonarQube berjalan di `http://localhost:9000`, lalu jalankan perintah berikut di terminal:

```powershell
sonar-scanner -D"sonar.host.url=http://localhost:9000" -D"sonar.login=TOKEN_GLOBAL_KAMU"
```

---

## Hasil Analisis SonarQube

Berdasarkan hasil pemindaian *static analysis*:

* **Maintainability Rating:** `A` dengan 7 *Open Issues* atau *Code Smells*.
* **Security Rating:** `A` dengan 0 *Open Vulnerabilities*.
* **Reliability Rating:** `C` dengan 21 *Potential Bugs* atau *Exceptions*.
* **Duplication:** `0.0%` dari 483 baris kode.
* **Lines of Code (LOC):** 185 baris kode yang dianalisis.

---

## Code Formatting dengan Laravel Pint

Untuk merapikan kode agar sesuai dengan standar penulisan PHP, jalankan perintah berikut menggunakan Laravel Pint:

```bash
./vendor/bin/pint
```

---

## Tag Rilis

Proyek ini telah diberi tag sebagai versi final untuk penilaian:

```bash
git tag modul-3-final
```
