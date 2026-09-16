var btnMuat = document.getElementById('btn-muat');
var btnCobaLagi = document.getElementById('btn-coba-lagi');
var teksOutput = document.getElementById('teks-output');
var statusAkses = document.getElementById('status-akses');

function ambilDataRandom() {
  return new Promise(function(resolve, reject) {
    var waktuDelay = Math.floor(Math.random() * (1500 - 500 + 1)) + 500;

    setTimeout(function() {
      var gagalSimulasi = Math.random() < 0.3;

      if (gagalSimulasi) {
        reject(new Error("Gagal mengambil data dari server (Simulasi Error 30%)."));
      } else {
        fetch('json/data.json')
          .then(function(res) {
            if (!res.ok) {
              throw new Error("Gagal membaca file data.json");
            }
            return res.json();
          })
          .then(function(daftarTips) {
            var indexAcak = Math.floor(Math.random() * daftarTips.length);
            resolve(daftarTips[indexAcak]);
          })
          .catch(function(err) {
            reject(err);
          });
      }
    }, waktuDelay);
  });
}

function aturStateLoading(sedangLoading) {
  if (sedangLoading) {
    btnMuat.disabled = true;
    btnCobaLagi.disabled = true;
    btnMuat.textContent = "Memuat...";
    teksOutput.className = "pesan-empty";
    teksOutput.textContent = "Sedang mengambil data tips...";
    statusAkses.textContent = "Sedang memuat data, mohon tunggu.";
  } else {
    btnMuat.disabled = false;
    btnCobaLagi.disabled = false;
    btnMuat.textContent = "Muat Data";
  }
}

function eksekusiMuatData() {
  aturStateLoading(true);
  btnCobaLagi.hidden = true;

  ambilDataRandom()
    .then(function(tips) {
      teksOutput.className = "pesan-sukses";
      teksOutput.textContent = tips;
      statusAkses.textContent = "Data berhasil dimuat.";
    })
    .catch(function(error) {
      teksOutput.className = "pesan-error";
      teksOutput.textContent = error.message;
      btnCobaLagi.hidden = false;
      statusAkses.textContent = "Terjadi kesalahan saat memuat data.";
      console.error("[Log Error]:", error);
    })
    .finally(function() {
      aturStateLoading(false);
    });
}

btnMuat.addEventListener('click', eksekusiMuatData);
btnCobaLagi.addEventListener('click', eksekusiMuatData);