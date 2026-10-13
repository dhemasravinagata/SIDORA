document.getElementById('sidebarToggle').addEventListener('click', () => {
    document.getElementById('sidebar').classList.toggle('active');
});

const targetInput = document.querySelector('input[placeholder="Berapa target pendonor?"]');
const volumeInput = document.querySelector('input[placeholder="Target volume (450 ml x jumlah)"]');

if (targetInput) {
    targetInput.addEventListener('change', () => {
        const target = parseInt(targetInput.value) || 0;
        volumeInput.value = target * 450;
    });
}
