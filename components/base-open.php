<?php
/**
 * Base layout — OPEN gedeelte (equivalent van Base.astro begin).
 * Include dit bovenaan elke pagina, vóór de pagina-inhoud.
 * Verwacht: $pageTitle (string)
 *
 * Sluit af met components/base-close.php
 */
$pageTitle = $pageTitle ?? 'DLP WC';
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars(get_lang()) ?>">
<head>
  <?php include __DIR__ . '/head.php'; ?>
</head>
<body class="min-h-screen page-enter" style="background:#f8f7fc;">

<?php include __DIR__ . '/magic-canvas.php'; ?>
<?php include __DIR__ . '/header.php'; ?>

<main>
