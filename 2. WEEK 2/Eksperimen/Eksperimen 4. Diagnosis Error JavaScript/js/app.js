'use strict'; 
  
const form = document.querySelector('#form-hitung'); 
const hargaInput = document.querySelector('#harga'); 
const jumlahInput = document.querySelector('#jumlah'); 
const hasil = document.querySelector('#hasil'); 
const pesan = document.querySelector('#pesan'); 
  
function hitungTotal(harga, jumlah) { 
  return harga * jumlah; 
} 
  
function tampilkanPesan(teks) { 
  pesan.textContent = teks; 
} 
  
function prosesForm(event) { 
  console.count('prosesForm'); 
  event.preventDefault(); 
  
  const harga = Number(hargaInput.value); 
  const jumlah = Number(jumlahInput.value); 
  
  if (harga <= 0 || jumlah <= 0) { 
    tampilkanPesan('Harga dan jumlah harus positif.'); 
    return; 
  } 
  
  const total = hitungTotal(harga, jumlah); 
  hasil.textContent = total.toLocaleString('id-ID'); 
  tampilkanPesan('Perhitungan berhasil.'); 
} 
  
form.addEventListener('submit', prosesForm); 