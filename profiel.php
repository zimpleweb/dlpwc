<?php
require_once __DIR__ . '/helpers/lang.php';
$lang = get_lang();
$pageTitle = ['en' => 'My profile', 'nl' => 'Mijn profiel', 'fr' => 'Mon profil'][$lang] ?? 'My profile';

$l = [
    'en' => [
        'loading'       => 'Loading...',
        'role_admin'    => '⭐ Admin',
        'role_user'     => '👤 User',
        'changePassword'=> '🔑 Change password',
        'currentPw'     => 'Current password *',
        'newPw'         => 'New password *',
        'newPwHint'     => '(min. 8 characters)',
        'confirmPw'     => 'Confirm new password *',
        'savePw'        => 'Save password',
        'savingPw'      => 'Saving...',
        'pwMismatch'    => '⚠️ Passwords do not match',
        'pwSuccess'     => '✅ Password changed successfully!',
        'myReviews'     => 'My reviews',
        'noReviews'     => 'You have not posted any reviews yet.',
        'hygiene'       => 'Hygiene',
        'crowd'         => 'Crowd',
        'location'      => 'Location',
        'facilities'    => 'Facilities',
        'approved'      => '✅ Approved',
        'pending'       => '⏳ Pending',
        'rejected'      => '❌ Rejected',
        'delete'        => '🗑️ Delete',
        'confirmDelete' => 'Delete review?',
        'deleteFailed'  => 'Delete failed',
        'avatarUpdated' => '✅ Profile photo updated',
        'avatarError'   => '⚠️ Error',
    ],
    'nl' => [
        'loading'       => 'Laden...',
        'role_admin'    => '⭐ Admin',
        'role_user'     => '👤 Gebruiker',
        'changePassword'=> '🔑 Wachtwoord wijzigen',
        'currentPw'     => 'Huidig wachtwoord *',
        'newPw'         => 'Nieuw wachtwoord *',
        'newPwHint'     => '(min. 8 tekens)',
        'confirmPw'     => 'Bevestig nieuw wachtwoord *',
        'savePw'        => 'Wachtwoord opslaan',
        'savingPw'      => 'Bezig...',
        'pwMismatch'    => '⚠️ Wachtwoorden komen niet overeen',
        'pwSuccess'     => '✅ Wachtwoord succesvol gewijzigd!',
        'myReviews'     => 'Mijn beoordelingen',
        'noReviews'     => 'Je hebt nog geen beoordelingen geplaatst.',
        'hygiene'       => 'Hygiëne',
        'crowd'         => 'Drukte',
        'location'      => 'Locatie',
        'facilities'    => 'Voorzieningen',
        'approved'      => '✅ Goedgekeurd',
        'pending'       => '⏳ In afwachting',
        'rejected'      => '❌ Afgekeurd',
        'delete'        => '🗑️ Verwijder',
        'confirmDelete' => 'Beoordeling verwijderen?',
        'deleteFailed'  => 'Verwijderen mislukt',
        'avatarUpdated' => '✅ Profielfoto bijgewerkt',
        'avatarError'   => '⚠️ Fout',
    ],
    'fr' => [
        'loading'       => 'Chargement...',
        'role_admin'    => '⭐ Admin',
        'role_user'     => '👤 Utilisateur',
        'changePassword'=> '🔑 Changer le mot de passe',
        'currentPw'     => 'Mot de passe actuel *',
        'newPw'         => 'Nouveau mot de passe *',
        'newPwHint'     => '(min. 8 caractères)',
        'confirmPw'     => 'Confirmer le nouveau mot de passe *',
        'savePw'        => 'Enregistrer le mot de passe',
        'savingPw'      => 'En cours...',
        'pwMismatch'    => '⚠️ Les mots de passe ne correspondent pas',
        'pwSuccess'     => '✅ Mot de passe modifié avec succès !',
        'myReviews'     => 'Mes avis',
        'noReviews'     => "Vous n'avez pas encore posté d'avis.",
        'hygiene'       => 'Hygiène',
        'crowd'         => 'Affluence',
        'location'      => 'Emplacement',
        'facilities'    => 'Équipements',
        'approved'      => '✅ Approuvé',
        'pending'       => '⏳ En attente',
        'rejected'      => '❌ Refusé',
        'delete'        => '🗑️ Supprimer',
        'confirmDelete' => "Supprimer l'avis ?",
        'deleteFailed'  => 'Échec de la suppression',
        'avatarUpdated' => '✅ Photo de profil mise à jour',
        'avatarError'   => '⚠️ Erreur',
    ],
][$lang] ?? [];

include __DIR__ . '/components/base-open.php';
?>

<div class="max-w-2xl mx-auto px-4 py-10">
  <div id="profile-loading" class="text-center text-slate-400 py-16">
    <div class="text-4xl mb-3">⏳</div>
    <?= htmlspecialchars($l['loading']) ?>
  </div>
  <div id="profile-content" class="hidden space-y-6"></div>
</div>

<script>
(function () {
  const l = <?= json_encode($l) ?>;

  const user = JSON.parse(localStorage.getItem('dlpwc_user') || 'null');
  if (!user) { location.href = '/login'; return; }

  // CSRF token ophalen
  let _csrfToken = '';
  fetch('/api/get-csrf.php', { credentials: 'include' })
    .then(r => r.json()).then(d => { _csrfToken = d.csrf_token || ''; });

  async function load() {
    const res = await fetch('/api/get-profile.php', { credentials: 'include' });
    if (res.status === 401) { location.href = '/login'; return; }
    const data = await res.json();

    document.getElementById('profile-loading').classList.add('hidden');
    const c = document.getElementById('profile-content');
    c.classList.remove('hidden');

    const u = data.user;
    const avatarUrl = u.avatar_url
      ? `/uploads/avatars/${u.avatar_url}`
      : `https://ui-avatars.com/api/?name=${encodeURIComponent(u.name)}&background=3d1f8c&color=fff&size=80`;

    c.innerHTML = `
      <div class="bg-white rounded-xl shadow p-6 flex items-center gap-5 border border-slate-100">
        <div class="relative">
          <img id="avatar-img" src="${avatarUrl}" alt="Avatar"
               class="w-20 h-20 rounded-full object-cover shadow"
               style="border: 4px solid #3d1f8c;" />
          <label class="absolute -bottom-1 -right-1 rounded-full w-7 h-7 cursor-pointer
                         hover:opacity-90 transition shadow"
                 style="background:#f5a800;display:flex;align-items:center;justify-content:center;"
                 title="${l.avatarUpdated}">
            <span style="font-size:14px;">📷</span>
            <input type="file" id="avatar-input" accept="image/*" class="hidden" />
          </label>
        </div>
        <div>
          <div class="text-xl font-bold" style="color:#3d1f8c;">${u.name}</div>
          <div class="text-sm text-slate-500">@${u.username}</div>
          <div class="text-sm text-slate-400">${u.email}</div>
          <div class="mt-1">
            <span class="inline-block text-xs px-2 py-0.5 rounded-full font-semibold
                          ${u.role === 'admin' ? 'text-white' : 'bg-slate-100 text-slate-600'}"
                  style="${u.role === 'admin' ? 'background:#3d1f8c;' : ''}">
              ${u.role === 'admin' ? l.role_admin : l.role_user}
            </span>
          </div>
        </div>
      </div>

      <div id="avatar-msg" class="text-sm hidden"></div>

      <details class="bg-white rounded-xl shadow border border-slate-100 group">
        <summary class="px-6 py-4 cursor-pointer select-none flex items-center justify-between
                         text-base font-bold list-none" style="color:#3d1f8c;">
          <span>${l.changePassword}</span>
          <span class="text-slate-400 text-sm font-normal">▼</span>
        </summary>
        <div class="px-6 pb-6 pt-2 border-t border-slate-100">
          <form id="pw-change-form" class="space-y-3 max-w-sm">
            <div>
              <label class="text-xs font-semibold text-slate-600">${l.currentPw}</label>
              <input type="password" name="current_password" required
                class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm mt-1
                       focus:outline-none focus:border-[#3d1f8c]" />
            </div>
            <div>
              <label class="text-xs font-semibold text-slate-600">
                ${l.newPw} <span class="font-normal text-slate-400">${l.newPwHint}</span>
              </label>
              <input type="password" name="new_password" required minlength="8"
                class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm mt-1
                       focus:outline-none focus:border-[#3d1f8c]" />
            </div>
            <div>
              <label class="text-xs font-semibold text-slate-600">${l.confirmPw}</label>
              <input type="password" name="confirm_password" required minlength="8"
                class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm mt-1
                       focus:outline-none focus:border-[#3d1f8c]" />
            </div>
            <div id="pw-change-msg" class="hidden text-sm rounded-lg px-3 py-2"></div>
            <button type="submit"
              class="text-white px-5 py-2 rounded-lg text-sm font-semibold hover:opacity-90 transition"
              style="background:#3d1f8c;">
              ${l.savePw}
            </button>
          </form>
        </div>
      </details>

      <div>
        <h2 class="text-lg font-bold mb-3" style="color:#3d1f8c;">
          ${l.myReviews} (${data.reviews.length})
        </h2>
        <div class="space-y-3">
          ${data.reviews.length === 0
            ? `<p class="text-slate-400 text-sm">${l.noReviews}</p>`
            : data.reviews.map(r => `
              <div class="bg-white rounded-lg shadow-sm p-4 border border-slate-100
                          flex justify-between items-start gap-3" id="rev-${r.id}">
                <div class="flex-1">
                  <div class="font-semibold text-sm" style="color:#3d1f8c;">${r.toilet_name}</div>
                  <div class="text-xs text-slate-400 mb-1">${(r.created_at ?? '').slice(0, 10)}</div>
                  <div class="text-xs text-slate-500">
                    ${l.hygiene} ${r.hygiene}★ · ${l.crowd} ${r.crowd}★ · ${l.location} ${r.location}★ · ${l.facilities} ${r.facilities}★
                  </div>
                  ${r.comment ? `<p class="text-sm text-slate-700 mt-1 italic">"${r.comment}"</p>` : ''}
                  <div class="mt-1">
                    <span class="text-xs px-2 py-0.5 rounded-full font-medium
                      ${r.status === 'approved' ? 'bg-green-100 text-green-700' :
                        r.status === 'pending'  ? 'bg-yellow-100 text-yellow-700' :
                        'bg-red-100 text-red-700'}">
                      ${r.status === 'approved' ? l.approved :
                        r.status === 'pending'  ? l.pending : l.rejected}
                    </span>
                  </div>
                </div>
                <button onclick="deleteReview(${r.id})"
                  class="text-xs text-red-500 hover:text-red-700 hover:underline flex-shrink-0 mt-1">
                  ${l.delete}
                </button>
              </div>
            `).join('')
          }
        </div>
      </div>
    `;

    // Avatar upload
    document.getElementById('avatar-input')?.addEventListener('change', async (e) => {
      const file = e.target.files?.[0];
      if (!file) return;
      const fd  = new FormData();
      fd.append('avatar', file);
      fd.append('_csrf', _csrfToken);
      const res  = await fetch('/api/update-avatar.php', { method: 'POST', body: fd, credentials: 'include', headers: { 'X-CSRF-Token': _csrfToken } });
      const data = await res.json();
      const msg  = document.getElementById('avatar-msg');
      if (data.success) {
        document.getElementById('avatar-img').src = `/uploads/avatars/${data.filename}?t=${Date.now()}`;
        msg.textContent = l.avatarUpdated;
        msg.className   = 'text-sm text-green-600';
      } else {
        msg.textContent = l.avatarError + ' ' + (data.error ?? '');
        msg.className   = 'text-sm text-red-600';
      }
      msg.classList.remove('hidden');
      setTimeout(() => msg.classList.add('hidden'), 3000);
    });

    // Wachtwoord wijzigen
    document.getElementById('pw-change-form')?.addEventListener('submit', async (e) => {
      e.preventDefault();
      const form   = e.target;
      const msg    = document.getElementById('pw-change-msg');
      const btn    = form.querySelector('button[type=submit]');
      const fd     = new FormData(form);
      const newPw  = fd.get('new_password');
      const confPw = fd.get('confirm_password');

      if (newPw !== confPw) {
        msg.textContent = l.pwMismatch;
        msg.className   = 'text-sm rounded-lg px-3 py-2 bg-red-50 text-red-700 border border-red-200';
        msg.classList.remove('hidden');
        return;
      }

      btn.disabled    = true;
      btn.textContent = l.savingPw;
      const res    = await fetch('/api/change-password.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': _csrfToken },
        body: JSON.stringify({ current_password: fd.get('current_password'), new_password: newPw }),
        credentials: 'include',
      });
      const result = await res.json();

      if (result.success) {
        msg.textContent = l.pwSuccess;
        msg.className   = 'text-sm rounded-lg px-3 py-2 bg-green-50 text-green-700 border border-green-200';
        form.reset();
        form.closest('details').removeAttribute('open');
      } else {
        msg.textContent = '⚠️ ' + (result.error ?? 'Fout');
        msg.className   = 'text-sm rounded-lg px-3 py-2 bg-red-50 text-red-700 border border-red-200';
      }
      msg.classList.remove('hidden');
      btn.disabled    = false;
      btn.textContent = l.savePw;
      setTimeout(() => msg.classList.add('hidden'), 5000);
    });
  }

  window.deleteReview = async (id) => {
    if (!confirm(l.confirmDelete)) return;
    const res  = await fetch('/api/delete-review.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': _csrfToken },
      body: JSON.stringify({ id }),
      credentials: 'include',
    });
    const data = await res.json();
    if (data.success) document.getElementById('rev-' + id)?.remove();
    else alert(data.error ?? l.deleteFailed);
  };

  load();
})();
</script>

<?php include __DIR__ . '/components/base-close.php'; ?>
