'use strict';

const SKILL_CATEGORY_META = {
  networking: {
    label: 'Networking',
    description: 'Primary focus for network support, troubleshooting, routing, switching, and practical Packet Tracer implementation.',
  },
  webDevelopment: {
    label: 'Web Development',
    description: 'Secondary supporting skills for web-based solutions, Laravel applications, and content-driven development.',
  },
  androidDevelopment: {
    label: 'Android Development',
    description: 'Supporting mobile development capabilities with Java, Android Studio, and Firebase.',
  },
  productivityTools: {
    label: 'Productivity Tools',
    description: 'Day-to-day tools for documentation, reporting, structured work, and AI-assisted project building.',
  },
  professionalStrengths: {
    label: 'Professional Strengths',
    description: 'Transferable strengths shaped by military service, instruction, coordination, and disciplined execution.',
  },
  frontend: {
    label: 'Frontend',
    description: 'Frontend skills carried forward from earlier project work.',
  },
  backend: {
    label: 'Backend',
    description: 'Backend and server-side development skills from existing project work.',
  },
  tools: {
    label: 'Tools',
    description: 'General tools and supporting technology skills.',
  },
};

const PortfolioRenderer = {
  data: null,

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
    this.renderAchievements();
    this.renderEducation();
    this.renderLanguages();
    this.renderContact();
    this.renderFooter();
  },

  renderMeta() {
    const { meta } = this.data;
    const pageMeta = window.__PORTFOLIO_META__ || {};
    document.title = meta.siteTitle || document.title;

    const setMeta = (selector, value, attr = 'content') => {
      document.querySelectorAll(selector).forEach((tag) => tag.setAttribute(attr, value || ''));
    };

    setMeta('meta[name="description"], meta[property="og:description"], meta[name="twitter:description"], meta[property="twitter:description"]', meta.siteDesc || '');
    setMeta('meta[name="keywords"]', meta.siteKeywords || '');
    setMeta('meta[name="author"]', meta.name || '');
    setMeta('meta[property="og:title"], meta[name="twitter:title"], meta[property="twitter:title"]', meta.siteTitle || '');

    const currentUrl = pageMeta.canonicalUrl || window.location.href.split('?')[0];
    const absoluteImageUrl = pageMeta.ogImageUrl || new URL('/og-image.jpg', window.location.origin).href;
    const imageAlt = pageMeta.ogImageAlt || `${meta.name || meta.siteTitle || 'Portfolio'} profile preview image`;
    const imageWidth = String(pageMeta.ogImageWidth || 1200);
    const imageHeight = String(pageMeta.ogImageHeight || 630);

    document.querySelectorAll('link[rel="canonical"]').forEach((tag) => tag.setAttribute('href', currentUrl));
    setMeta('meta[property="og:url"], meta[name="twitter:url"], meta[property="twitter:url"]', currentUrl);
    setMeta('meta[property="og:image"], meta[name="twitter:image"], meta[property="twitter:image"]', absoluteImageUrl);
    setMeta('meta[property="og:image:width"]', imageWidth);
    setMeta('meta[property="og:image:height"]', imageHeight);
    setMeta('meta[property="og:image:alt"], meta[name="twitter:image:alt"], meta[property="twitter:image:alt"]', imageAlt);
    setMeta('meta[name="twitter:card"], meta[property="twitter:card"]', 'summary_large_image');

    const initials = meta.brandText
      ? meta.brandText.trim()
      : (meta.name
        ? meta.name.trim().split(/\s+/).map((word) => word[0]).slice(0, 3).join('').toUpperCase()
        : 'MAS');

    document.querySelectorAll('.logo-name').forEach((el) => {
      el.textContent = initials;
    });

    this.setTextFavicon(initials);
  },

  setTextFavicon(text) {
    try {
      const size = 64;
      const canvas = document.createElement('canvas');
      canvas.width = canvas.height = size;
      const ctx = canvas.getContext('2d');
      const gradient = ctx.createLinearGradient(0, 0, size, size);
      gradient.addColorStop(0, 'hsl(250,84%,65%)');
      gradient.addColorStop(1, 'hsl(195,95%,55%)');
      ctx.fillStyle = gradient;
      ctx.beginPath();
      if (typeof ctx.roundRect === 'function') {
        ctx.roundRect(0, 0, size, size, 14);
      } else {
        ctx.rect(0, 0, size, size);
      }
      ctx.fill();

      const fontSize = text.length > 2 ? 20 : 26;
      ctx.fillStyle = '#ffffff';
      ctx.font = `700 ${fontSize}px "Outfit", Arial, sans-serif`;
      ctx.textAlign = 'center';
      ctx.textBaseline = 'middle';
      ctx.fillText(text, size / 2, size / 2);

      let link = document.querySelector('link[rel~="icon"]');
      if (!link) {
        link = document.createElement('link');
        link.rel = 'icon';
        document.head.appendChild(link);
      }
      link.type = 'image/png';
      link.href = canvas.toDataURL('image/png');
    } catch (_) {
      // Ignore favicon issues.
    }
  },

  renderHero() {
    const { hero, images, contact } = this.data;

    const tagEl = document.querySelector('.hero-tag');
    if (tagEl) {
      tagEl.innerHTML = `<span class="status-dot pulse"></span>${this.escapeHtml(hero.availableTag || '')}`;
    }

    const titleEl = document.querySelector('.hero-title');
    if (titleEl) {
      titleEl.innerHTML = `${this.escapeHtml(hero.firstName || '')} <span class="gradient-text">${this.escapeHtml(hero.highlightName || '')}</span>`;
    }

    const descEl = document.querySelector('.hero-desc');
    if (descEl) {
      descEl.innerHTML = hero.description || '';
    }

    const statsWrap = document.querySelector('.hero-stats');
    if (statsWrap) {
      statsWrap.innerHTML = (hero.stats || []).map((stat, index) => `
        <div class="stat-item">
          <span class="stat-number" data-target="${this.escapeHtml(String(stat.number ?? 0))}">0</span>
          <span class="stat-label">${this.escapeHtml(stat.label || '')}</span>
        </div>
        ${index < (hero.stats || []).length - 1 ? '<div class="stat-divider"></div>' : ''}
      `).join('');
    }

    const heroImg = document.querySelector('.profile-img');
    if (heroImg && images?.hero) {
      heroImg.src = images.hero;
    }

    this.configureLinkButton(document.getElementById('heroResumeBtn'), contact?.resumeUrl, 'View CV', true);
    this.configureDownloadLink(document.getElementById('heroDownloadCvBtn'), contact?.resumeUrl, 'Download CV');
    this.configureLinkButton(document.getElementById('heroContactBtn'), '#contact', 'Contact Me');
    this.configureLinkButton(document.getElementById('heroLinkedinBtn'), contact?.social?.linkedin, 'LinkedIn', true);
    this.configureLinkButton(document.getElementById('heroProjectsBtn'), '#projects', 'View Projects');

    window._portfolioTypedWords = hero.typedWords || [];
    this.renderOrbitBadges();
  },

  renderOrbitBadges() {
    const allSkills = this.flattenSkills();
    const duration = this.data.hero?.orbitSpeed || 14;
    const ring = document.querySelector('.profile-ring-outer');
    if (ring) {
      ring.style.setProperty('--orbit-duration', `${duration}s`);
      const orbitContainer = document.getElementById('orbitBadgesContainer') || ring;
      orbitContainer.innerHTML = '';

      allSkills.forEach((skill, index) => {
        const delay = -((index / allSkills.length) * duration).toFixed(3);
        const badge = document.createElement('div');
        const skillColor = skill.iconColor || '#7c3aed';
        const skillLevel = Math.max(0, Math.min(100, Number(skill.level ?? 100) || 100));
        badge.className = 'tech-badge';
        badge.style.cssText = `color:${skillColor}; animation-duration:${duration}s; animation-delay:${delay}s; --badge-color:${skillColor};`;
        badge.title = skill.name || '';
        badge.dataset.name = skill.name || '';
        badge.dataset.level = String(skillLevel);
        badge.dataset.iconClass = skill.iconClass || 'fas fa-code';
        badge.dataset.color = skillColor;
        badge.setAttribute('tabindex', '0');
        badge.setAttribute('role', 'button');
        badge.setAttribute('aria-label', `${skill.name || 'Skill'} skill level ${skillLevel}%`);
        badge.innerHTML = `<i class="${this.escapeHtml(skill.iconClass || 'fas fa-code')}"></i>`;
        orbitContainer.appendChild(badge);
      });
    }

    const loaderRing = document.querySelector('.loader-ring-outer');
    if (loaderRing) {
      loaderRing.style.setProperty('--orbit-duration', `${duration}s`);
      loaderRing.querySelectorAll('.loader-orbit-badge').forEach((badge) => badge.remove());
      const loaderSkills = allSkills.slice(0, Math.min(allSkills.length, 12));
      loaderSkills.forEach((skill, index) => {
        const delay = -((index / loaderSkills.length) * duration).toFixed(3);
        const badge = document.createElement('div');
        badge.className = 'loader-orbit-badge';
        badge.style.cssText = `color:${skill.iconColor || '#7c3aed'}; animation-duration:${duration}s; animation-delay:${delay}s;`;
        badge.title = skill.name || '';
        badge.innerHTML = `<i class="${this.escapeHtml(skill.iconClass || 'fas fa-code')}"></i>`;
        loaderRing.appendChild(badge);
      });
    }

    window.dispatchEvent(new CustomEvent('portfolio:orbit-badges-updated'));
  },

  renderAbout() {
    const { about, images, contact } = this.data;

    const headingEl = document.querySelector('.about-heading');
    if (headingEl) {
      headingEl.innerHTML = `${this.escapeHtml(about.heading || '')} <span class="gradient-text">${this.escapeHtml(about.headingHighlight || '')}</span>`;
    }

    const textEls = document.querySelectorAll('.about-text');
    if (textEls[0]) {
      textEls[0].innerHTML = about.text1 || '';
    }
    if (textEls[1]) {
      textEls[1].innerHTML = about.text2 || '';
    }

    const infoGrid = document.querySelector('.about-info-grid');
    if (infoGrid) {
      infoGrid.innerHTML = `
        <div class="info-item">
          <i class="fas fa-user"></i>
          <div>
            <span class="info-label">Name</span>
            <span class="info-value">${this.escapeHtml(about.name || '')}</span>
          </div>
        </div>
        <div class="info-item">
          <i class="fas fa-envelope"></i>
          <div>
            <span class="info-label">Email</span>
            <span class="info-value">${this.escapeHtml(about.email || '')}</span>
          </div>
        </div>
        <div class="info-item">
          <i class="fas fa-map-marker-alt"></i>
          <div>
            <span class="info-label">Location</span>
            <span class="info-value">${this.escapeHtml(about.location || '')}</span>
          </div>
        </div>
        <div class="info-item">
          <i class="fas fa-graduation-cap"></i>
          <div>
            <span class="info-label">Education</span>
            <span class="info-value">${this.escapeHtml(about.degree || '')}</span>
          </div>
        </div>
      `;
    }

    const expNum = document.querySelector('.exp-number');
    if (expNum) {
      expNum.textContent = about.expYears || '';
    }

    const aboutImg = document.querySelector('.about-img');
    if (aboutImg && images?.about) {
      aboutImg.src = images.about;
    }

    const code = document.querySelector('.code-body code');
    if (code) {
      code.innerHTML =
        `<span class="code-kw">const</span> <span class="code-var">profile</span> = {\n` +
        `  <span class="code-key">role</span>: <span class="code-str">"${this.escapeHtml(this.data.meta?.role || 'Network Support Engineer')}"</span>,\n` +
        `  <span class="code-key">focus</span>: <span class="code-str">"CCNA in progress"</span>,\n` +
        `  <span class="code-key">labs</span>: <span class="code-str">"Packet Tracer"</span>,\n` +
        `  <span class="code-key">location</span>: <span class="code-str">"${this.escapeHtml(about.location || '')}"</span>,\n` +
        `  <span class="code-key">openTo</span>: <span class="code-str">"Pakistan, UAE, KSA, Europe"</span>\n` +
        `};`;
    }

    this.configureLinkButton(document.getElementById('aboutResumeLink'), contact?.resumeUrl, 'View CV', true);
  },

  renderSkills() {
    const tabsWrap = document.getElementById('skillsTabs');
    const panelsWrap = document.getElementById('skillsPanels');
    if (!tabsWrap || !panelsWrap) {
      return;
    }

    const categories = Object.keys(this.data.skills || {}).filter((key) => Array.isArray(this.data.skills[key]) && this.data.skills[key].length);
    if (!categories.length) {
      tabsWrap.innerHTML = '';
      panelsWrap.innerHTML = '<p class="empty-state">Skills will appear here once added.</p>';
      return;
    }

    tabsWrap.innerHTML = categories.map((key, index) => {
      const meta = SKILL_CATEGORY_META[key] || { label: key, description: '' };
      return `<button class="skills-tab${index === 0 ? ' active' : ''}" data-tab="${this.escapeHtml(key)}">${this.escapeHtml(meta.label)}</button>`;
    }).join('');

    panelsWrap.innerHTML = categories.map((key, index) => {
      const meta = SKILL_CATEGORY_META[key] || { label: key, description: '' };
      const cards = (this.data.skills[key] || []).map((skill) => `
        <div class="skill-card skill-card--simple">
          <div class="skill-icon"><i class="${this.escapeHtml(skill.iconClass || 'fas fa-code')}" style="color:${this.escapeHtml(skill.iconColor || '#7c3aed')}"></i></div>
          <span class="skill-name">${this.escapeHtml(skill.name || '')}</span>
        </div>
      `).join('');

      return `
        <div class="skills-content${index === 0 ? ' active' : ''}" id="tabContent${this.capitalize(key)}">
          <div class="skills-panel-copy">
            <h3>${this.escapeHtml(meta.label)}</h3>
            <p>${this.escapeHtml(meta.description || '')}</p>
          </div>
          <div class="skills-grid skills-grid--simple">${cards}</div>
        </div>
      `;
    }).join('');
  },

  renderProjects() {
    const { projects } = this.data;
    const grid = document.getElementById('projectsGrid');
    if (!grid) {
      return;
    }

    const bar = document.getElementById('projectsShowMoreBar');

    const cardHtml = (project, index) => `
      <div class="project-card" data-category="${this.escapeHtml(project.category || '')}" data-aos="fade-up" data-aos-delay="${(index % 3 + 1) * 100}">
        <div class="project-image">
          <div class="project-img-placeholder ${this.escapeHtml(project.gradient || 'gradient-1')}"><i class="${this.escapeHtml(project.icon || 'fas fa-folder-open')}"></i></div>
          <div class="project-overlay">
            ${project.liveUrl ? `<a href="${this.escapeHtml(project.liveUrl)}" class="project-link" aria-label="Live Demo" target="_blank" rel="noopener noreferrer"><i class="fas fa-external-link-alt"></i></a>` : ''}
            ${project.githubUrl ? `<a href="${this.escapeHtml(project.githubUrl)}" class="project-link" aria-label="Source" target="_blank" rel="noopener noreferrer"><i class="fab fa-github"></i></a>` : ''}
          </div>
        </div>
        <div class="project-body">
          <div class="project-tags">${(project.tags || []).map((tag) => `<span class="project-tag">${this.escapeHtml(tag)}</span>`).join('')}</div>
          <h3 class="project-title">${this.escapeHtml(project.title || '')}</h3>
          <p class="project-desc">${this.escapeHtml(project.desc || '')}</p>
        </div>
      </div>
    `;

    const render = () => {
      const activeFilter = document.querySelector('.filter-btn.active')?.getAttribute('data-filter') || 'all';
      const filtered = activeFilter === 'all'
        ? projects
        : projects.filter((project) => project.category === activeFilter);

      grid.innerHTML = filtered.map((project, index) => cardHtml(project, index)).join('');

      if (bar) {
        bar.style.display = 'none';
      }

      if (window.AOS) {
        AOS.refresh();
      }
    };

    render();

    document.querySelectorAll('.filter-btn').forEach((filterBtn) => {
      if (filterBtn.dataset.rendererBound === '1') {
        return;
      }
      filterBtn.dataset.rendererBound = '1';
      filterBtn.addEventListener('click', () => {
        render();
      });
    });
  },

  renderExperience() {
    const container = document.querySelector('.timeline-container');
    if (!container) {
      return;
    }

    container.innerHTML = (this.data.experience || []).map((item, index) => `
      <div class="timeline-item timeline-item--${index % 2 === 0 ? 'left' : 'right'}" data-aos="fade-up" data-aos-delay="${(index + 1) * 100}">
        <div class="timeline-dot"><i class="${this.escapeHtml(item.iconClass || 'fas fa-briefcase')}"></i></div>
        <div class="timeline-card">
          <div class="timeline-header">
            <div>
              <h3 class="timeline-title">${this.escapeHtml(item.title || '')}</h3>
              <span class="timeline-company">${this.escapeHtml(item.company || '')}</span>
            </div>
            <div class="timeline-meta">
              <span class="timeline-period"><i class="fas fa-calendar-alt"></i> ${this.escapeHtml(item.period || '')}</span>
              <span class="timeline-location"><i class="fas fa-map-marker-alt"></i> ${this.escapeHtml(item.location || '')}</span>
            </div>
          </div>
          <p class="timeline-desc">${this.escapeHtml(item.desc || '')}</p>
          <div class="timeline-tags">${(item.tags || []).map((tag) => `<span>${this.escapeHtml(tag)}</span>`).join('')}</div>
        </div>
      </div>
    `).join('');
  },

  renderAchievements() {
    const grid = document.getElementById('achievementsGrid');
    if (!grid) {
      return;
    }

    grid.innerHTML = (this.data.achievements || []).map((item, index) => `
      <article class="achievement-card" data-aos="fade-up" data-aos-delay="${(index % 3 + 1) * 100}">
        <div class="achievement-top">
          <span class="achievement-period">${this.escapeHtml(item.period || 'Recognized')}</span>
          ${item.highlight ? `<span class="achievement-highlight">${this.escapeHtml(item.highlight)}</span>` : ''}
        </div>
        <h3>${this.escapeHtml(item.title || '')}</h3>
        ${item.subtitle ? `<p class="achievement-subtitle">${this.escapeHtml(item.subtitle)}</p>` : ''}
        <p class="achievement-desc">${this.escapeHtml(item.description || '')}</p>
      </article>
    `).join('');
  },

  renderEducation() {
    const grid = document.getElementById('educationGrid');
    if (!grid) {
      return;
    }

    grid.innerHTML = (this.data.education || []).map((item, index) => `
      <article class="credential-card" data-aos="fade-up" data-aos-delay="${(index + 1) * 120}">
        <div class="credential-badge">${this.escapeHtml(item.subtitle || 'Credential')}</div>
        <h3>${this.escapeHtml(item.title || '')}</h3>
        <p class="credential-status">${this.escapeHtml(item.status || '')}</p>
        <p class="credential-desc">${this.escapeHtml(item.description || '')}</p>
      </article>
    `).join('');
  },

  renderLanguages() {
    const grid = document.getElementById('languagesGrid');
    if (!grid) {
      return;
    }

    grid.innerHTML = (this.data.languages || []).map((item, index) => `
      <article class="language-card" data-aos="fade-up" data-aos-delay="${(index + 1) * 120}">
        <div class="language-icon"><i class="fas fa-language"></i></div>
        <div>
          <h3>${this.escapeHtml(item.name || '')}</h3>
          <p>${this.escapeHtml(item.proficiency || '')}</p>
        </div>
      </article>
    `).join('');
  },

  renderContact() {
    const { contact } = this.data;

    const heading = document.querySelector('.contact-heading');
    if (heading) {
      heading.textContent = contact.heading || '';
    }

    const subtext = document.querySelector('.contact-subtext');
    if (subtext) {
      subtext.textContent = contact.subtext || '';
    }

    this.configureContactCard(document.getElementById('contactEmail'), `mailto:${contact.email || ''}`, contact.email || '', !!contact.email);
    this.configureContactCard(document.getElementById('contactPhone'), `tel:${contact.phone || ''}`, contact.phone || '', !!contact.phone);

    const locationCard = document.getElementById('contactLocation');
    if (locationCard) {
      const value = locationCard.querySelector('.contact-card-value');
      if (value) {
        value.textContent = contact.location || '';
      }
    }

    this.configureContactCard(document.getElementById('contactPortfolio'), contact.portfolioUrl, this.displayUrl(contact.portfolioUrl) || 'Portfolio', !!contact.portfolioUrl, true);
    this.configureContactCard(document.getElementById('contactResume'), contact.resumeUrl || '#contact', contact.resumeUrl ? 'View PDF CV' : 'CV available on request', !!contact.resumeUrl, true);
    this.configureDownloadCard(document.getElementById('contactResumeDownload'), contact.resumeUrl || '#contact', contact.resumeUrl ? 'Download PDF CV' : 'CV available on request', !!contact.resumeUrl);

    this.configureSocialLink('socialGithub', contact.social?.github);
    this.configureSocialLink('socialLinkedin', contact.social?.linkedin);
    this.configureSocialLink('socialTwitter', contact.social?.twitter);
    this.configureSocialLink('socialDribbble', contact.social?.dribbble);
    this.configureSocialLink('socialInstagram', contact.social?.instagram);
  },

  renderFooter() {
    const { footer, contact } = this.data;

    const tagline = document.querySelector('.footer-tagline');
    if (tagline) {
      tagline.textContent = footer.tagline || '';
    }

    const footerSocialLinks = document.getElementById('footerSocialLinks');
    if (footerSocialLinks) {
      const socials = [
        ['github', 'fab fa-github', 'GitHub'],
        ['linkedin', 'fab fa-linkedin-in', 'LinkedIn'],
        ['twitter', 'fab fa-twitter', 'Twitter'],
        ['instagram', 'fab fa-instagram', 'Instagram'],
      ].filter(([key]) => !!contact.social?.[key]);

      footerSocialLinks.innerHTML = socials.map(([key, icon, label]) => `
        <a href="${this.escapeHtml(contact.social[key])}" class="social-link" aria-label="${this.escapeHtml(label)}" target="_blank" rel="noopener noreferrer">
          <i class="${this.escapeHtml(icon)}"></i>
        </a>
      `).join('');
    }

    this.configureLinkButton(document.getElementById('footerPortfolioLink'), contact.portfolioUrl, 'Open Portfolio', true);
    this.configureLinkButton(document.getElementById('footerLinkedinLink'), contact.social?.linkedin, 'LinkedIn Profile', true);
    this.configureLinkButton(document.getElementById('footerResumeLink'), contact.resumeUrl, 'View CV', true);
    this.configureDownloadLink(document.getElementById('footerDownloadCvLink'), contact.resumeUrl, 'Download CV');
  },

  configureLinkButton(element, url, label, openInNewTab = false) {
    if (!element) {
      return;
    }

    const textNode = element.querySelector('span');
    if (textNode) {
      textNode.textContent = label;
    } else {
      element.textContent = label;
    }

    if (url) {
      element.href = url;
      element.classList.remove('is-disabled');
      element.removeAttribute('aria-disabled');
      if (openInNewTab && !String(url).startsWith('#')) {
        element.setAttribute('target', '_blank');
        element.setAttribute('rel', 'noopener noreferrer');
      } else {
        element.removeAttribute('target');
        element.removeAttribute('rel');
      }
      return;
    }

    element.href = '#contact';
    element.classList.add('is-disabled');
    element.setAttribute('aria-disabled', 'true');
    element.removeAttribute('target');
    element.removeAttribute('rel');

    if (textNode) {
      textNode.textContent = label === 'View CV' || label === 'Download CV' ? 'CV Available On Request' : label;
    } else if (label === 'View CV' || label === 'Download CV') {
      element.textContent = 'CV Available On Request';
    }
  },

  configureDownloadLink(element, url, label) {
    this.configureLinkButton(element, url, label, false);
    this.setDownloadState(element, url);
  },

  configureDownloadCard(element, href, value, enabled) {
    this.configureContactCard(element, href, value, enabled, false);
    this.setDownloadState(element, enabled ? href : '');
  },

  setDownloadState(element, url) {
    if (!element) {
      return;
    }

    if (url && !String(url).startsWith('#')) {
      element.setAttribute('download', this.downloadFileName(url));
      return;
    }

    element.removeAttribute('download');
  },

  configureContactCard(element, href, value, enabled, openInNewTab = false) {
    if (!element) {
      return;
    }

    const valueNode = element.querySelector('.contact-card-value');
    if (valueNode) {
      valueNode.textContent = value || '';
    }

    if (!enabled) {
      element.href = '#contact';
      element.classList.add('is-disabled');
      element.setAttribute('aria-disabled', 'true');
      element.removeAttribute('target');
      element.removeAttribute('rel');
      return;
    }

    element.href = href;
    element.classList.remove('is-disabled');
    element.removeAttribute('aria-disabled');

    if (openInNewTab && href && !href.startsWith('#')) {
      element.setAttribute('target', '_blank');
      element.setAttribute('rel', 'noopener noreferrer');
    } else {
      element.removeAttribute('target');
      element.removeAttribute('rel');
    }
  },

  configureSocialLink(id, url) {
    const element = document.getElementById(id);
    if (!element) {
      return;
    }

    if (url) {
      element.href = url;
      element.style.display = '';
      element.setAttribute('target', '_blank');
      element.setAttribute('rel', 'noopener noreferrer');
      return;
    }

    element.style.display = 'none';
    element.removeAttribute('href');
    element.removeAttribute('target');
    element.removeAttribute('rel');
  },

  flattenSkills() {
    return Object.values(this.data.skills || {}).flatMap((items) => Array.isArray(items) ? items : []);
  },

  downloadFileName(url) {
    if (!url) {
      return 'Muhammad-Asif-Shabbir-CV.pdf';
    }

    try {
      const filename = decodeURIComponent(String(url).split('?')[0].split('/').pop() || '');
      if (filename.toLowerCase().endsWith('.pdf')) {
        return filename;
      }
    } catch (_) {
      // Fall back to the default file name below.
    }

    return 'Muhammad-Asif-Shabbir-CV.pdf';
  },

  displayUrl(url) {
    if (!url) {
      return '';
    }

    return String(url).replace(/^https?:\/\//i, '').replace(/\/$/, '');
  },

  capitalize(value) {
    return value.charAt(0).toUpperCase() + value.slice(1);
  },

  escapeHtml(value) {
    return String(value ?? '')
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#39;');
  },
};
