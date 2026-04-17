/* ══════════════════════════════════════════════════════
   DATA.JS — Portfolio Data Layer (localStorage CMS)
   GitHub: yasirraheel | Muhammad Asif Shabbir
   ══════════════════════════════════════════════════════ */

'use strict';

const PORTFOLIO_KEY = 'am_portfolio_v1';
const DATA_VERSION_KEY = 'am_portfolio_version';
const DATA_VERSION = 5; // Bumped: force local storage reset to load all 52 sync'd GitHub projects

// Auto-clear stale data from old version (e.g., "Alex Morgan" defaults)
(function migrateData() {
  const storedVersion = parseInt(localStorage.getItem(DATA_VERSION_KEY) || '0', 10);
  if (storedVersion < DATA_VERSION) {
    localStorage.removeItem(PORTFOLIO_KEY);
    localStorage.setItem(DATA_VERSION_KEY, String(DATA_VERSION));
  }
})();


const DEFAULT_DATA = {
  meta: {
    name: 'Muhammad Asif Shabbir',
    role: 'Full-Stack Developer | Laravel & JavaScript Specialist',
    brandText: 'MAS',
    siteTitle: 'Muhammad Asif Shabbir | Full-Stack Developer',
    siteDesc: 'Professional portfolio of Muhammad Asif Shabbir — Full-Stack Developer specializing in Laravel, PHP, JavaScript, React & Android development.'
  },
  hero: {
    availableTag: 'Available for Work',
    firstName: "Hi, I'm",
    highlightName: 'Asif Shabbir',
    typedWords: ['Laravel Applications', 'React Frontends', 'Android Apps', 'REST APIs', 'Full-Stack Products', 'PHP Solutions'],
    description: 'A passionate <strong>Full-Stack Developer</strong> with expertise in Laravel, PHP, JavaScript, and Android — building scalable, real-world products that make an impact.',
    orbitSpeed: 12,
    stats: [
      { number: 3,  label: 'Years Exp.' },
      { number: 51, label: 'GitHub Repos' },
      { number: 20, label: 'Happy Clients' }
    ]
  },
  about: {
    heading: 'Full-Stack Developer &',
    headingHighlight: 'Problem Solver',
    text1: "I'm a passionate full-stack developer with expertise in <strong>Laravel, PHP, JavaScript, and Android (Java)</strong>. I've shipped 51+ real-world projects across GitHub — from video streaming platforms and digital marketplaces to military-grade mobile apps, AI tools, and WhatsApp automation systems.",
    text2: "I love turning complex ideas into clean, scalable, and performance-driven applications. Whether it's a Laravel SaaS backend, a Next.js storefront, an Android app with biometric security, or a Python AI tool — I bring the full picture together with attention to detail and a genuine passion for quality.",
    name: 'Muhammad Asif Shabbir',
    email: 'yasirraheel@github.com',
    location: 'Pakistan',
    degree: 'Computer Science',
    expYears: '3+'
  },
  skills: {
    frontend: [
      { name: 'JavaScript',      iconClass: 'fab fa-js-square',   iconColor: '#f7df1e', level: 90 },
      { name: 'TypeScript',      iconClass: 'fas fa-code',        iconColor: '#3178c6', level: 78 },
      { name: 'React.js',        iconClass: 'fab fa-react',       iconColor: '#61dafb', level: 82 },
      { name: 'HTML5 / CSS3',    iconClass: 'fab fa-html5',       iconColor: '#e34f26', level: 92 },
      { name: 'Blade (Laravel)', iconClass: 'fas fa-layer-group', iconColor: '#ff2d20', level: 93 },
      { name: 'Bootstrap',       iconClass: 'fab fa-bootstrap',   iconColor: '#7952b3', level: 88 }
    ],
    backend: [
      { name: 'PHP',             iconClass: 'fab fa-php',         iconColor: '#8892be', level: 93 },
      { name: 'Laravel',         iconClass: 'fas fa-fire',        iconColor: '#ff2d20', level: 95 },
      { name: 'Node.js',         iconClass: 'fab fa-node-js',     iconColor: '#339933', level: 72 },
      { name: 'REST APIs',       iconClass: 'fas fa-server',      iconColor: '#ff6b35', level: 90 },
      { name: 'MySQL',           iconClass: 'fas fa-database',    iconColor: '#4479a1', level: 85 },
      { name: 'Java (Android)',  iconClass: 'fab fa-android',     iconColor: '#3ddc84', level: 75 }
    ],
    tools: [
      { name: 'Git / GitHub',    iconClass: 'fab fa-git-alt',     iconColor: '#f05032', level: 90 },
      { name: 'Linux / CLI',     iconClass: 'fab fa-linux',       iconColor: '#fcc624', level: 80 },
      { name: 'Android Studio',  iconClass: 'fas fa-mobile-alt',  iconColor: '#3ddc84', level: 75 },
      { name: 'Figma / UI Design',iconClass: 'fab fa-figma',      iconColor: '#f24e1e', level: 70 },
      { name: 'Composer / NPM',  iconClass: 'fas fa-cube',        iconColor: '#cb3837', level: 88 },
      { name: 'GoStock / APIs',  iconClass: 'fas fa-chart-line',  iconColor: '#43e97b', level: 80 }
    ]
  },
  projects: [
    {
        title: "CineWorm Web Platform",
        desc: "A comprehensive video streaming platform built with Laravel 10 — featuring movies, TV shows, live TV, and sports content. Includes subscription management with 12 payment gateways (Stripe, PayPal, Razorpay, Flutterwave, Paytm, etc.), social login (Google/Facebook), multi-device support, multi-language, watchlist, viewing history, and a full admin panel with analytics dashboard.",
        tags: [
            "Laravel 10",
            "PHP 8.1",
            "Stripe",
            "REST API",
            "MySQL"
        ],
        category: "fullstack",
        gradient: "gradient-1",
        icon: "fas fa-film",
        liveUrl: "https://cineworm.online",
        githubUrl: "https://github.com/yasirraheel/cineworm-org-web"
    },
    {
        title: "TwoFlip.com — E-Commerce Platform",
        desc: "A complete e-commerce business solution built with Laravel featuring a multi-vendor marketplace, auction/bidding system, wholesale management, and bulk pricing. Supports PayPal, Stripe, Razorpay, Paystack, Flutterwave, Bkash, Nagad and more payment gateways. Fully multi-language and multi-currency with SEO-friendly URLs.",
        tags: [
            "Laravel",
            "PHP",
            "Multi-vendor",
            "Stripe",
            "MySQL"
        ],
        category: "fullstack",
        gradient: "gradient-3",
        icon: "fas fa-store-alt",
        liveUrl: "https://github.com/yasirraheel/edit.twoflip.com",
        githubUrl: "https://github.com/yasirraheel/edit.twoflip.com"
    },
    {
        title: "WA-Sender — WhatsApp Automation",
        desc: "A Laravel-based WhatsApp automation platform for targeted campaigns. Features group sequence messaging with configurable delivery delays, a Node.js WhatsApp server for real-time message handling, and a management dashboard for scheduling and monitoring bulk messaging campaigns.",
        tags: [
            "Laravel",
            "PHP",
            "Node.js",
            "WhatsApp API",
            "MySQL"
        ],
        category: "fullstack",
        gradient: "gradient-4",
        icon: "fab fa-whatsapp",
        liveUrl: "https://github.com/yasirraheel/wa-sender",
        githubUrl: "https://github.com/yasirraheel/wa-sender"
    },
    {
        title: "GEO ENTERPRISES — Prize Bond System",
        desc: "A comprehensive prize bond booking and management system (Laravel 10). Features a real-time analytics admin dashboard, customer management, game category/subcategory organization with draw scheduling, a deposit approval workflow with payment proof uploads, FCM push notifications, balance management, and full REST API documentation.",
        tags: [
            "Laravel 10",
            "PHP 8.1",
            "FCM",
            "Bootstrap 5",
            "MySQL"
        ],
        category: "fullstack",
        gradient: "gradient-5",
        icon: "fas fa-ticket-alt",
        liveUrl: "https://github.com/yasirraheel/sevenlabs",
        githubUrl: "https://github.com/yasirraheel/sevenlabs"
    },
    {
        title: "FileBob — File Sharing SaaS",
        desc: "A comprehensive Laravel 9-based file sharing and management SaaS. Features secure chunked file uploads, password-protected shareable links with expiration, subscription plans (PayPal, Stripe, Mollie, Razorpay), multi-storage support (local, AWS S3, Backblaze B2, Wasabi), two-factor authentication, social OAuth login, and an SEO management system.",
        tags: [
            "Laravel 9",
            "PHP",
            "AWS S3",
            "Stripe",
            "SaaS"
        ],
        category: "fullstack",
        gradient: "gradient-6",
        icon: "fas fa-file-upload",
        liveUrl: "https://github.com/yasirraheel/filebob-starterkit",
        githubUrl: "https://github.com/yasirraheel/filebob-starterkit"
    },
    {
        title: "Portfolio Platform (GoStock)",
        desc: "A comprehensive Laravel 10-based portfolio builder. Features custom SEO-friendly URLs, dynamic logo system, color theme customization, dark/light mode, professional experience timeline, skills/proficiency tracking, education management, certification showcase, and project galleries with client testimonials.",
        tags: [
            "Laravel 10",
            "PHP 8.2",
            "Bootstrap 5",
            "MySQL"
        ],
        category: "fullstack",
        gradient: "gradient-1",
        icon: "fas fa-id-card",
        liveUrl: "https://github.com/yasirraheel/portfolio-gostock",
        githubUrl: "https://github.com/yasirraheel/portfolio-gostock"
    },
    {
        title: "Team Hifsa — E-Learning Platform",
        desc: "A Laravel 10-based e-learning platform for publishing courses, lessons, and quizzes with full progress tracking. Supports enrollment, lesson completion/undo, per-user notes, course reviews/ratings, multiple payment gateways (Stripe, Razorpay, CoinGate), Zoom live session hooks, and SMS/voice notifications via Twilio and Vonage.",
        tags: [
            "Laravel 10",
            "PHP",
            "Stripe",
            "Zoom API",
            "Twilio"
        ],
        category: "fullstack",
        gradient: "gradient-2",
        icon: "fas fa-graduation-cap",
        liveUrl: "https://github.com/yasirraheel/team-hifsa-skilltest",
        githubUrl: "https://github.com/yasirraheel/team-hifsa-skilltest"
    },
    {
        title: "MarketBob — Digital Marketplace",
        desc: "A Laravel 10-based digital marketplace for buying and selling digital products. Includes author profiles with follower system, digital item versioning, free & premium license management, KYC verification, referral/affiliate program, author earnings with withdrawals, MailChimp integration, two-factor authentication, and a blog with help center.",
        tags: [
            "Laravel 10",
            "PHP",
            "Stripe",
            "PayPal",
            "Razorpay"
        ],
        category: "fullstack",
        gradient: "gradient-3",
        icon: "fas fa-shopping-bag",
        liveUrl: "https://github.com/yasirraheel/marketbob",
        githubUrl: "https://github.com/yasirraheel/marketbob"
    },
    {
        title: "CineWorm Android App",
        desc: "A native Android video streaming app (Java, API 21+). Features ExoPlayer for smooth playback, Chromecast support, Picture-in-Picture mode, multiple subtitle options, Firebase Auth with Google/Facebook social login, OneSignal push notifications, and 13 payment gateways including Stripe, PayPal, Razorpay, and Flutterwave.",
        tags: [
            "Java",
            "Android",
            "ExoPlayer",
            "Firebase",
            "OneSignal"
        ],
        category: "mobile",
        gradient: "gradient-4",
        icon: "fab fa-android",
        liveUrl: "https://github.com/yasirraheel/cineworm-android",
        githubUrl: "https://github.com/yasirraheel/cineworm-android"
    },
    {
        title: "Guardyn — Offline Password Manager",
        desc: "A secure, fully offline Android password manager (Java) with biometric authentication (Face ID & Touch ID). Zero network permissions — all data stays on-device with encrypted local storage. A hardened, production-ready upgrade over its predecessor SafeGuardOffline.",
        tags: [
            "Java",
            "Android",
            "Biometrics",
            "Offline",
            "Security"
        ],
        category: "mobile",
        gradient: "gradient-5",
        icon: "fas fa-shield-alt",
        liveUrl: "https://github.com/yasirraheel/guardian",
        githubUrl: "https://github.com/yasirraheel/guardian"
    },
    {
        title: "CannonX — Artillery Calculator",
        desc: "A high-precision Android mortar and artillery calculator (Java) built for the Pakistan Army. Supports 60mm, 81mm, and 120mm mortars, GLHMG, and multiple fire mission types. Features hardware-locked activation via a companion admin app, ensuring only authorized devices can access this sensitive application.",
        tags: [
            "Java",
            "Android",
            "Military",
            "Precision Calc"
        ],
        category: "mobile",
        gradient: "gradient-6",
        icon: "fas fa-crosshairs",
        liveUrl: "https://github.com/yasirraheel/CannonX",
        githubUrl: "https://github.com/yasirraheel/CannonX"
    },
    {
        title: "AutoScroll — Teleprompter App",
        desc: "A professional teleprompter Android app (Java, SDK 24+) designed for TikTok and content creators. Features rich text editing with Urdu font support, 10 color presets plus custom color picker, smooth linear scrolling optimized for TikTok layouts, persistent settings with auto-save, and Material Design 3 UI.",
        tags: [
            "Java",
            "Android",
            "Material Design 3",
            "Content Creator"
        ],
        category: "mobile",
        gradient: "gradient-1",
        icon: "fas fa-scroll",
        liveUrl: "https://github.com/yasirraheel/autoscroll",
        githubUrl: "https://github.com/yasirraheel/autoscroll"
    },
    {
        title: "Guddu Enterprises TV",
        desc: "An Android live TV application (Java) built with ExoPlayer. Supports M3U playlist streams, news ticker overlays, and audio mixing for a professional broadcast experience. Designed as a branded live TV viewer for the Guddu Enterprises business.",
        tags: [
            "Java",
            "Android",
            "ExoPlayer",
            "M3U",
            "Live TV"
        ],
        category: "mobile",
        gradient: "gradient-2",
        icon: "fas fa-tv",
        liveUrl: "https://github.com/yasirraheel/guddu-enterprises-tv",
        githubUrl: "https://github.com/yasirraheel/guddu-enterprises-tv"
    },
    {
        title: "GEO Enterprises Android App",
        desc: "The native Android companion app (Java) for the GEO Enterprises prize bond platform. Features dynamic prize bond categories fetched from the Laravel backend, customer account management, and an in-app \"Paid Services\" purchasing flow integrated with the deposit/payment system.",
        tags: [
            "Java",
            "Android",
            "REST API",
            "Firebase"
        ],
        category: "mobile",
        gradient: "gradient-3",
        icon: "fas fa-mobile-alt",
        liveUrl: "https://github.com/yasirraheel/geo-enterprises-android-app",
        githubUrl: "https://github.com/yasirraheel/geo-enterprises-android-app"
    },
    {
        title: "Voice Detector — AI Speaker Segregator",
        desc: "A Python AI tool for automatic speaker identification and clustering in WhatsApp voice messages. Uses DBSCAN clustering to automatically separate and identify different speakers from audio recordings without prior training data — enabling easy organization of group voice note conversations.",
        tags: [
            "Python",
            "AI/ML",
            "DBSCAN",
            "Speaker Detection",
            "Audio"
        ],
        category: "frontend",
        gradient: "gradient-4",
        icon: "fas fa-microphone",
        liveUrl: "https://github.com/yasirraheel/voice-detector-script",
        githubUrl: "https://github.com/yasirraheel/voice-detector-script"
    },
    {
        title: "DoFlip — Next.js E-Commerce Storefront",
        desc: "A blazing-fast Next.js 14 e-commerce storefront with a Temu-inspired design. Built with TypeScript and Tailwind CSS using App Router for SSR — includes flash deals with countdown timers, category grid, product cards, cart/wishlist, and is ready to plug into a Laravel backend API. Vercel deployment-ready.",
        tags: [
            "Next.js 14",
            "TypeScript",
            "Tailwind CSS",
            "Laravel API"
        ],
        category: "frontend",
        gradient: "gradient-5",
        icon: "fab fa-react",
        liveUrl: "https://github.com/yasirraheel/doflip-react",
        githubUrl: "https://github.com/yasirraheel/doflip-react"
    },
    {
        title: "TubeRocket — YouTube View Booster",
        desc: "An Android utility (Java) designed to automate and boost YouTube video view counts. The final production release (v1.6.3) includes full AdMob/Play Console ad integration for monetization. A practical YouTube channel growth automation tool.",
        tags: [
            "Java",
            "Android",
            "YouTube",
            "Automation",
            "AdMob"
        ],
        category: "mobile",
        gradient: "gradient-6",
        icon: "fab fa-youtube",
        liveUrl: "https://github.com/yasirraheel/TubeRocket-Final",
        githubUrl: "https://github.com/yasirraheel/TubeRocket-Final"
    },
    {
        title: "Raasta — Pakistan Grid GPS App",
        desc: "An Android GPS and coordinate conversion application (Java) specialized for Pakistan. Supports conversion between the Pakistan Grid System, MGRS (Military Grid Reference System), and standard Lat/Long coordinates — an essential navigation tool for surveyors, military personnel, and field workers in Pakistan.",
        tags: [
            "Java",
            "Android",
            "GPS",
            "Maps",
            "Pakistan Grid"
        ],
        category: "mobile",
        gradient: "gradient-1",
        icon: "fas fa-map-marked-alt",
        liveUrl: "https://github.com/yasirraheel/raasta",
        githubUrl: "https://github.com/yasirraheel/raasta"
    },
    {
        title: "Todo Sync App (CCNA)",
        desc: "A PHP/MySQL task synchronization app hosted at learn-ccna.shahabtech.com. Features per-user task isolation with bearer token authentication, YouTube playlist import with full Data API pagination, and automatic API origin detection. Runs on Apache/LiteSpeed with mod_rewrite — no Node.js runtime required.",
        tags: [
            "PHP",
            "MySQL",
            "YouTube API",
            "Bearer Auth"
        ],
        category: "fullstack",
        gradient: "gradient-2",
        icon: "fas fa-clipboard-list",
        liveUrl: "https://learn-ccna.shahabtech.com",
        githubUrl: "https://github.com/yasirraheel/ccna-todo-list"
    },
    {
        title: "Stock Haqi Ali — Streaming Platform",
        desc: "A full-featured Laravel streaming platform live on stock.cineworm.org. Supports movies, TV series, sports, live TV, photos, and audio. Features AI-powered thumbnail generation via Google Gemini, 12 payment gateways, coupon/discount system, OneSignal push notifications, user device tracking, and a comprehensive mobile REST API (v1).",
        tags: [
            "Laravel",
            "PHP",
            "Gemini AI",
            "OneSignal",
            "REST API"
        ],
        category: "fullstack",
        gradient: "gradient-2",
        icon: "fas fa-play-circle",
        liveUrl: "https://stock.cineworm.org",
        githubUrl: "https://github.com/yasirraheel/stock_haqi_ali"
    },
    {
        title: "GamsGo — Gemini AI App",
        desc: "An AI-powered web application built with Google AI Studio and the Gemini API. Uses PHP with environment-based API key configuration, demonstrating integration with Google Gemini for AI-driven interactive features.",
        tags: [
            "PHP",
            "Gemini API",
            "Google AI Studio",
            "AI"
        ],
        category: "frontend",
        gradient: "gradient-3",
        icon: "fas fa-robot",
        liveUrl: "https://github.com/yasirraheel/gamsgo",
        githubUrl: "https://github.com/yasirraheel/gamsgo"
    },
    {
        title: "foliofy",
        desc: "A repository by yasirraheel.",
        tags: [
            "JavaScript"
        ],
        category: "frontend",
        gradient: "gradient-5",
        icon: "fab fa-react",
        liveUrl: "https://github.com/yasirraheel/foliofy",
        githubUrl: "https://github.com/yasirraheel/foliofy"
    },
    {
        title: "GEO-ENTERPRISES",
        desc: "This is Prize Bond Booking System",
        tags: [
            "PHP"
        ],
        category: "fullstack",
        gradient: "gradient-1",
        icon: "fas fa-code",
        liveUrl: "https://github.com/yasirraheel/GEO-ENTERPRISES",
        githubUrl: "https://github.com/yasirraheel/GEO-ENTERPRISES"
    },
    {
        title: "file-uploader",
        desc: "A repository by yasirraheel.",
        tags: [
            "Blade"
        ],
        category: "fullstack",
        gradient: "gradient-3",
        icon: "fas fa-code",
        liveUrl: "https://github.com/yasirraheel/file-uploader",
        githubUrl: "https://github.com/yasirraheel/file-uploader"
    },
    {
        title: "shahabtech",
        desc: "A repository by yasirraheel.",
        tags: [
            "Blade"
        ],
        category: "fullstack",
        gradient: "gradient-5",
        icon: "fas fa-code",
        liveUrl: "https://github.com/yasirraheel/shahabtech",
        githubUrl: "https://github.com/yasirraheel/shahabtech"
    },
    {
        title: "omnireach",
        desc: "A repository by yasirraheel.",
        tags: [
            "Blade"
        ],
        category: "fullstack",
        gradient: "gradient-1",
        icon: "fas fa-code",
        liveUrl: "https://github.com/yasirraheel/omnireach",
        githubUrl: "https://github.com/yasirraheel/omnireach"
    },
    {
        title: "xsender",
        desc: "A repository by yasirraheel.",
        tags: [
            "Blade"
        ],
        category: "fullstack",
        gradient: "gradient-3",
        icon: "fas fa-code",
        liveUrl: "https://github.com/yasirraheel/xsender",
        githubUrl: "https://github.com/yasirraheel/xsender"
    },
    {
        title: "downsub",
        desc: "A repository by yasirraheel.",
        tags: [
            "Blade"
        ],
        category: "fullstack",
        gradient: "gradient-5",
        icon: "fas fa-code",
        liveUrl: "https://github.com/yasirraheel/downsub",
        githubUrl: "https://github.com/yasirraheel/downsub"
    },
    {
        title: "Laravel-Final-Starter-Kit",
        desc: "A repository by yasirraheel.",
        tags: [
            "HTML"
        ],
        category: "frontend",
        gradient: "gradient-1",
        icon: "fab fa-react",
        liveUrl: "https://github.com/yasirraheel/Laravel-Final-Starter-Kit",
        githubUrl: "https://github.com/yasirraheel/Laravel-Final-Starter-Kit"
    },
    {
        title: "Down-YT-Sub",
        desc: "A repository by yasirraheel.",
        tags: [
            "Code",
            "Development"
        ],
        category: "fullstack",
        gradient: "gradient-3",
        icon: "fas fa-code",
        liveUrl: "https://github.com/yasirraheel/Down-YT-Sub",
        githubUrl: "https://github.com/yasirraheel/Down-YT-Sub"
    },
    {
        title: "onstream_cloud",
        desc: "A repository by yasirraheel.",
        tags: [
            "JavaScript"
        ],
        category: "frontend",
        gradient: "gradient-5",
        icon: "fab fa-react",
        liveUrl: "https://github.com/yasirraheel/onstream_cloud",
        githubUrl: "https://github.com/yasirraheel/onstream_cloud"
    },
    {
        title: "gamkon-topdeals-final",
        desc: "A repository by yasirraheel.",
        tags: [
            "JavaScript"
        ],
        category: "frontend",
        gradient: "gradient-1",
        icon: "fab fa-react",
        liveUrl: "https://github.com/yasirraheel/gamkon-topdeals-final",
        githubUrl: "https://github.com/yasirraheel/gamkon-topdeals-final"
    },
    {
        title: "mailwiz-promote-cineworm",
        desc: "A repository by yasirraheel.",
        tags: [
            "PHP"
        ],
        category: "fullstack",
        gradient: "gradient-3",
        icon: "fas fa-code",
        liveUrl: "https://github.com/yasirraheel/mailwiz-promote-cineworm",
        githubUrl: "https://github.com/yasirraheel/mailwiz-promote-cineworm"
    },
    {
        title: "cineworm_allinone",
        desc: "A repository by yasirraheel.",
        tags: [
            "JavaScript"
        ],
        category: "frontend",
        gradient: "gradient-5",
        icon: "fab fa-react",
        liveUrl: "https://github.com/yasirraheel/cineworm_allinone",
        githubUrl: "https://github.com/yasirraheel/cineworm_allinone"
    },
    {
        title: "EditTwoFlip_new",
        desc: "A repository by yasirraheel.",
        tags: [
            "PHP"
        ],
        category: "fullstack",
        gradient: "gradient-1",
        icon: "fas fa-code",
        liveUrl: "https://github.com/yasirraheel/EditTwoFlip_new",
        githubUrl: "https://github.com/yasirraheel/EditTwoFlip_new"
    },
    {
        title: "reactjs-twoflip",
        desc: "A repository by yasirraheel.",
        tags: [
            "TypeScript"
        ],
        category: "frontend",
        gradient: "gradient-3",
        icon: "fab fa-react",
        liveUrl: "https://github.com/yasirraheel/reactjs-twoflip",
        githubUrl: "https://github.com/yasirraheel/reactjs-twoflip"
    },
    {
        title: "twoflip-frontend",
        desc: "A repository by yasirraheel.",
        tags: [
            "Code",
            "Development"
        ],
        category: "fullstack",
        gradient: "gradient-5",
        icon: "fas fa-code",
        liveUrl: "https://github.com/yasirraheel/twoflip-frontend",
        githubUrl: "https://github.com/yasirraheel/twoflip-frontend"
    },
    {
        title: "multi-vendor-marketplace-",
        desc: "A repository by yasirraheel.",
        tags: [
            "PHP"
        ],
        category: "fullstack",
        gradient: "gradient-1",
        icon: "fas fa-code",
        liveUrl: "https://github.com/yasirraheel/multi-vendor-marketplace-",
        githubUrl: "https://github.com/yasirraheel/multi-vendor-marketplace-"
    },
    {
        title: "cineworm-stock-tv",
        desc: "A repository by yasirraheel.",
        tags: [
            "Java"
        ],
        category: "mobile",
        gradient: "gradient-3",
        icon: "fab fa-android",
        liveUrl: "https://github.com/yasirraheel/cineworm-stock-tv",
        githubUrl: "https://github.com/yasirraheel/cineworm-stock-tv"
    },
    {
        title: "reflip",
        desc: "A repository by yasirraheel.",
        tags: [
            "Blade"
        ],
        category: "fullstack",
        gradient: "gradient-5",
        icon: "fas fa-code",
        liveUrl: "https://github.com/yasirraheel/reflip",
        githubUrl: "https://github.com/yasirraheel/reflip"
    },
    {
        title: "starter-with-gostock",
        desc: "This is Laravel Starter Kit with GoStock ",
        tags: [
            "Blade"
        ],
        category: "fullstack",
        gradient: "gradient-1",
        icon: "fas fa-code",
        liveUrl: "https://github.com/yasirraheel/starter-with-gostock",
        githubUrl: "https://github.com/yasirraheel/starter-with-gostock"
    },
    {
        title: "CannonX-Admin",
        desc: "This is CannonX Admin app for Auth Code Generation",
        tags: [
            "Java"
        ],
        category: "mobile",
        gradient: "gradient-3",
        icon: "fab fa-android",
        liveUrl: "https://github.com/yasirraheel/CannonX-Admin",
        githubUrl: "https://github.com/yasirraheel/CannonX-Admin"
    },
    {
        title: "modern-laravel-portfolio",
        desc: "A repository by yasirraheel.",
        tags: [
            "Blade"
        ],
        category: "fullstack",
        gradient: "gradient-5",
        icon: "fas fa-code",
        liveUrl: "https://github.com/yasirraheel/modern-laravel-portfolio",
        githubUrl: "https://github.com/yasirraheel/modern-laravel-portfolio"
    },
    {
        title: "laravel-starter-kit",
        desc: "A repository by yasirraheel.",
        tags: [
            "Code",
            "Development"
        ],
        category: "fullstack",
        gradient: "gradient-1",
        icon: "fas fa-code",
        liveUrl: "https://github.com/yasirraheel/laravel-starter-kit",
        githubUrl: "https://github.com/yasirraheel/laravel-starter-kit"
    },
    {
        title: "SafeGuardOffline",
        desc: "Safeguard app for offline password saving",
        tags: [
            "Java"
        ],
        category: "mobile",
        gradient: "gradient-3",
        icon: "fab fa-android",
        liveUrl: "https://github.com/yasirraheel/SafeGuardOffline",
        githubUrl: "https://github.com/yasirraheel/SafeGuardOffline"
    },
    {
        title: "ccp_demo",
        desc: "A repository by yasirraheel.",
        tags: [
            "Java"
        ],
        category: "mobile",
        gradient: "gradient-5",
        icon: "fab fa-android",
        liveUrl: "https://github.com/yasirraheel/ccp_demo",
        githubUrl: "https://github.com/yasirraheel/ccp_demo"
    },
    {
        title: "PiLite",
        desc: "Pi Lite",
        tags: [
            "Java"
        ],
        category: "mobile",
        gradient: "gradient-1",
        icon: "fab fa-android",
        liveUrl: "https://github.com/yasirraheel/PiLite",
        githubUrl: "https://github.com/yasirraheel/PiLite"
    },
    {
        title: "TubeRocket",
        desc: "Tube rocket is app that can boost your YouTube views",
        tags: [
            "Java"
        ],
        category: "mobile",
        gradient: "gradient-3",
        icon: "fab fa-android",
        liveUrl: "https://github.com/yasirraheel/TubeRocket",
        githubUrl: "https://github.com/yasirraheel/TubeRocket"
    },
    {
        title: "Date-Picker-Dialog",
        desc: "A repository by yasirraheel.",
        tags: [
            "Java"
        ],
        category: "mobile",
        gradient: "gradient-5",
        icon: "fab fa-android",
        liveUrl: "https://github.com/yasirraheel/Date-Picker-Dialog",
        githubUrl: "https://github.com/yasirraheel/Date-Picker-Dialog"
    },
    {
        title: "FactorsCalc",
        desc: "A repository by yasirraheel.",
        tags: [
            "Java"
        ],
        category: "mobile",
        gradient: "gradient-1",
        icon: "fab fa-android",
        liveUrl: "https://github.com/yasirraheel/FactorsCalc",
        githubUrl: "https://github.com/yasirraheel/FactorsCalc"
    },
    {
        title: "yasirraheel",
        desc: "Config files for my GitHub profile.",
        tags: [
            "config",
            "github-config"
        ],
        category: "fullstack",
        gradient: "gradient-3",
        icon: "fas fa-code",
        liveUrl: "https://github.com/yasirraheel",
        githubUrl: "https://github.com/yasirraheel/yasirraheel"
    },
    {
        title: "MCQs",
        desc: "A repository by yasirraheel.",
        tags: [
            "Code",
            "Development"
        ],
        category: "fullstack",
        gradient: "gradient-5",
        icon: "fas fa-code",
        liveUrl: "https://github.com/yasirraheel/MCQs",
        githubUrl: "https://github.com/yasirraheel/MCQs"
    }
],
  experience: [
    {
      title: 'Full-Stack Developer',
      company: 'Team Hifsa',
      period: '2025 – Present',
      location: 'Pakistan (Remote)',
      desc: 'Building and maintaining full-stack web applications using PHP, Laravel, Blade, and JavaScript. Delivered a skill-assessment platform, CCNA tracker, and multiple client-facing tools.',
      tags: ['PHP', 'Laravel', 'JavaScript', 'MySQL'],
      iconClass: 'fas fa-briefcase'
    },
    {
      title: 'Laravel & PHP Developer',
      company: 'CineWorm / Freelance',
      period: '2024 – 2025',
      location: 'Remote',
      desc: 'Developed and deployed the CineWorm TV web platform and Android app. Built GoStock-integrated stock tracker, multi-vendor marketplace, XSender, OmniReach, and streaming infrastructure tools.',
      tags: ['Laravel', 'Blade', 'JavaScript', 'Android', 'Java'],
      iconClass: 'fas fa-code'
    },
    {
      title: 'Junior Web Developer',
      company: 'Freelance Projects',
      period: '2022 – 2024',
      location: 'Pakistan',
      desc: 'Kick-started career building 10+ client websites and tools. Specialised in PHP backends and Blade-templated frontends. Developed marketplaces, CMS systems, and outreach tools.',
      tags: ['PHP', 'HTML/CSS', 'JavaScript', 'MySQL'],
      iconClass: 'fas fa-laptop-code'
    },
    {
      title: 'Computer Science Degree',
      company: 'University',
      period: '2019 – 2023',
      location: 'Pakistan',
      desc: 'Studied Computer Science with a focus on software engineering, databases, networking, and mobile development. Built foundational expertise in algorithms, data structures, and system design.',
      tags: ['Algorithms', 'Data Structures', 'Networking', 'Software Eng.'],
      iconClass: 'fas fa-graduation-cap'
    }
  ],
  testimonials: [
    {
      text: 'Asif delivered our Laravel platform on time with excellent code quality. His understanding of the full stack meant we needed minimal back-and-forth. Highly recommended!',
      authorName: 'Hifsa Team Lead',
      authorRole: 'Director, Team Hifsa',
      initials: 'HT',
      avatarGradient: 'linear-gradient(135deg,#667eea,#764ba2)'
    },
    {
      text: 'The CineWorm web platform Asif built exceeded all expectations. It handled real user load from day one and looked incredible. Phenomenal full-stack work.',
      authorName: 'CineWorm Client',
      authorRole: 'Founder, CineWorm',
      initials: 'CW',
      avatarGradient: 'linear-gradient(135deg,#f093fb,#f5576c)'
    },
    {
      text: 'Working with Asif on our marketplace was a great experience. He understood business requirements quickly and delivered a robust, scalable PHP/Laravel solution.',
      authorName: 'MarketBob Owner',
      authorRole: 'CEO, MarketBob',
      initials: 'MB',
      avatarGradient: 'linear-gradient(135deg,#4facfe,#00f2fe)'
    },
    {
      text: "Asif's Android + Laravel combination is rare and powerful. He built our stock tracker app end-to-end and it's been running live on stock.cineworm.org with zero downtime since launch.",
      authorName: 'Haqi Ali',
      authorRole: 'Client, StockTracker Project',
      initials: 'HA',
      avatarGradient: 'linear-gradient(135deg,#43e97b,#38f9d7)'
    }
  ],
  contact: {
    heading: "Let's build something great together",
    subtext: "I'm currently available for freelance work and open to new opportunities. Whether it's a Laravel app, a React frontend, or a full SaaS product — let's talk!",
    email: 'yasirraheel@github.com',
    phone: '+92 000 000 0000',
    location: 'Pakistan',
    social: {
      github:    'https://github.com/yasirraheel',
      linkedin:  '#',
      twitter:   '#',
      dribbble:  '#',
      instagram: '#'
    }
  },
  footer: {
    tagline: 'Building full-stack products — from Laravel to Android, one commit at a time.'
  },
  images: {
    hero:  'profile.png',
    about: 'profile.png'
  }
};

/* ── Public API ── */
const PortfolioData = {
  /** Get current data (localStorage → defaults) */
  get() {
    try {
      const raw = localStorage.getItem(PORTFOLIO_KEY);
      if (!raw) return this._clone(DEFAULT_DATA);
      return this._merge(this._clone(DEFAULT_DATA), JSON.parse(raw));
    } catch { return this._clone(DEFAULT_DATA); }
  },

  /** Save full data object */
  save(data) {
    try { localStorage.setItem(PORTFOLIO_KEY, JSON.stringify(data)); return true; }
    catch { return false; }
  },

  /** Reset to factory defaults */
  reset() {
    localStorage.removeItem(PORTFOLIO_KEY);
    return this._clone(DEFAULT_DATA);
  },

  /** Return a fresh copy of defaults */
  defaults() { return this._clone(DEFAULT_DATA); },

  _clone(obj) { return JSON.parse(JSON.stringify(obj)); },

  /** Deep-merge stored data onto defaults so new default keys propagate */
  _merge(base, stored) {
    const out = Object.assign({}, base);
    for (const k of Object.keys(stored)) {
      if (stored[k] !== null && typeof stored[k] === 'object' && !Array.isArray(stored[k]) && typeof base[k] === 'object' && !Array.isArray(base[k])) {
        out[k] = this._merge(base[k], stored[k]);
      } else {
        out[k] = stored[k];
      }
    }
    return out;
  }
};
