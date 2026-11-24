<?php
/** @var string $page @var string $content */
$l = lang();
[$title, $desc] = page_meta($page);
$known = $page !== 'notfound';
$canonical = abs_url(route_url($known ? $page : 'home'));
$noindex = !config('seo.indexable') || !$known;
$other = $l === 'es' ? 'en' : 'es';
?>
<!doctype html>
<html lang="<?= e($l) ?>">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title) ?></title>
<meta name="description" content="<?= e($desc) ?>">
<?php if ($noindex): ?>
<meta name="robots" content="noindex, nofollow">
<?php endif; ?>
<link rel="canonical" href="<?= e($canonical) ?>">
<?php if ($known): foreach (LANGS as $alt): ?>
<link rel="alternate" hreflang="<?= $alt ?>" href="<?= e(abs_url(route_url($page, $alt))) ?>">
<?php endforeach; ?>
<link rel="alternate" hreflang="x-default" href="<?= e(abs_url(route_url($page, DEFAULT_LANG))) ?>">
<?php endif; ?>
<meta property="og:type" content="website">
<meta property="og:site_name" content="<?= e(config('site.name')) ?>">
<meta property="og:title" content="<?= e($title) ?>">
<meta property="og:description" content="<?= e($desc) ?>">
<meta property="og:url" content="<?= e($canonical) ?>">
<meta property="og:image" content="<?= e(abs_url('/assets/img/slider/1.jpg')) ?>">
<link rel="stylesheet" href="/assets/css/main.css">
<script type="application/ld+json"><?= json_encode(schema_hotel(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) ?></script>
</head>
<body>
<a class="skip" href="#main"><?= e(t('skip')) ?></a>
<header class="site-header">
  <div class="wrap bar">
    <a class="brand" href="<?= e(route_url('home')) ?>"><?= e(config('site.name')) ?></a>
    <button class="menu-btn" type="button" data-menu-btn aria-expanded="false" aria-controls="nav"><?= e(t('nav.menu')) ?></button>
    <nav class="nav" id="nav" data-nav aria-label="<?= e(t('nav.menu')) ?>">
      <ul>
        <?php foreach (routes()['pages'] as $id => $p): if (!$p['nav']) continue; ?>
        <li><a href="<?= e(route_url($id)) ?>"<?= $id === $page ? ' aria-current="page"' : '' ?>><?= e(t("nav.$id")) ?></a></li>
        <?php endforeach; ?>
        <?php if ($known): ?>
        <li><a class="lang" href="<?= e(route_url($page, $other)) ?>" hreflang="<?= $other ?>" lang="<?= $other ?>" aria-label="<?= e(t('nav.lang_label')) ?>"><?= e(t('nav.lang_name')) ?></a></li>
        <?php endif; ?>
      </ul>
    </nav>
  </div>
</header>
<main id="main"><?= $content ?></main>
<footer class="site-footer">
  <div class="wrap foot">
    <div>
      <strong><?= e(config('site.name')) ?></strong>
      <address>
        <?= e(config('address.street')) ?><br>
        <?= e(config('address.city')) ?>, <?= e(config('address.region')) ?>, <?= e(config('address.country')) ?> (<?= e(config('address.zip')) ?>)
      </address>
    </div>
    <div>
      <a href="tel:<?= e(config('contact.phone')) ?>"><?= e(config('contact.phone_display')) ?></a><br>
      <a href="<?= e(whatsapp_link()) ?>" data-track="whatsapp_footer">WhatsApp</a><br>
      <a href="mailto:<?= e(config('contact.email')) ?>"><?= e(config('contact.email')) ?></a>
    </div>
    <p>&copy; <?= date('Y') ?> <?= e(config('site.name')) ?></p>
  </div>
</footer>
<script src="/assets/js/app.js" defer></script>
</body>
</html>
