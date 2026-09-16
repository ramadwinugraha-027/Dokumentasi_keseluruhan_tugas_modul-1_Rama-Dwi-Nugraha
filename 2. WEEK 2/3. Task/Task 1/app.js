'use strict';

function validasiNilai(nilai) {
  let isNumber = typeof nilai === 'number';
  
  if (isNumber == true && nilai >= 0 && nilai <= 100) {
    return true;
  } else {
    return false;
  }
}

function tentukanKategori(nilai) {
  let cekValid = validasiNilai(nilai);
  
  if (cekValid == true) {
    if (nilai >= 85) {
      return 'A';
    } 
    else if (nilai >= 70 && nilai < 85) {
      return 'B';
    } 
    else if (nilai >= 60 && nilai < 70) {
      return 'C';
    } 
    else {
      return 'D';
    }
  } else {
    return null;
  }
}

function tentukanStatus(nilai) {
  let cekValid = validasiNilai(nilai);
  
  if (cekValid == true) {
    if (nilai >= 60) {
      return 'Lulus';
    } else {
      return 'Tidak lulus';
    }
  } else {
    return 'Data tidak valid';
  }
}

function buatRingkasan(nama, nilai) {
  let kategoriNya = tentukanKategori(nilai);
  let statusNya = tentukanStatus(nilai);
  
  let objekRingkasan = {
    nama: nama,
    nilai: nilai,
    kategori: kategoriNya,
    status: statusNya
  };
  
  return objekRingkasan;
}

let kasusUji = [
  { nama: 'Alya', nilai: 0 },
  { nama: 'Bima', nilai: 59 },
  { nama: 'Citra', nilai: 60 },
  { nama: 'Danu', nilai: 69 },
  { nama: 'Eka', nilai: 70 },
  { nama: 'Fani', nilai: 85 },
  { nama: 'Gilang', nilai: 101 },
  { nama: 'Hana', nilai: 84 },
  { nama: 'Indra', nilai: '80' }
];

let hasilUji = [];
for (let i = 0; i < kasusUji.length; i++) {
  let namaMahasiswa = kasusUji[i].nama;
  let nilaiMahasiswa = kasusUji[i].nilai;
  
  let hasil = buatRingkasan(namaMahasiswa, nilaiMahasiswa);
  hasilUji.push(hasil);
}

console.table(hasilUji);