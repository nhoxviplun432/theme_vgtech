// vgtech.js
document.addEventListener('DOMContentLoaded', function () {
    function closePopupIfOpen() {
        var modalEl = document.getElementById('vgtechMenuModal');
        if (modalEl && modalEl.classList.contains('show')) {
        if (window.bootstrap && typeof window.bootstrap.Modal === 'function') {
            // Nếu bootstrap.bundle.js đã load thì dùng hide() → có fade sẵn
            var inst = window.bootstrap.Modal.getInstance(modalEl);
            if (inst) {
                inst.hide();
            } else {
                new window.bootstrap.Modal(modalEl).hide();
            }
        } else {
            // Fallback: tự tạo hiệu ứng fade-out
            modalEl.style.transition = 'opacity 1s ease';
            modalEl.style.opacity = '1';
            requestAnimationFrame(function () {
                modalEl.style.opacity = '0';
            });
            setTimeout(function () {
            modalEl.classList.remove('show');
            modalEl.style.display = 'none';
            modalEl.style.opacity = '';
            document.body.classList.remove('modal-open');
            document.querySelectorAll('.modal-backdrop').forEach(function (b) {
                b.remove();
            });
            }, 300); // khớp với 0.3s ở trên
        }
        }
    }

    // Swup: khi bắt đầu visit → đóng popup
    document.addEventListener('swup:visit:start', closePopupIfOpen);

    // Click vào link trong popup menu → đóng popup
    document.addEventListener('click', function (e) {
        if (e.target && e.target.closest && e.target.closest('#vgtechMenuModal a[href]')) {
        closePopupIfOpen();
        }
    });
});

document.addEventListener('click', function(e) {
    // Chỉ xử lý khi bấm đúng nút caret
    const caretBtn = e.target.closest('.nav-vgtech[data-bs-toggle="dropdown"]');
    if (!caretBtn) return;

    e.preventDefault();
    e.stopPropagation();

    // Tìm dropdown parent gần nhất
    const dropdownLi = caretBtn.closest('.dropdown');
    if (!dropdownLi) return;

    // Đóng các dropdown anh em (tùy thích)
    const siblings = dropdownLi.parentElement?.querySelectorAll(':scope > .dropdown.show');
    if (siblings) {
        siblings.forEach(sib => { if (sib !== dropdownLi) sib.classList.remove('show'); });
    }

    // Toggle theo cách thủ công nếu cần (nhưng Bootstrap 5 sẽ xử lý nếu đã load js)
    dropdownLi.classList.toggle('show');

    // Cập nhật aria-expanded cho caret
    const expanded = dropdownLi.classList.contains('show');
    caretBtn.setAttribute('aria-expanded', expanded.toString());

    // Tìm .dropdown-menu để đặt display
    const menu = dropdownLi.querySelector(':scope > .dropdown-menu');
        if (menu) {
            if (expanded) menu.classList.add('show');
            else menu.classList.remove('show');
        }
});

    // Đóng dropdown khi click ra ngoài (fallback nếu chưa dùng data-bs-auto-close)
document.addEventListener('click', function(e) {
    document.querySelectorAll('.navbar .dropdown.show').forEach(li => {
        if (!li.contains(e.target)) {
            li.classList.remove('show');
            const btn = li.querySelector(':scope .nav-caret');
            const menu = li.querySelector(':scope > .dropdown-menu');
            if (btn) btn.setAttribute('aria-expanded', 'false');
            if (menu) menu.classList.remove('show');
        }
    });
});