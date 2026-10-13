<?php if (!defined('IGNORE')) {  } ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajukan Permintaan Darah - SIDORA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/global.css?v=1780743553">
    <link rel="stylesheet" href="assets/css/pages/dashboard.css?v=1780742032">
    <style>


        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: var(--spacing-md);
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }

        .blood-type-selector {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: var(--spacing-md);
            margin-bottom: var(--spacing-lg);
        }

        .blood-type-btn {
            padding: var(--spacing-md);
            border: 2px solid var(--border-color);
            background: white;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: var(--transition);
            font-weight: 600;
            font-size: 1rem;
        }

        .blood-type-btn:hover {
            border-color: var(--color-primary);
            background-color: var(--color-primary-soft);
        }

        .blood-type-btn.selected {
            background-color: var(--color-primary);
            color: white;
            border-color: var(--color-primary);
        }

        .quantity-input {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
        }

        .quantity-btn {
            width: 36px;
            height: 36px;
            border: 1px solid var(--border-color);
            background: white;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-weight: 600;
            transition: var(--transition);
        }

        .quantity-btn:hover {
            background-color: var(--light-gray);
        }

        .quantity-input input {
            width: 60px;
            text-align: center;
        }
        
        .quantity-input input::-webkit-outer-spin-button,
        .quantity-input input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        
        .quantity-input input[type=number] {
            -moz-appearance: textfield;
        }

        .request-summary {
            background: var(--light-gray);
            border-radius: var(--border-radius);
            padding: var(--spacing-lg);
            margin-top: var(--spacing-lg);
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: var(--spacing-sm) 0;
            border-bottom: 1px solid var(--border-color);
        }

        .summary-item:last-child {
            border-bottom: none;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            padding: var(--spacing-md) 0;
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--primary-color);
        }

        .alert-info {
            background: #cffafe;
            border: 1px solid #a5f3fc;
            color: #164e63;
            padding: var(--spacing-md);
            border-radius: var(--border-radius);
            margin-bottom: var(--spacing-lg);
        }

        .alert-warning {
            background: #fef3c7;
            border: 1px solid #fde68a;
            color: #92400e;
            padding: var(--spacing-md);
            border-radius: var(--border-radius);
            margin-bottom: var(--spacing-lg);
        }

        .file-upload-box {
            border: 2px dashed var(--border-color);
            border-radius: var(--border-radius);
            padding: var(--spacing-lg);
            background: var(--light-gray);
            text-align: center;
            transition: var(--transition);
            cursor: pointer;
            position: relative;
        }

        .file-upload-box:hover {
            border-color: var(--primary-color);
            background: #fff5f5;
        }

        .file-upload-box input[type="file"] {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
        }

        .file-upload-icon {
            margin-bottom: var(--spacing-sm);
            color: var(--gray);
        }

        .file-upload-box p {
            margin: 0 0 4px 0;
            font-size: 0.95rem;
            color: var(--dark-gray);
            font-weight: 500;
        }

        .file-upload-box .file-name {
            font-size: 0.85rem;
            color: var(--primary-color);
            font-weight: 600;
            margin-top: 6px;
            display: none;
        }
    </style>
    </head>
<body>
    <div class="dashboard-layout">
        <nav class="navbar dashboard-header">
            <div class="navbar-left">
                <button class="navbar-toggle" id="sidebarToggle"></button>
                <a href="index.php" class="navbar-logo"><img src="assets/img/logo-sidora.png" alt="SIDORA" class="navbar-logo-img"></a>
            </div>

            <div class="navbar-right">
                <div class="navbar-user">
                    <div class="user-avatar">RS</div>
                    <span>Rumah Sakit</span>
                    
                </div>
            </div>
        </nav>

        <aside class="sidebar" id="sidebar">
            <ul class="sidebar-menu">
                <li class="sidebar-menu-item">
                    <a href="index.php?page=rs-dashboard" class="sidebar-menu-link">
                        
                        
<i data-lucide="layout-dashboard"></i> <span>Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-title">PERMINTAAN DARAH</li>
                
                <li class="sidebar-menu-item">
                    <a href="index.php?page=rs-permintaan" class="sidebar-menu-link active">
                        
                        
<i data-lucide="file-plus"></i> <span>Ajukan Permintaan</span>
                    </a>
                </li>

                <li class="sidebar-menu-item">
                    <a href="index.php?page=rs-history-permintaan" class="sidebar-menu-link">
                        
                        
<i data-lucide="file-text"></i> <span>Riwayat Permintaan</span>
                    </a>
                </li>

                <li class="sidebar-title">INFORMASI</li>
                
                <li class="sidebar-menu-item">
                    <a href="index.php?page=rs-stok-darah" class="sidebar-menu-link">
                        
                        
<i data-lucide="droplet"></i> <span>Lihat Stok Darah</span>
                    </a>
                </li>

                <li class="sidebar-title" id="pengaturanTitle">PENGATURAN</li>
                
                <li class="sidebar-menu-item" id="profilMenuItem">
                    <a href="index.php?page=rs-profil" class="sidebar-menu-link">
                        
                        
<i data-lucide="user"></i> <span>Profil</span>
                    </a>
                </li>

                <li class="sidebar-divider" style="margin: 0;"></li>

                <li class="sidebar-menu-item">
                    <a href="index.php?page=logout" class="sidebar-menu-link">
                        
                        
<i data-lucide="log-out"></i> <span>Logout</span>
                    </a>
                </li>
            </ul>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <div class="breadcrumb">
                    <a href="index.php?page=rs-dashboard">Dashboard</a>
                    <span>/</span>
                    <span>Ajukan Permintaan Darah</span>
                </div>

                <div class="page-title">
                    <h1>Ajukan Permintaan Darah</h1>
                </div>
            </div>

            <?php render_alerts(); ?>

            <div class="alert-info" style="margin-bottom: var(--spacing-lg);">
Permintaan darah Anda akan diproses maksimal 24 jam. Silakan hubungi kami jika ada pertanyaan.
            </div>

            <form id="permintaanForm" action="index.php?page=rs-permintaan-store" method="POST" class="card">
                    <h3 style="margin-top: 0;"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;margin-right:8px;"><circle cx="12" cy="12" r="10"/><polyline points="12 16 16 12 12 8"/><line x1="8" y1="12" x2="16" y2="12"/></svg>Tujuan Permintaan</h3>
                    <div class="form-group" style="margin-bottom: var(--spacing-lg);">
                        <div style="display: flex; gap: var(--spacing-lg);">
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                <input type="radio" name="tujuan_permintaan" value="pasien" checked onchange="toggleTujuan()">
                                <span>Khusus Pasien (Cito/Crossmatching)</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                <input type="radio" name="tujuan_permintaan" value="stok" onchange="toggleTujuan()">
                                <span>Penambahan Stok BDRS (Dropping)</span>
                            </label>
                        </div>
                    </div>

                    <div id="informasiPasienSection">
                        <hr style="margin: var(--spacing-2xl) 0; border: none; border-top: 1px solid var(--border-color);">
                        <h3><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;margin-right:8px;"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>Informasi Pasien</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="namaPasien" class="required">Nama Pasien</label>
                            <input type="text" id="namaPasien" name="nama_pasien" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="noPasien" class="required">No. Pasien/Rekam Medis</label>
                            <input type="text" id="noPasien" name="no_pasien" class="form-control" required>
                        </div>
                    </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="ruangan" class="required">Ruangan/Dept.</label>
                                <input type="text" id="ruangan" name="ruangan" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="dokter" class="required">Dokter Penanggungjawab</label>
                                <input type="text" id="dokter" name="dokter" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <hr style="margin: var(--spacing-2xl) 0; border: none; border-top: 1px solid var(--border-color);">
                    <h3 style="margin-bottom: var(--spacing-lg);"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;margin-right:8px;"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>Jenis Darah yang Dibutuhkan</h3>

                    <p style="font-size: 0.95rem; color: var(--gray); margin-bottom: var(--spacing-md);">Pilih golongan darah yang Anda butuhkan:</p>

                    <div class="blood-type-selector">
                        <button type="button" class="blood-type-btn" data-gol="O" data-rh="+" onclick="selectBloodType(this, 'O', '+')">O+</button>
                        <button type="button" class="blood-type-btn" data-gol="O" data-rh="-" onclick="selectBloodType(this, 'O', '-')">O-</button>
                        <button type="button" class="blood-type-btn" data-gol="A" data-rh="+" onclick="selectBloodType(this, 'A', '+')">A+</button>
                        <button type="button" class="blood-type-btn" data-gol="A" data-rh="-" onclick="selectBloodType(this, 'A', '-')">A-</button>
                        <button type="button" class="blood-type-btn" data-gol="B" data-rh="+" onclick="selectBloodType(this, 'B', '+')">B+</button>
                        <button type="button" class="blood-type-btn" data-gol="B" data-rh="-" onclick="selectBloodType(this, 'B', '-')">B-</button>
                        <button type="button" class="blood-type-btn" data-gol="AB" data-rh="+" onclick="selectBloodType(this, 'AB', '+')">AB+</button>
                        <button type="button" class="blood-type-btn" data-gol="AB" data-rh="-" onclick="selectBloodType(this, 'AB', '-')">AB-</button>
                    </div>

                    <input type="hidden" id="selectedGolongan" name="golongan" required>
                    <input type="hidden" id="selectedRhesus" name="rhesus" required>

                    <div class="form-group">
                        <label for="jumlah" class="required">Jumlah Kantong Darah</label>
                        <div class="quantity-input">
                            <button type="button" class="quantity-btn" onclick="decreaseQuantity()">-</button>
                            <input type="number" id="jumlah" name="jumlah" value="1" min="1" max="100" class="form-control" style="width: 80px;" oninput="updateSummaryQty(this.value)" onchange="this.value = Math.max(1, Math.min(100, parseInt(this.value) || 1)); updateSummaryQty(this.value);">
                            <button type="button" class="quantity-btn" onclick="increaseQuantity()">+</button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="keterangan">Keterangan/Catatan Khusus</label>
                        <textarea id="keterangan" name="keterangan" class="form-control" rows="3" placeholder="Misalnya: untuk operasi darurat, kondisi pasien kritis, dll"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="prioritas" class="required">Prioritas</label>
                        <select id="prioritas" name="prioritas" class="form-control" required>
                            <option value="">-- Pilih Prioritas --</option>
                            <option value="biasa">Biasa (Standar)</option>
                            <option value="segera">Segera (Urgent)</option>
                            <option value="darurat">Darurat (Emergency)</option>
                        </select>
                    </div>

                    <div style="background: #e0f2fe; border-left: 4px solid var(--primary-color); padding: var(--spacing-lg); border-radius: var(--border-radius); margin: var(--spacing-xl) 0;">
                        <h4 style="margin-top: 0; color: var(--dark-gray); display:flex; align-items:center; gap:8px;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/></svg> Ringkasan Permintaan</h4>
                        <div class="summary-item">
                            <span>Golongan Darah:</span>
                            <strong id="summaryBlood">-</strong>
                        </div>
                        <div class="summary-item">
                            <span>Jumlah Kantong:</span>
                            <strong id="summaryQty">1 Kantong</strong>
                        </div>
                        <div class="summary-item" style="border-bottom: none;">
                            <span>Prioritas:</span>
                            <strong id="summaryPriority">-</strong>
                        </div>
                    </div>

                    <div style="display: flex; gap: var(--spacing-md); margin-top: var(--spacing-2xl);">
                        <button type="reset" class="btn btn-outline" style="flex: 1;"><i data-lucide="rotate-ccw"></i> <span>Bersihkan Form</span></button>
                        <button type="submit" class="btn btn-primary-sidora" style="flex: 1;"><i data-lucide="send"></i> <span>Ajukan Permintaan</span>
                        </button>
                    </div>
                </form>
        </main>
    </div>
    
    <script>
        function selectBloodType(btn, gol, rh) {
            document.querySelectorAll('.blood-type-btn').forEach(b => b.classList.remove('selected'));
            btn.classList.add('selected');
            document.getElementById('selectedGolongan').value = gol;
            document.getElementById('selectedRhesus').value = rh;
            document.getElementById('summaryBlood').textContent = gol + rh;
        }

        function toggleTujuan() {
            const isStok = document.querySelector('input[name="tujuan_permintaan"]:checked').value === 'stok';
            const pasienSection = document.getElementById('informasiPasienSection');
            const inputs = pasienSection.querySelectorAll('input');
            
            if (isStok) {
                pasienSection.style.display = 'none';
                inputs.forEach(input => {
                    input.required = false;
                    input.value = ''; 
                });
            } else {
                pasienSection.style.display = 'block';
                inputs.forEach(input => input.required = true);
            }
        }

        function decreaseQuantity() {
            const input = document.getElementById('jumlah');
            let val = parseInt(input.value);
            if (val > 1) {
                input.value = val - 1;
                updateSummaryQty(val - 1);
            }
        }

        function increaseQuantity() {
            const input = document.getElementById('jumlah');
            let val = parseInt(input.value);
            if (val < 100) {
                input.value = val + 1;
                updateSummaryQty(val + 1);
            }
        }

        function updateSummaryQty(qty) {
            document.getElementById('summaryQty').textContent = qty + " Kantong";
        }

        document.getElementById('prioritas').addEventListener('change', function() {
            document.getElementById('summaryPriority').textContent = this.options[this.selectedIndex].text;
        });

        document.getElementById('permintaanForm').addEventListener('reset', function() {
            setTimeout(() => {
                document.querySelectorAll('.blood-type-btn').forEach(b => b.classList.remove('selected'));
                document.getElementById('summaryBlood').textContent = '-';
                document.getElementById('summaryQty').textContent = '1 Kantong';
                document.getElementById('summaryPriority').textContent = '-';
            }, 10);
        });
    </script>
    <script src="assets/js/sidebar.js"></script>

    <script src="assets/vendor/lucide/lucide.min.js"></script>
    <script>lucide.createIcons();</script>
</body>
</html>
