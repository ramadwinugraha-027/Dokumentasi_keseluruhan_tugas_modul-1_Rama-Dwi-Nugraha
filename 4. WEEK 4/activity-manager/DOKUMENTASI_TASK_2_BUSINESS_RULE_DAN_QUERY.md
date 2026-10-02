# 5.2 Task 2 - Business Rule dan Query Experience

**Nama Mahasiswa:** Rama Dwi Nugraha  
**NIM:** 251511027  
**Kelas:** D3-2A  
**Mata Kuliah:** Proyek 3 (Pemrograman Web)  

---

## TARGET
Membangun daftar kegiatan yang dapat digunakan secara realistis sekaligus mengendalikan status melalui *business rule* yang eksplisit.

---

## BUTIR PEKERJAAN TASK 2

1. **Status awal Activity adalah `draft`:**
   - Pada migrasi dan model, setiap kegiatan baru yang dibuat otomatis memiliki status `draft`.
   - `ActivityService::create()` menetapkan `$data['status'] = Activity::STATUS_DRAFT`.

2. **Aksi Publish (BR-05):**
   - Aksi `publish` di `ActivityService` dan endpoint route `PATCH /activities/{activity}/publish`.
   - Hanya dapat dijalankan jika status awal kegiatan adalah `draft` dan data kegiatan lengkap (memiliki judul, tanggal kegiatan, kategori, dan deskripsi tidak kosong sesuai BR-05).
   - Apabila draft tidak lengkap atau status bukan draft, aksi ditolak dengan pesan yang jelas.

3. **Aksi Complete:**
   - Aksi `complete` di `ActivityService` dan endpoint route `PATCH /activities/{activity}/complete`.
   - Hanya dapat dijalankan jika kegiatan sudah berstatus `published`.

4. **Proteksi Form Edit Umum:**
   - Dropdown status dihapus dari form create dan edit (`create.blade.php`, `edit.blade.php`).
   - `UpdateActivityRequest` dan `ActivityService::update()` mengabaikan/menghapus modifikasi parameter status sehingga status tidak dapat diubah bebas secara sembarangan. Transisi status dikendalikan secara khusus melalui *explicit action routes* (`publish` dan `complete`).

5. **Search berdasarkan `code` atau `title`:**
   - Menggunakan Local Scope `scopeSearch` pada model `Activity` dengan klausa `LIKE %term%` pada kolom `code` dan `title`.

6. **Filter `category_id` dan `status`:**
   - Menggunakan Local Scope `scopeFilterCategory` dan `scopeFilterStatus` pada model `Activity`.

7. **Sort berdasarkan tanggal (`start_at` / `activity_date` terbaru atau terlama):**
   - Menggunakan Local Scope `scopeSortDate` pada model `Activity` yang mendukung pengurutan `latest` (terbaru / descending) dan `oldest` (terlama / ascending).

8. **Pagination 10 item dan mempertahankan Query String aktif:**
   - Menggunakan `$query->paginate(10)->withQueryString()` pada `ActivityService::getAll()`.
   - Menampilkan link navigasi halaman pada view `index.blade.php`.

9. **Penerapan Local Scope:**
   - Local Scope didefinisikan pada model `Activity`: `scopeSearch()`, `scopeFilterCategory()`, `scopeFilterStatus()`, `scopeSortDate()`, serta `scopeFilter()`. Controller tetap tipis (*thin controller*) dan mudah dibaca.

10. **Pengujian Kombinasi Filter:**
    - Diuji dengan kombinasi multi-parameter (search + category + status + sort) secara bersamaan pada antarmuka web dan unit/feature test.

---

## TABEL SKENARIO DAN BUKTI PENGUJIAN

| Skenario | Hasil yang Diharapkan | Status | Bukti |
| :--- | :--- | :---: | :--- |
| **Draft lengkap -> Published** | Berhasil dan status berubah menjadi `published` | [x] | `ActivityFeatureTest::test_skenario_draft_lengkap_ke_published_berhasil` lulus; status berhasil berubah ke `published` dan flash message `success` dikirim. |
| **Draft tidak lengkap -> Published** | Ditolak dengan pesan yang jelas | [x] | `ActivityFeatureTest::test_skenario_draft_tidak_lengkap_ke_published_ditolak_br05` lulus; sistem menolak publikasi draft tanpa deskripsi dengan pesan error: *"Kegiatan tidak dapat dipublikasikan karena data belum lengkap (deskripsi wajib diisi)."* |
| **Published -> Completed** | Berhasil dan status berubah menjadi `completed` | [x] | `ActivityFeatureTest::test_skenario_published_ke_completed_berhasil` lulus; status kegiatan beralih dari `published` ke `completed`. |
| **Completed -> Draft** | Ditolak | [x] | `ActivityFeatureTest::test_skenario_completed_ke_draft_ditolak` lulus; aksi publish ditolak dan percobaan inject status pada form update diabaikan. |
| **Search + Category + Status** | Hasil memenuhi seluruh parameter | [x] | `ActivityFeatureTest::test_skenario_kombinasi_search_category_dan_status` lulus; query hanya mengembalikan record yang memenuhi pencarian kata kunci, ID kategori, dan status secara bersamaan. |
| **Pagination halaman 2** | Parameter pencarian/filter tetap ada | [x] | `ActivityFeatureTest::test_skenario_pagination_halaman_2_mempertahankan_query_string` lulus; URL halaman 2 tetap membawa query string pencarian dan filter aktif via `withQueryString()`. |

---

## JAWABAN PERTANYAAN

### 1. Mengapa status tidak diubah melalui form edit umum?
Status mewakili siklus hidup (*lifecycle state*) dari suatu entitas bisnis yang memiliki aturan transisi tertentu (*finite state machine*). Jika status diletakkan sebagai field input bebas pada form edit umum:
- Pengguna dapat melompati aturan bisnis (misalnya langsung mengubah kegiatan yang belum lengkap dari `draft` ke `completed`, atau membatalkan kegiatan yang sudah selesai kembali ke `draft`).
- Validasi prasyarat (seperti kelengkapan data sebelum *publish*) menjadi sulit dikontrol dan berisiko terlewat.
- Dengan memisahkan transisi status ke aksi/endpoint tersendiri (`publish`, `complete`), setiap transisi dapat mengeksekusi validasi aturan bisnis (*business rules*) dan *side-effects* secara eksplisit dan aman.

### 2. Bagian mana yang merupakan validation rule dan bagian mana yang merupakan business rule?
- **Validation Rule (Form Request / Input Level):**
  Memastikan format data masukan valid, tipe data sesuai, dan memenuhi batasan struktural dasar sebelum data diproses lebih lanjut.
  *Contoh:* `code` wajib diisi maksimal 20 karakter & unik, `category_id` harus berupa integer yang ada di tabel categories, `title` minimal 5 karakter, format tanggal valid.
- **Business Rule (Domain / Service Layer Level):**
  Mengatur logika dan kebijakan domain aplikasi yang menentukan apakah suatu operasi diperbolehkan secara kontekstual dalam alur kerja sistem.
  *Contoh:* Kegiatan baru selalu berstatus `draft`; draft hanya boleh dipublikasikan jika seluruh informasi inti telah lengkap (BR-05); kegiatan yang dapat diselesaikan hanya yang sudah berstatus `published`; kategori yang memiliki kegiatan aktif tidak boleh dihapus (BR-08).

### 3. Mengapa filter dilakukan di query database dan bukan setelah `Activity::all()`?
- **Efisiensi Memori & Bandwidth (Database Performance):**
  Jika menggunakan `Activity::all()`, seluruh baris data dari tabel dimuat ke memori PHP (RAM) menjadi Collection sebelum difilter. Pada data berjumlah besar (ribuan/jutaan baris), hal ini menyebabkan pemborosan memori (*memory leak/exhaustion*) dan waktu loading yang lambat.
- **Pemanfaatan Database Indexing & Pagination:**
  Melakukan filter di level database (`WHERE`, `LIKE`, `ORDER BY`, `LIMIT`) memungkinkan database menggunakan *index*, hanya mengambil sejumlah data yang relevan sesuai halaman pagination (10 item), sehingga eksekusi query sangat cepat (*O(log N)*).

### 4. Kapan local scope membantu dan kapan justru membuat model terlalu penuh?
- **Kapan Local Scope Membantu:**
  - Saat ada kueri atau fragmen filter yang sering digunakan berulang kali di berbagai tempat/controller (misal pencarian judul/kode, filter status, filter kategori).
  - Menyederhanakan penulisan kueri di controller agar tetap ekspresif, bersih, dan mudah dibaca (*reusable & readable*).
- **Kapan Local Scope Justru Membuat Model Terlalu Penuh:**
  - Saat logika query menjadi sangat kompleks, melibatkan agregasi antar banyak tabel eksternal, atau mengandung aturan bisnis domain yang besar.
  - Jika seluruh variasi filter diletakkan di model, model akan menjadi *Fat Model* (melanggar *Single Responsibility Principle*). Pada skala tersebut, pendekatan yang lebih baik adalah memindahkan logika filter ke kelas terpisah seperti **Query Filter**, **Action Class**, atau **Repository/Specification Pattern**.
