# Security Audit Report — DLPWC-V3
**Datum:** 2026-05-01  
**Scope:** Volledige codebase (PHP API, admin panel, front-end)  
**Auditor:** Claude Sonnet 4.6

---

## Samenvatting

**Algemeen oordeel: GOED** — De codebase toont solide security-praktijken. Geen kritieke kwetsbaarheden (SQL injection, hardcoded credentials) gevonden. Drie low/medium issues gecorrigeerd.

---

## Fixes toegepast

### Fix 1 — `md5()` vervangen door `sha256` in rate limiter
- **Bestand:** `api/rate-limit.php` regel 8
- **Ernst:** Laag
- **Probleem:** `md5()` is een cryptografisch zwakke hash en niet geschikt voor security-gerelateerde operaties.
- **Fix:** Vervangen door `hash('sha256', $ip)`.
- **Status:** ✅ Gefixed

### Fix 2 — .htaccess bescherming voor upload directories
- **Bestanden:** `api/update-avatar.php`, `api/add-review.php`, `api/admin/set-editorial-photo.php`
- **Ernst:** Medium
- **Probleem:** Upload directories (`uploads/avatars/`, `uploads/reviews/`, `uploads/editorial/`) worden aangemaakt zonder bescherming tegen PHP-uitvoering. Een aanvaller die een PHP-bestand uploadt (bij gebrekkige MIME-validatie) kan code uitvoeren.
- **Fix:** Bij aanmaken van de directory wordt automatisch een `.htaccess` geplaatst die PHP-uitvoering en directory listing blokkeert.
- **Status:** ✅ Gefixed

### Fix 3 — Content-Security-Policy header toegevoegd
- **Bestand:** `api/db.php` regel 27
- **Ernst:** Laag
- **Probleem:** API-responses misten een CSP-header. Omdat alle API-endpoints JSON teruggeven (geen HTML), is `default-src 'none'` geschikt en veilig.
- **Fix:** `header("Content-Security-Policy: default-src 'none'");` toegevoegd.
- **Status:** ✅ Gefixed

---

## Bevindingen zonder actie vereist (positief)

| Categorie | Status |
|---|---|
| SQL injection | ✅ Alle queries gebruiken PDO prepared statements |
| Hardcoded credentials | ✅ Geen — alles via `.env` (correct in `.gitignore`) |
| CSRF bescherming | ✅ `bin2hex(random_bytes(32))`, gecontroleerd met `hash_equals()` |
| Session hardening | ✅ HttpOnly, Secure, SameSite=Strict |
| Wachtwoord hashing | ✅ `password_hash()` met `PASSWORD_BCRYPT`, cost=12 |
| Bestandsupload validatie | ✅ MIME-type via `finfo`, niet alleen extensie |
| Authorisatie | ✅ Role-based: `isAdmin()`, `isModerator()`, `isLoggedIn()` |
| Rate limiting | ✅ Login (5/15min), registratie (3/uur), reviews (10/uur) |
| CORS | ✅ Whitelist-only, geen wildcard |
| Output escaping | ✅ `htmlspecialchars()` consistent toegepast |
| Informatielekken | ✅ Generieke foutmeldingen, geen stack traces |

---

## Aanbevelingen (niet gefixed — vereist config-aanpassing)

### A. reCAPTCHA secret en Brevo API-sleutel in database
- **Ernst:** Medium
- **Locatie:** `api/add-review.php` (reCAPTCHA), `api/admin/save-smtp-settings.php` (Brevo)
- **Probleem:** API-sleutels worden in de database opgeslagen in plaats van in `.env`. Bij een database-compromis zijn de sleutels direct blootgesteld.
- **Aanbeveling:** Verplaats naar `.env` als de applicatie dat toelaat. Als het admin-configureerbaar moet blijven, overweeg encryptie-at-rest van de sleutelwaarden in de database.
- **Niet gefixed** — Dit is een architectuurkeuze die overleg vereist.

---

## Conclusie

De codebase is goed beveiligd. De drie toegepaste fixes adresseren low/medium issues rondom crypto-algoritmen en upload-directory hardening. Er zijn geen SQL injection, XSS, of authenticatie-bypass kwetsbaarheden gevonden.
