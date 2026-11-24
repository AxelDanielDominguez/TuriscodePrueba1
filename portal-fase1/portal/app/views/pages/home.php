<section class="hero">
  <?= img('slider/1.jpg', t('alt.hero'), true) ?>
  <div class="wrap hero-body">
    <h1><?= e(t('home.title')) ?></h1>
    <p><?= e(t('home.subtitle')) ?></p>
    <p class="actions">
      <a class="btn" href="<?= e(route_url('rates')) ?>" data-track="cta_rates_hero"><?= e(t('home.cta_rates')) ?></a>
      <a class="btn btn--ghost" href="<?= e(route_url('location')) ?>"><?= e(t('home.cta_location')) ?></a>
    </p>
  </div>
</section>

<section class="section wrap intro">
  <h2><?= e(t('home.intro_title')) ?></h2>
  <p><?= e(t('home.intro_text')) ?></p>
</section>

<section class="section wrap">
  <ul class="cards">
    <?php foreach ([['rooms', 'slider/5.jpg'], ['pool', 'pileta/pileta-3.webp'], ['spa', 'spa/spa-8.webp'], ['restaurant', 'slider/6.jpg']] as [$id, $photo]): ?>
    <li>
      <a href="<?= e(route_url($id)) ?>">
        <?= img($photo, t("home.cards.$id.alt")) ?>
        <h3><?= e(t("nav.$id")) ?></h3>
        <p><?= e(t("home.cards.$id.text")) ?></p>
      </a>
    </li>
    <?php endforeach; ?>
  </ul>
</section>

<section class="band">
  <div class="wrap section">
    <h2><?= e(t('home.where_title')) ?></h2>
    <p><?= e(t('home.where_text')) ?></p>
    <p><a class="btn" href="<?= e(route_url('location')) ?>"><?= e(t('home.cta_location')) ?></a></p>
  </div>
</section>
