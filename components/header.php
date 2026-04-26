<?php
/**
 * SiteHeader component — equivalent van SiteHeader.astro
 * Taalwisselaar + navigatie + gebruikerszone
 */
?>
<header class="bg-[#3d1f8c] text-white shadow-md" style="height:56px; position:relative; z-index:1000;">
  <div class="max-w-full h-full flex items-center justify-between px-5">

    <!-- Logo -->
    <a href="/" class="flex items-center gap-2 group">
      <img src="/dlpwc.png" alt="DLP WC" class="group-hover:opacity-90 transition" style="height:36px;width:auto;">
      <span id="nav-tagline" class="hidden sm:block text-xs text-white/50 ml-1"></span>
    </a>

    <!-- Navigatie -->
    <nav class="flex items-center gap-2 text-sm">
      <a href="/" id="nav-map-link" class="hover:text-[#f5a800] transition hidden sm:block"></a>

      <!-- Taalwisselaar -->
      <div class="relative" id="lang-picker">
        <button id="lang-btn" class="flex items-center gap-1.5 text-sm font-medium text-white/80
                   hover:text-white transition px-2 py-1 rounded-lg hover:bg-white/10">
          <span id="lang-flag" class="text-base">🇬🇧</span>
          <span id="lang-label-btn" class="hidden sm:inline text-xs">English</span>
          <svg class="w-3 h-3 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
          </svg>
        </button>
        <div id="lang-dropdown"
             class="hidden absolute right-0 mt-1 bg-white border border-slate-200
                    rounded-xl shadow-lg py-1 min-w-[150px]" style="z-index:1100;">
          <button data-lang="en" class="lang-option w-full flex items-center gap-2 px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 transition text-left">
            🇬🇧 <span>English</span>
          </button>
          <button data-lang="nl" class="lang-option w-full flex items-center gap-2 px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 transition text-left">
            🇳🇱 <span>Nederlands</span>
          </button>
          <button data-lang="fr" class="lang-option w-full flex items-center gap-2 px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 transition text-left">
            🇫🇷 <span>Français</span>
          </button>
        </div>
      </div>

      <!-- Gebruikerszone (login/profiel/admin — gevuld via JS) -->
      <div id="nav-user-area" class="flex items-center gap-2"></div>
    </nav>
  </div>
</header>

<!-- Groene accentlijn -->
<div style="height:3px; background:#00915a; position:relative; z-index:999;"></div>
