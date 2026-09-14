# Praktikum Modul 1 - Rama Dwi Nugraha / 251511027

## Ringkasan

Halaman ini merupakan web profil mahasiswa yang dibuat menggunakan HTML5 dan CSS tanpa framework. Struktur halaman menggunakan Semantic HTML dan pendekatan mobile-first agar tampilan dapat menyesuaikan berbagai ukuran layar.

## Tiga Keputusan Teknis

1. **Semantic HTML**
   Menggunakan `<header>`, `<nav>`, `<main>`, `<section>`, dan `<footer>` sesuai fungsi masing-masing agar struktur halaman lebih jelas dan mudah dipahami.

2. **Mobile-First dan Flexbox**
   Tampilan awal dibuat satu kolom untuk layar kecil. Pada `768px`, layout berubah menjadi beberapa kolom menggunakan Flexbox.

3. **Aksesibilitas Keyboard**
   Menggunakan `:focus-visible` pada elemen interaktif agar elemen yang sedang dipilih menggunakan tombol `Tab` lebih mudah terlihat.

## Masalah dan Perbaikan

* **Gambar tidak muncul:** Setelah diperiksa melalui DevTools, ditemukan error `404` karena path gambar tidak sesuai. Path kemudian diperbaiki menjadi `assets/profile.jpeg`.
* **Horizontal overflow:** Pada viewport kecil, gambar melebihi lebar container dan menyebabkan scrollbar horizontal. Saya menambahkan `max-width: 100%` dan `height: auto` agar gambar dapat menyesuaikan ukuran container.

## Hasil Pengujian

Pengujian dilakukan pada empat viewport:

* **320px:** Layout satu kolom dan tidak overflow.
* **375px:** Konten tetap rapi dan mudah dibaca.
* **768px:** Breakpoint aktif dan elemen mulai tersusun berdampingan.
* **1024px:** Konten tetap berada di tengah dengan lebar maksimal yang ditentukan.

## Refleksi Belajar

Dari praktikum ini saya lebih memahami bahwa HTML tidak hanya digunakan untuk menampilkan konten, tetapi juga untuk membuat struktur halaman yang jelas. Saya juga belajar bahwa CSS perlu dibuat responsif agar tampilan tetap baik pada ukuran layar yang berbeda. Penggunaan DevTools membantu saya menemukan penyebab error secara langsung, terutama saat memperbaiki path gambar dan masalah overflow.

## Log AI / Sumber Bantuan

AI saya gunakan sebagai tutor dan pemeriksa untuk memahami HTML, CSS, Semantic HTML, DevTools, responsivitas, serta memberikan feedback terhadap tugas dan mini project. Saran yang diberikan tetap saya cek melalui kode, DevTools, browser, dan pengujian langsung.
