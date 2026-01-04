const btnMenu = document.getElementById('menuBtn');
const sidebar = document.querySelector('nav');
const overlay = document.createElement('div');

overlay.classList.add('overlay');
document.body.appendChild(overlay);

btnMenu.addEventListener('click', () => {
    sidebar.classList.add('ativo');
    overlay.classList.add('ativo');
});

overlay.addEventListener('click', () => {
    sidebar.classList.remove('ativo');
    overlay.classList.remove('ativo');
});

document.addEventListener('keydown', (e) => {
    if (e.key === "Escape") {
        sidebar.classList.remove('ativo');
        overlay.classList.remove('ativo');
    }
});

function ajustarSidebar() {
    if (window.innerWidth >= 1100) {
        // Sempre ativa
        sidebar.classList.add('ativo');
        overlay.classList.remove('ativo');
    } else {
        // Mobile: sidebar só abre com o botão
        sidebar.classList.remove('ativo');
    }
}

// Rodar ao carregar
ajustarSidebar();

// Rodar sempre que redimensionar
window.addEventListener('resize', ajustarSidebar);

