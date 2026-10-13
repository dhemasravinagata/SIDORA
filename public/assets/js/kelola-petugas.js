const modal = document.getElementById('petugasModal');
const tambahBtn = document.getElementById('tambahPetugasBtn');
const closeBtn = document.getElementById('closeModal');
const cancelBtn = document.getElementById('cancelBtn');
const editBtns = document.querySelectorAll('.editBtn');

tambahBtn.addEventListener('click', () => {
    document.getElementById('modalTitle').textContent = 'Tambah Petugas';
    document.getElementById('petugasForm').reset();
    modal.classList.add('active');
});

editBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('modalTitle').textContent = 'Edit Petugas';
        modal.classList.add('active');
    });
});

closeBtn.addEventListener('click', () => modal.classList.remove('active'));
cancelBtn.addEventListener('click', () => modal.classList.remove('active'));

modal.addEventListener('click', (e) => {
    if (e.target === modal) modal.classList.remove('active');
});

document.getElementById('petugasForm').addEventListener('submit', (e) => {
    e.preventDefault();
    alert('Data petugas berhasil disimpan!');
    modal.classList.remove('active');
});

document.getElementById('sidebarToggle').addEventListener('click', () => {
    document.getElementById('sidebar').classList.toggle('active');
});
