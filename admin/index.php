<?php
/**
 * Admin-paneel — equivalent van admin.astro
 * Beveiligingscheck: alleen ingelogde admins/moderators via sessie-check in JS.
 * Tabs: reviews, recensies, media, fotos, geblokkeerd, toiletten, gebruikers, nieuw-account, site
 */
require_once __DIR__ . '/../helpers/lang.php';
$lang = get_lang();
$pageTitle = ['en' => 'Admin', 'nl' => 'Beheer', 'fr' => 'Administration'][$lang] ?? 'Admin';

$l = [
    'en' => [
        'accessDenied'     => '🚫 No access — admins and moderators only.',
        'tabReviews'       => 'Reviews',
        'tabRecensies'     => 'All reviews',
        'tabMedia'         => 'Media',
        'tabFotos'         => 'Photos',
        'tabGeblokkeerd'   => 'Blocked',
        'tabToiletten'     => 'Toilets',
        'tabGebruikers'    => 'Users',
        'tabNieuwAccount'  => 'New account',
        'tabSiteAdmin'     => 'Site',
        'pendingTitle'     => 'Pending reviews',
        'refresh'          => '↻ Refresh',
        'deleteTitle'      => '🗑️ Delete approved reviews',
        'searchPh'         => 'Search by toilet or user...',
        'load'             => 'Load',
        'approvedDefault'  => "Click 'Load' to show approved reviews.",
        'loading'          => 'Loading...',
        'usersTitle'       => 'User management',
        'mediaTitle'       => 'Media management',
        'mediaSubtitle'    => 'Upload, change or delete editorial photos per toilet.',
        'fotosTitle'       => "All uploaded photos",
        'blockTitle'       => 'Blocked users & guests',
        'toiletsTitle'     => 'Manage toilet locations',
        'addBtn'           => '+ New location',
        'formTitle'        => 'New location',
        'formHint'         => 'Click on the map to set coordinates.',
        'nameLabel'        => 'Name',
        'areaLabel'        => 'Area',
        'latLabel'         => 'Latitude',
        'lngLabel'         => 'Longitude',
        'descLabel'        => 'Description',
        'submit'           => 'Save',
        'cancel'           => 'Cancel',
        'siteTitle'        => 'Site settings',
        'tabMailtemplates' => 'Mail templates',
        'smtpTitle'        => 'E-mail via Brevo',
        'approve'          => 'Approve',
        'reject'           => 'Reject',
        'blockUser'        => 'Block user',
        'days3'            => '3 days',
        'week1'            => '1 week',
        'month1'           => '1 month',
        'forever'          => 'Forever',
        'noPending'        => 'No pending reviews.',
        'guest'            => 'Guest',
        'error'            => 'An error occurred.',
        'confirmDelete'    => 'Delete this review?',
        'noApproved'       => 'No results found.',
        'delete'           => 'Delete',
    ],
    'nl' => [
        'accessDenied'     => '🚫 Geen toegang — alleen admins en moderators.',
        'tabReviews'       => 'Reviews',
        'tabRecensies'     => 'Alle recensies',
        'tabMedia'         => 'Media',
        'tabFotos'         => "Foto's",
        'tabGeblokkeerd'   => 'Geblokkeerd',
        'tabToiletten'     => 'Toiletten',
        'tabGebruikers'    => 'Gebruikers',
        'tabNieuwAccount'  => 'Nieuw account',
        'tabSiteAdmin'     => 'Site',
        'pendingTitle'     => 'Openstaande recensies',
        'refresh'          => '↻ Vernieuwen',
        'deleteTitle'      => '🗑️ Goedgekeurde recensies verwijderen',
        'searchPh'         => 'Zoek op toilet of gebruiker...',
        'load'             => 'Laden',
        'approvedDefault'  => "Klik op 'Laden' om goedgekeurde recensies te tonen.",
        'loading'          => 'Laden...',
        'usersTitle'       => 'Gebruikersbeheer',
        'mediaTitle'       => 'Mediabeheer',
        'mediaSubtitle'    => "Redactiefoto's per toilet uploaden, wijzigen of verwijderen.",
        'fotosTitle'       => "Alle geüploade foto's",
        'blockTitle'       => 'Geblokkeerde gebruikers & gasten',
        'toiletsTitle'     => 'Toiletlocaties beheren',
        'addBtn'           => '+ Nieuwe locatie',
        'formTitle'        => 'Nieuwe locatie',
        'formHint'         => 'Klik op de kaart om coördinaten in te stellen.',
        'nameLabel'        => 'Naam',
        'areaLabel'        => 'Gebied',
        'latLabel'         => 'Breedtegraad',
        'lngLabel'         => 'Lengtegraad',
        'descLabel'        => 'Omschrijving',
        'submit'           => 'Opslaan',
        'cancel'           => 'Annuleren',
        'siteTitle'        => 'Site-instellingen',
        'tabMailtemplates' => 'Mailtemplates',
        'smtpTitle'        => 'E-mail via Brevo',
        'approve'          => 'Goedkeuren',
        'reject'           => 'Afkeuren',
        'blockUser'        => 'Blokkeer gebruiker',
        'days3'            => '3 dagen',
        'week1'            => '1 week',
        'month1'           => '1 maand',
        'forever'          => 'Voor altijd',
        'noPending'        => 'Geen openstaande reviews.',
        'guest'            => 'Gast',
        'error'            => 'Er is een fout opgetreden.',
        'confirmDelete'    => 'Deze review verwijderen?',
        'noApproved'       => 'Geen resultaten gevonden.',
        'delete'           => 'Verwijder',
    ],
    'fr' => [
        'accessDenied'     => '🚫 Accès refusé — réservé aux admins et modérateurs.',
        'tabReviews'       => 'Avis',
        'tabRecensies'     => 'Tous les avis',
        'tabMedia'         => 'Médias',
        'tabFotos'         => 'Photos',
        'tabGeblokkeerd'   => 'Bloqués',
        'tabToiletten'     => 'Toilettes',
        'tabGebruikers'    => 'Utilisateurs',
        'tabNieuwAccount'  => 'Nouveau compte',
        'tabSiteAdmin'     => 'Site',
        'pendingTitle'     => 'Avis en attente',
        'refresh'          => '↻ Actualiser',
        'deleteTitle'      => '🗑️ Supprimer les avis approuvés',
        'searchPh'         => 'Rechercher par toilette ou utilisateur...',
        'load'             => 'Charger',
        'approvedDefault'  => "Cliquez sur 'Charger' pour afficher les avis approuvés.",
        'loading'          => 'Chargement...',
        'usersTitle'       => 'Gestion des utilisateurs',
        'mediaTitle'       => 'Gestion des médias',
        'mediaSubtitle'    => 'Uploader, modifier ou supprimer des photos éditoriales par toilette.',
        'fotosTitle'       => 'Toutes les photos uploadées',
        'blockTitle'       => 'Utilisateurs & invités bloqués',
        'toiletsTitle'     => 'Gérer les emplacements',
        'addBtn'           => '+ Nouvel emplacement',
        'formTitle'        => 'Nouvel emplacement',
        'formHint'         => 'Cliquez sur la carte pour définir les coordonnées.',
        'nameLabel'        => 'Nom',
        'areaLabel'        => 'Zone',
        'latLabel'         => 'Latitude',
        'lngLabel'         => 'Longitude',
        'descLabel'        => 'Description',
        'submit'           => 'Enregistrer',
        'cancel'           => 'Annuler',
        'siteTitle'        => 'Paramètres du site',
        'tabMailtemplates' => 'Modèles de mail',
        'smtpTitle'        => 'E-mail via Brevo',
        'approve'          => 'Approuver',
        'reject'           => 'Rejeter',
        'blockUser'        => 'Bloquer l\'utilisateur',
        'days3'            => '3 jours',
        'week1'            => '1 semaine',
        'month1'           => '1 mois',
        'forever'          => 'Pour toujours',
        'noPending'        => 'Aucun avis en attente.',
        'guest'            => 'Invité',
        'error'            => 'Une erreur s\'est produite.',
        'confirmDelete'    => 'Supprimer cet avis ?',
        'noApproved'       => 'Aucun résultat trouvé.',
        'delete'           => 'Supprimer',
    ],
][$lang] ?? [];

include __DIR__ . '/../components/base-open.php';
?>

<!-- Toegangscontrole -->
<div id="access-denied" class="hidden text-center py-24 text-slate-500">
  <?= htmlspecialchars($l['accessDenied']) ?>
</div>

<!-- Admin-paneel -->
<div id="admin-panel" class="hidden">

  <!-- Tab-balk (desktop) -->
  <nav class="hidden sm:flex justify-center gap-1 py-2 px-4 border-b border-slate-200 bg-white sticky top-0 z-50 overflow-x-auto" id="tab-bar">
    <?php
    $tabs = [
        ['key' => 'reviews',      'icon' => '📋', 'label' => $l['tabReviews'],      'adminOnly' => false],
        ['key' => 'recensies',    'icon' => '💬', 'label' => $l['tabRecensies'],    'adminOnly' => false],
        ['key' => 'media',        'icon' => '🖼️', 'label' => $l['tabMedia'],        'adminOnly' => false],
        ['key' => 'fotos',        'icon' => '📸', 'label' => $l['tabFotos'],        'adminOnly' => false],
        ['key' => 'geblokkeerd',  'icon' => '🚫', 'label' => $l['tabGeblokkeerd'], 'adminOnly' => false],
        ['key' => 'toiletten',    'icon' => '🚽', 'label' => $l['tabToiletten'],   'adminOnly' => true],
        ['key' => 'gebruikers',   'icon' => '👥', 'label' => $l['tabGebruikers'],  'adminOnly' => true],
        ['key' => 'nieuw-account','icon' => '➕', 'label' => $l['tabNieuwAccount'],'adminOnly' => true],
        ['key' => 'mailtemplates','icon' => '✉️', 'label' => $l['tabMailtemplates'],'adminOnly' => true],
        ['key' => 'site',         'icon' => '⚙️', 'label' => $l['tabSiteAdmin'],   'adminOnly' => true],
    ];
    foreach ($tabs as $tab): ?>
      <button data-tab="<?= $tab['key'] ?>"
              class="tab-btn flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold
                     border-2 border-transparent transition-all duration-150
                     text-[#3d1f8c] hover:bg-[#3d1f8c]/10<?= $tab['adminOnly'] ? ' tab-admin-only' : '' ?>">
        <span class="text-sm leading-none"><?= $tab['icon'] ?></span>
        <span><?= htmlspecialchars($tab['label']) ?></span>
      </button>
    <?php endforeach; ?>
  </nav>

  <!-- Mobiel: select -->
  <div class="sm:hidden py-2 px-4 border-b border-slate-200 bg-white">
    <select id="mobile-tab-select"
            class="w-full border-2 border-[#3d1f8c] rounded-xl px-4 py-2.5 text-sm font-semibold
                   bg-white focus:outline-none text-[#3d1f8c]">
      <?php foreach ($tabs as $tab): ?>
        <option value="<?= $tab['key'] ?>"
                <?= $tab['adminOnly'] ? 'class="tab-admin-only-option"' : '' ?>>
          <?= $tab['icon'] . ' ' . htmlspecialchars($tab['label']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <!-- Tab-inhoud -->
  <div class="max-w-4xl mx-auto px-3 sm:px-4 py-4 sm:py-6 admin-scroll">

    <!-- Tab: Reviews (openstaand) -->
    <div id="tab-reviews">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-base font-semibold text-[#003f8a]"><?= htmlspecialchars($l['pendingTitle']) ?></h2>
        <button id="refresh-pending" class="text-xs text-slate-400 hover:underline"><?= htmlspecialchars($l['refresh']) ?></button>
      </div>
      <details class="mb-5 bg-white rounded-xl border border-slate-200 shadow-sm">
        <summary class="px-4 py-3 text-sm font-semibold text-[#003f8a] cursor-pointer select-none">
          <?= htmlspecialchars($l['deleteTitle']) ?>
        </summary>
        <div class="px-4 pb-4">
          <div class="flex gap-2 mt-3 mb-3">
            <input id="review-search" type="text" placeholder="<?= htmlspecialchars($l['searchPh']) ?>"
                   class="flex-1 border border-slate-300 rounded-lg px-3 py-1.5 text-sm
                          focus:outline-none focus:border-[#003f8a]">
            <button id="load-approved-btn" class="text-xs bg-[#003f8a] text-white px-4 py-1.5 rounded-lg hover:opacity-90">
              <?= htmlspecialchars($l['load']) ?>
            </button>
          </div>
          <div id="approved-reviews" class="space-y-2">
            <div class="text-slate-400 text-sm"><?= htmlspecialchars($l['approvedDefault']) ?></div>
          </div>
        </div>
      </details>
      <div id="pending-reviews" class="space-y-3">
        <div class="text-slate-400 text-sm"><?= htmlspecialchars($l['loading']) ?></div>
      </div>
    </div>

    <!-- Tab: Alle recensies -->
    <div id="tab-recensies" class="hidden">
      <div class="flex flex-col sm:flex-row flex-wrap gap-2 mb-4 p-4 bg-slate-50 rounded-xl border border-slate-200">
        <input id="rec-search" type="text" placeholder="🔍 Zoeken..."
               class="border border-slate-300 rounded-lg px-3 py-2 text-sm
                      focus:outline-none focus:border-[#3d1f8c] flex-1 min-w-[120px]">
        <select id="rec-stars" class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none bg-white">
          <option value="">⭐ —</option>
          <option value="5">★★★★★</option><option value="4">★★★★☆</option>
          <option value="3">★★★☆☆</option><option value="2">★★☆☆☆</option><option value="1">★☆☆☆☆</option>
        </select>
        <select id="rec-lang" class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none bg-white">
          <option value="">🌐 —</option>
          <option value="en">🇬🇧 English</option>
          <option value="nl">🇳🇱 Nederlands</option>
          <option value="fr">🇫🇷 Français</option>
        </select>
        <input id="rec-date-from" type="date" class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none">
        <span class="self-center text-slate-400 text-sm">→</span>
        <input id="rec-date-to" type="date" class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none">
        <button id="rec-reset" class="text-xs bg-slate-200 text-slate-700 px-3 py-2 rounded-lg hover:bg-slate-300 transition">↺</button>
      </div>
      <div id="recensies-count" class="text-xs text-slate-400 mb-3"></div>
      <div id="recensies-list" class="space-y-3"></div>
      <div id="recensies-pagination" class="flex flex-wrap gap-2 mt-4"></div>
    </div>

    <!-- Tab: Media -->
    <div id="tab-media" class="hidden">
      <h2 class="text-base font-semibold text-[#003f8a] mb-1"><?= htmlspecialchars($l['mediaTitle']) ?></h2>
      <p class="text-sm text-slate-500 mb-4"><?= htmlspecialchars($l['mediaSubtitle']) ?></p>
      <div id="media-list" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
        <div class="text-slate-400 text-sm col-span-4"><?= htmlspecialchars($l['loading']) ?></div>
      </div>
    </div>

    <!-- Tab: Foto's -->
    <div id="tab-fotos" class="hidden">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-base font-semibold text-[#003f8a]"><?= htmlspecialchars($l['fotosTitle']) ?></h2>
        <button id="refresh-photos" class="text-xs text-slate-400 hover:underline"><?= htmlspecialchars($l['refresh']) ?></button>
      </div>
      <div class="flex gap-2 mb-4 flex-wrap">
        <button data-filter="all" class="photo-filter-btn text-xs px-3 py-1.5 rounded-full bg-[#003f8a] text-white font-medium transition">Alles</button>
        <button data-filter="editorial" class="photo-filter-btn text-xs px-3 py-1.5 rounded-full bg-slate-100 text-slate-600 font-medium hover:bg-slate-200 transition">📷 Redactie</button>
        <button data-filter="review" class="photo-filter-btn text-xs px-3 py-1.5 rounded-full bg-slate-100 text-slate-600 font-medium hover:bg-slate-200 transition">👤 Reviews</button>
      </div>
      <div id="photos-count" class="text-xs text-slate-400 mb-3"></div>
      <div id="photos-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
        <div class="text-slate-400 text-sm col-span-5"><?= htmlspecialchars($l['loading']) ?></div>
      </div>
    </div>

    <!-- Tab: Geblokkeerd -->
    <div id="tab-geblokkeerd" class="hidden">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-base font-semibold text-[#003f8a]"><?= htmlspecialchars($l['blockTitle']) ?></h2>
        <button id="refresh-blocked" class="text-xs text-slate-400 hover:underline"><?= htmlspecialchars($l['refresh']) ?></button>
      </div>
      <div id="blocked-list" class="space-y-2">
        <div class="text-slate-400 text-sm"><?= htmlspecialchars($l['loading']) ?></div>
      </div>
    </div>

    <!-- Tab: Toiletten (admin only) -->
    <div id="tab-toiletten" class="hidden">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-base font-semibold text-[#003f8a]"><?= htmlspecialchars($l['toiletsTitle']) ?></h2>
        <button id="toggle-add-form" class="bg-[#003f8a] text-white text-sm px-4 py-2 rounded-lg hover:opacity-90 transition">
          <?= htmlspecialchars($l['addBtn']) ?>
        </button>
      </div>
      <div id="add-toilet-form" class="hidden mb-6 bg-slate-50 rounded-xl border border-slate-200 p-5">
        <h3 id="toilet-form-title" class="font-semibold text-[#003f8a] mb-1"><?= htmlspecialchars($l['formTitle']) ?></h3>
        <p class="text-xs text-slate-500 mb-3"><?= htmlspecialchars($l['formHint']) ?></p>
        <div id="admin-map" class="w-full rounded-lg border border-slate-300 mb-4" style="height:260px;"></div>
        <form id="toilet-form" class="grid grid-cols-2 gap-3">
          <input type="hidden" name="edit_id" id="toilet-edit-id" value="">
          <div class="col-span-2 sm:col-span-1">
            <label class="text-xs font-semibold text-slate-600"><?= htmlspecialchars($l['nameLabel']) ?> *</label>
            <input name="name" required class="w-full border rounded-lg px-3 py-2 text-sm mt-1 focus:outline-none focus:border-[#003f8a]">
          </div>
          <div>
            <label class="text-xs font-semibold text-slate-600"><?= htmlspecialchars($l['areaLabel']) ?> *</label>
            <select name="area" required class="w-full border rounded-lg px-3 py-2 text-sm mt-1 focus:outline-none focus:border-[#003f8a]">
              <option value="PARK">🏰 Park</option>
              <option value="STUDIOS">🎬 Studios</option>
              <option value="VILLAGE">🛍️ Village</option>
              <option value="HOTEL">🏨 Hotel</option>
              <option value="PARKING">🅿️ Parking</option>
            </select>
          </div>
          <div>
            <label class="text-xs font-semibold text-slate-600"><?= htmlspecialchars($l['latLabel']) ?> *</label>
            <input name="latitude" id="lat-input" required type="number" step="any"
                   class="w-full border rounded-lg px-3 py-2 text-sm mt-1 focus:outline-none focus:border-[#003f8a]">
          </div>
          <div>
            <label class="text-xs font-semibold text-slate-600"><?= htmlspecialchars($l['lngLabel']) ?> *</label>
            <input name="longitude" id="lng-input" required type="number" step="any"
                   class="w-full border rounded-lg px-3 py-2 text-sm mt-1 focus:outline-none focus:border-[#003f8a]">
          </div>
          <div class="col-span-2">
            <label class="text-xs font-semibold text-slate-600"><?= htmlspecialchars($l['descLabel']) ?></label>
            <input name="description" class="w-full border rounded-lg px-3 py-2 text-sm mt-1 focus:outline-none focus:border-[#003f8a]">
          </div>
          <div class="col-span-2" id="photo-upload-row">
            <label class="text-xs font-semibold text-slate-600">Foto (optioneel)</label>
            <input type="file" id="toilet-photo" accept="image/*"
                   class="w-full text-sm mt-1 border rounded-lg px-3 py-2 focus:outline-none focus:border-[#003f8a]">
          </div>
          <div class="col-span-2 flex gap-2">
            <button type="submit" id="toilet-submit-btn"
                    class="bg-[#003f8a] text-white px-5 py-2 rounded-lg text-sm font-semibold hover:opacity-90 transition">
              <?= htmlspecialchars($l['submit']) ?>
            </button>
            <button type="button" id="cancel-add"
                    class="bg-slate-200 text-slate-700 px-5 py-2 rounded-lg text-sm font-semibold hover:opacity-80 transition">
              <?= htmlspecialchars($l['cancel']) ?>
            </button>
          </div>
          <div id="toilet-msg" class="col-span-2 text-sm"></div>
        </form>
      </div>
      <div id="toilet-list" class="space-y-3">
        <div class="text-slate-400 text-sm"><?= htmlspecialchars($l['loading']) ?></div>
      </div>
    </div>

    <!-- Tab: Gebruikers (admin only) -->
    <div id="tab-gebruikers" class="hidden">
      <h2 class="text-base font-semibold text-[#003f8a] mb-3"><?= htmlspecialchars($l['usersTitle']) ?></h2>
      <div id="users-list" class="space-y-2">
        <div class="text-slate-400 text-sm"><?= htmlspecialchars($l['loading']) ?></div>
      </div>
    </div>

    <!-- Tab: Nieuw account (admin only) -->
    <div id="tab-nieuw-account" class="hidden">
      <h2 class="text-base font-semibold text-[#003f8a] mb-4">Nieuw account aanmaken</h2>
      <div id="create-user-content"></div>
    </div>

    <!-- Tab: Mailtemplates (admin only) -->
    <div id="tab-mailtemplates" class="hidden">
      <h2 class="text-base font-semibold text-[#003f8a] mb-4"><?= htmlspecialchars($l['tabMailtemplates']) ?></h2>
      <div id="mailtemplates-content"></div>
    </div>

    <!-- Tab: Site-instellingen (admin only) -->
    <div id="tab-site" class="hidden">
      <h2 class="text-base font-semibold text-[#003f8a] mb-4"><?= htmlspecialchars($l['siteTitle']) ?></h2>
      <div id="smtp-settings-content" class="mb-6"></div>
      <div id="recaptcha-settings-content" class="mb-6"></div>
      <div id="site-settings-content"></div>
    </div>

  </div><!-- /admin-scroll -->
</div><!-- /admin-panel -->

<!-- Mail Preview Modal -->
<div id="mail-preview-modal"
     class="fixed inset-0 bg-black/60 z-[9999] hidden items-center justify-center p-4"
     style="display:none;">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col">
    <!-- Modal header -->
    <div class="flex items-start justify-between px-5 py-4 border-b border-slate-200 flex-shrink-0">
      <div class="min-w-0 pr-4">
        <div class="flex items-center gap-2 mb-1">
          <span id="preview-flag" class="text-lg leading-none"></span>
          <span id="preview-template-label" class="font-semibold text-sm text-[#003f8a] truncate"></span>
        </div>
        <div class="flex items-center gap-1 text-xs text-slate-500">
          <span class="font-medium text-slate-400">Onderwerp:</span>
          <span id="preview-subject" class="font-semibold text-slate-700"></span>
        </div>
      </div>
      <button id="close-preview-modal"
              class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full
                     text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition text-lg font-bold leading-none">
        ✕
      </button>
    </div>
    <!-- Mail body -->
    <div class="flex-1 overflow-auto p-5">
      <div class="bg-slate-50 border border-slate-200 rounded-xl p-5">
        <pre id="preview-body"
             class="text-sm text-slate-700 whitespace-pre-wrap font-sans leading-relaxed"></pre>
      </div>
      <p class="text-xs text-slate-400 mt-3 italic">* Variabelen zijn vervangen door voorbeeldwaarden.</p>
    </div>
  </div>
</div>

<!-- Leaflet (voor admin-kaart) -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
(function () {
  const l = <?= json_encode($l) ?>;

  // ── Toegangscontrole ────────────────────────────────────────
  const user = JSON.parse(localStorage.getItem('dlpwc_user') || 'null');
  if (!user || (user.role !== 'admin' && user.role !== 'moderator')) {
    document.getElementById('access-denied').classList.remove('hidden');
    return;
  }
  document.getElementById('admin-panel').classList.remove('hidden');
  const isAdmin = user.role === 'admin';

  // ── CSRF token ophalen ──────────────────────────────────────
  let csrfToken = '';
  fetch('/api/get-csrf.php', { credentials: 'include' })
    .then(r => r.json())
    .then(d => { csrfToken = d.csrf_token || ''; });

  function csrfHeaders() {
    return { 'Content-Type': 'application/json', 'X-CSRF-Token': csrfToken };
  }

  // Verberg admin-only tabs voor moderators
  if (!isAdmin) {
    document.querySelectorAll('.tab-admin-only, .tab-admin-only-option').forEach(el => el.remove());
  }

  // ── Tab-navigatie ───────────────────────────────────────────
  function switchTab(key) {
    document.querySelectorAll('[id^="tab-"]').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));

    const target = document.getElementById('tab-' + key);
    if (target) target.classList.remove('hidden');
    document.querySelector(`[data-tab="${key}"]`)?.classList.add('active');

    onTabActivate(key);
  }

  document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => switchTab(btn.dataset.tab));
  });
  document.getElementById('mobile-tab-select')?.addEventListener('change', (e) => switchTab(e.target.value));

  // ── Lazy-load per tab ───────────────────────────────────────
  const loaded = new Set();

  switchTab('reviews');

  function onTabActivate(key) {
    if (loaded.has(key)) return;
    loaded.add(key);
    switch (key) {
      case 'reviews':      loadPending(); break;
      case 'recensies':    loadRecensies(); break;
      case 'media':        loadMedia(); break;
      case 'fotos':        loadFotos(); break;
      case 'geblokkeerd':  loadBlocked(); break;
      case 'toiletten':    loadToiletten(); break;
      case 'gebruikers':   loadGebruikers(); break;
      case 'nieuw-account':renderNieuwAccount(); break;
      case 'mailtemplates':loadMailTemplates(); break;
      case 'site':         loadSiteSettings(); break;
    }
  }

  // ── Tab: Reviews (pending) ──────────────────────────────────
  async function loadPending() {
    const el  = document.getElementById('pending-reviews');
    el.innerHTML = `<div class="text-slate-400 text-sm">${l.loading}</div>`;
    const res  = await fetch('/api/admin/get-pending-reviews.php', { credentials: 'include' });
    const data = await res.json();
    el.innerHTML = data.length === 0
      ? `<div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg p-4">${l.noPending}</div>`
      : data.map(r => reviewCard(r, true)).join('');
  }
  document.getElementById('refresh-pending').onclick = () => { loaded.delete('reviews'); loadPending(); };

  document.getElementById('load-approved-btn').onclick = async () => {
    const q   = document.getElementById('review-search').value.trim();
    const res = await fetch(`/api/admin/get-approved-reviews.php?q=${encodeURIComponent(q)}`, { credentials: 'include' });
    const data = await res.json();
    document.getElementById('approved-reviews').innerHTML = data.length === 0
      ? `<div class="text-slate-400 text-sm">${l.noApproved}</div>`
      : data.map(r => reviewCard(r, false)).join('');
  };
  document.getElementById('review-search').addEventListener('keydown', e => {
    if (e.key === 'Enter') document.getElementById('load-approved-btn').click();
  });

  function reviewCard(r, isPending) {
    const avg    = ((r.hygiene + r.crowd + r.location + r.facilities) / 4).toFixed(1);
    const author = r.user_name ?? r.username ?? r.guest_name ?? l.guest;
    let photos   = [];
    try { photos = JSON.parse(r.images_json || '[]'); } catch (_) {}

    const photosHtml = photos.length ? `
      <div class="flex gap-2 flex-wrap mb-3">
        ${photos.map(img => `
          <div style="width:60px;height:60px;border-radius:8px;overflow:hidden;
                      border:2px solid #e2e8f0;flex-shrink:0;">
            <img src="/uploads/reviews/${img}"
                 style="width:100%;height:100%;object-fit:cover;"
                 onerror="this.parentElement.style.display='none'">
          </div>`).join('')}
        <div class="text-xs text-amber-600 font-medium self-center">📷 ${photos.length}</div>
      </div>` : '';

    const actionHtml = isPending ? `
      <div class="flex flex-wrap gap-2 items-center mt-3">
        <button class="rv-action text-xs bg-green-500 text-white px-3 py-1.5 rounded-lg
                       hover:bg-green-600 transition font-medium flex-shrink-0"
                data-id="${r.id}" data-status="approved">${l.approve}</button>
        <button class="rv-action text-xs bg-amber-500 text-white px-3 py-1.5 rounded-lg
                       hover:bg-amber-600 transition font-medium flex-shrink-0"
                data-id="${r.id}" data-status="rejected">${l.reject}</button>
        <div class="flex items-center gap-1 flex-wrap">
          <select id="block-dur-${r.id}"
                  class="text-xs border border-red-300 rounded-lg px-2 py-1.5
                         focus:outline-none focus:border-red-500 bg-white text-slate-700">
            <option value="3days">${l.days3}</option>
            <option value="1week">${l.week1}</option>
            <option value="1month">${l.month1}</option>
            <option value="forever">${l.forever}</option>
          </select>
          <button class="rv-action text-xs bg-red-600 text-white px-3 py-1.5 rounded-lg
                         hover:bg-red-700 transition font-medium flex-shrink-0"
                  data-id="${r.id}" data-status="blocked">${l.blockUser}</button>
        </div>
      </div>` : `
      <div class="mt-2">
        <button class="rv-delete text-xs text-red-500 hover:text-red-700 hover:underline font-medium"
                data-id="${r.id}">🗑️ ${l.delete}</button>
      </div>`;

    return `
      <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4" id="rv-${r.id}">
        <div class="flex justify-between items-start mb-2 flex-wrap gap-2">
          <div>
            <span class="font-semibold text-sm text-[#003f8a]">${r.toilet_name ?? '—'}</span>
            <span class="text-slate-400 text-xs ml-2">· ${(r.created_at ?? '').slice(0,10)}</span>
          </div>
          <span class="text-xs px-2 py-0.5 rounded-full font-medium
            ${r.user_id ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-600'}">
            ${author}
          </span>
        </div>
        <div class="text-xs text-slate-500 mb-2 flex gap-3 flex-wrap">
          <span>⌀ ${avg}★</span>
          <span>🧹 ${r.hygiene}★</span><span>👥 ${r.crowd}★</span>
          <span>📍 ${r.location}★</span><span>🚿 ${r.facilities}★</span>
        </div>
        ${r.comment ? `<p class="text-sm text-slate-700 italic mb-2 bg-slate-50 rounded p-2">"${r.comment.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;')}"</p>` : ''}
        ${photosHtml}
        ${actionHtml}
      </div>`;
  }

  // Event delegation voor review-acties
  document.getElementById('pending-reviews').addEventListener('click', async (e) => {
    const btn = e.target.closest('.rv-action');
    if (!btn) return;
    const id     = parseInt(btn.dataset.id);
    const status = btn.dataset.status;
    const dur    = status === 'blocked' ? (document.getElementById('block-dur-' + id)?.value ?? '3days') : null;
    const res    = await fetch('/api/admin/update-review-status.php', {
      method: 'POST', headers: csrfHeaders(),
      body: JSON.stringify({ id, status, block_duration: dur }), credentials: 'include',
    });
    const result = await res.json();
    if (result.success) {
      document.getElementById('rv-' + id)?.remove();
      if (!document.querySelectorAll('[id^="rv-"]').length) {
        document.getElementById('pending-reviews').innerHTML =
          `<div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg p-4">${l.noPending}</div>`;
      }
      if (status === 'blocked') { loaded.delete('geblokkeerd'); }
    } else {
      alert(result.error ?? l.error);
    }
  });

  document.getElementById('approved-reviews').addEventListener('click', async (e) => {
    const btn = e.target.closest('.rv-delete');
    if (!btn) return;
    if (!confirm(l.confirmDelete)) return;
    const id  = parseInt(btn.dataset.id);
    const res = await fetch('/api/admin/delete-review.php', {
      method: 'POST', headers: csrfHeaders(),
      body: JSON.stringify({ id }), credentials: 'include',
    });
    const result = await res.json();
    if (result.success) document.getElementById('rv-' + id)?.remove();
    else alert(result.error ?? l.error);
  });

  // Delegate ook op recensies-tab
  document.getElementById('recensies-list').addEventListener('click', async (e) => {
    const btn = e.target.closest('.rv-delete');
    if (!btn) return;
    if (!confirm(l.confirmDelete)) return;
    const id  = parseInt(btn.dataset.id);
    const res = await fetch('/api/admin/delete-review.php', {
      method: 'POST', headers: csrfHeaders(),
      body: JSON.stringify({ id }), credentials: 'include',
    });
    const result = await res.json();
    if (result.success) document.getElementById('rv-' + id)?.remove();
    else alert(result.error ?? l.error);
  });

  // ── Tab: Alle recensies ─────────────────────────────────────
  async function loadRecensies() {
    const res  = await fetch('/api/admin/get-all-reviews.php', { credentials: 'include' });
    const data = await res.json();
    document.getElementById('recensies-count').textContent = `${data.length} recensies`;
    document.getElementById('recensies-list').innerHTML = data.length
      ? data.map(r => reviewCard(r, false)).join('')
      : `<p class="text-slate-400 text-sm">${l.noApproved}</p>`;
  }

  // ── Tab: Media ──────────────────────────────────────────────
  async function loadMedia() {
    const res  = await fetch('/api/admin/get-toilets.php', { credentials: 'include' });
    const data = await res.json();
    document.getElementById('media-list').innerHTML = (data.toilets ?? []).map(t => `
      <div class="bg-white rounded-xl border border-slate-200 p-3 shadow-sm text-center">
        ${t.editorial_photo
          ? `<img src="/uploads/editorial/${t.editorial_photo}" class="w-full h-28 object-cover rounded-lg mb-2" alt="">`
          : '<div class="w-full h-28 bg-slate-100 rounded-lg mb-2 flex items-center justify-center text-3xl">🚽</div>'}
        <div class="text-xs font-semibold text-slate-700 mb-2 truncate">${t.name}</div>
        <label class="cursor-pointer text-xs text-[#003f8a] hover:underline">
          📷 Upload
          <input type="file" accept="image/*" class="hidden" onchange="uploadEditorial(${t.id}, this)">
        </label>
        ${t.editorial_photo ? `<button onclick="deleteEditorial(${t.id})" class="text-xs text-red-500 hover:underline ml-2">🗑️</button>` : ''}
      </div>
    `).join('');
  }
  window.uploadEditorial = async (id, input) => {
    const fd = new FormData(); fd.append('photo', input.files[0]); fd.append('toilet_id', id);
    await fetch('/api/admin/set-editorial-photo.php', { method: 'POST', body: fd, credentials: 'include' });
    loaded.delete('media'); loadMedia();
  };
  window.deleteEditorial = async (id) => {
    await fetch('/api/admin/delete-photo.php', { method:'POST', headers:{'Content-Type':'application/json'},
      body: JSON.stringify({ toilet_id: id }), credentials: 'include' });
    loaded.delete('media'); loadMedia();
  };

  // ── Tab: Foto's ─────────────────────────────────────────────
  async function loadFotos() {
    const res  = await fetch('/api/admin/get-all-photos.php', { credentials: 'include' });
    const data = await res.json();
    document.getElementById('photos-count').textContent = `${data.photos?.length ?? 0} foto's`;
    document.getElementById('photos-grid').innerHTML = (data.photos ?? []).map(p => `
      <div class="relative group rounded-xl overflow-hidden border border-slate-200 shadow-sm" data-type="${p.type}">
        <img src="${p.url}" class="w-full h-28 object-cover" alt="">
        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center gap-2">
          <button onclick="deletePhoto('${p.filename}', '${p.type}')" class="text-white text-xs bg-red-600 px-2 py-1 rounded">🗑️</button>
        </div>
        <div class="px-2 py-1 text-xs text-slate-500 truncate">${p.filename}</div>
      </div>
    `).join('') || '<p class="text-slate-400 text-sm col-span-5">Geen foto\'s gevonden.</p>';
  }
  window.deletePhoto = async (filename, type) => {
    if (!confirm('Foto verwijderen?')) return;
    await fetch('/api/admin/delete-photo.php', { method:'POST', headers:{'Content-Type':'application/json'},
      body: JSON.stringify({ filename, type }), credentials: 'include' });
    loaded.delete('fotos'); loadFotos();
  };
  document.getElementById('refresh-photos').onclick = () => { loaded.delete('fotos'); loadFotos(); };

  // ── Tab: Geblokkeerd ────────────────────────────────────────
  async function loadBlocked() {
    const res  = await fetch('/api/admin/get-blocked.php', { credentials: 'include' });
    const data = await res.json();
    document.getElementById('blocked-list').innerHTML = (data.blocked ?? []).length === 0
      ? '<p class="text-slate-400 text-sm">Geen geblokkeerde gebruikers.</p>'
      : (data.blocked ?? []).map(b => `
        <div class="bg-white rounded-xl border border-slate-200 p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
          <div>
            <div class="font-semibold text-sm text-slate-700">${b.name ?? b.ip ?? 'Onbekend'}</div>
            <div class="text-xs text-slate-400">${b.type ?? ''} · t/m ${(b.blocked_until ?? '').slice(0,10) || '∞'}</div>
          </div>
          <button onclick="unblock(${b.id}, '${b.type}')" class="text-xs bg-green-600 text-white px-3 py-1.5 rounded-lg hover:opacity-90 flex-shrink-0">Deblokkeer</button>
        </div>
      `).join('');
  }
  document.getElementById('refresh-blocked').onclick = () => { loaded.delete('geblokkeerd'); loadBlocked(); };
  window.unblock = async (id, type) => {
    const payload = type === 'user'
      ? { type: 'user',  user_id:  id }
      : { type: 'guest', block_id: id };
    await fetch('/api/admin/unblock.php', { method:'POST', headers:{'Content-Type':'application/json'},
      body: JSON.stringify(payload), credentials: 'include' });
    loaded.delete('geblokkeerd'); loadBlocked();
  };

  // ── Tab: Toiletten ──────────────────────────────────────────
  let adminMap = null;
  let _toiletsCache = [];

  async function loadToiletten() {
    const res  = await fetch('/api/admin/get-toilets.php', { credentials: 'include' });
    const data = await res.json();
    _toiletsCache = data.toilets ?? [];
    renderToiletList(_toiletsCache);
  }

  function renderToiletList(toilets) {
    document.getElementById('toilet-list').innerHTML = toilets.length ? toilets.map(t => `
      <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex gap-0" id="tl-${t.id}">
        <div class="w-20 flex-shrink-0 bg-slate-100 flex items-center justify-center overflow-hidden">
          ${t.editorial_photo
            ? `<img src="/uploads/editorial/${t.editorial_photo}" class="w-full h-full object-cover" style="min-height:72px;" alt="">`
            : `<span class="text-3xl">🚽</span>`}
        </div>
        <div class="flex flex-1 flex-col sm:flex-row items-start sm:items-center justify-between gap-2 p-3 min-w-0">
          <div class="min-w-0 flex-1">
            <div class="font-semibold text-sm text-[#003f8a] truncate">${t.name}</div>
            <div class="text-xs text-slate-400 truncate">${t.area} · ${parseFloat(t.latitude).toFixed(4)}, ${parseFloat(t.longitude).toFixed(4)}</div>
            ${t.description ? `<div class="text-xs text-slate-500 mt-0.5 truncate">${t.description}</div>` : ''}
          </div>
          <div class="flex gap-2 flex-shrink-0">
            <button onclick="editToilet(${t.id})" class="text-xs bg-[#003f8a] text-white px-3 py-1.5 rounded-lg hover:opacity-90 whitespace-nowrap">✏️ Edit</button>
            <button onclick="deleteToilet(${t.id})" class="text-xs bg-red-500 text-white px-3 py-1.5 rounded-lg hover:opacity-90">🗑️</button>
          </div>
        </div>
      </div>
    `).join('') : '<p class="text-slate-400 text-sm">Geen toiletten gevonden.</p>';
  }

  window.editToilet = (id) => {
    const t = _toiletsCache.find(x => x.id === id || x.id === String(id));
    if (!t) return;

    const form = document.getElementById('add-toilet-form');
    form.classList.remove('hidden');
    document.getElementById('toilet-form-title').textContent = `✏️ ${t.name} bewerken`;
    document.getElementById('toilet-edit-id').value  = t.id;
    form.querySelector('[name="name"]').value         = t.name;
    form.querySelector('[name="area"]').value         = t.area;
    form.querySelector('[name="description"]').value  = t.description ?? '';
    document.getElementById('lat-input').value        = t.latitude;
    document.getElementById('lng-input').value        = t.longitude;

    if (!adminMap) {
      initAdminMap();
    } else {
      adminMap.setView([parseFloat(t.latitude), parseFloat(t.longitude)], 17);
    }

    form.scrollIntoView({ behavior: 'smooth', block: 'start' });
  };
  document.getElementById('toggle-add-form').onclick = () => {
    const form = document.getElementById('add-toilet-form');
    form.classList.toggle('hidden');
    if (!adminMap && !form.classList.contains('hidden')) initAdminMap();
  };
  document.getElementById('cancel-add').onclick = () => document.getElementById('add-toilet-form').classList.add('hidden');

  function initAdminMap() {
    const lat = parseFloat(document.getElementById('lat-input').value) || 48.8700;
    const lng = parseFloat(document.getElementById('lng-input').value) || 2.7800;
    adminMap = L.map('admin-map').setView([lat, lng], 17);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(adminMap);
    let marker;
    if (lat !== 48.8700 || lng !== 2.7800) {
      marker = L.marker([lat, lng]).addTo(adminMap);
    }
    adminMap.on('click', (e) => {
      if (marker) adminMap.removeLayer(marker);
      marker = L.marker(e.latlng).addTo(adminMap);
      document.getElementById('lat-input').value = e.latlng.lat.toFixed(6);
      document.getElementById('lng-input').value = e.latlng.lng.toFixed(6);
    });
  }

  document.getElementById('toilet-form').onsubmit = async (e) => {
    e.preventDefault();
    const fd  = new FormData(e.target);
    const data = Object.fromEntries(fd);
    const isEdit = !!data.edit_id;
    const url  = isEdit ? '/api/admin/update-toilet.php' : '/api/admin/add-toilet.php';
    const res  = await fetch(url, { method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify(data), credentials:'include' });
    const result = await res.json();
    const msg = document.getElementById('toilet-msg');
    if (result.success) {
      msg.textContent = isEdit ? '✅ Bijgewerkt' : '✅ Toegevoegd';
      msg.className = 'col-span-2 text-sm text-green-600';
      const photoFile = document.getElementById('toilet-photo')?.files[0];
      if (!isEdit && photoFile) {
        const pfd = new FormData(); pfd.append('photo', photoFile); pfd.append('toilet_id', result.id);
        await fetch('/api/admin/set-editorial-photo.php', { method: 'POST', body: pfd, credentials: 'include' });
      }
      e.target.reset();
      document.getElementById('toilet-edit-id').value = '';
      document.getElementById('toilet-form-title').textContent = <?= json_encode($l['formTitle']) ?>;
      loaded.delete('toiletten'); loadToiletten();
    } else {
      msg.textContent = '⚠️ ' + (result.error ?? 'Fout');
      msg.className = 'col-span-2 text-sm text-red-600';
    }
  };

  window.deleteToilet = async (id) => {
    if (!confirm('Toilet verwijderen?')) return;
    await fetch('/api/admin/delete-toilet.php', { method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({ id }), credentials:'include' });
    loaded.delete('toiletten'); loadToiletten();
  };

  // ── Tab: Gebruikers ─────────────────────────────────────────
  async function loadGebruikers() {
    const res  = await fetch('/api/admin/get-users.php', { credentials: 'include' });
    const data = await res.json();
    document.getElementById('users-list').innerHTML = (data.users ?? []).map(u => `
      <div class="bg-white rounded-xl border border-slate-200 p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3" id="user-${u.id}">
        <div class="flex items-center gap-3 min-w-0">
          <img src="${u.avatar_url ? `/uploads/avatars/${u.avatar_url}` : `https://ui-avatars.com/api/?name=${encodeURIComponent(u.name)}&background=3d1f8c&color=fff&size=40`}"
               class="w-10 h-10 rounded-full object-cover flex-shrink-0" alt="">
          <div class="min-w-0">
            <div class="font-semibold text-sm text-[#003f8a] truncate">${u.name} <span class="text-xs text-slate-400">@${u.username}</span></div>
            <div class="text-xs text-slate-400 truncate">${u.email} · ${u.role}</div>
          </div>
        </div>
        <div class="flex gap-2 flex-shrink-0">
          <select onchange="updateUserRole(${u.id}, this.value)" class="text-xs border rounded px-2 py-1.5 focus:outline-none">
            <option value="user" ${u.role==='user'?'selected':''}>user</option>
            <option value="moderator" ${u.role==='moderator'?'selected':''}>moderator</option>
            <option value="admin" ${u.role==='admin'?'selected':''}>admin</option>
          </select>
          <button onclick="deleteUser(${u.id})" class="text-xs bg-red-500 text-white px-2 py-1.5 rounded hover:opacity-90">🗑️</button>
        </div>
      </div>
    `).join('') || '<p class="text-slate-400 text-sm">Geen gebruikers gevonden.</p>';
  }
  window.updateUserRole = async (id, role) => {
    await fetch('/api/admin/update-user.php', { method:'POST', headers:{'Content-Type':'application/json'},
      body: JSON.stringify({ id, role }), credentials:'include' });
  };
  window.deleteUser = async (id) => {
    if (!confirm('Gebruiker verwijderen?')) return;
    await fetch('/api/admin/delete-user.php', { method:'POST', headers:{'Content-Type':'application/json'},
      body: JSON.stringify({ id }), credentials:'include' });
    document.getElementById('user-' + id)?.remove();
  };

  // ── Tab: Nieuw account ──────────────────────────────────────
  function renderNieuwAccount() {
    document.getElementById('create-user-content').innerHTML = `
      <form id="create-user-form" class="bg-white rounded-xl border border-slate-200 p-5 max-w-sm space-y-3">
        <div><label class="text-xs font-semibold text-slate-600">Naam *</label>
          <input name="name" required class="w-full border rounded-lg px-3 py-2 text-sm mt-1 focus:outline-none focus:border-[#003f8a]"></div>
        <div><label class="text-xs font-semibold text-slate-600">Gebruikersnaam *</label>
          <input name="username" required class="w-full border rounded-lg px-3 py-2 text-sm mt-1 focus:outline-none focus:border-[#003f8a]"></div>
        <div><label class="text-xs font-semibold text-slate-600">E-mail *</label>
          <input name="email" type="email" required class="w-full border rounded-lg px-3 py-2 text-sm mt-1 focus:outline-none focus:border-[#003f8a]"></div>
        <div><label class="text-xs font-semibold text-slate-600">Wachtwoord *</label>
          <input name="password" type="password" required minlength="8" class="w-full border rounded-lg px-3 py-2 text-sm mt-1 focus:outline-none focus:border-[#003f8a]"></div>
        <div><label class="text-xs font-semibold text-slate-600">Rol</label>
          <select name="role" class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
            <option value="user">user</option><option value="moderator">moderator</option><option value="admin">admin</option>
          </select></div>
        <div id="create-user-msg" class="hidden text-sm rounded-lg px-3 py-2"></div>
        <button type="submit" class="bg-[#003f8a] text-white px-5 py-2 rounded-lg text-sm font-semibold hover:opacity-90">Account aanmaken</button>
      </form>`;

    document.getElementById('create-user-form').onsubmit = async (e) => {
      e.preventDefault();
      const data = Object.fromEntries(new FormData(e.target));
      const res  = await fetch('/api/admin/create-user.php', { method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify(data), credentials:'include' });
      const result = await res.json();
      const msg = document.getElementById('create-user-msg');
      msg.textContent = result.success ? '✅ Account aangemaakt' : '⚠️ ' + (result.error ?? 'Fout');
      msg.className = result.success
        ? 'text-sm rounded-lg px-3 py-2 bg-green-50 text-green-700 border border-green-200'
        : 'text-sm rounded-lg px-3 py-2 bg-red-50 text-red-700 border border-red-200';
      if (result.success) e.target.reset();
    };
  }

  // ── Tab: Site-instellingen ──────────────────────────────────
  async function loadSiteSettings() {
    // Brevo e-mail instellingen
    const smtpRes  = await fetch('/api/admin/get-smtp-settings.php', { credentials: 'include' });
    const smtp     = smtpRes.ok ? await smtpRes.json() : {};
    document.getElementById('smtp-settings-content').innerHTML = `
      <div class="bg-white rounded-xl border border-slate-200 p-5 mb-6">
        <h3 class="font-semibold text-sm text-[#003f8a] mb-1">📧 E-mail via Brevo</h3>
        <p class="text-xs text-slate-500 mb-4">Transactionele e-mails worden verstuurd via de Brevo API.</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div class="sm:col-span-2">
            <label class="text-xs font-semibold text-slate-600">Brevo API-sleutel ${smtp.has_api_key ? '(ingesteld — leeg laten = niet wijzigen)' : ''}</label>
            <input id="brevo-api-key" type="password" placeholder="${smtp.has_api_key ? '••••••••' : 'xkeysib-...'}"
                   class="w-full border rounded-lg px-3 py-2 text-sm mt-1 focus:outline-none focus:border-[#003f8a] font-mono">
          </div>
          <div>
            <label class="text-xs font-semibold text-slate-600">Naam afzender</label>
            <input id="smtp-from-name" value="${smtp.smtp_from_name ?? 'DLPWC'}"
                   class="w-full border rounded-lg px-3 py-2 text-sm mt-1 focus:outline-none focus:border-[#003f8a]">
          </div>
          <div>
            <label class="text-xs font-semibold text-slate-600">E-mail afzender</label>
            <input id="smtp-from-email" type="email" value="${smtp.smtp_from_email ?? ''}"
                   class="w-full border rounded-lg px-3 py-2 text-sm mt-1 focus:outline-none focus:border-[#003f8a]">
          </div>
          <div class="sm:col-span-2">
            <label class="text-xs font-semibold text-slate-600">Admin notificatie-e-mail</label>
            <input id="admin-notification-email" type="email" value="${smtp.admin_notification_email ?? 'info@dlpwc.com'}"
                   class="w-full border rounded-lg px-3 py-2 text-sm mt-1 focus:outline-none focus:border-[#003f8a]">
            <p class="text-xs text-slate-400 mt-1">Adres dat notificaties ontvangt (nieuwe gebruikers, nieuwe reviews, …)</p>
          </div>
        </div>
        <div id="smtp-msg" class="hidden text-sm rounded-lg px-3 py-2 mt-3"></div>
        <button id="save-smtp" class="mt-4 bg-[#003f8a] text-white px-5 py-2 rounded-lg text-sm font-semibold hover:opacity-90">
          💾 Opslaan
        </button>
        <div class="mt-4 pt-4 border-t border-slate-100">
          <p class="text-xs font-semibold text-slate-600 mb-2">🧪 Testmail versturen</p>
          <div class="flex gap-2">
            <input id="smtp-test-email" type="email" placeholder="testadres@voorbeeld.nl"
                   class="flex-1 border rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#003f8a]">
            <button id="send-test-smtp" class="bg-slate-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:opacity-90 whitespace-nowrap">
              📨 Test
            </button>
          </div>
          <div id="smtp-test-msg" class="hidden text-sm rounded-lg px-3 py-2 mt-2"></div>
        </div>
      </div>`;

    document.getElementById('save-smtp').onclick = async () => {
      const payload = {
        brevo_api_key:             document.getElementById('brevo-api-key').value,
        smtp_from_name:            document.getElementById('smtp-from-name').value.trim(),
        smtp_from_email:           document.getElementById('smtp-from-email').value.trim(),
        admin_notification_email:  document.getElementById('admin-notification-email').value.trim(),
      };
      const r = await fetch('/api/admin/save-smtp-settings.php', {
        method: 'POST', headers: csrfHeaders(),
        body: JSON.stringify(payload), credentials: 'include',
      });
      const result = await r.json();
      const msg = document.getElementById('smtp-msg');
      msg.textContent = result.success ? '✅ Opgeslagen' : '⚠️ ' + (result.error ?? 'Fout');
      msg.className = result.success
        ? 'text-sm rounded-lg px-3 py-2 bg-green-50 text-green-700 border border-green-200'
        : 'text-sm rounded-lg px-3 py-2 bg-red-50 text-red-700 border border-red-200';
      msg.classList.remove('hidden');
      if (result.success) document.getElementById('brevo-api-key').value = '';
    };

    document.getElementById('send-test-smtp').onclick = async () => {
      const email = document.getElementById('smtp-test-email').value.trim();
      const msg   = document.getElementById('smtp-test-msg');
      if (!email) {
        msg.textContent = '⚠️ Voer een e-mailadres in';
        msg.className = 'text-sm rounded-lg px-3 py-2 mt-2 bg-red-50 text-red-700 border border-red-200';
        msg.classList.remove('hidden'); return;
      }
      msg.textContent = '⏳ Versturen…'; msg.className = 'text-sm rounded-lg px-3 py-2 mt-2 bg-slate-50 text-slate-600'; msg.classList.remove('hidden');
      const r = await fetch('/api/admin/test-smtp.php', { method:'POST', headers:csrfHeaders(), body:JSON.stringify({email}), credentials:'include' });
      const result = await r.json();
      msg.textContent = result.success ? '✅ Testmail verzonden!' : '⚠️ ' + (result.error ?? 'Fout');
      msg.className = result.success
        ? 'text-sm rounded-lg px-3 py-2 mt-2 bg-green-50 text-green-700 border border-green-200'
        : 'text-sm rounded-lg px-3 py-2 mt-2 bg-red-50 text-red-700 border border-red-200';
    };

    // reCAPTCHA
    const settingsRes  = await fetch('/api/admin/admin-get-settings.php', { credentials: 'include' });
    const settingsData = settingsRes.ok ? await settingsRes.json() : {};
    const rc = settingsData.recaptcha || {};
    document.getElementById('recaptcha-settings-content').innerHTML = `
      <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="font-semibold text-sm text-[#003f8a] mb-4">🤖 Google reCAPTCHA v2</h3>
        <p class="text-xs text-slate-500 mb-4">Laat de velden leeg om reCAPTCHA uit te schakelen.</p>
        <div class="grid grid-cols-1 gap-3">
          <div>
            <label class="text-xs font-semibold text-slate-600">Site Key (publiek)</label>
            <input id="rc-site-key" value="${rc.recaptcha_site_key ?? ''}" placeholder="6Le..."
                   class="w-full border rounded-lg px-3 py-2 text-sm mt-1 focus:outline-none focus:border-[#003f8a] font-mono">
          </div>
          <div>
            <label class="text-xs font-semibold text-slate-600">Secret Key (privé)</label>
            <input id="rc-secret-key" type="password" value="${rc.recaptcha_secret_key ?? ''}" placeholder="6Le..."
                   class="w-full border rounded-lg px-3 py-2 text-sm mt-1 focus:outline-none focus:border-[#003f8a] font-mono">
          </div>
        </div>
        <div id="rc-msg" class="hidden text-sm rounded-lg px-3 py-2 mt-3"></div>
        <button id="save-recaptcha" class="mt-4 bg-[#003f8a] text-white px-5 py-2 rounded-lg text-sm font-semibold hover:opacity-90">
          💾 reCAPTCHA opslaan
        </button>
      </div>`;

    document.getElementById('save-recaptcha').onclick = async () => {
      const payload = { recaptcha: {
        recaptcha_site_key:   document.getElementById('rc-site-key').value.trim(),
        recaptcha_secret_key: document.getElementById('rc-secret-key').value.trim(),
      }};
      const r = await fetch('/api/admin/admin-save-settings.php', {
        method: 'POST', headers: csrfHeaders(),
        body: JSON.stringify(payload), credentials: 'include',
      });
      const result = await r.json();
      const msg = document.getElementById('rc-msg');
      msg.textContent = result.success ? '✅ reCAPTCHA opgeslagen' : '⚠️ ' + (result.error ?? 'Fout');
      msg.className = result.success
        ? 'text-sm rounded-lg px-3 py-2 bg-green-50 text-green-700 border border-green-200'
        : 'text-sm rounded-lg px-3 py-2 bg-red-50 text-red-700 border border-red-200';
      msg.classList.remove('hidden');
    };

    // Kleuren
    const colorMap = {};
    (settingsData.colors || []).forEach(c => { colorMap[c.setting_key] = c.setting_value; });
    const primary = colorMap.color_primary ?? '#3d1f8c';
    const accent  = colorMap.color_accent  ?? '#f5a800';

    document.getElementById('site-settings-content').innerHTML = `
      <div class="bg-white rounded-xl border border-slate-200 p-5 max-w-sm space-y-4">
        <h3 class="font-semibold text-sm text-[#003f8a] mb-2">🎨 Kleuren</h3>
        <div>
          <label class="text-xs font-semibold text-slate-600">Primaire kleur</label>
          <div class="flex gap-2 mt-1">
            <input type="color" id="color-primary" value="${primary}" class="w-10 h-10 rounded cursor-pointer">
            <input type="text" id="color-primary-text" value="${primary}"
                   class="flex-1 border rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#003f8a]">
          </div>
        </div>
        <div>
          <label class="text-xs font-semibold text-slate-600">Accentkleur</label>
          <div class="flex gap-2 mt-1">
            <input type="color" id="color-accent" value="${accent}" class="w-10 h-10 rounded cursor-pointer">
            <input type="text" id="color-accent-text" value="${accent}"
                   class="flex-1 border rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#003f8a]">
          </div>
        </div>
        <div id="settings-msg" class="hidden text-sm rounded-lg px-3 py-2"></div>
        <button id="save-settings" class="bg-[#003f8a] text-white px-5 py-2 rounded-lg text-sm font-semibold hover:opacity-90">💾 Opslaan</button>
      </div>`;

    document.getElementById('color-primary').oninput = e => { document.getElementById('color-primary-text').value = e.target.value; };
    document.getElementById('color-accent').oninput  = e => { document.getElementById('color-accent-text').value  = e.target.value; };

    document.getElementById('save-settings').onclick = async () => {
      const payload = { colors: {
        color_primary: document.getElementById('color-primary-text').value,
        color_accent:  document.getElementById('color-accent-text').value,
      }};
      const r = await fetch('/api/admin/admin-save-settings.php', {
        method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify(payload), credentials:'include',
      });
      const result = await r.json();
      const msg = document.getElementById('settings-msg');
      msg.textContent = result.success ? '✅ Opgeslagen' : '⚠️ ' + (result.error ?? 'Fout');
      msg.className = result.success
        ? 'text-sm rounded-lg px-3 py-2 bg-green-50 text-green-700 border border-green-200'
        : 'text-sm rounded-lg px-3 py-2 bg-red-50 text-red-700 border border-red-200';
      msg.classList.remove('hidden');
    };
  }

  // ── Tab: Mailtemplates ──────────────────────────────────────
  const MAIL_TEMPLATE_LABELS = {
    new_user_admin:       'Nieuwe gebruiker (→ admin)',
    registration_confirm: 'Bevestiging registratie (→ gebruiker)',
    new_review_admin:     'Nieuwe review (→ admin)',
    review_pending:       'Review in behandeling (→ gebruiker)',
    review_approved:      'Review geplaatst (→ gebruiker)',
  };
  const MAIL_VARS = {
    new_user_admin:       '{{name}}, {{email}}, {{username}}',
    registration_confirm: '{{name}}, {{username}}',
    new_review_admin:     '{{toilet}}, {{reviewer}}, {{score}}',
    review_pending:       '{{name}}, {{toilet}}',
    review_approved:      '{{name}}, {{toilet}}, {{score}}',
  };
  const MAIL_SAMPLE_DATA = {
    new_user_admin:       { name: 'Jan de Vries', email: 'jan@voorbeeld.nl', username: 'jandevries' },
    registration_confirm: { name: 'Jan de Vries', username: 'jandevries' },
    new_review_admin:     { toilet: 'WC Hoofdingang Park', reviewer: 'jandevries', score: '4.2' },
    review_pending:       { name: 'Jan de Vries', toilet: 'WC Hoofdingang Park' },
    review_approved:      { name: 'Jan de Vries', toilet: 'WC Hoofdingang Park', score: '4.2' },
  };
  const FLAG_OF = { nl: '🇳🇱', en: '🇬🇧', fr: '🇫🇷' };

  // ── Mail Preview Modal ──────────────────────────────────────
  const previewModal   = document.getElementById('mail-preview-modal');
  const closePreviewBtn = document.getElementById('close-preview-modal');

  function openMailPreview(key, lang, subject, body) {
    const sample = MAIL_SAMPLE_DATA[key] ?? {};
    function replaceSample(str) {
      return str.replace(/\{\{(\w+)\}\}/g, (_, v) => sample[v] ?? `{{${v}}}`);
    }
    document.getElementById('preview-flag').textContent            = FLAG_OF[lang] ?? '';
    document.getElementById('preview-template-label').textContent  = `${MAIL_TEMPLATE_LABELS[key]} — ${lang.toUpperCase()}`;
    document.getElementById('preview-subject').textContent         = replaceSample(subject) || '(geen onderwerp)';
    document.getElementById('preview-body').textContent            = replaceSample(body)    || '(geen inhoud)';
    previewModal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
  }

  function closeMailPreview() {
    previewModal.style.display = 'none';
    document.body.style.overflow = '';
  }

  closePreviewBtn.addEventListener('click', closeMailPreview);
  previewModal.addEventListener('click', (e) => {
    if (e.target === previewModal) closeMailPreview();
  });
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && previewModal.style.display === 'flex') closeMailPreview();
  });

  async function loadMailTemplates() {
    const el = document.getElementById('mailtemplates-content');
    el.innerHTML = '<div class="text-slate-400 text-sm">Laden...</div>';
    const res  = await fetch('/api/admin/get-mail-templates.php', { credentials: 'include' });
    const data = await res.json();
    if (data.error) { el.innerHTML = `<p class="text-red-500 text-sm">⚠️ ${data.error}</p>`; return; }

    const keys = Object.keys(MAIL_TEMPLATE_LABELS);
    const langs = ['nl', 'en', 'fr'];

    el.innerHTML = `
      <div class="flex flex-col gap-6">
        ${keys.map(key => `
          <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <div class="font-semibold text-sm text-[#003f8a] mb-1">${MAIL_TEMPLATE_LABELS[key]}</div>
            <div class="text-xs text-slate-400 mb-3">Beschikbare variabelen: <code class="bg-slate-100 px-1 rounded">${MAIL_VARS[key]}</code></div>
            <div class="flex flex-col gap-4">
              ${langs.map(lang => {
                const tpl = (data[key] && data[key][lang]) || { subject: '', body: '' };
                return `
                  <div>
                    <div class="flex items-center justify-between mb-1">
                      <div class="text-xs font-semibold text-slate-500">${FLAG_OF[lang]} ${lang.toUpperCase()}</div>
                      <button class="tpl-preview-btn flex items-center gap-1 text-xs bg-slate-100 text-slate-600
                                     px-2.5 py-1 rounded-lg hover:bg-[#003f8a] hover:text-white transition font-medium"
                              data-key="${key}" data-lang="${lang}"
                              title="Preview van deze mailtemplate bekijken">
                        👁️ Preview
                      </button>
                    </div>
                    <input class="tpl-subject w-full border rounded-lg px-3 py-1.5 text-sm mb-1 focus:outline-none focus:border-[#003f8a]"
                           data-key="${key}" data-lang="${lang}"
                           placeholder="Onderwerp" value="${(tpl.subject||'').replace(/"/g,'&quot;')}">
                    <textarea class="tpl-body w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#003f8a] font-mono"
                              data-key="${key}" data-lang="${lang}"
                              rows="5" placeholder="Inhoud van de e-mail...">${tpl.body||''}</textarea>
                  </div>`;
              }).join('')}
            </div>
            <div class="mt-3 flex items-center gap-3">
              <button class="tpl-save-btn bg-[#003f8a] text-white text-xs px-4 py-2 rounded-lg hover:opacity-90 font-semibold"
                      data-key="${key}">💾 Opslaan</button>
              <span class="tpl-msg-${key} text-xs text-green-600 hidden">✅ Opgeslagen</span>
            </div>
          </div>`).join('')}
      </div>`;

    // Preview-knop event delegation
    el.addEventListener('click', (e) => {
      const btn = e.target.closest('.tpl-preview-btn');
      if (!btn) return;
      const key  = btn.dataset.key;
      const lang = btn.dataset.lang;
      const subject = el.querySelector(`.tpl-subject[data-key="${key}"][data-lang="${lang}"]`).value;
      const body    = el.querySelector(`.tpl-body[data-key="${key}"][data-lang="${lang}"]`).value;
      openMailPreview(key, lang, subject, body);
    });

    el.querySelectorAll('.tpl-save-btn').forEach(btn => {
      btn.addEventListener('click', async () => {
        const key     = btn.dataset.key;
        const payload = { [key]: {} };
        langs.forEach(lang => {
          const subject = el.querySelector(`.tpl-subject[data-key="${key}"][data-lang="${lang}"]`).value;
          const body    = el.querySelector(`.tpl-body[data-key="${key}"][data-lang="${lang}"]`).value;
          payload[key][lang] = { subject, body };
        });
        const r = await fetch('/api/admin/save-mail-templates.php', {
          method:'POST', headers:{'Content-Type':'application/json'},
          body: JSON.stringify(payload), credentials:'include',
        });
        const result = await r.json();
        const msgEl = el.querySelector(`.tpl-msg-${key}`);
        msgEl.textContent = result.success ? '✅ Opgeslagen' : '⚠️ ' + (result.error ?? 'Fout');
        msgEl.className = result.success
          ? 'tpl-msg-' + key + ' text-xs text-green-600'
          : 'tpl-msg-' + key + ' text-xs text-red-500';
        msgEl.classList.remove('hidden');
        setTimeout(() => msgEl.classList.add('hidden'), 3000);
      });
    });
  }

})();
</script>

<?php include __DIR__ . '/../components/base-close.php'; ?>
