<?php
// views/ReservaCatalogo/views/catalogo.php

// ✅ Nombre de tu proyecto en /www
if (!defined('APP_ROOT')) define('APP_ROOT', '/Sistema_RentACar');

// ✅ url() estable
if (!function_exists('url')) {
  define('BASE_URL', APP_ROOT);
  function url($path = '') { return BASE_URL . '/' . ltrim($path, '/'); }
}

if (!defined('ROOT_PATH')) define('ROOT_PATH', dirname(__DIR__, 3));
require_once ROOT_PATH . '/config/db.php';

$pdo = Database::connect();

/**
 * Helpers para detectar columnas
 */
function getVehicleColumns(PDO $pdo): array {
  $stmt = $pdo->prepare("
    SELECT COLUMN_NAME
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'tbvehiculos'
  ");
  $stmt->execute();
  return array_map(fn($r) => strtolower($r['COLUMN_NAME']), $stmt->fetchAll());
}

function pickCol(array $cols, array $candidates): ?string {
  foreach ($candidates as $c) {
    if (in_array(strtolower($c), $cols, true)) return $c;
  }
  return null;
}

function vehicleMainImage(int $id): string {
  $baseDir = ROOT_PATH . '/assets/img/vehiculos/';
  $baseUrl = url('assets/img/vehiculos/');
  $exts = ['jpg','jpeg','png','webp'];

  foreach ($exts as $ext) {
    $file = $baseDir . $id . "_1." . $ext;
    if (file_exists($file)) return $baseUrl . $id . "_1." . $ext;
  }
  return url('assets/img/placeholder-car.jpg');
}

// Detectar columnas
$cols = getVehicleColumns($pdo);

$colMarca   = pickCol($cols, ['marca']);                         // 'marca'
$colModelo  = pickCol($cols, ['modelo']);                        // 'modelo'
$colYear    = pickCol($cols, ['year','anio','año']);             // 'year'
$colPrecio  = pickCol($cols, ['precio_dia','precio_por_dia','precio']); // 'precio_dia'
$colEstado  = pickCol($cols, ['estado']);                        // 'estado'
$colDesc    = pickCol($cols, ['descripcion','descripción','detalle','detalles']); // 'descripcion'

// IMPORTANTE: el nombre de la columna de categoría es 'tipo_vehiculo'
$colCatTxt  = pickCol($cols, ['tipo_vehiculo', 'categoria', 'categoría', 'tipo', 'segmento', 'clase']);
$colCatId   = pickCol($cols, ['id_categoria','id_tipo','id_clase']);

// También incluimos car_name si existe (para mostrarlo)
$colCarName = pickCol($cols, ['car_name']); // 'car_name'

// Construir SELECT dinámico
$select = [];
$select[] = "v.id_vehiculo";
$select[] = $colCarName ? "v.`$colCarName` AS car_name" : "NULL AS car_name";
$select[] = $colMarca   ? "v.`$colMarca`  AS marca"    : "NULL AS marca";
$select[] = $colModelo  ? "v.`$colModelo` AS modelo"   : "NULL AS modelo";
$select[] = $colYear    ? "v.`$colYear`   AS year"     : "NULL AS year";
$select[] = $colPrecio  ? "v.`$colPrecio` AS precio_dia" : "0 AS precio_dia";
$select[] = $colEstado  ? "v.`$colEstado` AS estado"   : "NULL AS estado";
$select[] = $colDesc    ? "v.`$colDesc`   AS descripcion" : "NULL AS descripcion";

if ($colCatTxt) {
  $select[] = "v.`$colCatTxt` AS categoria";
} elseif ($colCatId) {
  $select[] = "v.`$colCatId` AS categoria_id";
  $select[] = "NULL AS categoria";
} else {
  $select[] = "NULL AS categoria";
  $select[] = "NULL AS categoria_id";
}

// Consulta SQL con filtro de estado (solo disponibles)
$sql = "
  SELECT " . implode(",\n", $select) . "
  FROM tbvehiculos v
";

if ($colEstado) {
  $sql .= " WHERE v.`$colEstado` = 'disponible' ";
}

$sql .= " ORDER BY v.id_vehiculo DESC";

$vehiculos = $pdo->query($sql)->fetchAll();

/**
 * Normalización de categoría: los valores de la BD ya coinciden (Sedan, SUV, etc.)
 * Solo aseguramos el formato (por ejemplo, "Sedan" -> "Sedán" si quieres acento).
 * Pero como el ENUM ya tiene los valores exactos, podemos dejarlos igual o hacer pequeñas correcciones.
 */
function normalizeCategory($rawCat, $rawId): string {
  $c = trim((string)$rawCat);

  // Si ya viene un valor no vacío, lo devolvemos (puedes aplicar correcciones de acentos si quieres)
  if ($c !== '') {
    // Opcional: cambiar "Sedan" a "Sedán", "Pick-up" ya está bien, etc.
    $map = [
      'Sedan' => 'Sedán',
      // Los demás están bien
    ];
    return $map[$c] ?? $c;
  }

  // Si solo tenemos ID numérico
  if ($rawId !== null && $rawId !== '') {
    return 'Categoría #' . (int)$rawId;
  }

  // Si no hay nada
  return 'Otros';
}

// Agrupar vehículos por categoría normalizada
$grouped = [];
foreach ($vehiculos as $v) {
  $cat = normalizeCategory($v['categoria'] ?? null, $v['categoria_id'] ?? null);
  if (!isset($grouped[$cat])) $grouped[$cat] = [];
  $grouped[$cat][] = $v;
}

// Orden personalizado
$preferredOrder = ['Sedán', 'SUV', 'Pick-up', 'Convertibles', 'Premium', 'Minivan', 'Compacto', 'Mini', 'Crossover', 'Otros'];
uksort($grouped, function($a,$b) use ($preferredOrder) {
  $ia = array_search($a, $preferredOrder, true);
  $ib = array_search($b, $preferredOrder, true);
  $ia = ($ia === false) ? 999 : $ia;
  $ib = ($ib === false) ? 999 : $ib;
  return $ia <=> $ib;
});

// Para depuración: mostrar categorías detectadas
$detectedCats = array_keys($grouped);
$colCatExists = ($colCatTxt || $colCatId) ? 'SÍ' : 'NO';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Catálogo de Vehículos | GO CAR</title>

  <!-- Vendor -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Tus estilos -->
  <link rel="stylesheet" href="<?= url('assets/css/client.css') ?>">
  <link rel="stylesheet" href="<?= url('assets/css/ReservaCatalogo/style.css') ?>">
  <link rel="stylesheet" href="<?= url('assets/css/ReservaCatalogo/home.css') ?>">
</head>

<body class="gocar-body">
  <?php include __DIR__ . '/../../layouts/navbar.php'; ?>

  <!-- DEPURACIÓN (visible en código fuente) -->
  <!-- Columna de categoría detectada: <?= $colCatExists ?> -->
  <!-- Categorías encontradas: <?= implode(', ', $detectedCats) ?> -->

  <!-- HERO -->
  <section class="catalog-hero"
    style="background-image: url('https://images.unsplash.com/photo-1541899481282-d53bffe3c35d?q=80&w=1920&auto=format&fit=crop');">
    <div class="catalog-hero-overlay"></div>

    <div class="container position-relative" style="z-index: 2;">
      <div class="hero-breadcrumb">
        <a href="<?= url('index.php') ?>">Inicio</a>
        <i class="fas fa-chevron-right"></i>
        <span>Catálogo</span>
      </div>

      <h1 class="hero-main-title">
        Elige tu vehículo ideal.
        <span class="hero-highlight">Reserva en minutos.</span>
      </h1>
      <div class="hero-divider"></div>

      <p class="hero-description">
        Flota real desde tu base de datos. Fotos reales desde tu sistema. Diseñado para que el cliente diga: “lo quiero”.
      </p>

      <div class="hero-actions">
        <a href="#vehicleCatalog" class="btn-hero-primary">
          <i class="fas fa-th-large"></i> Explorar Flota
        </a>
        <a href="#filters" class="btn-hero-outline">
          <i class="fas fa-sliders"></i> Filtrar
        </a>
      </div>

      <div class="hero-stats">
        <div class="hero-stat">
          <span class="hero-stat-number"><?= count($vehiculos) ?></span>
          <span class="hero-stat-label">Vehículos</span>
        </div>
        <div class="hero-stat-divider"></div>
        <div class="hero-stat">
          <span class="hero-stat-number"><?= count($grouped) ?></span>
          <span class="hero-stat-label">Categorías</span>
        </div>
        <div class="hero-stat-divider"></div>
        <div class="hero-stat">
          <span class="hero-stat-number">24/7</span>
          <span class="hero-stat-label">Atención</span>
        </div>
      </div>
    </div>

    <a href="#vehicleCatalog" class="hero-scroll-indicator">
      <i class="fas fa-chevron-down"></i>
    </a>
  </section>

  <section class="catalogo-section py-5">
    <div class="container">

      <!-- FILTROS -->
      <div id="filters" class="filters mb-4">
        <form class="row g-3" id="catalogFilters" autocomplete="off">
          <div class="col-md-4">
            <label for="category" class="form-label text-white">Categoría</label>
            <select id="category" class="form-select">
              <option value="">Todas</option>
              <?php foreach ($detectedCats as $cat): ?>
                <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-md-4">
            <label for="priceRange" class="form-label text-white">Rango de precio</label>
            <select id="priceRange" class="form-select">
              <option value="">Cualquier precio</option>
              <option value="0-50">$0 - $50</option>
              <option value="51-100">$51 - $100</option>
              <option value="101-150">$101 - $150</option>
              <option value="151-200">$151 - $200</option>
              <option value="200+">Más de $200</option>
            </select>
          </div>

          <div class="col-md-4">
            <label for="searchText" class="form-label text-white">Buscar</label>
            <input id="searchText" class="form-control" placeholder="Ej: Tacoma, Hilux, Corolla...">
          </div>
        </form>
      </div>

      <!-- Catálogo -->
      <div id="vehicleCatalog" class="row g-4">
        <?php if (empty($vehiculos)): ?>
          <div class="col-12">
            <div class="alert alert-warning">
              No hay vehículos disponibles en este momento.
            </div>
          </div>
        <?php endif; ?>

        <?php foreach ($grouped as $catName => $items): ?>
          <div class="col-12">
            <h3 class="category-title"><?= htmlspecialchars($catName) ?></h3>
          </div>

          <?php foreach ($items as $v): ?>
            <?php
              $id       = (int)$v['id_vehiculo'];
              $car_name = trim((string)($v['car_name'] ?? ''));
              $marca    = trim((string)($v['marca'] ?? ''));
              $modelo   = trim((string)($v['modelo'] ?? ''));
              // Priorizar car_name, si no, combinar marca + modelo
              $nombre = $car_name ?: trim($marca . ' ' . $modelo);
              if (!$nombre) $nombre = "Vehículo #$id";

              $anio   = $v['year'] ?? '';
              $precio = (float)($v['precio_dia'] ?? 0);
              $img    = vehicleMainImage($id);

              $dataCat = $catName; // categoría normalizada
            ?>
            <div class="col-md-6 col-lg-4 vehicle-item"
                 data-category="<?= htmlspecialchars($dataCat) ?>"
                 data-price="<?= htmlspecialchars((string)$precio) ?>"
                 data-name="<?= htmlspecialchars(mb_strtolower($nombre . ' ' . $marca . ' ' . $modelo, 'UTF-8')) ?>">

              <div class="car-card">
                <div class="car-image-container">
                  <img src="<?= htmlspecialchars($img) ?>" class="car-image" alt="<?= htmlspecialchars($nombre) ?>">
                </div>

                <div class="car-info p-4">
                  <h4 class="car-title"><?= htmlspecialchars($nombre) ?></h4>
                  <p class="car-meta"><?= htmlspecialchars((string)$anio) ?> • <?= htmlspecialchars($dataCat) ?></p>

                  <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="car-price">$<?= number_format($precio, 2) ?></span>
                    <span class="badge badge-soft">Disponible</span>
                  </div>

                 
                     <a href="<?= url('views/ReservaCatalogo/views/reservas.php?car_id=' . $id) ?>" class="btn btn-book-now w-100">
                    Reservar
                  </a>
                </div>
              </div>

            </div>
          <?php endforeach; ?>
        <?php endforeach; ?>

      </div>
    </div>
  </section>

  <?php include __DIR__ . '/../../layouts/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const categorySelect = document.getElementById('category');
      const priceSelect = document.getElementById('priceRange');
      const searchInput = document.getElementById('searchText');
      const vehicleItems = document.querySelectorAll('.vehicle-item');

      console.log('Categorías en filtro:', 
        Array.from(categorySelect.options).map(o => o.value).filter(v => v !== ''));

      function filterVehicles() {
        const selectedCategory = categorySelect.value;
        const selectedPrice = priceSelect.value;
        const searchTerm = searchInput.value.trim().toLowerCase();

        vehicleItems.forEach(item => {
          const itemCategory = item.dataset.category;
          const itemPrice = parseFloat(item.dataset.price);
          const itemName = item.dataset.name;

          let categoryMatch = true;
          if (selectedCategory !== '') {
            categoryMatch = (itemCategory === selectedCategory);
          }

          let priceMatch = true;
          if (selectedPrice !== '') {
            if (selectedPrice === '200+') {
              priceMatch = (itemPrice >= 200);
            } else {
              const [min, max] = selectedPrice.split('-').map(Number);
              priceMatch = (itemPrice >= min && itemPrice <= max);
            }
          }

          let searchMatch = true;
          if (searchTerm !== '') {
            searchMatch = itemName.includes(searchTerm);
          }

          if (categoryMatch && priceMatch && searchMatch) {
            item.style.display = '';
          } else {
            item.style.display = 'none';
          }

          // Depuración en consola (opcional)
          // if (selectedCategory !== '' && !categoryMatch) {
          //   console.log(`No coincide: esperado "${selectedCategory}", obtenido "${itemCategory}"`);
          // }
        });
      }

      categorySelect.addEventListener('change', filterVehicles);
      priceSelect.addEventListener('change', filterVehicles);
      searchInput.addEventListener('input', filterVehicles);

      // Ejecutar una vez al inicio
      filterVehicles();
    });
  </script>
</body>
</html>