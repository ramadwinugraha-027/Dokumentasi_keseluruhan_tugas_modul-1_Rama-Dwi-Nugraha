document.addEventListener('DOMContentLoaded', () => {
  let activitiesData = [];

  const cardTrackContainer = document.getElementById('activity-card-track');
  const filterSelect = document.getElementById('category-filter');

  async function loadActivitiesData() {
    try {
      const response = await fetch('data/kegiatan.json');
      activitiesData = await response.json();
      renderActivities(activitiesData);
    } catch (error) {
      if (cardTrackContainer) {
        const errorMsg = document.createElement('p');
        errorMsg.className = 'empty-state';
        errorMsg.textContent = 'Gagal memuat data kegiatan.';
        cardTrackContainer.appendChild(errorMsg);
      }
    }
  }

  function renderActivities(items) {
    if (!cardTrackContainer) return;

    cardTrackContainer.textContent = '';

    if (items.length === 0) {
      const emptyMsg = document.createElement('p');
      emptyMsg.className = 'empty-state';
      emptyMsg.textContent = 'Tidak ada kegiatan ditemukan untuk kategori ini.';
      cardTrackContainer.appendChild(emptyMsg);
      return;
    }

    items.forEach(item => {
      const card = document.createElement('article');
      card.className = item.category === 'rutin' ? 'board board-highlight' : 'board';

      const badge = document.createElement('span');
      badge.className = item.category === 'rutin' ? 'board-badge badge-invert' : 'board-badge';
      badge.textContent = item.badge;

      const title = document.createElement('h3');
      title.className = 'board-title';
      title.textContent = item.title;

      const desc = document.createElement('p');
      desc.className = 'board-text';
      desc.textContent = item.desc;

      const specsList = document.createElement('ul');
      specsList.className = 'board-specs';

      item.specs.forEach(specText => {
        const li = document.createElement('li');
        li.textContent = specText;
        specsList.appendChild(li);
      });

      card.appendChild(badge);
      card.appendChild(title);
      card.appendChild(desc);
      card.appendChild(specsList);

      cardTrackContainer.appendChild(card);
    });
  }

  if (filterSelect) {
    filterSelect.addEventListener('change', (e) => {
      const selectedValue = e.target.value;
      if (selectedValue === 'all') {
        renderActivities(activitiesData);
      } else {
        const filtered = activitiesData.filter(act => act.category === selectedValue);
        renderActivities(filtered);
      }
    });
  }

  loadActivitiesData();

  const navToggleBtn = document.getElementById('nav-toggle');
  const navMenu = document.getElementById('nav-menu');

  if (navToggleBtn && navMenu) {
    navToggleBtn.addEventListener('click', () => {
      const isExpanded = navToggleBtn.getAttribute('aria-expanded') === 'true';
      navToggleBtn.setAttribute('aria-expanded', !isExpanded);
      navMenu.classList.toggle('is-active');
    });

    const navItems = navMenu.querySelectorAll('a');
    navItems.forEach(item => {
      item.addEventListener('click', () => {
        if (navMenu.classList.contains('is-active')) {
          navMenu.classList.remove('is-active');
          navToggleBtn.setAttribute('aria-expanded', 'false');
        }
      });
    });
  }

  const accordionButtons = document.querySelectorAll('.accordion-trigger');

  accordionButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      const isExpanded = btn.getAttribute('aria-expanded') === 'true';
      const targetId = btn.getAttribute('aria-controls');
      const targetContent = document.getElementById(targetId);

      accordionButtons.forEach(otherBtn => {
        if (otherBtn !== btn) {
          otherBtn.setAttribute('aria-expanded', 'false');
          const otherId = otherBtn.getAttribute('aria-controls');
          const otherContent = document.getElementById(otherId);
          if (otherContent) otherContent.hidden = true;
        }
      });

      btn.setAttribute('aria-expanded', !isExpanded);
      if (targetContent) {
        targetContent.hidden = isExpanded;
      }
    });
  });

  const contactForm = document.getElementById('contact-form');
  const formFeedback = document.getElementById('form-feedback');

  if (contactForm) {
    contactForm.addEventListener('submit', (e) => {
      e.preventDefault();

      const inputNama = document.getElementById('form-nama');
      const inputEmail = document.getElementById('form-email');
      const inputAlasan = document.getElementById('form-alasan');

      const errNama = document.getElementById('err-nama');
      const errEmail = document.getElementById('err-email');
      const errAlasan = document.getElementById('err-alasan');

      errNama.textContent = '';
      errEmail.textContent = '';
      errAlasan.textContent = '';
      if (formFeedback) {
        formFeedback.textContent = '';
        formFeedback.classList.add('hidden');
      }

      let isValid = true;

      if (!inputNama.value.trim()) {
        errNama.textContent = 'Nama lengkap wajib diisi.';
        isValid = false;
      }

      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!inputEmail.value.trim()) {
        errEmail.textContent = 'Email kampus wajib diisi.';
        isValid = false;
      } else if (!emailRegex.test(inputEmail.value.trim())) {
        errEmail.textContent = 'Format email tidak valid (contoh: user@kampus.ac.id).';
        isValid = false;
      }

      if (!inputAlasan.value.trim()) {
        errAlasan.textContent = 'Alasan/Pesan wajib diisi.';
        isValid = false;
      } else if (inputAlasan.value.trim().length < 10) {
        errAlasan.textContent = 'Berikan alasan minimal 10 karakter.';
        isValid = false;
      }

      if (isValid && formFeedback) {
        const valNama = inputNama.value.trim();
        const valEmail = inputEmail.value.trim();

        const successTitle = document.createElement('strong');
        successTitle.textContent = 'Pendaftaran Berhasil Dikirim!';

        const successText = document.createElement('p');
        successText.textContent = `Terima kasih, ${valNama} (${valEmail}). Sesi diskusi dan tautan grup akan dikirimkan ke email Anda.`;

        formFeedback.appendChild(successTitle);
        formFeedback.appendChild(successText);
        formFeedback.classList.remove('hidden');

        contactForm.reset();
      }
    });
  }

  const backToTopBtn = document.getElementById('back-to-top');

  if (backToTopBtn) {
    window.addEventListener('scroll', () => {
      if (window.scrollY > 300) {
        backToTopBtn.classList.remove('hidden');
      } else {
        backToTopBtn.classList.add('hidden');
      }
    });

    backToTopBtn.addEventListener('click', () => {
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });
  }

  const themeToggleBtn = document.getElementById('theme-toggle');

  if (themeToggleBtn) {
    themeToggleBtn.addEventListener('click', () => {
      document.body.classList.toggle('dark-theme');
      const isDark = document.body.classList.contains('dark-theme');
      themeToggleBtn.textContent = isDark ? 'Tema Terang' : 'Tema Gelap';
    });
  }
});