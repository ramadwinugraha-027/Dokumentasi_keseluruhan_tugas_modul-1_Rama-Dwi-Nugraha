'use strict';

const status = document.querySelector('#status');
const daftar = document.querySelector('#daftar-materi');
const tombolMuat = document.querySelector('#muat');
const tombolCobaLagi = document.querySelector('#coba-lagi');

function aturState(state, pesan) {
  status.dataset.state = state;
  status.textContent = pesan;
  tombolCobaLagi.hidden = state !== 'error';
}

async function ambilMateri() {
  const response = await fetch('data/materi.json');

  if (!response.ok) {
    throw new Error(
      `Gagal memuat materi (HTTP ${response.status} ${response.statusText})`
    );
  }

  return response.json();
}

function renderMateri(data) {
  daftar.replaceChildren();

  data.forEach((materi) => {
    const kartu = document.createElement('article');
    kartu.className = 'kartu-materi';

    const judul = document.createElement('h3');
    judul.textContent = materi.judul;

    const deskripsi = document.createElement('p');
    deskripsi.textContent = materi.deskripsi;

    kartu.append(judul, deskripsi);
    daftar.appendChild(kartu);
  });
}

async function muatData() {
  aturState('loading', 'Memuat data...');
  tombolMuat.disabled = true;
  daftar.replaceChildren();

  try {
    const data = await ambilMateri();

    if (!Array.isArray(data)) {
      throw new Error('Format data materi tidak valid.');
    }

    if (data.length === 0) {
      aturState('empty', 'Belum ada materi.');
      return;
    }

    renderMateri(data);
    aturState('success', `${data.length} materi berhasil dimuat.`);
  } catch (error) {
    console.error(error);
    aturState(
      'error',
      `Gagal memuat materi: ${error.message || 'Terjadi kesalahan.'}`
    );
  } finally {
    tombolMuat.disabled = false;
  }
}

tombolMuat.addEventListener('click', muatData);
tombolCobaLagi.addEventListener('click', muatData);
