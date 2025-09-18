// assets/swup/app.loader.js
(function () {
    if (document.body.classList.contains('elementor-page') ||
        document.body.classList.contains('elementor-editor-active')) {
        return; // ĐỪNG khởi tạo Swup ở trang Elementor
    }

    const swup = new Swup({
        containers: ['#swup'],
        linkSelector: 'a[href^="/"]:not([data-no-swup]):not([target="_blank"])'
    });

    function initLocal() {
        // re-bind JS nội bộ của bạn (KHÔNG gọi Elementor, KHÔNG define customElements)
    }
    initLocal();

    document.addEventListener('swup:contentReplaced', () => {
        // Nếu điều hướng vào trang Elementor -> dừng Swup để tránh double-init
        if (document.body.classList.contains('elementor-page')) {
        try { swup.destroy(); } catch (e) {}
        return;
        }
        initLocal();
    });
})();
