# Klub Koding Kampus — Mini Project Modul 1

Landing page statis responsif yang dibuat sebagai tugas **Mini Project Modul 1: Dasar Web**, dengan materi utama Struktur HTML, Semantic HTML, Box Model, dan Flexbox.

* **Nama:** Rama Dwi Nugraha
* **NIM:** 251511027
* **Kelas:** D3-2A
* **Program Studi:** D3 Teknik Informatika

---

## 1. Ringkasan Produk

**Klub Koding Kampus** adalah landing page untuk kelompok belajar mahasiswa yang memiliki minat pada pemrograman web. Halaman ini dirancang sebagai tempat untuk memberikan informasi mengenai kegiatan belajar, jadwal diskusi, dan cara bergabung.

Target pengguna utamanya adalah mahasiswa tingkat awal Teknik Informatika yang ingin belajar pemrograman web bersama teman. Informasi dibuat sederhana dan dibagi menjadi beberapa bagian agar mudah dibaca dan ditemukan.

---

## 2. Struktur dan Fitur

Halaman dibuat menggunakan HTML5 dengan beberapa elemen semantic sesuai dengan fungsi kontennya.

* **Header:** Berisi nama atau identitas Klub Koding Kampus.
* **Navigasi:** Menyediakan link menuju bagian Tentang, Kegiatan, Jadwal, dan Gabung.
* **Hero Section:** Berisi judul utama, deskripsi singkat, dan tombol untuk bergabung.
* **Tentang Kami:** Menjelaskan tujuan dan suasana belajar dalam klub. Bagian ini juga menggunakan `<figure>`, `<img>`, dan `<figcaption>`.
* **Kegiatan:** Menampilkan tiga kegiatan utama dalam bentuk card yang disusun menggunakan Flexbox.
* **Jadwal:** Memberikan informasi mengenai jadwal kegiatan atau diskusi.
* **CTA/Gabung:** Mengajak pengguna untuk bergabung dan menyediakan kontak yang dapat digunakan.
* **Footer:** Berisi informasi penutup halaman.

---

## 3. Keputusan Teknis

### 1. Semantic HTML

Saya menggunakan `<header>`, `<nav>`, `<main>`, `<section>`, dan `<footer>` sesuai dengan fungsi masing-masing. Penggunaan elemen tersebut membuat struktur halaman lebih jelas dibandingkan menggunakan `<div>` untuk semua bagian.

### 2. Mobile-First

Layout dibuat dari ukuran layar kecil terlebih dahulu. Pada tampilan awal, konten disusun secara vertikal agar tetap nyaman digunakan pada perangkat mobile.

### 3. Flexbox

Flexbox digunakan untuk mengatur posisi dan susunan card kegiatan. Pada layar yang lebih lebar, card dapat ditampilkan secara berdampingan.

### 4. Responsive Layout

Breakpoint `768px` digunakan untuk menyesuaikan layout ketika ruang layar sudah lebih luas. Saya juga melakukan pengujian pada beberapa ukuran viewport untuk memastikan tidak terjadi horizontal overflow.

### 5. CSS Custom Properties

Beberapa warna dan nilai CSS yang digunakan berulang disimpan dalam CSS Custom Properties agar lebih mudah dikelola dan konsisten.

### 6. Focus State

Saya menggunakan `:focus-visible` pada elemen interaktif agar pengguna yang menggunakan keyboard dapat mengetahui elemen yang sedang mendapatkan fokus.

---

## 4. Pengujian Viewport

|  Viewport  | Status | Hasil                                                                |
| :--------: | :----: | :------------------------------------------------------------------- |
|  **320px** |  Lulus | Layout satu kolom dan tidak mengalami horizontal overflow.           |
|  **375px** |  Lulus | Konten tetap rapi dan mudah dibaca.                                  |
|  **768px** |  Lulus | Breakpoint aktif dan card tersusun berdampingan.                     |
| **1024px** |  Lulus | Konten tetap berada di tengah dengan lebar maksimal yang ditentukan. |

Pengujian dilakukan menggunakan **DevTools pada browser** dengan mengubah ukuran viewport untuk melihat perubahan layout pada setiap ukuran layar.

---

## 5. Masalah dan Perbaikan

Salah satu masalah yang ditemukan adalah **horizontal overflow** ketika halaman dibuka pada ukuran layar kecil. Setelah diperiksa melalui DevTools, ditemukan bahwa terdapat elemen yang ukurannya melebihi lebar container.

Masalah tersebut diperbaiki dengan menyesuaikan ukuran elemen dan menggunakan aturan seperti `max-width: 100%` pada gambar. Setelah diperbaiki, halaman diuji kembali pada beberapa viewport dan scrollbar horizontal sudah tidak muncul.

---

## 6. Cara Menjalankan

Project dapat dijalankan secara langsung tanpa instalasi tambahan.

1. Buka folder project.
2. Cari file `index.html`.
3. Buka file tersebut menggunakan browser.
4. Untuk menguji responsivitas, buka DevTools dan gunakan Device Toolbar untuk mencoba beberapa ukuran viewport.

Project ini dibuat menggunakan **HTML5 dan CSS murni**, sehingga tidak membutuhkan framework atau library tambahan.

---

## 7. Refleksi

Melalui mini project ini saya lebih memahami bagaimana HTML dan CSS digunakan secara bersama-sama untuk membuat halaman web. Saya juga lebih memahami pentingnya Semantic HTML dalam membuat struktur halaman, serta penggunaan Flexbox dan responsive layout agar tampilan dapat menyesuaikan ukuran layar.

Penggunaan DevTools juga membantu saya menemukan masalah secara langsung, terutama saat melakukan pengecekan struktur HTML, CSS, dan horizontal overflow.
