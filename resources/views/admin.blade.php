<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Portfolio Admin Dashboard</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="admin.css" />
</head>
<body>

<!-- ═══════════ LOGIN SCREEN ═══════════ -->
<div class="login-screen" id="loginScreen">
  <div class="login-bg">
    <div class="login-grid-lines"></div>
    <div class="login-glow"></div>
  </div>
  <div class="login-card" id="loginCard">
    <div class="login-logo">
      <span class="logo-bracket">&lt;</span><span class="logo-name">AM</span><span class="logo-bracket">/&gt;</span>
    </div>
    <h1 class="login-title">Admin Access</h1>
    <p class="login-subtitle">Enter your password to manage your portfolio</p>

    <div class="login-form">
      <div class="login-input-wrap">
        <i class="fas fa-lock"></i>
        <input type="password" id="loginPasswordInput" placeholder="Enter admin password" autocomplete="current-password" />
        <button type="button" id="toggleLoginPwd" aria-label="Toggle password visibility">
          <i class="fas fa-eye" id="loginEyeIcon"></i>
        </button>
      </div>
      <p class="login-error" id="loginError"></p>
      <button class="login-btn" id="loginBtn">
        <span>Login to Dashboard</span>
        <i class="fas fa-arrow-right"></i>
      </button>

    </div>
  </div>
</div>

<!-- ═══════════ MAIN DASHBOARD ═══════════ -->
<div class="dashboard" id="dashboard" style="display:none">

  <!-- SIDEBAR -->
  <aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
      <div class="sidebar-logo">
        <span class="logo-bracket">&lt;</span><span class="logo-name">AM</span><span class="logo-bracket">/&gt;</span>
      </div>
      <p class="sidebar-label">Portfolio CMS</p>
    </div>

    <nav class="sidebar-nav" id="sidebarNav">
      <button class="nav-item active" data-panel="overview" id="navOverview">
        <i class="fas fa-th-large"></i><span>Overview</span>
      </button>
      <div class="nav-divider"><span>Sections</span></div>
      <button class="nav-item" data-panel="hero" id="navHero">
        <i class="fas fa-home"></i><span>Hero</span>
      </button>
      <button class="nav-item" data-panel="about" id="navAbout">
        <i class="fas fa-user"></i><span>About</span>
      </button>
      <button class="nav-item" data-panel="skills" id="navSkills">
        <i class="fas fa-tools"></i><span>Skills</span>
      </button>
      <button class="nav-item" data-panel="projects" id="navProjects">
        <i class="fas fa-folder-open"></i><span>Projects</span>
      </button>
      <button class="nav-item" data-panel="experience" id="navExperience">
        <i class="fas fa-briefcase"></i><span>Experience</span>
      </button>
      <button class="nav-item" data-panel="achievements" id="navAchievements">
        <i class="fas fa-award"></i><span>Achievements</span>
      </button>
      <button class="nav-item" data-panel="education" id="navEducation">
        <i class="fas fa-graduation-cap"></i><span>Education</span>
      </button>
      <button class="nav-item" data-panel="contact" id="navContact">
        <i class="fas fa-envelope"></i><span>Contact</span>
      </button>
      <div class="nav-divider"><span>System</span></div>
      <button class="nav-item" data-panel="inbox" id="navInbox">
        <i class="fas fa-bell"></i><span>Inbox <span class="inbox-badge" id="inboxBadge" style="display:none"></span></span>
      </button>
      <button class="nav-item" data-panel="settings" id="navSettings">
        <i class="fas fa-cog"></i><span>Settings</span>
      </button>
    </nav>

    <div class="sidebar-footer">
      <a href="index.html" target="_blank" class="sidebar-action-btn" id="viewPortfolioBtn">
        <i class="fas fa-external-link-alt"></i><span>View Portfolio</span>
      </a>
      <button class="sidebar-action-btn danger" id="logoutBtn">
        <i class="fas fa-sign-out-alt"></i><span>Logout</span>
      </button>
    </div>
  </aside>

  <!-- MAIN CONTENT -->
  <div class="main-wrap">
    <!-- TOP BAR -->
    <header class="topbar">
      <div class="topbar-left">
        <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
          <i class="fas fa-bars"></i>
        </button>
        <div class="breadcrumb">
          <span>Dashboard</span>
          <i class="fas fa-chevron-right"></i>
          <span id="breadcrumbSection">Overview</span>
        </div>
      </div>
      <div class="topbar-right">
        <button class="btn-reset" id="resetDataBtn" title="Reset to factory defaults">
          <i class="fas fa-undo"></i> Reset Defaults
        </button>
        <button class="btn-save" id="saveBtn">
          <i class="fas fa-save"></i> Save Changes
        </button>
      </div>
    </header>

    <!-- TOAST NOTIFICATIONS -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- PANELS -->
    <main class="main-content">

      <!-- ── OVERVIEW PANEL ── -->
      <div class="panel active" id="panel-overview">
        <div class="panel-header">
          <h2 class="panel-title">Dashboard Overview</h2>
          <p class="panel-subtitle">Manage your portfolio content from one place</p>
        </div>
        <div class="overview-grid">
          <div class="overview-card" data-goto="hero"><i class="fas fa-home"></i><h3>Hero</h3><p>Name, title, description, stats</p></div>
          <div class="overview-card" data-goto="about"><i class="fas fa-user"></i><h3>About</h3><p>Bio, background, and profile summary</p></div>
          <div class="overview-card" data-goto="skills"><i class="fas fa-tools"></i><h3>Skills</h3><p>Networking, web, Android, tools</p></div>
          <div class="overview-card" data-goto="projects"><i class="fas fa-folder-open"></i><h3>Projects</h3><p>Showcase your best work</p></div>
          <div class="overview-card" data-goto="experience"><i class="fas fa-briefcase"></i><h3>Experience</h3><p>Military and technical background</p></div>
          <div class="overview-card" data-goto="achievements"><i class="fas fa-award"></i><h3>Achievements</h3><p>Training, honors, commendations</p></div>
          <div class="overview-card" data-goto="education"><i class="fas fa-graduation-cap"></i><h3>Education</h3><p>Education, certification, languages</p></div>
          <div class="overview-card" data-goto="contact"><i class="fas fa-envelope"></i><h3>Contact</h3><p>Email, phone, social links</p></div>
          <div class="overview-card" data-goto="settings"><i class="fas fa-cog"></i><h3>Settings</h3><p>Site meta & password</p></div>
        </div>
        <div class="quick-tip">
          <i class="fas fa-lightbulb"></i>
          <span>Click any card to edit that section. Changes only apply after you click <strong>Save Changes</strong>.</span>
        </div>
      </div>

      <!-- ── HERO PANEL ── -->
      <div class="panel" id="panel-hero">
        <div class="panel-header">
          <h2 class="panel-title">Hero Section</h2>
          <p class="panel-subtitle">The first thing visitors see — make it count!</p>
        </div>
        <div class="form-grid">
          <div class="form-group">
            <label>Availability Tag</label>
            <input type="text" id="heroTag" placeholder="Available for Work" />
          </div>
          <div class="form-group">
            <label>Greeting Text</label>
            <input type="text" id="heroFirstName" placeholder="Hi, I'm" />
          </div>
          <div class="form-group">
            <label>Highlight Name</label>
            <input type="text" id="heroHighlightName" placeholder="Muhammad Asif" />
          </div>
          <div class="form-group full-width">
            <label>Description <span class="hint">HTML allowed, e.g. &lt;strong&gt;text&lt;/strong&gt;</span></label>
            <textarea id="heroDescription" rows="3" placeholder="Networking-focused profile summary..."></textarea>
          </div>
        </div>

        <div class="section-divider"><span>Typed Words (the rotating phrases)</span></div>
        <div class="tag-input-wrap">
          <div class="tag-list" id="heroTypedTagList"></div>
          <div class="tag-add-row">
            <input type="text" id="heroTypedInput" placeholder="Add phrase and press Enter…" />
            <button class="btn-add-tag" id="heroTypedAddBtn"><i class="fas fa-plus"></i></button>
          </div>
        </div>

        <div class="section-divider"><span>Stats</span></div>
        <div id="heroStatsContainer" class="stats-edit-grid"></div>

        <div class="section-divider"><span>Skill Icons Orbit</span></div>
        <div class="form-grid">
          <div class="form-group full-width orbit-speed-wrap">
            <label>Orbit Circulation Speed <span class="hint">Lower = faster · Higher = slower</span></label>
            <div class="orbit-speed-row">
              <span class="orbit-speed-label-text"><i class="fas fa-bolt"></i> Fast</span>
              <input type="range" id="heroOrbitSpeed" min="3" max="30" step="1" value="12" />
              <span class="orbit-speed-label-text"><i class="fas fa-feather"></i> Slow</span>
              <span class="orbit-speed-value" id="heroOrbitSpeedVal">12s</span>
            </div>
          </div>
        </div>

        <div class="section-divider"><span>Profile Photo</span></div>
        <div class="image-upload-zone" id="heroUploadZone">
          <div class="img-upload-preview">
            <img id="heroImgPreviewEl" src="profile.png" alt="Hero Profile" />
          </div>
          <div class="img-upload-controls">
            <label class="btn-upload-img" for="heroImageInput">
              <i class="fas fa-cloud-upload-alt"></i> Upload Profile Photo
            </label>
            <input type="file" id="heroImageInput" accept="image/jpeg,image/png,image/webp,image/gif" />
            <p class="img-upload-hint"><i class="fas fa-info-circle"></i> JPG, PNG, WEBP · Max 8 MB · Used in Hero &amp; (optionally) About sections</p>
            <p class="img-upload-status" id="heroUploadStatus"></p>
          </div>
        </div>
      </div>

      <!-- ── ABOUT PANEL ── -->
      <div class="panel" id="panel-about">
        <div class="panel-header">
          <h2 class="panel-title">About Section</h2>
          <p class="panel-subtitle">Tell your story and share your background</p>
        </div>
        <div class="form-grid">
          <div class="form-group">
            <label>Heading Main Text</label>
            <input type="text" id="aboutHeading" placeholder="Retired Army Professional &" />
          </div>
          <div class="form-group">
            <label>Heading Highlight (Gradient)</label>
            <input type="text" id="aboutHeadingHighlight" placeholder="Networking-Focused IT Candidate" />
          </div>
          <div class="form-group full-width">
            <label>Bio Paragraph 1 <span class="hint">HTML allowed</span></label>
            <textarea id="aboutText1" rows="3"></textarea>
          </div>
          <div class="form-group full-width">
            <label>Bio Paragraph 2 <span class="hint">HTML allowed</span></label>
            <textarea id="aboutText2" rows="3"></textarea>
          </div>
        </div>

        <div class="section-divider"><span>Info Grid</span></div>
        <div class="form-grid">
          <div class="form-group">
            <label><i class="fas fa-user"></i> Full Name</label>
            <input type="text" id="aboutName" />
          </div>
          <div class="form-group">
            <label><i class="fas fa-envelope"></i> Email</label>
            <input type="email" id="aboutEmail" />
          </div>
          <div class="form-group">
            <label><i class="fas fa-map-marker-alt"></i> Location</label>
            <input type="text" id="aboutLocation" />
          </div>
          <div class="form-group">
            <label><i class="fas fa-graduation-cap"></i> Degree</label>
            <input type="text" id="aboutDegree" />
          </div>
          <div class="form-group">
            <label>Experience Badge (e.g. 5+)</label>
            <input type="text" id="aboutExpYears" placeholder="5+" />
          </div>
        </div>

        <div class="section-divider"><span>About Section Image</span></div>
        <div class="image-upload-zone" id="aboutUploadZone">
          <div class="img-upload-preview">
            <img id="aboutImgPreviewEl" src="profile.png" alt="About" />
          </div>
          <div class="img-upload-controls">
            <label class="btn-upload-img" for="aboutImageInput">
              <i class="fas fa-cloud-upload-alt"></i> Upload About Image
            </label>
            <input type="file" id="aboutImageInput" accept="image/jpeg,image/png,image/webp,image/gif" />
            <button class="btn-sync-img" id="aboutSyncBtn" title="Use same photo as Hero">
              <i class="fas fa-sync-alt"></i> Use Hero Photo
            </button>
            <p class="img-upload-hint"><i class="fas fa-info-circle"></i> JPG, PNG, WEBP · Max 8 MB</p>
            <p class="img-upload-status" id="aboutUploadStatus"></p>
          </div>
        </div>
      </div>

      <!-- ── SKILLS PANEL ── -->
      <div class="panel" id="panel-skills">
        <div class="panel-header">
          <h2 class="panel-title">Skills Section</h2>
          <p class="panel-subtitle">Manage your networking-first skill groups</p>
        </div>
        <div class="tabs-row">
          <button class="tab-btn active" data-skillstab="networking" id="skillTabNetworking">Networking</button>
          <button class="tab-btn" data-skillstab="webDevelopment" id="skillTabWebDevelopment">Web Development</button>
          <button class="tab-btn" data-skillstab="androidDevelopment" id="skillTabAndroidDevelopment">Android Development</button>
          <button class="tab-btn" data-skillstab="productivityTools" id="skillTabProductivityTools">Productivity Tools</button>
          <button class="tab-btn" data-skillstab="professionalStrengths" id="skillTabProfessionalStrengths">Professional Strengths</button>
        </div>
        <div class="skill-tab-content active" id="skillContent-networking"><div class="skill-list" id="skillList-networking"></div></div>
        <div class="skill-tab-content" id="skillContent-webDevelopment"><div class="skill-list" id="skillList-webDevelopment"></div></div>
        <div class="skill-tab-content" id="skillContent-androidDevelopment"><div class="skill-list" id="skillList-androidDevelopment"></div></div>
        <div class="skill-tab-content" id="skillContent-productivityTools"><div class="skill-list" id="skillList-productivityTools"></div></div>
        <div class="skill-tab-content" id="skillContent-professionalStrengths"><div class="skill-list" id="skillList-professionalStrengths"></div></div>
        <div class="add-item-bar">
          <button class="btn-add-item" id="addSkillBtn"><i class="fas fa-plus-circle"></i> Add Skill</button>
        </div>
      </div>

      <!-- ── PROJECTS PANEL ── -->
      <div class="panel" id="panel-projects">
        <div class="panel-header">
          <h2 class="panel-title">Projects Section</h2>
          <p class="panel-subtitle">Showcase your best work</p>
        </div>
        <div class="list-container" id="projectsList"></div>
        <div class="add-item-bar">
          <button class="btn-add-item" id="addProjectBtn"><i class="fas fa-plus-circle"></i> Add Project</button>
        </div>
      </div>

      <!-- ── EXPERIENCE PANEL ── -->
      <div class="panel" id="panel-experience">
        <div class="panel-header">
          <h2 class="panel-title">Military & Technical Background</h2>
          <p class="panel-subtitle">Translate military service and current technical focus for civilian recruiters</p>
        </div>
        <div class="list-container" id="experienceList"></div>
        <div class="add-item-bar">
          <button class="btn-add-item" id="addExperienceBtn"><i class="fas fa-plus-circle"></i> Add Entry</button>
        </div>
      </div>

      <!-- ── ACHIEVEMENTS PANEL ── -->
      <div class="panel" id="panel-achievements">
        <div class="panel-header">
          <h2 class="panel-title">Achievements & Training</h2>
          <p class="panel-subtitle">Courses, rankings, honors, certificates, and commendations</p>
        </div>
        <div class="list-container" id="achievementsList"></div>
        <div class="add-item-bar">
          <button class="btn-add-item" id="addAchievementBtn"><i class="fas fa-plus-circle"></i> Add Achievement</button>
        </div>
      </div>

      <!-- ── EDUCATION PANEL ── -->
      <div class="panel" id="panel-education">
        <div class="panel-header">
          <h2 class="panel-title">Education, Certifications & Languages</h2>
          <p class="panel-subtitle">Academic status, certification preparation, and language profile</p>
        </div>

        <div class="section-divider"><span>Education & Certifications</span></div>
        <div class="list-container" id="educationList"></div>
        <div class="add-item-bar">
          <button class="btn-add-item" id="addEducationBtn"><i class="fas fa-plus-circle"></i> Add Entry</button>
        </div>

        <div class="section-divider"><span>Languages</span></div>
        <div class="list-container" id="languagesList"></div>
        <div class="add-item-bar">
          <button class="btn-add-item" id="addLanguageBtn"><i class="fas fa-plus-circle"></i> Add Language</button>
        </div>
      </div>

      <!-- ── CONTACT PANEL ── -->
      <div class="panel" id="panel-contact">
        <div class="panel-header">
          <h2 class="panel-title">Contact Section</h2>
          <p class="panel-subtitle">Contact details and social media links</p>
        </div>
        <div class="form-grid">
          <div class="form-group full-width">
            <label>Section Heading</label>
            <input type="text" id="contactHeading" />
          </div>
          <div class="form-group full-width">
            <label>Subtext</label>
            <textarea id="contactSubtext" rows="2"></textarea>
          </div>
          <div class="form-group">
            <label><i class="fas fa-envelope"></i> Email</label>
            <input type="email" id="contactEmail" />
          </div>
          <div class="form-group">
            <label><i class="fas fa-phone"></i> Phone</label>
            <input type="text" id="contactPhone" />
          </div>
          <div class="form-group">
            <label><i class="fas fa-map-marker-alt"></i> Location</label>
            <input type="text" id="contactLocation" />
          </div>
          <div class="form-group">
            <label><i class="fas fa-globe"></i> Portfolio URL</label>
            <input type="url" id="contactPortfolioUrl" placeholder="https://foliofy.me/" />
          </div>
          <div class="form-group">
            <label><i class="fas fa-file-lines"></i> Resume URL</label>
            <input type="url" id="contactResumeUrl" placeholder="Add when available" />
          </div>
        </div>

        <div class="section-divider"><span>Social Links</span></div>
        <div class="form-grid">
          <div class="form-group">
            <label><i class="fab fa-github"></i> GitHub URL</label>
            <input type="url" id="socialGithub" placeholder="https://github.com/..." />
          </div>
          <div class="form-group">
            <label><i class="fab fa-linkedin-in"></i> LinkedIn URL</label>
            <input type="url" id="socialLinkedin" placeholder="https://linkedin.com/in/..." />
          </div>
          <div class="form-group">
            <label><i class="fab fa-twitter"></i> Twitter URL</label>
            <input type="url" id="socialTwitter" placeholder="https://twitter.com/..." />
          </div>
          <div class="form-group">
            <label><i class="fab fa-dribbble"></i> Dribbble URL</label>
            <input type="url" id="socialDribbble" placeholder="https://dribbble.com/..." />
          </div>
          <div class="form-group">
            <label><i class="fab fa-instagram"></i> Instagram URL</label>
            <input type="url" id="socialInstagram" placeholder="https://instagram.com/..." />
          </div>
        </div>

        <div class="section-divider"><span><i class="fab fa-whatsapp"></i> WhatsApp Notification API</span></div>
        <div class="form-grid">
          <div class="form-group full-width">
            <label>Enable WhatsApp Notifications
              <span class="hint">Send a WhatsApp message to your number every time someone submits the contact form</span>
            </label>
            <label class="toggle-switch">
              <input type="checkbox" id="waEnabled" />
              <span class="toggle-slider"></span>
              <span class="toggle-label" id="waEnabledLabel">Disabled</span>
            </label>
          </div>
          <div class="form-group">
            <label>API Key <span class="hint">Your X-Api-Key from wa-server</span></label>
            <input type="password" id="waApiKey" placeholder="Enter your API key" autocomplete="off" />
          </div>
          <div class="form-group">
            <label>Account Name <span class="hint">"account_name" from your WA session</span></label>
            <input type="text" id="waAccountName" placeholder="e.g. My Marketing Account" />
          </div>
          <div class="form-group full-width">
            <label>Your WhatsApp Number <span class="hint">Number to RECEIVE notifications — with country code, no spaces or + (e.g. 923001234567)</span></label>
            <input type="text" id="waTargetNumber" placeholder="e.g. 923001234567" />
          </div>
        </div>
      </div>

      <!-- ── INBOX PANEL ── -->
      <div class="panel" id="panel-inbox">
        <div class="panel-header">
          <h2 class="panel-title">Messages Inbox</h2>
          <p class="panel-subtitle">All contact form submissions from your portfolio visitors</p>
        </div>
        <div id="inboxContainer"><p class="inbox-empty"><i class="fas fa-inbox"></i><br>No messages yet. When visitors submit the contact form, they appear here.</p></div>
      </div>

      <!-- ── SETTINGS PANEL ── -->
      <div class="panel" id="panel-settings">
        <div class="panel-header">
          <h2 class="panel-title">Settings</h2>
          <p class="panel-subtitle">Site metadata and admin password</p>
        </div>
        <div class="form-grid">
          <div class="form-group">
            <label>Your Display Name</label>
            <input type="text" id="metaName" />
          </div>
          <div class="form-group">
            <label>Your Role / Tagline</label>
            <input type="text" id="metaRole" />
          </div>
          <div class="form-group">
            <label>Brand Logo Text <span class="hint">2-3 chars shown in navbar &amp; loader, e.g. "MAS" or "MS"</span></label>
            <input type="text" id="metaBrandText" placeholder="e.g. MAS" maxlength="4" />
          </div>
          <div class="form-group full-width">
            <label>Browser Tab Title</label>
            <input type="text" id="metaSiteTitle" />
          </div>
          <div class="form-group full-width">
            <label>Meta Description (SEO)</label>
            <textarea id="metaSiteDesc" rows="2"></textarea>
          </div>
          <div class="form-group full-width">
            <label>Meta Keywords (SEO)</label>
            <textarea id="metaSiteKeywords" rows="2"></textarea>
          </div>
          <div class="form-group full-width">
            <label>Footer Tagline</label>
            <input type="text" id="footerTagline" />
          </div>
        </div>

        <div class="section-divider"><span>Change Admin Password</span></div>
        <form id="changePwdForm" autocomplete="on" onsubmit="return false">
          <div class="form-grid">
            <div class="form-group">
              <label>Current Password</label>
              <div class="pwd-wrap"><input type="password" id="currentPwd" placeholder="Enter current password" autocomplete="current-password" /></div>
            </div>
            <div class="form-group">
              <label>New Password</label>
              <div class="pwd-wrap"><input type="password" id="newPwd" placeholder="New password" autocomplete="new-password" /></div>
            </div>
            <div class="form-group">
              <label>Confirm New Password</label>
              <div class="pwd-wrap"><input type="password" id="confirmPwd" placeholder="Repeat new password" autocomplete="new-password" /></div>
            </div>
          </div>
          <button class="btn-change-pwd" id="changePwdBtn" type="submit">
            <i class="fas fa-key"></i> Update Password
          </button>
        </form>

        <div class="section-divider danger-divider"><span>Danger Zone</span></div>
        <div class="danger-zone">
          <p>This will reset ALL portfolio content back to the original defaults. This action cannot be undone.</p>
          <button class="btn-danger" id="hardResetBtn"><i class="fas fa-trash-alt"></i> Reset Everything to Defaults</button>
        </div>
      </div>

    </main>
  </div>
</div>

<script src="data.js?v=11"></script>
<script src="admin.js?v=11"></script>
</body>
</html>
