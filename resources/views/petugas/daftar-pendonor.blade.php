<?php if (!defined('IGNORE')) {  } ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="{{ asset('/') }}">
    <title>Daftar Pendonor - SIDORA Petugas</title>
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
        
        .badge {
            display: inline-block;
            padding: 0.35rem 0.75rem;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .badge-success {
            background-color: #dcfce7;
            color: #166534;
        }

        .info {
            background: white;
            padding: var(--spacing-lg);
            border-radius: var(--border-radius);
            border-left: 4px solid var(--info-color);
            margin-bottom: var(--spacing-lg);
        }

        .btn-info {
            background-color: var(--info-color);
            color: white;
        }

        .btn-info:hover:not(:disabled) {
            background-color: #0270cc;
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
                    <div class="user-avatar">PT</div>
                    <span>Petugas</span>
                    
                </div>
            </div>
        </nav>

        <aside class="sidebar" id="sidebar">
            <ul class="sidebar-menu">
                <li class="sidebar-menu-item">
                    <a href="index.php?page=petugas-dashboard" class="sidebar-menu-link">
                        
                        
<i data-lucide="layout-dashboard"></i> <span>Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-title">MANAJEMEN DATA</li>
                
                <li class="sidebar-menu-item">
                    <a href="index.php?page=petugas-daftar-pendonor" class="sidebar-menu-link active">
                        
                        
<i data-lucide="users"></i> <span>Daftar Pendonor</span>
                    </a>
                </li>

                <li class="sidebar-menu-item">
                    <a href="index.php?page=petugas-riwayat-donasi" class="sidebar-menu-link">
                        
                        
<i data-lucide="file-text"></i> <span>Riwayat Donasi</span>
                    </a>
                </li>
                
                <li class="sidebar-title">INFORMASI</li>

                <li class="sidebar-menu-item">
                    <a href="index.php?page=petugas-jadwal-donor" class="sidebar-menu-link">
                        
                        
<i data-lucide="calendar"></i> <span>Jadwal Donor</span>
                    </a>
                </li>

                <li class="sidebar-menu-item">
                    <a href="index.php?page=petugas-stok-darah" class="sidebar-menu-link">
                        
                        
<i data-lucide="droplet"></i> <span>Stok Darah</span>
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
                    <a href="index.php?page=petugas-dashboard">Dashboard</a>
                    <span>/</span>
                    <span>Daftar Pendonor</span>
                </div>

                <div class="page-title">
                    <h1>Daftar Pendonor</h1>
                    <div style="display: flex; gap: 8px;">
                        <button type="button" class="btn btn-primary-sidora" onclick="openTambahPendonorModal()"><i data-lucide="plus"></i> <span>Tambah Pendonor Baru</span></button>
                    </div>
                </div>
            </div>

            <?php render_alerts(); ?>

            <div class="card filter-card">
                <div class="filter-group" style="flex: 2; min-width: 200px;">
                    <label for="searchPendonor">Cari Pendonor</label>
                    <input type="text" id="searchPendonor" placeholder="Nama, No. Identitas, No. Telepon...">
                </div>

                <div class="filter-group">
                    <label for="filterGolDarah">Golongan Darah</label>
                    <select id="filterGolDarah">
                        <option value="">Semua Golongan Darah</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="filterStatus">Status</label>
                    <select id="filterStatus">
                        <option value="">Semua Status</option>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Non-aktif</option>
                    </select>
                </div>

                <button type="button" id="resetPendonorFilter" class="btn btn-outline-gray"><i data-lucide="rotate-ccw"></i> <span>Reset</span></button>
            </div>

            <div class="table-container">
                <div class="table-header">
                    <h3>Data Pendonor Terdaftar</h3>
                    <div class="table-actions">
                        <button type="button" class="btn btn-outline btn-outline-sidora" onclick="exportTableToCSV('tabelPendonor','Pendonor_SIDORA.csv')"><i data-lucide="file-output"></i> <span>Export CSV</span></button>
                    </div>
                </div>

                <div class="table-wrapper">
                    <table id="tabelPendonor">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Lengkap</th>
                                <th>Gol. Darah</th>
                                <th>No. Telepon</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($pendonorList) && count($pendonorList) > 0): $no=1; foreach ($pendonorList as $pendonor): 
                                $gol = strtoupper($pendonor['golongan'] ?? '');
                                $rh = $pendonor['rhesus'] ?? '';
                                $darah = trim($gol . $rh);
                                $status = strtolower($pendonor['status'] ?? 'aktif');
                            ?>
                            <tr data-golongan="<?= htmlspecialchars($gol) ?>"
                                data-rhesus="<?= htmlspecialchars($rh) ?>"
                                data-darah="<?= htmlspecialchars($darah) ?>"
                                data-status="<?= htmlspecialchars($status) ?>">
                                <td><?= $no++ ?></td>
                                <td><strong><?= htmlspecialchars(!empty($pendonor['nama']) ? $pendonor['nama'] : '-') ?></strong></td>
                                <td><span style="font-weight:600; color:var(--primary-color);"><?= htmlspecialchars((!empty($pendonor['golongan']) ? $pendonor['golongan'] : '-') . (!empty($pendonor['rhesus']) ? $pendonor['rhesus'] : '')) ?></span></td>
                                <td><?= htmlspecialchars(!empty($pendonor['telepon']) ? $pendonor['telepon'] : '-') ?></td>
                                <td>
                                    <?php $s=$pendonor['status']??'aktif'; ?>
                                    <span class="badge <?= $s === 'aktif' ? 'badge-success' : 'badge-warning' ?>">
                                        <?php if ($s === 'aktif'): ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><polyline points="20 6 9 17 4 12"/></svg> Aktif
                                        <?php else: ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;flex-shrink:0;"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg> Non-aktif
                                        <?php endif; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button type="button" class="btn btn-outline-sidora btn-small" onclick='openEditPendonorModal(<?= htmlspecialchars(json_encode($pendonor), ENT_QUOTES, "UTF-8") ?>)'>
                                            <i data-lucide="pencil"></i> <span>Edit</span>
                                        </button>
                                        <button type="button" class="btn btn-outline-sidora btn-small" onclick='openDetailPendonorModal(<?= htmlspecialchars(json_encode($pendonor), ENT_QUOTES, "UTF-8") ?>)'>
                                            <i data-lucide="eye"></i> <span>Detail</span>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-small" onclick="hapusPendonor(<?= $pendonor['id'] ?>)">
                                            <i data-lucide="trash-2"></i> <span>Hapus</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr><td colspan="7" style="text-align: center;">Belum ada data pendonor.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>



    
    <!-- Modal Tambah Pendonor Baru -->
    <div class="modal" id="tambahPendonorModal">
        <div class="modal-content" style="max-width: 850px; max-height: 90vh; overflow-y: auto;">
            <div class="modal-header">
                <h2>Tambah Pendonor Baru</h2>
                <button type="button" class="modal-close" onclick="closeModal('tambahPendonorModal')">&times;</button>
            </div>

            <form id="tambahPendonorForm" action="index.php?page=petugas-tambah-pendonor" method="POST">
                @csrf
                <div class="modal-body">

                    <h3 style="margin-top: 0; color: var(--primary-color); border-bottom: 2px solid #f1f5f9; padding-bottom: 8px;">
                        <i data-lucide="user" style="display:inline-block;vertical-align:middle;width:18px;height:18px;"></i> Data Pribadi
                    </h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="tambahNama" class="required">Nama Lengkap</label>
                            <input type="text" id="tambahNama" name="nama" class="form-control" placeholder="Nama lengkap pendonor" required>
                        </div>
                        <div class="form-group">
                            <label for="tambahNik" class="required">No. Identitas (NIK/KTP)</label>
                            <input type="text" id="tambahNik" name="no_identitas" class="form-control" placeholder="16 digit NIK pendonor" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="tambahGolDarah" class="required">Golongan Darah</label>
                            <select id="tambahGolDarah" name="golongan_darah" class="form-control" required onchange="syncRhesusFromGolDarah(this, 'tambahRhesus')">
                                <option value="">-- Pilih Golongan Darah --</option>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="AB">AB</option>
                                <option value="O">O</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tambahRhesus" class="required">Rhesus</label>
                            <select id="tambahRhesus" name="rhesus" class="form-control" required onchange="syncGolDarahFromRhesus(this, 'tambahGolDarah')">
                                <option value="+">Positif (+)</option>
                                <option value="-">Negatif (-)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="tambahTelepon" class="required">No. Telepon / WhatsApp</label>
                            <input type="tel" id="tambahTelepon" name="telepon" class="form-control" placeholder="08xxxxxxxxxx" required>
                        </div>
                        <div class="form-group">
                            <label for="tambahEmail">Email</label>
                            <input type="email" id="tambahEmail" name="email" class="form-control" placeholder="pendonor@email.com">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="tambahTanggalLahir" class="required">Tanggal Lahir</label>
                            <input type="date" id="tambahTanggalLahir" name="tanggal_lahir" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="tambahJenisKelamin" class="required">Jenis Kelamin</label>
                            <select id="tambahJenisKelamin" name="jenis_kelamin" class="form-control" required>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="tambahAlamat" class="required">Alamat Lengkap</label>
                        <textarea id="tambahAlamat" name="alamat" class="form-control" rows="2" placeholder="Alamat domisili lengkap..." required></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="tambahKota">Kota / Kabupaten</label>
                            <input type="text" id="tambahKota" name="kota" class="form-control" placeholder="Kota domisili">
                        </div>
                        <div class="form-group">
                            <label for="tambahProvinsi">Provinsi</label>
                            <input type="text" id="tambahProvinsi" name="provinsi" class="form-control" placeholder="Provinsi">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="tambahPekerjaan">Pekerjaan</label>
                            <input type="text" id="tambahPekerjaan" name="pekerjaan" class="form-control" placeholder="Pekerjaan pendonor">
                        </div>
                        <div class="form-group form-check" style="display:flex; align-items:center; gap:8px; padding-top:25px;">
                            <input type="checkbox" id="tambahStatusAktif" name="status" value="aktif" checked style="width:18px;height:18px;">
                            <label for="tambahStatusAktif" style="margin:0;font-weight:600;">Jadikan Pendonor Aktif</label>
                        </div>
                    </div>

                    <!-- DATA MEDIS KESEHATAN -->
                    <h3 style="margin-top: var(--spacing-xl); color: var(--primary-color); border-bottom: 2px solid #f1f5f9; padding-bottom: 8px;">
                        <i data-lucide="activity" style="display:inline-block;vertical-align:middle;width:18px;height:18px;"></i> Data Pemeriksaan Medis
                    </h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="tambahTekananDarah" class="required">Tekanan Darah (Sistol/Diastol)</label>
                            <input type="text" id="tambahTekananDarah" name="tekanan_darah" class="form-control" placeholder="Misal: 120/80" value="120/80" required>
                        </div>
                        <div class="form-group">
                            <label for="tambahDetakJantung">Detak Jantung (bpm)</label>
                            <input type="text" id="tambahDetakJantung" name="detak_jantung" class="form-control" placeholder="Misal: 72" value="72">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="tambahHemoglobin">Hemoglobin (g/dL)</label>
                            <input type="text" id="tambahHemoglobin" name="hemoglobin" class="form-control" placeholder="Misal: 14.5" value="14.5">
                        </div>
                        <div class="form-group">
                            <label for="tambahSuhuTubuh">Suhu Tubuh (°C)</label>
                            <input type="text" id="tambahSuhuTubuh" name="suhu_tubuh" class="form-control" placeholder="Misal: 36.5" value="36.5">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="tambahKesehatanUmum">Kesehatan Umum</label>
                            <select id="tambahKesehatanUmum" name="kesehatan_umum" class="form-control">
                                <option value="Sehat Baik" selected>Sehat Baik</option>
                                <option value="Agak Sakit">Agak Sakit</option>
                                <option value="Sakit">Sakit</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tambahCatatan">Catatan Medis</label>
                            <input type="text" id="tambahCatatan" name="catatan" class="form-control" placeholder="Catatan kondisi fisik pendonor...">
                        </div>
                    </div>

                    <!-- RINCIAN DONASI -->
                    <h3 style="margin-top: var(--spacing-xl); color: var(--primary-color); border-bottom: 2px solid #f1f5f9; padding-bottom: 8px;">
                        <i data-lucide="droplet" style="display:inline-block;vertical-align:middle;width:18px;height:18px;"></i> Rincian Donasi Awal
                    </h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="tambahJumlahKantong" class="required">Jumlah Kantong</label>
                            <input type="number" id="tambahJumlahKantong" name="jumlah" class="form-control" value="1" min="1" max="10" required>
                        </div>
                        <div class="form-group">
                            <label for="tambahStatusDonasi" class="required">Status Donasi</label>
                            <select id="tambahStatusDonasi" name="status_donasi" class="form-control" required>
                                <option value="Berhasil" selected>Berhasil (Stok Darah Bertambah)</option>
                                <option value="Ditolak">Ditolak (Stok Darah Tidak Bertambah)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-gray" onclick="closeModal('tambahPendonorModal')"><i data-lucide="x"></i> <span>Batal</span></button>
                    <button type="submit" class="btn btn-primary-sidora"><i data-lucide="save"></i> <span>Simpan Pendonor & Donasi</span></button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Pendonor -->
    <div class="modal" id="editPendonorModal">
        <div class="modal-content" style="max-width: 850px; max-height: 90vh; overflow-y: auto;">
            <div class="modal-header">
                <h2>Edit Pendonor</h2>
                <button type="button" class="modal-close" onclick="closeModal('editPendonorModal')">&times;</button>
            </div>

            <form id="editPendonorForm" action="index.php?page=petugas-edit-pendonor" method="POST">
                @csrf
                <input type="hidden" id="editPendonorId" name="id">
                <div class="modal-body">
                    <h3 style="margin-top: 0; color: var(--primary-color); border-bottom: 2px solid #f1f5f9; padding-bottom: 8px;">
                        <i data-lucide="user" style="display:inline-block;vertical-align:middle;width:18px;height:18px;"></i> Data Pribadi
                    </h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="editNamaPendonor" class="required">Nama Lengkap</label>
                            <input type="text" id="editNamaPendonor" name="nama" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="editNoIdentitas" class="required">No. Identitas (KTP/NIK)</label>
                            <input type="text" id="editNoIdentitas" name="nik" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="editGolDarah" class="required">Golongan Darah</label>
                            <select id="editGolDarah" name="golongan_darah" class="form-control" required onchange="syncRhesusFromGolDarah(this, 'editRhesus')">
                                <option value="">-- Pilih Golongan Darah --</option>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="AB">AB</option>
                                <option value="O">O</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editRhesus" class="required">Rhesus</label>
                            <select id="editRhesus" name="rhesus" class="form-control" required onchange="syncGolDarahFromRhesus(this, 'editGolDarah')">
                                <option value="+">Positif (+)</option>
                                <option value="-">Negatif (-)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="editNomorTelepon" class="required">No. Telepon</label>
                            <input type="tel" id="editNomorTelepon" name="telepon" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="editEmail">Email</label>
                            <input type="email" id="editEmail" name="email" class="form-control">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="editTanggalLahir" class="required">Tanggal Lahir</label>
                            <input type="date" id="editTanggalLahir" name="tanggal_lahir" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="editJenisKelamin" class="required">Jenis Kelamin</label>
                            <select id="editJenisKelamin" name="jenis_kelamin" class="form-control" required>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="editAlamat" class="required">Alamat</label>
                        <textarea id="editAlamat" name="alamat" class="form-control" rows="2" required></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="editKota">Kota</label>
                            <input type="text" id="editKota" name="kota" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="editProvinsi">Provinsi</label>
                            <input type="text" id="editProvinsi" name="provinsi" class="form-control">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="editPekerjaan">Pekerjaan</label>
                            <input type="text" id="editPekerjaan" name="pekerjaan" class="form-control">
                        </div>
                        <div class="form-group form-check" style="display: flex; align-items: center; gap: 8px; padding-top:25px;">
                            <input type="checkbox" id="editStatusAktif" name="status" value="aktif" style="width:18px;height:18px;">
                            <label for="editStatusAktif" style="margin:0;font-weight:600;">Pendonor Aktif</label>
                        </div>
                    </div>

                    <!-- DATA MEDIS KESEHATAN PENDONOR -->
                    <h3 style="margin-top: var(--spacing-lg); color: var(--primary-color); border-bottom: 2px solid #f1f5f9; padding-bottom: 8px;">
                        <i data-lucide="activity" style="display:inline-block;vertical-align:middle;width:18px;height:18px;"></i> Data Pemeriksaan Medis
                    </h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="editTekananDarah">Tekanan Darah (Sistol/Diastol)</label>
                            <input type="text" id="editTekananDarah" name="tekanan_darah" class="form-control" placeholder="Misal: 120/80">
                        </div>
                        <div class="form-group">
                            <label for="editDetakJantung">Detak Jantung (bpm)</label>
                            <input type="text" id="editDetakJantung" name="detak_jantung" class="form-control" placeholder="Misal: 72">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="editHemoglobin">Hemoglobin (g/dL)</label>
                            <input type="text" id="editHemoglobin" name="hemoglobin" class="form-control" placeholder="Misal: 14.5">
                        </div>
                        <div class="form-group">
                            <label for="editSuhuTubuh">Suhu Tubuh (°C)</label>
                            <input type="text" id="editSuhuTubuh" name="suhu_tubuh" class="form-control" placeholder="Misal: 36.5">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="editKesehatanUmum">Kesehatan Umum</label>
                            <select id="editKesehatanUmum" name="kesehatan_umum" class="form-control">
                                <option value="Sehat Baik">Sehat Baik</option>
                                <option value="Agak Sakit">Agak Sakit</option>
                                <option value="Sakit">Sakit</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editCatatan">Catatan Medis</label>
                            <input type="text" id="editCatatan" name="catatan" class="form-control" placeholder="Catatan kondisi medis pendonor...">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-gray" onclick="closeModal('editPendonorModal')"><i data-lucide="x"></i> <span>Batal</span></button>
                    <button type="submit" class="btn btn-primary-sidora"><i data-lucide="save"></i> <span>Simpan Perubahan</span></button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Detail Pendonor -->
    <div class="modal" id="detailPendonorModal">
        <div class="modal-content" style="max-width: 750px; max-height: 90vh; overflow-y: auto;">
            <div class="modal-header">
                <h2>Detail Pendonor</h2>
                <button type="button" class="modal-close" onclick="closeModal('detailPendonorModal')">&times;</button>
            </div>
            <div class="modal-body" id="detailPendonorBody"></div>
        </div>
    </div>

    <form id="hapusPendonorForm" method="POST" action="index.php?page=petugas-hapus-pendonor" style="display:none;">
        @csrf
        <input type="hidden" name="id" id="hapusPendonorId">
    </form>

    <script src="assets/js/sidebar.js"></script>
    <script src="assets/js/modals.js"></script>
    <script src="assets/js/table-actions.js?v=1780743555"></script>
    <script>
        function openTambahPendonorModal() {
            const form = document.getElementById('tambahPendonorForm');
            if (form) form.reset();
            const modal = document.getElementById('tambahPendonorModal');
            if (modal) modal.classList.add('active');
            if (window.lucide) window.lucide.createIcons();
        }

        function hapusPendonor(id) {
            if (confirm('Hapus pendonor ini? Data riwayat donasi terkait mungkin juga terpengaruh.')) {
                document.getElementById('hapusPendonorId').value = id;
                document.getElementById('hapusPendonorForm').submit();
            }
        }

        function syncRhesusFromGolDarah(golSelect, rhesusSelectId) {
            const val = (golSelect.value || '').trim();
            const rhesusSelect = document.getElementById(rhesusSelectId);
            if (!rhesusSelect) return;
            if (val.endsWith('+')) {
                rhesusSelect.value = '+';
            } else if (val.endsWith('-')) {
                rhesusSelect.value = '-';
            }
        }

        function syncGolDarahFromRhesus(rhesusSelect, golSelectId) {
            const rh = (rhesusSelect.value || '').trim();
            const golSelect = document.getElementById(golSelectId);
            if (!golSelect) return;
            const curVal = golSelect.value || '';
            const base = curVal.replace(/[+-]/g, '').trim().toUpperCase();
            if (base && ['A', 'B', 'AB', 'O'].includes(base)) {
                const candidate = base + rh;
                for (let i = 0; i < golSelect.options.length; i++) {
                    if (golSelect.options[i].value === candidate) {
                        golSelect.value = candidate;
                        break;
                    }
                }
            }
        }

        function openEditPendonorModal(data) {
            document.getElementById('editPendonorId').value = data.id || '';
            document.getElementById('editNamaPendonor').value = data.nama || '';
            document.getElementById('editNoIdentitas').value = data.nik || '';

            const editGolSelect = document.getElementById('editGolDarah');
            const fullGol = ((data.golongan || '') + (data.rhesus || '')).trim().toUpperCase();
            let matched = false;
            if (fullGol && editGolSelect) {
                for (let i = 0; i < editGolSelect.options.length; i++) {
                    if (editGolSelect.options[i].value.toUpperCase() === fullGol) {
                        editGolSelect.value = editGolSelect.options[i].value;
                        matched = true;
                        break;
                    }
                }
            }
            if (!matched && editGolSelect) {
                editGolSelect.value = data.golongan || 'A';
            }
            document.getElementById('editRhesus').value = data.rhesus || '+';
            document.getElementById('editNomorTelepon').value = data.telepon || '';
            document.getElementById('editEmail').value = data.email || '';
            document.getElementById('editTanggalLahir').value = data.tanggal_lahir || data.tgl_lahir || '';
            document.getElementById('editJenisKelamin').value = data.jenis_kelamin || 'Laki-laki';
            document.getElementById('editAlamat').value = data.alamat || '';
            document.getElementById('editKota').value = data.kota || '';
            document.getElementById('editProvinsi').value = data.provinsi || '';
            document.getElementById('editPekerjaan').value = data.pekerjaan || '';
            document.getElementById('editStatusAktif').checked = (data.status === 'aktif' || !data.status);

            // Medical fields
            if (document.getElementById('editTekananDarah')) {
                document.getElementById('editTekananDarah').value = data.tekanan_darah || '120/80';
            }
            if (document.getElementById('editDetakJantung')) {
                document.getElementById('editDetakJantung').value = data.detak_jantung || '72';
            }
            if (document.getElementById('editHemoglobin')) {
                document.getElementById('editHemoglobin').value = data.hemoglobin || '14.5';
            }
            if (document.getElementById('editSuhuTubuh')) {
                document.getElementById('editSuhuTubuh').value = data.suhu_tubuh || '36.5';
            }
            if (document.getElementById('editKesehatanUmum')) {
                document.getElementById('editKesehatanUmum').value = data.kesehatan_umum || 'Sehat Baik';
            }
            if (document.getElementById('editCatatan')) {
                document.getElementById('editCatatan').value = data.catatan || '';
            }

            document.getElementById('editPendonorModal').classList.add('active');
            if (window.lucide) window.lucide.createIcons();
        }

        function openDetailPendonorModal(data) {
            const html = `
                <div class="detail-grid">
                    <div class="detail-item full-width" style="background:transparent;border:none;padding:0;">
                        <h4 style="margin:0;color:var(--primary-color);border-bottom:1px solid #e2e8f0;padding-bottom:5px;">Data Pribadi Pendonor (ID: #${data.id || '-'})</h4>
                    </div>
                    <div class="detail-item full-width">
                        <p class="detail-label">Nama Lengkap</p>
                        <p class="detail-value"><strong>${data.nama || '-'}</strong></p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">No. Identitas (NIK)</p>
                        <p class="detail-value">${data.nik || '-'}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Jenis Kelamin</p>
                        <p class="detail-value">${data.jenis_kelamin || '-'}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Tanggal Lahir</p>
                        <p class="detail-value">${data.tanggal_lahir || data.tgl_lahir || '-'}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Golongan Darah</p>
                        <p class="detail-value" style="font-size:1.1rem;font-weight:700;color:var(--primary-color);">${data.golongan || '-'}${data.rhesus || ''}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">No. Telepon / WA</p>
                        <p class="detail-value">${data.telepon || '-'}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Pekerjaan</p>
                        <p class="detail-value">${data.pekerjaan || '-'}</p>
                    </div>
                    <div class="detail-item full-width">
                        <p class="detail-label">Alamat Lengkap</p>
                        <p class="detail-value">${data.alamat || '-'} ${data.kota ? '(' + data.kota + ')' : ''}</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Status</p>
                        <p class="detail-value"><span class="badge ${(!data.status || data.status === 'aktif') ? 'badge-success' : 'badge-warning'}">${data.status || 'Aktif'}</span></p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Terakhir Donor</p>
                        <p class="detail-value">${data.terakhir_donor || data.tgl_donor || 'Belum donor'}</p>
                    </div>

                    <div class="detail-item full-width" style="background:transparent;border:none;padding:0;margin-top:10px;">
                        <h4 style="margin:0;color:var(--primary-color);border-bottom:1px solid #e2e8f0;padding-bottom:5px;">Data Pemeriksaan Medis</h4>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Tekanan Darah</p>
                        <p class="detail-value"><strong>${data.tekanan_darah || '120/80'}</strong> mmHg</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Detak Jantung</p>
                        <p class="detail-value">${data.detak_jantung || '72'} bpm</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Hemoglobin</p>
                        <p class="detail-value">${data.hemoglobin || '14.5'} g/dL</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Suhu Tubuh</p>
                        <p class="detail-value">${data.suhu_tubuh || '36.5'} °C</p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Kesehatan Umum</p>
                        <p class="detail-value"><span class="badge badge-success">${data.kesehatan_umum || 'Sehat Baik'}</span></p>
                    </div>
                    <div class="detail-item">
                        <p class="detail-label">Catatan Medis</p>
                        <p class="detail-value">${data.catatan || 'Kondisi fisik normal & sehat'}</p>
                    </div>
                </div>
            `;
            document.getElementById('detailPendonorBody').innerHTML = html;
            document.getElementById('detailPendonorModal').classList.add('active');
            if (window.lucide) window.lucide.createIcons();
        }

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchPendonor');
            const golSelect = document.getElementById('filterGolDarah');
            const statusSelect = document.getElementById('filterStatus');
            const tableBody = document.querySelector('#tabelPendonor tbody');
            const rows = tableBody ? tableBody.querySelectorAll('tr') : [];
            const resetBtn = document.getElementById('resetPendonorFilter');

            function filterTable() {
                const searchVal = (searchInput ? searchInput.value : '').toLowerCase().trim();
                const filterGol = (golSelect ? golSelect.value : '').toUpperCase().trim();
                const filterStatus = (statusSelect ? statusSelect.value : '').toLowerCase().trim();

                rows.forEach(row => {
                    if (row.cells.length <= 1) return;

                    const text = row.textContent.toLowerCase();
                    const rowGol = (row.getAttribute('data-golongan') || '').toUpperCase().trim();
                    const rowDarah = (row.getAttribute('data-darah') || '').toUpperCase().trim();
                    const rowStatus = (row.getAttribute('data-status') || '').toLowerCase().trim();

                    // Search match (Nama, NIK, No. Telepon, etc.)
                    const matchSearch = searchVal === '' || text.includes(searchVal);

                    // Golongan match:
                    // If filterGol is "A", it matches rows with rowGol === "A" (both A+ and A-).
                    // If filterGol is "A+", it matches rows with rowDarah === "A+".
                    let matchGol = false;
                    if (!filterGol) {
                        matchGol = true;
                    } else if (filterGol === rowDarah) {
                        matchGol = true;
                    } else if (filterGol === rowGol) {
                        matchGol = true;
                    }

                    // Status match:
                    let matchStatus = false;
                    if (!filterStatus) {
                        matchStatus = true;
                    } else if (filterStatus === rowStatus) {
                        matchStatus = true;
                    } else if (filterStatus === 'nonaktif' && (rowStatus === 'non-aktif' || rowStatus === 'nonaktif')) {
                        matchStatus = true;
                    }

                    row.style.display = (matchSearch && matchGol && matchStatus) ? '' : 'none';
                });
            }

            if (searchInput) searchInput.addEventListener('input', filterTable);
            if (golSelect) golSelect.addEventListener('change', filterTable);
            if (statusSelect) statusSelect.addEventListener('change', filterTable);

            if (resetBtn) {
                resetBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (searchInput) searchInput.value = '';
                    if (golSelect) golSelect.value = '';
                    if (statusSelect) statusSelect.value = '';
                    filterTable();
                });
            }
        });
    </script>

    <script src="assets/vendor/lucide/lucide.min.js"></script>
    <script>lucide.createIcons();</script>
</body>
</html>
