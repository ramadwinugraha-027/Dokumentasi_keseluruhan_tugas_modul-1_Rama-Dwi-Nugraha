# 7. Add-On: Poster Storage dan Refactoring

**Nama Mahasiswa:** Rama Dwi Nugraha  
**NIM:** 251511027  
**Kelas:** D3-2A  
**Mata Kuliah:** Proyek 3 (Pemrograman Web)  

---

## 7.1 FITUR POSTER KEGIATAN

1. **Input Poster Opsional:**  
   Form tambah (`create.blade.php`) dan form edit (`edit.blade.php`) dilengkapi input berkas poster dengan atribut `enctype="multipart/form-data"`.
2. **Validasi File Gambar:**  
   Divalidasi pada `StoreActivityRequest` dan `UpdateActivityRequest` dengan aturan: `'poster' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048']` (Maksimal 2 MB).
3. **Penyimpanan Berkas (Laravel Storage):**  
   Disimpan pada *disk* `public` di direktori `posters/` menggunakan method `$file->store('posters', 'public')`.
4. **Penyimpanan Path Basis Data:**  
   Hanya *path* berkas (`poster_path`) yang disimpan ke dalam tabel `activities`.
5. **Pembersihan Berkas Lama:**  
   Ketika poster diperbarui, berkas lama otomatis dihapus dari *storage* melalui `Storage::disk('public')->delete($oldPath)` untuk mencegah penumpukan berkas sampah (*orphan files*).
6. **Perlindungan Soft Delete:**  
   Saat kegiatan dihapus (*soft delete*), berkas poster fisik **tidak dihapus**, sehingga saat kegiatan di-*restore*, gambar poster tetap utuh dan dapat ditampilkan kembali.
7. **Kebijakan Force Delete:**  
   Saat dilakukan penghapusan permanen (`forceDelete()`), berkas poster fisik otomatis dibersihkan dari *storage*.

---

## 7.2 REFACTORING REVIEW

| Pemeriksaan | Pertanyaan Review | Jawaban & Analisis Arsitektur |
| :--- | :--- | :--- |
| **Controller** | Apakah controller masih berfungsi sebagai orchestrator atau mulai memuat business rule/query detail berlebihan? | **Controller tetap sebagai orchestrator (*Thin Controller*).** Controller hanya menerima request HTTP, memanggil method di `ActivityService` / `RegistrationService`, dan mengembalikan view atau redirect. |
| **Form Request** | Apakah input validation terpusat dan pesan kesalahan dapat dipahami? | **Ya, terpusat di `StoreActivityRequest`, `UpdateActivityRequest`, dan `StoreRegistrationRequest`.** Seluruh pesan error dalam bahasa Indonesia yang komunikatif. |
| **Service** | Apakah state transition dan proses domain memiliki method yang jelas? | **Ya.** Method transisi status dan alur bisnis terdefinisi eksplisit: `publish()`, `complete()`, `register()`, `getTrash()`, `restore()`, dan `forceDelete()`. |
| **Model** | Apakah relationship dan query scope relevan terhadap model, bukan sekadar memindahkan semua kode ke model? | **Ya.** Model `Activity` hanya memuat relasi Eloquent (`category`, `registrations`), accessor (`poster_url`), method helper domain (`isComplete`, `hasCapacity`), dan local scopes (`search`, `filterCategory`, `filterStatus`, `sortDate`). |
| **Blade** | Apakah Blade menampilkan data tanpa menjalankan business rule atau query database? | **Ya.** Seluruh data dipersiapkan terlebih dahulu oleh Service/Controller. Blade hanya menampilkan data dan kondisi logika tampilan (`@if`, `@forelse`). |
| **Penamaan** | Apakah nama method menggambarkan tindakan dan menghindari istilah generik seperti `processData`? | **Ya.** Penamaan method jelas dan spesifik seperti `publish()`, `complete()`, `restore()`, `register()`, `getTrash()`, dan `hasCapacity()`. |
| **Duplikasi** | Apakah kondisi search/filter atau rule yang sama ditulis berulang? | **Tidak.** Seluruh logika filter dipusatkan di Local Scope `scopeFilter` pada model `Activity` sehingga dapat digunakan ulang (*reusable*). |

---

## 7.3 DEFINITION OF DONE (TABEL LEMBAR KERJA H)

| Pemeriksaan | Status | Bukti / Keputusan |
| :--- | :---: | :--- |
| **Poster valid dapat diunggah dan ditampilkan.** | ☑ | `ActivityPosterTest::test_1_poster_valid_dapat_diunggah_dan_ditampilkan` lulus. Berkas berhasil tersimpan di `storage/app/public/posters/` dan gambar tampil di halaman detail kegiatan. |
| **File tidak sesuai ditolak dengan pesan yang jelas.** | ☑ | `ActivityPosterTest::test_2_file_tidak_sesuai_ditolak_dengan_pesan_yang_jelas` lulus. Pengunggahan file non-gambar (misal `.pdf`) ditolak dengan pesan: *"File poster harus berupa gambar."* |
| **Penggantian poster tidak meninggalkan file lama tanpa alasan.** | ☑ | `ActivityPosterTest::test_3_penggantian_poster_tidak_meninggalkan_file_lama` lulus. Saat poster baru diunggah pada form edit, file poster lama otomatis terhapus dari storage. |
| **Soft delete tidak merusak kemungkinan restore poster.** | ☑ | `ActivityPosterTest::test_4_soft_delete_tidak_merusak_kemungkinan_restore_poster` lulus. Berkas gambar tetap aman saat kegiatan di-soft-delete dan tetap tampil sempurna saat di-restore. |
| **Minimal satu refaktorisasi dijelaskan.** | ☑ | Refaktorisasi pengelolaan berkas poster dan transaksi pendaftaran diisolasi sepenuhnya ke dalam *Service Layer* (`ActivityService` dan `RegistrationService`), menjaga Controller tetap *thin* dan bebas dari manipulasi file I/O langsung. |
