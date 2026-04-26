<?php
/**
 * BaseHead component — equivalent van BaseHead.astro
 * Verwacht: $pageTitle (string)
 */
$pageTitle = $pageTitle ?? 'DLP WC';
?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($pageTitle) ?> — DLP WC</title>

<!-- CSS-variabelen vanuit database (kleuren/thema) -->
<link rel="stylesheet" href="/api/admin/get-css-vars.php">

<!-- Tailwind utility-classes (gecompileerd) -->
<link rel="stylesheet" href="/assets/css/tailwind.css">

<!-- DLP-project eigen stijlen -->
<link rel="stylesheet" href="/assets/css/custom.css">

<!-- Flag-icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flag-icons@7.2.3/css/flag-icons.min.css">
