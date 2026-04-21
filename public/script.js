/* ══════════════════════════════════════════════════════════
   PORTFOLIO JAVASCRIPT — Animations, Interactions, Logic
   ══════════════════════════════════════════════════════════ */

'use strict';

const IS_MOBILE_VIEW = window.matchMedia('(max-width: 768px)').matches;
const PREFERS_REDUCED_MOTION = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const SHOULD_MINIMIZE_MOTION = IS_MOBILE_VIEW || PREFERS_REDUCED_MOTION;
let activeElasticScrollFrame = null;

function stopElasticScroll() {
  if (activeElasticScrollFrame !== null) {
    cancelAnimationFrame(activeElasticScrollFrame);
    activeElasticScrollFrame = null;
  }
}

function easeOutElastic(progress) {
  if (progress === 0) return 0;
  if (progress === 1) return 1;
  const c4 = (2 * Math.PI) / 3;
  return Math.pow(2, -10 * progress) * Math.sin((progress * 10 - 0.75) * c4) + 1;
}

function getMaxScrollY() {
  return Math.max(0, document.documentElement.scrollHeight - window.innerHeight);
}

function animateElasticScrollTo(targetY, duration = 900) {
  const destination = Math.min(Math.max(targetY, 0), getMaxScrollY());

  if (PREFERS_REDUCED_MOTION) {
    window.scrollTo(0, destination);
    return;
  }

  const startY = window.scrollY;
  const distance = destination - startY;

  if (Math.abs(distance) < 2) {
    window.scrollTo(0, destination);
    return;
  }

  stopElasticScroll();
  const startTime = performance.now();

  const step = (now) => {
    const elapsed = now - startTime;
    const progress = Math.min(elapsed / duration, 1);
    const eased = easeOutElastic(progress);
    const nextY = Math.min(Math.max(startY + distance * eased, 0), getMaxScrollY());
    window.scrollTo(0, nextY);

    if (progress < 1) {
      activeElasticScrollFrame = requestAnimationFrame(step);
      return;
    }

    window.scrollTo(0, destination);
    activeElasticScrollFrame = null;
  };

  activeElasticScrollFrame = requestAnimationFrame(step);
}

/* ─── 0. RENDERER INIT (must run first) ─── */
(function initRenderer() {
  if (typeof PortfolioRenderer !== 'undefined' && typeof PortfolioData !== 'undefined') {
    PortfolioRenderer.init();
  }
})();

/* ─── 1. PAGE LOADER ─── */
(function initLoader() {
  const loader  = document.getElementById('pageLoader');
  window.addEventListener('load', () => {
    setTimeout(() => loader.classList.add('hidden'), 2200);
  });
})();

/* ─── 2. THEME TOGGLE ─── */
(function initTheme() {
  const toggle  = document.getElementById('themeToggle');
  const icon    = document.getElementById('themeIcon');
  const html    = document.documentElement;
  const storageKey = 'portfolioThemePreference';
  const legacyStorageKey = 'portfolioTheme';
  const adminDefault = (() => {
    const fromData = window.__PORTFOLIO_DATA__?.meta?.themeDefault;
    if (fromData === 'light' || fromData === 'dark') return fromData;
    const fromHtml = html.getAttribute('data-theme');
    return fromHtml === 'light' ? 'light' : 'dark';
  })();
  const saved = localStorage.getItem(storageKey) || localStorage.getItem(legacyStorageKey);
  const normalize = (theme) => theme === 'light' ? 'light' : 'dark';

  const apply = (theme, { persist = false } = {}) => {
    const nextTheme = normalize(theme);
    html.setAttribute('data-theme', nextTheme);
    icon.className = nextTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
    if (persist) {
      localStorage.setItem(storageKey, nextTheme);
      localStorage.removeItem(legacyStorageKey);
    }
  };

  apply(saved || adminDefault);
  toggle.addEventListener('click', () => {
    const next = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
    apply(next, { persist: true });
  });
})();

/* ─── 3. CUSTOM CURSOR ─── */
(function initCursor() {
  const outer = document.getElementById('cursorOuter');
  const inner = document.getElementById('cursorInner');
  if (!outer || !inner) return;

  let mouseX = 0, mouseY = 0;
  let outerX = 0, outerY = 0;

  document.addEventListener('mousemove', (e) => {
    mouseX = e.clientX; mouseY = e.clientY;
    inner.style.left = mouseX + 'px';
    inner.style.top  = mouseY + 'px';
  });

  const animateCursor = () => {
    outerX += (mouseX - outerX) * 0.12;
    outerY += (mouseY - outerY) * 0.12;
    outer.style.left = outerX + 'px';
    outer.style.top  = outerY + 'px';
    requestAnimationFrame(animateCursor);
  };
  animateCursor();

  const bindHoverTargets = () => {
    document.querySelectorAll('a, button, .project-card, .skill-card, .tech-badge').forEach(el => {
      if (el.dataset.cursorBound) return;
      el.dataset.cursorBound = 'true';
      el.addEventListener('mouseenter', () => document.body.classList.add('cursor-hover'));
      el.addEventListener('mouseleave', () => document.body.classList.remove('cursor-hover'));
    });
  };

  bindHoverTargets();
  window.addEventListener('portfolio:orbit-badges-updated', bindHoverTargets);
})();

/* ─── 4. NAVBAR SCROLL BEHAVIOUR ─── */
(function initNavbar() {
  const navbar = document.getElementById('navbar');
  const btns   = document.querySelectorAll('.nav-link');
  const sections = document.querySelectorAll('section[id]');

  const onScroll = () => {
    navbar.classList.toggle('scrolled', window.scrollY > 20);

    // Active link highlight
    let current = '';
    sections.forEach(sec => {
      if (window.scrollY >= sec.offsetTop - 100) current = sec.id;
    });
    btns.forEach(b => {
      b.classList.toggle('active', b.getAttribute('data-section') === current);
    });
  };

  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();
})();

/* ─── 5. MOBILE HAMBURGER MENU ─── */
(function initMobileMenu() {
  const ham     = document.getElementById('hamburger');
  const overlay = document.getElementById('mobileNavOverlay');
  const links   = document.querySelectorAll('.mobile-nav-link');

  const close = () => {
    ham.classList.remove('open');
    overlay.classList.remove('open');
    document.body.style.overflow = '';
  };

  ham.addEventListener('click', () => {
    const isOpen = ham.classList.toggle('open');
    overlay.classList.toggle('open', isOpen);
    document.body.style.overflow = isOpen ? 'hidden' : '';
  });

  links.forEach(l => l.addEventListener('click', close));
  overlay.addEventListener('click', (e) => { if (e.target === overlay) close(); });
})();

/* ─── 6. HERO CANVAS — PARTICLE NETWORK ─── */
(function initHeroCanvas() {
  const canvas = document.getElementById('heroCanvas');
  if (!canvas) return;
  if (SHOULD_MINIMIZE_MOTION) {
    canvas.style.display = 'none';
    return;
  }
  const ctx = canvas.getContext('2d');
  let W, H, particles = [], animFrame;
  const PARTICLE_COUNT = 80;
  const CONNECTION_DIST = 130;

  const resize = () => {
    W = canvas.width  = canvas.offsetWidth;
    H = canvas.height = canvas.offsetHeight;
  };

  class Particle {
    constructor() { this.reset(); }
    reset() {
      this.x  = Math.random() * W;
      this.y  = Math.random() * H;
      this.vx = (Math.random() - 0.5) * 0.5;
      this.vy = (Math.random() - 0.5) * 0.5;
      this.r  = Math.random() * 2 + 1;
      this.opacity = Math.random() * 0.5 + 0.2;
    }
    update() {
      this.x += this.vx; this.y += this.vy;
      if (this.x < 0 || this.x > W) this.vx *= -1;
      if (this.y < 0 || this.y > H) this.vy *= -1;
    }
    draw() {
      const isDark = document.documentElement.getAttribute('data-theme') !== 'light';
      const color  = isDark ? `rgba(139,92,246,${this.opacity})` : `rgba(100,60,200,${this.opacity * 0.5})`;
      ctx.beginPath();
      ctx.arc(this.x, this.y, this.r, 0, Math.PI * 2);
      ctx.fillStyle = color;
      ctx.fill();
    }
  }

  const init = () => {
    particles = [];
    for (let i = 0; i < PARTICLE_COUNT; i++) particles.push(new Particle());
  };

  const drawLines = () => {
    const isDark = document.documentElement.getAttribute('data-theme') !== 'light';
    for (let i = 0; i < particles.length; i++) {
      for (let j = i + 1; j < particles.length; j++) {
        const dx = particles[i].x - particles[j].x;
        const dy = particles[i].y - particles[j].y;
        const dist = Math.sqrt(dx * dx + dy * dy);
        if (dist < CONNECTION_DIST) {
          const alpha = (1 - dist / CONNECTION_DIST) * (isDark ? 0.15 : 0.08);
          ctx.beginPath();
          ctx.moveTo(particles[i].x, particles[i].y);
          ctx.lineTo(particles[j].x, particles[j].y);
          ctx.strokeStyle = isDark ? `rgba(139,92,246,${alpha})` : `rgba(100,60,200,${alpha})`;
          ctx.lineWidth = 1;
          ctx.stroke();
        }
      }
    }
  };

  const animate = () => {
    ctx.clearRect(0, 0, W, H);
    particles.forEach(p => { p.update(); p.draw(); });
    drawLines();
    animFrame = requestAnimationFrame(animate);
  };

  resize();
  init();
  animate();
  window.addEventListener('resize', () => { resize(); init(); });
})();

/* ─── 7. TYPED TEXT EFFECT ─── */
(function initTyped() {
  const el = document.getElementById('typedText');
  if (!el) return;
  // Use words from renderer (set via window._portfolioTypedWords) or fallback
  const words = (window._portfolioTypedWords && window._portfolioTypedWords.length)
    ? window._portfolioTypedWords
    : ['Network Support', 'Router and Switch Labs', 'Packet Tracer Practice', 'Troubleshooting', 'IT Support'];
  let wordIdx = 0, charIdx = 0, deleting = false;

  const type = () => {
    const word = words[wordIdx];
    if (!deleting) {
      el.textContent = word.substring(0, ++charIdx);
      if (charIdx === word.length) {
        setTimeout(() => { deleting = true; tick(); }, 1800);
        return;
      }
    } else {
      el.textContent = word.substring(0, --charIdx);
      if (charIdx === 0) {
        deleting = false;
        wordIdx  = (wordIdx + 1) % words.length;
      }
    }
    tick();
  };

  const tick = () => setTimeout(type, deleting ? 50 : 90);
  tick();
})();

/* ─── 8. COUNTER ANIMATION ─── */
(function initCounters() {
  const counters = document.querySelectorAll('.stat-number');
  let triggered  = false;

  if (SHOULD_MINIMIZE_MOTION) {
    counters.forEach(counter => {
      const target = +counter.getAttribute('data-target');
      if (Number.isFinite(target)) counter.textContent = target;
    });
    return;
  }

  const animateCounters = () => {
    counters.forEach(counter => {
      const target = +counter.getAttribute('data-target');
      const start  = performance.now();
      const duration = 1800;

      const step = (now) => {
        const elapsed  = now - start;
        const progress = Math.min(elapsed / duration, 1);
        const eased    = 1 - Math.pow(1 - progress, 3);
        counter.textContent = Math.floor(eased * target);
        if (progress < 1) requestAnimationFrame(step);
        else counter.textContent = target;
      };
      requestAnimationFrame(step);
    });
  };

  const observer = new IntersectionObserver((entries) => {
    if (entries[0].isIntersecting && !triggered) {
      triggered = true;
      animateCounters();
    }
  }, { threshold: 0.3 });

  const hero = document.querySelector('.hero-stats');
  if (hero) observer.observe(hero);
})();

/* ─── 9. SKILLS TAB SWITCHING + BAR ANIMATION ─── */
(function initSkills() {
  const tabs = document.querySelectorAll('.skills-tab');
  const contents = document.querySelectorAll('.skills-content');
  const barsAnimated = new Set();

  if (!tabs.length || !contents.length) return;

  const animateBars = (container) => {
    container.querySelectorAll('.skill-bar').forEach(bar => {
      bar.style.width = bar.getAttribute('data-width') + '%';
    });
  };

  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      const target = tab.getAttribute('data-tab');
      tabs.forEach(t => t.classList.remove('active'));
      contents.forEach(c => c.classList.remove('active'));
      tab.classList.add('active');
      const content = document.getElementById('tabContent' + capitalize(target));
      if (content) {
        content.classList.add('active');
        if (!barsAnimated.has(target)) {
          barsAnimated.add(target);
          setTimeout(() => animateBars(content), 50);
        }
      }
    });
  });

  // Animate initial bars when the section enters the viewport
  const skillsSection = document.getElementById('skills');
  const io = new IntersectionObserver((entries) => {
    if (entries[0].isIntersecting) {
      const firstTab = document.querySelector('.skills-tab.active') || document.querySelector('.skills-tab');
      const target = firstTab?.getAttribute('data-tab');
      if (!target || barsAnimated.has(target)) return;
      barsAnimated.add(target);
      const content = document.getElementById('tabContent' + capitalize(target));
      if (content) animateBars(content);
    }
  }, { threshold: 0.2 });
  if (skillsSection) io.observe(skillsSection);

  function capitalize(s) { return s.charAt(0).toUpperCase() + s.slice(1); }
})();

function initOrbitSkillPopup() {
  const ring = document.querySelector('.profile-ring-outer');
  const popup = document.getElementById('orbitSkillPopup');
  const iconWrap = document.getElementById('orbitSkillPopupIcon');
  const titleNode = document.getElementById('orbitSkillPopupTitle');
  const levelNode = document.getElementById('orbitSkillPopupLevel');
  const meterNode = document.getElementById('orbitSkillPopupMeter');

  if (!ring || !popup || !iconWrap || !titleNode || !levelNode || !meterNode) return;

  const iconNode = iconWrap.querySelector('i');
  let activeBadge = null;
  let hideTimer = null;

  const cancelHide = () => {
    if (hideTimer) {
      window.clearTimeout(hideTimer);
      hideTimer = null;
    }
  };

  const hidePopup = () => {
    cancelHide();
    if (activeBadge) {
      activeBadge.classList.remove('is-active');
      activeBadge = null;
    }
    popup.classList.remove('is-visible');
    popup.setAttribute('aria-hidden', 'true');
  };

  const scheduleHide = () => {
    cancelHide();
    hideTimer = window.setTimeout(hidePopup, 110);
  };

  const positionPopup = (badge) => {
    if (!badge || !document.body.contains(badge)) return;

    const ringRect = ring.getBoundingClientRect();
    const badgeRect = badge.getBoundingClientRect();
    const popupRect = popup.getBoundingClientRect();
    const badgeCenterX = badgeRect.left + badgeRect.width / 2;
    const badgeCenterY = badgeRect.top + badgeRect.height / 2;
    const isMobile = window.innerWidth <= 768;
    const gap = isMobile ? 14 : 24;
    const padding = isMobile ? 10 : 16;
    const spaceLeft = ringRect.left;
    const spaceRight = window.innerWidth - ringRect.right;

    let side = 'right';
    let left;
    let top;

    if (isMobile) {
      side = 'bottom';
      left = (ringRect.width - popupRect.width) / 2;
      top = ringRect.height + gap;
    } else {
      const canUseRight = spaceRight >= popupRect.width + gap;
      const canUseLeft = spaceLeft >= popupRect.width + gap;

      if (canUseRight) {
        side = 'right';
      } else if (canUseLeft) {
        side = 'left';
      } else {
        side = spaceRight >= spaceLeft ? 'right' : 'left';
      }

      left = side === 'right'
        ? ringRect.width + gap
        : -popupRect.width - gap;

      top = badgeCenterY - ringRect.top - popupRect.height / 2;

      const viewportTop = ringRect.top + top;
      const viewportBottom = viewportTop + popupRect.height;
      if (viewportTop < padding) {
        top += padding - viewportTop;
      }
      if (viewportBottom > window.innerHeight - padding) {
        top -= viewportBottom - (window.innerHeight - padding);
      }
    }

    popup.dataset.side = side;
    popup.style.left = left + 'px';
    popup.style.top = top + 'px';
  };

  const showPopup = (badge) => {
    cancelHide();

    if (activeBadge && activeBadge !== badge) {
      activeBadge.classList.remove('is-active');
    }

    activeBadge = badge;
    badge.classList.add('is-active');

    const skillName = badge.dataset.name || 'Skill';
    const skillLevel = Math.max(0, Math.min(100, parseInt(badge.dataset.level || '100', 10) || 100));
    const skillColor = badge.dataset.color || '#7c3aed';
    const iconClass = badge.dataset.iconClass || 'fas fa-code';

    titleNode.textContent = skillName;
    levelNode.textContent = skillLevel + '%';
    popup.style.setProperty('--skill-color', skillColor);
    popup.style.setProperty('--skill-level', skillLevel + '%');
    meterNode.style.width = skillLevel + '%';
    if (iconNode) iconNode.className = iconClass;

    positionPopup(badge);
    popup.classList.add('is-visible');
    popup.setAttribute('aria-hidden', 'false');
  };

  const bindBadges = () => {
    const badges = ring.querySelectorAll('.tech-badge');
    if (!badges.length) {
      hidePopup();
      return;
    }

    badges.forEach((badge) => {
      if (badge.dataset.popupBound) return;
      badge.dataset.popupBound = 'true';
      badge.addEventListener('mouseenter', () => showPopup(badge));
      badge.addEventListener('focus', () => showPopup(badge));
      badge.addEventListener('mousemove', () => positionPopup(badge));
      badge.addEventListener('mouseleave', scheduleHide);
      badge.addEventListener('blur', scheduleHide);
      badge.addEventListener('click', () => showPopup(badge));
    });
  };

  bindBadges();
  ring.addEventListener('mouseleave', scheduleHide);
  window.addEventListener('resize', () => {
    if (activeBadge) positionPopup(activeBadge);
  });
  window.addEventListener('portfolio:orbit-badges-updated', bindBadges);
}

/* ─── 10. PROJECT FILTER (uses dynamic cards rendered by CMS) ─── */
function initProjectFilter() {
  const btns  = document.querySelectorAll('.filter-btn');

  btns.forEach(btn => {
    btn.addEventListener('click', () => {
      const filter = btn.getAttribute('data-filter');
      btns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      // Re-query cards each time (they may have been re-rendered)
      const cards = document.querySelectorAll('.project-card');
      cards.forEach(card => {
        const cat = card.getAttribute('data-category');
        const show = filter === 'all' || cat === filter;
        card.style.transition = 'all 0.35s ease';
        if (show) {
          card.classList.remove('hidden');
          card.style.opacity = '0'; card.style.transform = 'scale(0.9) translateY(20px)';
          setTimeout(() => {
            card.style.opacity = '1'; card.style.transform = 'scale(1) translateY(0)';
          }, 50);
        } else {
          card.style.opacity = '0'; card.style.transform = 'scale(0.9)';
          setTimeout(() => card.classList.add('hidden'), 350);
        }
      });
    });
  });
}

/* ─── 11. TESTIMONIALS SWIPER (init after renderer populates slides) ─── */
function initSwiper() {
  if (typeof Swiper === 'undefined') return;
  const root = document.querySelector('.testimonials-swiper');
  if (!root) return;
  // Destroy previous instance if re-initialising
  if (window._swiperInstance) { window._swiperInstance.destroy(true, true); }
  window._swiperInstance = new Swiper(root, {
    slidesPerView: 1,
    spaceBetween: 24,
    loop: true,
    autoplay: { delay: 4500, disableOnInteraction: false },
    pagination: { el: '#testimonialPagination', clickable: true },
    navigation: { prevEl: '#testimonialPrev', nextEl: '#testimonialNext' },
    breakpoints: {
      640: { slidesPerView: 1 },
      900: { slidesPerView: 2 }
    },
    effect: 'slide'
  });
}

/* ─── 12. CONTACT FORM + MATH CAPTCHA ─── */
(function initContactForm() {

  // ── Math Captcha helpers ──
  let _captchaAnswer = 0;

  function rand(min, max) { return Math.floor(Math.random() * (max - min + 1)) + min; }

  function generateCaptcha() {
    const ops = ['+', '−', '×'];
    const op  = ops[Math.floor(Math.random() * ops.length)];
    let a, b, answer;

    if (op === '+')  { a = rand(1,20); b = rand(1,20); answer = a + b; }
    else if (op === '−') { a = rand(5,20); b = rand(1, a); answer = a - b; }
    else             { a = rand(2, 9);  b = rand(2, 9);  answer = a * b; }

    _captchaAnswer = answer;

    const n1  = document.getElementById('captchaNum1');
    const n2  = document.getElementById('captchaNum2');
    const op2 = document.getElementById('captchaOp');
    const ans = document.getElementById('captchaAnswer');
    const err = document.getElementById('captchaError');

    if (n1)  n1.textContent  = a;
    if (n2)  n2.textContent  = b;
    if (op2) op2.textContent = op;
    if (ans) ans.value       = '';
    if (ans) ans.style.borderColor = '';
    if (err) err.style.display = 'none';
  }

  generateCaptcha();

  // Disable submit until captcha is correct
  const submitBtn = document.getElementById('submitBtn');
  if (submitBtn) { submitBtn.disabled = true; submitBtn.style.opacity = '0.5'; submitBtn.style.cursor = 'not-allowed'; }

  // Live captcha checking
  const captchaInput = document.getElementById('captchaAnswer');
  if (captchaInput) {
    captchaInput.addEventListener('input', () => {
      const entered = parseInt(captchaInput.value, 10);
      const correct = !isNaN(entered) && entered === _captchaAnswer;
      captchaInput.style.borderColor = captchaInput.value === '' ? '' : (correct ? '#22c55e' : '#f43f5e');
      const captchaErr = document.getElementById('captchaError');
      if (captchaErr) captchaErr.style.display = 'none';
      if (submitBtn) {
        submitBtn.disabled   = !correct;
        submitBtn.style.opacity = correct ? '1' : '0.5';
        submitBtn.style.cursor  = correct ? 'pointer' : 'not-allowed';
      }
    });
  }

  const refreshBtn = document.getElementById('captchaRefresh');
  if (refreshBtn) refreshBtn.addEventListener('click', () => {
    generateCaptcha();
    const ans = document.getElementById('captchaAnswer');
    if (ans) ans.focus();
    // Lock submit again on new question
    if (submitBtn) { submitBtn.disabled = true; submitBtn.style.opacity = '0.5'; submitBtn.style.cursor = 'not-allowed'; }
  });

  // ── Form setup ──
  const form    = document.getElementById('contactForm');
  const success = document.getElementById('formSuccess');
  const btn     = document.getElementById('submitBtn');
  if (!form) return;

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    // Validate required fields + email format
    const fields = form.querySelectorAll('input[required], textarea[required]');
    let valid = true;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    fields.forEach(field => {
      field.style.borderColor = '';
      // Remove any previous error hint
      const prev = field.closest('.form-group')?.querySelector('.field-error-hint');
      if (prev) prev.remove();

      if (!field.value.trim()) {
        field.style.borderColor = '#f43f5e';
        valid = false;
      } else if (field.type === 'email' && !emailRegex.test(field.value.trim())) {
        field.style.borderColor = '#f43f5e';
        const hint = document.createElement('p');
        hint.className = 'field-error-hint';
        hint.style.cssText = 'color:#f43f5e;font-size:.78rem;margin:.3rem 0 0 .2rem;';
        hint.textContent = '⚠️ Enter a valid email (e.g. name@domain.com)';
        field.closest('.form-group').appendChild(hint);
        valid = false;
      }

      field.addEventListener('input', () => {
        field.style.borderColor = '';
        const h = field.closest('.form-group')?.querySelector('.field-error-hint');
        if (h) h.remove();
      }, { once: true });
    });

    if (!valid) return;

    // Validate captcha
    const captchaInput = document.getElementById('captchaAnswer');
    const captchaErr   = document.getElementById('captchaError');
    const userAnswer   = parseInt(captchaInput?.value, 10);
    if (isNaN(userAnswer) || userAnswer !== _captchaAnswer) {
      if (captchaInput) captchaInput.style.borderColor = '#f43f5e';
      if (captchaErr)   captchaErr.style.display = 'block';
      generateCaptcha();
      return;
    }
    if (captchaErr) captchaErr.style.display = 'none';

    // Collect form data
    const name    = form.querySelector('[name="name"]')?.value.trim()    || form.querySelector('#contactName')?.value.trim()    || '';
    const email   = form.querySelector('[name="email"]')?.value.trim()   || form.querySelector('#contactEmailInput')?.value.trim() || '';
    const subject = form.querySelector('[name="subject"]')?.value.trim() || form.querySelector('#contactSubject')?.value.trim() || '';
    const message = form.querySelector('[name="message"]')?.value.trim() || form.querySelector('#contactMessage')?.value.trim() || '';

    btn.disabled = true;
    btn.querySelector('.btn-text').textContent = 'Sending…';
    btn.style.opacity = '0.7';

    // 1. Log message to server
    try {
      await fetch('api_messages.php?action=save', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ name, email, subject, message })
      });
    } catch (_) { /* silent */ }

    // Reset UI
    btn.disabled = false;
    btn.querySelector('.btn-text').textContent = 'Send Message';
    btn.style.opacity = '1';
    form.reset();
    generateCaptcha();
    if (submitBtn) { submitBtn.disabled = true; submitBtn.style.opacity = '0.5'; submitBtn.style.cursor = 'not-allowed'; }
    success.classList.add('show');
    setTimeout(() => success.classList.remove('show'), 5000);
  });

  // Real-time border clearance
  form.querySelectorAll('input, textarea').forEach(field => {
    field.addEventListener('input', () => { field.style.borderColor = ''; });
  });

})();

/* ─── 13. BACK TO TOP ─── */
(function initBackToTop() {
  const btn = document.getElementById('backToTop');
  if (!btn) return;
  window.addEventListener('scroll', () => {
    btn.classList.toggle('visible', window.scrollY > 400);
  }, { passive: true });
  btn.addEventListener('click', (e) => {
    e.preventDefault();
    animateElasticScrollTo(0, 960);
  });
})();

/* ─── 14. FOOTER YEAR ─── */
document.getElementById('footerYear').textContent = new Date().getFullYear();

/* ─── 15. AOS INIT + DEFERRED INITS (after renderer) ─── */
document.addEventListener('DOMContentLoaded', () => {
  if (typeof AOS !== 'undefined' && !SHOULD_MINIMIZE_MOTION) {
    AOS.init({
      duration: 700,
      easing: 'ease-out-cubic',
      once: true,
      offset: 60
    });
  }
  // Init dynamic-content-dependent functions after DOM is ready
  initProjectFilter();
  initSwiper();
  initTilt();
  initOrbitSkillPopup();
});

/* ─── 16. SMOOTH SECTION SCROLL FOR ALL NAV LINKS ─── */
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', (e) => {
    const targetSelector = anchor.getAttribute('href');

    if (!targetSelector || targetSelector === '#') {
      e.preventDefault();
      animateElasticScrollTo(0, 900);
      return;
    }

    const target = document.querySelector(targetSelector);
    if (target) {
      e.preventDefault();
      const navbar = document.getElementById('navbar');
      const offset = navbar ? navbar.offsetHeight + 10 : 80;
      const targetY = target.getBoundingClientRect().top + window.scrollY - offset;
      animateElasticScrollTo(targetY, 900);
    }
  });
});

/* ─── 17. SCROLL PROGRESS BAR ─── */
(function initScrollProgress() {
  if (SHOULD_MINIMIZE_MOTION) return;

  const bar = document.createElement('div');
  bar.style.cssText = `
    position: fixed; top: 0; left: 0; height: 3px; z-index: 2000;
    background: linear-gradient(90deg, hsl(250,84%,65%), hsl(195,95%,55%));
    width: 0; transition: width 0.1s linear; pointer-events: none;
  `;
  document.body.prepend(bar);

  window.addEventListener('scroll', () => {
    const total = document.documentElement.scrollHeight - window.innerHeight;
    const pct   = total > 0 ? (window.scrollY / total) * 100 : 0;
    bar.style.width = pct + '%';
  }, { passive: true });
})();

/* ─── 18. CARD TILT EFFECT (exported so DOMContentLoaded can call it after render) ─── */
function initTilt() {
  const cards = document.querySelectorAll('.project-card, .testimonial-card');
  cards.forEach(card => {
    if (card._tiltBound) return; // avoid double-binding
    card._tiltBound = true;
    card.addEventListener('mousemove', (e) => {
      const rect   = card.getBoundingClientRect();
      const x      = e.clientX - rect.left - rect.width  / 2;
      const y      = e.clientY - rect.top  - rect.height / 2;
      const rotX   = (-y / rect.height * 8).toFixed(2);
      const rotY   = ( x / rect.width  * 8).toFixed(2);
      card.style.transform = `perspective(600px) rotateX(${rotX}deg) rotateY(${rotY}deg) translateY(-4px)`;
    });
    card.addEventListener('mouseleave', () => {
      card.style.transform = '';
    });
  });
}

/* ─── 19. NEWSLETTER FORM ─── */
(function initNewsletter() {
  const btn   = document.getElementById('newsletterSubmit');
  const input = document.getElementById('newsletterEmail');
  if (!btn || !input) return;
  btn.addEventListener('click', () => {
    if (!input.value || !input.value.includes('@')) {
      input.style.borderColor = '#f43f5e';
      return;
    }
    input.style.borderColor = '';
    input.value = '';
    btn.innerHTML = '<i class="fas fa-check"></i>';
    btn.style.background = 'linear-gradient(135deg,#43e97b,#38f9d7)';
    setTimeout(() => {
      btn.innerHTML = '<i class="fas fa-arrow-right"></i>';
      btn.style.background = '';
    }, 2000);
  });
})();

/* ─── 20. DOWNLOAD CV BUTTON ─── */
document.getElementById('downloadCV')?.addEventListener('click', (e) => {
  e.preventDefault();
  // Simulates download — replace with actual CV path
  const link = document.createElement('a');
  link.href  = '#'; // Replace with: './assets/cv.pdf'
  link.download = 'Muhammad_Asif_Shabbir_CV.pdf';
  link.click();
});
