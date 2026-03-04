<?php
// views/ReservaCatalogo/views/perfil.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($cliente)) {
    header('Location: /Sistema_RentACar/index.php?area=cliente&controller=Cliente&action=perfil');
    exit;
}
?>
<!DOCTYPE html>
<html class="dark" lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil | GoCar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#137fec",
                        "background-dark": "#101922",
                    },
                    fontFamily: {
                        display: ["Manrope", "sans-serif"],
                    },
                },
            },
        };
    </script>
</head>
<body class="bg-background-dark font-display text-white">
    <?php include __DIR__ . '/../../layouts/navbar.php'; ?>

    <main class="container mx-auto px-4 py-8 max-w-4xl">
        <div class="bg-[#1c2a38] rounded-xl border border-[#233648] p-8">
            <h1 class="text-3xl font-bold mb-6">Mi Perfil</h1>

            <?php if (isset($_GET['success'])): ?>
                <div class="bg-green-500/20 border border-green-500 text-green-500 px-4 py-3 rounded-lg mb-6">
                    Perfil actualizado correctamente.
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
                <div class="bg-red-500/20 border border-red-500 text-red-500 px-4 py-3 rounded-lg mb-6">
                    Error al actualizar el perfil.
                </div>
            <?php endif; ?>

            <?php if ($perfilIncompleto): ?>
                <div class="bg-yellow-500/20 border border-yellow-500 text-yellow-500 px-4 py-3 rounded-lg mb-6">
                    Tu perfil está incompleto. Completa tus datos para poder reservar.
                </div>
            <?php endif; ?>

            <div class="flex items-center gap-6 mb-8">
                <div class="w-24 h-24 rounded-full bg-primary/20 flex items-center justify-center text-4xl font-bold text-primary">
                    <?= strtoupper(substr($cliente['nombre'] ?? 'G', 0, 1) . substr($cliente['apellido'] ?? 'C', 0, 1)) ?>
                </div>
                <div>
                    <h2 class="text-2xl font-bold"><?= htmlspecialchars($cliente['nombre'] . ' ' . $cliente['apellido']) ?></h2>
                    <p class="text-[#92adc9]"><?= htmlspecialchars($cliente['correo']) ?></p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="text-sm text-[#92adc9] font-bold uppercase tracking-wider">DUI</label>
                    <div class="bg-[#111a22] p-3 rounded-lg border border-[#233648]">
                        <?= htmlspecialchars($cliente['DUI'] ?? 'No registrado') ?>
                    </div>
                </div>
                <div>
                    <label class="text-sm text-[#92adc9] font-bold uppercase tracking-wider">Teléfono</label>
                    <div class="bg-[#111a22] p-3 rounded-lg border border-[#233648]">
                        <?= htmlspecialchars($cliente['telefono'] ?? 'No registrado') ?>
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label class="text-sm text-[#92adc9] font-bold uppercase tracking-wider">Dirección</label>
                    <div class="bg-[#111a22] p-3 rounded-lg border border-[#233648]">
                        <?= htmlspecialchars($cliente['direccion'] ?? 'No registrada') ?>
                    </div>
                </div>
                <div>
                    <label class="text-sm text-[#92adc9] font-bold uppercase tracking-wider">Registrado el</label>
                    <div class="bg-[#111a22] p-3 rounded-lg border border-[#233648]">
                        <?= date('d/m/Y', strtotime($cliente['created_at'])) ?>
                    </div>
                </div>
            </div>

            <button onclick="document.getElementById('modalEditar').classList.remove('hidden')"
                    class="bg-primary hover:bg-primary/90 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                <?= $perfilIncompleto ? 'Completar Perfil' : 'Editar Perfil' ?>
            </button>
        </div>
    </main>

    <!-- Modal Editar Perfil -->
    <div id="modalEditar" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-[#1c2a38] rounded-xl border border-[#233648] p-8 max-w-lg w-full mx-4">
            <h2 class="text-2xl font-bold mb-6"><?= $perfilIncompleto ? 'Completar Datos' : 'Editar Datos' ?></h2>
            <form method="POST" action="/Sistema_RentACar/index.php?area=cliente&controller=Cliente&action=actualizar">
                <div class="space-y-4">
                    <div>
                        <label class="text-sm text-[#92adc9] font-bold uppercase tracking-wider">DUI</label>
                        <input type="text" name="DUI" maxlength="10" placeholder="00000000-0"
                               value="<?= htmlspecialchars($cliente['DUI'] ?? '') ?>"
                               class="w-full bg-[#111a22] border border-[#233648] rounded-lg p-3 text-white focus:ring-1 focus:ring-primary"
                               required pattern="\d{8}-\d{1}" title="Formato: 12345678-9">
                    </div>
                    <div>
                        <label class="text-sm text-[#92adc9] font-bold uppercase tracking-wider">Teléfono</label>
                        <input type="text" name="telefono" maxlength="9" placeholder="0000-0000"
                               value="<?= htmlspecialchars($cliente['telefono'] ?? '') ?>"
                               class="w-full bg-[#111a22] border border-[#233648] rounded-lg p-3 text-white focus:ring-1 focus:ring-primary"
                               required pattern="\d{4}-\d{4}" title="Formato: 7123-4567">
                    </div>
                    <div>
                        <label class="text-sm text-[#92adc9] font-bold uppercase tracking-wider">Dirección</label>
                        <input type="text" name="direccion"
                               value="<?= htmlspecialchars($cliente['direccion'] ?? '') ?>"
                               class="w-full bg-[#111a22] border border-[#233648] rounded-lg p-3 text-white focus:ring-1 focus:ring-primary"
                               required>
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="document.getElementById('modalEditar').classList.add('hidden')"
                            class="flex-1 bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 rounded-lg transition-colors">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="flex-1 bg-primary hover:bg-primary/90 text-white font-bold py-3 rounded-lg transition-colors">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script para formato DUI y teléfono -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const duiInput = document.querySelector('input[name="DUI"]');
            const telInput = document.querySelector('input[name="telefono"]');

            if (duiInput) {
                duiInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.length > 8) {
                        value = value.substring(0,8) + '-' + value.substring(8,9);
                    }
                    e.target.value = value;
                });
            }

            if (telInput) {
                telInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.length > 4) {
                        value = value.substring(0,4) + '-' + value.substring(4,8);
                    }
                    e.target.value = value;
                });
            }
        });
    </script>

    <?php if ($perfilIncompleto): ?>
    <script>
        // Abrir el modal automáticamente si el perfil está incompleto
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('modalEditar').classList.remove('hidden');
        });
    </script>
    <?php endif; ?>
</body>
</html>