/**
 * filters.js — Gebied-knoppen, score-filter, sortering.
 * Vereist: map.js + sidebar.js geladen vóór dit script.
 */

(function (D) {

  D.initFilters = function () {
    const t    = (D._trans && D._trans.map) || {};
    const lang = D.lang || 'en';

    // ── i18n labels bijwerken ──────────────────────────────────
    _setText('filter-label',    t.filter    || 'Filter:');
    _setText('score-label',     t.minScore  || '⭐ Min. score:');
    _setText('legend-good',     t.good      || 'Goed');
    _setText('legend-average',  t.average   || 'Gemiddeld');
    _setText('legend-poor',     t.poor      || 'Slecht');
    _setText('legend-unknown',  t.unknown   || 'Onbekend');

    // Gebied-knoppen tekst
    const AREA_TRANS = {
      '':       t.all           || 'Alles',
      PARK:     t.park          || 'Park',
      STUDIOS:  t.adventureWorld || 'Adventure World',
      VILLAGE:  t.village       || 'Village',
      HOTEL:    t.hotels        || 'Hotels',
      PARKING:  t.parking       || 'Parking',
    };
    document.querySelectorAll('.area-btn').forEach(btn => {
      const span = btn.querySelector('span');
      const key  = btn.dataset.area ?? '';
      if (span && AREA_TRANS[key]) span.textContent = AREA_TRANS[key];
    });

    // ── Gebied-knoppen klikken ─────────────────────────────────
    document.querySelectorAll('.area-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const area   = btn.dataset.area ?? '';
        D.state.activeArea = area;

        // Kaart vliegen naar gebied
        const center = D.getCenter(area);
        D.map.flyTo([center.lat, center.lng], center.zoom, { duration: 1.2 });

        // Filter opnieuw laden
        const minScore = document.getElementById('filter-score')?.value ?? '';
        D.loadToilets(area, minScore);

        // Actieve knop-stijl
        document.querySelectorAll('.area-btn').forEach(b => {
          const active = b === btn;
          b.style.background = active ? '#003f8a'     : 'transparent';
          b.style.color      = active ? 'white'       : '#3d1f8c';
          b.classList.toggle('active', active);
        });
      });
    });

    // ── Score-filter ───────────────────────────────────────────
    document.getElementById('filter-score')?.addEventListener('change', e => {
      D.loadToilets(D.state.activeArea, e.target.value);
    });
  };

  function _setText(id, text) {
    const el = document.getElementById(id);
    if (el) el.textContent = text;
  }

})(window.DLPWC);
