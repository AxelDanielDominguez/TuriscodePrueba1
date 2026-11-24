# Mantenimiento: qué tocar y cuándo

Regla general: **cada dato vive en un solo lugar** y se refleja en todo el sitio.

| Quiero cambiar... | Archivo | Clave | Se refleja en |
|---|---|---|---|
| Número de WhatsApp | `data/site.json` | `contact.whatsapp` (solo dígitos, ej. `5493885411616`) | Botones, footer |
| Teléfono / email | `data/site.json` | `contact.phone`, `contact.phone_display`, `contact.email` | Footer, Google (schema) |
| Dirección | `data/site.json` | `address.*` | Footer, Google (schema) |
| Coordenadas (mapa/SEO local) | `data/site.json` | `geo.lat`, `geo.lng` | Google (schema) |
| Facebook / Booking / Airbnb | `data/site.json` | `social.*` | Google (schema) |
| Dominio final | `data/site.json` | `site.url` | Canonical, sitemap |
| **Publicar para Google** | `data/site.json` | `seo.indexable` → `true` | robots.txt, meta robots |
| Cualquier texto del sitio | `data/lang/es.json` y `en.json` | clave del texto | Páginas |
| Agregar una página | `app/routes.php` + `app/views/pages/<id>.php` + textos `nav.<id>` | | Menú, sitemap, idiomas |
| Imágenes | `public/assets/img/` | | Se usan con `img('carpeta/archivo.jpg', 'texto alternativo')` |
| Colores y tipografías | `public/assets/css/main.css` | bloque `:root` | Todo el sitio |

## Cuándo
- **Mientras el dominio sea `prueba.homes`:** dejar `seo.indexable` en `false` (si no, Google puede indexar una copia duplicada).
- **Al pasar a `elportaldelaquebrada.com`:** cambiar `site.url` y poner `seo.indexable` en `true`.

## Funciones reutilizables (no duplicar)
`config()` datos del sitio · `t()` textos traducidos · `route_url()` URLs · `whatsapp_link()` enlaces de WhatsApp · `img()` imágenes · `render()` páginas · `window.track()` (JS) métricas.

## Despliegue (importante)
El panel `/admin` (Fase 5) modificará `data/*.json` **en el servidor**. Por eso, después del primer despliegue, las actualizaciones de código **no deben sobrescribir `data/`** (excluirla al subir por SFTP/rsync o no incluirla en el `git pull`).

## Pendiente (próximas fases)
Tarifas y botón de WhatsApp (Fase 3), SEO completo (Fase 4), panel admin con login y métricas (Fase 5).
