(function () {
    'use strict';

    const root = document.documentElement;
    const sidebar = document.getElementById('engipiSidebar');
    const collapseButton = document.getElementById('engipiSidebarCollapse');
    const mobileTrigger = document.getElementById('topnav-hamburger-icon');
    const closeButton = document.getElementById('engipiDrawerClose');
    const overlay = document.getElementById('engipiDrawerOverlay');
    const tooltip = document.getElementById('engipiSidebarTooltip');
    const userMenuButton = document.getElementById('engipiUserMenuButton');
    const userMenu = document.getElementById('engipiUserMenu');
    const storageKey = 'engipi.sidebar.collapsed';
    let drawerOpener = null;

    if (!sidebar) return;

    const isDesktop = () => window.matchMedia('(min-width: 992px)').matches;
    const focusableSelector = 'a[href], button:not([disabled]), [tabindex]:not([tabindex="-1"])';

    function setCollapsed(value) {
        root.classList.toggle('engipi-shell-collapsed', value);
        if (!collapseButton) return;
        collapseButton.setAttribute('aria-expanded', String(!value));
        collapseButton.setAttribute('aria-label', value ? 'باز کردن منو' : 'جمع کردن منو');
        collapseButton.querySelector('i').className = value ? 'ri-expand-left-line' : 'ri-contract-right-line';
    }

    function closeDrawer(returnFocus) {
        sidebar.classList.remove('is-open');
        sidebar.setAttribute('aria-hidden', isDesktop() ? 'false' : 'true');
        document.body.classList.remove('engipi-drawer-open');
        overlay?.classList.remove('is-visible');
        overlay?.setAttribute('aria-hidden', 'true');
        mobileTrigger?.setAttribute('aria-expanded', 'false');
        if (returnFocus && drawerOpener) drawerOpener.focus();
    }

    function openDrawer(event) {
        drawerOpener = event?.currentTarget || document.activeElement;
        sidebar.classList.add('is-open');
        sidebar.setAttribute('aria-hidden', 'false');
        document.body.classList.add('engipi-drawer-open');
        overlay?.classList.add('is-visible');
        overlay?.setAttribute('aria-hidden', 'false');
        mobileTrigger?.setAttribute('aria-expanded', 'true');
        closeButton?.focus();
    }

    function hideTooltip() {
        if (!tooltip) return;
        tooltip.classList.remove('is-visible');
        tooltip.setAttribute('aria-hidden', 'true');
    }

    function showTooltip(target) {
        if (!tooltip || !isDesktop() || !root.classList.contains('engipi-shell-collapsed')) return;
        const label = target.dataset.tooltip;
        if (!label) return;
        const rect = target.getBoundingClientRect();
        tooltip.textContent = label;
        tooltip.style.top = Math.max(8, rect.top + (rect.height - tooltip.offsetHeight) / 2) + 'px';
        tooltip.style.right = Math.max(8, window.innerWidth - rect.left + 10) + 'px';
        tooltip.classList.add('is-visible');
        tooltip.setAttribute('aria-hidden', 'false');
    }

    try {
        setCollapsed(isDesktop() && localStorage.getItem(storageKey) === '1');
    } catch (error) {}

    collapseButton?.addEventListener('click', () => {
        const value = !root.classList.contains('engipi-shell-collapsed');
        setCollapsed(value);
        hideTooltip();
        try { localStorage.setItem(storageKey, value ? '1' : '0'); } catch (error) {}
    });

    mobileTrigger?.addEventListener('click', openDrawer);
    closeButton?.addEventListener('click', () => closeDrawer(true));
    overlay?.addEventListener('click', () => closeDrawer(true));

    sidebar.addEventListener('pointerover', event => {
        const target = event.target.closest('[data-tooltip]');
        if (target) showTooltip(target);
    });
    sidebar.addEventListener('pointerout', event => {
        if (event.target.closest('[data-tooltip]')) hideTooltip();
    });
    sidebar.addEventListener('focusin', event => {
        const target = event.target.closest('[data-tooltip]');
        if (target) showTooltip(target);
    });
    sidebar.addEventListener('focusout', hideTooltip);
    sidebar.addEventListener('click', event => {
        if (!isDesktop() && event.target.closest('a[href]')) closeDrawer(false);
    });

    if (userMenuButton && userMenu) {
        userMenuButton.addEventListener('click', event => {
            event.stopPropagation();
            userMenu.hidden = !userMenu.hidden;
            userMenuButton.setAttribute('aria-expanded', String(!userMenu.hidden));
        });
    }

    document.addEventListener('click', event => {
        if (userMenu && !userMenu.hidden && !event.target.closest('.engipi-user-menu')) {
            userMenu.hidden = true;
            userMenuButton?.setAttribute('aria-expanded', 'false');
        }
    });

    document.addEventListener('keydown', event => {
        if (event.key === 'Escape') {
            if (sidebar.classList.contains('is-open')) closeDrawer(true);
            if (userMenu) userMenu.hidden = true;
            hideTooltip();
        }
        if (event.key === 'Tab' && sidebar.classList.contains('is-open') && !isDesktop()) {
            const focusable = [...sidebar.querySelectorAll(focusableSelector)];
            if (!focusable.length) return;
            const first = focusable[0];
            const last = focusable[focusable.length - 1];
            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault();
                last.focus();
            } else if (!event.shiftKey && document.activeElement === last) {
                event.preventDefault();
                first.focus();
            }
        }
    });

    window.addEventListener('resize', () => {
        if (isDesktop()) {
            closeDrawer(false);
            sidebar.setAttribute('aria-hidden', 'false');
            try { setCollapsed(localStorage.getItem(storageKey) === '1'); } catch (error) {}
        } else {
            root.classList.remove('engipi-shell-collapsed');
            sidebar.setAttribute('aria-hidden', sidebar.classList.contains('is-open') ? 'false' : 'true');
            hideTooltip();
        }
    });

    if (!isDesktop()) sidebar.setAttribute('aria-hidden', 'true');
})();