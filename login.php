<?php
require_once __DIR__ . '/helpers/lang.php';
$lang = get_lang();
$pageTitle = ['en' => 'Login', 'nl' => 'Inloggen', 'fr' => 'Connexion'][$lang] ?? 'Login';

$l = [
    'en' => [
        'heading'         => 'Login',
        'subtitle'        => 'Welcome back to DLP WC',
        'email'           => 'Email address',
        'password'        => 'Password',
        'submit'          => 'Login →',
        'loading'         => 'Loading...',
        'noAccount'       => 'No account yet?',
        'register'        => 'Register',
        'successMsg'      => '✅ Logged in! Redirecting...',
        'errorFallback'   => 'Login failed',
        'serverError'     => '⚠️ Server error — invalid JSON received.',
        'connectionError' => '⚠️ Connection error — is the server running?',
    ],
    'nl' => [
        'heading'         => 'Inloggen',
        'subtitle'        => 'Welkom terug bij DLP WC',
        'email'           => 'E-mailadres',
        'password'        => 'Wachtwoord',
        'submit'          => 'Inloggen →',
        'loading'         => 'Bezig...',
        'noAccount'       => 'Nog geen account?',
        'register'        => 'Registreren',
        'successMsg'      => '✅ Ingelogd! Je wordt doorgestuurd...',
        'errorFallback'   => 'Inloggen mislukt',
        'serverError'     => '⚠️ Serverfout — geen geldige JSON ontvangen.',
        'connectionError' => '⚠️ Verbindingsfout — is de server actief?',
    ],
    'fr' => [
        'heading'         => 'Connexion',
        'subtitle'        => 'Bon retour sur DLP WC',
        'email'           => 'Adresse e-mail',
        'password'        => 'Mot de passe',
        'submit'          => 'Se connecter →',
        'loading'         => 'Chargement...',
        'noAccount'       => 'Pas encore de compte ?',
        'register'        => "S'inscrire",
        'successMsg'      => '✅ Connecté ! Redirection en cours...',
        'errorFallback'   => 'Échec de la connexion',
        'serverError'     => '⚠️ Erreur serveur — JSON invalide reçu.',
        'connectionError' => '⚠️ Erreur de connexion — le serveur est-il actif ?',
    ],
][$lang] ?? [];

include __DIR__ . '/components/base-open.php';
?>

<div class="max-w-md mx-auto px-4 py-16">
  <div class="text-center mb-8">
    <div class="text-5xl mb-3">🚽</div>
    <h1 class="text-2xl font-bold text-[#3d1f8c]"><?= htmlspecialchars($l['heading']) ?></h1>
    <p class="text-slate-500 text-sm mt-1"><?= htmlspecialchars($l['subtitle']) ?></p>
  </div>

  <form id="login-form" class="bg-white rounded-2xl shadow-md p-7 space-y-4 border border-slate-100">
    <div>
      <label class="text-sm font-semibold text-slate-700"><?= htmlspecialchars($l['email']) ?></label>
      <input type="email" name="email" required autocomplete="email"
             class="w-full border border-slate-300 rounded-xl px-3 py-2.5 text-sm mt-1
                    focus:outline-none focus:border-[#3d1f8c] transition">
    </div>
    <div>
      <label class="text-sm font-semibold text-slate-700"><?= htmlspecialchars($l['password']) ?></label>
      <input type="password" name="password" required autocomplete="current-password"
             class="w-full border border-slate-300 rounded-xl px-3 py-2.5 text-sm mt-1
                    focus:outline-none focus:border-[#3d1f8c] transition">
    </div>
    <div id="login-msg" class="hidden text-sm rounded-xl px-4 py-3 border"></div>
    <button type="submit" id="login-btn"
            class="w-full text-white py-3 rounded-xl font-bold text-sm
                   hover:opacity-90 transition shadow-md"
            style="background: linear-gradient(to right, #3d1f8c, #5229b8);">
      <?= htmlspecialchars($l['submit']) ?>
    </button>
    <p class="text-sm text-slate-500 text-center">
      <?= htmlspecialchars($l['noAccount']) ?>
      <a href="/register" class="font-semibold hover:underline" style="color:#3d1f8c;">
        <?= htmlspecialchars($l['register']) ?>
      </a>
    </p>
  </form>
</div>

<script>
(function () {
  const l = <?= json_encode($l) ?>;
  const form = document.getElementById('login-form');
  const msg  = document.getElementById('login-msg');
  const btn  = document.getElementById('login-btn');

  function showMsg(text, isError) {
    msg.textContent = text;
    msg.className = isError
      ? 'text-sm rounded-xl px-4 py-3 border bg-red-50 border-red-200 text-red-700'
      : 'text-sm rounded-xl px-4 py-3 border bg-green-50 border-green-200 text-green-700';
    msg.classList.remove('hidden');
  }

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    btn.disabled    = true;
    btn.textContent = l.loading;
    msg.classList.add('hidden');

    const fd = new FormData(form);
    try {
      const csrfRes = await fetch('/api/get-csrf.php', { credentials: 'include' });
      const { csrf_token } = await csrfRes.json();
      const res  = await fetch('/api/login.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': csrf_token },
        body: JSON.stringify({ email: fd.get('email').trim(), password: fd.get('password').trim() }),
        credentials: 'include',
      });
      const text = await res.text();
      let result;
      try { result = JSON.parse(text); } catch (_) { showMsg(l.serverError, true); return; }

      if (result.success) {
        localStorage.setItem('dlpwc_user', JSON.stringify(result));
        showMsg(l.successMsg, false);
        setTimeout(() => { location.href = result.role === 'admin' ? '/admin/' : '/'; }, 800);
      } else {
        showMsg('⚠️ ' + (result.error ?? l.errorFallback), true);
      }
    } catch (err) {
      showMsg(l.connectionError + ' (' + err.message + ')', true);
    } finally {
      btn.disabled    = false;
      btn.textContent = l.submit;
    }
  });
})();
</script>

<?php include __DIR__ . '/components/base-close.php'; ?>
