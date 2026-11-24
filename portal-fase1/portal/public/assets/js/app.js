(() => {
  // Menú móvil
  const btn = document.querySelector('[data-menu-btn]');
  const nav = document.querySelector('[data-nav]');
  if (btn && nav) {
    btn.addEventListener('click', () => {
      btn.setAttribute('aria-expanded', String(nav.classList.toggle('is-open')));
    });
  }

  // Única función de métricas. Cualquier elemento con data-track="nombre" la usa.
  window.track = (event, data = {}) => {
    const body = JSON.stringify({ event, page: location.pathname, ...data });
    if (navigator.sendBeacon) navigator.sendBeacon('/api/track', body);
  };
  document.addEventListener('click', (e) => {
    const el = e.target.closest('[data-track]');
    if (el) window.track(el.dataset.track);
  });
})();
