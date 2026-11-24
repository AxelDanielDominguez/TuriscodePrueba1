<?php
declare(strict_types=1);

$path = '/' . trim(rawurldecode((string) parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH)), '/');

// Servidor local de PHP (php -S): deja pasar archivos reales (css, js, imágenes)
if (PHP_SAPI === 'cli-server' && $path !== '/' && is_file(__DIR__ . $path)) {
    return false;
}

require dirname(__DIR__) . '/app/helpers.php';
send_security_headers();

if ($path === '/robots.txt') {
    header('Content-Type: text/plain; charset=utf-8');
    echo robots_txt();
    exit;
}
if ($path === '/sitemap.xml') {
    header('Content-Type: application/xml; charset=utf-8');
    echo sitemap_xml();
    exit;
}
if ($path === '/api/track') { // Fase 5: acá se guardan las métricas
    http_response_code(204);
    exit;
}

$legacy = routes()['legacy'];
if (isset($legacy[$path])) {
    redirect(route_url($legacy[$path], DEFAULT_LANG));
}

$l = DEFAULT_LANG;
$inner = $path;
if (preg_match('#^/(en)(/.*)?$#', $path, $m)) {
    $l = $m[1];
    $inner = $m[2] ?? '/';
}
lang($l);
$slug = trim($inner, '/');

foreach (routes()['pages'] as $id => $page) {
    if (($page[$l] ?? null) === $slug) {
        render($id, ['page' => $id]);
        exit;
    }
}
render('notfound', ['page' => 'notfound'], 404);
