# Descarga las imágenes del sitio original a public/assets/img
# Uso (desde la raíz del proyecto):  powershell -ExecutionPolicy Bypass -File scripts\descargar-imagenes.ps1
$base = "https://www.elportaldelaquebrada.com"
$dest = Join-Path $PSScriptRoot "..\public\assets\img"

$files = @(
  "assets/img/slider/1.jpg","assets/img/slider/2.jpg","assets/img/slider/4.jpg","assets/img/slider/5.jpg",
  "assets/img/slider/6.jpg","assets/img/slider/7.jpg","assets/img/slider/9.jpg",
  "images/pileta/pileta-3.webp","images/pileta/pileta-8.webp","images/pileta/pileta-5.webp","images/pileta/pileta-7.webp",
  "images/confiteria/confiteria-3.webp","images/confiteria/confiteria-4.webp","images/confiteria/confiteria-8.webp","images/confiteria/confiteria-2.webp",
  "images/spa/spa-8.webp","images/spa/spa-12.webp","images/spa/spa-11.webp","images/spa/spa-19.webp"
)

foreach ($f in $files) {
  $rel = $f -replace '^assets/img/','' -replace '^images/',''
  $out = Join-Path $dest $rel
  New-Item -ItemType Directory -Force -Path (Split-Path $out) | Out-Null
  try {
    Invoke-WebRequest -Uri "$base/$f" -OutFile $out -UseBasicParsing
    Write-Host "OK    $rel"
  } catch {
    Write-Host "FALLO $rel  ($($_.Exception.Message))" -ForegroundColor Yellow
  }
}
