function hitungDiskon(subtotal, isMember) {
    var diskon = 0;

    if (subtotal >= 200000) {
        diskon = 0.20;
    } else if (subtotal >= 100000) {
        diskon = 0.10;
    }

    if (isMember === true) {
        diskon = diskon + 0.05;
    }

    if (diskon > 0.25) {
        diskon = 0.25;
    }

    return subtotal * diskon;
}

function prosesTransaksi(harga, jumlah, isMember) {
    if (harga <= 0) {
        console.log("Error: Harga harus lebih besar dari 0");
        return;
    }

    if (jumlah <= 0) {
        console.log("Error: Jumlah harus lebih besar dari 0");
        return;
    }

    var subtotal = harga * jumlah;
    var totalDiskon = hitungDiskon(subtotal, isMember);
    var totalBayar = subtotal - totalDiskon;

    var statusMember = "Bukan Member";
    if (isMember === true) {
        statusMember = "Member";
    }

    console.log("Harga      : Rp " + harga);
    console.log("Jumlah     : " + jumlah);
    console.log("Status     : " + statusMember);
    console.log("Subtotal   : Rp " + subtotal);
    console.log("Diskon     : Rp " + totalDiskon);
    console.log("Total Bayar: Rp " + totalBayar);
    console.log("-----------------------------------");
}

prosesTransaksi(-5000, 2, false);
prosesTransaksi(50000, 2, false);
prosesTransaksi(100000, 1, true);
prosesTransaksi(200000, 1, false);
prosesTransaksi(250000, 1, true);