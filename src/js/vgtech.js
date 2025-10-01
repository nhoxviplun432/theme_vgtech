// Logic nội bộ cho menu và popup
export const initVgtech = () => {
    console.log('Init JS sau khi load Swup');
    initDropdowns();
    closePopupIfOpen(); // luôn đóng popup khi chuyển trang
};

// ====================== POPUP HANDLER ======================
const closePopupIfOpen = () => {
    const modalEl = document.getElementById('vgtechMenuModal');
    if (!modalEl || !modalEl.classList.contains('show')) return;

    if (window.bootstrap?.Modal) {
        const inst = window.bootstrap.Modal.getInstance(modalEl) || new window.bootstrap.Modal(modalEl);
        inst.hide();
    } else {
        modalEl.classList.remove('show');
        modalEl.style.display = 'none';
        document.body.classList.remove('modal-open');
        document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
    }
};

// ====================== DROPDOWN HANDLER ======================
const initDropdowns = () => {
  document.querySelectorAll('.nav-vgtech[data-bs-toggle="dropdown"]').forEach(btn => {
    btn.removeEventListener('click', toggleDropdown);
    btn.addEventListener('click', toggleDropdown);
  });

  document.removeEventListener('click', closeDropdownsOutside);
  document.addEventListener('click', closeDropdownsOutside);
};

const toggleDropdown = (e) => {
  e.preventDefault();
  e.stopPropagation();

  const caretBtn = e.currentTarget;
  const dropdownLi = caretBtn.closest('.dropdown');
  if (!dropdownLi) return;

  dropdownLi.parentElement?.querySelectorAll(':scope > .dropdown.show').forEach(sib => {
    if (sib !== dropdownLi) sib.classList.remove('show');
  });

  dropdownLi.classList.toggle('show');
  const expanded = dropdownLi.classList.contains('show');
  caretBtn.setAttribute('aria-expanded', expanded);

  const menu = dropdownLi.querySelector(':scope > .dropdown-menu');
  if (menu) menu.classList.toggle('show', expanded);
};

const closeDropdownsOutside = (e) => {
  document.querySelectorAll('.navbar .dropdown.show').forEach(li => {
    if (!li.contains(e.target)) {
      li.classList.remove('show');
      const btn = li.querySelector(':scope .nav-vgtech');
      const menu = li.querySelector(':scope > .dropdown-menu');
      if (btn) btn.setAttribute('aria-expanded', 'false');
      if (menu) menu.classList.remove('show');
    }
  });
};

// Auto-close popup khi click link bên trong
document.addEventListener('click', e => {
  if (e.target.closest('#vgtechMenuModal a[href]')) {
    closePopupIfOpen();
  }
});
