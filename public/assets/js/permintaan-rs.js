function handleFileChange(input) {
    const fileName = document.getElementById('fileName');
    const uploadBox = document.getElementById('uploadBox');
    if (input.files && input.files[0]) {
        fileName.textContent = '📎 ' + input.files[0].name;
        fileName.style.display = 'block';
        uploadBox.style.borderColor = 'var(--primary-color)';
        uploadBox.style.background = '#fff5f5';
    } else {
        fileName.style.display = 'none';
        uploadBox.style.borderColor = '';
        uploadBox.style.background = '';
    }
}

let selectedBloodType = null;

function selectBloodType(btn, type) {
    document.querySelectorAll('.blood-type-btn').forEach(b => {
        b.classList.remove('selected');
    });

    btn.classList.add('selected');
    selectedBloodType = type;
    document.getElementById('selectedBloodType').value = type;
    
    document.getElementById('summaryBlood').textContent = type;
    updateSummary();
}

function increaseQuantity() {
    const input = document.getElementById('jumlah');
    input.value = Math.min(100, parseInt(input.value) + 1);
    document.getElementById('summaryQty').textContent = input.value + ' kantong';
    updateSummary();
}

function decreaseQuantity() {
    const input = document.getElementById('jumlah');
    input.value = Math.max(1, parseInt(input.value) - 1);
    document.getElementById('summaryQty').textContent = input.value + ' kantong';
    updateSummary();
}

function updateSummary() {
    const prioritas = document.getElementById('prioritas').value;
    const qty = document.getElementById('jumlah').value;
    const blood = selectedBloodType || '-';
    
    document.getElementById('summaryQty').textContent = qty + ' kantong';
    document.getElementById('summaryPriority').textContent = prioritas || '-';
    document.getElementById('summaryTotal').textContent = `${blood} x ${qty} kantong`;
}

document.getElementById('prioritas').addEventListener('change', updateSummary);

document.getElementById('permintaanForm').addEventListener('submit', (e) => {
    e.preventDefault();

    if (!selectedBloodType) {
        alert('Pilih golongan darah terlebih dahulu!');
        return;
    }

    alert('Permintaan darah berhasil diajukan! Anda akan menerima notifikasi persetujuan dalam waktu maksimal 24 jam.');
});

function getCurrentPage() {
    return new URLSearchParams(window.location.search).get('page') || 'rs-permintaan';
}

function initProfileMenuVisibility() {
    const currentPage = getCurrentPage();
    const profilMenuItem = document.getElementById('profilMenuItem');
    const pengaturanTitle = document.getElementById('pengaturanTitle');

    if (currentPage === 'rs-dashboard') {
        profilMenuItem.style.display = 'block';
        pengaturanTitle.style.display = 'block';
    }
}

initProfileMenuVisibility();
document.getElementById('sidebarToggle').addEventListener('click', () => {
    document.getElementById('sidebar').classList.toggle('active');
});
