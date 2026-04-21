/* ════════════════════════════════════════════════════════
   ADMIN.JS — Dashboard Logic, CRUD, Save to localStorage
   ════════════════════════════════════════════════════════ */

'use strict';

/* ══════════════════════════════════════
   0. HELPERS
══════════════════════════════════════ */
const $ = id => document.getElementById(id);
const csrfToken = window.__CSRF_TOKEN__ || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
let dashboardInitialized = false;

const SKILL_TABS = [
  { key: 'networking', label: 'Networking' },
  { key: 'webDevelopment', label: 'Web Development' },
  { key: 'androidDevelopment', label: 'Android Development' },
  { key: 'productivityTools', label: 'Productivity Tools' },
  { key: 'professionalStrengths', label: 'Professional Strengths' }
];

const toast = (msg, type = 'success') => {
  const c = $('toastContainer');
  const t = document.createElement('div');
  const icons = { success: 'fa-check-circle', error: 'fa-times-circle', warning: 'fa-exclamation-triangle' };
  t.className = `toast ${type}`;
  t.innerHTML = `<i class="fas ${icons[type] || icons.success}"></i><span class="toast-msg">${msg}</span><i class="fas fa-times toast-close"></i>`;
  t.querySelector('.toast-close').addEventListener('click', () => removeToast(t));
  c.appendChild(t);
  setTimeout(() => removeToast(t), 4000);
};
const removeToast = el => {
  el.classList.add('hiding');
  setTimeout(() => el.remove(), 350);
};

async function requestJson(url, options = {}) {
  const { method = 'GET', data, headers = {} } = options;
  const requestHeaders = { Accept: 'application/json', ...headers };

  if (method !== 'GET' && method !== 'HEAD' && csrfToken) {
    requestHeaders['X-CSRF-TOKEN'] = csrfToken;
  }

  if (data !== undefined && !requestHeaders['Content-Type']) {
    requestHeaders['Content-Type'] = 'application/json';
  }

  const response = await fetch(url, {
    method,
    headers: requestHeaders,
    body: data === undefined ? undefined : JSON.stringify(data)
  });

  const contentType = response.headers.get('content-type') || '';
  const payload = contentType.includes('application/json') ? await response.json() : {};

  if (!response.ok) {
    const error = new Error(payload.error || payload.message || 'Request failed.');
    error.status = response.status;
    error.payload = payload;
    throw error;
  }

  return payload;
}

function replaceServerState(data) {
  PortfolioData.save(data || {});
}

function showDashboard(data) {
  if (data) replaceServerState(data);

  $('loginScreen').style.display = 'none';
  $('dashboard').style.display = 'flex';

  if (!dashboardInitialized) {
    dashboardInitialized = true;
    initDashboard();
    return;
  }

  draft = PortfolioData.get();
  populateForms();
}

function showLogin(error = '') {
  $('dashboard').style.display = 'none';
  $('loginScreen').style.display = 'flex';
  $('loginError').textContent = error;
}

/* ══════════════════════════════════════
   1. AUTH
══════════════════════════════════════ */
async function initAuth() {
  // Toggle password visibility
  $('toggleLoginPwd').addEventListener('click', () => {
    const inp  = $('loginPasswordInput');
    const icon = $('loginEyeIcon');
    if (inp.type === 'password') { inp.type = 'text'; icon.className = 'fas fa-eye-slash'; }
    else                         { inp.type = 'password'; icon.className = 'fas fa-eye'; }
  });

  // Login
  const doLogin = async () => {
    const val = $('loginPasswordInput').value;
    const btn = $('loginBtn');
    if (!val) { $('loginError').textContent = 'Please enter a password.'; return; }

    $('loginError').textContent = '';
    btn.disabled = true;

    try {
      const payload = await requestJson('/admin/login', {
        method: 'POST',
        data: { password: val },
        headers: { 'Content-Type': 'application/json' }
      });
      $('loginPasswordInput').value = '';
      showDashboard(payload.data || {});
    } catch (error) {
      $('loginError').textContent = error.message || 'Login failed.';
    } finally {
      btn.disabled = false;
    }
  };

  $('loginBtn').addEventListener('click', doLogin);
  $('loginPasswordInput').addEventListener('keydown', e => { if (e.key === 'Enter') void doLogin(); });

  // Logout
  $('logoutBtn').addEventListener('click', async () => {
    try {
      await requestJson('/admin/logout', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' }
      });
    } catch (_) {
      // Reloading still clears the UI state even if the request fails.
    }
    location.reload();
  });

  try {
    const payload = await requestJson('/admin/bootstrap');
    if (payload.authenticated) {
      showDashboard(payload.data || {});
      return;
    }
  } catch (_) {
    // Keep the login screen visible if the bootstrap check fails.
  }

  showLogin();
}

/* ══════════════════════════════════════
   2. PANEL SWITCHING
══════════════════════════════════════ */
let currentPanel = 'overview';

function switchPanel(name) {
  // Deactivate all nav items
  document.querySelectorAll('.nav-item').forEach(b => b.classList.remove('active'));
  const navBtn = document.querySelector(`[data-panel="${name}"]`);
  if (navBtn) navBtn.classList.add('active');

  // Hide all panels, show target
  document.querySelectorAll('.panel').forEach(p => p.classList.remove('active'));
  const panel = $(`panel-${name}`);
  if (panel) panel.classList.add('active');

  // Breadcrumb
  const labels = {
    overview:'Overview', hero:'Hero', about:'About', skills:'Skills',
    projects:'Projects', experience:'Experience', achievements:'Achievements',
    education:'Education', contact:'Contact', inbox:'Inbox', settings:'Settings'
  };
  $('breadcrumbSection').textContent = labels[name] || name;
  currentPanel = name;

  // Load inbox when navigating to it
  if (name === 'inbox') loadInbox();

  // Mobile: close sidebar
  if (window.innerWidth < 900) $('sidebar').classList.remove('mobile-open');
}

/* ══════════════════════════════════════
   3. WORKING DATA (in-memory draft)
══════════════════════════════════════ */
let draft = {};

/* ══════════════════════════════════════
   4. POPULATE FORMS FROM DATA
══════════════════════════════════════ */
function populateForms() {
  const d = draft;

  ensureSkillBuckets();
  if (!Array.isArray(d.achievements)) d.achievements = [];
  if (!Array.isArray(d.education)) d.education = [];
  if (!Array.isArray(d.languages)) d.languages = [];
  if (!d.contact) d.contact = {};
  if (!d.contact.social) d.contact.social = {};

  /* META / SETTINGS */
  setValue('metaName',      d.meta?.name);
  setValue('metaRole',      d.meta?.role);
  setValue('metaBrandText', d.meta?.brandText);
  setValue('metaSiteTitle', d.meta?.siteTitle);
  setValue('metaSiteDesc',  d.meta?.siteDesc);
  setValue('metaSiteKeywords', d.meta?.siteKeywords);
  setValue('footerTagline', d.footer?.tagline);

  /* HERO */
  setValue('heroTag',           d.hero?.availableTag);
  setValue('heroFirstName',     d.hero?.firstName);
  setValue('heroHighlightName', d.hero?.highlightName);
  setValue('heroDescription',   d.hero?.description);
  // Orbit speed slider
  const orbitSlider = $('heroOrbitSpeed');
  const orbitVal    = $('heroOrbitSpeedVal');
  if (orbitSlider && d.hero?.orbitSpeed) {
    orbitSlider.value = d.hero.orbitSpeed;
    if (orbitVal) orbitVal.textContent = d.hero.orbitSpeed + 's';
  }
  buildTagChips('heroTypedTagList', 'heroTypedInput', 'heroTypedAddBtn', d.hero?.typedWords || [], (newList) => { draft.hero.typedWords = newList; });
  buildStatsEditor(d.hero?.stats || []);

  /* ABOUT */
  setValue('aboutHeading',          d.about?.heading);
  setValue('aboutHeadingHighlight', d.about?.headingHighlight);
  setValue('aboutText1',            d.about?.text1);
  setValue('aboutText2',            d.about?.text2);
  setValue('aboutName',             d.about?.name);
  setValue('aboutEmail',            d.about?.email);
  setValue('aboutLocation',         d.about?.location);
  setValue('aboutDegree',           d.about?.degree);
  setValue('aboutExpYears',         d.about?.expYears);

  /* SKILLS */
  SKILL_TABS.forEach(({ key }) => buildSkillsList(key));

  /* PROJECTS */
  buildProjectsList();

  /* EXPERIENCE */
  buildExperienceList();

  /* ACHIEVEMENTS / EDUCATION */
  buildAchievementsList();
  buildEducationList();
  buildLanguagesList();

  /* CONTACT */
  setValue('contactHeading',  d.contact?.heading);
  setValue('contactSubtext',  d.contact?.subtext);
  setValue('contactEmail',    d.contact?.email);
  setValue('contactPhone',    d.contact?.phone);
  setValue('contactLocation', d.contact?.location);
  setValue('contactPortfolioUrl', d.contact?.portfolioUrl);
  setValue('contactResumeUrl', d.contact?.resumeUrl);
  setValue('socialGithub',    d.contact?.social?.github);
  setValue('socialLinkedin',  d.contact?.social?.linkedin);
  setValue('socialTwitter',   d.contact?.social?.twitter);
  setValue('socialDribbble',  d.contact?.social?.dribbble);
  setValue('socialInstagram', d.contact?.social?.instagram);

  /* WHATSAPP API CONFIG */
  const waEnabled = $('waEnabled');
  const waLabel   = $('waEnabledLabel');
  if (waEnabled) {
    waEnabled.checked = !!(d.contact?.whatsappApi?.enabled);
    if (waLabel) waLabel.textContent = waEnabled.checked ? 'Enabled' : 'Disabled';
    waEnabled.addEventListener('change', () => {
      if (waLabel) waLabel.textContent = waEnabled.checked ? 'Enabled' : 'Disabled';
    });
  }
  setValue('waApiKey',       d.contact?.whatsappApi?.apiKey);
  setValue('waAccountName',  d.contact?.whatsappApi?.accountName);
  setValue('waTargetNumber', d.contact?.whatsappApi?.targetNumber);

  /* IMAGES — set preview thumbnails */
  const hi = $('heroImgPreviewEl');
  if (hi && d.images?.hero)  hi.src = d.images.hero;
  const ai = $('aboutImgPreviewEl');
  if (ai && d.images?.about) ai.src = d.images.about;
}

function setValue(id, val) {
  const el = $(id);
  if (el && val !== undefined && val !== null) el.value = val;
}

/* ── STATS EDITOR ── */
function buildStatsEditor(stats) {
  const container = $('heroStatsContainer');
  container.innerHTML = '';
  stats.forEach((stat, i) => {
    const card = document.createElement('div');
    card.className = 'stat-edit-card';
    card.innerHTML = `
      <div><label>Stat ${i+1} Number</label><input type="number" id="statNum${i}" value="${stat.number}" /></div>
      <div><label>Stat ${i+1} Label</label><input type="text" id="statLbl${i}" value="${stat.label}" /></div>`;
    container.appendChild(card);
  });
}

/* ── TAG CHIPS ── */
function buildTagChips(listId, inputId, addBtnId, tags, onChange) {
  const listEl = $(listId);
  const inputEl = $(inputId);
  const addBtn = $(addBtnId);
  if (!listEl) return;

  let items = [...tags];

  const render = () => {
    listEl.innerHTML = '';
    items.forEach((tag, i) => {
      const chip = document.createElement('span');
      chip.className = 'tag-chip';
      chip.innerHTML = `${tag} <button aria-label="Remove ${tag}"><i class="fas fa-times"></i></button>`;
      chip.querySelector('button').addEventListener('click', () => {
        items.splice(i, 1); onChange([...items]); render();
      });
      listEl.appendChild(chip);
    });
  };

  const addTag = () => {
    const val = inputEl.value.trim();
    if (!val || items.includes(val)) return;
    items.push(val); inputEl.value = ''; onChange([...items]); render();
  };

  addBtn.addEventListener('click', addTag);
  inputEl.addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); addTag(); } });
  render();
}

/* ══════════════════════════════════════
   5. SKILLS LIST
══════════════════════════════════════ */
function ensureSkillBuckets() {
  if (!draft.skills) draft.skills = {};
  SKILL_TABS.forEach(({ key }) => {
    if (!Array.isArray(draft.skills[key])) {
      draft.skills[key] = [];
    }
  });
}

let activeSkillTab = SKILL_TABS[0].key;

function buildSkillsList(tab) {
  ensureSkillBuckets();
  const container = $(`skillList-${tab}`);
  if (!container) return;
  container.innerHTML = '';
  (draft.skills[tab] || []).forEach((skill, i) => {
    container.appendChild(makeSkillRow(tab, i, skill));
  });
}

function makeSkillRow(tab, idx, skill) {
  const row = document.createElement('div');
  row.className = 'skill-item';
  row.innerHTML = `
    <div>
      <label>Skill Name</label>
      <input type="text" class="si-name" value="${escH(skill.name)}" placeholder="Skill Name" />
    </div>
    <div>
      <label>Icon Class (Font Awesome)</label>
      <input type="text" class="si-icon" value="${escH(skill.iconClass)}" placeholder="fab fa-react" />
    </div>
    <div>
      <label>Icon Color</label>
      <input type="color" class="si-color" value="${skill.iconColor || '#7c3aed'}" />
    </div>
    <div class="skill-range-wrap">
      <label>Level</label>
      <input type="range" class="si-level" min="1" max="100" value="${skill.level}" />
      <span class="skill-range-label">${skill.level}%</span>
    </div>
    <button class="btn-remove-skill" title="Remove skill"><i class="fas fa-times"></i></button>`;

  // Live level label
  const range = row.querySelector('.si-level');
  const label = row.querySelector('.skill-range-label');
  range.addEventListener('input', () => {
    label.textContent = range.value + '%';
    draft.skills[tab][idx].level = +range.value;
  });

  // Remove
  row.querySelector('.btn-remove-skill').addEventListener('click', () => {
    draft.skills[tab].splice(idx, 1);
    buildSkillsList(tab);
  });

  // Live sync
  row.querySelector('.si-name').addEventListener('input', e => { draft.skills[tab][idx].name = e.target.value; });
  row.querySelector('.si-icon').addEventListener('input', e => { draft.skills[tab][idx].iconClass = e.target.value; });
  row.querySelector('.si-color').addEventListener('input', e => { draft.skills[tab][idx].iconColor = e.target.value; });

  return row;
}

function initSkillsTab() {
  document.querySelectorAll('.tab-btn[data-skillstab]').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('.tab-btn[data-skillstab]').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      activeSkillTab = btn.getAttribute('data-skillstab');
      document.querySelectorAll('.skill-tab-content').forEach(c => c.classList.remove('active'));
      $(`skillContent-${activeSkillTab}`).classList.add('active');
    });
  });

  $('addSkillBtn').addEventListener('click', () => {
    ensureSkillBuckets();
    draft.skills[activeSkillTab].push({ name: 'New Skill', iconClass: 'fas fa-code', iconColor: '#7c3aed', level: 100 });
    buildSkillsList(activeSkillTab);
    // Scroll to new item
    const list = $(`skillList-${activeSkillTab}`);
    list.lastElementChild?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  });
}

/* ══════════════════════════════════════
   6. PROJECTS LIST
══════════════════════════════════════ */
const GRADIENTS = ['gradient-1','gradient-2','gradient-3','gradient-4','gradient-5','gradient-6'];
const CATEGORIES = ['fullstack','frontend','mobile'];

function buildProjectsList() {
  const container = $('projectsList');
  container.innerHTML = '';
  (draft.projects || []).forEach((p, i) => {
    container.appendChild(makeProjectCard(i, p));
  });
}

function makeProjectCard(i, p) {
  const card = buildCollapsibleCard(
    p.title || 'Untitled Project',
    p.tags ? p.tags.join(', ') : '',
    buildProjectForm(i, p)
  );
  return card;
}

function buildProjectForm(i, p) {
  const wrap = document.createElement('div');
  wrap.innerHTML = `
    <div class="item-form-grid">
      <div><label>Project Title</label><input type="text" id="proj-title-${i}" value="${escH(p.title)}" /></div>
      <div><label>Category</label>
        <select id="proj-cat-${i}">
          ${CATEGORIES.map(c => `<option value="${c}" ${p.category===c?'selected':''}>${capitalize(c)}</option>`).join('')}
        </select>
      </div>
      <div class="full-width"><label>Description</label><textarea id="proj-desc-${i}" rows="3">${escH(p.desc)}</textarea></div>
      <div><label>Live URL</label><input type="url" id="proj-live-${i}" value="${escH(p.liveUrl || '#')}" placeholder="https://..." /></div>
      <div><label>GitHub URL</label><input type="url" id="proj-github-${i}" value="${escH(p.githubUrl || '#')}" placeholder="https://github.com/..." /></div>
      <div><label>Icon Class (Font Awesome)</label><input type="text" id="proj-icon-${i}" value="${escH(p.icon)}" /></div>
    </div>
    <div style="margin-bottom:12px">
      <label style="font-size:0.75rem;color:var(--text-muted);display:block;margin-bottom:8px;font-weight:600">Card Gradient Color</label>
      <div class="gradient-preview" id="proj-grad-${i}">
        ${GRADIENTS.map(g => `<div class="gradient-swatch g${g.slice(-1)} ${p.gradient===g?'selected':''}" data-g="${g}" title="${g}"></div>`).join('')}
      </div>
    </div>
    <div class="item-tags-section">
      <label style="font-size:0.75rem;color:var(--text-muted);font-weight:600">Tech Tags</label>
      <div class="item-tag-list" id="proj-tags-${i}"></div>
      <div class="item-tag-add">
        <input type="text" id="proj-tag-input-${i}" placeholder="Add tag…" />
        <button id="proj-tag-add-${i}"><i class="fas fa-plus"></i></button>
      </div>
    </div>`;

  // Live sync inputs
  const sync = (elId, key) => { const el = $(elId); if (el) el.addEventListener('input', e => { draft.projects[i][key] = e.target.value; updateCardTitle(wrap, draft.projects[i].title); }); };
  setTimeout(() => {
    sync(`proj-title-${i}`, 'title');
    sync(`proj-desc-${i}`, 'desc');
    sync(`proj-live-${i}`, 'liveUrl');
    sync(`proj-github-${i}`, 'githubUrl');
    sync(`proj-icon-${i}`, 'icon');
    const cat = $(`proj-cat-${i}`); if (cat) cat.addEventListener('change', e => { draft.projects[i].category = e.target.value; });

    // Gradient swatches
    const gradWrap = $(`proj-grad-${i}`);
    if (gradWrap) {
      gradWrap.querySelectorAll('.gradient-swatch').forEach(sw => {
        sw.addEventListener('click', () => {
          gradWrap.querySelectorAll('.gradient-swatch').forEach(s => s.classList.remove('selected'));
          sw.classList.add('selected');
          draft.projects[i].gradient = sw.getAttribute('data-g');
        });
      });
    }

    // Tags
    initItemTags(`proj-tags-${i}`, `proj-tag-input-${i}`, `proj-tag-add-${i}`, draft.projects[i].tags || [], v => { draft.projects[i].tags = v; });
  }, 0);

  addItemFooter(wrap, () => { draft.projects.splice(i, 1); buildProjectsList(); });
  return wrap;
}

$('addProjectBtn')?.addEventListener('click', () => {
  draft.projects.push({ title:'New Project', desc:'Project description...', tags:[], category:'fullstack', gradient:'gradient-1', icon:'fas fa-code', liveUrl:'#', githubUrl:'#' });
  buildProjectsList();
});

/* ══════════════════════════════════════
   7. EXPERIENCE LIST
══════════════════════════════════════ */
function buildExperienceList() {
  const container = $('experienceList');
  container.innerHTML = '';
  (draft.experience || []).forEach((it, i) => container.appendChild(makeExperienceCard(i, it)));
}

function makeExperienceCard(i, item) {
  const wrap = document.createElement('div');
  wrap.innerHTML = `
    <div class="item-form-grid">
      <div><label>Job/Degree Title</label><input type="text" id="exp-title-${i}" value="${escH(item.title)}" /></div>
      <div><label>Company / Institution</label><input type="text" id="exp-company-${i}" value="${escH(item.company)}" /></div>
      <div><label>Period (e.g. 2020 – 2022)</label><input type="text" id="exp-period-${i}" value="${escH(item.period)}" /></div>
      <div><label>Location</label><input type="text" id="exp-location-${i}" value="${escH(item.location)}" /></div>
      <div><label>Icon Class</label><input type="text" id="exp-icon-${i}" value="${escH(item.iconClass)}" placeholder="fas fa-briefcase" /></div>
      <div class="full-width"><label>Description</label><textarea id="exp-desc-${i}" rows="3">${escH(item.desc)}</textarea></div>
    </div>
    <div class="item-tags-section">
      <label style="font-size:0.75rem;color:var(--text-muted);font-weight:600">Tech Tags</label>
      <div class="item-tag-list" id="exp-tags-${i}"></div>
      <div class="item-tag-add">
        <input type="text" id="exp-tag-input-${i}" placeholder="Add tag…" />
        <button id="exp-tag-add-${i}"><i class="fas fa-plus"></i></button>
      </div>
    </div>`;

  setTimeout(() => {
    ['title','company','period','location','iconClass','desc'].forEach(key => {
      const el = $(`exp-${key === 'iconClass' ? 'icon' : key}-${i}`);
      const dk = key;
      if (el) el.addEventListener('input', e => {
        draft.experience[i][dk] = e.target.value;
        const titleEl = $(`exp-title-${i}`); if (titleEl) updateCardTitle(wrap, titleEl.value);
      });
    });
    initItemTags(`exp-tags-${i}`, `exp-tag-input-${i}`, `exp-tag-add-${i}`, draft.experience[i].tags || [], v => { draft.experience[i].tags = v; });
  }, 0);

  const card = buildCollapsibleCard(item.title, item.company, wrap);
  addItemFooter(wrap, () => { draft.experience.splice(i, 1); buildExperienceList(); });
  return card;
}

$('addExperienceBtn')?.addEventListener('click', () => {
  draft.experience.push({ title:'New Role', company:'Company Name', period:'2024 – Present', location:'Remote', desc:'Describe your role...', tags:[], iconClass:'fas fa-briefcase' });
  buildExperienceList();
});

/* ══════════════════════════════════════
   8. ACHIEVEMENTS / EDUCATION / LANGUAGES
══════════════════════════════════════ */
function buildAchievementsList() {
  const container = $('achievementsList');
  if (!container) return;
  container.innerHTML = '';
  (draft.achievements || []).forEach((item, i) => container.appendChild(makeAchievementCard(i, item)));
}

function makeAchievementCard(i, item) {
  const wrap = document.createElement('div');
  wrap.innerHTML = `
    <div class="item-form-grid">
      <div><label>Title</label><input type="text" id="achievement-title-${i}" value="${escH(item.title)}" /></div>
      <div><label>Subtitle</label><input type="text" id="achievement-subtitle-${i}" value="${escH(item.subtitle)}" /></div>
      <div><label>Period / Year</label><input type="text" id="achievement-period-${i}" value="${escH(item.period)}" /></div>
      <div><label>Highlight</label><input type="text" id="achievement-highlight-${i}" value="${escH(item.highlight)}" /></div>
      <div class="full-width"><label>Description</label><textarea id="achievement-description-${i}" rows="3">${escH(item.description)}</textarea></div>
    </div>`;

  setTimeout(() => {
    [['title', 'title'], ['subtitle', 'subtitle'], ['period', 'period'], ['highlight', 'highlight'], ['description', 'description']]
      .forEach(([suffix, key]) => {
        const el = $(`achievement-${suffix}-${i}`);
        if (el) el.addEventListener('input', (e) => {
          draft.achievements[i][key] = e.target.value;
          const titleEl = $(`achievement-title-${i}`);
          if (titleEl) updateCardTitle(wrap, titleEl.value);
        });
      });
  }, 0);

  const card = buildCollapsibleCard(item.title, item.highlight || item.subtitle, wrap);
  addItemFooter(wrap, () => { draft.achievements.splice(i, 1); buildAchievementsList(); });
  return card;
}

$('addAchievementBtn')?.addEventListener('click', () => {
  if (!Array.isArray(draft.achievements)) draft.achievements = [];
  draft.achievements.push({ title:'New Achievement', subtitle:'Course or honor', period:'', highlight:'', description:'Describe this achievement...' });
  buildAchievementsList();
});

function buildEducationList() {
  const container = $('educationList');
  if (!container) return;
  container.innerHTML = '';
  (draft.education || []).forEach((item, i) => container.appendChild(makeEducationCard(i, item)));
}

function makeEducationCard(i, item) {
  const wrap = document.createElement('div');
  wrap.innerHTML = `
    <div class="item-form-grid">
      <div><label>Title</label><input type="text" id="education-title-${i}" value="${escH(item.title)}" /></div>
      <div><label>Subtitle</label><input type="text" id="education-subtitle-${i}" value="${escH(item.subtitle)}" /></div>
      <div><label>Status</label><input type="text" id="education-status-${i}" value="${escH(item.status)}" /></div>
      <div class="full-width"><label>Description</label><textarea id="education-description-${i}" rows="3">${escH(item.description)}</textarea></div>
    </div>`;

  setTimeout(() => {
    [['title', 'title'], ['subtitle', 'subtitle'], ['status', 'status'], ['description', 'description']]
      .forEach(([suffix, key]) => {
        const el = $(`education-${suffix}-${i}`);
        if (el) el.addEventListener('input', (e) => {
          draft.education[i][key] = e.target.value;
          const titleEl = $(`education-title-${i}`);
          if (titleEl) updateCardTitle(wrap, titleEl.value);
        });
      });
  }, 0);

  const card = buildCollapsibleCard(item.title, item.status || item.subtitle, wrap);
  addItemFooter(wrap, () => { draft.education.splice(i, 1); buildEducationList(); });
  return card;
}

$('addEducationBtn')?.addEventListener('click', () => {
  if (!Array.isArray(draft.education)) draft.education = [];
  draft.education.push({ title:'New Credential', subtitle:'Education or Certification', status:'In Progress', description:'Add details here...' });
  buildEducationList();
});

function buildLanguagesList() {
  const container = $('languagesList');
  if (!container) return;
  container.innerHTML = '';
  (draft.languages || []).forEach((item, i) => container.appendChild(makeLanguageCard(i, item)));
}

function makeLanguageCard(i, item) {
  const wrap = document.createElement('div');
  wrap.innerHTML = `
    <div class="item-form-grid">
      <div><label>Language</label><input type="text" id="language-name-${i}" value="${escH(item.name)}" /></div>
      <div><label>Proficiency</label><input type="text" id="language-proficiency-${i}" value="${escH(item.proficiency)}" /></div>
    </div>`;

  setTimeout(() => {
    [['name', 'name'], ['proficiency', 'proficiency']].forEach(([suffix, key]) => {
      const el = $(`language-${suffix}-${i}`);
      if (el) el.addEventListener('input', (e) => {
        draft.languages[i][key] = e.target.value;
        const nameEl = $(`language-name-${i}`);
        if (nameEl) updateCardTitle(wrap, nameEl.value);
      });
    });
  }, 0);

  const card = buildCollapsibleCard(item.name, item.proficiency, wrap);
  addItemFooter(wrap, () => { draft.languages.splice(i, 1); buildLanguagesList(); });
  return card;
}

$('addLanguageBtn')?.addEventListener('click', () => {
  if (!Array.isArray(draft.languages)) draft.languages = [];
  draft.languages.push({ name:'New Language', proficiency:'Intermediate' });
  buildLanguagesList();
});

/* ══════════════════════════════════════
   9. SHARED HELPERS FOR LIST ITEMS
══════════════════════════════════════ */
function buildCollapsibleCard(title, subtitle, bodyEl) {
  const card = document.createElement('div');
  card.className = 'list-item-card';

  const header = document.createElement('div');
  header.className = 'list-item-header';
  header.innerHTML = `
    <i class="fas fa-grip-vertical item-drag-handle"></i>
    <div style="flex:1">
      <div class="item-title-preview">${escH(title)}</div>
      <div class="item-subtitle-preview">${escH(subtitle)}</div>
    </div>
    <i class="fas fa-chevron-down item-toggle-icon"></i>`;

  const body = document.createElement('div');
  body.className = 'list-item-body';
  body.appendChild(bodyEl);

  header.addEventListener('click', () => card.classList.toggle('expanded'));
  card.appendChild(header);
  card.appendChild(body);
  return card;
}

function updateCardTitle(wrap, newTitle) {
  const card = wrap.closest?.('.list-item-card');
  if (card) {
    const titleEl = card.querySelector('.item-title-preview');
    if (titleEl) titleEl.textContent = newTitle;
  }
}

function addItemFooter(wrap, onRemove) {
  const footer = document.createElement('div');
  footer.className = 'item-footer';
  footer.innerHTML = `<button class="btn-remove-item"><i class="fas fa-trash"></i> Remove</button>`;
  footer.querySelector('.btn-remove-item').addEventListener('click', onRemove);
  wrap.appendChild(footer);
}

function initItemTags(listId, inputId, addBtnId, tags, onChange) {
  const listEl = $(listId);
  const inputEl = $(inputId);
  const addBtn = $(addBtnId);
  if (!listEl) return;
  let items = [...tags];

  const render = () => {
    listEl.innerHTML = '';
    items.forEach((tag, i) => {
      const chip = document.createElement('span');
      chip.className = 'tag-chip';
      chip.innerHTML = `${escH(tag)} <button aria-label="Remove"><i class="fas fa-times"></i></button>`;
      chip.querySelector('button').addEventListener('click', () => { items.splice(i, 1); onChange([...items]); render(); });
      listEl.appendChild(chip);
    });
  };

  const addTag = () => {
    const val = inputEl.value.trim();
    if (!val || items.includes(val)) return;
    items.push(val); inputEl.value = ''; onChange([...items]); render();
  };

  if (addBtn) addBtn.addEventListener('click', addTag);
  if (inputEl) inputEl.addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); addTag(); } });
  render();
}

/* ══════════════════════════════════════
   10. COLLECT FORM DATA → DRAFT
══════════════════════════════════════ */
function collectDraft() {
  // META — ensure meta object exists before writing
  if (!draft.meta) draft.meta = {};
  draft.meta.name      = val('metaName');
  draft.meta.role      = val('metaRole');
  draft.meta.brandText = val('metaBrandText') || draft.meta.brandText || 'MAS';
  draft.meta.siteTitle = val('metaSiteTitle');
  draft.meta.siteDesc  = val('metaSiteDesc');
  draft.meta.siteKeywords = val('metaSiteKeywords');
  if (!draft.footer) draft.footer = {};
  draft.footer.tagline = val('footerTagline');

  // HERO
  if (!draft.hero) draft.hero = {};
  draft.hero.availableTag  = val('heroTag');
  draft.hero.firstName     = val('heroFirstName');
  draft.hero.highlightName = val('heroHighlightName');
  draft.hero.description   = val('heroDescription');
  const orbitSpeedEl = $('heroOrbitSpeed');
  if (orbitSpeedEl) draft.hero.orbitSpeed = parseInt(orbitSpeedEl.value) || 12;
  // typedWords already live-synced
  // stats
  const stats = draft.hero.stats || [];
  stats.forEach((s, i) => {
    s.number = parseInt(val(`statNum${i}`)) || s.number;
    s.label  = val(`statLbl${i}`) || s.label;
  });

  // ABOUT
  if (!draft.about) draft.about = {};
  draft.about.heading          = val('aboutHeading');
  draft.about.headingHighlight = val('aboutHeadingHighlight');
  draft.about.text1            = val('aboutText1');
  draft.about.text2            = val('aboutText2');
  draft.about.name             = val('aboutName');
  draft.about.email            = val('aboutEmail');
  draft.about.location         = val('aboutLocation');
  draft.about.degree           = val('aboutDegree');
  draft.about.expYears         = val('aboutExpYears');

  // SKILLS — already live-synced via input events

  // CONTACT
  if (!draft.contact) draft.contact = {};
  if (!draft.contact.social) draft.contact.social = {};
  draft.contact.heading  = val('contactHeading');
  draft.contact.subtext  = val('contactSubtext');
  draft.contact.email    = val('contactEmail');
  draft.contact.phone    = val('contactPhone');
  draft.contact.location = val('contactLocation');
  draft.contact.portfolioUrl = val('contactPortfolioUrl');
  draft.contact.resumeUrl = val('contactResumeUrl');
  draft.contact.social.github    = val('socialGithub');
  draft.contact.social.linkedin  = val('socialLinkedin');
  draft.contact.social.twitter   = val('socialTwitter');
  draft.contact.social.dribbble  = val('socialDribbble');
  draft.contact.social.instagram = val('socialInstagram');

  // WHATSAPP API CONFIG
  if (!draft.contact.whatsappApi) draft.contact.whatsappApi = {};
  const waEl = $('waEnabled');
  draft.contact.whatsappApi.enabled      = waEl ? waEl.checked : false;
  draft.contact.whatsappApi.apiKey       = val('waApiKey');
  draft.contact.whatsappApi.accountName  = val('waAccountName');
  draft.contact.whatsappApi.targetNumber = val('waTargetNumber');
  // images are live-synced via setupImageUpload, no need to read from DOM here
}

function val(id) { const el = $(id); return el ? el.value : ''; }

/* ══════════════════════════════════════
   11. SAVE
══════════════════════════════════════ */
async function saveAll(successMessage = 'All changes saved! Open your portfolio to see them live.') {
  collectDraft();
  const saveBtn = $('saveBtn');

  if (saveBtn) saveBtn.disabled = true;

  try {
    const payload = await requestJson('/admin/portfolio-data', {
      method: 'POST',
      data: { data: draft }
    });

    replaceServerState(payload.data || {});
    draft = PortfolioData.get();
    toast(successMessage, 'success');
    return true;
  } catch (error) {
    if (error.status === 401) {
      showLogin('Your session expired. Please log in again.');
    }
    toast(error.message || 'Failed to save your changes.', 'error');
    return false;
  } finally {
    if (saveBtn) saveBtn.disabled = false;
  }
}

/* ══════════════════════════════════════
   11b. INBOX — Load messages from server
══════════════════════════════════════ */
function loadInbox() {
  const container = $('inboxContainer');
  const badge     = $('inboxBadge');
  if (!container) return;

  container.innerHTML = '<p class="inbox-loading"><i class="fas fa-spinner fa-spin"></i> Loading messages…</p>';

  fetch('api_messages.php?action=get')
    .then(r => r.json())
    .then(messages => {
      if (!messages || !messages.length) {
        container.innerHTML = '<p class="inbox-empty"><i class="fas fa-inbox"></i><br>No messages yet. When visitors submit the contact form, they appear here.</p>';
        if (badge) badge.style.display = 'none';
        return;
      }

      // Update badge
      if (badge) { badge.textContent = messages.length; badge.style.display = 'inline-block'; }

      container.innerHTML = '';
      // newest first
      [...messages].reverse().forEach((msg, idx) => {
        const card = document.createElement('div');
        card.className = 'inbox-card';
        card.innerHTML = `
          <div class="inbox-card-header">
            <div class="inbox-meta">
              <span class="inbox-name"><i class="fas fa-user"></i> ${escH(msg.name)}</span>
              <span class="inbox-email"><i class="fas fa-envelope"></i> ${escH(msg.email)}</span>
            </div>
            <div class="inbox-time"><i class="fas fa-clock"></i> ${escH(msg.timestamp || '')}</div>
          </div>
          ${msg.subject ? `<div class="inbox-subject"><strong>Subject:</strong> ${escH(msg.subject)}</div>` : ''}
          <div class="inbox-message">${escH(msg.message)}</div>
          <div class="inbox-actions">
            <a href="mailto:${escH(msg.email)}" class="btn-reply"><i class="fas fa-reply"></i> Reply via Email</a>
            <button class="btn-delete-msg" data-idx="${messages.length - 1 - idx}"><i class="fas fa-trash"></i></button>
          </div>`;
        container.appendChild(card);
      });

      // Delete message
      container.querySelectorAll('.btn-delete-msg').forEach(btn => {
        btn.addEventListener('click', () => {
          const i = parseInt(btn.getAttribute('data-idx'));
          fetch('api_messages.php?action=delete&index=' + i, { method: 'POST' })
            .then(() => loadInbox());
        });
      });
    })
    .catch(() => {
      container.innerHTML = '<p class="inbox-empty"><i class="fas fa-server"></i><br>Could not connect to the server. Make sure <code>api_messages.php</code> is deployed on your live hosting.</p>';
    });
}

function escHInline(str) {
  if (!str) return '';
  return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

/* ══════════════════════════════════════
   12. SETTINGS — CHANGE PASSWORD
══════════════════════════════════════ */
function initSettings() {
  // Live-sync orbit speed slider label
  const orbitSlider = $('heroOrbitSpeed');
  const orbitVal    = $('heroOrbitSpeedVal');
  if (orbitSlider) {
    orbitSlider.addEventListener('input', () => {
      const v = parseInt(orbitSlider.value);
      if (orbitVal) orbitVal.textContent = v + 's';
      if (!draft.hero) draft.hero = {};
      draft.hero.orbitSpeed = v;
    });
  }

  // Live-sync brand text directly into draft so it's always captured at save time
  const brandInput = $('metaBrandText');
  if (brandInput) {
    brandInput.addEventListener('input', () => {
      if (!draft.meta) draft.meta = {};
      draft.meta.brandText = brandInput.value.trim();
    });
  }

  $('changePwdBtn').addEventListener('click', () => {
    const cur  = $('currentPwd').value;
    const nw   = $('newPwd').value;
    const conf = $('confirmPwd').value;
    if (cur !== getAdminPwd()) { toast('Current password is incorrect.', 'error'); return; }
    if (!nw || nw.length < 6) { toast('New password must be at least 6 characters.', 'warning'); return; }
    if (nw !== conf) { toast('Passwords do not match.', 'error'); return; }
    localStorage.setItem(ADMIN_PWD_KEY, nw);
    $('currentPwd').value = $('newPwd').value = $('confirmPwd').value = '';
    toast('Password updated successfully!', 'success');
  });

  $('hardResetBtn').addEventListener('click', () => {
    if (!confirm('⚠️ Reset ALL portfolio content to factory defaults?\n\nThis cannot be undone.')) return;
    draft = PortfolioData.reset();
    populateForms();
    PortfolioData.save(draft);
    toast('Portfolio reset to factory defaults.', 'warning');
  });

  $('resetDataBtn').addEventListener('click', () => {
    if (!confirm('Discard all unsaved changes and reload defaults from saved data?')) return;
    draft = PortfolioData.get();
    populateForms();
    toast('Reloaded from saved data.', 'warning');
  });
}

/* ══════════════════════════════════════
   IMAGE UPLOAD SYSTEM
══════════════════════════════════════ */
function initImageUploads() {
  setupImageUpload('heroImageInput',  'heroImgPreviewEl',  'heroUploadStatus',  'hero');
  setupImageUpload('aboutImageInput', 'aboutImgPreviewEl', 'aboutUploadStatus', 'about');

  // "Use Hero Photo" button copies the hero image into the about slot
  const syncBtn = $('aboutSyncBtn');
  if (syncBtn) {
    syncBtn.addEventListener('click', () => {
      if (!draft.images) draft.images = {};
      const heroSrc = draft.images.hero || 'profile.png';
      draft.images.about = heroSrc;
      const ai = $('aboutImgPreviewEl');
      if (ai) ai.src = heroSrc;
      setUploadStatus('aboutUploadStatus', 'Synced from Hero photo. Click Save Changes to persist.', 'ok');
    });
  }
}

function setupImageUpload(inputId, previewId, statusId, imageKey) {
  const input   = $(inputId);
  const preview = $(previewId);
  if (!input || !preview) return;

  if (!draft.images) draft.images = { hero: 'profile.png', about: 'profile.png' };
  if (draft.images[imageKey]) preview.src = draft.images[imageKey];

  // Drag-and-drop on the zone container
  const zone = preview.closest?.('.image-upload-zone');
  if (zone) {
    zone.addEventListener('dragover',  e => { e.preventDefault(); zone.classList.add('drag-over'); });
    zone.addEventListener('dragleave', ()  => zone.classList.remove('drag-over'));
    zone.addEventListener('drop', e => {
      e.preventDefault();
      zone.classList.remove('drag-over');
      const file = e.dataTransfer?.files[0];
      if (file) processImageFile(file, preview, statusId, imageKey);
    });
  }

  input.addEventListener('change', e => {
    const file = e.target.files[0];
    if (file) processImageFile(file, preview, statusId, imageKey);
    input.value = '';
  });
}

async function processImageFile(file, preview, statusId, imageKey) {
  const validTypes = ['image/jpeg','image/png','image/webp','image/gif'];
  if (!validTypes.includes(file.type)) {
    setUploadStatus(statusId, '⚠️ Invalid type. Use JPG, PNG, WEBP or GIF.', 'err'); return;
  }
  if (file.size > 8 * 1024 * 1024) {
    setUploadStatus(statusId, '⚠️ File too large. Max 8 MB.', 'err'); return;
  }

  setUploadStatus(statusId, 'Uploading…', 'info');
  const dataUrl = await readFileAsDataURL(file);
  preview.src = dataUrl; // immediate local preview

  // Try PHP upload first
  const phpOk = await tryPhpUpload(file, imageKey, statusId);
  if (phpOk) return;

  // Fallback: compress & store as base64 in localStorage
  setUploadStatus(statusId, 'Compressing & saving locally…', 'info');
  const compressed = await compressImageToDataUrl(dataUrl, 900, 0.82);
  if (!draft.images) draft.images = {};
  draft.images[imageKey] = compressed;
  setUploadStatus(statusId, '✅ Saved (base64). Click Save Changes to persist.', 'ok');
}

async function tryPhpUpload(file, imageKey, statusId) {
  try {
    const fd = new FormData();
    fd.append('image', file);
    fd.append('imageKey', imageKey);
    const old = draft.images?.[imageKey] || '';
    if (old.startsWith('uploads/')) fd.append('replaces', old.replace('uploads/', ''));

    const res = await fetch('upload.php', { method: 'POST', body: fd });
    const ct  = res.headers.get('content-type') || '';
    if (!res.ok || !ct.includes('json')) return false;

    const json = await res.json();
    if (json.success && json.url) {
      if (!draft.images) draft.images = {};
      draft.images[imageKey] = json.url;
      setUploadStatus(statusId, '✅ Uploaded to server. Click Save Changes to persist.', 'ok');
      return true;
    }
    setUploadStatus(statusId, '⚠️ Server: ' + (json.error || 'unknown error'), 'err');
    return false;
  } catch { return false; }
}

function readFileAsDataURL(file) {
  return new Promise(resolve => {
    const r = new FileReader();
    r.onload = e => resolve(e.target.result);
    r.readAsDataURL(file);
  });
}

function compressImageToDataUrl(dataUrl, maxPx, quality) {
  return new Promise(resolve => {
    const img = new Image();
    img.onload = () => {
      const scale  = Math.min(1, maxPx / Math.max(img.width, img.height));
      const canvas = document.createElement('canvas');
      canvas.width  = Math.round(img.width  * scale);
      canvas.height = Math.round(img.height * scale);
      canvas.getContext('2d').drawImage(img, 0, 0, canvas.width, canvas.height);
      resolve(canvas.toDataURL('image/jpeg', quality));
    };
    img.src = dataUrl;
  });
}

function setUploadStatus(statusId, msg, type) {
  const el = $(statusId);
  if (!el) return;
  el.textContent = msg;
  el.style.color = type === 'err'  ? 'var(--danger)'
                 : type === 'ok'   ? 'var(--success)'
                 : 'var(--text-muted)';
}

/* ══════════════════════════════════════
   13. SIDEBAR TOGGLE (mobile)
══════════════════════════════════════ */
function initSidebarToggle() {
  $('sidebarToggle').addEventListener('click', () => {
    const sb = $('sidebar');
    const mw = document.querySelector('.main-wrap');
    if (window.innerWidth < 900) {
      sb.classList.toggle('mobile-open');
    } else {
      sb.classList.toggle('hidden');
      mw.classList.toggle('full');
    }
  });
}

/* ══════════════════════════════════════
   14. MAIN INIT
══════════════════════════════════════ */
function initDashboard() {
  // Load data into draft
  draft = PortfolioData.get();
  if (!draft.images) draft.images = { hero: 'profile.png', about: 'profile.png' };

  // Set branded favicon for admin tab
  setAdminFavicon(draft.meta);

  // Populate forms
  populateForms();

  // Image uploads
  initImageUploads();

  // Skills tabs
  initSkillsTab();

  // Settings
  initSettings();

  // Sidebar toggle
  initSidebarToggle();

  // Panel navigation — sidebar
  document.querySelectorAll('.nav-item[data-panel]').forEach(btn => {
    btn.addEventListener('click', () => switchPanel(btn.getAttribute('data-panel')));
  });

  // Overview cards → jump to panel
  document.querySelectorAll('.overview-card[data-goto]').forEach(card => {
    card.addEventListener('click', () => switchPanel(card.getAttribute('data-goto')));
  });

  // Save button
  $('saveBtn').addEventListener('click', saveAll);

  // Keyboard shortcut: Ctrl+S
  document.addEventListener('keydown', e => {
    if ((e.ctrlKey || e.metaKey) && e.key === 's') { e.preventDefault(); saveAll(); }
  });
}

/* ══════════════════════════════════════
   UTILITY
══════════════════════════════════════ */
function escH(str) {
  if (str === undefined || str === null) return '';
  return String(str)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');
}

function capitalize(s) { return s ? s[0].toUpperCase() + s.slice(1) : s; }

/** Generates a canvas-based favicon from brand text and sets it as the page icon */
function setAdminFavicon(meta) {
  try {
    const brandText = meta?.brandText
      ? meta.brandText.trim()
      : (meta?.name
          ? meta.name.trim().split(/\s+/).map(w => w[0]).slice(0, 3).join('').toUpperCase()
          : 'AM');

    const size = 64;
    const canvas = document.createElement('canvas');
    canvas.width = canvas.height = size;
    const ctx = canvas.getContext('2d');

    // Gradient background
    const grad = ctx.createLinearGradient(0, 0, size, size);
    grad.addColorStop(0, 'hsl(250,84%,65%)');
    grad.addColorStop(1, 'hsl(195,95%,55%)');
    ctx.fillStyle = grad;
    ctx.beginPath();
    ctx.roundRect(0, 0, size, size, 14);
    ctx.fill();

    // Brand text
    const fontSize = brandText.length > 2 ? 20 : 26;
    ctx.fillStyle = '#ffffff';
    ctx.font = `700 ${fontSize}px "Outfit", Arial, sans-serif`;
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    ctx.fillText(brandText, size / 2, size / 2);

    // Apply to <link rel="icon">
    let link = document.querySelector('link[rel~="icon"]');
    if (!link) {
      link = document.createElement('link');
      link.rel = 'icon';
      document.head.appendChild(link);
    }
    link.type = 'image/png';
    link.href = canvas.toDataURL('image/png');
  } catch (e) {
    // Non-critical — silently ignore
  }
}

/* ── BOOT ── */
loadInbox = async function () {
  const container = $('inboxContainer');
  const badge = $('inboxBadge');
  if (!container) return;

  container.innerHTML = '<p class="inbox-loading"><i class="fas fa-spinner fa-spin"></i> Loading messages...</p>';

  try {
    const messages = await requestJson('api_messages.php?action=get');

    if (!messages || !messages.length) {
      container.innerHTML = '<p class="inbox-empty"><i class="fas fa-inbox"></i><br>No messages yet. When visitors submit the contact form, they appear here.</p>';
      if (badge) badge.style.display = 'none';
      return;
    }

    if (badge) {
      badge.textContent = messages.length;
      badge.style.display = 'inline-block';
    }

    container.innerHTML = '';
    [...messages].reverse().forEach(msg => {
      const card = document.createElement('div');
      card.className = 'inbox-card';
      card.innerHTML = `
        <div class="inbox-card-header">
          <div class="inbox-meta">
            <span class="inbox-name"><i class="fas fa-user"></i> ${escH(msg.name)}</span>
            <span class="inbox-email"><i class="fas fa-envelope"></i> ${escH(msg.email)}</span>
          </div>
          <div class="inbox-time"><i class="fas fa-clock"></i> ${escH(msg.timestamp || '')}</div>
        </div>
        ${msg.subject ? `<div class="inbox-subject"><strong>Subject:</strong> ${escH(msg.subject)}</div>` : ''}
        <div class="inbox-message">${escH(msg.message)}</div>
        <div class="inbox-actions">
          <a href="mailto:${escH(msg.email)}" class="btn-reply"><i class="fas fa-reply"></i> Reply via Email</a>
          <button class="btn-delete-msg" data-id="${msg.id || ''}" data-idx="${msg.index || ''}"><i class="fas fa-trash"></i></button>
        </div>`;
      container.appendChild(card);
    });

    container.querySelectorAll('.btn-delete-msg').forEach(btn => {
      btn.addEventListener('click', async () => {
        const id = btn.getAttribute('data-id');
        const index = btn.getAttribute('data-idx');
        const qs = id ? `id=${encodeURIComponent(id)}` : `index=${encodeURIComponent(index)}`;

        try {
          await requestJson(`api_messages.php?action=delete&${qs}`, { method: 'POST' });
          await loadInbox();
        } catch (error) {
          toast(error.message || 'Could not delete the message.', 'error');
        }
      });
    });
  } catch (error) {
    if (error.status === 401) {
      container.innerHTML = '<p class="inbox-empty"><i class="fas fa-lock"></i><br>Please log in again to view inbox messages.</p>';
      if (badge) badge.style.display = 'none';
      return;
    }

    container.innerHTML = '<p class="inbox-empty"><i class="fas fa-server"></i><br>Could not connect to the server. Make sure Laravel and MySQL are running.</p>';
  }
};

initSettings = function () {
  const orbitSlider = $('heroOrbitSpeed');
  const orbitVal = $('heroOrbitSpeedVal');
  if (orbitSlider) {
    orbitSlider.addEventListener('input', () => {
      const v = parseInt(orbitSlider.value, 10);
      if (orbitVal) orbitVal.textContent = v + 's';
      if (!draft.hero) draft.hero = {};
      draft.hero.orbitSpeed = v;
    });
  }

  const brandInput = $('metaBrandText');
  if (brandInput) {
    brandInput.addEventListener('input', () => {
      if (!draft.meta) draft.meta = {};
      draft.meta.brandText = brandInput.value.trim();
    });
  }

  $('changePwdBtn').addEventListener('click', async () => {
    const cur = $('currentPwd').value;
    const nw = $('newPwd').value;
    const conf = $('confirmPwd').value;

    try {
      await requestJson('/admin/password', {
        method: 'POST',
        data: {
          current_password: cur,
          new_password: nw,
          new_password_confirmation: conf
        }
      });
      $('currentPwd').value = '';
      $('newPwd').value = '';
      $('confirmPwd').value = '';
      toast('Password updated successfully!', 'success');
    } catch (error) {
      toast(error.message || 'Could not update the password.', 'error');
    }
  });

  $('hardResetBtn').addEventListener('click', async () => {
    if (!confirm('Reset ALL portfolio content to factory defaults?\n\nThis cannot be undone.')) return;
    draft = PortfolioData.defaults();
    populateForms();
    await saveAll('Portfolio reset to factory defaults.');
  });

  $('resetDataBtn').addEventListener('click', () => {
    if (!confirm('Discard all unsaved changes and reload the last saved data?')) return;
    draft = PortfolioData.get();
    populateForms();
    toast('Reloaded from saved data.', 'warning');
  });
};

processImageFile = async function (file, preview, statusId, imageKey) {
  const validTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
  if (!validTypes.includes(file.type)) {
    setUploadStatus(statusId, 'Invalid type. Use JPG, PNG, WEBP or GIF.', 'err');
    return;
  }
  if (file.size > 8 * 1024 * 1024) {
    setUploadStatus(statusId, 'File too large. Max 8 MB.', 'err');
    return;
  }

  const previousSrc = draft.images?.[imageKey] || preview.src;
  setUploadStatus(statusId, 'Uploading...', 'info');
  preview.src = await readFileAsDataURL(file);

  const phpOk = await tryPhpUpload(file, imageKey, statusId);
  if (phpOk) return;

  preview.src = previousSrc;
  setUploadStatus(statusId, 'Upload failed. Please make sure you are logged in and the server is running.', 'err');
};

tryPhpUpload = async function (file, imageKey, statusId) {
  try {
    const fd = new FormData();
    fd.append('image', file);
    fd.append('imageKey', imageKey);
    const old = draft.images?.[imageKey] || '';
    if (old.startsWith('uploads/')) fd.append('replaces', old.replace('uploads/', ''));

    const headers = csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {};
    const res = await fetch('upload.php', { method: 'POST', body: fd, headers });
    const ct = res.headers.get('content-type') || '';

    if (res.status === 401) {
      setUploadStatus(statusId, 'Your session expired. Please log in again.', 'err');
      return false;
    }

    if (!res.ok || !ct.includes('json')) return false;

    const json = await res.json();
    if (json.success && json.url) {
      if (!draft.images) draft.images = {};
      draft.images[imageKey] = json.url;
      setUploadStatus(statusId, 'Uploaded to server. Click Save Changes to persist.', 'ok');
      return true;
    }

    setUploadStatus(statusId, 'Server: ' + (json.error || 'unknown error'), 'err');
    return false;
  } catch (_) {
    return false;
  }
};

initDashboard = function () {
  draft = PortfolioData.get();
  if (!draft.images) draft.images = { hero: 'profile.png', about: 'profile.png' };

  setAdminFavicon(draft.meta);
  populateForms();
  initImageUploads();
  initSkillsTab();
  initSettings();
  initSidebarToggle();

  document.querySelectorAll('.nav-item[data-panel]').forEach(btn => {
    btn.addEventListener('click', () => switchPanel(btn.getAttribute('data-panel')));
  });

  document.querySelectorAll('.overview-card[data-goto]').forEach(card => {
    card.addEventListener('click', () => switchPanel(card.getAttribute('data-goto')));
  });

  $('saveBtn').addEventListener('click', () => { void saveAll(); });

  document.addEventListener('keydown', e => {
    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
      e.preventDefault();
      void saveAll();
    }
  });
};

document.addEventListener('DOMContentLoaded', initAuth);
