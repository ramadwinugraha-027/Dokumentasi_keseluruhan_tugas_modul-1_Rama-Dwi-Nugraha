# 6. Independent Challenge: Atomic Registration

**Nama Mahasiswa:** Rama Dwi Nugraha  
**NIM:** 251511027  
**Kelas:** D3-2A  
**Mata Kuliah:** Proyek 3 (Pemrograman Web)  

---

## 6.1 SKENARIO DAN ATURAN BISNIS

Fitur pendaftaran peserta sederhana (*Atomic Registration*) dibangun pada halaman detail kegiatan (*Activity*) dengan menerapkan mekanisme **Database Transaction (`DB::transaction`)** untuk menjamin konsistensi data (*ACID properties*).

| Aturan | Keterangan | Implementasi Teknis |
| :--- | :--- | :--- |
| **IC-01** | Pendaftaran hanya untuk Activity berstatus `published`. | `if ($activity->status !== Activity::STATUS_PUBLISHED) { throw new DomainException(...); }` |
| **IC-02** | Pendaftaran ditolak jika tanggal kegiatan (`activity_date`) sudah lewat. | `if ($activity->activity_date < now()->startOfDay()) { throw new DomainException(...); }` |
| **IC-03** | Email yang sama tidak boleh mendaftar dua kali pada Activity yang sama. | Validasi aplikasi via `Registration::where(...)->exists()` dan proteksi database `$table->unique(['activity_id', 'email'])`. |
| **IC-04** | Jumlah pendaftar tidak boleh melebihi `capacity`. | `if ($activity->registered_count >= $activity->capacity) { throw new DomainException(...); }` |
| **IC-05** | Satu proses pendaftaran membuat `Registration` dan memperbarui `registered_count` sebagai satu transaksi. | Dibungkus di dalam `DB::transaction(function () { ... })` pada `RegistrationService::register()`. |
| **IC-06** | Jika update `registered_count` gagal, `Registration` tidak boleh tertinggal sebagai data parsial (*rollback*). | Mekanisme *automatic rollback* dari `DB::transaction` saat terjadi kegagalan/exception di tengah transaksi. |

---

## 6.2 STRUKTUR BASIS DATA

### 1. Tabel `registrations`
```php
Schema::create('registrations', function (Blueprint $table) {
    $table->id();
    $table->foreignId('activity_id')->constrained('activities')->cascadeOnDelete();
    $table->string('participant_name', 100);
    $table->string('email', 100);
    $table->timestamp('registered_at')->nullable();
    $table->string('participant_phone', 20)->nullable();
    $table->string('status', 30)->default('Registered');
    $table->timestamps();

    $table->unique(['activity_id', 'email']);
});
```

### 2. Penambahan Kolom pada Tabel `activities`
```php
$table->unsignedInteger('capacity')->default(20);
$table->unsignedInteger('registered_count')->default(0);
```

---

## 6.4 BUKTI WAJIB PENGUJIAN

| Bukti Wajib | Status | Rincian Pengujian |
| :--- | :---: | :--- |
| **[x] Satu pendaftaran valid berhasil** | **Lulus** | `AtomicRegistrationTest::test_bukti_1_satu_pendaftaran_valid_berhasil` lulus; record `registrations` terbuat dan kolom `registered_count` pada tabel `activities` bertambah 1 secara atomis. |
| **[x] Email duplikat pada kegiatan yang sama ditolak** | **Lulus** | `AtomicRegistrationTest::test_bukti_2_email_duplikat_pada_kegiatan_yang_sama_ditolak` lulus; pendaftaran kedua dengan email yang sama ditolak dengan pesan: *"Email ini sudah terdaftar pada kegiatan yang sama."* |
| **[x] Kegiatan draft menolak pendaftaran** | **Lulus** | `AtomicRegistrationTest::test_bukti_3_kegiatan_draft_menolak_pendaftaran` lulus; pendaftaran pada kegiatan non-published ditolak dan data tidak masuk ke database. |
| **[x] Kegiatan tanggal lewat menolak pendaftaran** | **Lulus** | `AtomicRegistrationTest::test_bukti_4_kegiatan_tanggal_lewat_menolak_pendaftaran` lulus; pendaftaran pada kegiatan dengan tanggal yang sudah lewat otomatis ditolak. |
| **[x] Kapasitas penuh menolak pendaftaran** | **Lulus** | `AtomicRegistrationTest::test_bukti_5_kapasitas_penuh_menolak_pendaftaran` lulus; saat `registered_count >= capacity`, request ditolak dengan pesan: *"Pendaftaran ditolak karena kuota pendaftaran sudah penuh."* |
| **[x] Eksperimen kegagalan terkontrol membuktikan rollback** | **Lulus** | `AtomicRegistrationTest::test_bukti_6_eksperimen_kegagalan_terkontrol_membuktikan_rollback_atomis` lulus; simulasi kegagalan setelah `Registration::create()` membuktikan `DB::transaction` berhasil membatalkan seluruh operasi (tidak ada data parsial dan `registered_count` tidak bertambah). |
| **[x] Mahasiswa dapat menjelaskan mengapa validation saja tidak menggantikan transaction** | **Lulus** | Terjawab lengkap pada bagian pembahasan di bawah. |

---

## PEMBAHASAN: MENGAPA VALIDATION SAJA TIDAK MENGGANTIKAN TRANSACTION?

Validation dan Database Transaction berada pada **lapisan tanggung jawab (*layer of concern*) yang sangat berbeda**:

1. **Validation Rule (Validasi Input & Pra-Kondisi):**
   - Validasi hanya memeriksa apakah data masukan memenuhi format (misal email valid, nama tidak kosong) dan apakah kondisi awal saat request datang sesuai (misal kuota saat itu masih ada).
   - Validasi bekerja **sebelum** proses penyimpanan multi-langkah dieksekusi.

2. **Database Transaction (Integritas Eksekusi Multi-Operasi / Atomisitas):**
   - Proses pendaftaran melibatkan **lebih dari satu operasi basis data**:
     - Operasi 1: `INSERT INTO registrations ...`
     - Operasi 2: `UPDATE activities SET registered_count = registered_count + 1 ...`
   - Tanpa transaksi, jika Operasi 1 berhasil tetapi server mati, terjadi kegagalan jaringan, atau error pada Operasi 2, maka data pendaftar baru tetap tersimpan sedangkan hitungan `registered_count` tidak sinkron (**data korup / tidak konsisten**).
   - **Race Condition (Konkurensi):** Jika dua pengguna mendaftar di detik yang sama persis pada kuota tersisa 1, keduanya bisa lolos validasi input. Hanya transaksi basis data (`DB::transaction` dan *database lock / constraints*) yang dapat menjamin kuota tidak terlampaui dan eksekusi bersifat *Atomic* (semua operasi berhasil bersama atau semua dibatalkan bersama/rollback).
