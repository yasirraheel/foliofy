<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Muhammad Asif Shabbir | Network Support Engineer</title>
  <meta name="description" content="Professional portfolio of Muhammad Asif Shabbir, a networking-focused IT professional with Pakistan Army leadership experience, CCNA in progress, Packet Tracer lab practice, and supporting web and Android development skills." />
  <meta name="keywords" content="Muhammad Asif Shabbir, Network Support Engineer, Junior Network Engineer, NOC Engineer, IT Support, CCNA in progress, Networking, Router and Switch Configuration, Troubleshooting, VLAN, OSPF, DHCP, NAT, ACL, Packet Tracer, Technical Instructor, Military Leadership, Web Development, Android Development" />
  <meta name="author" content="Muhammad Asif Shabbir" />
  <link rel="canonical" href="https://foliofy.me/" />

  <meta property="og:type" content="website" />
  <meta property="og:url" content="https://foliofy.me/" />
  <meta property="og:title" content="Muhammad Asif Shabbir | Network Support Engineer" />
  <meta property="og:description" content="Networking-focused IT professional with Pakistan Army leadership experience, CCNA in progress, and practical Packet Tracer lab exposure." />
  <meta property="og:image" content="https://foliofy.me/profile.png?v=3" />

  <meta property="twitter:card" content="summary_large_image" />
  <meta property="twitter:url" content="https://foliofy.me/" />
  <meta property="twitter:title" content="Muhammad Asif Shabbir | Network Support Engineer" />
  <meta property="twitter:description" content="Networking-focused IT professional with Pakistan Army leadership experience, CCNA in progress, and practical Packet Tracer lab exposure." />
  <meta property="twitter:image" content="https://foliofy.me/profile.png?v=3" />

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet" />
  <script>
    (() => {
      try {
        const savedTheme = localStorage.getItem('portfolioThemePreference') || localStorage.getItem('portfolioTheme');
        if (savedTheme === 'light' || savedTheme === 'dark') {
          document.documentElement.setAttribute('data-theme', savedTheme);
        }
      } catch (_) {}
    })();
  </script>
  <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <link rel="stylesheet" href="style.css?v=13" />
</head>
<body>
  <div class="cursor-outer" id="cursorOuter"></div>
  <div class="cursor-inner" id="cursorInner"></div>

  <div class="page-loader" id="pageLoader">
    <div class="loader-ring-outer">
      <div class="loader-ring-inner">
        <span class="loader-bracket">&lt;</span><span class="logo-name">MAS</span><span class="loader-bracket">/&gt;</span>
      </div>
      <div id="loaderOrbitContainer"></div>
    </div>
    <p class="loader-tagline">Loading portfolio...</p>
  </div>

  <nav class="navbar" id="navbar">
    <div class="nav-container">
      <a href="/" class="nav-logo" id="navLogo">
        <span class="logo-bracket">&lt;</span>
        <span class="logo-name">MAS</span>
        <span class="logo-bracket">/&gt;</span>
      </a>

      <ul class="nav-links" id="navLinks">
        <li><a href="#home" class="nav-link active" data-section="home">Home</a></li>
        <li><a href="#about" class="nav-link" data-section="about">About</a></li>
        <li><a href="#skills" class="nav-link" data-section="skills">Skills</a></li>
        <li><a href="#projects" class="nav-link" data-section="projects">Projects</a></li>
        <li><a href="#experience" class="nav-link" data-section="experience">Background</a></li>
        <li><a href="#achievements" class="nav-link" data-section="achievements">Training</a></li>
        <li><a href="#education" class="nav-link" data-section="education">Education</a></li>
        <li><a href="#languages" class="nav-link" data-section="languages">Languages</a></li>
        <li><a href="#contact" class="nav-link" data-section="contact">Contact</a></li>
      </ul>

      <div class="nav-actions">
        <button class="theme-toggle" id="themeToggle" aria-label="Toggle theme">
          <i class="fas fa-sun" id="themeIcon"></i>
        </button>
        <a href="#contact" class="btn btn-primary btn-sm">Contact Me</a>
        <button class="hamburger" id="hamburger" aria-label="Menu">
          <span></span><span></span><span></span>
        </button>
      </div>
    </div>
  </nav>

  <div class="mobile-nav-overlay" id="mobileNavOverlay">
    <ul class="mobile-nav-links">
      <li><a href="#home" class="mobile-nav-link">Home</a></li>
      <li><a href="#about" class="mobile-nav-link">About</a></li>
      <li><a href="#skills" class="mobile-nav-link">Skills</a></li>
      <li><a href="#projects" class="mobile-nav-link">Projects</a></li>
      <li><a href="#experience" class="mobile-nav-link">Background</a></li>
      <li><a href="#achievements" class="mobile-nav-link">Training</a></li>
      <li><a href="#education" class="mobile-nav-link">Education</a></li>
      <li><a href="#languages" class="mobile-nav-link">Languages</a></li>
      <li><a href="#contact" class="mobile-nav-link">Contact</a></li>
    </ul>
  </div>

  <section class="hero" id="home">
    <canvas class="hero-canvas" id="heroCanvas"></canvas>

    <div class="hero-container">
      <div class="hero-content">
        <div class="hero-tag" data-aos="fade-down" data-aos-delay="200">
          <span class="status-dot pulse"></span>
          Open to Networking Opportunities
        </div>

        <h1 class="hero-title" data-aos="fade-up" data-aos-delay="300">
          Muhammad Asif <span class="gradient-text">Shabbir</span>
        </h1>

        <div class="hero-subtitle" data-aos="fade-up" data-aos-delay="400">
          <span class="static-text">Focused on </span>
          <span class="typed-text" id="typedText"></span>
          <span class="cursor-blink">|</span>
        </div>

        <p class="hero-desc" data-aos="fade-up" data-aos-delay="500">
          Retired Pakistan Army professional transitioning into civilian IT as a <strong>Network Support Engineer</strong>, with <strong>CCNA in preparation</strong>, practical <strong>Packet Tracer lab experience</strong>, and supporting skills in web development, Android development, and AI-assisted project building.
        </p>

        <div class="hero-actions hero-actions--stacked" data-aos="fade-up" data-aos-delay="600">
          <a href="#contact" class="btn btn-outline" id="heroResumeBtn">
            <i class="fas fa-file-lines"></i>
            <span>View CV</span>
          </a>
          <a href="#contact" class="btn btn-outline" id="heroDownloadCvBtn">
            <i class="fas fa-download"></i>
            <span>Download CV</span>
          </a>
          <a href="#contact" class="btn btn-primary" id="heroContactBtn">
            <span>Contact Me</span>
            <i class="fas fa-arrow-right"></i>
          </a>
          <a href="https://www.linkedin.com/in/asif-shabbiir/" class="btn btn-outline" id="heroLinkedinBtn" target="_blank" rel="noopener noreferrer">
            <i class="fab fa-linkedin-in"></i>
            <span>LinkedIn</span>
          </a>
          <a href="#projects" class="btn btn-outline" id="heroProjectsBtn">
            <i class="fas fa-folder-open"></i>
            <span>View Projects</span>
          </a>
        </div>

        <div class="hero-stats" data-aos="fade-up" data-aos-delay="700">
          <div class="stat-item">
            <span class="stat-number" data-target="23">0</span>
            <span class="stat-label">Years of Service</span>
          </div>
          <div class="stat-divider"></div>
          <div class="stat-item">
            <span class="stat-number" data-target="2">0</span>
            <span class="stat-label">COAS Commendations</span>
          </div>
          <div class="stat-divider"></div>
          <div class="stat-item">
            <span class="stat-number" data-target="4">0</span>
            <span class="stat-label">Target Markets</span>
          </div>
        </div>
      </div>

      <div class="hero-visual" data-aos="fade-left" data-aos-delay="400">
        <div class="profile-ring-outer">
          <div class="orbit-skill-popup" id="orbitSkillPopup" aria-hidden="true">
            <div class="orbit-skill-popup__icon" id="orbitSkillPopupIcon">
              <i class="fas fa-code"></i>
            </div>
            <div class="orbit-skill-popup__content">
              <span class="orbit-skill-popup__eyebrow">Portfolio Skill</span>
              <div class="orbit-skill-popup__headline">
                <h3 id="orbitSkillPopupTitle">Skill</h3>
                <span class="orbit-skill-popup__level" id="orbitSkillPopupLevel">100%</span>
              </div>
              <div class="orbit-skill-popup__meter">
                <span id="orbitSkillPopupMeter"></span>
              </div>
            </div>
          </div>
          <div class="profile-ring-inner">
            <div class="profile-image-wrap">
              <img src="profile.png" alt="Muhammad Asif Shabbir" class="profile-img" />
            </div>
          </div>
          <div id="orbitBadgesContainer"></div>
        </div>
      </div>
    </div>

    <div class="scroll-indicator">
      <div class="scroll-mouse">
        <div class="scroll-wheel"></div>
      </div>
      <span>Scroll Down</span>
    </div>
  </section>

  <section class="section about" id="about">
    <div class="container">
      <div class="section-header" data-aos="fade-up">
        <span class="section-tag">About Me</span>
        <h2 class="section-title">Professional <span class="gradient-text">Profile</span></h2>
        <p class="section-desc">Military discipline, instructor experience, and a networking-focused IT transition.</p>
      </div>

      <div class="about-grid">
        <div class="about-visual" data-aos="fade-right" data-aos-delay="100">
          <div class="about-image-card">
            <img src="profile.png" alt="Muhammad Asif Shabbir profile" class="about-img" />
            <div class="about-exp-badge">
              <span class="exp-number">23+</span>
              <span class="exp-label">Years of Service</span>
            </div>
          </div>
          <div class="about-code-snippet">
            <div class="code-header">
              <span class="code-dot red"></span>
              <span class="code-dot yellow"></span>
              <span class="code-dot green"></span>
              <span class="code-filename">profile.js</span>
            </div>
            <pre class="code-body"><code><span class="code-kw">const</span> <span class="code-var">profile</span> = {
  <span class="code-key">role</span>: <span class="code-str">"Network Support Engineer"</span>,
  <span class="code-key">focus</span>: <span class="code-str">"CCNA in progress"</span>,
  <span class="code-key">labs</span>: <span class="code-str">"Packet Tracer"</span>,
  <span class="code-key">openTo</span>: <span class="code-str">"Pakistan, UAE, KSA, Europe"</span>,
  <span class="code-key">supportingSkills</span>: <span class="code-str">"Web + Android + AI-assisted building"</span>
};</code></pre>
          </div>
        </div>

        <div class="about-content" data-aos="fade-left" data-aos-delay="200">
          <h3 class="about-heading">
            Retired Army Professional & <span class="gradient-text">Networking-Focused IT Candidate</span>
          </h3>
          <p class="about-text">
            I am a retired Pakistan Army professional with service from <strong>28 Apr 2003 to 28 Apr 2026</strong>, including instructor duties, leadership exposure, operational responsibility, and structured performance-focused work.
          </p>
          <p class="about-text">
            My primary career direction is <strong>Networking</strong>. I am currently preparing for <strong>CCNA</strong> and building strong practical lab experience in <strong>Packet Tracer</strong>, while also maintaining supporting skills in web development, Android development, and AI-assisted project building.
          </p>

          <div class="about-info-grid">
            <div class="info-item">
              <i class="fas fa-user"></i>
              <div>
                <span class="info-label">Name</span>
                <span class="info-value">Muhammad Asif Shabbir</span>
              </div>
            </div>
            <div class="info-item">
              <i class="fas fa-envelope"></i>
              <div>
                <span class="info-label">Email</span>
                <span class="info-value">islammujahid921@gmail.com</span>
              </div>
            </div>
            <div class="info-item">
              <i class="fas fa-map-marker-alt"></i>
              <div>
                <span class="info-label">Location</span>
                <span class="info-value">Bahawalpur, Punjab, Pakistan</span>
              </div>
            </div>
            <div class="info-item">
              <i class="fas fa-graduation-cap"></i>
              <div>
                <span class="info-label">Education</span>
                <span class="info-value">BS IT - In Progress</span>
              </div>
            </div>
          </div>

          <div class="about-actions">
            <a href="#contact" class="btn btn-primary">Contact Me</a>
            <a href="#contact" class="btn btn-outline" id="aboutResumeLink">View CV <i class="fas fa-file-lines"></i></a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="section skills" id="skills">
    <div class="container">
      <div class="section-header" data-aos="fade-up">
        <span class="section-tag">Core Competencies</span>
        <h2 class="section-title">Skills & <span class="gradient-text">Capabilities</span></h2>
        <p class="section-desc">Networking first, with web, Android, productivity, and professional strengths supporting the profile.</p>
      </div>

      <div class="skills-tabs" id="skillsTabs" data-aos="fade-up" data-aos-delay="100"></div>
      <div id="skillsPanels" data-aos="fade-up" data-aos-delay="200"></div>
    </div>
  </section>

  <section class="section projects" id="projects">
    <div class="container">
      <div class="section-header" data-aos="fade-up">
        <span class="section-tag">Projects</span>
        <h2 class="section-title">Portfolio <span class="gradient-text">Projects</span></h2>
        <p class="section-desc">Existing project work retained as part of the portfolio.</p>
      </div>

      <div class="project-filters" data-aos="fade-up" data-aos-delay="100">
        <button class="filter-btn active" data-filter="all">All</button>
        <button class="filter-btn" data-filter="fullstack">Full Stack</button>
        <button class="filter-btn" data-filter="frontend">Frontend</button>
        <button class="filter-btn" data-filter="mobile">Mobile</button>
      </div>

      <div class="projects-grid" id="projectsGrid"></div>

      <div class="projects-show-more-bar" id="projectsShowMoreBar" style="display:none">
        <button class="btn btn-outline" id="projectsShowMoreBtn">
          <span id="projectsShowMoreLabel">Show More</span>
          <i class="fas fa-chevron-down"></i>
        </button>
      </div>
    </div>
  </section>

  <section class="section experience" id="experience">
    <div class="container">
      <div class="section-header" data-aos="fade-up">
        <span class="section-tag">Professional Background</span>
        <h2 class="section-title">Military & Technical <span class="gradient-text">Background</span></h2>
        <p class="section-desc">Transferable experience presented in recruiter-friendly, civilian-facing language.</p>
      </div>

      <div class="timeline-container"></div>
    </div>
  </section>

  <section class="section achievements" id="achievements">
    <div class="container">
      <div class="section-header" data-aos="fade-up">
        <span class="section-tag">Achievements</span>
        <h2 class="section-title">Training & <span class="gradient-text">Honors</span></h2>
        <p class="section-desc">Formal military training, course positions, certificates, and commendations.</p>
      </div>

      <div class="achievement-grid" id="achievementsGrid"></div>
    </div>
  </section>

  <section class="section education" id="education">
    <div class="container">
      <div class="section-header" data-aos="fade-up">
        <span class="section-tag">Education & Certifications</span>
        <h2 class="section-title">Learning & <span class="gradient-text">Preparation</span></h2>
        <p class="section-desc">Academic progress and certification preparation aligned with networking roles.</p>
      </div>

      <div class="credential-grid" id="educationGrid"></div>
    </div>
  </section>

  <section class="section languages" id="languages">
    <div class="container">
      <div class="section-header" data-aos="fade-up">
        <span class="section-tag">Languages</span>
        <h2 class="section-title">Communication <span class="gradient-text">Profile</span></h2>
        <p class="section-desc">Language strengths for professional and regional opportunities.</p>
      </div>

      <div class="language-grid" id="languagesGrid"></div>
    </div>
  </section>

  <section class="section contact" id="contact">
    <div class="container">
      <div class="section-header" data-aos="fade-up">
        <span class="section-tag">Contact</span>
        <h2 class="section-title">Let's <span class="gradient-text">Connect</span></h2>
        <p class="section-desc">Open to networking, IT support, and infrastructure-focused opportunities.</p>
      </div>

      <div class="contact-grid">
        <div class="contact-info" data-aos="fade-right" data-aos-delay="100">
          <h3 class="contact-heading">Let's connect for networking and IT support opportunities</h3>
          <p class="contact-subtext">I am open to networking, NOC, IT support, and technical infrastructure opportunities in Pakistan, UAE, KSA, and Europe.</p>

          <div class="contact-cards">
            <a href="mailto:islammujahid921@gmail.com" class="contact-card-item" id="contactEmail">
              <div class="contact-card-icon"><i class="fas fa-envelope"></i></div>
              <div>
                <span class="contact-card-label">Email</span>
                <span class="contact-card-value">islammujahid921@gmail.com</span>
              </div>
            </a>
            <a href="tel:03006859611" class="contact-card-item" id="contactPhone">
              <div class="contact-card-icon"><i class="fas fa-phone"></i></div>
              <div>
                <span class="contact-card-label">Phone</span>
                <span class="contact-card-value">03006859611</span>
              </div>
            </a>
            <div class="contact-card-item" id="contactLocation">
              <div class="contact-card-icon"><i class="fas fa-map-marker-alt"></i></div>
              <div>
                <span class="contact-card-label">Location</span>
                <span class="contact-card-value">Bahawalpur, Punjab, Pakistan</span>
              </div>
            </div>
            <a href="https://foliofy.me/" class="contact-card-item" id="contactPortfolio" target="_blank" rel="noopener noreferrer">
              <div class="contact-card-icon"><i class="fas fa-globe"></i></div>
              <div>
                <span class="contact-card-label">Portfolio</span>
                <span class="contact-card-value">foliofy.me</span>
              </div>
            </a>
            <a href="#contact" class="contact-card-item" id="contactResume">
              <div class="contact-card-icon"><i class="fas fa-file-lines"></i></div>
              <div>
                <span class="contact-card-label">View CV</span>
                <span class="contact-card-value">Need user input</span>
              </div>
            </a>
            <a href="#contact" class="contact-card-item" id="contactResumeDownload">
              <div class="contact-card-icon"><i class="fas fa-download"></i></div>
              <div>
                <span class="contact-card-label">Download CV</span>
                <span class="contact-card-value">Need user input</span>
              </div>
            </a>
          </div>

          <div class="social-links">
            <a href="#" class="social-link" id="socialGithub" aria-label="GitHub"><i class="fab fa-github"></i></a>
            <a href="https://www.linkedin.com/in/asif-shabbiir/" class="social-link" id="socialLinkedin" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
            <a href="#" class="social-link" id="socialTwitter" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
            <a href="#" class="social-link" id="socialDribbble" aria-label="Dribbble"><i class="fab fa-dribbble"></i></a>
            <a href="#" class="social-link" id="socialInstagram" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
          </div>
        </div>

        <div class="contact-form-wrap" data-aos="fade-left" data-aos-delay="200">
          <form class="contact-form" id="contactForm" novalidate>
            <div class="form-row">
              <div class="form-group">
                <label for="contactName">Full Name</label>
                <div class="input-wrap">
                  <i class="fas fa-user"></i>
                  <input type="text" id="contactName" name="name" placeholder="John Doe" required />
                </div>
              </div>
              <div class="form-group">
                <label for="contactEmailInput">Email Address</label>
                <div class="input-wrap">
                  <i class="fas fa-envelope"></i>
                  <input type="email" id="contactEmailInput" name="email" placeholder="john@example.com" required />
                </div>
              </div>
            </div>
            <div class="form-group">
              <label for="contactSubject">Subject</label>
              <div class="input-wrap">
                <i class="fas fa-tag"></i>
                <input type="text" id="contactSubject" name="subject" placeholder="Opportunity or project inquiry" required />
              </div>
            </div>
            <div class="form-group">
              <label for="contactMessage">Message</label>
              <div class="input-wrap textarea-wrap">
                <i class="fas fa-comment-alt"></i>
                <textarea id="contactMessage" name="message" rows="5" placeholder="Tell me about the role, project, or opportunity..." required></textarea>
              </div>
            </div>

            <div class="form-group captcha-group">
              <label>Quick Verification <span class="captcha-hint">Prove you're human</span></label>
              <div class="captcha-wrap">
                <div class="captcha-question" id="captchaQuestion">
                  <span id="captchaNum1"></span>
                  <span class="captcha-op" id="captchaOp"></span>
                  <span id="captchaNum2"></span>
                  <span class="captcha-eq">=</span>
                </div>
                <div class="input-wrap captcha-input-wrap">
                  <i class="fas fa-calculator"></i>
                  <input type="number" id="captchaAnswer" placeholder="Your answer" autocomplete="off" />
                </div>
                <button type="button" class="captcha-refresh" id="captchaRefresh" title="New question">
                  <i class="fas fa-sync-alt"></i>
                </button>
              </div>
              <p class="captcha-error" id="captchaError" style="display:none">Wrong answer - try the new question.</p>
            </div>

            <button type="submit" class="btn btn-primary btn-full" id="submitBtn">
              <span class="btn-text">Send Message</span>
              <i class="fas fa-paper-plane"></i>
            </button>
            <div class="form-success" id="formSuccess">
              <i class="fas fa-check-circle"></i>
              <span>Message sent successfully. I will get back to you soon.</span>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>

  <footer class="footer">
    <div class="container">
      <div class="footer-grid">
        <div class="footer-brand">
          <a href="#home" class="nav-logo">
            <span class="logo-bracket">&lt;</span>
            <span class="logo-name">MAS</span>
            <span class="logo-bracket">/&gt;</span>
          </a>
          <p class="footer-tagline">Networking-focused IT professional with disciplined military leadership, technical instruction experience, and practical lab-based learning.</p>
          <div class="social-links" id="footerSocialLinks"></div>
        </div>

        <div class="footer-links-col">
          <h4 class="footer-col-title">Navigation</h4>
          <ul>
            <li><a href="#home">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#skills">Skills</a></li>
            <li><a href="#projects">Projects</a></li>
          </ul>
        </div>

        <div class="footer-links-col">
          <h4 class="footer-col-title">Profile</h4>
          <ul>
            <li><a href="#experience">Background</a></li>
            <li><a href="#achievements">Training</a></li>
            <li><a href="#education">Education</a></li>
            <li><a href="#languages">Languages</a></li>
          </ul>
        </div>

        <div class="footer-newsletter">
          <h4 class="footer-col-title">Direct Links</h4>
          <p>Use the portfolio, LinkedIn, or CV links to continue the conversation.</p>
          <div class="footer-link-stack">
            <a href="https://foliofy.me/" class="btn btn-outline btn-full" id="footerPortfolioLink" target="_blank" rel="noopener noreferrer">Open Portfolio</a>
            <a href="https://www.linkedin.com/in/asif-shabbiir/" class="btn btn-outline btn-full" id="footerLinkedinLink" target="_blank" rel="noopener noreferrer">LinkedIn Profile</a>
            <a href="#contact" class="btn btn-outline btn-full" id="footerResumeLink">View CV</a>
            <a href="#contact" class="btn btn-outline btn-full" id="footerDownloadCvLink">Download CV</a>
          </div>
        </div>
      </div>

      <div class="footer-bottom">
        <p>&copy; <span id="footerYear"></span> Muhammad Asif Shabbir. All rights reserved.</p>
        <p>Built to support networking, IT support, and infrastructure opportunities.</p>
      </div>
    </div>
  </footer>

  <button class="back-to-top" id="backToTop" aria-label="Back to top">
    <i class="fas fa-chevron-up"></i>
  </button>

  <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <script src="data.js?v=13"></script>
  <script src="renderer.js?v=13"></script>
  <script src="script.js?v=13"></script>
</body>
</html>
