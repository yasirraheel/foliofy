<?php
$meta = $portfolio['meta'] ?? [];
$hero = $portfolio['hero'] ?? [];
$about = $portfolio['about'] ?? [];
$skills = array_filter(
    $portfolio['skills'] ?? [],
    static fn ($items): bool => is_array($items) && $items !== []
);
$projects = array_values(
    array_filter(
        $portfolio['projects'] ?? [],
        static fn ($project): bool => is_array($project) && trim((string) ($project['title'] ?? '')) !== ''
    )
);
$experience = array_values(
    array_filter(
        $portfolio['experience'] ?? [],
        static fn ($item): bool => is_array($item) && trim((string) ($item['title'] ?? '')) !== ''
    )
);
$achievements = array_values(
    array_filter(
        $portfolio['achievements'] ?? [],
        static fn ($item): bool => is_array($item) && trim((string) ($item['title'] ?? '')) !== ''
    )
);
$education = array_values(
    array_filter(
        $portfolio['education'] ?? [],
        static fn ($item): bool => is_array($item) && trim((string) ($item['title'] ?? '')) !== ''
    )
);
$languages = array_values(
    array_filter(
        $portfolio['languages'] ?? [],
        static fn ($item): bool => is_array($item) && trim((string) ($item['name'] ?? '')) !== ''
    )
);
$contact = $portfolio['contact'] ?? [];
$contactSocial = $contact['social'] ?? [];
$footer = $portfolio['footer'] ?? [];
$images = $portfolio['images'] ?? [];
$heroImage = trim((string) ($images['hero'] ?? '')) !== '' ? $images['hero'] : 'profile.png';
$aboutImage = trim((string) ($images['about'] ?? '')) !== '' ? $images['about'] : $heroImage;
$personName = trim((string) ($about['name'] ?? $meta['name'] ?? 'Portfolio'));
$contactEmail = trim((string) ($contact['email'] ?? $about['email'] ?? ''));
$contactPhone = trim((string) ($contact['phone'] ?? ''));
$contactLocation = trim((string) ($contact['location'] ?? $about['location'] ?? ''));
$portfolioUrl = trim((string) ($contact['portfolioUrl'] ?? ($pageMeta['canonicalUrl'] ?? '')));
$resumeUrl = trim((string) ($contact['resumeUrl'] ?? ''));
$linkedinUrl = trim((string) ($contactSocial['linkedin'] ?? ''));
$heroFirstName = trim((string) ($hero['firstName'] ?? ''));
$heroHighlightName = trim((string) ($hero['highlightName'] ?? ''));
if ($heroFirstName === '' && $heroHighlightName === '') {
    $nameParts = preg_split('/\s+/', $personName) ?: [];
    $heroHighlightName = array_pop($nameParts) ?? '';
    $heroFirstName = trim(implode(' ', $nameParts));
}

$themeDefault = in_array($meta['themeDefault'] ?? null, ['dark', 'light'], true)
    ? $meta['themeDefault']
    : 'dark';

$esc = static fn ($value): string => htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
$displayUrl = static function (?string $url): string {
    if (! is_string($url) || trim($url) === '') {
        return '';
    }

    return (string) preg_replace('/\/$/', '', preg_replace('#^https?://#i', '', trim($url)));
};

$socialLinks = array_values(array_filter([
    ['id' => 'socialGithub', 'url' => trim((string) ($contactSocial['github'] ?? '')), 'label' => 'GitHub', 'icon' => 'fab fa-github'],
    ['id' => 'socialLinkedin', 'url' => $linkedinUrl, 'label' => 'LinkedIn', 'icon' => 'fab fa-linkedin-in'],
    ['id' => 'socialTwitter', 'url' => trim((string) ($contactSocial['twitter'] ?? '')), 'label' => 'Twitter', 'icon' => 'fab fa-twitter'],
    ['id' => 'socialDribbble', 'url' => trim((string) ($contactSocial['dribbble'] ?? '')), 'label' => 'Dribbble', 'icon' => 'fab fa-dribbble'],
    ['id' => 'socialInstagram', 'url' => trim((string) ($contactSocial['instagram'] ?? '')), 'label' => 'Instagram', 'icon' => 'fab fa-instagram'],
], static fn (array $link): bool => $link['url'] !== ''));

$footerSocialLinks = array_values(array_filter([
    ['url' => trim((string) ($contactSocial['github'] ?? '')), 'label' => 'GitHub', 'icon' => 'fab fa-github'],
    ['url' => $linkedinUrl, 'label' => 'LinkedIn', 'icon' => 'fab fa-linkedin-in'],
    ['url' => trim((string) ($contactSocial['twitter'] ?? '')), 'label' => 'Twitter', 'icon' => 'fab fa-twitter'],
    ['url' => trim((string) ($contactSocial['instagram'] ?? '')), 'label' => 'Instagram', 'icon' => 'fab fa-instagram'],
], static fn (array $link): bool => $link['url'] !== ''));

$resumeViewLabel = $resumeUrl !== '' ? 'View PDF CV' : 'CV Available On Request';
$resumeDownloadLabel = $resumeUrl !== '' ? 'Download PDF CV' : 'CV Available On Request';
$typedWords = array_values(array_filter($hero['typedWords'] ?? [], static fn ($word): bool => trim((string) $word) !== ''));
$heroStats = array_values(array_filter($hero['stats'] ?? [], static fn ($stat): bool => is_array($stat) && trim((string) ($stat['label'] ?? '')) !== ''));
$skillCategories = [];

foreach ($skills as $key => $items) {
    $categoryMeta = $skillCategoryMeta[$key] ?? [
        'label' => \Illuminate\Support\Str::headline((string) $key),
        'description' => '',
    ];

    $skillCategories[] = [
        'key' => $key,
        'label' => $categoryMeta['label'],
        'description' => $categoryMeta['description'],
        'items' => array_values($items),
    ];
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="<?= $esc($themeDefault) ?>">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= $esc($pageMeta['title'] ?? '') ?></title>
  <meta name="description" content="<?= $esc($pageMeta['description'] ?? '') ?>" />
  <meta name="keywords" content="<?= $esc($pageMeta['keywords'] ?? '') ?>" />
  <meta name="author" content="<?= $esc($pageMeta['author'] ?? '') ?>" />
  <link rel="canonical" href="<?= $esc($pageMeta['canonicalUrl'] ?? '') ?>" />

  <meta property="og:type" content="website" />
  <meta property="og:site_name" content="<?= $esc($pageMeta['siteName'] ?? '') ?>" />
  <meta property="og:url" content="<?= $esc($pageMeta['canonicalUrl'] ?? '') ?>" />
  <meta property="og:title" content="<?= $esc($pageMeta['title'] ?? '') ?>" />
  <meta property="og:description" content="<?= $esc($pageMeta['description'] ?? '') ?>" />
  <meta property="og:image" content="<?= $esc($pageMeta['ogImageUrl'] ?? '') ?>" />
  <meta property="og:image:type" content="image/jpeg" />
  <meta property="og:image:width" content="<?= $esc($pageMeta['ogImageWidth'] ?? 1200) ?>" />
  <meta property="og:image:height" content="<?= $esc($pageMeta['ogImageHeight'] ?? 630) ?>" />
  <meta property="og:image:alt" content="<?= $esc($pageMeta['ogImageAlt'] ?? '') ?>" />

  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:url" content="<?= $esc($pageMeta['canonicalUrl'] ?? '') ?>" />
  <meta name="twitter:title" content="<?= $esc($pageMeta['title'] ?? '') ?>" />
  <meta name="twitter:description" content="<?= $esc($pageMeta['description'] ?? '') ?>" />
  <meta name="twitter:image" content="<?= $esc($pageMeta['ogImageUrl'] ?? '') ?>" />
  <meta name="twitter:image:alt" content="<?= $esc($pageMeta['ogImageAlt'] ?? '') ?>" />

<?php if ($structuredDataJson !== ''): ?>
  <script type="application/ld+json"><?= $structuredDataJson ?></script>
<?php endif; ?>

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
        <span class="loader-bracket">&lt;</span><span class="logo-name"><?= $esc($meta['brandText'] ?? 'MAS') ?></span><span class="loader-bracket">/&gt;</span>
      </div>
      <div id="loaderOrbitContainer"></div>
    </div>
    <p class="loader-tagline">Loading portfolio...</p>
  </div>

  <nav class="navbar" id="navbar">
    <div class="nav-container">
      <a href="/" class="nav-logo" id="navLogo">
        <span class="logo-bracket">&lt;</span>
        <span class="logo-name"><?= $esc($meta['brandText'] ?? 'MAS') ?></span>
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
          <?= $esc($hero['availableTag'] ?? 'Open to Opportunities') ?>
        </div>

        <h1 class="hero-title" data-aos="fade-up" data-aos-delay="300">
          <?= $esc($heroFirstName) ?> <span class="gradient-text"><?= $esc($heroHighlightName) ?></span>
        </h1>

        <div class="hero-subtitle" data-aos="fade-up" data-aos-delay="400">
          <span class="static-text">Focused on </span>
          <span class="typed-text" id="typedText"><?= $esc($typedWords[0] ?? 'Networking') ?></span>
          <span class="cursor-blink">|</span>
        </div>

        <p class="hero-desc" data-aos="fade-up" data-aos-delay="500"><?= $hero['description'] ?? '' ?></p>

        <div class="hero-actions hero-actions--stacked" data-aos="fade-up" data-aos-delay="600">
          <a href="<?= $esc($resumeUrl !== '' ? $resumeUrl : '#contact') ?>" class="btn btn-outline<?= $resumeUrl === '' ? ' is-disabled' : '' ?>" id="heroResumeBtn"<?= $resumeUrl !== '' ? ' target="_blank" rel="noopener noreferrer"' : ' aria-disabled="true"' ?>>
            <i class="fas fa-file-lines"></i>
            <span><?= $resumeUrl !== '' ? 'View CV' : 'CV Available On Request' ?></span>
          </a>
          <a href="<?= $esc($resumeUrl !== '' ? $resumeUrl : '#contact') ?>" class="btn btn-outline<?= $resumeUrl === '' ? ' is-disabled' : '' ?>" id="heroDownloadCvBtn"<?= $resumeUrl !== '' ? ' download' : ' aria-disabled="true"' ?>>
            <i class="fas fa-download"></i>
            <span><?= $resumeUrl !== '' ? 'Download CV' : 'CV Available On Request' ?></span>
          </a>
          <a href="#contact" class="btn btn-primary" id="heroContactBtn">
            <span>Contact Me</span>
            <i class="fas fa-arrow-right"></i>
          </a>
          <a href="<?= $esc($linkedinUrl !== '' ? $linkedinUrl : '#contact') ?>" class="btn btn-outline<?= $linkedinUrl === '' ? ' is-disabled' : '' ?>" id="heroLinkedinBtn"<?= $linkedinUrl !== '' ? ' target="_blank" rel="noopener noreferrer"' : ' aria-disabled="true"' ?>>
            <i class="fab fa-linkedin-in"></i>
            <span>LinkedIn</span>
          </a>
          <a href="#projects" class="btn btn-outline" id="heroProjectsBtn">
            <i class="fas fa-folder-open"></i>
            <span>View Projects</span>
          </a>
        </div>

<?php if ($heroStats !== []): ?>
        <div class="hero-stats" data-aos="fade-up" data-aos-delay="700">
<?php foreach ($heroStats as $index => $stat): ?>
          <div class="stat-item">
            <span class="stat-number" data-target="<?= $esc($stat['number'] ?? 0) ?>">0</span>
            <span class="stat-label"><?= $esc($stat['label'] ?? '') ?></span>
          </div>
<?php if ($index < count($heroStats) - 1): ?>
          <div class="stat-divider"></div>
<?php endif; ?>
<?php endforeach; ?>
        </div>
<?php endif; ?>
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
              <img src="<?= $esc($heroImage) ?>" alt="<?= $esc($personName) ?>" class="profile-img" />
            </div>
          </div>
          <div id="orbitBadgesContainer">
<?php foreach ($skillCategories as $category): ?>
<?php foreach ($category['items'] as $skill): ?>
            <div class="tech-badge" title="<?= $esc($skill['name'] ?? '') ?>" data-name="<?= $esc($skill['name'] ?? '') ?>" data-level="<?= $esc($skill['level'] ?? 100) ?>" data-icon-class="<?= $esc($skill['iconClass'] ?? 'fas fa-code') ?>" data-color="<?= $esc($skill['iconColor'] ?? '#7c3aed') ?>" tabindex="0" role="button" aria-label="<?= $esc(($skill['name'] ?? 'Skill') . ' skill level ' . ($skill['level'] ?? 100) . '%') ?>" style="color:<?= $esc($skill['iconColor'] ?? '#7c3aed') ?>; --badge-color:<?= $esc($skill['iconColor'] ?? '#7c3aed') ?>;">
              <i class="<?= $esc($skill['iconClass'] ?? 'fas fa-code') ?>"></i>
            </div>
<?php endforeach; ?>
<?php endforeach; ?>
          </div>
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
            <img src="<?= $esc($aboutImage) ?>" alt="<?= $esc($personName) ?> profile" class="about-img" />
            <div class="about-exp-badge">
              <span class="exp-number"><?= $esc($about['expYears'] ?? '') ?></span>
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
  <span class="code-key">role</span>: <span class="code-str">"<?= $esc($meta['role'] ?? 'Network Support Engineer') ?>"</span>,
  <span class="code-key">focus</span>: <span class="code-str">"CCNA in progress"</span>,
  <span class="code-key">labs</span>: <span class="code-str">"Packet Tracer"</span>,
  <span class="code-key">location</span>: <span class="code-str">"<?= $esc($about['location'] ?? '') ?>"</span>,
  <span class="code-key">openTo</span>: <span class="code-str">"Pakistan, UAE, KSA, Europe"</span>
};</code></pre>
          </div>
        </div>

        <div class="about-content" data-aos="fade-left" data-aos-delay="200">
          <h3 class="about-heading">
            <?= $esc($about['heading'] ?? 'Professional Background &') ?> <span class="gradient-text"><?= $esc($about['headingHighlight'] ?? 'Career Focus') ?></span>
          </h3>
          <p class="about-text"><?= $about['text1'] ?? '' ?></p>
          <p class="about-text"><?= $about['text2'] ?? '' ?></p>

          <div class="about-info-grid">
            <div class="info-item">
              <i class="fas fa-user"></i>
              <div>
                <span class="info-label">Name</span>
                <span class="info-value"><?= $esc($about['name'] ?? $personName) ?></span>
              </div>
            </div>
            <div class="info-item">
              <i class="fas fa-envelope"></i>
              <div>
                <span class="info-label">Email</span>
                <span class="info-value"><?= $esc($about['email'] ?? $contactEmail) ?></span>
              </div>
            </div>
            <div class="info-item">
              <i class="fas fa-map-marker-alt"></i>
              <div>
                <span class="info-label">Location</span>
                <span class="info-value"><?= $esc($about['location'] ?? $contactLocation) ?></span>
              </div>
            </div>
            <div class="info-item">
              <i class="fas fa-graduation-cap"></i>
              <div>
                <span class="info-label">Education</span>
                <span class="info-value"><?= $esc($about['degree'] ?? '') ?></span>
              </div>
            </div>
          </div>

          <div class="about-actions">
            <a href="#contact" class="btn btn-primary">Contact Me</a>
            <a href="<?= $esc($resumeUrl !== '' ? $resumeUrl : '#contact') ?>" class="btn btn-outline<?= $resumeUrl === '' ? ' is-disabled' : '' ?>" id="aboutResumeLink"<?= $resumeUrl !== '' ? ' target="_blank" rel="noopener noreferrer"' : ' aria-disabled="true"' ?>>View CV <i class="fas fa-file-lines"></i></a>
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

      <div class="skills-tabs" id="skillsTabs" data-aos="fade-up" data-aos-delay="100">
<?php foreach ($skillCategories as $index => $category): ?>
        <button class="skills-tab<?= $index === 0 ? ' active' : '' ?>" data-tab="<?= $esc($category['key']) ?>"><?= $esc($category['label']) ?></button>
<?php endforeach; ?>
      </div>

      <div id="skillsPanels" data-aos="fade-up" data-aos-delay="200">
<?php if ($skillCategories === []): ?>
        <p class="empty-state">Skills will appear here once added.</p>
<?php else: ?>
<?php foreach ($skillCategories as $index => $category): ?>
        <div class="skills-content<?= $index === 0 ? ' active' : '' ?>" id="tabContent<?= $esc(\Illuminate\Support\Str::studly($category['key'])) ?>">
          <div class="skills-panel-copy">
            <h3><?= $esc($category['label']) ?></h3>
            <p><?= $esc($category['description']) ?></p>
          </div>
          <div class="skills-grid skills-grid--simple">
<?php foreach ($category['items'] as $skill): ?>
            <div class="skill-card skill-card--simple">
              <div class="skill-icon"><i class="<?= $esc($skill['iconClass'] ?? 'fas fa-code') ?>" style="color:<?= $esc($skill['iconColor'] ?? '#7c3aed') ?>"></i></div>
              <span class="skill-name"><?= $esc($skill['name'] ?? '') ?></span>
            </div>
<?php endforeach; ?>
          </div>
        </div>
<?php endforeach; ?>
<?php endif; ?>
      </div>
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

      <div class="projects-grid" id="projectsGrid">
<?php if ($projects === []): ?>
        <p class="empty-state">Projects will appear here once added.</p>
<?php else: ?>
<?php foreach ($projects as $index => $project): ?>
        <div class="project-card" data-category="<?= $esc($project['category'] ?? '') ?>" data-aos="fade-up" data-aos-delay="<?= $esc((($index % 3) + 1) * 100) ?>">
          <div class="project-image">
            <div class="project-img-placeholder <?= $esc($project['gradient'] ?? 'gradient-1') ?>"><i class="<?= $esc($project['icon'] ?? 'fas fa-folder-open') ?>"></i></div>
            <div class="project-overlay">
<?php if (!empty($project['liveUrl'])): ?>
              <a href="<?= $esc($project['liveUrl']) ?>" class="project-link" aria-label="Live Demo" target="_blank" rel="noopener noreferrer"><i class="fas fa-external-link-alt"></i></a>
<?php endif; ?>
<?php if (!empty($project['githubUrl'])): ?>
              <a href="<?= $esc($project['githubUrl']) ?>" class="project-link" aria-label="Source" target="_blank" rel="noopener noreferrer"><i class="fab fa-github"></i></a>
<?php endif; ?>
            </div>
          </div>
          <div class="project-body">
            <div class="project-tags">
<?php foreach (($project['tags'] ?? []) as $tag): ?>
              <span class="project-tag"><?= $esc($tag) ?></span>
<?php endforeach; ?>
            </div>
            <h3 class="project-title"><?= $esc($project['title'] ?? '') ?></h3>
            <p class="project-desc"><?= $esc($project['desc'] ?? '') ?></p>
          </div>
        </div>
<?php endforeach; ?>
<?php endif; ?>
      </div>

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

      <div class="timeline-container">
<?php if ($experience === []): ?>
        <p class="empty-state">Background items will appear here once added.</p>
<?php else: ?>
<?php foreach ($experience as $index => $item): ?>
        <div class="timeline-item timeline-item--<?= $index % 2 === 0 ? 'left' : 'right' ?>" data-aos="fade-up" data-aos-delay="<?= $esc(($index + 1) * 100) ?>">
          <div class="timeline-dot"><i class="<?= $esc($item['iconClass'] ?? 'fas fa-briefcase') ?>"></i></div>
          <div class="timeline-card">
            <div class="timeline-header">
              <div>
                <h3 class="timeline-title"><?= $esc($item['title'] ?? '') ?></h3>
                <span class="timeline-company"><?= $esc($item['company'] ?? '') ?></span>
              </div>
              <div class="timeline-meta">
                <span class="timeline-period"><i class="fas fa-calendar-alt"></i> <?= $esc($item['period'] ?? '') ?></span>
                <span class="timeline-location"><i class="fas fa-map-marker-alt"></i> <?= $esc($item['location'] ?? '') ?></span>
              </div>
            </div>
            <p class="timeline-desc"><?= $esc($item['desc'] ?? '') ?></p>
            <div class="timeline-tags">
<?php foreach (($item['tags'] ?? []) as $tag): ?>
              <span><?= $esc($tag) ?></span>
<?php endforeach; ?>
            </div>
          </div>
        </div>
<?php endforeach; ?>
<?php endif; ?>
      </div>
    </div>
  </section>

  <section class="section achievements" id="achievements">
    <div class="container">
      <div class="section-header" data-aos="fade-up">
        <span class="section-tag">Achievements</span>
        <h2 class="section-title">Training & <span class="gradient-text">Honors</span></h2>
        <p class="section-desc">Formal military training, course positions, certificates, and commendations.</p>
      </div>

      <div class="achievement-grid" id="achievementsGrid">
<?php if ($achievements === []): ?>
        <p class="empty-state">Achievements will appear here once added.</p>
<?php else: ?>
<?php foreach ($achievements as $index => $item): ?>
        <article class="achievement-card" data-aos="fade-up" data-aos-delay="<?= $esc((($index % 3) + 1) * 100) ?>">
          <div class="achievement-top">
            <span class="achievement-period"><?= $esc($item['period'] ?? 'Recognized') ?></span>
<?php if (!empty($item['highlight'])): ?>
            <span class="achievement-highlight"><?= $esc($item['highlight']) ?></span>
<?php endif; ?>
          </div>
          <h3><?= $esc($item['title'] ?? '') ?></h3>
<?php if (!empty($item['subtitle'])): ?>
          <p class="achievement-subtitle"><?= $esc($item['subtitle']) ?></p>
<?php endif; ?>
          <p class="achievement-desc"><?= $esc($item['description'] ?? '') ?></p>
        </article>
<?php endforeach; ?>
<?php endif; ?>
      </div>
    </div>
  </section>

  <section class="section education" id="education">
    <div class="container">
      <div class="section-header" data-aos="fade-up">
        <span class="section-tag">Education & Certifications</span>
        <h2 class="section-title">Learning & <span class="gradient-text">Preparation</span></h2>
        <p class="section-desc">Academic progress and certification preparation aligned with networking roles.</p>
      </div>

      <div class="credential-grid" id="educationGrid">
<?php if ($education === []): ?>
        <p class="empty-state">Education items will appear here once added.</p>
<?php else: ?>
<?php foreach ($education as $index => $item): ?>
        <article class="credential-card" data-aos="fade-up" data-aos-delay="<?= $esc(($index + 1) * 120) ?>">
          <div class="credential-badge"><?= $esc($item['subtitle'] ?? 'Credential') ?></div>
          <h3><?= $esc($item['title'] ?? '') ?></h3>
          <p class="credential-status"><?= $esc($item['status'] ?? '') ?></p>
          <p class="credential-desc"><?= $esc($item['description'] ?? '') ?></p>
        </article>
<?php endforeach; ?>
<?php endif; ?>
      </div>
    </div>
  </section>

  <section class="section languages" id="languages">
    <div class="container">
      <div class="section-header" data-aos="fade-up">
        <span class="section-tag">Languages</span>
        <h2 class="section-title">Communication <span class="gradient-text">Profile</span></h2>
        <p class="section-desc">Language strengths for professional and regional opportunities.</p>
      </div>

      <div class="language-grid" id="languagesGrid">
<?php if ($languages === []): ?>
        <p class="empty-state">Languages will appear here once added.</p>
<?php else: ?>
<?php foreach ($languages as $index => $item): ?>
        <article class="language-card" data-aos="fade-up" data-aos-delay="<?= $esc(($index + 1) * 120) ?>">
          <div class="language-icon"><i class="fas fa-language"></i></div>
          <div>
            <h3><?= $esc($item['name'] ?? '') ?></h3>
            <p><?= $esc($item['proficiency'] ?? '') ?></p>
          </div>
        </article>
<?php endforeach; ?>
<?php endif; ?>
      </div>
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
          <h3 class="contact-heading"><?= $esc($contact['heading'] ?? "Let's connect for networking and IT support opportunities") ?></h3>
          <p class="contact-subtext"><?= $esc($contact['subtext'] ?? 'I am open to networking, NOC, IT support, and technical infrastructure opportunities.') ?></p>

          <div class="contact-cards">
<?php if ($contactEmail !== ''): ?>
            <a href="mailto:<?= $esc($contactEmail) ?>" class="contact-card-item" id="contactEmail">
              <div class="contact-card-icon"><i class="fas fa-envelope"></i></div>
              <div>
                <span class="contact-card-label">Email</span>
                <span class="contact-card-value"><?= $esc($contactEmail) ?></span>
              </div>
            </a>
<?php endif; ?>

<?php if ($contactPhone !== ''): ?>
            <a href="tel:<?= $esc($contactPhone) ?>" class="contact-card-item" id="contactPhone">
              <div class="contact-card-icon"><i class="fas fa-phone"></i></div>
              <div>
                <span class="contact-card-label">Phone</span>
                <span class="contact-card-value"><?= $esc($contactPhone) ?></span>
              </div>
            </a>
<?php endif; ?>

<?php if ($contactLocation !== ''): ?>
            <div class="contact-card-item" id="contactLocation">
              <div class="contact-card-icon"><i class="fas fa-map-marker-alt"></i></div>
              <div>
                <span class="contact-card-label">Location</span>
                <span class="contact-card-value"><?= $esc($contactLocation) ?></span>
              </div>
            </div>
<?php endif; ?>

            <a href="<?= $esc($portfolioUrl !== '' ? $portfolioUrl : ($pageMeta['canonicalUrl'] ?? '#home')) ?>" class="contact-card-item<?= $portfolioUrl === '' ? ' is-disabled' : '' ?>" id="contactPortfolio"<?= $portfolioUrl !== '' ? ' target="_blank" rel="noopener noreferrer"' : ' aria-disabled="true"' ?>>
              <div class="contact-card-icon"><i class="fas fa-globe"></i></div>
              <div>
                <span class="contact-card-label">Portfolio</span>
                <span class="contact-card-value"><?= $esc($displayUrl($portfolioUrl !== '' ? $portfolioUrl : ($pageMeta['canonicalUrl'] ?? '')) ?: 'Portfolio') ?></span>
              </div>
            </a>

            <a href="<?= $esc($resumeUrl !== '' ? $resumeUrl : '#contact') ?>" class="contact-card-item<?= $resumeUrl === '' ? ' is-disabled' : '' ?>" id="contactResume"<?= $resumeUrl !== '' ? ' target="_blank" rel="noopener noreferrer"' : ' aria-disabled="true"' ?>>
              <div class="contact-card-icon"><i class="fas fa-file-lines"></i></div>
              <div>
                <span class="contact-card-label">View CV</span>
                <span class="contact-card-value"><?= $esc($resumeViewLabel) ?></span>
              </div>
            </a>

            <a href="<?= $esc($resumeUrl !== '' ? $resumeUrl : '#contact') ?>" class="contact-card-item<?= $resumeUrl === '' ? ' is-disabled' : '' ?>" id="contactResumeDownload"<?= $resumeUrl !== '' ? ' download' : ' aria-disabled="true"' ?>>
              <div class="contact-card-icon"><i class="fas fa-download"></i></div>
              <div>
                <span class="contact-card-label">Download CV</span>
                <span class="contact-card-value"><?= $esc($resumeDownloadLabel) ?></span>
              </div>
            </a>
          </div>

          <div class="social-links">
<?php foreach ($socialLinks as $link): ?>
            <a href="<?= $esc($link['url']) ?>" class="social-link" id="<?= $esc($link['id']) ?>" aria-label="<?= $esc($link['label']) ?>" target="_blank" rel="noopener noreferrer"><i class="<?= $esc($link['icon']) ?>"></i></a>
<?php endforeach; ?>
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
            <span class="logo-name"><?= $esc($meta['brandText'] ?? 'MAS') ?></span>
            <span class="logo-bracket">/&gt;</span>
          </a>
          <p class="footer-tagline"><?= $esc($footer['tagline'] ?? 'Portfolio built for recruiter-friendly discovery and career opportunities.') ?></p>
          <div class="social-links" id="footerSocialLinks">
<?php foreach ($footerSocialLinks as $link): ?>
            <a href="<?= $esc($link['url']) ?>" class="social-link" aria-label="<?= $esc($link['label']) ?>" target="_blank" rel="noopener noreferrer">
              <i class="<?= $esc($link['icon']) ?>"></i>
            </a>
<?php endforeach; ?>
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
            <a href="<?= $esc($portfolioUrl !== '' ? $portfolioUrl : ($pageMeta['canonicalUrl'] ?? '#home')) ?>" class="btn btn-outline btn-full<?= $portfolioUrl === '' ? ' is-disabled' : '' ?>" id="footerPortfolioLink"<?= $portfolioUrl !== '' ? ' target="_blank" rel="noopener noreferrer"' : ' aria-disabled="true"' ?>>Open Portfolio</a>
            <a href="<?= $esc($linkedinUrl !== '' ? $linkedinUrl : '#contact') ?>" class="btn btn-outline btn-full<?= $linkedinUrl === '' ? ' is-disabled' : '' ?>" id="footerLinkedinLink"<?= $linkedinUrl !== '' ? ' target="_blank" rel="noopener noreferrer"' : ' aria-disabled="true"' ?>>LinkedIn Profile</a>
            <a href="<?= $esc($resumeUrl !== '' ? $resumeUrl : '#contact') ?>" class="btn btn-outline btn-full<?= $resumeUrl === '' ? ' is-disabled' : '' ?>" id="footerResumeLink"<?= $resumeUrl !== '' ? ' target="_blank" rel="noopener noreferrer"' : ' aria-disabled="true"' ?>><?= $resumeUrl !== '' ? 'View CV' : 'CV Available On Request' ?></a>
            <a href="<?= $esc($resumeUrl !== '' ? $resumeUrl : '#contact') ?>" class="btn btn-outline btn-full<?= $resumeUrl === '' ? ' is-disabled' : '' ?>" id="footerDownloadCvLink"<?= $resumeUrl !== '' ? ' download' : ' aria-disabled="true"' ?>><?= $resumeUrl !== '' ? 'Download CV' : 'CV Available On Request' ?></a>
          </div>
        </div>
      </div>

      <div class="footer-bottom">
        <p>&copy; <span id="footerYear"></span> <?= $esc($personName) ?>. All rights reserved.</p>
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
