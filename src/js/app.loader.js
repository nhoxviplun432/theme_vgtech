(function () {
  if (document.body.classList.contains('elementor-page') ||
      document.body.classList.contains('elementor-editor-active')) return;

  var swup;
  try {
    var sameOrigin = location.origin.replace(/[-/\\^$*+?.()|[\]{}]/g, '\\$&');
    var linkSelector = [
      'a[href^="/"]',
      'a[href^="./"]',
      'a[href^="../"]',
      'a[href^="' + sameOrigin + '"]'
    ].join(':not([data-no-swup]):not([target="_blank"]), ') +
      ':not([data-no-swup]):not([target="_blank"])';

    swup = new Swup({
      containers: ['#swup'],
      linkSelector: linkSelector
    });
  } catch (e) {
    // Fallback: rời trang thật thì trước khi unload vẫn bật loader (không bắt được CSS class)
    window.addEventListener('beforeunload', function () {
      var el = document.getElementById('swup-loader');
      if (el) el.style.opacity = '1';
    });
    return;
  }

  function initLocal(){/* re-bind JS nội bộ nếu cần */}
  initLocal();

  // Nếu vẫn muốn hook event cho init lại JS sau replace
  if (typeof swup.on === 'function') {
    swup.on('contentReplaced', function () {
      if (document.body.classList.contains('elementor-page')) {
        try { swup.destroy(); } catch(e){}
        return;
      }
      initLocal();
    });
  }
  document.addEventListener('swup:content:replace', function () {
    if (document.body.classList.contains('elementor-page')) {
      try { swup && swup.destroy && swup.destroy(); } catch(e){}
      return;
    }
    initLocal();
  });
})();
