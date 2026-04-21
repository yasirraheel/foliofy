/* ══════════════════════════════════════════════════════
   DATA.JS - Portfolio Data Layer (Laravel + MySQL)
   GitHub: yasirraheel | Muhammad Asif Shabbir
   ══════════════════════════════════════════════════════ */

'use strict';

const DEFAULT_DATA = {
  meta: {
    name: 'Muhammad Asif Shabbir',
    role: 'Network Support Engineer | CCNA in Progress',
    brandText: 'MAS',
    siteTitle: 'Muhammad Asif Shabbir | Network Support Engineer',
    siteDesc: 'Professional portfolio of Muhammad Asif Shabbir, a networking-focused IT professional with Pakistan Army leadership experience, CCNA in progress, Packet Tracer lab practice, and supporting web and Android development skills.',
    siteKeywords: 'Muhammad Asif Shabbir, Network Support Engineer, Junior Network Engineer, NOC Engineer, IT Support, CCNA in progress, Networking, Router and Switch Configuration, Troubleshooting, VLAN, OSPF, DHCP, NAT, ACL, Packet Tracer, Technical Instructor, Military Leadership, Web Development, Android Development'
  },
  hero: {
    availableTag: 'Open to Networking Opportunities',
    firstName: 'Muhammad Asif',
    highlightName: 'Shabbir',
    typedWords: ['Network Support', 'Router and Switch Labs', 'Packet Tracer Practice', 'Troubleshooting', 'IT Support', 'Technical Instruction'],
    description: 'Retired Pakistan Army professional transitioning into civilian IT as a <strong>Network Support Engineer</strong>, with <strong>CCNA in preparation</strong>, practical <strong>Packet Tracer lab experience</strong>, and supporting skills in web development, Android development, and AI-assisted project building.',
    orbitSpeed: 14,
    stats: [
      { number: 23, label: 'Years of Service' },
      { number: 2, label: 'COAS Commendations' },
      { number: 4, label: 'Target Markets' }
    ]
  },
  about: {
    heading: 'Retired Army Professional &',
    headingHighlight: 'Networking-Focused IT Candidate',
    text1: 'I am a retired Pakistan Army professional with service from <strong>28 Apr 2003 to 28 Apr 2026</strong>, including instructor duties, leadership exposure, operational responsibility, and structured performance-focused work. My background has built strong discipline, accountability, adaptability, and the ability to work effectively under pressure.',
    text2: 'My primary career direction is <strong>Networking</strong>. I am currently preparing for <strong>CCNA</strong> and have strong practical hands-on lab experience in <strong>Packet Tracer</strong>, including IP addressing, VLANs, routing, DHCP, NAT, ACLs, and troubleshooting. I also bring supporting skills in web development, Android development, MS Office, and AI-assisted project building, and I am open to roles in Pakistan, UAE, KSA, and Europe.',
    name: 'Muhammad Asif Shabbir',
    email: 'islammujahid921@gmail.com',
    location: 'Bahawalpur, Punjab, Pakistan',
    degree: 'BS IT - In Progress',
    expYears: '23+'
  },
  skills: {
    networking: [
      { name: 'IP Addressing and Subnetting', iconClass: 'fas fa-network-wired', iconColor: '#0ea5e9', level: 100 },
      { name: 'VLAN Configuration', iconClass: 'fas fa-diagram-project', iconColor: '#22c55e', level: 100 },
      { name: 'Inter-VLAN Routing', iconClass: 'fas fa-route', iconColor: '#06b6d4', level: 100 },
      { name: 'Static Routing', iconClass: 'fas fa-arrows-split-up-and-left', iconColor: '#2563eb', level: 100 },
      { name: 'OSPF', iconClass: 'fas fa-circle-nodes', iconColor: '#0284c7', level: 100 },
      { name: 'DHCP', iconClass: 'fas fa-server', iconColor: '#14b8a6', level: 100 },
      { name: 'NAT', iconClass: 'fas fa-shield-halved', iconColor: '#0f766e', level: 100 },
      { name: 'ACLs', iconClass: 'fas fa-list-check', iconColor: '#0f172a', level: 100 },
      { name: 'STP', iconClass: 'fas fa-sitemap', iconColor: '#1d4ed8', level: 100 },
      { name: 'EtherChannel', iconClass: 'fas fa-link', iconColor: '#0891b2', level: 100 },
      { name: 'Router and Switch Security', iconClass: 'fas fa-lock', iconColor: '#334155', level: 100 },
      { name: 'Network Troubleshooting', iconClass: 'fas fa-screwdriver-wrench', iconColor: '#ea580c', level: 100 },
      { name: 'Packet Tracer Lab Implementation', iconClass: 'fas fa-laptop-code', iconColor: '#6366f1', level: 100 }
    ],
    webDevelopment: [
      { name: 'HTML', iconClass: 'fab fa-html5', iconColor: '#e34f26', level: 100 },
      { name: 'CSS', iconClass: 'fab fa-css3-alt', iconColor: '#1572b6', level: 100 },
      { name: 'JavaScript', iconClass: 'fab fa-js-square', iconColor: '#f7df1e', level: 100 },
      { name: 'Bootstrap', iconClass: 'fab fa-bootstrap', iconColor: '#7952b3', level: 100 },
      { name: 'PHP', iconClass: 'fab fa-php', iconColor: '#8892be', level: 100 },
      { name: 'Laravel', iconClass: 'fab fa-laravel', iconColor: '#ff2d20', level: 100 },
      { name: 'WordPress', iconClass: 'fab fa-wordpress', iconColor: '#21759b', level: 100 }
    ],
    androidDevelopment: [
      { name: 'Java', iconClass: 'fab fa-java', iconColor: '#f89820', level: 100 },
      { name: 'Android Studio', iconClass: 'fab fa-android', iconColor: '#3ddc84', level: 100 },
      { name: 'Firebase', iconClass: 'fas fa-fire', iconColor: '#f59e0b', level: 100 }
    ],
    productivityTools: [
      { name: 'MS Word', iconClass: 'fas fa-file-word', iconColor: '#2563eb', level: 100 },
      { name: 'MS Excel', iconClass: 'fas fa-file-excel', iconColor: '#16a34a', level: 100 },
      { name: 'MS PowerPoint', iconClass: 'fas fa-file-powerpoint', iconColor: '#ea580c', level: 100 },
      { name: 'AI-Assisted Development', iconClass: 'fas fa-robot', iconColor: '#8b5cf6', level: 100 },
      { name: 'Vibe Coding', iconClass: 'fas fa-code', iconColor: '#7c3aed', level: 100 },
      { name: 'Building Projects Using AI Tools', iconClass: 'fas fa-wand-magic-sparkles', iconColor: '#db2777', level: 100 },
      { name: 'General Technical Problem Solving', iconClass: 'fas fa-puzzle-piece', iconColor: '#ca8a04', level: 100 }
    ],
    professionalStrengths: [
      { name: 'Leadership', iconClass: 'fas fa-user-shield', iconColor: '#0f172a', level: 100 },
      { name: 'Training and Instruction', iconClass: 'fas fa-chalkboard-user', iconColor: '#0891b2', level: 100 },
      { name: 'Discipline', iconClass: 'fas fa-medal', iconColor: '#dc2626', level: 100 },
      { name: 'Team Coordination', iconClass: 'fas fa-users', iconColor: '#2563eb', level: 100 },
      { name: 'Operations Support', iconClass: 'fas fa-compass-drafting', iconColor: '#475569', level: 100 },
      { name: 'Structured Execution', iconClass: 'fas fa-list-ol', iconColor: '#1d4ed8', level: 100 },
      { name: 'Attention to Detail', iconClass: 'fas fa-magnifying-glass', iconColor: '#0f766e', level: 100 },
      { name: 'Adaptability', iconClass: 'fas fa-arrows-rotate', iconColor: '#7c2d12', level: 100 }
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
      title: 'Havildar (Sergeant)',
      company: 'Pakistan Army',
      period: '28 Apr 2003 - 28 Apr 2026',
      location: 'Pakistan',
      desc: 'Completed more than two decades of disciplined military service with responsibilities spanning training environments, operations support, leadership, team coordination, and structured execution. Built a strong record of responsibility, consistency, and performance under pressure.',
      tags: ['Leadership', 'Discipline', 'Operations Support', 'Team Coordination'],
      iconClass: 'fas fa-shield'
    },
    {
      title: 'Weapons Training Instructor',
      company: 'Pakistan Army / SI&T Quetta',
      period: 'During military service',
      location: 'Quetta, Pakistan',
      desc: 'Served in instructor-focused roles with responsibility for structured training delivery, performance standards, evaluation, and disciplined knowledge transfer. This background directly supports technical instruction, support roles, and process-driven work in civilian IT.',
      tags: ['Training and Instruction', 'Performance Standards', 'Responsibility', 'Attention to Detail'],
      iconClass: 'fas fa-chalkboard-user'
    },
    {
      title: 'Networking Track - CCNA Under Preparation',
      company: 'Packet Tracer Lab Practice',
      period: 'Current focus',
      location: 'Bahawalpur, Punjab, Pakistan',
      desc: 'Actively preparing for CCNA with hands-on Packet Tracer lab work covering IP addressing, subnetting, VLANs, inter-VLAN routing, static routing, OSPF, DHCP, NAT, ACLs, STP, EtherChannel, basic device security, and troubleshooting.',
      tags: ['CCNA in Progress', 'Packet Tracer', 'Routing', 'Troubleshooting'],
      iconClass: 'fas fa-network-wired'
    }
  ],
  achievements: [
    {
      title: 'JNC Quetta',
      subtitle: 'AX2',
      period: '2018',
      highlight: '2nd Position',
      description: 'Completed the course with second position and received recognition for performance.'
    },
    {
      title: 'JLC at JLA Shinkiari Academy',
      subtitle: 'AX2',
      period: '2019',
      highlight: '3rd Position',
      description: 'Ranked third and earned position-based recognition during course completion.'
    },
    {
      title: 'Sniper Course',
      subtitle: 'Qualified',
      period: '2009',
      highlight: 'Qualified',
      description: 'Successfully completed the course and met qualification standards.'
    },
    {
      title: 'Unarmed Combat Course',
      subtitle: 'Military Training',
      period: '2010',
      highlight: 'Completed',
      description: 'Completed formal close-combat training as part of military professional development.'
    },
    {
      title: 'Desert Warfare Course at ADWS Chor Sindh',
      subtitle: 'AX2 Grade',
      period: '2012',
      highlight: '2nd Position',
      description: 'Completed the course with second position and earned formal recognition for performance.'
    },
    {
      title: 'Position Certificates in Multiple Courses',
      subtitle: 'Professional Military Training',
      period: '',
      highlight: 'Repeated Recognition',
      description: 'Received position certificates across multiple courses in recognition of consistent course performance.'
    },
    {
      title: 'COAS Commendation Cards',
      subtitle: 'Recognition for Course Performance and Outstanding Service',
      period: '',
      highlight: 'Awarded by Two Army Chiefs',
      description: 'Received COAS Commendation Cards from General Qamar Javed Bajwa and Field Marshal Asim Munir in recognition of professional excellence and service quality.'
    }
  ],
  education: [
    {
      title: 'BS IT',
      subtitle: 'Education',
      status: 'In Progress',
      description: 'Formal information technology studies currently in progress.'
    },
    {
      title: 'CCNA',
      subtitle: 'Certification Track',
      status: 'In Preparation',
      description: 'Currently preparing for CCNA with practical Packet Tracer labs and networking fundamentals.'
    }
  ],
  languages: [
    { name: 'Urdu', proficiency: 'Native / Strong' },
    { name: 'English', proficiency: 'Intermediate' },
    { name: 'Arabic', proficiency: 'Basic' }
  ],
  testimonials: [],
  contact: {
    heading: "Let's connect for networking and IT support opportunities",
    subtext: 'I am open to networking, NOC, IT support, and technical infrastructure opportunities in Pakistan, UAE, KSA, and Europe. I am also available for selected web, Android, and AI-assisted project work where relevant.',
    email: 'islammujahid921@gmail.com',
    phone: '03006859611',
    location: 'Bahawalpur, Punjab, Pakistan',
    portfolioUrl: 'https://foliofy.me/',
    resumeUrl: '',
    social: {
      github: '',
      linkedin: 'https://www.linkedin.com/in/asif-shabbiir/',
      twitter: '',
      dribbble: '',
      instagram: ''
    },
    whatsappApi: {
      enabled:      false,
      apiKey:       '',
      accountName:  '',
      targetNumber: ''
    }
  },
  footer: {
    tagline: 'Networking-focused IT professional with disciplined military leadership, technical instruction experience, and practical lab-based learning.'
  },
  images: {
    hero:  'profile.png',
    about: 'profile.png'
  }
};

/* Public API */
const clonePortfolioData = (obj) => JSON.parse(JSON.stringify(obj));

const mergePortfolioData = (base, overrides) => {
  const out = Object.assign({}, base);

  for (const key of Object.keys(overrides || {})) {
    const nextValue = overrides[key];
    const baseValue = base[key];

    if (
      nextValue !== null &&
      typeof nextValue === 'object' &&
      !Array.isArray(nextValue) &&
      baseValue !== null &&
      typeof baseValue === 'object' &&
      !Array.isArray(baseValue)
    ) {
      out[key] = mergePortfolioData(baseValue, nextValue);
      continue;
    }

    out[key] = nextValue;
  }

  return out;
};

const hasPortfolioContent = (value) => {
  if (Array.isArray(value)) {
    return value.length > 0;
  }

  if (value && typeof value === 'object') {
    return Object.values(value).some(hasPortfolioContent);
  }

  return value !== null && value !== undefined && value !== '';
};

const SERVER_DATA_STATE = (() => {
  if (typeof window === 'undefined' || typeof window.__PORTFOLIO_DATA__ !== 'object' || window.__PORTFOLIO_DATA__ === null) {
    return {};
  }

  return clonePortfolioData(window.__PORTFOLIO_DATA__);
})();

const syncWindowPortfolioData = () => {
  if (typeof window !== 'undefined') {
    window.__PORTFOLIO_DATA__ = clonePortfolioData(SERVER_DATA_STATE);
  }
};

const replaceServerPortfolioData = (data) => {
  Object.keys(SERVER_DATA_STATE).forEach((key) => delete SERVER_DATA_STATE[key]);
  Object.assign(SERVER_DATA_STATE, clonePortfolioData(data || {}));
  syncWindowPortfolioData();
};

const PortfolioData = {
  get() {
    if (!hasPortfolioContent(SERVER_DATA_STATE)) {
      return this.defaults();
    }

    return mergePortfolioData(this.defaults(), clonePortfolioData(SERVER_DATA_STATE));
  },

  save(data) {
    if (!data || typeof data !== 'object') return false;

    replaceServerPortfolioData(data);
    return true;
  },

  reset() {
    const defaults = this.defaults();
    replaceServerPortfolioData(defaults);
    return defaults;
  },

  defaults() {
    return clonePortfolioData(DEFAULT_DATA);
  },

  _clone(obj) {
    return clonePortfolioData(obj);
  },

  _merge(base, overrides) {
    return mergePortfolioData(base, overrides);
  }
};
