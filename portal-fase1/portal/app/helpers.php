<?php
declare(strict_types=1);

define('ROOT', dirname(__DIR__));
define('DATA_DIR', ROOT . '/data');
const DEFAULT_LANG = 'es';
const LANGS = ['es', 'en'];

function e(?string $s): string
{
    return htmlspecialchars((string) $s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Lee data/<name>.json una sola vez por petición. */
function load_json(string $name): array
{
    static $cache = [];
    if (!isset($cache[$name])) {
        $path = DATA_DIR . '/' . $name . '.json';
        $raw = is_file($path) ? file_get_contents($path) : false;
        $cache[$name] = $raw ? (json_decode($raw, true) ?: []) : [];
    }
    return $cache[$name];
}

/** Acceso por "ruta.con.puntos" a cualquier array. */
function dig(array $arr, string $path, $default = null)
{
    foreach (explode('.', $path) as $key) {
        if (!is_array($arr) || !array_key_exists($key, $arr)) {
            return $default;
        }
        $arr = $arr[$key];
    }
    return $arr;
}

/** Dato de data/site.json: config('contact.whatsapp') */
function config(string $path, $default = null)
{
    return dig(load_json('site'), $path, $default);
}

function lang(?string $set = null): string
{
    static $current = DEFAULT_LANG;
    if ($set !== null && in_array($set, LANGS, true)) {
        $current = $set;
    }
    return $current;
}

/** Texto traducido de data/lang/<idioma>.json (si falta, usa español; si falta todo, devuelve la clave). */
function t(string $key, array $vars = []): string
{
    $text = dig(load_json('lang/' . lang()), $key) ?? dig(load_json('lang/' . DEFAULT_LANG), $key, $key);
    $text = is_string($text) ? $text : $key;
    foreach ($vars as $name => $value) {
        $text = str_replace('{' . $name . '}', (string) $value, $text);
    }
    return $text;
}

function routes(): array
{
    static $routes;
    return $routes ??= require ROOT . '/app/routes.php';
}

/** URL relativa de una página en un idioma. */
function route_url(string $id, ?string $l = null): string
{
    $l ??= lang();
    $slug = routes()['pages'][$id][$l] ?? '';
    $prefix = $l === DEFAULT_LANG ? '' : '/' . $l;
    return $prefix . '/' . $slug;
}

function abs_url(string $path): string
{
    return rtrim((string) config('site.url', ''), '/') . $path;
}

function redirect(string $to, int $code = 301): void
{
    header('Location: ' . $to, true, $code);
    exit;
}

/** Enlace a WhatsApp con mensaje prellenado. Único lugar donde se arma. */
function whatsapp_link(string $message = ''): string
{
    $number = preg_replace('/\D+/', '', (string) config('contact.whatsapp'));
    return 'https://wa.me/' . $number . ($message !== '' ? '?text=' . rawurlencode($message) : '');
}

/** Única forma de mostrar imágenes (lazy por defecto; la principal usa $priority = true). */
function img(string $src, string $alt, bool $priority = false, string $class = ''): string
{
    $attrs = $priority ? 'fetchpriority="high"' : 'loading="lazy"';
    $cls = $class !== '' ? ' class="' . e($class) . '"' : '';
    return '<img src="/assets/img/' . e($src) . '" alt="' . e($alt) . '" ' . $attrs . ' decoding="async"' . $cls . '>';
}

/** [título, descripción] de una página. */
function page_meta(string $page): array
{
    $title = t("meta.$page.title");
    if ($title === "meta.$page.title") {
        $title = t("nav.$page") . ' | ' . config('site.name');
    }
    $desc = t("meta.$page.description");
    if ($desc === "meta.$page.description") {
        $desc = t('meta.home.description');
    }
    return [$title, $desc];
}

function render(string $view, array $vars = [], int $status = 200): void
{
    http_response_code($status);
    $file = ROOT . '/app/views/pages/' . $view . '.php';
    if (!is_file($file)) {
        $file = ROOT . '/app/views/pages/soon.php';
    }
    extract($vars, EXTR_SKIP);
    ob_start();
    require $file;
    $content = ob_get_clean();
    require ROOT . '/app/views/layout.php';
}

function send_security_headers(): void
{
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: geolocation=(), camera=(), microphone=()');
}

/** Datos estructurados para Google (SEO local). Salen de data/site.json. */
function schema_hotel(): array
{
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Hotel',
        'name' => config('site.name'),
        'url' => abs_url('/'),
        'telephone' => config('contact.phone'),
        'email' => config('contact.email'),
        'image' => abs_url('/assets/img/slider/1.jpg'),
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => config('address.street'),
            'addressLocality' => config('address.city'),
            'addressRegion' => config('address.region'),
            'postalCode' => config('address.zip'),
            'addressCountry' => 'AR',
        ],
        'amenityFeature' => [
            ['@type' => 'LocationFeatureSpecification', 'name' => 'Pool', 'value' => true],
            ['@type' => 'LocationFeatureSpecification', 'name' => 'Spa', 'value' => true],
            ['@type' => 'LocationFeatureSpecification', 'name' => 'Restaurant', 'value' => true],
        ],
        'sameAs' => array_values(array_filter([config('social.facebook'), config('social.booking'), config('social.airbnb')])),
    ];
    if (config('geo.lat') !== null && config('geo.lng') !== null) {
        $schema['geo'] = ['@type' => 'GeoCoordinates', 'latitude' => config('geo.lat'), 'longitude' => config('geo.lng')];
    }
    return $schema;
}

function robots_txt(): string
{
    if (!config('seo.indexable')) {
        return "User-agent: *\nDisallow: /\n";
    }
    return "User-agent: *\nDisallow: /admin\nSitemap: " . abs_url('/sitemap.xml') . "\n";
}

function sitemap_xml(): string
{
    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
        . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . "\n";
    foreach (array_keys(routes()['pages']) as $id) {
        foreach (LANGS as $l) {
            $xml .= '<url><loc>' . e(abs_url(route_url($id, $l))) . '</loc>';
            foreach (LANGS as $alt) {
                $xml .= '<xhtml:link rel="alternate" hreflang="' . $alt . '" href="' . e(abs_url(route_url($id, $alt))) . '"/>';
            }
            $xml .= '</url>' . "\n";
        }
    }
    return $xml . '</urlset>';
}
