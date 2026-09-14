'use strict';
const tombolMuat = document.querySelector('#muat-data');
const status = document.querySelector('#status');
const daftarFitur = document.querySelector('#daftar-fitur');

function tampilkanState(state, pesan) {
    status.dataset.state = state;
    status.textContent = pesan;
}
function buatKartu(item) {
    const article = document.createElement('article');
    const heading = document.createElement('h2');
    const description = document.createElement('p');
    article.classList.add('feature-card');
    heading.textContent = item.judul;
    description.textContent = item.deskripsi;
    article.append(heading, description);
    return article;
}

function renderFitur(items) {
    daftarFitur.textContent = '';
    for (const item of items) {
      daftarFitur.append(buatKartu(item));
    }
}

// console.log('A: mulai');
// setTimeout(() => {
//  console.log('C: timer selesai');
// }, 0);

// console.log('B: akhir');

// function tunggu(ms) {
//     return new Promise((resolve) => {
//     setTimeout(resolve, ms);
//  });
// }
// async function ujiTunggu() {
//     console.log('Promise dimulai');
//     await tunggu(800);
//     console.log('Promise selesai');
// }
// ujiTunggu();

async function ambilFitur() {
 const response = await fetch('data/features.json');
 if (!response.ok) {
 throw new Error(`HTTP ${response.status}`);
 }
 return response.json();
}

async function muatFitur() {
    tombolMuat.disabled = true;
    tombolMuat.setAttribute('aria-busy', 'true');
    daftarFitur.textContent = '';
    tampilkanState('loading', 'Memuat data...');
    try {
    const data = await ambilFitur();
    if (!Array.isArray(data)) {
    throw new Error('Format data bukan array.');
    }
    if (data.length === 0) {
    tampilkanState('empty', 'Data kosong.');
    return;
    }
    renderFitur(data);
    tampilkanState('success', `${data.length} data tampil.`);
    } catch (error) {
    console.error(error);
    tampilkanState('error', `Gagal: ${error.message}`);
    } finally {
    tombolMuat.disabled = false;
    tombolMuat.removeAttribute('aria-busy');
    }
}
tombolMuat.addEventListener('click', muatFitur);
