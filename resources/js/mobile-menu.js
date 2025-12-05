document.addEventListener('DOMContentLoaded', function () {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileMenuIcon = document.getElementById('mobile-menu-icon');

    if (mobileMenuButton && mobileMenu && mobileMenuIcon) {
        mobileMenuButton.addEventListener('click', function () {
            if (mobileMenu.classList.contains('hidden')) {
                mobileMenu.classList.remove('hidden');
                mobileMenu.style.maxHeight = '0px';
                mobileMenu.style.opacity = '0';
                setTimeout(() => {
                    mobileMenu.style.maxHeight = mobileMenu.scrollHeight + 'px';
                    mobileMenu.style.opacity = '1';
                }, 10);

                // Animate hamburger to X
                mobileMenuIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>';
            } else {
                mobileMenu.style.maxHeight = '0px';
                mobileMenu.style.opacity = '0';
                setTimeout(() => {
                    mobileMenu.classList.add('hidden');
                }, 300);

                // Animate X to hamburger
                mobileMenuIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>';
            }
        });

        // Close menu when clicking outside
        document.addEventListener('click', function (event) {
            if (!mobileMenuButton.contains(event.target) && !mobileMenu.contains(event.target) && !mobileMenu.classList.contains('hidden')) {
                mobileMenu.style.maxHeight = '0px';
                mobileMenu.style.opacity = '0';
                setTimeout(() => {
                    mobileMenu.classList.add('hidden');
                }, 300);

                mobileMenuIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>';
            }
        });
    }
});
