/** Navegação progressiva; o carrossel permanece funcional sem JavaScript. */
(() => {
  'use strict';
  document.querySelectorAll('[data-conferp-links]').forEach((root) => {
    const track = root.querySelector('.conferp-links__track');
    const prev = root.querySelector('[data-conferp-links-prev]');
    const next = root.querySelector('[data-conferp-links-next]');
    if (!track || !prev || !next) return;

    const update = () => {
      const max = Math.max(0, track.scrollWidth - track.clientWidth);
      prev.disabled = track.scrollLeft <= 2;
      next.disabled = track.scrollLeft >= max - 2;
    };
    const move = (direction) => {
      const card = track.querySelector('.conferp-links__card');
      if (!card) return;
      const gap = parseFloat(getComputedStyle(track).columnGap) || 0;
      const distance = card.getBoundingClientRect().width + gap;
      const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
      track.scrollBy({ left: direction * distance, behavior: reduced ? 'instant' : 'smooth' });
    };
    prev.addEventListener('click', () => move(-1));
    next.addEventListener('click', () => move(1));
    track.addEventListener('scroll', update, { passive: true });
    window.addEventListener('resize', update, { passive: true });
    update();
  });
})();
