<?php
// views/ReservaCatalogo/views/reservas.php

if (!defined('APP_ROOT')) define('APP_ROOT', '/Sistema_RentACar');
if (!function_exists('asset')) {
  function asset($path = '') { return APP_ROOT . '/' . ltrim($path, '/'); }
}

if (!defined('ROOT_PATH')) define('ROOT_PATH', dirname(__DIR__, 3));
require_once ROOT_PATH . '/config/db.php';

$pdo = Database::connect();

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

function vehicleGallery(int $id): array {
  $baseDir = ROOT_PATH . '/assets/img/vehiculos/';
  $baseUrl = asset('assets/img/vehiculos/');
  $exts = ['jpg','jpeg','png','webp'];

  $out = [];
  for ($i = 1; $i <= 4; $i++) {
    foreach ($exts as $ext) {
      $file = $baseDir . $id . "_$i." . $ext;
      if (file_exists($file)) { $out[] = $baseUrl . $id . "_$i." . $ext; break; }
    }
  }
  if (empty($out)) $out[] = asset('assets/img/placeholder-car.jpg');
  return $out;
}

$carId = isset($_GET['car_id']) ? (int)$_GET['car_id'] : 0;
$vehiculo = null;
$imagenes = [];

if ($carId > 0) {
  $cols = getVehicleColumns($pdo);

  $colMarca  = pickCol($cols, ['marca']);
  $colModelo = pickCol($cols, ['modelo']);
  $colYear   = pickCol($cols, ['year','anio','año']);
  $colPrecio = pickCol($cols, ['precio_dia','precio_por_dia','precio']);
  $colDesc   = pickCol($cols, ['descripcion','descripción','detalle','detalles']);
  $colTipo   = pickCol($cols, ['tipo_vehiculo', 'categoria', 'tipo']);

  $select = [];
  $select[] = "v.id_vehiculo";
  $select[] = $colMarca  ? "v.`$colMarca`  AS marca"  : "NULL AS marca";
  $select[] = $colModelo ? "v.`$colModelo` AS modelo" : "NULL AS modelo";
  $select[] = $colYear   ? "v.`$colYear`   AS year"   : "NULL AS year";
  $select[] = $colPrecio ? "v.`$colPrecio` AS precio_dia" : "0 AS precio_dia";
  $select[] = $colDesc   ? "v.`$colDesc`   AS descripcion" : "NULL AS descripcion";
  if ($colTipo) {
    $select[] = "v.`$colTipo` AS tipo";
  } else {
    $select[] = "NULL AS tipo";
  }

  $stmt = $pdo->prepare("
    SELECT " . implode(",\n", $select) . "
    FROM tbvehiculos v
    WHERE v.id_vehiculo = :id
    LIMIT 1
  ");
  $stmt->execute([':id' => $carId]);
  $vehiculo = $stmt->fetch();

  if ($vehiculo) $imagenes = vehicleGallery($carId);
}
?>
<!DOCTYPE html>
<html class="dark" lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $vehiculo ? htmlspecialchars(trim(($vehiculo['marca'] ?? '').' '.($vehiculo['modelo'] ?? ''))) . ' | Reserva' : 'Reservas' ?> | GO CAR</title>

  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

  <script>
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            primary: "#137fec",
            "background-light": "#f6f7f8",
            "background-dark": "#101922",
          },
          fontFamily: {
            display: ["Manrope", "sans-serif"]
          },
          borderRadius: {
            DEFAULT: "0.25rem",
            lg: "0.5rem",
            xl: "0.75rem",
            full: "9999px"
          },
        },
      },
    }
  </script>

  <link rel="stylesheet" href="<?= asset('assets/css/client.css') ?>">
  <link rel="stylesheet" href="<?= asset('assets/css/ReservaCatalogo/style.css') ?>">
  <link rel="stylesheet" href="<?= asset('assets/css/ReservaCatalogo/reservas.css') ?>">
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-white transition-colors duration-300">
<div class="relative flex h-auto min-h-screen w-full flex-col overflow-x-hidden">
  <div class="layout-container flex h-full grow flex-col">
    <!-- Navbar (puede ser tu archivo incluido) -->
    <?php include __DIR__ . '/../../layouts/navbar.php'; ?>

    <?php if ($vehiculo): ?>
      <?php
        $id       = (int)$vehiculo['id_vehiculo'];
        $marca    = trim($vehiculo['marca'] ?? '');
        $modelo   = trim($vehiculo['modelo'] ?? '');
        $nombre   = trim($marca . ' ' . $modelo);
        $anio     = $vehiculo['year'] ?? '';
        $precio   = (float)($vehiculo['precio_dia'] ?? 0);
        $desc     = $vehiculo['descripcion'] ?? '';
        $tipo     = $vehiculo['tipo'] ?? 'Vehículo';
        $imgPrincipal = $imagenes[0] ?? asset('assets/img/placeholder-car.jpg');
      ?>

      <main class="flex flex-col items-center py-6 px-4 lg:px-40">
        <div class="layout-content-container flex flex-col max-w-[1200px] w-full">

          <!-- Breadcrumbs -->
          <div class="flex flex-wrap gap-2 py-4">
            <a class="text-[#92adc9] hover:text-white text-sm font-medium leading-normal" href="<?= asset('index.php') ?>">Inicio</a>
            <span class="text-[#92adc9] text-sm font-medium">/</span>
            <a class="text-[#92adc9] hover:text-white text-sm font-medium leading-normal" href="<?= asset('index.php?v=catalogo') ?>">Catálogo</a>
            <span class="text-[#92adc9] text-sm font-medium">/</span>
            <a class="text-[#92adc9] hover:text-white text-sm font-medium leading-normal" href="#"><?= htmlspecialchars($tipo) ?></a>
            <span class="text-[#92adc9] text-sm font-medium">/</span>
            <span class="text-white text-sm font-medium leading-normal"><?= htmlspecialchars($nombre ?: "Vehículo #$id") ?></span>
          </div>

          <!-- Grid principal -->
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            <!-- Columna izquierda: galería y detalles -->
            <div class="lg:col-span-2 space-y-8">
              <!-- Imagen principal -->
              <div class="@container">
                <div class="bg-cover bg-center flex flex-col justify-end overflow-hidden bg-[#111a22] rounded-xl min-h-[480px] shadow-2xl group relative"
                     style="background-image: linear-gradient(0deg, rgba(0,0,0,0.6) 0%, rgba(0,0,0,0) 30%), url('<?= htmlspecialchars($imgPrincipal) ?>');">
                  <div class="absolute top-4 left-4 bg-primary px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider"><?= htmlspecialchars($tipo) ?></div>
                  <div class="flex justify-center gap-2 p-5">
                    <?php foreach ($imagenes as $i => $img): ?>
                      <div class="size-2 rounded-full <?= $i === 0 ? 'bg-white' : 'bg-white opacity-40' ?>"></div>
                    <?php endforeach; ?>
                  </div>
                </div>
              </div>

              <!-- Miniaturas -->
              <div class="flex overflow-x-auto gap-4 pb-2 scrollbar-hide" id="galleryThumbs">
                <?php foreach ($imagenes as $i => $src): ?>
                  <div class="flex flex-col gap-2 min-w-48 group cursor-pointer <?= $i === 0 ? '' : 'opacity-70 hover:opacity-100 transition-opacity' ?>"
                       data-index="<?= $i ?>"
                       onclick="document.querySelector('.bg-cover.bg-center').style.backgroundImage = 'linear-gradient(0deg, rgba(0,0,0,0.6) 0%, rgba(0,0,0,0) 30%), url(\\'' + '<?= htmlspecialchars($src) ?>' + '\\')'; document.querySelectorAll('#galleryThumbs .ring-2').forEach(el => el.classList.remove('ring-2', 'ring-primary')); this.querySelector('div').classList.add('ring-2', 'ring-primary');">
                    <div class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-lg <?= $i === 0 ? 'ring-2 ring-primary' : '' ?>"
                         style="background-image: url('<?= htmlspecialchars($src) ?>');"></div>
                    <p class="text-white text-sm font-medium text-center"><?= $i === 0 ? 'Principal' : 'Foto ' . ($i+1) ?></p>
                  </div>
                <?php endforeach; ?>
              </div>

              <!-- Título y detalles -->
              <div class="flex flex-wrap justify-between items-end gap-3 border-b border-[#233648] pb-6">
                <div class="flex flex-col gap-2">
                  <h1 class="text-white text-5xl font-black leading-tight tracking-[-0.033em]"><?= htmlspecialchars($nombre ?: "Vehículo #$id") ?></h1>
                  <div class="flex items-center gap-4">
                    <p class="text-[#92adc9] text-lg font-normal">
                      <?= htmlspecialchars($tipo) ?> • <?= htmlspecialchars($anio) ?>
                    </p>
                    <div class="flex items-center gap-1 text-yellow-400">
                      <span class="material-symbols-outlined text-sm fill-1">star</span>
                      <span class="text-white font-bold text-sm">4.9</span>
                      <span class="text-[#92adc9] text-xs font-normal">(124 reviews)</span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Especificaciones -->
              <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-[#1c2a38] p-4 rounded-xl border border-[#233648]">
                  <span class="material-symbols-outlined text-primary mb-2">bolt</span>
                  <p class="text-[#92adc9] text-xs uppercase font-bold tracking-widest">Potencia</p>
                  <p class="text-white font-bold">-- HP</p>
                </div>
                <div class="bg-[#1c2a38] p-4 rounded-xl border border-[#233648]">
                  <span class="material-symbols-outlined text-primary mb-2">settings_input_component</span>
                  <p class="text-[#92adc9] text-xs uppercase font-bold tracking-widest">Transmisión</p>
                  <p class="text-white font-bold">Automática</p>
                </div>
                <div class="bg-[#1c2a38] p-4 rounded-xl border border-[#233648]">
                  <span class="material-symbols-outlined text-primary mb-2">local_gas_station</span>
                  <p class="text-[#92adc9] text-xs uppercase font-bold tracking-widest">Combustible</p>
                  <p class="text-white font-bold">Gasolina</p>
                </div>
                <div class="bg-[#1c2a38] p-4 rounded-xl border border-[#233648]">
                  <span class="material-symbols-outlined text-primary mb-2">group</span>
                  <p class="text-[#92adc9] text-xs uppercase font-bold tracking-widest">Capacidad</p>
                  <p class="text-white font-bold">5 personas</p>
                </div>
              </div>

              <!-- Descripción -->
              <?php if (trim($desc) !== ''): ?>
                <div class="space-y-4">
                  <h3 class="text-xl font-bold">Descripción</h3>
                  <p class="text-[#92adc9]"><?= nl2br(htmlspecialchars($desc)) ?></p>
                </div>
              <?php endif; ?>

              <!-- Amenidades -->
              <div class="space-y-4">
                <h3 class="text-xl font-bold">Incluye</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-3">
                  <div class="flex items-center gap-3 text-[#92adc9]">
                    <span class="material-symbols-outlined text-green-500">check_circle</span>
                    <span class="text-white">Aire acondicionado</span>
                  </div>
                  <div class="flex items-center gap-3 text-[#92adc9]">
                    <span class="material-symbols-outlined text-green-500">check_circle</span>
                    <span class="text-white">Bluetooth</span>
                  </div>
                  <div class="flex items-center gap-3 text-[#92adc9]">
                    <span class="material-symbols-outlined text-green-500">check_circle</span>
                    <span class="text-white">Cámara de reversa</span>
                  </div>
                  <div class="flex items-center gap-3 text-[#92adc9]">
                    <span class="material-symbols-outlined text-green-500">check_circle</span>
                    <span class="text-white">Asientos de cuero</span>
                  </div>
                </div>
              </div>

              <!-- Calendario placeholder -->
              <div class="bg-[#1c2a38] p-6 rounded-xl border border-[#233648] space-y-4">
                <div class="flex justify-between items-center">
                  <h3 class="text-lg font-bold">Disponibilidad</h3>
                  <div class="flex gap-4">
                    <button class="p-1 hover:bg-[#233648] rounded"><span class="material-symbols-outlined">chevron_left</span></button>
                    <p class="font-bold">Marzo 2026</p>
                    <button class="p-1 hover:bg-[#233648] rounded"><span class="material-symbols-outlined">chevron_right</span></button>
                  </div>
                </div>
                <p class="text-[#92adc9] text-sm">Consulta disponibilidad en el formulario de reserva.</p>
              </div>
            </div>

            <!-- Columna derecha: widget de reserva -->
            <div class="lg:sticky lg:top-24">
              <div class="bg-[#1c2a38] p-6 rounded-xl border border-[#233648] shadow-2xl space-y-6">
                <div class="flex justify-between items-baseline">
                  <p class="text-3xl font-black text-white">$<?= number_format($precio, 2) ?><span class="text-sm font-normal text-[#92adc9]"> / día</span></p>
                  <p class="text-xs text-green-400 font-bold uppercase">Reserva instantánea</p>
                </div>

                <!-- Formulario de reserva -->
                <form method="POST" action="procesar_reserva.php" class="space-y-4">
                  <input type="hidden" name="car_id" value="<?= $id ?>">
                  <input type="hidden" name="precio_dia" value="<?= $precio ?>">

                  <div class="grid grid-cols-1 gap-4">
                    <div class="space-y-1">
                      <label class="text-xs font-bold text-[#92adc9] uppercase tracking-tighter">Ubicación de recogida</label>
                      <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#92adc9] text-lg">location_on</span>
                        <select name="ubicacion" class="w-full bg-[#111a22] border-none rounded-lg pl-10 text-sm h-12 focus:ring-1 focus:ring-primary" required>
                          <option value="San Salvador">San Salvador</option>
                          <option value="Santa Ana">Santa Ana</option>
                          <option value="San Miguel">San Miguel</option>
                        </select>
                      </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                      <div class="space-y-1">
                        <label class="text-xs font-bold text-[#92adc9] uppercase tracking-tighter">Fecha inicio</label>
                        <input type="date" name="fecha_inicio" class="w-full bg-[#111a22] border-none rounded-lg text-sm h-12 focus:ring-1 focus:ring-primary" required>
                      </div>
                      <div class="space-y-1">
                        <label class="text-xs font-bold text-[#92adc9] uppercase tracking-tighter">Fecha fin</label>
                        <input type="date" name="fecha_fin" class="w-full bg-[#111a22] border-none rounded-lg text-sm h-12 focus:ring-1 focus:ring-primary" required>
                      </div>
                    </div>
                  </div>

                  <!-- Desglose de precio -->
                  <div class="pt-4 border-t border-[#233648] space-y-2">
                    <div class="flex justify-between text-sm text-[#92adc9]">
                      <span>$<?= number_format($precio, 2) ?> x <span id="dias">3</span> días</span>
                      <span class="text-white" id="subtotal">$<?= number_format($precio * 3, 2) ?></span>
                    </div>
                    <div class="flex justify-between text-sm text-[#92adc9]">
                      <span>Seguro (incluido)</span>
                      <span class="text-white">$0.00</span>
                    </div>
                    <div class="flex justify-between text-sm text-[#92adc9]">
                      <span>Tarifa de servicio</span>
                      <span class="text-white">$0.00</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold border-t border-[#233648] pt-3 mt-2">
                      <span>Total</span>
                      <span class="text-primary" id="total">$<?= number_format($precio * 3, 2) ?></span>
                    </div>
                  </div>

                  <button type="submit" class="w-full bg-primary hover:bg-primary/90 py-4 rounded-xl font-bold text-lg shadow-lg shadow-primary/20 transition-all flex items-center justify-center gap-2">
                    <span>Proceder al Pago</span>
                    <span class="material-symbols-outlined">arrow_forward</span>
                  </button>
                </form>

                <div class="flex flex-col items-center gap-2 pt-2">
                  <div class="flex items-center gap-1 text-[#92adc9] text-xs">
                    <span class="material-symbols-outlined text-sm">security</span>
                    <span>Pagos seguros y datos encriptados</span>
                  </div>
                  <p class="text-[10px] text-[#445566] text-center">Cancelación gratuita hasta 48 horas antes. Sin cargos ocultos.</p>
                </div>
              </div>

              <!-- Mapa miniatura -->
              <div class="mt-6 rounded-xl overflow-hidden h-40 relative group">
                <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAOxqmG6R1F1L_xz9kyN0qULWo_oPad-u87sOhZMAd6rqp7jVP8TLLh_z9OBJBLuron8ubJelleMO4WzrEmCjhqV7lfDPTv1bIsabdM9dBdLIZ2OIeXBogOmqPYTo4OnhoB7L4V3146D3Zj3lS9T4s8F5MKhXy40nAW7mwU0Ywp-6Nj_pGGiP-XqZIM2LgcBDto048ga_KgjTp3imP4SsVlVSoWj4-psA_Qg9jmdDf5-6ThdZkvT_L3SIH8RZu48HPUCNPeDzsZ_DzT');"></div>
                <div class="absolute inset-0 bg-background-dark/20 group-hover:bg-transparent transition-colors"></div>
                <div class="absolute bottom-2 right-2 bg-background-dark/80 px-2 py-1 rounded text-[10px] flex items-center gap-1">
                  <span class="material-symbols-outlined text-[12px]">map</span> Ver mapa
                </div>
              </div>
            </div>
          </div> <!-- fin grid principal -->
        </div> <!-- fin layout-content-container -->
      </main>

      <!-- FOOTER (fuera del contenedor con ancho máximo) -->
      <?php include __DIR__ . '/../../layouts/footer.php'; ?>

    <?php else: ?>
      <!-- Mensaje cuando no hay vehículo seleccionado -->
      <main class="flex flex-col items-center py-6 px-4 lg:px-40">
        <div class="layout-content-container max-w-[1200px] w-full">
          <div class="bg-[#1c2a38] p-8 rounded-xl border border-[#233648] text-center">
            <span class="material-symbols-outlined text-6xl text-[#92adc9] mb-4">directions_car</span>
            <h2 class="text-2xl font-bold mb-2">No has seleccionado un vehículo</h2>
            <p class="text-[#92adc9] mb-6">Para hacer una reserva, primero elige un vehículo de nuestro catálogo.</p>
            <a href="<?= asset('index.php?v=catalogo') ?>" class="inline-block bg-primary hover:bg-primary/90 px-6 py-3 rounded-lg font-bold transition-colors">
              <i class="fas fa-th-large mr-2"></i> Ir al Catálogo
            </a>
          </div>
        </div>
      </main>
      <!-- Footer también en caso de error -->
      <footer class="mt-20 py-10 border-t border-[#233648] bg-background-light dark:bg-background-dark w-full">
        <div class="max-w-[1200px] mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-8">
          <!-- mismo contenido que arriba, o puedes incluir footer.php -->
          <div class="col-span-1 md:col-span-2 space-y-4">
            <div class="flex items-center gap-3 text-primary">
              <div class="size-6">
                <svg fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                  <path d="M36.7273 44C33.9891 44 31.6043 39.8386 30.3636 33.69C29.123 39.8386 26.7382 44 24 44C21.2618 44 18.877 39.8386 17.6364 33.69C16.3957 39.8386 14.0109 44 11.2727 44C7.25611 44 4 35.0457 4 24C4 12.9543 7.25611 4 11.2727 4C14.0109 4 16.3957 8.16144 17.6364 14.31C18.877 8.16144 21.2618 4 24 4C26.7382 4 29.123 8.16144 30.3636 14.31C31.6043 8.16144 33.9891 4 36.7273 4C40.7439 4 44 12.9543 44 24C44 35.0457 40.7439 44 36.7273 44Z" fill="currentColor"></path>
                </svg>
              </div>
              <h2 class="text-white text-lg font-bold">GoCar Rent A Car</h2>
            </div>
            <p class="text-[#92adc9] text-sm max-w-sm">Experience the future of mobility...</p>
          </div>
          <!-- ... resto del footer ... -->
        </div>
      </footer>
    <?php endif; ?>

  </div> <!-- fin layout-container -->
</div> <!-- fin relative -->

<!-- Script para cálculo dinámico de días y total -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const fechaInicio = document.querySelector('input[name="fecha_inicio"]');
    const fechaFin = document.querySelector('input[name="fecha_fin"]');
    const diasSpan = document.getElementById('dias');
    const subtotalSpan = document.getElementById('subtotal');
    const totalSpan = document.getElementById('total');
    const precioDia = <?= $precio ?? 0 ?>;

    function actualizarPrecio() {
      if (fechaInicio && fechaFin && fechaInicio.value && fechaFin.value) {
        const inicio = new Date(fechaInicio.value);
        const fin = new Date(fechaFin.value);
        const diffTime = fin - inicio;
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        if (diffDays > 0) {
          const subtotal = precioDia * diffDays;
          diasSpan.textContent = diffDays;
          subtotalSpan.textContent = '$' + subtotal.toFixed(2);
          totalSpan.textContent = '$' + subtotal.toFixed(2);
        } else {
          diasSpan.textContent = '0';
          subtotalSpan.textContent = '$0.00';
          totalSpan.textContent = '$0.00';
        }
      }
    }

    if (fechaInicio && fechaFin) {
      fechaInicio.addEventListener('change', actualizarPrecio);
      fechaFin.addEventListener('change', actualizarPrecio);
      // Ejecutar una vez para establecer valores iniciales
      actualizarPrecio();
    }
  });
</script>

<!-- Script para la galería (opcional, ya está inline) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= asset('assets/js/client-ui.js') ?>"></script>
<script src="<?= asset('assets/js/ReservaCatalogo/reservas.js') ?>"></script>
</body>
</html>