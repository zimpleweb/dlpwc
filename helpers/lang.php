<?php
/**
 * Taal-helper — zelfde logica als getLangFromCookies() in Astro.
 * Haalt taal op uit cookie 'lang', fallback naar 'en'.
 */

function get_lang(): string {
    $allowed = ['en', 'nl', 'fr'];
    $lang = $_COOKIE['lang'] ?? 'en';
    return in_array($lang, $allowed, true) ? $lang : 'en';
}

/**
 * Vertaal-helper: geeft label terug op basis van taal.
 * $labels = ['en' => [...], 'nl' => [...], 'fr' => [...]]
 * $key    = 'submit' (optioneel, anders het hele array)
 */
function t(array $labels, string $lang, string $key = ''): mixed {
    $set = $labels[$lang] ?? $labels['en'];
    if ($key === '') return $set;
    return $set[$key] ?? ($labels['en'][$key] ?? '');
}
