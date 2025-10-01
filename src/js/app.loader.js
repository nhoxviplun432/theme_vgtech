import Swup from "swup";

import { initVgtech } from "./vgtech.js";

// Guard: tránh khởi tạo 2 lần
if (!window.__VGTECH_SWUP_BOOTED__) {
  window.__VGTECH_SWUP_BOOTED__ = true;

  const html = document.documentElement;
  const body = document.body;

  html.classList.add('preload');

  // Elementor → skip Swup
  if (body.classList.contains('elementor-page') || body.classList.contains('elementor-editor-active')) {
    requestAnimationFrame(() => {
      html.classList.add('done');
      html.classList.remove('preload');
    });
  } else {
    // Khởi tạo Swup
    const swup = new Swup({
      containers: ['#swup'],
      linkSelector: 'a[href^="/"]:not([data-no-swup]):not([target="_blank"]), a[href^="./"]:not([data-no-swup]):not([target="_blank"]), a[href^="../"]:not([data-no-swup]):not([target="_blank"]), a[href^="' + location.origin + '"]:not([data-no-swup]):not([target="_blank"])',
    });

    // Lần đầu init
    initVgtech();

    // Hoàn tất hiệu ứng load
    requestAnimationFrame(() => {
      html.classList.add('done');
      html.classList.remove('preload');
    });

    // Sau mỗi lần replace
    swup.hooks.on('page:view', () => {
      initVgtech();
    });
  }
}

// ====================== A11Y cho Modal Menu ======================
(function setupMenuModalA11y() {
  const modalEl = document.getElementById('vgtechMenuModal');
  if (!modalEl || !window.bootstrap) return;

  const modal = bootstrap.Modal.getOrCreateInstance(modalEl, { backdrop: true, focus: true });

  modalEl.addEventListener('show.bs.modal', () => {
    modalEl.removeAttribute('inert');
    modalEl.removeAttribute('aria-hidden');
  });

  modalEl.addEventListener('hide.bs.modal', () => {
    if (modalEl.contains(document.activeElement)) document.activeElement.blur();
  });

  modalEl.addEventListener('hidden.bs.modal', () => {
    modalEl.setAttribute('inert', '');
    modalEl.setAttribute('aria-hidden', 'true');
    const opener = document.querySelector('[data-bs-toggle="modal"][data-bs-target="#vgtechMenuModal"]');
    if (opener) opener.focus();
  });

  if (!modalEl.classList.contains('show')) {
    modalEl.setAttribute('inert', '');
    modalEl.setAttribute('aria-hidden', 'true');
  }

  document.addEventListener('swup:will-replace-content', () => {
    if (modalEl.classList.contains('show')) modal.hide();
  });
})();
