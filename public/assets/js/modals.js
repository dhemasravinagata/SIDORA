


function openModal(modalId, permintaanId = null) {
    const modal = document.getElementById(modalId);
    if (!modal) return;
    modal.classList.add('active');
    
    if (permintaanId !== null) {
        const input = modal.querySelector('input[name="permintaan_id"], input[name="id"]');
        if (input) input.value = permintaanId;
    }
}


function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) modal.classList.remove('active');
}


document.addEventListener('click', function (e) {
    if (e.target.classList.contains('modal')) {
        e.target.classList.remove('active');
    }
});


document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal.active').forEach(m => m.classList.remove('active'));
    }
});


function openDetailModal(data) {
    if (typeof data === 'string') {
        try { data = JSON.parse(data); } catch(e) { return; }
    }

    const status = data.status || 'Pending';
    const statusColors = {
        'Pending':   'background:#FEF3C7;color:#92400E;',
        'Disetujui': 'background:#D1FAE5;color:#065F46;',
        'Dikirim':   'background:#DBEAFE;color:#1E40AF;',
        'Ditolak':   'background:#FEE2E2;color:#991B1B;',
    };
    const badgeStyle = statusColors[status] || statusColors['Pending'];

    let html = `
        <div class="detail-row"><span class="detail-label">ID Permintaan</span><strong>#REQ-${data.id || '-'}</strong></div>
        <div class="detail-row"><span class="detail-label">Tanggal Dibuat</span>${data.created_at || data.tanggal || '-'}</div>
        <div class="detail-row"><span class="detail-label">Rumah Sakit</span>${data.rumah_sakit || '-'}</div>
        <div class="detail-row"><span class="detail-label">Golongan Darah</span><strong>${(data.golongan || '-') + (data.rhesus || '')}</strong></div>
        <div class="detail-row"><span class="detail-label">Jumlah Kantong</span>${data.detail_jumlah || data.jumlah || 0}</div>
        <div class="detail-row"><span class="detail-label">Keterangan</span>${data.keterangan || '-'}</div>
        <div class="detail-row"><span class="detail-label">Prioritas</span>${data.prioritas || '-'}</div>
        <div class="detail-row"><span class="detail-label">Status</span>
            <span style="display:inline-block;padding:0.3rem 0.8rem;border-radius:999px;font-weight:600;font-size:0.82rem;${badgeStyle}">${status}</span>
        </div>
    `;

    if (status === 'Ditolak') {
        html += `
        <div class="detail-row"><span class="detail-label">Alasan Penolakan</span>${data.alasan_tolak || '-'}</div>
        <div class="detail-row"><span class="detail-label">Tanggal Ditolak</span>${data.tanggal_tolak || '-'}</div>`;
    } else if (status === 'Dikirim') {
        html += `
        <div class="detail-row"><span class="detail-label">Kurir</span>${data.kurir || '-'}</div>
        <div class="detail-row"><span class="detail-label">Tanggal Kirim</span>${data.tanggal_kirim || '-'}</div>`;
    }

    const body = document.getElementById('detailBody');
    if (body) body.innerHTML = html;

    const modal = document.getElementById('detailModal');
    if (modal) modal.classList.add('active');
}


function openTolakModal(id) {
    const el = document.getElementById('tolakId');
    if (el) el.value = id;
    openModal('tolakModal');
}


function openKirimModal(id) {
    const modal = document.getElementById('kirimModal');
    if (!modal) return;
    const input = modal.querySelector('input[name="permintaan_id"]');
    if (input) input.value = id;
    modal.classList.add('active');
}


function openDetailPendonorModal(data) {
    if (typeof data === 'string') {
        try { data = JSON.parse(data); } catch(e) { return; }
    }

    const statusStyle = data.status === 'aktif'
        ? 'background:#D1FAE5;color:#065F46;'
        : 'background:#FEE2E2;color:#991B1B;';

    const html = `
        <div class="detail-row"><span class="detail-label">Nama</span>${data.nama || '-'}</div>
        <div class="detail-row"><span class="detail-label">NIK</span>${data.nik || '-'}</div>
        <div class="detail-row"><span class="detail-label">Golongan Darah</span><strong>${(data.golongan || '-') + (data.rhesus || '')}</strong></div>
        <div class="detail-row"><span class="detail-label">Jenis Kelamin</span>${data.jenis_kelamin || '-'}</div>
        <div class="detail-row"><span class="detail-label">Tanggal Lahir</span>${data.tanggal_lahir || '-'}</div>
        <div class="detail-row"><span class="detail-label">Telepon</span>${data.telepon || '-'}</div>
        <div class="detail-row"><span class="detail-label">Email</span>${data.email || '-'}</div>
        <div class="detail-row"><span class="detail-label">Alamat</span>${data.alamat || '-'}, ${data.kota || ''} ${data.provinsi || ''}</div>
        <div class="detail-row"><span class="detail-label">Pekerjaan</span>${data.pekerjaan || '-'}</div>
        <div class="detail-row"><span class="detail-label">Status</span>
            <span style="display:inline-block;padding:0.3rem 0.8rem;border-radius:999px;font-weight:600;font-size:0.82rem;${statusStyle}">${data.status || '-'}</span>
        </div>
    `;

    const body = document.getElementById('detailPendonorBody');
    if (body) body.innerHTML = html;
    openModal('detailPendonorModal');
}


function openEditPendonorModal(data) {
    if (typeof data === 'string') {
        try { data = JSON.parse(data); } catch(e) { return; }
    }
    const modal = document.getElementById('editPendonorModal');
    if (!modal) return;

    const fields = ['id', 'nama', 'nik', 'telepon', 'email', 'alamat', 'kota', 'provinsi', 'pekerjaan', 'tanggal_lahir'];
    fields.forEach(f => {
        const el = modal.querySelector(`[name="${f}"]`);
        if (el) el.value = data[f] || '';
    });
    
    const golEl = modal.querySelector('[name="golongan_darah"]');
    const rhEl  = modal.querySelector('[name="rhesus"]');
    if (golEl) golEl.value = data.golongan || '';
    if (rhEl)  rhEl.value  = data.rhesus  || '';
    
    const statEl = modal.querySelector('[name="status"]');
    if (statEl) statEl.value = data.status || 'aktif';
    
    const jkEl = modal.querySelector('[name="jenis_kelamin"]');
    if (jkEl) jkEl.value = data.jenis_kelamin || '';

    modal.classList.add('active');
}


function openEditPetugasModal(data) {
    if (typeof data === 'string') {
        try { data = JSON.parse(data); } catch(e) { return; }
    }
    const modal = document.getElementById('editPetugasModal');
    if (!modal) return;

    ['id', 'nama', 'email', 'telepon'].forEach(f => {
        const el = modal.querySelector(`[name="${f}"]`);
        if (el) el.value = data[f] || data['name'] || '';
    });
    const nameEl = modal.querySelector('[name="nama"]');
    if (nameEl) nameEl.value = data.name || '';
    const statEl = modal.querySelector('[name="status"]');
    if (statEl) statEl.value = data.status || 'aktif';

    modal.classList.add('active');
}


function openHapusModal(modalId, id) {
    const modal = document.getElementById(modalId);
    if (!modal) return;
    const input = modal.querySelector('input[name="id"]');
    if (input) input.value = id;
    modal.classList.add('active');
}


function openDetailJadwalModal(data) {
    if (typeof data === 'string') {
        try { data = JSON.parse(data); } catch(e) { return; }
    }
    const html = `
        <div class="detail-row"><span class="detail-label">Lokasi</span>${data.lokasi || '-'}</div>
        <div class="detail-row"><span class="detail-label">Tanggal</span>${data.tanggal || '-'}</div>
        <div class="detail-row"><span class="detail-label">Target Pendonor</span>${data.target || 0} orang</div>
        <div class="detail-row"><span class="detail-label">Sudah Terdaftar</span>${data.terdaftar || 0} orang</div>
    `;
    const body = document.getElementById('detailJadwalBody');
    if (body) body.innerHTML = html;
    openModal('detailJadwalModal');
}


function openEditJadwalModal(data) {
    if (typeof data === 'string') {
        try { data = JSON.parse(data); } catch(e) { return; }
    }
    const modal = document.getElementById('editJadwalModal');
    if (!modal) return;
    ['id', 'lokasi', 'tanggal', 'target'].forEach(f => {
        const el = modal.querySelector(`[name="${f}"]`);
        if (el) el.value = data[f] || '';
    });
    modal.classList.add('active');
}


function openDetailRiwayatModal(data) {
    if (typeof data === 'string') {
        try { data = JSON.parse(data); } catch(e) { return; }
    }
    const statusStyle = data.status === 'Berhasil'
        ? 'background:#D1FAE5;color:#065F46;'
        : 'background:#FEE2E2;color:#991B1B;';
    const html = `
        <div class="detail-row"><span class="detail-label">Pendonor</span>${data.nama_pendonor || '-'}</div>
        <div class="detail-row"><span class="detail-label">Golongan Darah</span><strong>${(data.golongan || '-') + (data.rhesus || '')}</strong></div>
        <div class="detail-row"><span class="detail-label">Jumlah</span>${data.jumlah || 0} kantong</div>
        <div class="detail-row"><span class="detail-label">Tanggal Donor</span>${data.tanggal || '-'}</div>
        <div class="detail-row"><span class="detail-label">Tekanan Darah</span>${data.tekanan_darah || '-'}</div>
        <div class="detail-row"><span class="detail-label">Status</span>
            <span style="display:inline-block;padding:0.3rem 0.8rem;border-radius:999px;font-weight:600;font-size:0.82rem;${statusStyle}">${data.status || '-'}</span>
        </div>
    `;
    const body = document.getElementById('detailRiwayatBody');
    if (body) body.innerHTML = html;
    openModal('detailRiwayatModal');
}


function openDetailPermintaanModal(data) {
    if (typeof data === 'string') {
        try { data = JSON.parse(data); } catch(e) { return; }
    }
    openDetailModal(data); 
}
