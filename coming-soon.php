<?php
/**
 * Coming-soon pagina — standalone (geen Base layout), eigen CSS.
 * Wachtwoord-unlock via /api/coming-soon-check.php (of inline validatie).
 */
$lang = isset($_COOKIE['lang']) && in_array($_COOKIE['lang'], ['en','nl','fr']) ? $_COOKIE['lang'] : 'nl';

$labels = [
    'en' => ['title' => 'Coming Soon', 'remember' => 'Remember me 3 days', 'btn' => '✨ The toilet awaits', 'error' => 'Incorrect password', 'welcome' => 'Welcome! ✨'],
    'nl' => ['title' => 'Bienvenue',   'remember' => 'Onthoud mij 3 dagen', 'btn' => '✨ De WC wacht op je',  'error' => 'Onjuist wachtwoord',   'welcome' => 'Welkom! ✨'],
    'fr' => ['title' => 'Bienvenue',   'remember' => 'Se souvenir 3 jours', 'btn' => '✨ Les WC vous attendent', 'error' => 'Mot de passe incorrect', 'welcome' => 'Bienvenue ! ✨'],
];
$l = $labels[$lang] ?? $labels['nl'];
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DLP WC — Coming Soon</title>
  <link rel="stylesheet" href="/assets/css/coming-soon.css">
</head>
<body>

  <!-- Taalbar -->
  <div class="lang-bar">
    <button class="lang-btn<?= $lang === 'nl' ? ' active' : '' ?>" data-lang="nl">🇳🇱 <span>NL</span></button>
    <button class="lang-btn<?= $lang === 'en' ? ' active' : '' ?>" data-lang="en">🇬🇧 <span>EN</span></button>
    <button class="lang-btn<?= $lang === 'fr' ? ' active' : '' ?>" data-lang="fr">🇫🇷 <span>FR</span></button>
  </div>

  <!-- Sterren-achtergrond -->
  <div class="stars" id="stars"></div>

  <!-- Kasteel SVG -->
  <div class="castle-wrap">
    <svg viewBox="0 0 600 300" fill="white" xmlns="http://www.w3.org/2000/svg">
      <rect x="240" y="180" width="120" height="120"/>
      <rect x="270" y="140" width="60"  height="50"/>
      <rect x="285" y="80"  width="30"  height="70"/>
      <polygon points="300,20 285,80 315,80"/>
      <rect x="220" y="200" width="40"  height="100"/>
      <rect x="340" y="200" width="40"  height="100"/>
      <rect x="180" y="220" width="50"  height="80"/>
      <rect x="370" y="220" width="50"  height="80"/>
      <rect x="240" y="175" width="15"  height="15"/>
      <rect x="262" y="175" width="15"  height="15"/>
      <rect x="323" y="175" width="15"  height="15"/>
      <rect x="345" y="175" width="15"  height="15"/>
      <rect x="220" y="195" width="12"  height="12"/>
      <rect x="248" y="195" width="12"  height="12"/>
      <rect x="340" y="195" width="12"  height="12"/>
      <rect x="368" y="195" width="12"  height="12"/>
    </svg>
  </div>

  <!-- Kaart -->
  <div class="card" id="card">
    <div class="logo-wrap">
      <img src="/dlpwc.png" alt="DLP WC">
    </div>
    <h1 id="cs-title"><?= htmlspecialchars($l['title']) ?></h1>
    <p class="subtitle" id="cs-subtitle">✨ Site in aanbouw ✨</p>

    <form id="unlock-form" autocomplete="off">
      <div class="input-wrap">
        <span class="input-icon">🔑</span>
        <input type="password" id="password" autofocus>
      </div>
      <label class="remember">
        <input type="checkbox" id="remember">
        <span id="cs-remember"><?= htmlspecialchars($l['remember']) ?></span>
      </label>
      <button type="submit" class="btn" id="cs-btn"><?= htmlspecialchars($l['btn']) ?></button>
      <p class="error-msg" id="error-msg"><?= htmlspecialchars($l['error']) ?></p>
    </form>
    <p class="footer-text" id="cs-footer">DLP WC · Onafhankelijke toiletreviews</p>
  </div>

  <!-- Success overlay -->
  <div class="success-overlay" id="success-overlay">
    <img src="/dlpwc.png" alt="DLP WC" style="height:72px;filter:drop-shadow(0 0 24px rgba(245,168,0,0.8));">
    <p id="cs-welcome"><?= htmlspecialchars($l['welcome']) ?></p>
  </div>

<script>
(function () {
  // ── Sterren genereren ──────────────────────────────────────
  const starsEl = document.getElementById('stars');
  for (let i = 0; i < 120; i++) {
    const s = document.createElement('div');
    s.className = 'star';
    const size = Math.random() * 2.5 + 0.5;
    s.style.cssText = `left:${Math.random()*100}%;top:${Math.random()*100}%;
      width:${size}px;height:${size}px;--d:${(Math.random()*3+1).toFixed(1)}s;
      animation-delay:${(Math.random()*3).toFixed(1)}s`;
    starsEl.appendChild(s);
  }

  // ── Sparkle bij muisbeweging ────────────────────────────────
  document.addEventListener('mousemove', (e) => {
    if (Math.random() > 0.3) return;
    const sp = document.createElement('div');
    sp.className = 'sparkle';
    const dx = (Math.random() - 0.5) * 60, dy = (Math.random() - 0.5) * 60;
    sp.style.cssText = `left:${e.clientX - 3}px;top:${e.clientY - 3}px;--dx:${dx}px;--dy:${dy}px`;
    document.body.appendChild(sp);
    setTimeout(() => sp.remove(), 1800);
  });

  // ── Taalwisselaar ──────────────────────────────────────────
  document.querySelectorAll('.lang-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      document.cookie = `lang=${btn.dataset.lang};path=/;max-age=31536000`;
      location.reload();
    });
  });

  // ── Wachtwoord-unlock ──────────────────────────────────────
  const UNLOCK_KEY = 'dlpwc_unlocked';

  function checkLocalUnlock() {
    const exp = localStorage.getItem(UNLOCK_KEY);
    if (exp && Date.now() < parseInt(exp)) location.href = '/';
  }
  checkLocalUnlock();

  document.getElementById('unlock-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const pw  = document.getElementById('password').value;
    const rem = document.getElementById('remember').checked;

    try {
      const res  = await fetch('/api/coming-soon-check.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ password: pw }),
      });
      const data = await res.json();

      if (data.success) {
        if (rem) localStorage.setItem(UNLOCK_KEY, Date.now() + 3 * 86400 * 1000);
        launchFireworks();
        document.getElementById('success-overlay').classList.add('show');
        setTimeout(() => { location.href = '/'; }, 1800);
      } else {
        const err = document.getElementById('error-msg');
        err.style.display = 'block';
        setTimeout(() => { err.style.display = 'none'; }, 2500);
      }
    } catch (_) {
      document.getElementById('error-msg').style.display = 'block';
    }
  });

  function launchFireworks() {
    for (let i = 0; i < 18; i++) {
      setTimeout(() => {
        const fw = document.createElement('div');
        fw.className = 'firework';
        const colors = ['#f5a800','#fff','#3d1f8c','#00915a'];
        fw.style.cssText = `left:${40+Math.random()*20}%;top:${20+Math.random()*40}%;
          background:${colors[Math.floor(Math.random()*colors.length)]};
          --dx:${(Math.random()-0.5)*120}px;--dy:${(Math.random()-0.5)*120}px`;
        document.body.appendChild(fw);
        setTimeout(() => fw.remove(), 1000);
      }, i * 60);
    }
  }
})();
</script>
</body>
</html>
