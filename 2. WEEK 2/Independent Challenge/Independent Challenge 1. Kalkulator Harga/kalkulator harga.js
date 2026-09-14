function hitungPembayaran(harga, jumlah, anggota) {
  if (harga <= 0 || jumlah <= 0) {
    return {
      valid: false,
      pesan: "Harga dan jumlah harus lebih besar dari nol."
    };
  }
  const subtotal = harga * jumlah;
  let diskon = 0;
  
  if (subtotal >= 200000) {
    diskon = 20;
  } else if (subtotal >= 100000) {
    diskon = 10;
  }

  if (anggota) {
    diskon = diskon + 5;
  }

  if (diskon > 25) {
    diskon = 25;
  }

  const nilaiDiskon = subtotal * diskon / 100;
  const total = subtotal - nilaiDiskon;
  return {
    valid: true,harga,jumlah, anggota, subtotal, diskon,nilaiDiskon,total
  };
}
