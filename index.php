<?php
require_once __DIR__ . '/helpers/lang.php';

$lang = get_lang();
$pageTitle = 'Startpagina';

$labels = [
    'en' => [
        'filter'         => 'Filter:',
        'minScore'       => '⭐ Min. score:',
        'minScoreMobile' => '⭐',
        'all'            => 'All',
        'scoreAll'       => 'All',
        'good'           => 'Good',
        'average'        => 'Average',
        'poor'           => 'Poor',
        'unknown'        => 'Unknown',
        'hotels'         => 'Hotels',
        'adventureWorld' => 'Adventure World',
        'loading'        => 'Loading toilets...',
        'overview'       => 'Overview',
        'pin'            => 'Pin',
        'sort'           => 'Sort:',
        'sortHigh'       => 'Highest',
        'sortLow'        => 'Lowest',
        'count'          => '0 toilets',
    ],
    'nl' => [
        'filter'         => 'Filter:',
        'minScore'       => '⭐ Min. score:',
        'minScoreMobile' => '⭐',
        'all'            => 'Alles',
        'scoreAll'       => 'Alle',
        'good'           => 'Goed',
        'average'        => 'Gemiddeld',
        'poor'           => 'Slecht',
        'unknown'        => 'Onbekend',
        'hotels'         => 'Hotels',
        'adventureWorld' => 'Adventure World',
        'loading'        => 'Toiletten laden...',
        'overview'       => 'Overzicht',
        'pin'            => 'Vastpinnen',
        'sort'           => 'Sorteren:',
        'sortHigh'       => 'Hoogste',
        'sortLow'        => 'Laagste',
        'count'          => '0 toiletten',
    ],
    'fr' => [
        'filter'         => 'Filtrer :',
        'minScore'       => '⭐ Score min. :',
        'minScoreMobile' => '⭐',
        'all'            => 'Tout',
        'scoreAll'       => 'Tous',
        'good'           => 'Bon',
        'average'        => 'Moyen',
        'poor'           => 'Mauvais',
        'unknown'        => 'Inconnu',
        'hotels'         => 'Hôtels',
        'adventureWorld' => 'Adventure World',
        'loading'        => 'Chargement des toilettes...',
        'overview'       => 'Aperçu',
        'pin'            => 'Épingler',
        'sort'           => 'Trier :',
        'sortHigh'       => 'Plus élevé',
        'sortLow'        => 'Plus bas',
        'count'          => '0 toilettes',
    ],
];
$l = $labels[$lang] ?? $labels['en'];

include __DIR__ . '/components/base-open.php';
?>

<!-- Kaartpagina: full-height layout onder de header (56px) -->
<div class="flex flex-col" style="height: calc(100vh - 59px);">

  <!-- ── MapFilters ──────────────────────────────── -->
  <div class="bg-white border-b-2 border-[#00915a] px-4 py-2 flex flex-wrap gap-2 items-center shadow z-10 flex-shrink-0">
    <span id="filter-label" class="text-sm font-bold mr-1 hidden sm:inline" style="color:#3d1f8c;">
      <?= htmlspecialchars($l['filter']) ?>
    </span>

    <!-- Sterren-filter -->
    <label class="text-sm font-medium text-slate-600 flex items-center gap-1">
      <span id="score-label" class="hidden sm:inline"><?= htmlspecialchars($l['minScore']) ?></span>
      <span class="sm:hidden"><?= htmlspecialchars($l['minScoreMobile']) ?></span>
      <select id="filter-score" class="border border-slate-300 rounded-full px-2 py-0.5 text-sm
               text-slate-700 focus:outline-none focus:border-[#3d1f8c]">
        <option value=""><?= htmlspecialchars($l['scoreAll']) ?></option>
        <option value="4">4+ ★</option>
        <option value="3">3+ ★</option>
        <option value="2">2+ ★</option>
      </select>
    </label>

    <!-- Gebied-knoppen -->
    <div class="flex flex-wrap gap-1.5">
      <button data-area="" class="area-btn px-3 py-1 rounded-full border-2 text-xs font-semibold transition"
              style="border-color:#3d1f8c;background:#3d1f8c;color:white;">
        🗺️ <span class="hidden sm:inline"><?= htmlspecialchars($l['all']) ?></span>
      </button>
      <button data-area="PARK" class="area-btn px-3 py-1 rounded-full border-2 text-xs font-semibold transition"
              style="border-color:#3d1f8c;color:#3d1f8c;background:transparent;"
              onmouseover="this.style.background='#3d1f8c';this.style.color='white'"
              onmouseout="if(!this.classList.contains('active')){this.style.background='transparent';this.style.color='#3d1f8c'}">
        🏰 <span class="hidden sm:inline">Park</span>
      </button>
      <button data-area="STUDIOS" class="area-btn px-3 py-1 rounded-full border-2 text-xs font-semibold transition"
              style="border-color:#3d1f8c;color:#3d1f8c;background:transparent;"
              onmouseover="this.style.background='#3d1f8c';this.style.color='white'"
              onmouseout="if(!this.classList.contains('active')){this.style.background='transparent';this.style.color='#3d1f8c'}">
        🌟 <span class="hidden sm:inline"><?= htmlspecialchars($l['adventureWorld']) ?></span>
      </button>
      <button data-area="VILLAGE" class="area-btn px-3 py-1 rounded-full border-2 text-xs font-semibold transition"
              style="border-color:#3d1f8c;color:#3d1f8c;background:transparent;"
              onmouseover="this.style.background='#3d1f8c';this.style.color='white'"
              onmouseout="if(!this.classList.contains('active')){this.style.background='transparent';this.style.color='#3d1f8c'}">
        🛍️ <span class="hidden sm:inline">Village</span>
      </button>
      <button data-area="HOTEL" class="area-btn px-3 py-1 rounded-full border-2 text-xs font-semibold transition"
              style="border-color:#3d1f8c;color:#3d1f8c;background:transparent;"
              onmouseover="this.style.background='#3d1f8c';this.style.color='white'"
              onmouseout="if(!this.classList.contains('active')){this.style.background='transparent';this.style.color='#3d1f8c'}">
        🏨 <span class="hidden sm:inline"><?= htmlspecialchars($l['hotels']) ?></span>
      </button>
      <button data-area="PARKING" class="area-btn px-3 py-1 rounded-full border-2 text-xs font-semibold transition"
              style="border-color:#3d1f8c;color:#3d1f8c;background:transparent;"
              onmouseover="this.style.background='#3d1f8c';this.style.color='white'"
              onmouseout="if(!this.classList.contains('active')){this.style.background='transparent';this.style.color='#3d1f8c'}">
        🅿️ <span class="hidden sm:inline">Parking</span>
      </button>
    </div>

    <!-- Legenda (desktop) -->
    <div class="ml-auto hidden sm:flex items-center gap-3 text-xs text-slate-500">
      <span class="flex items-center gap-1">
        <span style="display:inline-block;width:11px;height:11px;border-radius:50%;background:#22c55e;border:2px solid white;flex-shrink:0;"></span>
        <span id="legend-good"><?= htmlspecialchars($l['good']) ?></span>
      </span>
      <span class="flex items-center gap-1">
        <span style="display:inline-block;width:11px;height:11px;border-radius:50%;background:#eab308;border:2px solid white;flex-shrink:0;"></span>
        <span id="legend-average"><?= htmlspecialchars($l['average']) ?></span>
      </span>
      <span class="flex items-center gap-1">
        <span style="display:inline-block;width:11px;height:11px;border-radius:50%;background:#ef4444;border:2px solid white;flex-shrink:0;"></span>
        <span id="legend-poor"><?= htmlspecialchars($l['poor']) ?></span>
      </span>
      <span class="flex items-center gap-1">
        <span style="display:inline-block;width:11px;height:11px;border-radius:50%;background:#94a3b8;border:2px solid white;flex-shrink:0;"></span>
        <span id="legend-unknown"><?= htmlspecialchars($l['unknown']) ?></span>
      </span>
    </div>
  </div>

  <!-- ── Kaart + Sidebar ────────────────────────── -->
  <div class="flex flex-1 overflow-hidden relative">

    <!-- Sidebar toggle-knop -->
    <button id="sidebar-toggle"
            class="absolute top-3 left-3 z-[700] text-white rounded-full
                   w-9 h-9 flex items-center justify-center shadow-lg transition"
            style="background:#3d1f8c;"
            onmouseover="this.style.background='#5229b8'"
            onmouseout="this.style.background='#3d1f8c'"
            title="<?= htmlspecialchars($l['overview']) ?>">☰</button>

    <!-- Sidebar -->
    <div id="sidebar"
         class="absolute top-0 left-0 h-full bg-white shadow-2xl border-r border-slate-200
                flex flex-col overflow-hidden"
         style="width:300px;z-index:1001;transform:translateX(-300px);">

      <!-- Sidebar header -->
      <div class="text-white px-4 py-3 flex items-center justify-between flex-shrink-0"
           style="background:#3d1f8c;">
        <span id="sidebar-overview-title" class="font-bold text-sm">
          <?= htmlspecialchars($l['overview']) ?>
        </span>
        <div class="flex items-center gap-2">
          <button id="sidebar-pin"
                  class="text-xs px-2 py-0.5 rounded border border-white/50
                         hover:bg-white/20 transition hidden sm:block">
            <?= htmlspecialchars($l['pin']) ?>
          </button>
          <button id="sidebar-close" class="text-white/70 hover:text-white text-lg leading-none">✕</button>
        </div>
      </div>

      <!-- Drag handle (mobiel) -->
      <div class="sm:hidden flex justify-center pt-2 pb-1 flex-shrink-0">
        <div style="width:36px;height:4px;border-radius:2px;background:#e2e8f0;"></div>
      </div>

      <!-- Sortering -->
      <div class="px-3 py-2 border-b border-slate-100 flex items-center gap-2 flex-shrink-0">
        <span id="sort-label" class="text-xs text-slate-500"><?= htmlspecialchars($l['sort']) ?></span>
        <button data-sort="desc" class="sort-btn text-xs px-2 py-1 rounded-full border text-white transition"
                style="border-color:#3d1f8c;background:#3d1f8c;">
          <?= htmlspecialchars($l['sortHigh']) ?>
        </button>
        <button data-sort="asc" class="sort-btn text-xs px-2 py-1 rounded-full border transition hover:text-white"
                style="border-color:#3d1f8c;color:#3d1f8c;"
                onmouseover="this.style.background='#3d1f8c'"
                onmouseout="this.style.background=''">
          <?= htmlspecialchars($l['sortLow']) ?>
        </button>
      </div>

      <div id="sidebar-list" class="flex-1 overflow-y-auto px-2 py-2 space-y-1.5">
        <div class="text-center text-slate-400 text-sm py-8"><?= htmlspecialchars($l['loading']) ?></div>
      </div>
      <div id="sidebar-count" class="px-3 py-2 border-t border-slate-100 text-xs text-slate-400 flex-shrink-0">
        <?= htmlspecialchars($l['count']) ?>
      </div>
    </div>

    <!-- ── MapContainer ────────────────────────── -->
    <div id="map-container" class="flex-1 relative transition-all duration-300">
      <div id="map" class="w-full h-full"></div>
      <canvas id="map-magic-canvas" class="absolute inset-0 pointer-events-none"
              style="z-index:500;width:100%;height:100%;mix-blend-mode:screen;opacity:0.5;"></canvas>
      <div id="map-loading" class="absolute inset-0 flex flex-col items-center justify-center bg-white/80"
           style="z-index:600;">
        <div class="text-5xl animate-bounce mb-3">🚽</div>
        <div id="map-loading-text" class="font-semibold" style="color:#3d1f8c;">
          <?= htmlspecialchars($l['loading']) ?>
        </div>
      </div>
      <div id="map-error"
           class="hidden absolute bottom-20 left-1/2 -translate-x-1/2
                  bg-red-50 border border-red-300 text-red-700 text-sm
                  px-4 py-2 rounded-lg shadow" style="z-index:600;"></div>
    </div>
  </div>
</div>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

<!-- DLPWC globale config (vóór alle kaart-scripts) -->
<script>
window.DLPWC = { lang: <?= json_encode($lang) ?> };
</script>

<!-- Leaflet + kaart-scripts in vereiste volgorde -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="/assets/js/map.js"></script>
<script src="/assets/js/sidebar.js"></script>
<script src="/assets/js/filters.js"></script>

<!-- Bootstrap: kaart + sidebar + filters starten -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  DLPWC.initMap();
  DLPWC.initSidebar();

  // Vertalingen ophalen, daarna filters updaten
  fetch('/api/get-translations.php?lang=<?= $lang ?>')
    .then(r => r.json())
    .then(data => {
      DLPWC._trans = {
        map:    Object.assign({}, DLPWC._trans && DLPWC._trans.map    || {}, data.map    || {}),
        toilet: Object.assign({}, DLPWC._trans && DLPWC._trans.toilet || {}, data.toilet || {}),
      };
      DLPWC.initFilters();
    })
    .catch(() => DLPWC.initFilters()); // fallback bij API-fout
});
</script>

<?php include __DIR__ . '/components/base-close.php'; ?>
