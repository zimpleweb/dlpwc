/**
 * toilet-page.js — Volledig toilet-detailpagina script.
 * Vereist: Leaflet (window.L), window.DLPWC.lang ingesteld door PHP.
 */

(function () {
  const lang = (window.DLPWC && window.DLPWC.lang) || 'en';
  const API  = '/api';

  // ── Fallback-vertalingen ──────────────────────────────────────
  const T_FB = {
    en: {
      toilet: {
        back: '← Back to map', hygiene: 'Hygiene', crowd: 'Crowd',
        locationLabel: 'Location', facilities: 'Facilities',
        notRated: 'Not rated', ratings: 'ratings', rating: 'rating',
        reviews: 'Reviews', noReviews: 'No reviews yet.',
        location: 'Location', openMaps: 'Open in Maps',
        nearby: 'Nearby toilets', nearbyLoading: 'Loading…',
        featured: 'Featured review',
      },
      form: {
        title: 'Write a review', submit: 'Submit review', submitting: 'Submitting…',
        comment: 'Comment', commentMax: '(optional, max 150 words)',
        commentPlaceholder: 'Your experience…',
        photos: 'Photos', photosMax: '(max 3)', photosWarning: 'Photos are reviewed before publication.',
        words: 'words', name: 'Name', nameOptional: '(optional)',
        language: 'Review language', reviewingAs: 'Reviewing as',
        success: '✅ Review submitted!',
        pendingTitle: 'Review pending', pendingMsg: 'Your review is awaiting moderation.',
        blockedTitle: 'Access blocked', blockedUntil: 'Blocked until',
        blockedPerm: 'You are permanently blocked.',
        errorLink: '⚠️ Links are not allowed in reviews.',
        errorBanned: '⚠️ Your comment contains inappropriate language.',
        errorWords: '⚠️ Maximum 150 words allowed.',
        errorGeneric: 'Something went wrong.', errorConnection: '⚠️ Connection error.',
        errorRecaptcha: '⚠️ Please complete the reCAPTCHA.',
        wantAccount: 'Create an account or log in for a better experience.',
        yesAccount: 'Create account', loginBtn: 'Log in', noAccount: 'Continue as guest',
        regTitle: 'Create account', regName: 'Full name', regEmail: 'E-mail',
        regUsername: 'Username', regPassword: 'Password (min. 8 characters)',
        regSubmit: 'Create account', regSubmitting: 'Creating account…',
        loginTitle: 'Log in', loginEmail: 'E-mail', loginPassword: 'Password',
        loginSubmit: 'Log in', loginSubmitting: 'Logging in…',
        loginError: '⚠️ Invalid e-mail or password.',
      },
      reviewFilter: { all: 'All languages' },
    },
    nl: {
      toilet: {
        back: '← Terug naar kaart', hygiene: 'Hygiëne', crowd: 'Drukte',
        locationLabel: 'Locatie', facilities: 'Voorzieningen',
        notRated: 'Niet beoordeeld', ratings: 'beoordelingen', rating: 'beoordeling',
        reviews: 'Beoordelingen', noReviews: 'Nog geen beoordelingen.',
        location: 'Locatie', openMaps: 'Open in Maps',
        nearby: 'Toiletten in de buurt', nearbyLoading: 'Laden…',
        featured: 'Uitgelichte recensie',
      },
      form: {
        title: 'Schrijf een beoordeling', submit: 'Beoordeling plaatsen', submitting: 'Bezig…',
        comment: 'Reactie', commentMax: '(optioneel, max 150 woorden)',
        commentPlaceholder: 'Jouw ervaring…',
        photos: "Foto's", photosMax: '(max 3)', photosWarning: "Foto's worden beoordeeld vóór publicatie.",
        words: 'woorden', name: 'Naam', nameOptional: '(optioneel)',
        language: 'Taal van de beoordeling', reviewingAs: 'Beoordeling als',
        success: '✅ Beoordeling geplaatst!',
        pendingTitle: 'Beoordeling in behandeling', pendingMsg: 'Je beoordeling wacht op goedkeuring.',
        blockedTitle: 'Toegang geblokkeerd', blockedUntil: 'Geblokkeerd tot',
        blockedPerm: 'Je bent permanent geblokkeerd.',
        errorLink: '⚠️ Links zijn niet toegestaan in beoordelingen.',
        errorBanned: '⚠️ Je reactie bevat ongepast taalgebruik.',
        errorWords: '⚠️ Maximaal 150 woorden toegestaan.',
        errorGeneric: 'Er ging iets mis.', errorConnection: '⚠️ Verbindingsfout.',
        errorRecaptcha: '⚠️ Voltooi de reCAPTCHA.',
        wantAccount: 'Maak een account aan of log in voor een betere ervaring.',
        yesAccount: 'Account aanmaken', loginBtn: 'Inloggen', noAccount: 'Verder als gast',
        regTitle: 'Account aanmaken', regName: 'Volledige naam', regEmail: 'E-mailadres',
        regUsername: 'Gebruikersnaam', regPassword: 'Wachtwoord (min. 8 tekens)',
        regSubmit: 'Account aanmaken', regSubmitting: 'Account aanmaken…',
        loginTitle: 'Inloggen', loginEmail: 'E-mailadres', loginPassword: 'Wachtwoord',
        loginSubmit: 'Inloggen', loginSubmitting: 'Bezig met inloggen…',
        loginError: '⚠️ Onjuist e-mailadres of wachtwoord.',
      },
      reviewFilter: { all: 'Alle talen' },
    },
    fr: {
      toilet: {
        back: '← Retour à la carte', hygiene: 'Hygiène', crowd: 'Affluence',
        locationLabel: 'Emplacement', facilities: 'Équipements',
        notRated: 'Non évalué', ratings: 'avis', rating: 'avis',
        reviews: 'Avis', noReviews: 'Aucun avis pour l\'instant.',
        location: 'Emplacement', openMaps: 'Ouvrir dans Maps',
        nearby: 'Toilettes à proximité', nearbyLoading: 'Chargement…',
        featured: 'Avis en vedette',
      },
      form: {
        title: 'Écrire un avis', submit: 'Soumettre l\'avis', submitting: 'En cours…',
        comment: 'Commentaire', commentMax: '(optionnel, max 150 mots)',
        commentPlaceholder: 'Votre expérience…',
        photos: 'Photos', photosMax: '(max 3)', photosWarning: 'Les photos sont vérifiées avant publication.',
        words: 'mots', name: 'Nom', nameOptional: '(optionnel)',
        language: 'Langue de l\'avis', reviewingAs: 'Avis en tant que',
        success: '✅ Avis soumis !',
        pendingTitle: 'Avis en attente', pendingMsg: 'Votre avis attend la modération.',
        blockedTitle: 'Accès bloqué', blockedUntil: 'Bloqué jusqu\'au',
        blockedPerm: 'Vous êtes bloqué définitivement.',
        errorLink: '⚠️ Les liens ne sont pas autorisés dans les avis.',
        errorBanned: '⚠️ Votre commentaire contient un langage inapproprié.',
        errorWords: '⚠️ Maximum 150 mots autorisés.',
        errorGeneric: 'Une erreur s\'est produite.', errorConnection: '⚠️ Erreur de connexion.',
        errorRecaptcha: '⚠️ Veuillez compléter le reCAPTCHA.',
        wantAccount: 'Créez un compte ou connectez-vous pour une meilleure expérience.',
        yesAccount: 'Créer un compte', loginBtn: 'Se connecter', noAccount: 'Continuer en invité',
        regTitle: 'Créer un compte', regName: 'Nom complet', regEmail: 'E-mail',
        regUsername: 'Nom d\'utilisateur', regPassword: 'Mot de passe (min. 8 caractères)',
        regSubmit: 'Créer le compte', regSubmitting: 'Création en cours…',
        loginTitle: 'Se connecter', loginEmail: 'E-mail', loginPassword: 'Mot de passe',
        loginSubmit: 'Se connecter', loginSubmitting: 'Connexion en cours…',
        loginError: '⚠️ E-mail ou mot de passe incorrect.',
      },
      reviewFilter: { all: 'Toutes les langues' },
    },
  };

  let t = T_FB[lang] || T_FB.en;  // overschreven door API

  // ── Hulpfuncties ──────────────────────────────────────────────
  const AREA_LABELS = {
    en: { PARK: '🏰 Park', STUDIOS: '🌟 Adventure World', VILLAGE: '🛍️ Village', HOTEL: '🏨 Hotel', PARKING: '🅿️ Parking' },
    nl: { PARK: '🏰 Park', STUDIOS: '🌟 Adventure World', VILLAGE: '🛍️ Village', HOTEL: '🏨 Hotel', PARKING: '🅿️ Parking' },
    fr: { PARK: '🏰 Parc', STUDIOS: '🌟 Adventure World', VILLAGE: '🛍️ Village', HOTEL: '🏨 Hôtels', PARKING: '🅿️ Parking' },
  };
  const LANG_FLAGS = {
    en: { flag: '🇬🇧', label: 'English' },
    nl: { flag: '🇳🇱', label: 'Nederlands' },
    fr: { flag: '🇫🇷', label: 'Français' },
  };

  function scoreColor(s) {
    if (!s && s !== 0) return '#94a3b8';
    if (s < 2)    return '#ef4444';
    if (s < 2.75) return '#f97316';
    if (s < 3.5)  return '#eab308';
    if (s < 4.25) return '#84cc16';
    return '#22c55e';
  }

  function starHtml(score, size = 18) {
    if (!score) return `<span style="color:#94a3b8;font-size:12px;">${t.toilet.notRated}</span>`;
    const n = Math.floor(score);
    return `<span style="color:#c9a84c;font-size:${size}px;">${'★'.repeat(n)}${'☆'.repeat(5 - n)}</span>`;
  }

  function containsLink(text) {
    return /https?:\/\/|www\.|\.com|\.nl|\.org|\.net|\.io/i.test(text);
  }

  const BANNED = ['fuck','shit','bitch','asshole','bastard','cunt','dick','pussy','cock','whore',
    'slut','nigger','nigga','faggot','fag','retard','twat','wanker','prick','motherfucker'];

  function containsBannedWord(text) {
    const lower = text.toLowerCase();
    return BANNED.some(w => new RegExp('\\b' + w + '\\b').test(lower));
  }

  function getFingerprint() {
    let fp = localStorage.getItem('dlpwc_fp');
    if (!fp) {
      fp = (crypto.randomUUID ? crypto.randomUUID() : Math.random().toString(36).slice(2) + Date.now().toString(36));
      localStorage.setItem('dlpwc_fp', fp);
    }
    return fp;
  }

  // ── Gallerij ──────────────────────────────────────────────────
  function buildGallery(images) {
    if (!images.length) return '';
    const escapedImages = JSON.stringify(images).replace(/"/g, '&quot;');
    const slides = images.map((img, i) => `
      <div style="flex:0 0 100%;width:100%;height:100%;position:relative;cursor:zoom-in;"
           class="lb-trigger" data-lb-idx="${i}"
           data-images="${escapedImages}">
        <img src="${img.src}" style="width:100%;height:100%;object-fit:cover;"
             onerror="this.parentElement.style.display='none'">
        <div style="position:absolute;bottom:10px;left:10px;
                    background:rgba(0,0,0,.52);color:white;
                    font-size:10px;padding:2px 9px;border-radius:99px;
                    backdrop-filter:blur(4px);">📷 ${img.caption}</div>
        ${images.length > 1 ? `<div style="position:absolute;bottom:10px;right:10px;
            background:rgba(0,0,0,.52);color:white;font-size:10px;
            padding:2px 9px;border-radius:99px;backdrop-filter:blur(4px);">${i + 1}/${images.length}</div>` : ''}
      </div>`).join('');

    const dots = images.length > 1 ? `
      <div style="display:flex;justify-content:center;gap:6px;position:absolute;
                  bottom:38px;left:50%;transform:translateX(-50%);z-index:5;">
        ${images.map((_, i) => `<button class="gallery-dot" data-index="${i}"
          style="width:7px;height:7px;border-radius:50%;border:none;cursor:pointer;padding:0;
                 background:${i === 0 ? 'white' : 'rgba(255,255,255,.45)'};transition:all .2s;
                 ${i === 0 ? 'transform:scale(1.3);' : ''}"></button>`).join('')}
      </div>` : '';

    const arrows = images.length > 1 ? `
      <button id="gallery-prev" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);
        z-index:5;background:rgba(0,0,0,.45);color:white;border:none;width:34px;height:34px;
        border-radius:50%;font-size:16px;cursor:pointer;display:flex;align-items:center;
        justify-content:center;backdrop-filter:blur(4px);">‹</button>
      <button id="gallery-next" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);
        z-index:5;background:rgba(0,0,0,.45);color:white;border:none;width:34px;height:34px;
        border-radius:50%;font-size:16px;cursor:pointer;display:flex;align-items:center;
        justify-content:center;backdrop-filter:blur(4px);">›</button>` : '';

    return `
      <div id="gallery-root" style="position:relative;border-radius:16px;overflow:hidden;
            height:260px;background:#e2e8f0;user-select:none;margin-bottom:16px;">
        <div id="gallery-track" style="display:flex;height:100%;
              transition:transform .35s cubic-bezier(.4,0,.2,1);">
          ${slides}
        </div>
        ${arrows}${dots}
      </div>`;
  }

  function initGalleryControls() {
    const track = document.getElementById('gallery-track');
    if (!track) return;
    const count = track.querySelectorAll('div[data-images]').length;
    if (count <= 1) return;
    let idx = 0, startX = 0, dragging = false;

    function goTo(n) {
      idx = (n + count) % count;
      track.style.transform = `translateX(-${idx * 100}%)`;
      document.querySelectorAll('.gallery-dot').forEach((d, i) => {
        d.style.background   = i === idx ? 'white' : 'rgba(255,255,255,.45)';
        d.style.transform    = i === idx ? 'scale(1.3)' : 'scale(1)';
      });
    }

    document.getElementById('gallery-prev')?.addEventListener('click', e => { e.stopPropagation(); goTo(idx - 1); });
    document.getElementById('gallery-next')?.addEventListener('click', e => { e.stopPropagation(); goTo(idx + 1); });
    document.querySelectorAll('.gallery-dot').forEach(d => {
      d.addEventListener('click', e => { e.stopPropagation(); goTo(+d.dataset.index); });
    });
    track.addEventListener('pointerdown', e => { startX = e.clientX; dragging = true; track.style.transition = 'none'; });
    window.addEventListener('pointerup', e => {
      if (!dragging) return; dragging = false;
      track.style.transition = 'transform .35s cubic-bezier(.4,0,.2,1)';
      const dx = e.clientX - startX;
      if (Math.abs(dx) > 40) goTo(dx < 0 ? idx + 1 : idx - 1); else goTo(idx);
    });
  }

  // ── Mini-kaart ────────────────────────────────────────────────
  function initMiniMap(lat, lng, name) {
    if (!window.L) return;
    const map = L.map('mini-map', {
      zoomControl: false, dragging: false, scrollWheelZoom: false,
      doubleClickZoom: false, touchZoom: false, keyboard: false, attributionControl: false,
    }).setView([lat, lng], 17);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);
    L.marker([lat, lng], {
      icon: L.divIcon({
        className: '',
        html: `<div style="width:32px;height:32px;border-radius:50%;background:#003f8a;
               border:3px solid white;box-shadow:0 3px 10px rgba(0,0,0,.35);
               display:flex;align-items:center;justify-content:center;font-size:15px;">🚽</div>`,
        iconSize: [32, 32], iconAnchor: [16, 16],
      })
    }).bindTooltip(name, { permanent: true, direction: 'top', offset: [0, -18] }).addTo(map);
    document.getElementById('mini-map')?.addEventListener('click', () => {
      location.href = `/?lat=${lat}&lng=${lng}`;
    });
  }

  // ── Reviews HTML ──────────────────────────────────────────────
  function buildReviewsHtml(reviews) {
    if (!reviews.length) return `<p style="color:#94a3b8;font-size:13px;padding:16px 0;">${t.toilet.noReviews}</p>`;
    return reviews.map(r => {
      const photos = _parseJson(r.images_json, []);
      const photosData = JSON.stringify(photos.map(f => ({
        src: `/uploads/reviews/${f}`,
        caption: `📷 ${r.username ?? r.guest_name ?? 'Guest'}`,
      }))).replace(/"/g, '&quot;');
      const langInfo = LANG_FLAGS[r.review_lang] || null;
      const color = scoreColor(((r.hygiene + r.crowd + r.location + r.facilities) / 4));

      return `
        <div style="background:white;border-radius:18px;padding:16px;
                    border:1px solid #ede8f8;box-shadow:0 2px 10px rgba(61,31,140,.06);
                    transition:box-shadow .15s,transform .15s;"
             onmouseover="this.style.boxShadow='0 6px 20px rgba(61,31,140,.12)';this.style.transform='translateY(-1px)'"
             onmouseout="this.style.boxShadow='0 2px 10px rgba(61,31,140,.06)';this.style.transform='translateY(0)'">

          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
            <div style="display:flex;align-items:center;gap:10px;">
              <div style="width:34px;height:34px;border-radius:50%;flex-shrink:0;
                          background:linear-gradient(135deg,#3d1f8c,#5229b8);
                          display:flex;align-items:center;justify-content:center;
                          font-size:13px;font-weight:700;color:white;">
                ${(r.username ?? r.guest_name ?? 'G').charAt(0).toUpperCase()}
              </div>
              <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
                <span style="font-weight:700;font-size:13px;color:#1e293b;">${r.username ?? r.guest_name ?? 'Guest'}</span>
                ${r.is_admin_review ? `<span style="font-size:10px;background:linear-gradient(135deg,#3d1f8c,#5229b8);
                  color:white;padding:2px 8px;border-radius:99px;font-weight:600;">Admin</span>` : ''}
                ${langInfo ? `<span title="${langInfo.label}">${langInfo.flag}</span>` : ''}
              </div>
            </div>
            <span style="font-size:11px;color:#94a3b8;background:#f8f7fc;
                         padding:2px 10px;border-radius:99px;border:1px solid #ede8f8;">
              ${(r.created_at ?? '').slice(0, 10)}
            </span>
          </div>

          <div style="display:flex;gap:6px;margin-bottom:10px;flex-wrap:wrap;">
            ${[[t.toilet.hygiene, r.hygiene],[t.toilet.crowd, r.crowd],[t.toilet.locationLabel, r.location],[t.toilet.facilities, r.facilities]].map(([label, val]) => `
              <span style="font-size:10px;padding:3px 10px;border-radius:99px;
                           background:linear-gradient(135deg,rgba(61,31,140,.07),rgba(61,31,140,.04));
                           color:#3d1f8c;font-weight:600;border:1px solid rgba(61,31,140,.1);">
                ${label} <span style="color:#f5a800;">${val}★</span>
              </span>`).join('')}
          </div>

          ${r.comment ? `<p style="font-size:13px;color:#374151;line-height:1.65;margin:0 0 10px;
                           padding:10px 14px;background:#faf9ff;border-radius:10px;
                           border-left:3px solid rgba(61,31,140,.27);">${r.comment}</p>` : ''}

          ${photos.length ? `
            <div style="display:flex;gap:6px;flex-wrap:wrap;margin-top:6px;">
              ${photos.map((f, i) => `
                <div style="width:72px;height:72px;border-radius:10px;overflow:hidden;cursor:zoom-in;
                            border:2px solid #ede8f8;box-shadow:0 1px 6px rgba(61,31,140,.1);
                            transition:transform .15s,box-shadow .15s;"
                     onmouseover="this.style.transform='scale(1.06)';this.style.boxShadow='0 4px 12px rgba(61,31,140,.2)'"
                     onmouseout="this.style.transform='scale(1)';this.style.boxShadow='0 1px 6px rgba(61,31,140,.1)'"
                     class="lb-trigger" data-lb-idx="${i}"
                     data-images="${photosData}">
                  <img src="/uploads/reviews/${f}" style="width:100%;height:100%;object-fit:cover;"
                       onerror="this.parentElement.style.display='none'">
                </div>`).join('')}
            </div>` : ''}
        </div>`;
    }).join('');
  }

  // ── Buurt-toiletten laden ─────────────────────────────────────
  async function loadNearby(toiletId, lat, lng) {
    const el = document.getElementById('nearby-section');
    if (!el) return;
    try {
      const data = await _fetch(`${API}/get-nearby.php?id=${toiletId}&lat=${lat}&lng=${lng}&limit=5`);
      if (!Array.isArray(data) || !data.length) {
        el.innerHTML = `<p style="color:#94a3b8;font-size:13px;">${t.toilet.noReviews}</p>`; return;
      }
      const labels = AREA_LABELS[lang] || AREA_LABELS.en;
      el.innerHTML = data.map(n => {
        const sc  = n.score !== null ? parseFloat(n.score).toFixed(1) : '—';
        const col = scoreColor(n.score);
        const stars = n.score ? '★'.repeat(Math.floor(n.score)) + '☆'.repeat(5 - Math.floor(n.score)) : '';
        return `
          <a href="/toilet/${n.id}" style="display:flex;align-items:center;gap:12px;
             background:white;border-radius:12px;border:1px solid #e2e8f0;padding:12px;
             text-decoration:none;transition:all .2s;"
             onmouseover="this.style.borderColor='#003f8a';this.style.boxShadow='0 4px 12px rgba(0,63,138,.12)'"
             onmouseout="this.style.borderColor='#e2e8f0';this.style.boxShadow=''">
            <div style="width:36px;height:36px;border-radius:50%;flex-shrink:0;background:${col};
                        border:2px solid white;box-shadow:0 2px 8px rgba(0,0,0,.2);
                        display:flex;align-items:center;justify-content:center;font-size:16px;">🚽</div>
            <div style="flex:1;min-width:0;">
              <div style="font-size:13px;font-weight:700;color:#003f8a;
                          white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${n.name}</div>
              <div style="font-size:11px;color:#94a3b8;margin-top:2px;">${labels[n.area] ?? n.area}</div>
              <div style="color:#c9a84c;font-size:12px;margin-top:2px;">${stars}</div>
            </div>
            <div style="text-align:right;flex-shrink:0;">
              <div style="font-size:1.15rem;font-weight:800;color:${col};">${sc}</div>
              <div style="font-size:11px;color:#94a3b8;">${n.review_count ?? 0} rev.</div>
              ${n.distance_label ? `<div style="font-size:11px;font-weight:600;margin-top:4px;
                padding:2px 8px;border-radius:99px;background:#f1f5f9;color:#475569;display:inline-block;">
                📍 ${n.distance_label}</div>` : ''}
            </div>
          </a>`;
      }).join('');
    } catch {
      el.innerHTML = `<p style="color:#94a3b8;font-size:13px;">${t.toilet.nearbyLoading}</p>`;
    }
  }

  // ── Review-formulier ──────────────────────────────────────────
  function buildReviewForm(toiletId, isLoggedIn, username) {
    const userBlock = isLoggedIn
      ? `<div id="user-block-wrap">
           <div style="background:linear-gradient(135deg,rgba(61,31,140,.06),rgba(61,31,140,.03));
                        border:1px solid rgba(61,31,140,.15);border-radius:14px;
                        padding:12px 16px;font-size:13px;color:#3d1f8c;
                        display:flex;align-items:center;gap:10px;">
             <span style="font-size:20px;">👤</span>
             <span>${t.form.reviewingAs} <strong>${username}</strong></span>
           </div>
         </div>`
      : `<div>
           <div id="reg-prompt-wrap" style="background:linear-gradient(135deg,#faf9ff,#f3f0fb);
                border:1px solid rgba(61,31,140,.15);border-radius:14px;padding:14px 16px;margin-bottom:12px;">
             <p style="font-size:12px;color:#3d1f8c;font-weight:600;margin:0 0 10px;">
               ${t.form.wantAccount}
             </p>
             <div style="display:flex;gap:8px;flex-wrap:wrap;">
               <button type="button" id="reg-yes-btn"
                 style="flex:1;min-width:110px;padding:8px 10px;border-radius:10px;border:none;cursor:pointer;
                        font-size:12px;font-weight:700;
                        background:linear-gradient(135deg,#3d1f8c,#5229b8);color:white;">
                 👤 ${t.form.yesAccount}
               </button>
               <button type="button" id="login-btn"
                 style="flex:1;min-width:90px;padding:8px 10px;border-radius:10px;cursor:pointer;
                        font-size:12px;font-weight:700;
                        border:1.5px solid #3d1f8c;background:white;color:#3d1f8c;">
                 🔑 ${t.form.loginBtn}
               </button>
               <button type="button" id="reg-no-btn"
                 style="flex:1;min-width:90px;padding:8px 10px;border-radius:10px;cursor:pointer;
                        font-size:12px;font-weight:500;
                        border:1.5px solid #e2e8f0;background:white;color:#94a3b8;">
                 ${t.form.noAccount}
               </button>
             </div>
           </div>
           <div id="user-block-wrap">
             <label style="font-size:13px;font-weight:600;color:#3d1f8c;display:block;margin-bottom:6px;">
               ${t.form.name} <span style="color:#94a3b8;font-weight:400;">${t.form.nameOptional}</span>
             </label>
             <input name="guest_name" type="text"
               style="width:100%;border:1.5px solid #ede8f8;border-radius:12px;
                      padding:9px 14px;font-size:13px;outline:none;transition:border-color .15s;
                      box-sizing:border-box;background:#faf9ff;"
               onfocus="this.style.borderColor='#3d1f8c'"
               onblur="this.style.borderColor='#ede8f8'"
               placeholder="${t.form.name}">
           </div>
         </div>`;

    const scoreCats = [['hygiene', t.toilet.hygiene], ['crowd', t.toilet.crowd],
                       ['location', t.toilet.locationLabel], ['facilities', t.toilet.facilities]];

    return `
      <div style="background:white;border-radius:22px;box-shadow:0 4px 24px rgba(61,31,140,.09);
                  border:1px solid #ede8f8;padding:24px;margin-bottom:16px;">
        <style>
          .star-label span { font-size:1.4rem; transition:all .1s; opacity:.22; display:block; line-height:1; cursor:pointer; user-select:none; }
          .star-row { display:flex; gap:2px; }
          @media(max-width:400px){ .star-label span { font-size:1.1rem; } .score-grid-item { padding:10px 8px !important; } }
        </style>
        <h2 style="font-size:1.05rem;font-weight:800;margin:0 0 20px;
                   background:linear-gradient(135deg,#3d1f8c,#003f8a);
                   -webkit-background-clip:text;-webkit-text-fill-color:transparent;
                   background-clip:text;">${t.form.title}</h2>

        <div id="pending-notice" style="display:none;background:#fffbeb;border:1px solid #fcd34d;
             border-radius:14px;padding:16px;margin-bottom:16px;">
          <div style="display:flex;align-items:flex-start;gap:12px;">
            <span style="font-size:24px;">⏳</span>
            <div>
              <div style="font-weight:700;color:#92400e;font-size:14px;">${t.form.pendingTitle}</div>
              <div style="color:#b45309;font-size:13px;margin-top:4px;">${t.form.pendingMsg}</div>
            </div>
          </div>
        </div>

        <div id="blocked-notice" style="display:none;background:#fef2f2;border:1px solid #fca5a5;
             border-radius:14px;padding:16px;margin-bottom:16px;">
          <div style="display:flex;align-items:flex-start;gap:12px;">
            <span style="font-size:24px;">🚫</span>
            <div>
              <div style="font-weight:700;color:#991b1b;font-size:14px;">${t.form.blockedTitle}</div>
              <div id="blocked-notice-msg" style="color:#b91c1c;font-size:13px;margin-top:4px;"></div>
            </div>
          </div>
        </div>

        <form id="review-form" enctype="multipart/form-data" style="display:flex;flex-direction:column;gap:16px;">
          <input type="hidden" name="toilet_id" value="${toiletId}">

          ${userBlock}

          <div>
            <label style="font-size:13px;font-weight:600;color:#3d1f8c;display:block;margin-bottom:6px;">${t.form.language}</label>
            <select id="review-lang-select" name="review_lang"
              style="border:1.5px solid #ede8f8;border-radius:12px;padding:9px 14px;
                     font-size:13px;outline:none;background:#faf9ff;cursor:pointer;min-width:180px;
                     color:#3d1f8c;font-weight:500;">
              <option value="en">🇬🇧 English</option>
              <option value="nl" ${lang === 'nl' ? 'selected' : ''}>🇳🇱 Nederlands</option>
              <option value="fr" ${lang === 'fr' ? 'selected' : ''}>🇫🇷 Français</option>
            </select>
          </div>

          <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
            ${scoreCats.map(([field, label]) => `
              <div class="score-grid-item"
                   style="background:linear-gradient(160deg,#faf9ff,#f3f0fb);border-radius:14px;
                          padding:12px 10px;border:1px solid #ede8f8;">
                <label style="font-size:11px;font-weight:600;color:#3d1f8c;display:block;
                              margin-bottom:6px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                  ${label} <span style="color:#ef4444;">*</span>
                </label>
                <div class="star-row">
                  ${[1,2,3,4,5].map(v => `
                    <label data-field="${field}" data-value="${v}" class="star-label"
                           style="cursor:pointer;flex:1;text-align:center;">
                      <input type="radio" name="${field}" value="${v}"
                             style="position:absolute;opacity:0;width:0;height:0;" required>
                      <span>⭐</span>
                    </label>`).join('')}
                </div>
              </div>`).join('')}
          </div>

          <div>
            <label style="font-size:13px;font-weight:600;color:#3d1f8c;display:block;margin-bottom:6px;">
              ${t.form.comment} <span style="color:#94a3b8;font-weight:400;">${t.form.commentMax}</span>
            </label>
            <textarea name="comment" rows="3" id="review-comment"
              style="width:100%;border:1.5px solid #ede8f8;border-radius:12px;
                     padding:10px 14px;font-size:13px;outline:none;resize:none;
                     transition:border-color .15s;box-sizing:border-box;background:#faf9ff;"
              onfocus="this.style.borderColor='#3d1f8c'"
              onblur="this.style.borderColor='#ede8f8'"
              placeholder="${t.form.commentPlaceholder}"></textarea>
            <div style="font-size:11px;color:#94a3b8;margin-top:4px;">
              <span id="word-count">0</span>/150 ${t.form.words}
            </div>
          </div>

          <div>
            <label style="font-size:13px;font-weight:600;color:#3d1f8c;display:block;margin-bottom:6px;">
              ${t.form.photos} <span style="color:#94a3b8;font-weight:400;">${t.form.photosMax}</span>
            </label>
            <input type="file" name="images[]" multiple accept="image/jpeg,image/png,image/webp"
                   id="img-input"
                   class="mt-1 text-sm text-slate-600 block w-full border border-slate-200
                          rounded-xl px-3 py-2 cursor-pointer
                          file:mr-3 file:py-1 file:px-3 file:rounded-full file:border-0
                          file:text-xs file:font-semibold file:bg-[#3d1f8c] file:text-white
                          hover:file:opacity-90">
            <p style="font-size:11px;color:#d97706;margin-top:6px;">${t.form.photosWarning}</p>
            <div id="img-preview" style="display:flex;gap:8px;flex-wrap:wrap;margin-top:8px;"></div>
          </div>

          <div id="recaptcha-widget"></div>

          <button type="submit" id="review-submit-btn"
            style="background:linear-gradient(135deg,#3d1f8c,#5229b8);color:white;
                   padding:13px 32px;border-radius:14px;font-weight:700;font-size:14px;
                   border:none;cursor:pointer;box-shadow:0 4px 16px rgba(61,31,140,.4);
                   transition:opacity .15s;width:100%;"
            onmouseover="this.style.opacity='.88'"
            onmouseout="this.style.opacity='1'">
            ${t.form.submit}
          </button>
          <div id="form-msg" style="font-size:13px;margin-top:4px;"></div>
        </form>
      </div>`;
  }

  // ── Registratie-modal ─────────────────────────────────────────
  function showRegisterModal() {
    const existing = document.getElementById('reg-modal');
    if (existing) existing.remove();

    const modal = document.createElement('div');
    modal.id = 'reg-modal';
    modal.style.cssText = 'position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.55);display:flex;align-items:center;justify-content:center;padding:16px;';
    modal.innerHTML = `
      <div style="background:white;border-radius:22px;padding:28px;width:100%;max-width:400px;
                  box-shadow:0 20px 60px rgba(0,0,0,.3);max-height:90vh;overflow-y:auto;">
        <h3 style="font-size:1.05rem;font-weight:800;margin:0 0 20px;
                   background:linear-gradient(135deg,#3d1f8c,#003f8a);
                   -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">
          👤 ${t.form.regTitle}
        </h3>
        <div style="display:flex;flex-direction:column;gap:10px;">
          <input id="rm-name" type="text" placeholder="${t.form.regName}"
                 style="border:1.5px solid #ede8f8;border-radius:12px;padding:10px 14px;font-size:13px;outline:none;width:100%;box-sizing:border-box;background:#faf9ff;">
          <input id="rm-username" type="text" placeholder="${t.form.regUsername}"
                 style="border:1.5px solid #ede8f8;border-radius:12px;padding:10px 14px;font-size:13px;outline:none;width:100%;box-sizing:border-box;background:#faf9ff;">
          <input id="rm-email" type="email" placeholder="${t.form.regEmail}"
                 style="border:1.5px solid #ede8f8;border-radius:12px;padding:10px 14px;font-size:13px;outline:none;width:100%;box-sizing:border-box;background:#faf9ff;">
          <input id="rm-pass" type="password" placeholder="${t.form.regPassword}"
                 style="border:1.5px solid #ede8f8;border-radius:12px;padding:10px 14px;font-size:13px;outline:none;width:100%;box-sizing:border-box;background:#faf9ff;">
          <div id="rm-msg" style="display:none;font-size:12px;border-radius:10px;padding:8px 12px;"></div>
          <button id="rm-submit"
                  style="background:linear-gradient(135deg,#3d1f8c,#5229b8);color:white;
                         padding:13px;border-radius:14px;font-weight:700;font-size:14px;
                         border:none;cursor:pointer;width:100%;">
            ${t.form.regSubmit}
          </button>
          <button id="rm-cancel"
                  style="background:none;border:none;color:#94a3b8;font-size:13px;cursor:pointer;padding:4px;">
            Annuleren / Cancel
          </button>
        </div>
      </div>`;

    document.body.appendChild(modal);

    document.getElementById('rm-cancel').onclick = () => modal.remove();
    modal.addEventListener('click', e => { if (e.target === modal) modal.remove(); });

    document.getElementById('rm-submit').onclick = async () => {
      const btn   = document.getElementById('rm-submit');
      const msg   = document.getElementById('rm-msg');
      const name  = document.getElementById('rm-name').value.trim();
      const uname = document.getElementById('rm-username').value.trim();
      const email = document.getElementById('rm-email').value.trim();
      const pass  = document.getElementById('rm-pass').value;

      const showErr = txt => {
        msg.textContent = txt;
        msg.style.cssText = 'display:block;background:#fef2f2;color:#b91c1c;font-size:12px;border-radius:10px;padding:8px 12px;';
      };

      if (!name || !uname || !email || !pass) { showErr('⚠️ Alle velden zijn verplicht.'); return; }
      if (pass.length < 8) { showErr('⚠️ Wachtwoord minimaal 8 tekens.'); return; }

      btn.disabled = true;
      btn.textContent = t.form.regSubmitting;

      try {
        const res = await fetch(`${API}/register.php`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': _csrfToken },
          body: JSON.stringify({ name, username: uname, email, password: pass }),
          credentials: 'include',
        });
        const data = await res.json();

        if (data.success) {
          localStorage.setItem('dlpwc_user', JSON.stringify({
            user_id: data.user_id, username: data.username, role: data.role,
          }));
          modal.remove();
          const regWrap = document.getElementById('reg-prompt-wrap');
          if (regWrap) regWrap.style.display = 'none';
          const userBlockWrap = document.getElementById('user-block-wrap');
          if (userBlockWrap) {
            userBlockWrap.innerHTML = `
              <div style="background:linear-gradient(135deg,rgba(61,31,140,.06),rgba(61,31,140,.03));
                           border:1px solid rgba(61,31,140,.15);border-radius:14px;
                           padding:12px 16px;font-size:13px;color:#3d1f8c;
                           display:flex;align-items:center;gap:10px;">
                <span style="font-size:20px;">👤</span>
                <span>${t.form.reviewingAs} <strong>${data.username}</strong></span>
              </div>`;
          }
        } else {
          showErr('⚠️ ' + (data.error ?? 'Registratie mislukt.'));
          btn.disabled = false;
          btn.textContent = t.form.regSubmit;
        }
      } catch {
        showErr('⚠️ Verbindingsfout.');
        btn.disabled = false;
        btn.textContent = t.form.regSubmit;
      }
    };
  }

  // ── Login-modal ───────────────────────────────────────────────
  function showLoginModal() {
    const existing = document.getElementById('login-modal');
    if (existing) existing.remove();

    const modal = document.createElement('div');
    modal.id = 'login-modal';
    modal.style.cssText = 'position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.55);display:flex;align-items:center;justify-content:center;padding:16px;';
    modal.innerHTML = `
      <div style="background:white;border-radius:22px;padding:28px;width:100%;max-width:380px;
                  box-shadow:0 20px 60px rgba(0,0,0,.3);">
        <h3 style="font-size:1.05rem;font-weight:800;margin:0 0 20px;
                   background:linear-gradient(135deg,#3d1f8c,#003f8a);
                   -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">
          🔑 ${t.form.loginTitle}
        </h3>
        <div style="display:flex;flex-direction:column;gap:10px;">
          <input id="lm-email" type="email" placeholder="${t.form.loginEmail}"
                 style="border:1.5px solid #ede8f8;border-radius:12px;padding:10px 14px;font-size:13px;outline:none;width:100%;box-sizing:border-box;background:#faf9ff;">
          <input id="lm-pass" type="password" placeholder="${t.form.loginPassword}"
                 style="border:1.5px solid #ede8f8;border-radius:12px;padding:10px 14px;font-size:13px;outline:none;width:100%;box-sizing:border-box;background:#faf9ff;">
          <div id="lm-msg" style="display:none;font-size:12px;border-radius:10px;padding:8px 12px;"></div>
          <button id="lm-submit"
                  style="background:linear-gradient(135deg,#3d1f8c,#5229b8);color:white;
                         padding:13px;border-radius:14px;font-weight:700;font-size:14px;
                         border:none;cursor:pointer;width:100%;">
            ${t.form.loginSubmit}
          </button>
          <button id="lm-cancel"
                  style="background:none;border:none;color:#94a3b8;font-size:13px;cursor:pointer;padding:4px;">
            Annuleren / Cancel
          </button>
        </div>
      </div>`;

    document.body.appendChild(modal);
    document.getElementById('lm-email').focus();

    document.getElementById('lm-cancel').onclick = () => modal.remove();
    modal.addEventListener('click', e => { if (e.target === modal) modal.remove(); });

    document.getElementById('lm-submit').onclick = async () => {
      const btn   = document.getElementById('lm-submit');
      const msg   = document.getElementById('lm-msg');
      const email = document.getElementById('lm-email').value.trim();
      const pass  = document.getElementById('lm-pass').value;

      const showErr = txt => {
        msg.textContent = txt;
        msg.style.cssText = 'display:block;background:#fef2f2;color:#b91c1c;font-size:12px;border-radius:10px;padding:8px 12px;';
      };

      if (!email || !pass) { showErr('⚠️ Vul beide velden in.'); return; }

      btn.disabled = true;
      btn.textContent = t.form.loginSubmitting;

      try {
        const res  = await fetch(`${API}/login.php`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': _csrfToken },
          body: JSON.stringify({ email, password: pass }),
          credentials: 'include',
        });
        const data = await res.json();

        if (data.success) {
          localStorage.setItem('dlpwc_user', JSON.stringify({
            user_id: data.user_id, username: data.username, role: data.role,
          }));
          modal.remove();
          const regWrap = document.getElementById('reg-prompt-wrap');
          if (regWrap) regWrap.style.display = 'none';
          const userBlockWrap = document.getElementById('user-block-wrap');
          if (userBlockWrap) {
            userBlockWrap.innerHTML = `
              <div style="background:linear-gradient(135deg,rgba(61,31,140,.06),rgba(61,31,140,.03));
                           border:1px solid rgba(61,31,140,.15);border-radius:14px;
                           padding:12px 16px;font-size:13px;color:#3d1f8c;
                           display:flex;align-items:center;gap:10px;">
                <span style="font-size:20px;">👤</span>
                <span>${t.form.reviewingAs} <strong>${data.username}</strong></span>
              </div>`;
          }
        } else {
          showErr(t.form.loginError);
          btn.disabled = false;
          btn.textContent = t.form.loginSubmit;
        }
      } catch {
        showErr('⚠️ Verbindingsfout.');
        btn.disabled = false;
        btn.textContent = t.form.loginSubmit;
      }
    };

    // Enter-toets in wachtwoordveld
    document.getElementById('lm-pass').addEventListener('keydown', e => {
      if (e.key === 'Enter') document.getElementById('lm-submit').click();
    });
  }

  // ── Review-formulier initialiseren ────────────────────────────
  async function initReviewForm(toiletId, reloadReviews) {
    // reCAPTCHA laden indien geconfigureerd
    let _rcWidgetId = null;
    let _rcSiteKey  = '';
    try {
      const cfg = await fetch(`${API}/get-public-config.php`).then(r => r.json());
      _rcSiteKey = cfg.recaptcha_site_key || '';
    } catch (_) {}

    if (_rcSiteKey) {
      window.dlpwcRcReady = () => {
        const el = document.getElementById('recaptcha-widget');
        if (el) _rcWidgetId = grecaptcha.render(el, { sitekey: _rcSiteKey });
      };
      const s = document.createElement('script');
      s.src = `https://www.google.com/recaptcha/api.js?render=explicit&onload=dlpwcRcReady&hl=${lang}`;
      document.head.appendChild(s);
    }

    // Register/login-prompt knoppen
    document.getElementById('reg-yes-btn')?.addEventListener('click', () => showRegisterModal());
    document.getElementById('login-btn')?.addEventListener('click',   () => showLoginModal());
    document.getElementById('reg-no-btn')?.addEventListener('click', () => {
      const wrap = document.getElementById('reg-prompt-wrap');
      if (wrap) wrap.style.display = 'none';
    });

    // Sterren-klik
    document.querySelectorAll('.star-label').forEach(lbl => {
      lbl.addEventListener('click', () => {
        const field = lbl.dataset.field;
        const val   = +lbl.dataset.value;
        document.querySelectorAll(`.star-label[data-field="${field}"] span`).forEach((sp, i) => {
          sp.style.opacity   = i < val ? '1' : '0.22';
          sp.style.transform = i < val ? 'scale(1.1)' : 'scale(1)';
        });
      });
    });

    // Foto-preview
    document.getElementById('img-input')?.addEventListener('change', e => {
      const files = Array.from(e.target.files ?? []).slice(0, 3);
      const prev  = document.getElementById('img-preview');
      prev.innerHTML = '';
      files.forEach(f => {
        const reader = new FileReader();
        reader.onload = ev => {
          const wrap = document.createElement('div');
          wrap.style.cssText = 'width:64px;height:64px;border-radius:8px;overflow:hidden;border:2px solid #003f8a;flex-shrink:0;';
          const img = document.createElement('img');
          img.src = ev.target.result;
          img.style.cssText = 'width:100%;height:100%;object-fit:cover;';
          wrap.appendChild(img); prev.appendChild(wrap);
        };
        reader.readAsDataURL(f);
      });
    });

    // Woordenteller
    document.getElementById('review-comment')?.addEventListener('input', e => {
      const words = e.target.value.trim().split(/\s+/).filter(Boolean).length;
      const el    = document.getElementById('word-count');
      if (el) { el.textContent = words; el.style.color = words > 150 ? '#ef4444' : '#94a3b8'; }
    });

    // Blokkeer-/pending-check
    const fp = getFingerprint();
    fetch(`${API}/check-pending.php?toilet_id=${toiletId}&fingerprint=${encodeURIComponent(fp)}`, { credentials: 'include' })
      .then(r => r.json()).then(data => {
        if (data.blocked) {
          document.getElementById('review-form').style.display = 'none';
          document.getElementById('blocked-notice').style.display = 'block';
          const msg = document.getElementById('blocked-notice-msg');
          if (msg) msg.textContent = data.blocked_until
            ? `${t.form.blockedUntil} ${new Date(data.blocked_until).toLocaleDateString(lang, { day: 'numeric', month: 'long', year: 'numeric' })}.`
            : t.form.blockedPerm;
        } else if (data.pending) {
          document.getElementById('review-form').style.display = 'none';
          document.getElementById('pending-notice').style.display = 'block';
        }
      }).catch(() => {});

    // Formulier verzenden
    document.getElementById('review-form')?.addEventListener('submit', async e => {
      e.preventDefault();
      const form    = e.target;
      const msgEl   = document.getElementById('form-msg');
      const btn     = document.getElementById('review-submit-btn');
      const comment = form.querySelector('[name="comment"]')?.value ?? '';
      const langSel = document.getElementById('review-lang-select')?.value ?? lang;

      if (containsLink(comment)) {
        msgEl.textContent = t.form.errorLink; msgEl.style.color = '#ef4444'; return;
      }
      if (containsBannedWord(comment)) {
        msgEl.textContent = t.form.errorBanned; msgEl.style.color = '#ef4444'; return;
      }
      const wordCount = comment.trim().split(/\s+/).filter(Boolean).length;
      if (wordCount > 150) {
        msgEl.textContent = t.form.errorWords; msgEl.style.color = '#ef4444'; return;
      }

      // reCAPTCHA
      if (_rcSiteKey) {
        if (_rcWidgetId === null) {
          msgEl.textContent = t.form.errorRecaptcha; msgEl.style.color = '#ef4444'; return;
        }
        const rcToken = grecaptcha.getResponse(_rcWidgetId);
        if (!rcToken) {
          msgEl.textContent = t.form.errorRecaptcha; msgEl.style.color = '#ef4444'; return;
        }
      }

      btn.disabled    = true;
      btn.textContent = t.form.submitting;
      msgEl.textContent = '';

      const fd = new FormData(form);
      if (_rcSiteKey && _rcWidgetId !== null) {
        fd.append('recaptcha_token', grecaptcha.getResponse(_rcWidgetId));
      }
      fd.append('fingerprint', fp);
      fd.append('review_lang', langSel);

      try {
        const res = await _fetch(`${API}/add-review.php`, { method: 'POST', body: fd });
        if (res.success) {
          if (_rcSiteKey && _rcWidgetId !== null) grecaptcha.reset(_rcWidgetId);
          if (res.status === 'approved') {
            msgEl.textContent = t.form.success; msgEl.style.color = '#16a34a';
            form.reset();
            document.getElementById('img-preview').innerHTML = '';
            document.querySelectorAll('.star-label span').forEach(s => { s.style.opacity = '0.22'; s.style.transform = 'scale(1)'; });
            document.getElementById('word-count').textContent = '0';
            setTimeout(reloadReviews, 1000);
          } else {
            form.style.display = 'none';
            document.getElementById('pending-notice').style.display = 'block';
          }
        } else if (res.blocked) {
          form.style.display = 'none';
          document.getElementById('blocked-notice').style.display = 'block';
          const msg = document.getElementById('blocked-notice-msg');
          if (msg) msg.textContent = res.error ?? t.form.blockedPerm;
        } else {
          msgEl.textContent = '⚠️ ' + (res.error ?? t.form.errorGeneric);
          msgEl.style.color = '#ef4444';
        }
      } catch {
        msgEl.textContent = t.form.errorConnection; msgEl.style.color = '#ef4444';
      } finally {
        btn.disabled    = false;
        btn.textContent = t.form.submit;
      }
    });
  }

  // ── Lightbox ──────────────────────────────────────────────────
  let _lbImages = [], _lbIdx = 0;

  window.openLightbox = function (images, startIdx) {
    const lb = document.getElementById('lightbox');
    if (lb && lb.parentNode !== document.body) document.body.appendChild(lb);
    _lbImages = images;
    _lbIdx    = startIdx;
    _lbShow();
    lb.style.display = 'flex';
    document.body.style.overflow = 'hidden';
  };

  function _lbShow() {
    const img  = document.getElementById('lightbox-img');
    const cap  = document.getElementById('lightbox-caption');
    if (!img || !_lbImages.length) return;
    img.src = _lbImages[_lbIdx].src;
    if (cap) cap.textContent = _lbImages[_lbIdx].caption ?? '';
    document.getElementById('lightbox-prev').style.opacity = _lbIdx > 0 ? '1' : '0.2';
    document.getElementById('lightbox-next').style.opacity = _lbIdx < _lbImages.length - 1 ? '1' : '0.2';
  }

  function _lbClose() {
    document.getElementById('lightbox').style.display = 'none';
    document.body.style.overflow = '';
  }

  function initLightbox() {
    document.getElementById('lightbox-close')?.addEventListener('click', _lbClose);
    document.getElementById('lightbox')?.addEventListener('click', e => { if (e.target === e.currentTarget) _lbClose(); });
    document.getElementById('lightbox-prev')?.addEventListener('click', e => { e.stopPropagation(); if (_lbIdx > 0) { _lbIdx--; _lbShow(); } });
    document.getElementById('lightbox-next')?.addEventListener('click', e => { e.stopPropagation(); if (_lbIdx < _lbImages.length - 1) { _lbIdx++; _lbShow(); } });
    document.addEventListener('keydown', e => {
      if (document.getElementById('lightbox')?.style.display === 'none') return;
      if (e.key === 'Escape') _lbClose();
      if (e.key === 'ArrowLeft'  && _lbIdx > 0) { _lbIdx--; _lbShow(); }
      if (e.key === 'ArrowRight' && _lbIdx < _lbImages.length - 1) { _lbIdx++; _lbShow(); }
    });
    document.addEventListener('click', e => {
      const trigger = e.target.closest('.lb-trigger');
      if (!trigger) return;
      try {
        const images = JSON.parse(trigger.dataset.images);
        const idx    = parseInt(trigger.dataset.lbIdx ?? '0', 10);
        window.openLightbox(images, idx);
      } catch (_) {}
    });
  }

  // ── Hoofd-render ──────────────────────────────────────────────
  async function init() {
    const pageEl   = document.getElementById('toilet-page');
    const toiletId = pageEl?.dataset.id;
    if (!toiletId) return;

    initLightbox();

    // Vertalingen ophalen
    try {
      const apiTrans = await _fetch(`${API}/get-translations.php?lang=${lang}`);
      t = {
        toilet:       Object.assign({}, t.toilet,       apiTrans.toilet       || {}),
        form:         Object.assign({}, t.form,         apiTrans.form         || {}),
        reviewFilter: Object.assign({}, t.reviewFilter, apiTrans.reviewFilter || {}),
      };
    } catch (_) {}

    // Data laden
    let data;
    try {
      data = await _fetch(`${API}/get-toilet.php?id=${toiletId}`);
    } catch (err) {
      document.getElementById('loading').innerHTML =
        `<div style="color:#ef4444;font-size:13px;padding:20px 0;">⚠️ ${err.message}</div>`;
      return;
    }

    document.getElementById('loading').classList.add('hidden');
    const content = document.getElementById('content');
    content.classList.remove('hidden');

    const toilet    = data.toilet;
    const subscores = data.subscores ?? {};
    const reviews   = data.reviews   ?? [];
    const featured  = data.featured  ?? null;
    const lat       = parseFloat(toilet.latitude);
    const lng       = parseFloat(toilet.longitude);
    const color     = scoreColor(toilet.score);
    const areaLabel = (AREA_LABELS[lang] || AREA_LABELS.en)[toilet.area] ?? toilet.area;
    const cnt       = toilet.review_count ?? 0;
    const cntLabel  = cnt !== 1 ? t.toilet.ratings : t.toilet.rating;

    // Alle afbeeldingen: redactioneel + reviews
    const editPhoto = toilet.editorial_photo
      ? `/uploads/editorial/${toilet.editorial_photo}`
      : 'https://www.looopings.nl/img/foto/041116proper1.jpg';
    const galleryImages = [{ src: editPhoto, caption: 'Redactie' }];
    reviews.forEach(r => {
      _parseJson(r.images_json, []).forEach(f =>
        galleryImages.push({ src: `/uploads/reviews/${f}`, caption: `📷 ${r.username ?? r.guest_name ?? 'Guest'}` })
      );
    });

    // Score-kaartjes
    const scoreCats = [
      ['hygiene',   t.toilet.hygiene],
      ['crowd',     t.toilet.crowd],
      ['location',  t.toilet.locationLabel],
      ['facilities',t.toilet.facilities],
    ];
    const scoreTiles = scoreCats.map(([key, label]) => {
      const val = subscores['avg_' + key] ? parseFloat(subscores['avg_' + key]) : null;
      const col = scoreColor(val);
      return `
        <div style="background:linear-gradient(160deg,#faf9ff,#f3f0fb);border-radius:14px;
                    padding:12px 6px;text-align:center;border:1px solid #ede8f8;
                    box-shadow:0 1px 4px rgba(61,31,140,.06);">
          <div style="font-size:9px;text-transform:uppercase;letter-spacing:.5px;
                      color:#94a3b8;margin-bottom:6px;font-weight:600;">${label}</div>
          <div style="font-size:1.4rem;font-weight:800;color:${col};line-height:1;">${val ? val.toFixed(1) : '—'}</div>
          <div style="color:#f5a800;font-size:11px;margin-top:2px;">
            ${val ? '★'.repeat(Math.round(val)) + '☆'.repeat(5 - Math.round(val)) : ''}
          </div>
        </div>`;
    }).join('');

    // Taalfilter voor reviews
    const reviewLangs = [...new Set(reviews.map(r => r.review_lang).filter(Boolean))];
    const langFilterHtml = reviewLangs.length > 1 ? `
      <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:16px;" id="review-lang-filter">
        <button class="rev-lang-btn" data-lang=""
          style="font-size:11px;padding:5px 14px;border-radius:99px;font-weight:600;border:none;cursor:pointer;
                 background:linear-gradient(135deg,#3d1f8c,#5229b8);color:white;
                 box-shadow:0 2px 8px rgba(61,31,140,.3);">
          ${t.reviewFilter.all}
        </button>
        ${reviewLangs.map(l => {
          const info = LANG_FLAGS[l] ?? { flag: '🌐', label: l };
          return `<button class="rev-lang-btn" data-lang="${l}"
            style="font-size:11px;padding:5px 14px;border-radius:99px;font-weight:600;
                   border:1px solid #e2e8f0;cursor:pointer;background:white;color:#475569;
                   display:inline-flex;align-items:center;gap:5px;">
            ${info.flag} ${info.label}
          </button>`;
        }).join('')}
      </div>` : '';

    // Ingelogde gebruiker
    const user = JSON.parse(localStorage.getItem('dlpwc_user') || 'null');

    content.innerHTML = `
      <a href="/" style="display:inline-flex;align-items:center;gap:6px;color:#3d1f8c;
         font-size:13px;font-weight:600;text-decoration:none;margin-bottom:16px;
         padding:6px 16px;border-radius:99px;background:white;border:1.5px solid #e2e8f0;
         box-shadow:0 1px 4px rgba(61,31,140,.08);transition:all .15s;"
         onmouseover="this.style.borderColor='#3d1f8c';this.style.boxShadow='0 3px 10px rgba(61,31,140,.15)'"
         onmouseout="this.style.borderColor='#e2e8f0';this.style.boxShadow='0 1px 4px rgba(61,31,140,.08)'">
        ${t.toilet.back}
      </a>

      ${buildGallery(galleryImages)}

      <!-- Header -->
      <div style="background:white;border-radius:22px;box-shadow:0 4px 24px rgba(61,31,140,.09);
                  border:1px solid #ede8f8;padding:20px;margin-bottom:16px;">
        <div style="display:flex;align-items:flex-start;gap:14px;flex-wrap:wrap;">
          <div style="width:54px;height:54px;border-radius:50%;flex-shrink:0;
                      background:linear-gradient(135deg,#3d1f8c,#5229b8);border:3px solid white;
                      box-shadow:0 4px 16px rgba(61,31,140,.35);
                      display:flex;align-items:center;justify-content:center;font-size:24px;">🚽</div>
          <div style="flex:1;min-width:0;">
            <h1 style="font-size:1.35rem;font-weight:800;margin:0 0 6px;line-height:1.2;
                       background:linear-gradient(135deg,#3d1f8c,#003f8a);
                       -webkit-background-clip:text;-webkit-text-fill-color:transparent;
                       background-clip:text;">${toilet.name}</h1>
            <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
              <span style="font-size:11px;color:#3d1f8c;padding:3px 10px;border-radius:99px;
                           font-weight:600;background:rgba(61,31,140,.08);
                           border:1px solid rgba(61,31,140,.15);">${areaLabel}</span>
              <span style="font-size:11px;color:#94a3b8;">${cnt} ${cntLabel}</span>
            </div>
          </div>
          <div style="text-align:right;flex-shrink:0;">
            ${toilet.score
              ? `<span style="color:${color};font-size:2.6rem;font-weight:800;line-height:1;
                               text-shadow:0 2px 8px ${color}44;">${parseFloat(toilet.score).toFixed(1)}</span>
                 <span style="color:#94a3b8;font-size:1rem;">/5</span>`
              : `<span style="color:#94a3b8;font-size:13px;">${t.toilet.notRated}</span>`}
            <div style="margin-top:2px;">${starHtml(toilet.score)}</div>
          </div>
        </div>
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:8px;margin-top:18px;">
          ${scoreTiles}
        </div>
      </div>

      <!-- Mini-kaart -->
      <div style="background:white;border-radius:22px;box-shadow:0 4px 24px rgba(61,31,140,.09);
                  border:1px solid #ede8f8;margin-bottom:16px;overflow:hidden;">
        <div style="padding:14px 18px 10px;display:flex;align-items:center;justify-content:space-between;">
          <h2 style="font-size:14px;font-weight:700;color:#3d1f8c;margin:0;">${t.toilet.location}</h2>
          <a href="https://www.google.com/maps?q=${lat},${lng}" target="_blank"
             style="font-size:11px;color:#3d1f8c;text-decoration:none;font-weight:600;
                    border:1.5px solid #3d1f8c;padding:3px 12px;border-radius:99px;transition:all .15s;"
             onmouseover="this.style.background='#3d1f8c';this.style.color='white'"
             onmouseout="this.style.background='transparent';this.style.color='#3d1f8c'">
            ${t.toilet.openMaps} ↗
          </a>
        </div>
        <div id="mini-map" style="height:180px;cursor:pointer;"></div>
        <div style="padding:8px 18px 12px;font-size:11px;color:#94a3b8;">${lat.toFixed(5)}, ${lng.toFixed(5)}</div>
      </div>

      ${featured ? `
        <div style="background:linear-gradient(135deg,rgba(61,31,140,.04),rgba(245,168,0,.08));
                    border:1px solid rgba(245,168,0,.3);border-radius:20px;
                    padding:20px;margin-bottom:16px;position:relative;overflow:hidden;">
          <div style="position:absolute;top:-8px;right:14px;font-size:64px;opacity:.06;
                      color:#3d1f8c;line-height:1;font-family:serif;">"</div>
          <div style="font-size:10px;font-weight:700;color:#f5a800;text-transform:uppercase;
                      letter-spacing:.8px;margin-bottom:10px;display:flex;align-items:center;gap:6px;">
            <span style="width:18px;height:2px;background:#f5a800;border-radius:99px;display:inline-block;"></span>
            ${t.toilet.featured}
            <span style="width:18px;height:2px;background:#f5a800;border-radius:99px;display:inline-block;"></span>
          </div>
          <p style="font-size:13px;color:#374151;font-style:italic;line-height:1.7;margin:0 0 12px;">
            "${featured.comment ?? ''}"
          </p>
          <p style="font-size:11px;color:#94a3b8;margin:0;font-weight:600;">— ${featured.username ?? 'Admin'}</p>
        </div>` : ''}

      <!-- Reviews -->
      <div style="display:flex;align-items:center;justify-content:space-between;
                  margin-bottom:14px;flex-wrap:wrap;gap:8px;">
        <h2 style="font-size:1.1rem;font-weight:800;margin:0;
                   background:linear-gradient(135deg,#3d1f8c,#003f8a);
                   -webkit-background-clip:text;-webkit-text-fill-color:transparent;
                   background-clip:text;">
          ${t.toilet.reviews}
          <span style="font-size:13px;font-weight:600;color:#94a3b8;
                       -webkit-text-fill-color:#94a3b8;margin-left:6px;">(${reviews.length})</span>
        </h2>
      </div>

      ${langFilterHtml}
      <div id="reviews-list" style="display:flex;flex-direction:column;gap:10px;margin-bottom:24px;">
        ${buildReviewsHtml(reviews)}
      </div>

      ${buildReviewForm(toiletId, !!user, user?.username ?? user?.name ?? '')}

      <!-- In de buurt -->
      <div style="background:white;border-radius:22px;box-shadow:0 4px 24px rgba(61,31,140,.09);
                  border:1px solid #ede8f8;padding:20px;">
        <h2 style="font-size:1.05rem;font-weight:800;margin:0 0 14px;
                   background:linear-gradient(135deg,#3d1f8c,#003f8a);
                   -webkit-background-clip:text;-webkit-text-fill-color:transparent;
                   background-clip:text;">${t.toilet.nearby}</h2>
        <div id="nearby-section" style="display:flex;flex-direction:column;gap:8px;">
          <div style="color:#94a3b8;font-size:13px;">${t.toilet.nearbyLoading}</div>
        </div>
      </div>`;

    // Initialisaties na DOM-inject
    initGalleryControls();
    if (!isNaN(lat) && !isNaN(lng)) initMiniMap(lat, lng, toilet.name);
    initReviewForm(toiletId, async () => {
      const fresh = await _fetch(`${API}/get-toilet.php?id=${toiletId}`);
      document.getElementById('reviews-list').innerHTML = buildReviewsHtml(fresh.reviews ?? []);
    });
    if (!isNaN(lat) && !isNaN(lng)) loadNearby(toiletId, lat, lng);

    // Taalfilter-knoppen
    document.querySelectorAll('.rev-lang-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const filterLang = btn.dataset.lang;
        document.querySelectorAll('.rev-lang-btn').forEach(b => {
          const active = b === btn;
          b.style.background = active ? 'linear-gradient(135deg,#3d1f8c,#5229b8)' : 'white';
          b.style.color      = active ? 'white' : '#475569';
          b.style.boxShadow  = active ? '0 2px 8px rgba(61,31,140,.3)' : 'none';
          b.style.border     = active ? 'none' : '1px solid #e2e8f0';
        });
        const filtered = filterLang ? reviews.filter(r => r.review_lang === filterLang) : reviews;
        document.getElementById('reviews-list').innerHTML = buildReviewsHtml(filtered);
      });
    });
  }

  // ── CSRF token ────────────────────────────────────────────────
  let _csrfToken = '';
  fetch('/api/get-csrf.php', { credentials: 'include' })
    .then(r => r.json()).then(d => { _csrfToken = d.csrf_token || ''; });

  // ── Fetch-hulpfunctie ─────────────────────────────────────────
  async function _fetch(url, opts = {}) {
    const headers = { 'X-CSRF-Token': _csrfToken, ...(opts.headers || {}) };
    const res = await fetch(url, { credentials: 'include', ...opts, headers });
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    return res.json();
  }

  function _parseJson(str, fallback) {
    try { return JSON.parse(str || '[]'); } catch { return fallback; }
  }

  // ── Start ─────────────────────────────────────────────────────
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
