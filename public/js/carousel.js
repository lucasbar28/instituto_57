(function () {
  'use strict';

  if (typeof Glider === 'undefined') {
    console.warn('Glider no está cargado. carousel.js no se inicializa.');
    return;
  }

  const AUTOPLAY_MS = 3000;

  document.querySelectorAll('.glider').forEach((gliderEl) => {
    const slides = gliderEl.querySelectorAll('.slide');
    const slidesCount = Math.max(1, slides.length);

    const gliderInstance = new Glider(gliderEl, {
      slidesToShow: 1,
      slidesToScroll: 1,
      draggable: true,
      dots: '.dots',
      arrows: { prev: '.glider-prev', next: '.glider-next' },
      rewind: true
    });

    let autoplayTimer = null;
    let currentIndex = 0;

    function goTo(index) {
      currentIndex = (index + slidesCount) % slidesCount;
      try { gliderInstance.slideItem(currentIndex); } catch (e) { /* ignore */ }
    }

    function startAutoplay() {
      if (autoplayTimer || slidesCount <= 1) return;
      autoplayTimer = setInterval(() => {
        if (document.hidden) return;
        currentIndex = (currentIndex + 1) % slidesCount;
        goTo(currentIndex);
      }, AUTOPLAY_MS);
    }

    function stopAutoplay() {
      if (!autoplayTimer) return;
      clearInterval(autoplayTimer);
      autoplayTimer = null;
    }

    gliderEl.addEventListener('glider-slide', (ev) => {
      if (ev && ev.detail && typeof ev.detail.slide === 'number') {
        currentIndex = ev.detail.slide;
      }
    });

    gliderEl.addEventListener('mouseenter', stopAutoplay, { passive: true });
    gliderEl.addEventListener('mouseleave', startAutoplay, { passive: true });
    gliderEl.addEventListener('touchstart', stopAutoplay, { passive: true });
    gliderEl.addEventListener('touchend', startAutoplay, { passive: true });

    document.addEventListener('visibilitychange', () => {
      document.hidden ? stopAutoplay() : startAutoplay();
    });

    const io = new IntersectionObserver((entries) => {
      const entry = entries[0];
      if (!entry) return;
      entry.isIntersecting ? startAutoplay() : stopAutoplay();
    }, { threshold: 0.25 });
    io.observe(gliderEl);

    if (slidesCount > 1) startAutoplay();

    window.addEventListener('beforeunload', () => {
      stopAutoplay();
      io.disconnect();
    }, { passive: true });
  });
})();