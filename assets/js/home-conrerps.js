/* CONFERP — consulta por UF e rolagem para o painel de resultados. */
(function () {
  'use strict';
  function init(root) {
    if (root.dataset.conferpInitialized === '1') return;
    root.dataset.conferpInitialized = '1';
    var select = root.querySelector('[data-conferp-uf]');
    var status = root.querySelector('[data-conferp-status]');
    var details = root.querySelector('[data-conferp-details]');
    var mapNode = root.querySelector('[data-conferp-uf-map]');
    var cards = root.querySelectorAll('[data-conferp-regional]');
    if (!select || !status || !details || !mapNode) return;
    var associations;
    try { associations = JSON.parse(mapNode.textContent); } catch (error) { return; }
    function update(scrollToResult) {
      var uf = select.value;
      var ids = uf && Array.isArray(associations[uf]) ? associations[uf].map(String) : [];
      var shown = 0;
      cards.forEach(function (card) {
        var visible = ids.indexOf(card.getAttribute('data-conferp-regional')) !== -1;
        card.hidden = !visible;
        if (visible) shown++;
      });
      details.hidden = !uf;
      status.textContent = !uf ? '' : shown === 1 ? '1 Conselho Regional encontrado.' :
        shown ? shown + ' Conselhos Regionais encontrados.' :
        'Nenhum Conselho Regional ativo cadastrado para este estado.';
      if (scrollToResult && uf) {
        var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        details.scrollIntoView({ behavior: reduceMotion ? 'instant' : 'smooth', block: 'start' });
      }
    }
    select.addEventListener('change', function () { update(true); });
    update(false);
  }
  function boot() { document.querySelectorAll('[data-conferp-conrerps]').forEach(init); }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', boot);
  else boot();
})();
