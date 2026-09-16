var daftarTombol = document.querySelectorAll('.faq-btn');

daftarTombol.forEach(function(tombol) {
  tombol.addEventListener('click', function() {
    var sedangTerbuka = tombol.getAttribute('aria-expanded') === 'true';
    var idJawaban = tombol.getAttribute('aria-controls');
    var elemenJawaban = document.getElementById(idJawaban);

    daftarTombol.forEach(function(itemTombol) {
      itemTombol.setAttribute('aria-expanded', 'false');
      var idTarget = itemTombol.getAttribute('aria-controls');
      document.getElementById(idTarget).setAttribute('hidden', '');
    });

    if (!sedangTerbuka) {
      tombol.setAttribute('aria-expanded', 'true');
      elemenJawaban.removeAttribute('hidden');
    }
  });
});