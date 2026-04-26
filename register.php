<?php
require_once __DIR__ . '/helpers/lang.php';
$lang = get_lang();
$pageTitle = ['en' => 'Register', 'nl' => 'Registreren', 'fr' => 'Inscription'][$lang] ?? 'Register';

$l = [
    'en' => [
        'heading'         => 'Create account',
        'subtitle'        => 'Create an account to write reviews',
        'name'            => 'Full name',
        'namePh'          => 'John Doe',
        'username'        => 'Username',
        'usernamePh'      => 'johndoe',
        'email'           => 'Email address',
        'emailPh'         => 'john@example.com',
        'password'        => 'Password',
        'passwordHint'    => '(min. 8 characters)',
        'submit'          => 'Create account',
        'loading'         => 'Please wait...',
        'hasAccount'      => 'Already have an account?',
        'login'           => 'Login',
        'successMsg'      => '✅ Account created! Redirecting to login...',
        'errorFallback'   => 'Something went wrong',
        'connectionError' => '⚠️ Connection error, please try again',
    ],
    'nl' => [
        'heading'         => 'Account aanmaken',
        'subtitle'        => 'Maak een account aan om reviews te schrijven',
        'name'            => 'Volledige naam',
        'namePh'          => 'Jan Jansen',
        'username'        => 'Gebruikersnaam',
        'usernamePh'      => 'janjansen',
        'email'           => 'E-mailadres',
        'emailPh'         => 'jan@voorbeeld.nl',
        'password'        => 'Wachtwoord',
        'passwordHint'    => '(min. 8 tekens)',
        'submit'          => 'Account aanmaken',
        'loading'         => 'Even geduld...',
        'hasAccount'      => 'Al een account?',
        'login'           => 'Inloggen',
        'successMsg'      => '✅ Account aangemaakt! Je wordt doorgestuurd...',
        'errorFallback'   => 'Er ging iets mis',
        'connectionError' => '⚠️ Verbindingsfout, probeer opnieuw',
    ],
    'fr' => [
        'heading'         => 'Créer un compte',
        'subtitle'        => 'Créez un compte pour écrire des avis',
        'name'            => 'Nom complet',
        'namePh'          => 'Jean Dupont',
        'username'        => "Nom d'utilisateur",
        'usernamePh'      => 'jeandupont',
        'email'           => 'Adresse e-mail',
        'emailPh'         => 'jean@exemple.fr',
        'password'        => 'Mot de passe',
        'passwordHint'    => '(min. 8 caractères)',
        'submit'          => 'Créer un compte',
        'loading'         => 'Veuillez patienter...',
        'hasAccount'      => 'Déjà un compte ?',
        'login'           => 'Se connecter',
        'successMsg'      => '✅ Compte créé ! Redirection vers la page de connexion...',
        'errorFallback'   => 'Une erreur s\'est produite',
        'connectionError' => '⚠️ Erreur de connexion, veuillez réessayer',
    ],
][$lang] ?? [];

include __DIR__ . '/components/base-open.php';
?>

<div class="min-h-[calc(100vh-59px)] flex items-center justify-center px-4 py-10" style="background:#f8f7fc;">
  <div class="w-full max-w-md bg-white rounded-2xl shadow-lg border border-slate-200 p-8">
    <div class="text-center mb-6">
      <div class="text-4xl mb-2">🚽</div>
      <h1 class="text-2xl font-bold" style="color:#3d1f8c;"><?= htmlspecialchars($l['heading']) ?></h1>
      <p class="text-sm text-slate-500 mt-1"><?= htmlspecialchars($l['subtitle']) ?></p>
    </div>

    <form id="register-form" class="space-y-4">
      <div>
        <label class="text-xs font-semibold text-slate-600"><?= htmlspecialchars($l['name']) ?> *</label>
        <input name="name" required
               placeholder="<?= htmlspecialchars($l['namePh']) ?>"
               class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm mt-1
                      focus:outline-none focus:border-[#3d1f8c]">
      </div>
      <div>
        <label class="text-xs font-semibold text-slate-600"><?= htmlspecialchars($l['username']) ?> *</label>
        <input name="username" required
               placeholder="<?= htmlspecialchars($l['usernamePh']) ?>"
               class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm mt-1
                      focus:outline-none focus:border-[#3d1f8c]">
      </div>
      <div>
        <label class="text-xs font-semibold text-slate-600"><?= htmlspecialchars($l['email']) ?> *</label>
        <input name="email" type="email" required
               placeholder="<?= htmlspecialchars($l['emailPh']) ?>"
               class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm mt-1
                      focus:outline-none focus:border-[#3d1f8c]">
      </div>
      <div>
        <label class="text-xs font-semibold text-slate-600">
          <?= htmlspecialchars($l['password']) ?> *
          <span class="font-normal text-slate-400"><?= htmlspecialchars($l['passwordHint']) ?></span>
        </label>
        <input name="password" type="password" required minlength="8"
               placeholder="••••••••"
               class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm mt-1
                      focus:outline-none focus:border-[#3d1f8c]">
      </div>
      <div id="register-msg" class="hidden text-sm rounded-lg px-3 py-2"></div>
      <button type="submit"
              class="w-full text-white font-bold py-2.5 rounded-lg hover:opacity-90 transition text-sm"
              style="background:#3d1f8c;">
        <?= htmlspecialchars($l['submit']) ?>
      </button>
      <p class="text-center text-xs text-slate-500 pt-1">
        <?= htmlspecialchars($l['hasAccount']) ?>
        <a href="/login" class="font-semibold hover:underline" style="color:#3d1f8c;">
          <?= htmlspecialchars($l['login']) ?>
        </a>
      </p>
    </form>
  </div>
</div>

<script>
(function () {
  const l    = <?= json_encode($l) ?>;
  const form = document.getElementById('register-form');
  const msg  = document.getElementById('register-msg');

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = form.querySelector('button[type=submit]');
    btn.disabled    = true;
    btn.textContent = l.loading;
    msg.className   = 'hidden';

    const data = Object.fromEntries(new FormData(form));
    try {
      const res    = await fetch('/api/register.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data),
        credentials: 'include',
      });
      const result = await res.json();
      if (result.success) {
        msg.textContent = l.successMsg;
        msg.className   = 'text-sm rounded-lg px-3 py-2 bg-green-50 text-green-700 border border-green-200';
        setTimeout(() => { location.href = '/login'; }, 2000);
      } else {
        msg.textContent = '⚠️ ' + (result.error ?? l.errorFallback);
        msg.className   = 'text-sm rounded-lg px-3 py-2 bg-red-50 text-red-700 border border-red-200';
        btn.disabled    = false;
        btn.textContent = l.submit;
      }
    } catch (_) {
      msg.textContent = l.connectionError;
      msg.className   = 'text-sm rounded-lg px-3 py-2 bg-red-50 text-red-700 border border-red-200';
      btn.disabled    = false;
      btn.textContent = l.submit;
    }
  });
})();
</script>

<?php include __DIR__ . '/components/base-close.php'; ?>
