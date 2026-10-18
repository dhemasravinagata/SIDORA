<?php if (!defined('IGNORE')) {  } ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="{{ asset('/') }}">
    <title>SIDORA - Sistem Informasi Donor Darah</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/global.css?v=1780743553">
    <style>
        .navbar-public {
            background: #1D1616;
            padding: 0;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
        }
        .navbar-inner {
            max-width: 100%;
            margin: 0 auto;
            padding: 0 var(--spacing-lg);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        
        .navbar-actions { display: flex; gap: 12px; }
        .btn-outline-white {
            box-sizing: border-box;
            background: transparent;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 40px;
            color: white;
            border: 2px solid rgba(255,255,255,0.7);
            padding: 8px 20px;
            border-radius: var(--border-radius);
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            text-decoration: none;
            transition: var(--transition);
        }
        .btn-outline-white:hover {
            border-color: white;
            background: rgba(255,255,255,0.1);
            text-decoration: none;
            color: white;
        }

        .hero {
            background: #8E1616;
            color: white;
            padding: 100px var(--spacing-lg);
            text-align: center;
            min-height: 340px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .hero h1 {
            color: white;
            font-size: 2.8rem;
            margin-bottom: var(--spacing-base);
            font-weight: 700;
        }
        .hero p {
            color: rgba(255,255,255,0.9);
            font-size: 1.05rem;
            max-width: 540px;
            margin: 0 auto var(--spacing-lg);
            line-height: 1.75;
        }
        .hero-btns { display: flex; justify-content: center; gap: 14px; flex-wrap: wrap; }
        .btn-light-hero {
            box-sizing: border-box;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 50px !important;
            background: white;
            color: #8E1616;
            font-weight: 600;
            font-size: 15px !important;
            padding: 0 32px !important;
            border-radius: var(--border-radius);
            text-decoration: none;
            font-family: 'Poppins', sans-serif;
            transition: var(--transition);
            border: 2px solid white !important;
            line-height: 1 !important;
        }
        .btn-light-hero:hover {
            background: transparent;
            color: white;
            text-decoration: none;
        }

        .hero-btns .btn-outline-white {
            height: 50px !important;
            padding: 0 32px !important;
            font-size: 15px !important;
            line-height: 1 !important;
            border: 2px solid white !important;
        }

        .features-section {
            padding: 70px var(--spacing-lg);
            background: var(--light-gray);
        }
        .section-title { text-align: center; margin-bottom: var(--spacing-2xl); }
        .section-title h2 { color: var(--dark-gray); }
        .section-title p  { color: var(--gray); margin: 0; }
        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: var(--spacing-lg);
            max-width: 1000px;
            margin: 0 auto;
        }
        .feature-card {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            padding: var(--spacing-lg) var(--spacing-md);
            text-align: center;
            box-shadow: var(--shadow);
            transition: var(--transition);
        }
        .feature-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.1); transform: translateY(-3px); }
        .feature-card .icon { font-size: 3rem; color: #8E1616; margin-bottom: var(--spacing-md); }
        .feature-card h3 { font-size: 18px; color: var(--dark-gray); margin-bottom: var(--spacing-sm); }
        .feature-card p  { color: var(--gray); font-size: 14px; line-height: 1.6; margin: 0; }

        .simple-contact {
            padding: 80px var(--spacing-lg);
            background: white;
            text-align: center;
            border-top: 1px solid var(--border-color);
        }
        .simple-contact-header {
            margin-bottom: 50px;
        }
        .simple-contact-header h2 {
            color: var(--dark-gray);
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .simple-contact-header p {
            color: var(--gray);
            font-size: 16px;
            max-width: 600px;
            margin: 0 auto;
        }
        .simple-contact-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            max-width: 1000px;
            margin: 0 auto;
        }
        .simple-contact-card {
            padding: 40px 20px;
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.06);
            transition: all 0.3s ease;
        }
        .simple-contact-card:hover {
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            border-color: #8E1616;
            transform: translateY(-5px);
        }
        .simple-contact-card i {
            font-size: 32px;
            color: #8E1616;
            margin-bottom: 20px;
        }
        .simple-contact-card h3 {
            font-size: 18px;
            color: var(--dark-gray);
            margin-bottom: 10px;
            font-weight: 600;
        }
        .simple-contact-card p {
            font-size: 15px;
            color: var(--gray);
            line-height: 1.6;
            margin: 0;
        }

        @media (max-width: 768px) {
            .simple-contact-grid { grid-template-columns: 1fr; }
        }

        .footer-pub {
            background: #1D1616;
            color: rgba(255,255,255,0.5);
            text-align: center;
            padding: var(--spacing-lg);
            font-size: 14px;
        }
        .footer-pub p { margin: 0; }

        @media (max-width: 768px) {
            .hero h1 { font-size: 2rem; }
            .features-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <nav class="navbar-public landing-navbar">
        <div class="navbar-inner">
            <a href="index.php" class="navbar-logo"><img src="assets/img/logo-sidora.png" alt="SIDORA" class="navbar-logo-img"></a>
            <div class="navbar-actions">
                <a href="index.php?page=login" class="btn-outline-white">Masuk</a>
                <a href="index.php?page=register-rs" class="btn btn-primary-sidora" style="padding: 8px 20px; border: 2px solid transparent; box-sizing: border-box; display: inline-flex; align-items: center; justify-content: center; min-height: 40px;">Daftar Rumah Sakit</a>
            </div>
        </div>
    </nav>

    <section class="hero">
        <h1>Selamat Datang di SIDORA</h1>
        <p>Sistem Informasi Donor Darah berbasis web untuk pengelolaan data pendonor, stok darah, dan permintaan secara terintegrasi.</p>
        <div class="hero-btns">
            <a href="index.php?page=login" class="btn-light-hero">Login Sistem</a>
            <a href="#features" class="btn-outline-white">Pelajari Lebih Lanjut</a>
        </div>
    </section>

    <section id="features" class="features-section">
        <div class="section-title">
            <h2>Fitur Utama</h2>
            <p>Kemudahan dalam pengelolaan donor darah</p>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <div class="icon"><i class="fa-solid fa-users"></i></div>
                <h3>Manajemen Pendonor</h3>
                <p>Kelola data pendonor dan riwayat donasi dengan mudah dan terstruktur.</p>
            </div>
            <div class="feature-card">
                <div class="icon"><i class="fa-solid fa-vial"></i></div>
                <h3>Monitoring Stok Darah</h3>
                <p>Pantau ketersediaan stok darah berbagai golongan secara real-time.</p>
            </div>
            <div class="feature-card">
                <div class="icon"><i class="fa-solid fa-truck-medical"></i></div>
                <h3>Permintaan Darah</h3>
                <p>Fasilitas bagi Rumah Sakit untuk mengajukan permintaan darah dengan cepat.</p>
            </div>
        </div>
    </section>

    <section id="contact" class="simple-contact">
        <div class="simple-contact-header">
            <h2>Hubungi Kami</h2>
            <p>Punya pertanyaan atau butuh bantuan terkait layanan SIDORA? Jangan ragu untuk menghubungi tim kami melalui saluran di bawah ini.</p>
        </div>
        <div class="simple-contact-grid">
            <div class="simple-contact-card">
                <i class="fa-solid fa-phone"></i>
                <h3>Telepon / WhatsApp</h3>
                <p>+62 812 3456 7890<br>Senin - Jumat (08:00 - 16:00)</p>
            </div>
            <div class="simple-contact-card">
                <i class="fa-solid fa-envelope"></i>
                <h3>Email</h3>
                <p>cs@sidora.com<br>info@sidora.com</p>
            </div>
            <div class="simple-contact-card">
                <i class="fa-solid fa-location-dot"></i>
                <h3>Alamat</h3>
                <p>Jl. PMI No. 123<br>Bandar Lampung, Indonesia</p>
            </div>
        </div>
    </section>

    <footer class="footer-pub">
        <p>&copy; 2026 SIDORA - Sistem Informasi Donor Darah. All rights reserved.</p>
    </footer>


    <script src="assets/vendor/lucide/lucide.min.js"></script>
    <script>lucide.createIcons();</script>
</body>
</html>
