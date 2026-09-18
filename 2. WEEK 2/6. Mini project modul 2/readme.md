# Klub Koding Kampus - Mini Project Modul 2

Klub Koding Kampus adalah landing page untuk komunitas belajar mahasiswa Teknik Informatika. Proyek ini merupakan pengembangan dari landing page pada Modul 1 yang sebelumnya masih statis. Pada Modul 2, saya menambahkan beberapa fitur interaktif menggunakan JavaScript.

**Nama:** Rama Dwi Nugraha
**NIM:** 251511027
**Kelas:** D3-2A
**Program Studi:** D3 Teknik Informatika
**Mata Kuliah:** Proyek Pengembangan Perangkat Lunak Berbasis Web

---

## 1. Tentang Project

Klub Koding Kampus dibuat sebagai tempat untuk mendapatkan informasi tentang kegiatan belajar, jadwal diskusi, FAQ, dan pendaftaran kelompok belajar.

Target pengguna dari website ini adalah mahasiswa tingkat awal Teknik Informatika yang ingin belajar pemrograman web dari dasar dan berdiskusi bersama.

---

## 2. Fitur yang Dibuat

Pada Modul 2, saya menambahkan 7 fitur interaktif:

1. **Navigasi Mobile**
   Menu dapat dibuka dan ditutup menggunakan tombol.

2. **Daftar Kegiatan Dinamis**
   Data kegiatan disimpan dalam array lalu ditampilkan ke halaman menggunakan JavaScript.

3. **Filter Kegiatan**
   Daftar kegiatan dapat difilter berdasarkan kategori.

4. **FAQ Accordion**
   Jawaban FAQ dapat dibuka dan ditutup. Hanya satu jawaban yang terbuka dalam satu waktu.

5. **Form Kontak**
   Form melakukan validasi dan menampilkan pesan jika data yang dimasukkan salah atau berhasil dikirim.

6. **Back to Top**
   Tombol muncul ketika halaman di-scroll dan dapat digunakan untuk kembali ke bagian atas.

7. **Dark Mode**
   Pengguna dapat mengubah tampilan website menjadi tema gelap.

---

## 3. Teknologi yang Digunakan

Project ini menggunakan:

* HTML
* CSS
* Vanilla JavaScript

JavaScript dibuat pada file terpisah yaitu `js/app.js` dan menggunakan `defer`.

Pada daftar kegiatan, saya menggunakan array of objects. Elemen yang ditampilkan dibuat menggunakan `createElement()`, `textContent`, dan `appendChild()`.

Untuk interaksi seperti tombol, filter, FAQ, dan form digunakan `addEventListener()`.

Project ini tidak menggunakan framework atau library seperti React, Vue, atau jQuery.

---

## 4. Aksesibilitas

Beberapa fitur dibuat agar bisa digunakan menggunakan keyboard. Saya juga menggunakan atribut seperti `aria-expanded` dan `aria-controls` pada beberapa bagian yang membutuhkan.

Navigasi keyboard dapat dilakukan menggunakan `Tab`, `Enter`, dan `Space`.

---

## 5. Struktur Folder

```text
klub-koding-kampus/
├── assets/
│   └── workspace.jpg.png
├── css/
│   └── stylebaru.css
├── js/
│   └── app.js
├── index.html
└── README.md
```

---

## 6. Pengujian

Website sudah dicoba pada ukuran layar:

* 320px
* 768px
* 1024px

Saya juga mencoba fitur menggunakan mouse dan keyboard serta mengecek Console untuk memastikan tidak ada error.

---

## 7. Kesimpulan

Pada Modul 2, saya belajar membuat website yang sebelumnya statis menjadi lebih interaktif menggunakan JavaScript. Dari project ini saya belajar tentang event, DOM, array, filter, validasi form, dan perubahan tampilan menggunakan class CSS.
