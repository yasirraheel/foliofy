<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Muhammad Asif Shabbir | Full-Stack Developer & Designer</title>
  <meta name="description" content="Professional portfolio of Muhammad Asif Shabbir — Full-Stack Developer specializing in Laravel, PHP, JavaScript, React & Android development." />
  <meta name="keywords" content="Muhammad Asif, Full-Stack Developer, Laravel, PHP, JavaScript, React, Android Developer, Backend, Portfolio" />
  <meta name="author" content="Muhammad Asif Shabbir" />
  <link rel="canonical" href="https://foliofy.me/" />

  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website" />
  <meta property="og:url" content="https://foliofy.me/" />
  <meta property="og:title" content="Muhammad Asif Shabbir | Full-Stack Developer & Designer" />
  <meta property="og:description" content="A passionate Full-Stack Developer with expertise in Laravel, PHP, JavaScript, and Android — building scalable, real-world products that make an impact." />
  <meta property="og:image" content="https://foliofy.me/profile.png?v=3" />

  <!-- Twitter -->
  <meta property="twitter:card" content="summary_large_image" />
  <meta property="twitter:url" content="https://foliofy.me/" />
  <meta property="twitter:title" content="Muhammad Asif Shabbir | Full-Stack Developer & Designer" />
  <meta property="twitter:description" content="A passionate Full-Stack Developer with expertise in Laravel, PHP, JavaScript, and Android — building scalable, real-world products that make an impact." />
  <meta property="twitter:image" content="https://foliofy.me/profile.png?v=3" />
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet" />

  <!-- AOS Animation Library -->
  <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css" />

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

  <!-- Swiper CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

  <!-- Stylesheet -->
  <link rel="stylesheet" href="style.css" />
</head>
<body>

  <!-- ═══════════════════════════════════════ CURSOR ═══════════════════════════════════════ -->
  <div class="cursor-outer" id="cursorOuter"></div>
  <div class="cursor-inner" id="cursorInner"></div>

  <!-- ═══════════════════════════════════════ LOADER ═══════════════════════════════════════ -->
  <div class="page-loader" id="pageLoader">
    <div class="loader-ring-outer">
      <div class="loader-ring-inner">
        <span class="loader-bracket">&lt;</span><span class="logo-name">AM</span><span class="loader-bracket">/&gt;</span>
      </div>
      <!-- Skill icon badges injected by renderer.js -->
      <div id="loaderOrbitContainer"></div>
    </div>
    <p class="loader-tagline">Loading portfolio&hellip;</p>
  </div>

  <!-- ═══════════════════════════════════════ NAVBAR ═══════════════════════════════════════ -->
  <nav class="navbar" id="navbar">
    <div class="nav-container">
      <a href="/" class="nav-logo" id="navLogo">
        <span class="logo-bracket">&lt;</span>
        <span class="logo-name">AM</span>
        <span class="logo-bracket">/&gt;</span>
      </a>

      <ul class="nav-links" id="navLinks">
        <li><a href="/" class="nav-link active" data-section="home">Home</a></li>
        <li><a href="#about" class="nav-link" data-section="about">About</a></li>
        <li><a href="#skills" class="nav-link" data-section="skills">Skills</a></li>
        <li><a href="#projects" class="nav-link" data-section="projects">Projects</a></li>
        <li><a href="#experience" class="nav-link" data-section="experience">Experience</a></li>
        <li><a href="#testimonials" class="nav-link" data-section="testimonials">Testimonials</a></li>
        <li><a href="#contact" class="nav-link" data-section="contact">Contact</a></li>
      </ul>

      <div class="nav-actions">
        <button class="theme-toggle" id="themeToggle" aria-label="Toggle theme">
          <i class="fas fa-sun" id="themeIcon"></i>
        </button>
        <a href="#contact" class="btn btn-primary btn-sm">Hire Me</a>
        <button class="hamburger" id="hamburger" aria-label="Menu">
          <span></span><span></span><span></span>
        </button>
      </div>
    </div>
  </nav>

  <!-- Mobile Nav Overlay -->
  <div class="mobile-nav-overlay" id="mobileNavOverlay">
    <ul class="mobile-nav-links">
      <li><a href="/" class="mobile-nav-link">Home</a></li>
      <li><a href="#about" class="mobile-nav-link">About</a></li>
      <li><a href="#skills" class="mobile-nav-link">Skills</a></li>
      <li><a href="#projects" class="mobile-nav-link">Projects</a></li>
      <li><a href="#experience" class="mobile-nav-link">Experience</a></li>
      <li><a href="#testimonials" class="mobile-nav-link">Testimonials</a></li>
      <li><a href="#contact" class="mobile-nav-link">Contact</a></li>
    </ul>
  </div>

  <!-- ═══════════════════════════════════════ HERO ═══════════════════════════════════════ -->
  <section class="hero" id="home">
    <canvas class="hero-canvas" id="heroCanvas"></canvas>

    <div class="hero-container">
      <div class="hero-content">
        <div class="hero-tag" data-aos="fade-down" data-aos-delay="200">
          <span class="status-dot pulse"></span>
          Available for Work
        </div>

        <h1 class="hero-title" data-aos="fade-up" data-aos-delay="300">
          Hi, I'm <span class="gradient-text">Muhammad Asif</span>
        </h1>

        <div class="hero-subtitle" data-aos="fade-up" data-aos-delay="400">
          <span class="static-text">I build </span>
          <span class="typed-text" id="typedText"></span>
          <span class="cursor-blink">|</span>
        </div>

        <p class="hero-desc" data-aos="fade-up" data-aos-delay="500">
          A passionate <strong>Full-Stack Developer & UI/UX Designer</strong> crafting pixel-perfect,
          high-performance digital experiences that make an impact.
        </p>

        <div class="hero-actions" data-aos="fade-up" data-aos-delay="600">
          <a href="#projects" class="btn btn-primary">
            <span>View My Work</span>
            <i class="fas fa-arrow-right"></i>
          </a>
          <a href="#" class="btn btn-outline" id="downloadCV">
            <i class="fas fa-download"></i>
            <span>Download CV</span>
          </a>
        </div>

        <div class="hero-stats" data-aos="fade-up" data-aos-delay="700">
          <div class="stat-item">
            <span class="stat-number" data-target="5">0</span><span class="stat-plus">+</span>
            <span class="stat-label">Years Exp.</span>
          </div>
          <div class="stat-divider"></div>
          <div class="stat-item">
            <span class="stat-number" data-target="80">0</span><span class="stat-plus">+</span>
            <span class="stat-label">Projects Done</span>
          </div>
          <div class="stat-divider"></div>
          <div class="stat-item">
            <span class="stat-number" data-target="40">0</span><span class="stat-plus">+</span>
            <span class="stat-label">Happy Clients</span>
          </div>
        </div>
      </div>

      <div class="hero-visual" data-aos="fade-left" data-aos-delay="400">
        <div class="profile-ring-outer">
          <div class="profile-ring-inner">
            <div class="profile-image-wrap">
              <img src="profile.png" alt="Muhammad Asif - Developer" class="profile-img" />
            </div>
          </div>
          <!-- Floating tech badges — dynamically injected by renderer.js -->
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

  <!-- ═══════════════════════════════════════ ABOUT ═══════════════════════════════════════ -->
  <section class="section about" id="about">
    <div class="container">
      <div class="section-header" data-aos="fade-up">
        <span class="section-tag">Who I Am</span>
        <h2 class="section-title">About <span class="gradient-text">Me</span></h2>
        <p class="section-desc">The story behind the code</p>
      </div>

      <div class="about-grid">
        <div class="about-visual" data-aos="fade-right" data-aos-delay="100">
          <div class="about-image-card">
            <img src="profile.png" alt="About Muhammad Asif" class="about-img" />
            <div class="about-exp-badge">
              <span class="exp-number">5+</span>
              <span class="exp-label">Years of Experience</span>
            </div>
          </div>
          <div class="about-code-snippet">
            <div class="code-header">
              <span class="code-dot red"></span>
              <span class="code-dot yellow"></span>
              <span class="code-dot green"></span>
              <span class="code-filename">about.js</span>
            </div>
            <pre class="code-body"><code><span class="code-kw">const</span> <span class="code-var">developer</span> = {
  <span class="code-key">name</span>: <span class="code-str">"Muhammad Asif"</span>,
  <span class="code-key">role</span>: <span class="code-str">"Full-Stack Dev"</span>,
  <span class="code-key">location</span>: <span class="code-str">"New York, USA"</span>,
  <span class="code-key">passion</span>: <span class="code-str">"Building Dreams"</span>,
  <span class="code-key">open</span>: <span class="code-bool">true</span>
};</code></pre>
          </div>
        </div>

        <div class="about-content" data-aos="fade-left" data-aos-delay="200">
          <h3 class="about-heading">
            Full-Stack Developer & <span class="gradient-text">Creative Thinker</span>
          </h3>
          <p class="about-text">
            I'm a passionate developer with <strong>5+ years</strong> of experience creating elegant, scalable,
            and user-centric web applications. I specialize in turning complex problems into simple, beautiful,
            and intuitive solutions.
          </p>
          <p class="about-text">
            My journey started with a curiosity for how the web works, and it evolved into a deep love for
            crafting experiences that are both technically robust and visually stunning. I believe great
            software is where logic meets art.
          </p>

          <div class="about-info-grid">
            <div class="info-item">
              <i class="fas fa-user"></i>
              <div>
                <span class="info-label">Name</span>
                <span class="info-value">Muhammad Asif</span>
              </div>
            </div>
            <div class="info-item">
              <i class="fas fa-envelope"></i>
              <div>
                <span class="info-label">Email</span>
                <span class="info-value">alex@example.com</span>
              </div>
            </div>
            <div class="info-item">
              <i class="fas fa-map-marker-alt"></i>
              <div>
                <span class="info-label">Location</span>
                <span class="info-value">New York, USA</span>
              </div>
            </div>
            <div class="info-item">
              <i class="fas fa-graduation-cap"></i>
              <div>
                <span class="info-label">Degree</span>
                <span class="info-value">B.Sc. Computer Science</span>
              </div>
            </div>
          </div>

          <div class="about-actions">
            <a href="#contact" class="btn btn-primary">Let's Talk</a>
            <a href="#" class="btn btn-outline">Download CV <i class="fas fa-download"></i></a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ═══════════════════════════════════════ SKILLS ═══════════════════════════════════════ -->
  <section class="section skills" id="skills">
    <div class="container">
      <div class="section-header" data-aos="fade-up">
        <span class="section-tag">What I Know</span>
        <h2 class="section-title">My <span class="gradient-text">Skills</span></h2>
        <p class="section-desc">Technologies I work with daily</p>
      </div>

      <div class="skills-tabs" data-aos="fade-up" data-aos-delay="100">
        <button class="skills-tab active" data-tab="frontend" id="tabFrontend">Frontend</button>
        <button class="skills-tab" data-tab="backend" id="tabBackend">Backend</button>
        <button class="skills-tab" data-tab="tools" id="tabTools">Tools & Design</button>
      </div>

      <div class="skills-content active" id="tabContentFrontend" data-aos="fade-up" data-aos-delay="200">
        <div class="skills-grid">
          <div class="skill-card">
            <div class="skill-icon"><i class="fab fa-html5" style="color:#e34f26"></i></div>
            <span class="skill-name">HTML5</span>
            <div class="skill-bar-wrap"><div class="skill-bar" data-width="95"></div></div>
            <span class="skill-percent">95%</span>
          </div>
          <div class="skill-card">
            <div class="skill-icon"><i class="fab fa-css3-alt" style="color:#1572b6"></i></div>
            <span class="skill-name">CSS3 / SCSS</span>
            <div class="skill-bar-wrap"><div class="skill-bar" data-width="90"></div></div>
            <span class="skill-percent">90%</span>
          </div>
          <div class="skill-card">
            <div class="skill-icon"><i class="fab fa-js-square" style="color:#f7df1e"></i></div>
            <span class="skill-name">JavaScript</span>
            <div class="skill-bar-wrap"><div class="skill-bar" data-width="92"></div></div>
            <span class="skill-percent">92%</span>
          </div>
          <div class="skill-card">
            <div class="skill-icon"><i class="fab fa-react" style="color:#61dafb"></i></div>
            <span class="skill-name">React.js</span>
            <div class="skill-bar-wrap"><div class="skill-bar" data-width="88"></div></div>
            <span class="skill-percent">88%</span>
          </div>
          <div class="skill-card">
            <div class="skill-icon"><i class="fab fa-vuejs" style="color:#42b883"></i></div>
            <span class="skill-name">Vue.js</span>
            <div class="skill-bar-wrap"><div class="skill-bar" data-width="75"></div></div>
            <span class="skill-percent">75%</span>
          </div>
          <div class="skill-card">
            <div class="skill-icon"><i class="fas fa-wind" style="color:#38bdf8"></i></div>
            <span class="skill-name">Tailwind CSS</span>
            <div class="skill-bar-wrap"><div class="skill-bar" data-width="85"></div></div>
            <span class="skill-percent">85%</span>
          </div>
        </div>
      </div>

      <div class="skills-content" id="tabContentBackend" data-aos="fade-up" data-aos-delay="200">
        <div class="skills-grid">
          <div class="skill-card">
            <div class="skill-icon"><i class="fab fa-node-js" style="color:#339933"></i></div>
            <span class="skill-name">Node.js</span>
            <div class="skill-bar-wrap"><div class="skill-bar" data-width="88"></div></div>
            <span class="skill-percent">88%</span>
          </div>
          <div class="skill-card">
            <div class="skill-icon"><i class="fab fa-python" style="color:#3776ab"></i></div>
            <span class="skill-name">Python</span>
            <div class="skill-bar-wrap"><div class="skill-bar" data-width="82"></div></div>
            <span class="skill-percent">82%</span>
          </div>
          <div class="skill-card">
            <div class="skill-icon"><i class="fas fa-database" style="color:#4479a1"></i></div>
            <span class="skill-name">MySQL / PostgreSQL</span>
            <div class="skill-bar-wrap"><div class="skill-bar" data-width="80"></div></div>
            <span class="skill-percent">80%</span>
          </div>
          <div class="skill-card">
            <div class="skill-icon"><i class="fas fa-leaf" style="color:#47a248"></i></div>
            <span class="skill-name">MongoDB</span>
            <div class="skill-bar-wrap"><div class="skill-bar" data-width="85"></div></div>
            <span class="skill-percent">85%</span>
          </div>
          <div class="skill-card">
            <div class="skill-icon"><i class="fas fa-server" style="color:#ff6b35"></i></div>
            <span class="skill-name">REST APIs</span>
            <div class="skill-bar-wrap"><div class="skill-bar" data-width="90"></div></div>
            <span class="skill-percent">90%</span>
          </div>
          <div class="skill-card">
            <div class="skill-icon"><i class="fab fa-docker" style="color:#2496ed"></i></div>
            <span class="skill-name">Docker</span>
            <div class="skill-bar-wrap"><div class="skill-bar" data-width="72"></div></div>
            <span class="skill-percent">72%</span>
          </div>
        </div>
      </div>

      <div class="skills-content" id="tabContentTools" data-aos="fade-up" data-aos-delay="200">
        <div class="skills-grid">
          <div class="skill-card">
            <div class="skill-icon"><i class="fab fa-figma" style="color:#f24e1e"></i></div>
            <span class="skill-name">Figma</span>
            <div class="skill-bar-wrap"><div class="skill-bar" data-width="88"></div></div>
            <span class="skill-percent">88%</span>
          </div>
          <div class="skill-card">
            <div class="skill-icon"><i class="fab fa-git-alt" style="color:#f05032"></i></div>
            <span class="skill-name">Git / GitHub</span>
            <div class="skill-bar-wrap"><div class="skill-bar" data-width="92"></div></div>
            <span class="skill-percent">92%</span>
          </div>
          <div class="skill-card">
            <div class="skill-icon"><i class="fab fa-aws" style="color:#ff9900"></i></div>
            <span class="skill-name">AWS</span>
            <div class="skill-bar-wrap"><div class="skill-bar" data-width="70"></div></div>
            <span class="skill-percent">70%</span>
          </div>
          <div class="skill-card">
            <div class="skill-icon"><i class="fas fa-mobile-alt" style="color:#a855f7"></i></div>
            <span class="skill-name">UI/UX Design</span>
            <div class="skill-bar-wrap"><div class="skill-bar" data-width="85"></div></div>
            <span class="skill-percent">85%</span>
          </div>
          <div class="skill-card">
            <div class="skill-icon"><i class="fas fa-cube" style="color:#00d8ff"></i></div>
            <span class="skill-name">Three.js</span>
            <div class="skill-bar-wrap"><div class="skill-bar" data-width="65"></div></div>
            <span class="skill-percent">65%</span>
          </div>
          <div class="skill-card">
            <div class="skill-icon"><i class="fas fa-bolt" style="color:#ffd700"></i></div>
            <span class="skill-name">Performance Opt.</span>
            <div class="skill-bar-wrap"><div class="skill-bar" data-width="87"></div></div>
            <span class="skill-percent">87%</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ═══════════════════════════════════════ PROJECTS ═══════════════════════════════════════ -->
  <section class="section projects" id="projects">
    <div class="container">
      <div class="section-header" data-aos="fade-up">
        <span class="section-tag">My Work</span>
        <h2 class="section-title">Featured <span class="gradient-text">Projects</span></h2>
        <p class="section-desc">Hand-picked projects that showcase my expertise</p>
      </div>

      <div class="projects-filter" data-aos="fade-up" data-aos-delay="100">
        <button class="filter-btn active" data-filter="all" id="filterAll">All</button>
        <button class="filter-btn" data-filter="fullstack" id="filterFullstack">Full Stack</button>
        <button class="filter-btn" data-filter="frontend" id="filterFrontend">Frontend</button>
        <button class="filter-btn" data-filter="mobile" id="filterMobile">Mobile</button>
      </div>

      <div class="projects-grid" id="projectsGrid">

        <div class="project-card" data-category="fullstack" data-aos="fade-up" data-aos-delay="100">
          <div class="project-image">
            <div class="project-img-placeholder gradient-1">
              <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="project-overlay">
              <a href="#" class="project-link" aria-label="Live Demo"><i class="fas fa-external-link-alt"></i></a>
              <a href="#" class="project-link" aria-label="GitHub"><i class="fab fa-github"></i></a>
            </div>
          </div>
          <div class="project-body">
            <div class="project-tags">
              <span class="project-tag">React</span>
              <span class="project-tag">Node.js</span>
              <span class="project-tag">MongoDB</span>
            </div>
            <h3 class="project-title">E-Commerce Platform</h3>
            <p class="project-desc">A full-featured e-commerce platform with real-time inventory, Stripe payments, and an AI-powered recommendation engine.</p>
          </div>
        </div>

        <div class="project-card" data-category="frontend" data-aos="fade-up" data-aos-delay="150">
          <div class="project-image">
            <div class="project-img-placeholder gradient-2">
              <i class="fas fa-chart-line"></i>
            </div>
            <div class="project-overlay">
              <a href="#" class="project-link" aria-label="Live Demo"><i class="fas fa-external-link-alt"></i></a>
              <a href="#" class="project-link" aria-label="GitHub"><i class="fab fa-github"></i></a>
            </div>
          </div>
          <div class="project-body">
            <div class="project-tags">
              <span class="project-tag">Vue.js</span>
              <span class="project-tag">D3.js</span>
              <span class="project-tag">Firebase</span>
            </div>
            <h3 class="project-title">Analytics Dashboard</h3>
            <p class="project-desc">Real-time business intelligence dashboard with interactive charts, custom date ranges, and export capabilities.</p>
          </div>
        </div>

        <div class="project-card" data-category="mobile" data-aos="fade-up" data-aos-delay="200">
          <div class="project-image">
            <div class="project-img-placeholder gradient-3">
              <i class="fas fa-heartbeat"></i>
            </div>
            <div class="project-overlay">
              <a href="#" class="project-link" aria-label="Live Demo"><i class="fas fa-external-link-alt"></i></a>
              <a href="#" class="project-link" aria-label="GitHub"><i class="fab fa-github"></i></a>
            </div>
          </div>
          <div class="project-body">
            <div class="project-tags">
              <span class="project-tag">React Native</span>
              <span class="project-tag">Python</span>
              <span class="project-tag">ML</span>
            </div>
            <h3 class="project-title">Health & Fitness App</h3>
            <p class="project-desc">AI-powered fitness tracking app with personalized workout plans, nutrition tracking, and health insights.</p>
          </div>
        </div>

        <div class="project-card" data-category="fullstack" data-aos="fade-up" data-aos-delay="100">
          <div class="project-image">
            <div class="project-img-placeholder gradient-4">
              <i class="fas fa-comments"></i>
            </div>
            <div class="project-overlay">
              <a href="#" class="project-link" aria-label="Live Demo"><i class="fas fa-external-link-alt"></i></a>
              <a href="#" class="project-link" aria-label="GitHub"><i class="fab fa-github"></i></a>
            </div>
          </div>
          <div class="project-body">
            <div class="project-tags">
              <span class="project-tag">Socket.io</span>
              <span class="project-tag">Node.js</span>
              <span class="project-tag">Redis</span>
            </div>
            <h3 class="project-title">Real-Time Chat App</h3>
            <p class="project-desc">Scalable real-time messaging platform with end-to-end encryption, file sharing, and video calls.</p>
          </div>
        </div>

        <div class="project-card" data-category="frontend" data-aos="fade-up" data-aos-delay="150">
          <div class="project-image">
            <div class="project-img-placeholder gradient-5">
              <i class="fas fa-brain"></i>
            </div>
            <div class="project-overlay">
              <a href="#" class="project-link" aria-label="Live Demo"><i class="fas fa-external-link-alt"></i></a>
              <a href="#" class="project-link" aria-label="GitHub"><i class="fab fa-github"></i></a>
            </div>
          </div>
          <div class="project-body">
            <div class="project-tags">
              <span class="project-tag">Three.js</span>
              <span class="project-tag">GSAP</span>
              <span class="project-tag">WebGL</span>
            </div>
            <h3 class="project-title">3D Portfolio Website</h3>
            <p class="project-desc">An immersive 3D portfolio experience using Three.js and WebGL with particle systems and interactive elements.</p>
          </div>
        </div>

        <div class="project-card" data-category="fullstack" data-aos="fade-up" data-aos-delay="200">
          <div class="project-image">
            <div class="project-img-placeholder gradient-6">
              <i class="fas fa-cloud"></i>
            </div>
            <div class="project-overlay">
              <a href="#" class="project-link" aria-label="Live Demo"><i class="fas fa-external-link-alt"></i></a>
              <a href="#" class="project-link" aria-label="GitHub"><i class="fab fa-github"></i></a>
            </div>
          </div>
          <div class="project-body">
            <div class="project-tags">
              <span class="project-tag">AWS</span>
              <span class="project-tag">Docker</span>
              <span class="project-tag">Kubernetes</span>
            </div>
            <h3 class="project-title">Cloud DevOps Pipeline</h3>
            <p class="project-desc">Automated CI/CD pipeline with Docker containerization, Kubernetes orchestration, and AWS deployment.</p>
          </div>
        </div>

      </div>

      <!-- Show More bar (mobile pagination — managed by renderer.js) -->
      <div class="projects-show-more" id="projectsShowMoreBar" style="display:none">
        <button class="btn-show-more" id="projectsShowMoreBtn">
          <i class="fas fa-chevron-down"></i>
          <span id="projectsShowMoreLabel">Show More Projects</span>
        </button>
      </div>

      <div class="projects-cta" data-aos="fade-up">
        <a href="#" class="btn btn-outline">
          View All Projects <i class="fab fa-github"></i>
        </a>
      </div>
    </div>
  </section>

  <!-- ═══════════════════════════════════════ EXPERIENCE ═══════════════════════════════════════ -->
  <section class="section experience" id="experience">
    <div class="container">
      <div class="section-header" data-aos="fade-up">
        <span class="section-tag">My Journey</span>
        <h2 class="section-title">Work <span class="gradient-text">Experience</span></h2>
        <p class="section-desc">Places I've worked and grown</p>
      </div>

      <div class="timeline-container">

        <div class="timeline-item" data-aos="fade-right" data-aos-delay="100">
          <div class="timeline-dot"><i class="fas fa-briefcase"></i></div>
          <div class="timeline-card">
            <div class="timeline-header">
              <div>
                <h3 class="timeline-title">Senior Full-Stack Developer</h3>
                <span class="timeline-company">TechVision Corp</span>
              </div>
              <div class="timeline-meta">
                <span class="timeline-period"><i class="fas fa-calendar-alt"></i> 2022 – Present</span>
                <span class="timeline-location"><i class="fas fa-map-marker-alt"></i> San Francisco, CA</span>
              </div>
            </div>
            <p class="timeline-desc">Led development of microservices architecture serving 2M+ users. Built and maintained React/Node.js applications, improving performance by 40%.</p>
            <div class="timeline-tags">
              <span>React</span><span>Node.js</span><span>AWS</span><span>PostgreSQL</span>
            </div>
          </div>
        </div>

        <div class="timeline-item" data-aos="fade-left" data-aos-delay="200">
          <div class="timeline-dot"><i class="fas fa-code"></i></div>
          <div class="timeline-card">
            <div class="timeline-header">
              <div>
                <h3 class="timeline-title">Frontend Developer</h3>
                <span class="timeline-company">InnovateLab Agency</span>
              </div>
              <div class="timeline-meta">
                <span class="timeline-period"><i class="fas fa-calendar-alt"></i> 2020 – 2022</span>
                <span class="timeline-location"><i class="fas fa-map-marker-alt"></i> Remote</span>
              </div>
            </div>
            <p class="timeline-desc">Developed pixel-perfect UI components and interactive web experiences for 25+ client projects. Specialized in performance optimization and accessibility.</p>
            <div class="timeline-tags">
              <span>Vue.js</span><span>SCSS</span><span>Figma</span><span>GraphQL</span>
            </div>
          </div>
        </div>

        <div class="timeline-item" data-aos="fade-right" data-aos-delay="300">
          <div class="timeline-dot"><i class="fas fa-laptop-code"></i></div>
          <div class="timeline-card">
            <div class="timeline-header">
              <div>
                <h3 class="timeline-title">Junior Web Developer</h3>
                <span class="timeline-company">StartupHub NYC</span>
              </div>
              <div class="timeline-meta">
                <span class="timeline-period"><i class="fas fa-calendar-alt"></i> 2019 – 2020</span>
                <span class="timeline-location"><i class="fas fa-map-marker-alt"></i> New York, NY</span>
              </div>
            </div>
            <p class="timeline-desc">Built responsive web applications for early-stage startups. Collaborated with designers and product managers to deliver 10+ successful product launches.</p>
            <div class="timeline-tags">
              <span>HTML/CSS</span><span>JavaScript</span><span>PHP</span><span>MySQL</span>
            </div>
          </div>
        </div>

        <div class="timeline-item" data-aos="fade-left" data-aos-delay="400">
          <div class="timeline-dot"><i class="fas fa-graduation-cap"></i></div>
          <div class="timeline-card">
            <div class="timeline-header">
              <div>
                <h3 class="timeline-title">B.Sc. Computer Science</h3>
                <span class="timeline-company">New York University</span>
              </div>
              <div class="timeline-meta">
                <span class="timeline-period"><i class="fas fa-calendar-alt"></i> 2015 – 2019</span>
                <span class="timeline-location"><i class="fas fa-map-marker-alt"></i> New York, NY</span>
              </div>
            </div>
            <p class="timeline-desc">Graduated with honors. Specialized in Software Engineering and Human-Computer Interaction. Led the university coding club for 2 consecutive years.</p>
            <div class="timeline-tags">
              <span>Algorithms</span><span>Data Structures</span><span>HCI</span><span>Software Eng.</span>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- ═══════════════════════════════════════ TESTIMONIALS ═══════════════════════════════════════ -->
  <section class="section testimonials" id="testimonials">
    <div class="container">
      <div class="section-header" data-aos="fade-up">
        <span class="section-tag">Kind Words</span>
        <h2 class="section-title">Client <span class="gradient-text">Testimonials</span></h2>
        <p class="section-desc">What people say about working with me</p>
      </div>

      <div class="swiper testimonials-swiper" data-aos="fade-up" data-aos-delay="200">
        <div class="swiper-wrapper">

          <div class="swiper-slide">
            <div class="testimonial-card">
              <div class="testimonial-quote"><i class="fas fa-quote-left"></i></div>
              <p class="testimonial-text">Alex delivered beyond expectations. The e-commerce platform he built doubled our conversion rate within the first month. Absolutely phenomenal work!</p>
              <div class="testimonial-author">
                <div class="author-avatar"><span>SR</span></div>
                <div>
                  <p class="author-name">Sarah Reynolds</p>
                  <p class="author-role">CEO, StyleBrand Inc.</p>
                </div>
                <div class="testimonial-stars">
                  <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
              </div>
            </div>
          </div>

          <div class="swiper-slide">
            <div class="testimonial-card">
              <div class="testimonial-quote"><i class="fas fa-quote-left"></i></div>
              <p class="testimonial-text">Working with Alex was a game-changer. His technical expertise combined with his eye for design produced a product that our users love. Highly recommended!</p>
              <div class="testimonial-author">
                <div class="author-avatar" style="background: linear-gradient(135deg,#f093fb,#f5576c)"><span>MK</span></div>
                <div>
                  <p class="author-name">Marcus Kim</p>
                  <p class="author-role">CTO, DataFlow Labs</p>
                </div>
                <div class="testimonial-stars">
                  <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
              </div>
            </div>
          </div>

          <div class="swiper-slide">
            <div class="testimonial-card">
              <div class="testimonial-quote"><i class="fas fa-quote-left"></i></div>
              <p class="testimonial-text">Professional, responsive, and incredibly talented. Alex turned our complex requirements into a flawless application. Will definitely work with him again!</p>
              <div class="testimonial-author">
                <div class="author-avatar" style="background: linear-gradient(135deg,#4facfe,#00f2fe)"><span>JP</span></div>
                <div>
                  <p class="author-name">Jessica Park</p>
                  <p class="author-role">Founder, NexGen Startup</p>
                </div>
                <div class="testimonial-stars">
                  <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
              </div>
            </div>
          </div>

          <div class="swiper-slide">
            <div class="testimonial-card">
              <div class="testimonial-quote"><i class="fas fa-quote-left"></i></div>
              <p class="testimonial-text">Alex's ability to understand our vision and translate it into a beautiful, functional product is unmatched. The new dashboard transformed how our team works.</p>
              <div class="testimonial-author">
                <div class="author-avatar" style="background: linear-gradient(135deg,#43e97b,#38f9d7)"><span>DH</span></div>
                <div>
                  <p class="author-name">David Harper</p>
                  <p class="author-role">Product Manager, CloudSuite</p>
                </div>
                <div class="testimonial-stars">
                  <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
              </div>
            </div>
          </div>

        </div>
        <div class="swiper-pagination" id="testimonialPagination"></div>
        <div class="swiper-button-prev" id="testimonialPrev"></div>
        <div class="swiper-button-next" id="testimonialNext"></div>
      </div>
    </div>
  </section>

  <!-- ═══════════════════════════════════════ CONTACT ═══════════════════════════════════════ -->
  <section class="section contact" id="contact">
    <div class="container">
      <div class="section-header" data-aos="fade-up">
        <span class="section-tag">Get In Touch</span>
        <h2 class="section-title">Let's <span class="gradient-text">Connect</span></h2>
        <p class="section-desc">Have a project in mind? Let's build something amazing together.</p>
      </div>

      <div class="contact-grid">
        <div class="contact-info" data-aos="fade-right" data-aos-delay="100">
          <h3 class="contact-heading">Let's talk about your project</h3>
          <p class="contact-subtext">I'm currently available for freelance work and open to new opportunities. Feel free to reach out — I'll get back to you within 24 hours.</p>

          <div class="contact-cards">
            <a href="mailto:alex@example.com" class="contact-card-item" id="contactEmail">
              <div class="contact-card-icon"><i class="fas fa-envelope"></i></div>
              <div>
                <span class="contact-card-label">Email</span>
                <span class="contact-card-value">alex@example.com</span>
              </div>
            </a>
            <a href="tel:+1234567890" class="contact-card-item" id="contactPhone">
              <div class="contact-card-icon"><i class="fas fa-phone"></i></div>
              <div>
                <span class="contact-card-label">Phone</span>
                <span class="contact-card-value">+1 (234) 567-890</span>
              </div>
            </a>
            <div class="contact-card-item" id="contactLocation">
              <div class="contact-card-icon"><i class="fas fa-map-marker-alt"></i></div>
              <div>
                <span class="contact-card-label">Location</span>
                <span class="contact-card-value">New York, USA</span>
              </div>
            </div>
          </div>

          <div class="social-links">
            <a href="#" class="social-link" id="socialGithub" aria-label="GitHub"><i class="fab fa-github"></i></a>
            <a href="#" class="social-link" id="socialLinkedin" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
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
                <input type="text" id="contactSubject" name="subject" placeholder="Project Inquiry" required />
              </div>
            </div>
            <div class="form-group">
              <label for="contactMessage">Message</label>
              <div class="input-wrap textarea-wrap">
                <i class="fas fa-comment-alt"></i>
                <textarea id="contactMessage" name="message" rows="5" placeholder="Tell me about your project..." required></textarea>
              </div>
            </div>

            <!-- Math Captcha -->
            <div class="form-group captcha-group">
              <label>Quick Verification <span class="captcha-hint">Prove you're human 🤖</span></label>
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
              <p class="captcha-error" id="captchaError" style="display:none">❌ Wrong answer — try the new question!</p>
            </div>

            <button type="submit" class="btn btn-primary btn-full" id="submitBtn">
              <span class="btn-text">Send Message</span>
              <i class="fas fa-paper-plane"></i>
            </button>
            <div class="form-success" id="formSuccess">
              <i class="fas fa-check-circle"></i>
              <span>Message sent successfully! I'll get back to you soon.</span>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>

  <!-- ═══════════════════════════════════════ FOOTER ═══════════════════════════════════════ -->
  <footer class="footer">
    <div class="container">
      <div class="footer-grid">
        <div class="footer-brand">
          <a href="#home" class="nav-logo">
            <span class="logo-bracket">&lt;</span>
            <span class="logo-name">AM</span>
            <span class="logo-bracket">/&gt;</span>
          </a>
          <p class="footer-tagline">Crafting digital experiences that make an impact.</p>
          <div class="social-links">
            <a href="#" class="social-link" aria-label="GitHub"><i class="fab fa-github"></i></a>
            <a href="#" class="social-link" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
            <a href="#" class="social-link" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
            <a href="#" class="social-link" aria-label="Dribbble"><i class="fab fa-dribbble"></i></a>
          </div>
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
          <h4 class="footer-col-title">More</h4>
          <ul>
            <li><a href="#experience">Experience</a></li>
            <li><a href="#testimonials">Testimonials</a></li>
            <li><a href="#contact">Contact</a></li>
            <li><a href="#">Download CV</a></li>
          </ul>
        </div>

        <div class="footer-newsletter">
          <h4 class="footer-col-title">Stay Updated</h4>
          <p>Subscribe for article updates & dev tips.</p>
          <div class="newsletter-form">
            <input type="email" id="newsletterEmail" placeholder="your@email.com" aria-label="Newsletter email" />
            <button id="newsletterSubmit" aria-label="Subscribe"><i class="fas fa-arrow-right"></i></button>
          </div>
        </div>
      </div>

      <div class="footer-bottom">
        <p>&copy; <span id="footerYear"></span> Muhammad Asif. All rights reserved.</p>
        <p>Designed & Built with <i class="fas fa-heart" style="color:#f43f5e"></i> by Muhammad Asif</p>
      </div>
    </div>
  </footer>

  <!-- Back to Top -->
  <button class="back-to-top" id="backToTop" aria-label="Back to top">
    <i class="fas fa-chevron-up"></i>
  </button>

  <!-- Scripts -->
  <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <!-- Core Data & Renderer -->
  <script src="data.js?v=10"></script>
  <script src="renderer.js?v=10"></script>
  <script src="script.js?v=10"></script>
</body>
</html>
