/* ══════════════════════════════════════════════════════════
   PORTFOLIO JAVASCRIPT — Animations, Interactions, Logic
   ══════════════════════════════════════════════════════════ */

'use strict';

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
  const saved   = localStorage.getItem('portfolioTheme') || 'dark';

  const apply = (theme) => {
    html.setAttribute('data-theme', theme);
    icon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
    localStorage.setItem('portfolioTheme', theme);
  };

  apply(saved);
  toggle.addEventListener('click', () => {
    const next = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
    apply(next);
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

  document.querySelectorAll('a, button, .project-card, .skill-card').forEach(el => {
    el.addEventListener('mouseenter', () => document.body.classList.add('cursor-hover'));
    el.addEventListener('mouseleave', () => document.body.classList.remove('cursor-hover'));
  });
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
    : ['Web Applications','Mobile Experiences','UI/UX Designs','REST APIs','Beautiful Interfaces','Digital Products'];
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
  const tabs     = document.querySelectorAll('.skills-tab');
  const contents = document.querySelectorAll('.skills-content');
  let barsAnimated = { frontend: false, backend: false, tools: false };

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
        if (!barsAnimated[target]) {
          barsAnimated[target] = true;
          setTimeout(() => animateBars(content), 50);
        }
      }
    });
  });

  // Animate initial (frontend) bars when section enters viewport
  const skillsSection = document.getElementById('skills');
  const io = new IntersectionObserver((entries) => {
    if (entries[0].isIntersecting && !barsAnimated.frontend) {
      barsAnimated.frontend = true;
      const fc = document.getElementById('tabContentFrontend');
      if (fc) animateBars(fc);
    }
  }, { threshold: 0.2 });
  if (skillsSection) io.observe(skillsSection);

  function capitalize(s) { return s.charAt(0).toUpperCase() + s.slice(1); }
})();

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
  // Destroy previous instance if re-initialising
  if (window._swiperInstance) { window._swiperInstance.destroy(true, true); }
  window._swiperInstance = new Swiper('.testimonials-swiper', {
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

/* ─── 12. CONTACT FORM ─── */
  // ── Math Captcha ──
  let _captchaAnswer = 0;

  function generateCaptcha() {
    const ops = ['+', '-', '×'];
    const op  = ops[Math.floor(Math.random() * ops.length)];
    let a, b, answer;

    if (op === '+')      { a = rand(1,20); b = rand(1,20); answer = a + b; }
    else if (op === '-') { a = rand(5,20); b = rand(1, a); answer = a - b; }
    else                 { a = rand(2, 9); b = rand(2, 9); answer = a * b; }

    _captchaAnswer = answer;

    const n1 = document.getElementById('captchaNum1');
    const n2 = document.getElementById('captchaNum2');
    const op2 = document.getElementById('captchaOp');
    const ans = document.getElementById('captchaAnswer');
    const err = document.getElementById('captchaError');

    if (n1) n1.textContent = a;
    if (n2) n2.textContent = b;
    if (op2) op2.textContent = op;
    if (ans) ans.value = '';
    if (err) err.style.display = 'none';
  }

  function rand(min, max) { return Math.floor(Math.random() * (max - min + 1)) + min; }

  generateCaptcha();
  const refreshBtn = document.getElementById('captchaRefresh');
  if (refreshBtn) refreshBtn.addEventListener('click', () => {
    generateCaptcha();
    const ans = document.getElementById('captchaAnswer');
    if (ans) { ans.style.borderColor = ''; ans.focus(); }
  });


  const form    = document.getElementById('contactForm');
  const success = document.getElementById('formSuccess');
  const btn     = document.getElementById('submitBtn');
  if (!form) return;

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
      const fields = form.querySelectorAll('input[required], textarea[required]');
      let valid = true;
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

      fields.forEach(field => {
        field.style.borderColor = '';
        field.removeAttribute('data-err');

        if (!field.value.trim()) {
          field.style.borderColor = '#f43f5e';
          valid = false;
        } else if (field.type === 'email' && !emailRegex.test(field.value.trim())) {
          field.style.borderColor = '#f43f5e';
          // Show inline hint below the field
          let hint = field.parentElement.querySelector('.field-error-hint');
          if (!hint) {
            hint = document.createElement('p');
            hint.className = 'field-error-hint';
            hint.style.cssText = 'color:#f43f5e;font-size:.78rem;margin:.3rem 0 0 .2rem;';
            field.parentElement.insertAdjacentElement('afterend', hint);
          }
          hint.textContent = '⚠️ Enter a valid email (e.g. name@domain.com)';
          field.setAttribute('data-err', '1');
          valid = false;
        }

        // Clear hint on correct re-entry
        field.addEventListener('input', () => {
          field.style.borderColor = '';
          const existingHint = field.parentElement.querySelector('.field-error-hint') ||
                               field.parentElement.nextElementSibling?.classList?.contains('field-error-hint') &&
                               field.parentElement.nextElementSibling;
          if (existingHint && existingHint.classList?.contains('field-error-hint')) {
            existingHint.remove();
          }
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
      generateCaptcha(); // new question on failure
      return;
    }
    if (captchaErr) captchaErr.style.display = 'none';

    // Collect form data
    const name    = (form.querySelector('#contactName')    || form.querySelector('[name="name"]'))?.value.trim()    || '';
    const email   = (form.querySelector('#contactEmail')   || form.querySelector('[name="email"]'))?.value.trim()   || '';
    const subject = (form.querySelector('#contactSubject') || form.querySelector('[name="subject"]'))?.value.trim() || '';
    const message = (form.querySelector('#contactMessage') || form.querySelector('[name="message"]'))?.value.trim() || '';

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
    } catch (_) { /* silent — don't block user experience */ }

    // 2. Fire WhatsApp notification via server-side proxy (avoids CORS)
    try {
      const data = PortfolioData.get();
      const wa   = data?.contact?.whatsappApi;
      if (wa && wa.enabled && wa.apiKey && wa.accountName && wa.targetNumber) {
        const waMsg =
          `📬 *New Portfolio Contact!*\n\n` +
          `*Name:* ${name}\n` +
          `*Email:* ${email}\n` +
          (subject ? `*Subject:* ${subject}\n` : '') +
          `*Message:*\n${message}`;

        await fetch('wa_proxy.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            apiKey:      wa.apiKey,
            accountName: wa.accountName,
            number:      wa.targetNumber,
            message:     waMsg
          })
        });
      }
    } catch (_) { /* silent — WA failure should not affect UX */ }

    // Reset UI
    btn.disabled = false;
    btn.querySelector('.btn-text').textContent = 'Send Message';
    btn.style.opacity = '1';
    form.reset();
    generateCaptcha(); // fresh question for next visitor
    success.classList.add('show');
    setTimeout(() => success.classList.remove('show'), 5000);
  });

  // Real-time validation clearance
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
  btn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
})();

/* ─── 14. FOOTER YEAR ─── */
document.getElementById('footerYear').textContent = new Date().getFullYear();

/* ─── 15. AOS INIT + DEFERRED INITS (after renderer) ─── */
document.addEventListener('DOMContentLoaded', () => {
  if (typeof AOS !== 'undefined') {
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
});

/* ─── 16. SMOOTH SECTION SCROLL FOR ALL NAV LINKS ─── */
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', (e) => {
    const target = document.querySelector(anchor.getAttribute('href'));
    if (target) {
      e.preventDefault();
      target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  });
});

/* ─── 17. SCROLL PROGRESS BAR ─── */
(function initScrollProgress() {
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
