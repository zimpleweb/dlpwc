/**
 * sidebar.js — Kaart-sidebar: open/sluit/pin + lijstweergave.
 * Vereist: map.js (window.DLPWC) geladen vóór dit script.
 */

(function (D) {

  let isOpen   = false;
  let isPinned = false;

  const getSidebar  = () => document.getElementById('sidebar');
  const getToggle   = () => document.getElementById('sidebar-toggle');
  const getMapCont  = () => document.getElementById('map-container');

  // ── Open / sluit ──────────────────────────────────────────────
  function openSidebar() {
    isOpen = true;
    getSidebar().style.transform = 'translateX(0)';
    getToggle().style.display    = 'none';
    if (isPinned) getMapCont().style.marginLeft = '300px';
    setTimeout(() => D.map && D.map.invalidateSize(), 310);
  }

  function closeSidebar() {
    if (isPinned) return;
    isOpen = false;
    getSidebar().style.transform = 'translateX(-300px)';
    getToggle().style.display    = '';
    getMapCont().style.marginLeft = '0';
    setTimeout(() => D.map && D.map.invalidateSize(), 310);
  }

  // ── Pin-knop ──────────────────────────────────────────────────
  function updatePinBtn() {
    const t   = D._trans;
    const btn = document.getElementById('sidebar-pin');
    if (!btn || !t) return;
    btn.textContent = isPinned ? (t.map.unpin || 'Losmaken') : (t.map.pin || 'Vastpinnen');
    btn.style.background = isPinned ? 'rgba(255,255,255,.25)' : '';
  }

  // ── Sidebar-item openen (ook aanroepbaar via onclick) ────────
  D._openSidebar = function (id) {
    id = parseInt(id);
    const toilet = (D.state.currentData || []).find(t => parseInt(t.id) === id);
    const marker = D.markers && D.markers.get(id);
    if (!toilet || !marker || !D.map) return;

    D.highlightSidebarItem(id);
    if (window.innerWidth <= 768) closeSidebar();

    const lat = parseFloat(toilet.latitude);
    const lng = parseFloat(toilet.longitude);

    let opened = false;
    const openOnce = () => { if (!opened) { opened = true; marker.openPopup(); } };
    D.map.once('moveend', openOnce);
    setTimeout(openOnce, 600);
    D.map.panTo([lat, lng], { animate: true });
  };

  // ── Sidebar-lijst renderen ────────────────────────────────────
  D.renderSidebar = function (toilets) {
    const t        = D._trans || {};
    const mapTrans = t.map || {};
    const list     = document.getElementById('sidebar-list');
    const countEl  = document.getElementById('sidebar-count');

    // Sorteer
    const sorted = [...toilets].sort((a, b) => {
      const sa = a.score ?? -1;
      const sb = b.score ?? -1;
      return D.state.sortDir === 'desc' ? sb - sa : sa - sb;
    });

    // Teller
    if (countEl) {
      const word = sorted.length !== 1 ? (mapTrans.toilets || 'toiletten') : (mapTrans.toilet || 'toilet');
      countEl.textContent = `${sorted.length} ${word}`;
    }

    // Geen resultaten
    if (!sorted.length) {
      list.innerHTML = `<div class="text-center text-slate-400 text-sm py-8">${mapTrans.noResults || 'Geen resultaten'}</div>`;
      return;
    }

    // Kaart-items
    list.innerHTML = sorted.map(toilet => {
      const color  = D.scoreColor(toilet.score);
      const score  = toilet.score ? parseFloat(toilet.score).toFixed(1) : '—';
      const area   = _areaLabel(toilet.area);
      return `
        <div class="sidebar-item rounded-lg border border-slate-100 bg-white
                    hover:border-[#3d1f8c] hover:shadow-md transition cursor-pointer p-3"
             data-id="${toilet.id}"
             onclick="DLPWC._openSidebar(${toilet.id})">
          <div class="flex items-center gap-2">
            <div style="width:30px;height:30px;border-radius:50%;flex-shrink:0;
                        background:${color};border:2px solid white;
                        box-shadow:0 2px 5px rgba(0,0,0,.2);
                        display:flex;align-items:center;justify-content:center;font-size:13px;">🚽</div>
            <div class="flex-1 min-w-0">
              <div class="text-xs font-semibold truncate" style="color:#3d1f8c;">${toilet.name}</div>
              <div class="text-xs text-slate-400">${area}</div>
            </div>
            <div class="text-right flex-shrink-0">
              <div style="color:${color};font-weight:700;font-size:14px;">${score}</div>
              <div class="text-xs text-slate-400">${toilet.review_count ?? 0} rev.</div>
            </div>
          </div>
          <div class="mt-1.5">${D.starRating(toilet.score, 12)}</div>
        </div>`;
    }).join('');

    // Klik-listeners worden centraal beheerd via initSidebar (event delegation)

    // Vertalingen in de sidebar-header updaten
    const overviewEl = document.getElementById('sidebar-overview-title');
    if (overviewEl && mapTrans.overview) overviewEl.textContent = mapTrans.overview;

    const sortLabelEl = document.getElementById('sort-label');
    if (sortLabelEl && mapTrans.sort) sortLabelEl.textContent = mapTrans.sort;

    document.querySelectorAll('.sort-btn').forEach(btn => {
      if (btn.dataset.sort === 'desc' && mapTrans.sortHigh) btn.textContent = mapTrans.sortHigh;
      if (btn.dataset.sort === 'asc'  && mapTrans.sortLow)  btn.textContent = mapTrans.sortLow;
    });

    updatePinBtn();
  };

  // Lokale vertaalhulp voor gebiedsname (sidebar gebruikt D._trans ook niet altijd al)
  function _areaLabel(area) {
    const LABELS = {
      en: { PARK: '🏰 Park', STUDIOS: '🌟 Adventure World', VILLAGE: '🛍️ Village', HOTEL: '🏨 Hotel', PARKING: '🅿️ Parking' },
      nl: { PARK: '🏰 Park', STUDIOS: '🌟 Adventure World', VILLAGE: '🛍️ Village', HOTEL: '🏨 Hotel', PARKING: '🅿️ Parking' },
      fr: { PARK: '🏰 Parc', STUDIOS: '🌟 Adventure World', VILLAGE: '🛍️ Village', HOTEL: '🏨 Hôtels', PARKING: '🅿️ Parking' },
    };
    return (LABELS[D.lang] || LABELS.en)[area] || area;
  }

  // ── Event-listeners ───────────────────────────────────────────
  D.initSidebar = function () {

    // ── Sidebar-item klik (event delegation — werkt altijd, ook na herrenderen) ──
    document.getElementById('sidebar-list')?.addEventListener('click', e => {
      const item = e.target.closest('.sidebar-item');
      if (!item) return;

      const id     = parseInt(item.dataset.id);
      const toilet = (D.state.currentData || []).find(t => parseInt(t.id) === id);
      const marker = D.markers && D.markers.get(id);
      if (!toilet || !marker || !D.map) return;

      D.highlightSidebarItem(id);
      if (window.innerWidth <= 768) closeSidebar();

      const lat = parseFloat(toilet.latitude);
      const lng = parseFloat(toilet.longitude);

      // Popup openen: zodra map klaar is met bewegen (met fallback als map al op positie staat)
      let opened = false;
      const openOnce = () => { if (!opened) { opened = true; marker.openPopup(); } };
      D.map.once('moveend', openOnce);
      setTimeout(openOnce, 600);          // fallback als map niet beweegt
      D.map.panTo([lat, lng], { animate: true });
    });

    getToggle()?.addEventListener('click', () => isOpen ? closeSidebar() : openSidebar());

    document.getElementById('sidebar-close')?.addEventListener('click', () => {
      if (isPinned) { isPinned = false; updatePinBtn(); }
      closeSidebar();
    });

    document.getElementById('sidebar-pin')?.addEventListener('click', () => {
      isPinned = !isPinned;
      updatePinBtn();
      if (isPinned) {
        openSidebar();
        getMapCont().style.marginLeft = '300px';
      } else {
        getMapCont().style.marginLeft = '0';
        getToggle().style.display = 'none'; // toggle blijft weg zolang open
      }
      setTimeout(() => D.map && D.map.invalidateSize(), 310);
    });

    // Sorteer-knoppen
    document.querySelectorAll('.sort-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        D.state.sortDir = btn.dataset.sort;
        document.querySelectorAll('.sort-btn').forEach(b => {
          const active = b === btn;
          b.style.background = active ? '#003f8a' : '';
          b.style.color      = active ? 'white'   : '';
        });
        D.renderSidebar(D.state.currentData);
      });
    });

    // Touch-swipe omlaag sluit sidebar op mobiel
    let touchStartY = 0;
    getSidebar()?.addEventListener('touchstart', e => { touchStartY = e.touches[0].clientY; }, { passive: true });
    getSidebar()?.addEventListener('touchmove',  e => {
      if (e.touches[0].clientY - touchStartY > 60) closeSidebar();
    }, { passive: true });

    // Extern event (voor andere scripts)
    window.addEventListener('dlpwc:toggle-sidebar', () => isOpen ? closeSidebar() : openSidebar());
  };

})(window.DLPWC);
