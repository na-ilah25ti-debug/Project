const menuToggle = document.querySelector('.burger');
const sidebar = document.querySelector('.sidebar');
const drawerBackdrop = document.querySelector('[data-close-menu]');
const mobileLayout = window.matchMedia('(max-width: 850px)');

function setMenu(open, restoreFocus = false) {
    menuToggle.setAttribute('aria-expanded', String(open));
    sidebar.classList.toggle('is-open', open);
    if (open) {
        sidebar.querySelector('a').focus();
    } else if (restoreFocus) {
        menuToggle.focus();
    }
}

menuToggle.addEventListener('click', () => setMenu(menuToggle.getAttribute('aria-expanded') === 'false'));
drawerBackdrop.addEventListener('click', () => setMenu(false, true));
mobileLayout.addEventListener('change', () => setMenu(false));
document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && menuToggle.getAttribute('aria-expanded') === 'true') {
        setMenu(false, true);
    }
});
