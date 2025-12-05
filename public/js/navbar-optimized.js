/**
 * Navbar Optimization & Animation Script
 * Handles smooth animations, lazy loading, and performance
 * Updated to use Event Delegation for robust Livewire support
 */

(function () {
    'use strict';

    // ============================================
    // CONFIGURATION
    // ============================================
    const config = {
        mobileBreakpoint: 768,
        scrollThreshold: 50,
        loadingBarDuration: 300
    };

    // ============================================
    // ID MAPPINGS
    // ============================================
    const menuMap = {
        'mobile-menu-button': { menu: 'mobile-menu', icon: 'mobile-menu-icon' }
    };

    // ============================================
    // PAGE LOADING INDICATOR
    // ============================================
    function initLoadingBar() {
        if (document.querySelector('.page-loading')) return; // Prevent duplicate

        const loadingBar = document.createElement('div');
        loadingBar.className = 'page-loading';
        document.body.prepend(loadingBar);

        document.addEventListener('click', (e) => {
            const link = e.target.closest('a');
            if (link && !link.hasAttribute('target') && link.href &&
                link.href.indexOf(window.location.origin) === 0 &&
                !link.href.includes('#') && !link.hasAttribute('wire:click')) {
                loadingBar.classList.add('active');
            }
        });

        const hideLoading = () => {
            setTimeout(() => loadingBar.classList.remove('active'), config.loadingBarDuration);
        };

        window.addEventListener('load', hideLoading);
        window.addEventListener('pageshow', hideLoading);
        document.addEventListener('livewire:navigated', hideLoading);
    }

    // ============================================
    // MOBILE MENU LOGIC (Event Delegation)
    // ============================================
    function initMobileMenuDelegation() {
        // We only need to attach this ONCE to the document
        if (window.mobileMenuDelegationInitialized) return;
        window.mobileMenuDelegationInitialized = true;

        document.addEventListener('click', (e) => {
            const button = e.target.closest('button');
            if (!button || !menuMap[button.id]) {
                // Check if clicking outside to close
                const openMenus = document.querySelectorAll('.mobile-menu.active');
                openMenus.forEach(menu => {
                    // Find which button corresponds to this menu (reverse lookup or just check all)
                    // Simpler: if click is not inside menu and not inside any known button
                    if (!menu.contains(e.target)) {
                        // We need to find the button that opened this menu to reset its state
                        // This is tricky with reverse lookup. 
                        // Instead, let's just close all menus if click is outside
                        closeAllMenus();
                    }
                });
                return;
            }

            e.stopPropagation();
            const { menu: menuId, icon: iconId } = menuMap[button.id];
            const menu = document.getElementById(menuId);
            const icon = document.getElementById(iconId); // Might be null if using SVG directly inside button without ID

            if (!menu) return;

            const isActive = menu.classList.contains('active');
            if (isActive) {
                closeMenu(button, menu, icon);
            } else {
                openMenu(button, menu, icon);
            }
        });

        // Close on Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeAllMenus();
        });

        // Close on Resize
        window.addEventListener('resize', () => {
            if (window.innerWidth >= config.mobileBreakpoint) closeAllMenus();
        });
    }

    function openMenu(button, menu, icon) {
        // Close others first
        closeAllMenus();

        menu.classList.remove('hidden');

        // Double RAF for smooth transition
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                menu.classList.add('active');
            });
        });

        button.classList.add('active');
        if (icon) icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>';

        if (window.innerWidth < config.mobileBreakpoint) {
            document.body.style.overflow = 'hidden';
        }
    }

    function closeMenu(button, menu, icon) {
        menu.classList.remove('active');
        button.classList.remove('active');

        setTimeout(() => {
            if (!menu.classList.contains('active')) {
                menu.classList.add('hidden');
            }
        }, 300);

        if (icon) icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>';
        document.body.style.overflow = '';
    }

    function closeAllMenus() {
        Object.keys(menuMap).forEach(btnId => {
            const button = document.getElementById(btnId);
            const { menu: menuId, icon: iconId } = menuMap[btnId];
            const menu = document.getElementById(menuId);
            const icon = document.getElementById(iconId);

            if (button && menu && menu.classList.contains('active')) {
                closeMenu(button, menu, icon);
            }
        });
    }

    // ============================================
    // NAVBAR SCROLL EFFECT
    // ============================================
    function initNavbarScroll() {
        const navbar = document.querySelector('.navbar-sticky');
        if (!navbar) return;

        let lastScroll = 0;
        let ticking = false;

        const onScroll = () => {
            lastScroll = window.scrollY;
            if (!ticking) {
                window.requestAnimationFrame(() => {
                    if (lastScroll > config.scrollThreshold) {
                        navbar.classList.add('scrolled');
                    } else {
                        navbar.classList.remove('scrolled');
                    }
                    ticking = false;
                });
                ticking = true;
            }
        };

        window.removeEventListener('scroll', window.navbarScrollHandler); // Cleanup old
        window.navbarScrollHandler = onScroll;
        window.addEventListener('scroll', onScroll, { passive: true });
    }

    // ============================================
    // LAZY LOAD & PREFETCH (Simplified)
    // ============================================
    function initLazyLoad() {
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries, obs) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.classList.remove('lazy');
                            obs.unobserve(img);
                        }
                    }
                });
            });
            document.querySelectorAll('img[data-src]').forEach(img => observer.observe(img));
        }
    }

    function initActiveLinks() {
        const currentPath = window.location.pathname;
        document.querySelectorAll('.nav-link').forEach(link => {
            const href = link.getAttribute('href');
            if (href && (href === currentPath || currentPath.startsWith(href + '/'))) {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        });
    }

    // ============================================
    // INITIALIZATION
    // ============================================
    function initAll() {
        initLoadingBar();
        initNavbarScroll();
        initLazyLoad();
        initActiveLinks();
        // Mobile menu is handled by delegation, so we just init it once
        initMobileMenuDelegation();
    }

    // Boot
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAll);
    } else {
        initAll();
    }

    // Livewire Hook
    document.addEventListener('livewire:navigated', initAll);

})();
