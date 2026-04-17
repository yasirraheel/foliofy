/* ════════════════════════════════════════════════════════
   ADMIN.JS — Dashboard Logic, CRUD, Save to localStorage
   ════════════════════════════════════════════════════════ */

'use strict';

/* ══════════════════════════════════════
   0. HELPERS
══════════════════════════════════════ */
const ADMIN_PWD_KEY  = 'am_admin_pwd';
const ADMIN_AUTH_KEY = 'am_admin_auth';
const DEFAULT_PWD    = 'admin@2024';

const $ = id => document.getElementById(id);
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

const getAdminPwd = () => localStorage.getItem(ADMIN_PWD_KEY) || DEFAULT_PWD;

/* ══════════════════════════════════════
   1. AUTH
══════════════════════════════════════ */
function initAuth() {
  // Toggle password visibility
  $('toggleLoginPwd').addEventListener('click', () => {
    const inp  = $('loginPasswordInput');
    const icon = $('loginEyeIcon');
    if (inp.type === 'password') { inp.type = 'text'; icon.className = 'fas fa-eye-slash'; }
    else                         { inp.type = 'password'; icon.className = 'fas fa-eye'; }
  });

  // Login
  const doLogin = () => {
    const val = $('loginPasswordInput').value;
    if (!val)          { $('loginError').textContent = 'Please enter a password.'; return; }
    if (val !== getAdminPwd()) { $('loginError').textContent = 'Incorrect password. Try again.'; return; }
    sessionStorage.setItem(ADMIN_AUTH_KEY, '1');
    $('loginScreen').style.display = 'none';
    $('dashboard').style.display = 'flex';
    initDashboard();
  };

  $('loginBtn').addEventListener('click', doLogin);
  $('loginPasswordInput').addEventListener('keydown', e => { if (e.key === 'Enter') doLogin(); });

  // Check if already authenticated (page refresh)
  if (sessionStorage.getItem(ADMIN_AUTH_KEY) === '1') {
    $('loginScreen').style.display = 'none';
    $('dashboard').style.display = 'flex';
    initDashboard();
  }

  // Logout
  $('logoutBtn').addEventListener('click', () => {
    sessionStorage.removeItem(ADMIN_AUTH_KEY);
    location.reload();
  });
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
    projects:'Projects', experience:'Experience', testimonials:'Testimonials',
    contact:'Contact', settings:'Settings'
  };
  $('breadcrumbSection').textContent = labels[name] || name;
  currentPanel = name;

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

  /* META / SETTINGS */
  setValue('metaName',      d.meta?.name);
  setValue('metaRole',      d.meta?.role);
  setValue('metaBrandText', d.meta?.brandText);
  setValue('metaSiteTitle', d.meta?.siteTitle);
  setValue('metaSiteDesc',  d.meta?.siteDesc);
  setValue('footerTagline', d.footer?.tagline);

  /* HERO */
  setValue('heroTag',           d.hero?.availableTag);
  setValue('heroFirstName',     d.hero?.firstName);
  setValue('heroHighlightName', d.hero?.highlightName);
  setValue('heroDescription',   d.hero?.description);
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
  buildSkillsList('frontend');
  buildSkillsList('backend');
  buildSkillsList('tools');

  /* PROJECTS */
  buildProjectsList();

  /* EXPERIENCE */
  buildExperienceList();

  /* TESTIMONIALS */
  buildTestimonialsList();

  /* CONTACT */
  setValue('contactHeading',  d.contact?.heading);
  setValue('contactSubtext',  d.contact?.subtext);
  setValue('contactEmail',    d.contact?.email);
  setValue('contactPhone',    d.contact?.phone);
  setValue('contactLocation', d.contact?.location);
  setValue('socialGithub',    d.contact?.social?.github);
  setValue('socialLinkedin',  d.contact?.social?.linkedin);
  setValue('socialTwitter',   d.contact?.social?.twitter);
  setValue('socialDribbble',  d.contact?.social?.dribbble);
  setValue('socialInstagram', d.contact?.social?.instagram);
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
let activeSkillTab = 'frontend';

function buildSkillsList(tab) {
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
    draft.skills[activeSkillTab].push({ name: 'New Skill', iconClass: 'fas fa-code', iconColor: '#7c3aed', level: 70 });
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
   8. TESTIMONIALS LIST
══════════════════════════════════════ */
function buildTestimonialsList() {
  const container = $('testimonialsList');
  container.innerHTML = '';
  (draft.testimonials || []).forEach((t, i) => container.appendChild(makeTestimonialCard(i, t)));
}

function makeTestimonialCard(i, t) {
  const wrap = document.createElement('div');
  wrap.innerHTML = `
    <div class="item-form-grid">
      <div class="full-width"><label>Quote Text</label><textarea id="testi-text-${i}" rows="4">${escH(t.text)}</textarea></div>
      <div><label>Author Name</label><input type="text" id="testi-name-${i}" value="${escH(t.authorName)}" /></div>
      <div><label>Author Role</label><input type="text" id="testi-role-${i}" value="${escH(t.authorRole)}" /></div>
      <div><label>Author Initials (2 letters)</label><input type="text" id="testi-init-${i}" value="${escH(t.initials)}" maxlength="2" /></div>
      <div><label>Avatar Gradient CSS <span style="color:var(--text-muted);">(optional)</span></label>
        <input type="text" id="testi-grad-${i}" value="${escH(t.avatarGradient)}" placeholder="linear-gradient(135deg,#f093fb,#f5576c)" /></div>
    </div>`;

  setTimeout(() => {
    const fields = [['text','text'],['name','authorName'],['role','authorRole'],['init','initials'],['grad','avatarGradient']];
    fields.forEach(([sfx, key]) => {
      const el = $(`testi-${sfx}-${i}`);
      if (el) el.addEventListener('input', e => {
        draft.testimonials[i][key] = e.target.value;
        const nameEl = $(`testi-name-${i}`); if (nameEl) updateCardTitle(wrap, nameEl.value);
      });
    });
  }, 0);

  const card = buildCollapsibleCard(t.authorName, t.authorRole, wrap);
  addItemFooter(wrap, () => { draft.testimonials.splice(i, 1); buildTestimonialsList(); });
  return card;
}

$('addTestimonialBtn')?.addEventListener('click', () => {
  draft.testimonials.push({ text:'Great experience working with this developer!', authorName:'Client Name', authorRole:'CEO, Company', initials:'CN', avatarGradient:'' });
  buildTestimonialsList();
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
  if (!draft.footer) draft.footer = {};
  draft.footer.tagline = val('footerTagline');

  // HERO
  draft.hero.availableTag  = val('heroTag');
  draft.hero.firstName     = val('heroFirstName');
  draft.hero.highlightName = val('heroHighlightName');
  draft.hero.description   = val('heroDescription');
  // typedWords already live-synced
  // stats
  const stats = draft.hero.stats || [];
  stats.forEach((s, i) => {
    s.number = parseInt(val(`statNum${i}`)) || s.number;
    s.label  = val(`statLbl${i}`) || s.label;
  });

  // ABOUT
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
  draft.contact.heading  = val('contactHeading');
  draft.contact.subtext  = val('contactSubtext');
  draft.contact.email    = val('contactEmail');
  draft.contact.phone    = val('contactPhone');
  draft.contact.location = val('contactLocation');
  draft.contact.social.github    = val('socialGithub');
  draft.contact.social.linkedin  = val('socialLinkedin');
  draft.contact.social.twitter   = val('socialTwitter');
  draft.contact.social.dribbble  = val('socialDribbble');
  draft.contact.social.instagram = val('socialInstagram');
}

function val(id) { const el = $(id); return el ? el.value : ''; }

/* ══════════════════════════════════════
   11. SAVE
══════════════════════════════════════ */
function saveAll() {
  collectDraft();
  const ok = PortfolioData.save(draft);
  if (ok) {
    toast('All changes saved! Open your portfolio to see them live.', 'success');
  } else {
    toast('Failed to save. Storage may be full.', 'error');
  }
}

/* ══════════════════════════════════════
   12. SETTINGS — CHANGE PASSWORD
══════════════════════════════════════ */
function initSettings() {
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

  // Populate forms
  populateForms();

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

/* ── BOOT ── */
document.addEventListener('DOMContentLoaded', initAuth);
