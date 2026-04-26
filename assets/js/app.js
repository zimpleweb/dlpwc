/**
 * app.js — Gedeeld script voor alle pagina's.
 * Equivalent van de Base.astro script-tag + SiteHeader JS.
 */
(function () {

  // ── Taalwisselaar ──────────────────────────────────────────
  const langBtn      = document.getElementById('lang-btn');
  const langDropdown = document.getElementById('lang-dropdown');

  langBtn?.addEventListener('click', (e) => {
    e.stopPropagation();
    langDropdown.classList.toggle('hidden');
  });
  document.addEventListener('click', () => langDropdown?.classList.add('hidden'));

  document.querySelectorAll('.lang-option').forEach(btn => {
    btn.addEventListener('click', () => {
      const lang = btn.dataset.lang;
      document.cookie = `lang=${lang};path=/;max-age=31536000;SameSite=Lax`;
      location.reload();
    });
  });

  // Taalindicator bijwerken
  const langMap = { en: { flag: '🇬🇧', label: 'GB' }, nl: { flag: '🇳🇱', label: 'NL' }, fr: { flag: '🇫🇷', label: 'FR' } };
  const curLang = document.cookie.match(/(?:^|; )lang=([^;]+)/)?.[1] ?? 'en';
  const info    = langMap[curLang] ?? langMap.en;
  if (document.getElementById('lang-flag'))       document.getElementById('lang-flag').textContent       = info.flag;
  if (document.getElementById('lang-label-btn'))  document.getElementById('lang-label-btn').textContent  = info.label;

  // ── Nav: gebruikerszone ────────────────────────────────────
  const navUser = document.getElementById('nav-user-area');
  if (navUser) {
    const user = JSON.parse(localStorage.getItem('dlpwc_user') || 'null');
    if (user) {
      navUser.innerHTML = `
        <a href="/profiel" class="text-xs font-medium text-white/80 hover:text-white transition">${user.name ?? user.email ?? 'Profiel'}</a>
        ${user.role === 'admin' || user.role === 'moderator'
          ? '<a href="/admin/" class="text-xs font-medium text-[#f5a800] hover:opacity-80 transition">⚙️ Admin</a>'
          : ''}
        <button id="logout-btn" class="text-xs font-medium text-white/60 hover:text-white transition">Uitloggen</button>`;

      document.getElementById('logout-btn').addEventListener('click', async () => {
        await fetch('/api/logout.php', { credentials: 'include' });
        localStorage.removeItem('dlpwc_user');
        location.href = '/';
      });
    } else {
      navUser.innerHTML = `
        <a href="/login" class="text-xs font-medium text-white/80 hover:text-white transition">Inloggen</a>
        <a href="/register" class="text-xs font-medium bg-[#f5a800] text-[#3d1f8c] px-2 py-1 rounded-lg font-bold hover:opacity-90 transition">Account</a>`;
    }
  }

  // ── Nav: tagline & map-link (i18n) ─────────────────────────
  const i18n = {
    en: { tagline: 'Toilet reviews · Disneyland Paris', mapLink: '🗺️ Map' },
    nl: { tagline: 'Toiletreviews · Disneyland Paris',  mapLink: '🗺️ Kaart' },
    fr: { tagline: 'Avis toilettes · Disneyland Paris', mapLink: '🗺️ Carte' },
  };
  const t = i18n[curLang] ?? i18n.en;
  if (document.getElementById('nav-tagline'))   document.getElementById('nav-tagline').textContent  = t.tagline;
  if (document.getElementById('nav-map-link'))  document.getElementById('nav-map-link').textContent = t.mapLink;

  // ── Magic cursor-sparkle ───────────────────────────────────
  const canvas = document.getElementById('magic-canvas');
  if (!canvas) return;
  const ctx = canvas.getContext('2d');
  let particles = [];

  function resize() {
    canvas.width  = window.innerWidth;
    canvas.height = window.innerHeight;
  }
  window.addEventListener('resize', resize);
  resize();

  document.addEventListener('mousemove', (e) => {
    for (let i = 0; i < 2; i++) {
      particles.push({
        x: e.clientX, y: e.clientY,
        vx: (Math.random() - 0.5) * 2,
        vy: (Math.random() - 0.5) * 2 - 1,
        life: 1,
        size: Math.random() * 3 + 1,
        color: Math.random() > 0.5 ? '#f5a800' : '#ffffff',
      });
    }
  });

  function animate() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    particles = particles.filter(p => p.life > 0);
    particles.forEach(p => {
      p.x += p.vx; p.y += p.vy; p.life -= 0.025;
      ctx.globalAlpha = p.life;
      ctx.fillStyle   = p.color;
      ctx.beginPath();
      ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
      ctx.fill();
    });
    ctx.globalAlpha = 1;
    requestAnimationFrame(animate);
  }
  animate();

})();
