/* ══════════════════════════════════════════════════════
   RENDERER.JS — Reads PortfolioData → Updates DOM
   ══════════════════════════════════════════════════════ */

'use strict';

const PortfolioRenderer = {
  data: null,

  /** Bootstrap: load data and render everything */
  init() {
    this.data = PortfolioData.get();
    this.renderAll();
  },

  renderAll() {
    this.renderMeta();
    this.renderHero();
    this.renderAbout();
    this.renderSkills();
    this.renderProjects();
    this.renderExperience();
    this.renderTestimonials();
    this.renderContact();
    this.renderFooter();
  },

  /* ── META ── */
  renderMeta() {
    const { meta } = this.data;
    document.title = meta.siteTitle;
    const d = document.querySelector('meta[name="description"]');
    if (d) d.setAttribute('content', meta.siteDesc);
  },

  /* ── HERO ── */
  renderHero() {
    const { hero } = this.data;

    // Availability tag
    const tagEl = document.querySelector('.hero-tag');
    if (tagEl) tagEl.innerHTML = `<span class="status-dot pulse"></span>${hero.availableTag}`;

    // Title
    const titleEl = document.querySelector('.hero-title');
    if (titleEl) titleEl.innerHTML = `${hero.firstName} <span class="gradient-text">${hero.highlightName}</span>`;

    // Description
    const descEl = document.querySelector('.hero-desc');
    if (descEl) descEl.innerHTML = hero.description;

    // Stats
    const statItems = document.querySelectorAll('.stat-item');
    (hero.stats || []).forEach((stat, i) => {
      if (!statItems[i]) return;
      const num = statItems[i].querySelector('.stat-number');
      const lbl = statItems[i].querySelector('.stat-label');
      if (num) { num.textContent = '0'; num.setAttribute('data-target', stat.number); }
      if (lbl) lbl.textContent = stat.label;
    });

    // Pass typed words to script.js via window
    window._portfolioTypedWords = hero.typedWords || [];
  },

  /* ── ABOUT ── */
  renderAbout() {
    const { about } = this.data;

    const hEl = document.querySelector('.about-heading');
    if (hEl) hEl.innerHTML = `${about.heading} <span class="gradient-text">${about.headingHighlight}</span>`;

    const texts = document.querySelectorAll('.about-text');
    if (texts[0]) texts[0].innerHTML = about.text1;
    if (texts[1]) texts[1].innerHTML = about.text2;

    const infoVals = document.querySelectorAll('.info-value');
    [about.name, about.email, about.location, about.degree].forEach((v, i) => {
      if (infoVals[i]) infoVals[i].textContent = v;
    });

    const expNum = document.querySelector('.exp-number');
    if (expNum) expNum.textContent = about.expYears;

    // Code snippet
    const code = document.querySelector('.code-body code');
    if (code) code.innerHTML =
      `<span class="code-kw">const</span> <span class="code-var">developer</span> = {\n` +
      `  <span class="code-key">name</span>: <span class="code-str">"${about.name}"</span>,\n` +
      `  <span class="code-key">role</span>: <span class="code-str">"Full-Stack Dev"</span>,\n` +
      `  <span class="code-key">location</span>: <span class="code-str">"${about.location}"</span>,\n` +
      `  <span class="code-key">passion</span>: <span class="code-str">"Building Dreams"</span>,\n` +
      `  <span class="code-key">open</span>: <span class="code-bool">true</span>\n};`;
  },

  /* ── SKILLS ── */
  renderSkills() {
    const { skills } = this.data;
    const renderGroup = (arr, containerId) => {
      const wrap = document.getElementById(containerId);
      if (!wrap) return;
      const grid = wrap.querySelector('.skills-grid');
      if (!grid) return;
      grid.innerHTML = arr.map(s => `
        <div class="skill-card">
          <div class="skill-icon"><i class="${s.iconClass}" style="color:${s.iconColor}"></i></div>
          <span class="skill-name">${s.name}</span>
          <div class="skill-bar-wrap"><div class="skill-bar" data-width="${s.level}" style="width:0"></div></div>
          <span class="skill-percent">${s.level}%</span>
        </div>`).join('');
    };
    renderGroup(skills.frontend, 'tabContentFrontend');
    renderGroup(skills.backend,  'tabContentBackend');
    renderGroup(skills.tools,    'tabContentTools');
  },

  /* ── PROJECTS ── */
  renderProjects() {
    const { projects } = this.data;
    const grid = document.getElementById('projectsGrid');
    if (!grid) return;
    grid.innerHTML = projects.map((p, i) => `
      <div class="project-card" data-category="${p.category}" data-aos="fade-up" data-aos-delay="${(i % 3 + 1) * 100}">
        <div class="project-image">
          <div class="project-img-placeholder ${p.gradient}"><i class="${p.icon}"></i></div>
          <div class="project-overlay">
            <a href="${p.liveUrl || '#'}" class="project-link" aria-label="Live Demo"><i class="fas fa-external-link-alt"></i></a>
            <a href="${p.githubUrl || '#'}" class="project-link" aria-label="GitHub"><i class="fab fa-github"></i></a>
          </div>
        </div>
        <div class="project-body">
          <div class="project-tags">${(p.tags || []).map(t => `<span class="project-tag">${t}</span>`).join('')}</div>
          <h3 class="project-title">${p.title}</h3>
          <p class="project-desc">${p.desc}</p>
        </div>
      </div>`).join('');
  },

  /* ── EXPERIENCE ── */
  renderExperience() {
    const { experience } = this.data;
    const container = document.querySelector('.timeline-container');
    if (!container) return;
    container.innerHTML = experience.map((item, i) => `
      <div class="timeline-item" data-aos="${i % 2 === 0 ? 'fade-right' : 'fade-left'}" data-aos-delay="${(i + 1) * 100}">
        <div class="timeline-dot"><i class="${item.iconClass}"></i></div>
        <div class="timeline-card">
          <div class="timeline-header">
            <div>
              <h3 class="timeline-title">${item.title}</h3>
              <span class="timeline-company">${item.company}</span>
            </div>
            <div class="timeline-meta">
              <span class="timeline-period"><i class="fas fa-calendar-alt"></i> ${item.period}</span>
              <span class="timeline-location"><i class="fas fa-map-marker-alt"></i> ${item.location}</span>
            </div>
          </div>
          <p class="timeline-desc">${item.desc}</p>
          <div class="timeline-tags">${(item.tags || []).map(t => `<span>${t}</span>`).join('')}</div>
        </div>
      </div>`).join('');
  },

  /* ── TESTIMONIALS ── */
  renderTestimonials() {
    const { testimonials } = this.data;
    const wrapper = document.querySelector('.testimonials-swiper .swiper-wrapper');
    if (!wrapper) return;
    wrapper.innerHTML = testimonials.map(t => `
      <div class="swiper-slide">
        <div class="testimonial-card">
          <div class="testimonial-quote"><i class="fas fa-quote-left"></i></div>
          <p class="testimonial-text">${t.text}</p>
          <div class="testimonial-author">
            <div class="author-avatar" ${t.avatarGradient ? `style="background:${t.avatarGradient}"` : ''}><span>${t.initials}</span></div>
            <div><p class="author-name">${t.authorName}</p><p class="author-role">${t.authorRole}</p></div>
            <div class="testimonial-stars">
              <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
            </div>
          </div>
        </div>
      </div>`).join('');
  },

  /* ── CONTACT ── */
  renderContact() {
    const { contact } = this.data;

    const hEl = document.querySelector('.contact-heading');
    if (hEl) hEl.textContent = contact.heading;

    const sEl = document.querySelector('.contact-subtext');
    if (sEl) sEl.textContent = contact.subtext;

    const emailCard = document.getElementById('contactEmail');
    if (emailCard) {
      emailCard.href = `mailto:${contact.email}`;
      const v = emailCard.querySelector('.contact-card-value');
      if (v) v.textContent = contact.email;
    }

    const phoneCard = document.getElementById('contactPhone');
    if (phoneCard) {
      phoneCard.href = `tel:${contact.phone}`;
      const v = phoneCard.querySelector('.contact-card-value');
      if (v) v.textContent = contact.phone;
    }

    const locCard = document.getElementById('contactLocation');
    if (locCard) {
      const v = locCard.querySelector('.contact-card-value');
      if (v) v.textContent = contact.location;
    }

    ['github','linkedin','twitter','dribbble','instagram'].forEach(s => {
      const el = document.getElementById('social' + s[0].toUpperCase() + s.slice(1));
      if (el) el.href = contact.social[s] || '#';
    });
  },

  /* ── FOOTER ── */
  renderFooter() {
    const { footer, meta } = this.data;
    const tEl = document.querySelector('.footer-tagline');
    if (tEl) tEl.textContent = footer.tagline;
  }
};
