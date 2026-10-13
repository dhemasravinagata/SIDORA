function getCurrentPage() {
    return new URLSearchParams(window.location.search).get('page') || 'rs-dashboard';
}

function initProfileMenuVisibility() {
    const currentPage = getCurrentPage();
    const profilMenuItem = document.getElementById('profilMenuItem');
    const pengaturanTitle = document.getElementById('pengaturanTitle');

    if (currentPage === 'rs-dashboard') {
        profilMenuItem.style.display = 'block';
        pengaturanTitle.style.display = 'block';
    } else {
        profilMenuItem.style.display = 'none';
        pengaturanTitle.style.display = 'none';
    }
}

initProfileMenuVisibility();


document.getElementById('sidebarToggle').addEventListener('click', () => {
    document.getElementById('sidebar').classList.toggle('active');
});
