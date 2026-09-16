let currentSkills = [];

async function fetchProfileData() {
  const loadingEl = document.getElementById('state-loading');
  const errorEl = document.getElementById('state-error');
  const cardEl = document.getElementById('profile-card');

  loadingEl.classList.remove('hidden');
  errorEl.classList.add('hidden');
  cardEl.classList.add('hidden');

  try {
    const response = await fetch('json/data.json');
    if (!response.ok) throw new Error('Gagal mengambil data');
    const data = await response.json();

    renderProfile(data);
    currentSkills = [...data.skills]; 
    renderSkills();

    loadingEl.classList.add('hidden');
    cardEl.classList.remove('hidden');
  } catch (err) {
    loadingEl.classList.add('hidden');
    errorEl.classList.remove('hidden');
  }
}

function renderProfile(data) {
  document.getElementById('profile-img').src = data.avatar;
  document.getElementById('profile-name').textContent = data.name;
  document.getElementById('profile-role').textContent = data.role;
  document.getElementById('profile-bio').textContent = data.bio;
  document.getElementById('detail-text').textContent = data.detail;
}

function renderSkills() {
  const listEl = document.getElementById('skill-list');
  const emptyEl = document.getElementById('state-empty-skill');
  listEl.innerHTML = '';

  if (currentSkills.length === 0) {
    emptyEl.classList.remove('hidden');
  } else {
    emptyEl.classList.add('hidden');
    currentSkills.forEach((skill, index) => {
      const li = document.createElement('li');
      li.innerHTML = `
        <span>${skill}</span>
        <button class="btn-delete-skill" onclick="deleteSkill(${index})">✕</button>
      `;
      listEl.appendChild(li);
    });
  }
}

function deleteSkill(index) {
  currentSkills.splice(index, 1);
  renderSkills();
}

document.getElementById('skill-form').addEventListener('submit', (e) => {
  e.preventDefault();
  const input = document.getElementById('skill-input');
  const errorEl = document.getElementById('form-error');
  const value = input.value.trim();

  if (!value) {
    errorEl.classList.remove('hidden');
    return;
  }

  errorEl.classList.add('hidden');
  currentSkills.push(value);
  renderSkills();
  input.value = '';
});

const btnDetail = document.getElementById('btn-detail');
const detailBox = document.getElementById('profile-detail');

btnDetail.addEventListener('click', () => {
  const isExpanded = btnDetail.getAttribute('aria-expanded') === 'true';
  btnDetail.setAttribute('aria-expanded', !isExpanded);
  detailBox.classList.toggle('hidden');
  btnDetail.textContent = isExpanded ? 'Tampilkan Detail' : 'Sembunyikan Detail';
});

document.getElementById('theme-toggle').addEventListener('click', () => {
  document.body.classList.toggle('dark-mode');
});

document.getElementById('btn-retry').addEventListener('click', fetchProfileData);

fetchProfileData();