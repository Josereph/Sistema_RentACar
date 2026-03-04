<?php
// views/layouts/footer.php
if (!function_exists('url')) {
    function url($path = '') {
        $base = '/Sistema_RentACar';
        return $base . '/' . ltrim($path, '/');
    }
}
?>
<style>
    .gocar-footer {
        background-color: #0f1a24;
        border-top: 1px solid #233648;
        padding: 2.5rem 1rem 1rem;
        font-family: 'Manrope', sans-serif;
        margin-top: 3rem;
    }
    .gocar-footer .container {
        max-width: 1200px;
        margin: 0 auto;
    }
    .gocar-footer .grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    @media (min-width: 768px) {
        .gocar-footer .grid {
            grid-template-columns: 2fr 1fr 1fr;
        }
    }
    .gocar-footer .logo-area {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: #137fec;
        margin-bottom: 1rem;
    }
    .gocar-footer .logo-icon {
        width: 28px;
        height: 28px;
        fill: currentColor;
    }
    .gocar-footer .logo-text {
        font-weight: 700;
        font-size: 1.2rem;
        color: #fff;
    }
    .gocar-footer .description {
        color: #92adc9;
        font-size: 0.9rem;
        line-height: 1.5;
        max-width: 300px;
    }
    .gocar-footer .col-title {
        color: white;
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: 1rem;
    }
    .gocar-footer .links {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .gocar-footer .links li {
        margin-bottom: 0.5rem;
    }
    .gocar-footer .links a {
        color: #92adc9;
        text-decoration: none;
        font-size: 0.9rem;
        transition: color 0.2s;
    }
    .gocar-footer .links a:hover {
        color: white;
    }
    .gocar-footer .bottom {
        text-align: center;
        color: #445566;
        font-size: 0.8rem;
        border-top: 1px solid #233648;
        margin-top: 2rem;
        padding-top: 1.5rem;
    }
</style>

<footer class="gocar-footer">
    <div class="container">
        <div class="grid">
            <!-- Columna principal -->
            <div>
                <div class="logo-area">
                    <img src="<?= url('assets/img/img.jpeg') ?>" 
                         alt="GO CAR Logo" 
                         style="width:40px; height:40px; object-fit:contain;">
                </div>
                <p class="description">
                    Experiencia de movilidad moderna. Vehículos de alta calidad, opciones eléctricas y un proceso de reserva sin complicaciones.
                </p>
            </div>
            <!-- Columna Renta -->
            <div>
                <h4 class="col-title">Renta</h4>
                <ul class="links">
                    <li><a href="#">Autos de lujo</a></li>
                    <li><a href="#">Flota eléctrica</a></li>
                    <li><a href="#">SUVs y Vans</a></li>
                    <li><a href="#">Rentas empresariales</a></li>
                </ul>
            </div>
            <!-- Columna Compañía -->
            <div>
                <h4 class="col-title">Compañía</h4>
                <ul class="links">
                    <li><a href="#">Sobre nosotros</a></li>
                    <li><a href="#">Alianzas</a></li>
                    <li><a href="#">Política de privacidad</a></li>
                    <li><a href="#">Términos de servicio</a></li>
                </ul>
            </div>
        </div>
        <div class="bottom">
            &copy; <?= date('Y') ?> GoCar Rent A Car. Todos los derechos reservados.
        </div>
    </div>
</footer>