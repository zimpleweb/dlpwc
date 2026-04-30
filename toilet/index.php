<?php
/**
 * Toilet detail-pagina.
 * URL: /toilet/42  (via .htaccess rewrite → ?id=42)
 */
require_once __DIR__ . '/../helpers/lang.php';
$lang = get_lang();

$toiletId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($toiletId <= 0) {
    header('Location: /');
    exit;
}

$pageTitle = 'Toilet detail';
include __DIR__ . '/../components/base-open.php';
?>

<!-- Toilet detail — DOM-skeleton, gevuld door toilet-page.js -->
<div id="toilet-page" class="max-w-3xl mx-auto px-4 py-6" data-id="<?= $toiletId ?>">

  <!-- Laad-indicator -->
  <div id="loading" class="text-center text-slate-400 py-16">
    <div class="text-5xl animate-bounce mb-3">🚽</div>
    <div id="loading-text">Laden…</div>
  </div>

  <!-- Inhoud (gevuld door JS) -->
  <div id="content" class="hidden"></div>
</div>

<!-- Lightbox -->
<div id="lightbox"
     style="display:none;position:fixed;inset:0;
            background:rgba(0,0,0,.88);z-index:9999;
            align-items:center;justify-content:center;
            flex-direction:column;cursor:zoom-out;overflow:hidden;">
  <button id="lightbox-close"
          style="position:absolute;top:16px;right:16px;background:white;border:none;
                 border-radius:50%;width:38px;height:38px;font-size:18px;cursor:pointer;
                 box-shadow:0 2px 10px rgba(0,0,0,.4);z-index:10;">✕</button>
  <button id="lightbox-prev"
          style="position:absolute;left:16px;top:50%;transform:translateY(-50%);
                 background:rgba(255,255,255,.15);border:none;
                 border-radius:50%;width:42px;height:42px;font-size:22px;cursor:pointer;color:white;">‹</button>
  <img id="lightbox-img"
       style="display:block;max-width:90vw;max-height:80vh;border-radius:12px;
              box-shadow:0 8px 40px rgba(0,0,0,.6);object-fit:contain;flex-shrink:0;" alt="">
  <div id="lightbox-caption"
       style="margin-top:12px;background:rgba(0,0,0,.55);color:white;font-size:12px;
              padding:4px 14px;border-radius:99px;backdrop-filter:blur(4px);
              white-space:nowrap;flex-shrink:0;"></div>
  <button id="lightbox-next"
          style="position:absolute;right:16px;top:50%;transform:translateY(-50%);
                 background:rgba(255,255,255,.15);border:none;
                 border-radius:50%;width:42px;height:42px;font-size:22px;cursor:pointer;color:white;">›</button>
</div>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

<!-- DLPWC globale config + toilet-page script -->
<script>
window.DLPWC = { lang: <?= json_encode($lang) ?>, toiletId: <?= $toiletId ?> };
</script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="/assets/js/toilet-page.js"></script>

<?php include __DIR__ . '/../components/base-close.php'; ?>
