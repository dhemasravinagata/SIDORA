function openProsesDitinjauModal() {
    document.getElementById('prosesDitinjauModal').classList.add('active');
}

function openKirimModal() {
    document.getElementById('kirimModal').classList.add('active');
}

function openDetailModal(status) {
    if (status === 'ditinjau') {
        document.getElementById('detailDitinjauModal').classList.add('active');
    } else if (status === 'disetujui') {
        document.getElementById('detailDisetujuiModal').classList.add('active');
    } else if (status === 'dikirim') {
        document.getElementById('detailDikirimModal').classList.add('active');
    } else if (status === 'ditolak') {
        document.getElementById('detailDitolakModal').classList.add('active');
    }
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
}

document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('active');
        }
    });
});

function prosesSetujui() {
    alert('Permintaan telah disetujui!');
    closeModal('prosesDitinjauModal');
}

function prosesTolak() {
    alert('Permintaan telah ditolak dengan alasan: ' + document.getElementById('catatanAdmin').value);
    closeModal('prosesDitinjauModal');
}

function konfirmasiKirim() {
    const namaKurir = document.getElementById('namaKurir').value;
    if (!namaKurir) {
        alert('Harap masukkan nama kurir terlebih dahulu');
        return;
    }
    alert('Pengiriman telah dikonfirmasi oleh: ' + namaKurir);
    closeModal('kirimModal');
}

document.getElementById('sidebarToggle').addEventListener('click', function() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('active');
});
