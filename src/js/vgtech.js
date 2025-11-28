// ====================== FIX BODY HEIGHT ======================
export function fixBodyHeight() {
  const height = window.innerHeight;
  document.body.style.height = height + 'px';
}

// ====================== SAFE CUSTOM ELEMENT DEFINE ======================
if (window.customElements) {
  const originalDefine = window.customElements.define;
  window.customElements.define = function (name, constructor, options) {
    if (customElements.get(name)) {
      console.warn(`[Turbo] Skipping duplicate custom element registration: ${name}`);
      return;
    }
    originalDefine.call(this, name, constructor, options);
  };
}

// ====================== MODAL CLEANUP ======================
export function cleanUpBootstrapModals() {
  const modals = document.querySelectorAll(".modal.show");
  const Modal = window.bootstrap?.Modal;

  modals.forEach((modalEl) => {
    const inst = Modal?.getInstance(modalEl);
    inst?.hide?.();

    modalEl.classList.remove("show");
    modalEl.style.display = "none";
  });

  document.body.classList.remove("modal-open");
  document.body.style.removeProperty("padding-right");
}

// ====================== KHỞI TẠO CHÍNH ======================
export function initVgtech() {
  cleanUpBootstrapModals();
  fixBodyHeight();
}

// ====================== GẮN GLOBAL ======================
window.initVgtech = initVgtech;
window.cleanUpBootstrapModals = cleanUpBootstrapModals;
window.fixBodyHeight = fixBodyHeight;
