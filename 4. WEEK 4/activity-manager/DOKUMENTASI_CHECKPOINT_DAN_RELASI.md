# 5.1 Task 1 - Relational CRUD dan Integrity

**Nama Mahasiswa:** Rama Dwi Nugraha  
**NIM:** 251511027  
**Kelas:** D3-2A  
**Mata Kuliah:** Proyek 3 (Pemrograman Web)  

---

## TARGET
Mengubah CRUD kegiatan dari entitas tunggal menjadi CRUD yang terhubung dengan kategori dan dilindungi *constraint* database.

---

## 8 BUTIR PEKERJAAN TASK 1

1. **Buat migration, model, dan seeder Category:**
   - Migration: [2026_09_20_000000_create_categories_table.php](file:///d:/KULIAH/Semester%203/03.%20Proyek%20Pemrograman%20Web%203%20Sks/05.%20Tugas/251511027_Rama%20Dwi%20Nugraha_Project%203/3.%20WEEK%203%20-%20Copy/activity-manager/database/migrations/2026_09_20_000000_create_categories_table.php)
   - Model: [Category.php](file:///d:/KULIAH/Semester%203/03.%20Proyek%20Pemrograman%20Web%203%20Sks/05.%20Tugas/251511027_Rama%20Dwi%20Nugraha_Project%203/3.%20WEEK%203%20-%20Copy/activity-manager/app/Models/Category.php)
   - Seeder: [CategorySeeder.php](file:///d:/KULIAH/Semester%203/03.%20Proyek%20Pemrograman%20Web%203%20Sks/05.%20Tugas/251511027_Rama%20Dwi%20Nugraha_Project%203/3.%20WEEK%203%20-%20Copy/activity-manager/database/seeders/CategorySeeder.php)

2. **Tambahkan `category_id` pada Activity dan definisikan relationship dua arah:**
   - Di [Category.php](file:///d:/KULIAH/Semester%203/03.%20Proyek%20Pemrograman%20Web%203%20Sks/05.%20Tugas/251511027_Rama%20Dwi%20Nugraha_Project%203/3.%20WEEK%203%20-%20Copy/activity-manager/app/Models/Category.php): `public function activities(): HasMany { return $this->hasMany(Activity::class); }`
   - Di [Activity.php](file:///d:/KULIAH/Semester%203/03.%20Proyek%20Pemrograman%20Web%203%20Sks/05.%20Tugas/251511027_Rama%20Dwi%20Nugraha_Project%203/3.%20WEEK%203%20-%20Copy/activity-manager/app/Models/Activity.php): `public function category(): BelongsTo { return $this->belongsTo(Category::class); }`

3. **Pastikan `code` pada Activity mempunyai unique index:**
   - Didefinisikan di migration: `$table->string('code', 20)->unique();`

4. **Perbarui create dan edit form agar kategori dipilih dari data Category:**
   - [create.blade.php](file:///d:/KULIAH/Semester%203/03.%20Proyek%20Pemrograman%20Web%203%20Sks/05.%20Tugas/251511027_Rama%20Dwi%20Nugraha_Project%203/3.%20WEEK%203%20-%20Copy/activity-manager/resources/views/activities/create.blade.php): `<select name="category_id">` mengambil data dinamis dari tabel `categories`.
   - [edit.blade.php](file:///d:/KULIAH/Semester%203/03.%20Proyek%20Pemrograman%20Web%203%20Sks/05.%20Tugas/251511027_Rama%20Dwi%20Nugraha_Project%203/3.%20WEEK%203%20-%20Copy/activity-manager/resources/views/activities/edit.blade.php): Pilihan kategori terpilih sesuai relasi yang sedang diedit.

5. **Gunakan validation rule `exists` untuk `category_id` dan `unique` untuk `code` pada create/update:**
   - [StoreActivityRequest.php](file:///d:/KULIAH/Semester%203/03.%20Proyek%20Pemrograman%20Web%203%20Sks/05.%20Tugas/251511027_Rama%20Dwi%20Nugraha_Project%203/3.%20WEEK%203%20-%20Copy/activity-manager/app/Http/Requests/StoreActivityRequest.php): `'code' => ['required', 'string', 'max:20', 'unique:activities,code']`, `'category_id' => ['required', 'integer', 'exists:categories,id']`.
   - [UpdateActivityRequest.php](file:///d:/KULIAH/Semester%203/03.%20Proyek%20Pemrograman%20Web%203%20Sks/05.%20Tugas/251511027_Rama%20Dwi%20Nugraha_Project%203/3.%20WEEK%203%20-%20Copy/activity-manager/app/Http/Requests/UpdateActivityRequest.php): `'code' => ['required', 'string', Rule::unique('activities', 'code')->ignore($activity)]`.

6. **Terapkan kebijakan bahwa kategori yang masih digunakan tidak dapat dihapus (BR-08):**
   - Foreign key constraint di level basis data: `$table->foreignId('category_id')->constrained('categories')->restrictOnDelete();`

7. **Tampilkan nama kategori pada index dan detail Activity menggunakan relationship:**
   - Di [index.blade.php](file:///d:/KULIAH/Semester%203/03.%20Proyek%20Pemrograman%20Web%203%20Sks/05.%20Tugas/251511027_Rama%20Dwi%20Nugraha_Project%203/3.%20WEEK%203%20-%20Copy/activity-manager/resources/views/activities/index.blade.php): `$activity->category->name`
   - Di [show.blade.php](file:///d:/KULIAH/Semester%203/03.%20Proyek%20Pemrograman%20Web%203%20Sks/05.%20Tugas/251511027_Rama%20Dwi%20Nugraha_Project%203/3.%20WEEK%203%20-%20Copy/activity-manager/resources/views/activities/show.blade.php): `$activity->category->name`

8. **Uji minimal enam skenario:**

| Skenario | Hasil yang Diharapkan | Status | Bukti |
| :--- | :--- | :---: | :--- |
| **Create dengan kategori valid** | Data tersimpan dan relasi benar | [x] | Tangkapan layar form tambah menyimpan data kegiatan dan di halaman index/show tampil relasi nama kategori. |
| **Create dengan category_id tidak valid** | Request ditolak | [x] | Form request mengembalikan error: *"Kategori yang dipilih tidak valid atau tidak ditemukan."* |
| **Create kode duplikat** | Request ditolak | [x] | Form request mengembalikan error: *"Kode kegiatan sudah digunakan."* |
| **Update tanpa mengganti kode** | Tidak dianggap duplikat terhadap diri sendiri | [x] | `Rule::unique()->ignore($activity)` meloloskan validasi dan data kegiatan berhasil diperbarui. |
| **Hapus kategori yang masih dipakai** | Ditolak; data kegiatan tetap utuh | [x] | Eksekusi `$category->delete()` gagal ditahan oleh constraint database `FOREIGN KEY constraint failed (restrictOnDelete)`. |
| **Hapus kategori kosong** | Berhasil | [x] | Kategori tanpa relasi berhasil terhapus dari tabel database. |

---

## KRITERIA SELESAI

- [x] Relationship model dan foreign key dapat dijelaskan.
- [x] Tidak ada Activity dengan category_id yatim.
- [x] Kode kegiatan unik dijaga di request dan database.
- [x] Delete policy sesuai BR-08.
- [x] Controller tidak berisi query kategori atau business rule yang berulang tanpa alasan.
