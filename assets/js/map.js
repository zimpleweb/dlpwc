/**
 * map.js — Kaartlogica voor DLPWC.
 * Vereist: Leaflet (window.L) + window.DLPWC.lang
 * Laad volgorde: leaflet.js → map.js → sidebar.js → filters.js
 */

window.DLPWC = window.DLPWC || {};

(function (D) {

  // ── Fallback-vertalingen (worden overschreven via API) ────────
  const TRANS_FALLBACK = {
    en: {
      map: {
        loading: 'Loading toilets…', filter: 'Filter:', minScore: '⭐ Min. score:',
        good: 'Good', average: 'Average', poor: 'Poor', unknown: 'Unknown',
        all: 'All', park: 'Park', adventureWorld: 'Adventure World',
        village: 'Village', hotels: 'Hotels', parking: 'Parking',
        errorLoad: 'Error loading toilets', noResults: 'No results',
        toilets: 'toilets', toilet: 'toilet',
        sort: 'Sort:', sortHigh: 'Highest', sortLow: 'Lowest',
        overview: 'Overview', pin: 'Pin', unpin: 'Unpin',
        moreInfo: 'More info & review', ratings: 'ratings', rating: 'rating',
      }
    },
    nl: {
      map: {
        loading: 'Toiletten laden…', filter: 'Filter:', minScore: '⭐ Min. score:',
        good: 'Goed', average: 'Gemiddeld', poor: 'Slecht', unknown: 'Onbekend',
        all: 'Alles', park: 'Park', adventureWorld: 'Adventure World',
        village: 'Village', hotels: 'Hotels', parking: 'Parking',
        errorLoad: 'Fout bij laden', noResults: 'Geen resultaten',
        toilets: 'toiletten', toilet: 'toilet',
        sort: 'Sorteren:', sortHigh: 'Hoogste', sortLow: 'Laagste',
        overview: 'Overzicht', pin: 'Vastpinnen', unpin: 'Losmaken',
        moreInfo: 'Meer info & review', ratings: 'beoordelingen', rating: 'beoordeling',
      }
    },
    fr: {
      map: {
        loading: 'Chargement des toilettes…', filter: 'Filtrer :', minScore: '⭐ Score min. :',
        good: 'Bon', average: 'Moyen', poor: 'Mauvais', unknown: 'Inconnu',
        all: 'Tout', park: 'Parc', adventureWorld: 'Adventure World',
        village: 'Village', hotels: 'Hôtels', parking: 'Parking',
        errorLoad: 'Erreur de chargement', noResults: 'Aucun résultat',
        toilets: 'toilettes', toilet: 'toilette',
        sort: 'Trier :', sortHigh: 'Plus élevé', sortLow: 'Plus bas',
        overview: 'Aperçu', pin: 'Épingler', unpin: 'Désépingler',
        moreInfo: 'Plus d\'infos & avis', ratings: 'avis', rating: 'avis',
      }
    },
  };

  // Vertalingen ophalen: eerst fallback, dan uit API mergen
  D._trans = null;

  async function getTrans() {
    if (D._trans) return D._trans;
    const lang = D.lang || 'en';
    const fb   = TRANS_FALLBACK[lang] || TRANS_FALLBACK.en;
    try {
      const res  = await fetch(`/api/get-translations.php?lang=${lang}`);
      const data = await res.json();
      // Merge: API-data wint over fallback
      D._trans = {
        map:    Object.assign({}, fb.map,    data.map    || {}),
        toilet: Object.assign({}, fb.toilet, data.toilet || {}),
      };
    } catch (_) {
      D._trans = fb;
    }
    return D._trans;
  }

  // ── Gebieds-labels (per taal) ─────────────────────────────────
  const AREA_LABELS = {
    en: { PARK: '🏰 Park', STUDIOS: '🌟 Adventure World', VILLAGE: '🛍️ Village', HOTEL: '🏨 Hotel',  PARKING: '🅿️ Parking' },
    nl: { PARK: '🏰 Park', STUDIOS: '🌟 Adventure World', VILLAGE: '🛍️ Village', HOTEL: '🏨 Hotel',  PARKING: '🅿️ Parking' },
    fr: { PARK: '🏰 Parc', STUDIOS: '🌟 Adventure World', VILLAGE: '🛍️ Village', HOTEL: '🏨 Hôtels', PARKING: '🅿️ Parking' },
  };

  function areaLabel(area) {
    return (AREA_LABELS[D.lang] || AREA_LABELS.en)[area] || area;
  }

  // ── Kaart-centrerings per gebied (desktop/mobiel) ─────────────
  const CENTERS_DESKTOP = {
    '':       { lat: 48.8706,    lng: 2.779,    zoom: 15 },
    PARK:     { lat: 48.8726,    lng: 2.7767,   zoom: 17 },
    STUDIOS:  { lat: 48.8672,    lng: 2.7792,   zoom: 17 },
    VILLAGE:  { lat: 48.869341,  lng: 2.784396, zoom: 17 },
    HOTEL:    { lat: 48.870365,  lng: 2.789953, zoom: 17 },
    PARKING:  { lat: 48.874465,  lng: 2.786133, zoom: 17 },
  };
  const CENTERS_MOBILE = {
    '':       { lat: 48.8706,    lng: 2.779,    zoom: 15 },
    PARK:     { lat: 48.8726,    lng: 2.7767,   zoom: 17 },
    STUDIOS:  { lat: 48.866848,  lng: 2.777925, zoom: 17 },
    VILLAGE:  { lat: 48.869341,  lng: 2.784396, zoom: 17 },
    HOTEL:    { lat: 48.870365,  lng: 2.789953, zoom: 15 },
    PARKING:  { lat: 48.875562,  lng: 2.787088, zoom: 16 },
  };

  D.getCenter = function (area) {
    const table = window.innerWidth < 768 ? CENTERS_MOBILE : CENTERS_DESKTOP;
    return table[area] || table[''];
  };

  // ── Score → kleur ─────────────────────────────────────────────
  D.scoreColor = function (score) {
    if (score === null || score === undefined) return '#94a3b8';
    if (score < 2)    return '#ef4444';
    if (score < 2.75) return '#f97316';
    if (score < 3.5)  return '#eab308';
    if (score < 4.25) return '#84cc16';
    return '#22c55e';
  };

  // ── Sterren-weergave ──────────────────────────────────────────
  const NOT_RATED = { en: 'Not rated', nl: 'Niet beoordeeld', fr: 'Non évalué' };

  D.starRating = function (score, size = 15) {
    if (!score) return `<span style="color:#94a3b8;font-size:11px;">${NOT_RATED[D.lang] || NOT_RATED.en}</span>`;
    const filled = Math.floor(score);
    return `<span style="color:#c9a84c;font-size:${size}px;">${'★'.repeat(filled)}${'☆'.repeat(5 - filled)}</span>`
         + `<span style="color:#64748b;font-size:11px;margin-left:3px;">${parseFloat(score).toFixed(1)}</span>`;
  };

  // ── Gedeelde staat ────────────────────────────────────────────
  D.state = { sortDir: 'desc', activeArea: '', currentData: [] };

  // ── Marker-opmaak ─────────────────────────────────────────────
  const DEFAULT_PHOTO = 'https://www.looopings.nl/img/foto/041116proper1.jpg';

  D.makeIcon = function (color, large = false) {
    const size   = large ? 40 : 32;
    const shadow = large
      ? `box-shadow:0 0 0 4px ${color}55,0 4px 14px rgba(0,0,0,.4);`
      : 'box-shadow:0 3px 10px rgba(0,0,0,.35);';
    return L.divIcon({
      className: '',
      html: `<div style="
        width:${size}px;height:${size}px;border-radius:50%;
        background:${color};border:3px solid white;${shadow}
        display:flex;align-items:center;justify-content:center;
        font-size:${large ? 18 : 15}px;cursor:pointer;
        transition:all .15s;">🚽</div>`,
      iconSize:    [size, size],
      iconAnchor:  [size / 2, size / 2],
      popupAnchor: [0, -(size / 2) - 4],
    });
  };

  // ── Popup-inhoud ──────────────────────────────────────────────
  async function makePopupHtml(toilet) {
    const t      = await getTrans();
    const color  = D.scoreColor(toilet.score);
    const photo  = toilet.editorial_photo
      ? `/uploads/editorial/${toilet.editorial_photo}`
      : DEFAULT_PHOTO;
    const count  = toilet.review_count ?? 0;
    const label  = count !== 1 ? (t.map.ratings || 'ratings') : (t.map.rating || 'rating');

    return `
      <div style="font-family:'Segoe UI',sans-serif;border-radius:12px;overflow:hidden;width:240px;">
        <div style="position:relative;height:120px;overflow:hidden;background:#e2e8f0;">
          <img src="${photo}"
               style="width:100%;height:100%;object-fit:cover;"
               onerror="this.src='${DEFAULT_PHOTO}'">
          <div style="position:absolute;top:8px;left:8px;background:${color};color:white;
                      font-size:11px;font-weight:700;padding:2px 8px;border-radius:99px;
                      box-shadow:0 2px 6px rgba(0,0,0,.25);">
            ${toilet.score ? parseFloat(toilet.score).toFixed(1) + ' ★' : 'n.v.t.'}
          </div>
        </div>
        <div style="padding:12px 14px;">
          <div style="font-weight:700;font-size:13px;color:#3d1f8c;margin-bottom:4px;">
            ${toilet.name}
          </div>
          <div style="margin-bottom:6px;">${D.starRating(toilet.score, 14)}</div>
          <div style="font-size:11px;color:#94a3b8;margin-bottom:10px;">
            ${count} ${label} &bull; ${areaLabel(toilet.area)}
          </div>
          <a href="/toilet/${toilet.id}"
             style="display:block;text-align:center;
                    background:linear-gradient(135deg,#3d1f8c,#5229b8);
                    color:#fff;font-size:12px;font-weight:600;
                    padding:8px 14px;border-radius:20px;text-decoration:none;
                    box-shadow:0 2px 8px rgba(61,31,140,.3);">
            ✨ ${t.map.moreInfo || 'More info & review'}
          </a>
        </div>
      </div>`;
  }

  // ── Markers ───────────────────────────────────────────────────
  D.markers = new Map();

  D.renderMarkers = function (toilets) {
    D.markers.forEach(m => D.map.removeLayer(m));
    D.markers.clear();

    toilets.forEach(toilet => {
      const lat = parseFloat(toilet.latitude);
      const lng = parseFloat(toilet.longitude);
      if (isNaN(lat) || isNaN(lng)) return;

      const color  = D.scoreColor(toilet.score);
      const marker = L.marker([lat, lng], { icon: D.makeIcon(color) }).addTo(D.map);
      const popup  = L.popup({ maxWidth: 260, autoPanPadding: L.point(20, 80), autoPan: true });

      marker.bindPopup(popup);

      marker.on('popupopen', async () => {
        popup.setContent('<div style="padding:12px;text-align:center;color:#94a3b8;">⏳</div>');
        const html = await makePopupHtml(toilet);
        popup.setContent(html);

        // Popup boven of onder de marker afhankelijk van positie
        const pt   = D.map.latLngToContainerPoint([lat, lng]);
        const mapH = D.map.getSize().y;
        popup.options.offset = pt.y < mapH * 0.35
          ? L.point(0, 20)
          : L.point(0, -20);

        marker.setIcon(D.makeIcon(color, true));
        D.highlightSidebarItem(toilet.id);
      });

      marker.on('popupclose', () => {
        marker.setIcon(D.makeIcon(color, false));
      });

      D.markers.set(parseInt(toilet.id), marker);
    });
  };

  // ── Sidebar-item oplichten ────────────────────────────────────
  D.highlightSidebarItem = function (id) {
    document.querySelectorAll('.sidebar-item').forEach(el => {
      const active = parseInt(el.dataset.id) === parseInt(id);
      el.style.background   = active ? '#f3f0ff' : '';
      el.style.borderColor  = active ? '#3d1f8c' : '';
    });
  };

  // ── Toiletten laden ───────────────────────────────────────────
  D.loadToilets = async function (area = '', minScore = '') {
    const t = await getTrans();

    D.renderMarkers([]);
    document.getElementById('map-loading').style.display = 'flex';
    document.getElementById('map-error').classList.add('hidden');
    document.getElementById('sidebar-list').innerHTML =
      `<div class="text-center text-slate-400 text-sm py-8">${t.map.loading}</div>`;

    try {
      const params = new URLSearchParams();
      if (area)     params.set('area', area);
      if (minScore) params.set('minScore', minScore);

      const res  = await fetch(`/api/get-toilets.php?${params}`, { credentials: 'include' });
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      const data = await res.json();

      D.state.currentData = data;
      D.renderMarkers(data);

      if (typeof D.renderSidebar === 'function') D.renderSidebar(data);

    } catch (err) {
      const errEl = document.getElementById('map-error');
      errEl.textContent = `⚠️ ${t.map.errorLoad} — ${err.message}`;
      errEl.classList.remove('hidden');
      document.getElementById('sidebar-list').innerHTML =
        `<div class="text-center text-red-400 text-sm py-8">${t.map.errorLoad}</div>`;
    } finally {
      document.getElementById('map-loading').style.display = 'none';
    }
  };

  // ── Kaart initialiseren ───────────────────────────────────────
  D.initMap = function () {
    const START  = [48.8706, 2.779];
    const ZOOM   = 15;
    const BOUNDS = L.latLngBounds([[48.858, 2.76], [48.885, 2.802]]);

    D.map = L.map('map', {
      zoomControl:          false,
      minZoom:              ZOOM,
      maxZoom:              19,
      maxBounds:            BOUNDS,
      maxBoundsViscosity:   0.8,
    }).setView(START, ZOOM);

    D.map.on('drag', () => D.map.panInsideBounds(BOUNDS, { animate: false }));

    L.control.zoom({ position: 'bottomright' }).addTo(D.map);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom:     19,
      attribution: '&copy; <a href="https://openstreetmap.org">OpenStreetMap</a>',
    }).addTo(D.map);

    D.loadToilets();
  };

})(window.DLPWC);
