<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventify — Online Event Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --orange: #FF8C00;
            --orange-deep: #ff4500;
            --orange-glow: rgba(255,140,0,0.4);
            --surface: rgba(255,255,255,0.04);
            --border: rgba(255,255,255,0.08);
            --muted: rgba(255,255,255,0.42);
        }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Inter', Arial, sans-serif;
            background: #080818;
            color: #fff;
            overflow-x: hidden;
        }

        /* ── Animated Mesh Background ── */
        .mesh-bg {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 0;
            background:
                radial-gradient(ellipse 90% 70% at 10% 0%, rgba(255,100,0,0.2) 0%, transparent 55%),
                radial-gradient(ellipse 70% 80% at 90% 20%, rgba(100,50,255,0.18) 0%, transparent 55%),
                radial-gradient(ellipse 60% 60% at 50% 100%, rgba(0,150,255,0.12) 0%, transparent 55%),
                #080818;
            pointer-events: none;
        }
        .mesh-bg::before {
            content: ''; position: absolute; inset: 0;
            background-image:
                radial-gradient(circle 1.5px at 20% 15%, rgba(255,140,0,0.7) 0%, transparent 0%),
                radial-gradient(circle 1px at 42% 58%, rgba(150,100,255,0.6) 0%, transparent 0%),
                radial-gradient(circle 1.5px at 72% 28%, rgba(0,200,255,0.6) 0%, transparent 0%),
                radial-gradient(circle 1px at 88% 72%, rgba(255,140,0,0.5) 0%, transparent 0%),
                radial-gradient(circle 1px at 8% 82%, rgba(150,100,255,0.5) 0%, transparent 0%),
                radial-gradient(circle 2px at 62% 8%, rgba(255,200,0,0.6) 0%, transparent 0%),
                radial-gradient(circle 1px at 33% 92%, rgba(0,255,200,0.5) 0%, transparent 0%),
                radial-gradient(circle 1px at 78% 48%, rgba(255,100,150,0.5) 0%, transparent 0%),
                radial-gradient(circle 1px at 55% 35%, rgba(200,150,255,0.4) 0%, transparent 0%),
                radial-gradient(circle 1.5px at 15% 55%, rgba(255,200,0,0.4) 0%, transparent 0%);
            background-size: 22% 22%;
        }

        /* ── Navbar ── */
        .navbar-custom {
            position: sticky; top: 0; z-index: 100;
            display: flex; align-items: center; justify-content: space-between;
            padding: 14px 56px;
            background: rgba(8,8,24,0.8);
            border-bottom: 1px solid rgba(255,255,255,0.06);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
        }
        .logo { display: flex; align-items: center; gap: 12px; text-decoration: none; }

        /* 3D Spinning Cube Logo */
        .logo-cube {
            width: 38px; height: 38px;
            position: relative;
            transform-style: preserve-3d;
            animation: spinCube 9s linear infinite;
        }
        @keyframes spinCube {
            0%   { transform: rotateX(20deg) rotateY(0deg); }
            100% { transform: rotateX(20deg) rotateY(360deg); }
        }
        .cube-face {
            position: absolute; width: 38px; height: 38px;
            display: flex; align-items: center; justify-content: center;
            font-size: 16px; font-weight: 900; color: #fff;
            border: 1px solid rgba(255,140,0,0.35);
        }
        .cf-front  { background: rgba(255,130,0,0.9);  transform: translateZ(19px); }
        .cf-back   { background: rgba(200,80,0,0.7);   transform: rotateY(180deg) translateZ(19px); }
        .cf-left   { background: rgba(180,60,0,0.65);  transform: rotateY(-90deg) translateZ(19px); }
        .cf-right  { background: rgba(220,100,0,0.75); transform: rotateY(90deg)  translateZ(19px); }
        .cf-top    { background: rgba(255,160,0,0.85); transform: rotateX(90deg)  translateZ(19px); }
        .cf-bottom { background: rgba(160,50,0,0.55);  transform: rotateX(-90deg) translateZ(19px); }

        .logo-text { font-size: 1.25rem; font-weight: 800; color: #fff; letter-spacing: -0.3px; }
        .logo-text span { color: var(--orange); }

        .nav-links-custom { display: flex; align-items: center; gap: 4px; list-style: none; }
        .nav-links-custom a {
            color: rgba(255,255,255,0.55); font-size: 13.5px; font-weight: 500;
            text-decoration: none; padding: 8px 14px; border-radius: 9px;
            transition: all 0.2s;
        }
        .nav-links-custom a:hover { color: #fff; background: rgba(255,255,255,0.07); }
        .nav-cta {
            background: linear-gradient(135deg, var(--orange), var(--orange-deep)) !important;
            color: #fff !important; font-weight: 700 !important;
            box-shadow: 0 4px 18px var(--orange-glow); margin-left: 8px;
        }
        .nav-cta:hover { transform: translateY(-1px); box-shadow: 0 8px 28px rgba(255,140,0,0.55) !important; }
        .navbar-toggler {
            border: 1px solid rgba(255,255,255,0.15); border-radius: 8px; padding: 6px 10px;
            background: rgba(255,255,255,0.04);
        }
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3E%3Cpath stroke='rgba(255,255,255,0.75)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
        }

        /* ── Hero ── */
        .hero {
            position: relative; z-index: 1;
            min-height: 90vh;
            display: flex; align-items: center; justify-content: center;
            text-align: center; padding: 80px 24px 60px;
        }
        .hero-inner { max-width: 700px; }
        .badge-pill {
            display: inline-flex; align-items: center; gap: 9px;
            background: rgba(255,140,0,0.1); border: 1px solid rgba(255,140,0,0.28);
            color: #FFB347; font-size: 11px; font-weight: 700;
            letter-spacing: 1.2px; padding: 6px 18px; border-radius: 99px;
            margin-bottom: 28px; text-transform: uppercase;
        }
        .badge-dot {
            width: 6px; height: 6px; border-radius: 50%; background: var(--orange);
            animation: blink 2s ease-in-out infinite;
        }
        @keyframes blink { 0%,100%{opacity:1;} 50%{opacity:0.3;} }
        .hero h1 {
            font-size: clamp(2.8rem, 6vw, 4.8rem); font-weight: 900;
            letter-spacing: -2.5px; line-height: 1.06; margin-bottom: 20px;
            background: linear-gradient(160deg, #fff 30%, rgba(255,255,255,0.6));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }
        .hero h1 .accent {
            background: linear-gradient(135deg, #FF8C00, #ff4500, #ffb300);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }
        .hero p { font-size: 1.1rem; color: var(--muted); max-width: 500px; margin: 0 auto 44px; line-height: 1.75; }
        .cta-group { display: flex; align-items: center; justify-content: center; gap: 14px; flex-wrap: wrap; }
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--orange), var(--orange-deep));
            color: #fff; font-size: 15px; font-weight: 700;
            padding: 15px 38px; border: none; border-radius: 13px; cursor: pointer;
            box-shadow: 0 8px 30px var(--orange-glow); transition: all 0.25s;
            text-decoration: none; display: inline-block;
        }
        .btn-primary-custom:hover { transform: translateY(-2px); box-shadow: 0 14px 40px rgba(255,140,0,0.55); color: #fff; }
        .btn-ghost {
            background: rgba(255,255,255,0.05); color: rgba(255,255,255,0.8);
            font-size: 15px; font-weight: 600; padding: 15px 34px;
            border: 1px solid rgba(255,255,255,0.12); border-radius: 13px;
            cursor: pointer; transition: all 0.25s; text-decoration: none; display: inline-block;
        }
        .btn-ghost:hover { background: rgba(255,255,255,0.09); border-color: rgba(255,255,255,0.22); color: #fff; }

        /* ── Stats Bar ── */
        .stats-bar {
            position: relative; z-index: 1;
            display: flex; align-items: center; justify-content: center;
            margin: 0 56px 80px; padding: 40px;
            background: rgba(255,255,255,0.025);
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 24px;
        }
        .stat-item { text-align: center; flex: 1; }
        .stat-num { font-size: 2.2rem; font-weight: 900; color: var(--orange); letter-spacing: -1.5px; line-height: 1; }
        .stat-label { font-size: 10.5px; color: rgba(255,255,255,0.3); font-weight: 600; letter-spacing: 1px; text-transform: uppercase; margin-top: 6px; }
        .stat-sep { width: 1px; height: 50px; background: rgba(255,255,255,0.06); }

        /* ── Section Helpers ── */
        .section-divider { height: 1px; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.05), transparent); margin: 0 56px; }
        .section { position: relative; z-index: 1; padding: 90px 56px; }
        .sec-tag {
            font-size: 10.5px; font-weight: 700; letter-spacing: 2px;
            text-transform: uppercase; color: var(--orange); margin-bottom: 14px;
            display: flex; align-items: center; gap: 8px;
        }
        .sec-tag::before { content: ''; display: block; width: 18px; height: 1.5px; background: var(--orange); }
        .sec-title { font-size: clamp(1.8rem, 2.8vw, 2.4rem); font-weight: 800; letter-spacing: -1px; line-height: 1.15; margin-bottom: 8px; }
        .sec-sub { font-size: 0.95rem; color: var(--muted); line-height: 1.75; max-width: 440px; }

        /* ── 3D Image Showcase ── */
        .showcase-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 22px; perspective: 1400px; }
        .img-3d-wrap {
            transform-style: preserve-3d;
            transition: transform 0.5s cubic-bezier(0.23,1,0.32,1);
        }
        .img-frame {
            border-radius: 22px; overflow: hidden;
            border: 1px solid rgba(255,255,255,0.07);
            box-shadow: 0 24px 64px rgba(0,0,0,0.55);
            position: relative;
        }
        .img-frame img { width: 100%; height: 290px; object-fit: cover; display: block; transition: transform 0.5s; }
        .img-3d-wrap:hover .img-frame img { transform: scale(1.04); }
        .img-overlay-glass {
            position: absolute; inset: 0;
            background: linear-gradient(to top, rgba(8,8,24,0.72) 0%, transparent 55%);
            display: flex; align-items: flex-end; padding: 22px;
            pointer-events: none;
        }
        .img-tag {
            font-size: 12px; font-weight: 600; color: rgba(255,255,255,0.88);
            background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.14);
            padding: 5px 14px; border-radius: 99px; backdrop-filter: blur(12px);
        }

        /* ── 3D Feature Cards ── */
        .features-3d-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 20px; }
        .card-3d {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 22px; padding: 36px 28px;
            transform-style: preserve-3d;
            transition: transform 0.35s cubic-bezier(0.23,1,0.32,1), box-shadow 0.35s, background 0.25s, border-color 0.25s;
            cursor: default; position: relative; overflow: hidden;
        }
        .card-3d::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,140,0,0.7), transparent);
            opacity: 0; transition: opacity 0.3s;
        }
        .card-3d:hover {
            box-shadow: 0 30px 70px rgba(0,0,0,0.55), 0 0 40px rgba(255,140,0,0.1), inset 0 1px 0 rgba(255,255,255,0.08);
            border-color: rgba(255,140,0,0.25); background: rgba(255,255,255,0.06);
        }
        .card-3d:hover::before { opacity: 1; }
        .card-icon-3d {
            width: 56px; height: 56px; border-radius: 16px;
            background: rgba(255,140,0,0.1); border: 1px solid rgba(255,140,0,0.2);
            display: flex; align-items: center; justify-content: center;
            font-size: 24px; margin-bottom: 22px;
            transition: all 0.3s;
        }
        .card-3d:hover .card-icon-3d { background: rgba(255,140,0,0.2); border-color: rgba(255,140,0,0.4); box-shadow: 0 0 22px rgba(255,140,0,0.25); }
        .card-3d h4 { font-size: 1rem; font-weight: 700; margin-bottom: 10px; color: #fff; }
        .card-3d p { font-size: 0.875rem; color: var(--muted); line-height: 1.72; }

        /* ── CTA Banner ── */
        .cta-banner {
            position: relative; z-index: 1;
            margin: 0 56px 90px;
            background: linear-gradient(135deg, rgba(255,140,0,0.1) 0%, rgba(255,69,0,0.07) 100%);
            border: 1px solid rgba(255,140,0,0.18);
            border-radius: 26px; padding: 64px; text-align: center; overflow: hidden;
        }
        .cta-banner::before {
            content: ''; position: absolute; top: -50%; left: 50%; transform: translateX(-50%);
            width: 600px; height: 400px; border-radius: 50%;
            background: radial-gradient(circle, rgba(255,140,0,0.1) 0%, transparent 70%);
            pointer-events: none;
        }
        .cta-banner h2 { font-size: clamp(1.8rem, 3vw, 2.4rem); font-weight: 800; letter-spacing: -1px; margin-bottom: 14px; }
        .cta-banner p { font-size: 1rem; color: var(--muted); margin-bottom: 36px; }

        /* ── Footer ── */
        footer {
            position: relative; z-index: 1;
            padding: 40px 56px;
            background: rgba(255,255,255,0.015);
            border-top: 1px solid rgba(255,255,255,0.05);
        }
        .footer-inner { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; }
        .footer-logo { font-size: 1.1rem; font-weight: 800; color: #fff; }
        .footer-logo span { color: var(--orange); }
        .footer-info { font-size: 11.5px; color: rgba(255,255,255,0.25); margin-top: 4px; }
        .footer-links { display: flex; gap: 22px; }
        .footer-links a { font-size: 12.5px; color: rgba(255,255,255,0.28); text-decoration: none; transition: color 0.2s; }
        .footer-links a:hover { color: var(--orange); }
        .footer-copy { font-size: 11.5px; color: rgba(255,255,255,0.18); text-align: center; margin-top: 22px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.04); }

        /* ── Responsive ── */
        @media (max-width: 992px) {
            .navbar-custom { padding: 14px 24px; }
            .section { padding: 64px 24px; }
            .section-divider, .cta-banner { margin-left: 24px; margin-right: 24px; }
            .features-3d-grid { grid-template-columns: 1fr; }
            .showcase-grid { grid-template-columns: 1fr; }
            .stats-bar { margin: 0 24px 60px; flex-wrap: wrap; gap: 24px; }
            .stat-sep { display: none; }
            footer { padding: 32px 24px; }
        }
    </style>
</head>
<body>

<div class="mesh-bg"></div>

<!-- Navbar -->
<nav class="navbar-custom navbar navbar-expand-lg">
    <a class="logo" href="#">
        <div class="logo-cube">
            <div class="cube-face cf-front">E</div>
            <div class="cube-face cf-back"></div>
            <div class="cube-face cf-left"></div>
            <div class="cube-face cf-right"></div>
            <div class="cube-face cf-top"></div>
            <div class="cube-face cf-bottom"></div>
        </div>
        <span class="logo-text">Event<span>ify</span></span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="nav-links-custom ms-auto navbar-nav">
            <li><a href="login.html">Login</a></li>
            <li><a href="signup.html">Sign Up</a></li>
            <li><a href="contact.html">Contact Us</a></li>
            <li><a href="about.html">About Us</a></li>
           <li><a href="login.html" class="nav-cta">Get Started</a></li>
        </ul>
    </div>
</nav>

<!-- Hero -->
<section class="hero">
    <div class="hero-inner">
        <div class="badge-pill">
            <span class="badge-dot"></span>
            Now Live — Event Season 2025
        </div>
        <h1>Manage Events<br><span class="accent">Like Never Before</span></h1>
        <p>Your journey to seamless event planning starts here. Create, manage, and grow your events with zero friction.</p>
        <div class="cta-group">
          <?php if (isset($_SESSION['user_id'])): ?>
    <a href="register.php" class="btn-primary-custom">🎟 Create Your Event</a>
<?php else: ?>
    <a href="login.html" class="btn-primary-custom">🎟 Create Your Event</a>
<?php endif; ?>
            <a href="#gallery" class="btn-ghost">Explore Events →</a>
        </div>
    </div>
</section>

<!-- Stats -->
<div class="stats-bar">
    <div class="stat-item"><div class="stat-num">12K+</div><div class="stat-label">Events Hosted</div></div>
    <div class="stat-sep"></div>
    <div class="stat-item"><div class="stat-num">98%</div><div class="stat-label">Satisfaction Rate</div></div>
    <div class="stat-sep"></div>
    <div class="stat-item"><div class="stat-num">200+</div><div class="stat-label">Countries</div></div>
    <div class="stat-sep"></div>
    <div class="stat-item"><div class="stat-num">4.9★</div><div class="stat-label">Avg Rating</div></div>
</div>

<!-- Image Showcase -->
<section class="section" id="gallery">
    <div style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:36px;gap:24px;flex-wrap:wrap;">
        <div>
            <div class="sec-tag">Gallery</div>
            <div class="sec-title">Real Events,<br>Real Moments</div>
        </div>
        <div class="sec-sub">From intimate gatherings to massive conferences — we power them all, beautifully.</div>
    </div>
    <div class="showcase-grid">
        <div class="img-3d-wrap" id="img3d-1">
            <div class="img-frame">
                <img src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=900&q=85" alt="Celebration crowd">
                <div class="img-overlay-glass"><div class="img-tag">Celebration Events</div></div>
            </div>
        </div>
        <div class="img-3d-wrap" id="img3d-2">
            <div class="img-frame">
                <img src="https://images.unsplash.com/photo-1511795409834-ef04bbd61622?w=900&q=85" alt="Conference hall">
                <div class="img-overlay-glass"><div class="img-tag">Corporate Conferences</div></div>
            </div>
        </div>
    </div>
</section>

<div class="section-divider"></div>

<!-- Features -->
<section class="section">
    <div class="sec-tag">Why Eventify</div>
    <div class="sec-title" style="margin-bottom:40px;">Everything You Need,<br>Nothing You Don't</div>
    <div class="features-3d-grid">
        <div class="card-3d">
            <div class="card-icon-3d"><i class="bi bi-palette2" style="color:var(--orange);font-size:22px;"></i></div>
            <h4>Custom Themes</h4>
            <p>Hundreds of curated event themes to make your celebration truly unique and unforgettable.</p>
        </div>
        <div class="card-3d">
            <div class="card-icon-3d"><i class="bi bi-wallet2" style="color:var(--orange);font-size:22px;"></i></div>
            <h4>Smart Pricing</h4>
            <p>Transparent, flexible plans with zero hidden fees — pure value for every event size and budget.</p>
        </div>
        <div class="card-3d">
            <div class="card-icon-3d"><i class="bi bi-lightning-charge" style="color:var(--orange);font-size:22px;"></i></div>
            <h4>Lightning Fast</h4>
            <p>Registration to check-in in seconds. Built for speed so you never miss a single moment.</p>
        </div>
        <div class="card-3d">
            <div class="card-icon-3d"><i class="bi bi-shield-check" style="color:var(--orange);font-size:22px;"></i></div>
            <h4>Secure & Reliable</h4>
            <p>Enterprise-grade security with 99.9% uptime guarantee. Your data is always protected.</p>
        </div>
        <div class="card-3d">
            <div class="card-icon-3d"><i class="bi bi-bar-chart-line" style="color:var(--orange);font-size:22px;"></i></div>
            <h4>Live Analytics</h4>
            <p>Real-time dashboards for registrations, revenue, and attendance — all in one place.</p>
        </div>
        <div class="card-3d">
            <div class="card-icon-3d"><i class="bi bi-headset" style="color:var(--orange);font-size:22px;"></i></div>
            <h4>24/7 Support</h4>
            <p>Our expert team is always on standby — get instant help any time, day or night.</p>
        </div>
    </div>
</section>

<!-- CTA Banner -->
<div class="cta-banner">
    <h2>Ready to Host Your Next Event?</h2>
    <p>Join thousands of event organizers who trust Eventify to deliver unforgettable experiences.</p>
    <?php if (isset($_SESSION['user_id'])): ?>
    <a href="register.php" class="btn-primary-custom">🎟 Create Your Event</a>
<?php else: ?>
    <a href="login.html" class="btn-primary-custom">🎟 Create Your Event</a>
<?php endif; ?>
</div>

<!-- Footer -->
<footer>
    <div class="footer-inner">
        <div>
            <div class="footer-logo">Event<span>ify</span></div>
            <div class="footer-info">123 Event Street, Celebration City · support@eventify.com · +1 (123) 456-7890</div>
        </div>
        <div class="footer-links">
            <a href="#">Privacy Policy</a>
            <a href="#">Terms of Service</a>
            <a href="contact.html">Help Center</a>
            <a href="about.html">About Us</a>
        </div>
    </div>
    <div class="footer-copy">© 2024 Eventify. All rights reserved.</div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // 3D mouse-tracking tilt on feature cards
    document.querySelectorAll('.card-3d').forEach(card => {
        card.addEventListener('mousemove', e => {
            const r = card.getBoundingClientRect();
            const x = (e.clientX - r.left) / r.width - 0.5;
            const y = (e.clientY - r.top)  / r.height - 0.5;
            card.style.transform = `rotateX(${-y * 18}deg) rotateY(${x * 18}deg) translateZ(20px) scale(1.04)`;
        });
        card.addEventListener('mouseleave', () => { card.style.transform = ''; });
    });

    // 3D mouse-tracking tilt on images
    [['img3d-1', 1], ['img3d-2', -1]].forEach(([id, dir]) => {
        const el = document.getElementById(id);
        if (!el) return;
        el.addEventListener('mousemove', e => {
            const r = el.getBoundingClientRect();
            const x = (e.clientX - r.left) / r.width - 0.5;
            const y = (e.clientY - r.top)  / r.height - 0.5;
            el.style.transform = `rotateX(${-y * 12}deg) rotateY(${x * 14 * dir}deg) scale(1.02)`;
        });
        el.addEventListener('mouseleave', () => { el.style.transform = ''; });
    });
</script>
</body>
</html>