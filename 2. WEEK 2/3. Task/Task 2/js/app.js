'use strict'; 

const peserta = [ 
  { id: 1, nama: 'Alya', prodi: 'Teknik Informatika' }, 
  { id: 2, nama: 'Bima', prodi: 'Sistem Informasi' }, 
]; 

const form = document.querySelector('#form-peserta'); 
const namaInput = document.querySelector('#nama'); 
const prodiInput = document.querySelector('#prodi'); 
const filterInput = document.querySelector('#filter-prodi'); 
const daftar = document.querySelector('#daftar-peserta'); 
const status = document.querySelector('#status'); 
const errorNama = document.querySelector('#error-nama'); 
const errorProdi = document.querySelector('#error-prodi'); 

function validasiPeserta(calon) { 
  var pesanErrorNama = '';
  var pesanErrorProdi = '';

  if (!calon.nama || calon.nama.trim() === '') {
    pesanErrorNama = 'Nama wajib diisi.';
  } else if (calon.nama.trim().length <= 2) {
    pesanErrorNama = 'Nama terlalu pendek, minimal harus 3 karakter.';
  }

  if (!calon.prodi || calon.prodi.trim() === '') {
    pesanErrorProdi = 'Program studi wajib dipilih/diisi.';
  }

  return {
    valid: pesanErrorNama === '' && pesanErrorProdi === '',
    errorNama: pesanErrorNama,
    errorProdi: pesanErrorProdi
  };
} 

function buatKartuPeserta(item) { 
  var article = document.createElement('article');
  article.className = 'kartu-peserta';

  var h2 = document.createElement('h2');
  h2.textContent = item.nama;

  var p = document.createElement('p');
  p.textContent = item.prodi;

  article.appendChild(h2);
  article.appendChild(p);

  return article;
} 

function renderPeserta(data) { 
  daftar.textContent = '';

  if (data.length === 0) {
    var pKosong = document.createElement('p');
    pKosong.textContent = 'Tidak ada peserta yang ditemukan.';
    daftar.appendChild(pKosong);
    status.textContent = 'Menampilkan 0 peserta.';
    return;
  }

  data.forEach(function(item) {
    var kartu = buatKartuPeserta(item);
    daftar.appendChild(kartu);
  });

  status.textContent = 'Menampilkan ' + data.length + ' peserta.';
} 

form.addEventListener('submit', (event) => { 
  event.preventDefault(); 
  
  var calon = {
    nama: namaInput.value,
    prodi: prodiInput.value
  };

  var hasilValidasi = validasiPeserta(calon);

  if (!hasilValidasi.valid) {
    errorNama.textContent = hasilValidasi.errorNama;
    errorProdi.textContent = hasilValidasi.errorProdi;

    namaInput.setAttribute('aria-invalid', hasilValidasi.errorNama !== '' ? 'true' : 'false');
    prodiInput.setAttribute('aria-invalid', hasilValidasi.errorProdi !== '' ? 'true' : 'false');
    return;
  }

  errorNama.textContent = '';
  errorProdi.textContent = '';
  namaInput.setAttribute('aria-invalid', 'false');
  prodiInput.setAttribute('aria-invalid', 'false');

  var idBaru = peserta.length > 0 ? peserta[peserta.length - 1].id + 1 : 1;
  
  peserta.push({
    id: idBaru,
    nama: calon.nama.trim(),
    prodi: calon.prodi.trim()
  });

  form.reset();
  renderPeserta(peserta);
}); 

filterInput.addEventListener('change', () => { 
  var pilihan = filterInput.value;

  if (pilihan === 'semua' || pilihan === '') {
    renderPeserta(peserta);
  } else {
    var hasilFilter = peserta.filter(function(item) {
      return item.prodi.toLowerCase() === pilihan.toLowerCase();
    });
    renderPeserta(hasilFilter);
  }
}); 

renderPeserta(peserta);