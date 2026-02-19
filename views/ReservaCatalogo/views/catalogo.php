<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Vehículos | GO CAR</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/Sistema_RentACar/assets/css/ReservaCatalogo/style.css">
</head>

<body>
    <?php include __DIR__ . '/navbar.php'; ?>

    <!-- Hero Section del Catálogo -->
    <section class="catalog-hero"
        style="background-image: url('https://spcdn.shortpixel.ai/spio/ret_img,q_cdnize,to_webp,s_webp/girk.com.gt/wp-content/uploads/2022/01/toyota-22r-hilux-radiadores.jpeg');">
        <div class="catalog-hero-overlay"></div>
        <div class="container position-relative" style="z-index: 2;">
            <div class="hero-breadcrumb">
                <a href="/Sistema_RentACar/index.php">Inicio</a>
                <i class="fas fa-chevron-right"></i>
                <span>Catálogo</span>
            </div>
            <h1 class="hero-main-title">
                Conduce tu Ambición.
                <span class="hero-highlight">Rentas Premium Simplificadas.</span>
            </h1>
            <div class="hero-divider"></div>
            <p class="hero-description">
                Experimenta la máxima libertad en la carretera con nuestra flota de vehículos de alto rendimiento,
                diseñados para cada viaje y presupuesto.
            </p>
            <div class="hero-actions">
                <a href="#vehicleCatalog" class="btn-hero-primary">
                    <i class="fas fa-th-large"></i> Explorar Flota
                </a>
                <a href="#" class="btn-hero-outline">
                    <i class="fas fa-tags"></i> Ver Ofertas Especiales
                </a>
            </div>
            <div class="hero-stats">
                <div class="hero-stat">
                    <span class="hero-stat-number">9+</span>
                    <span class="hero-stat-label">Vehículos</span>
                </div>
                <div class="hero-stat-divider"></div>
                <div class="hero-stat">
                    <span class="hero-stat-number">3</span>
                    <span class="hero-stat-label">Ubicaciones</span>
                </div>
                <div class="hero-stat-divider"></div>
                <div class="hero-stat">
                    <span class="hero-stat-number">500+</span>
                    <span class="hero-stat-label">Clientes Satisfechos</span>
                </div>
            </div>
        </div>
        <a href="#vehicleCatalog" class="hero-scroll-indicator">
            <i class="fas fa-chevron-down"></i>
        </a>
    </section>

    <section class="catalogo-section py-5">
        <div class="container">
            <h2 class="text-center mb-5">Catálogo de Vehículos</h2>

            <!-- Filtros -->
            <div class="filters mb-4">
                <form class="row g-3">
                    <div class="col-md-4">
                        <label for="category" class="form-label text-white">Categoría</label>
                        <select id="category" class="form-select">
                            <option value="">Todas las categorías</option>
                            <option value="sedan">Sedán</option>
                            <option value="suv">SUV</option>
                            <option value="pickup">Pickup</option>
                            <option value="compacto">Compacto</option>
                            <option value="crossover">Crossover</option>
                            <option value="mini">Mini</option>
                            <option value="minivan">Minivan</option>
                            <option value="lujo">Lujo o Premium</option>
                            <option value="convertible">Convertible</option>
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
                        <label for="availability" class="form-label text-white">Disponibilidad</label>
                        <select id="availability" class="form-select">
                            <option value="">Cualquier disponibilidad</option>
                            <option value="disponible">Disponible</option>
                            <option value="no-disponible">No disponible</option>
                        </select>
                    </div>
                </form>
            </div>

            <!-- Vehículos -->
            <div class="row g-4" id="vehicleCatalog">

                <!-- Categoría: Sedán -->
                <div class="col-12">
                    <h3 class="category-title">Sedán</h3>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="car-card" data-category="sedan">
                        <div class="car-image-container">

                            <img src="data:image/jpeg;base64,/"
                                class="car-image" alt="Toyota Corolla">
                        </div>
                        <div class="car-info p-4">
                            <h4 class="car-title">Toyota Corolla</h4>
                            <p class="car-meta">2022 • Sedán Compacto</p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="car-price">$85</span>
                            </div>
                            <a href="reservas.php?car=toyota-corolla" class="btn btn-book-now w-100">Reservar</a>
                        </div>
                    </div>
                </div>

                <!-- Categoría: SUV -->
                <div class="col-12">
                    <h3 class="category-title">SUV</h3>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="car-card" data-category="suv">
                        <div class="car-image-container">
                            <img src="https://www.onlineauto.com.au/contentAsset/image/c5b285b0-bba4-4fc0-bfc7-f04b58346764 "
                                class="car-image" alt="Toyota RAV4">
                        </div>
                        <div class="car-info p-4">
                            <h4 class="car-title">Toyota RAV4</h4>
                            <p class="car-meta">2023 • SUV Compacto</p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="car-price">$110</span>
                            </div>
                            <a href="reservas.php?car=toyota-rav4" class="btn btn-book-now w-100">Reservar</a>
                        </div>
                    </div>
                </div>

                <!-- Categoría: Pickup -->
                <div class="col-12">
                    <h3 class="category-title">Pickups</h3>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="car-card" data-category="pickup">
                        <div class="car-image-container">

                            <img src="https://carsguide-res.cloudinary.com/image/upload/c_fit,h_841,w_1490,f_auto,t_cg_base/v1/editorial/2023-Toyota-Hilux-SR5-Ute-White-1001x565-(1).jpg"
                                class="car-image" alt="Toyota Hilux">
                        </div>
                        <div class="car-info p-4">
                            <h4 class="car-title">Toyota Hilux</h4>
                            <p class="car-meta">2023 • Pickup</p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="car-price">$130</span>
                            </div>
                            <a href="reservas.php?car=toyota-hilux" class="btn btn-book-now w-100">Reservar</a>
                        </div>
                    </div>
                </div>

                <!-- Categoría: Compacto -->
                <div class="col-12">
                    <h3 class="category-title">Compactos</h3>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="car-card" data-category="compacto">
                        <div class="car-image-container">
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTEyeJjtl1_TawqSyTzJe2CDNIXyTpotGsTGQ&s"
                                class="car-image" alt="Kia Picanto 2006">
                        </div>
                        <div class="car-info p-4">
                            <h4 class="car-title">Kia Picanto 2006</h4>
                            <p class="car-meta">2006 • Compacto</p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="car-price">$50</span>
                            </div>
                            <a href="reservas.php?car=kia-picanto" class="btn btn-book-now w-100">Reservar</a>
                        </div>
                    </div>
                </div>

                <!-- Categoría: Crossover -->
                <div class="col-12">
                    <h3 class="category-title">Crossover</h3>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="car-card" data-category="crossover">
                        <div class="car-image-container">
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSnpDvvAcYQZRoI0xYMsqetcUbR4n-lPLc_aQ&s"
                                class="car-image" alt="Crossover">
                        </div>
                        <div class="car-info p-4">
                            <h4 class="car-title">Crossover</h4>
                            <p class="car-meta">2023 • Crossover</p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="car-price">$95</span>
                            </div>
                            <a href="reservas.php?car=crossover" class="btn btn-book-now w-100">Reservar</a>
                        </div>
                    </div>
                </div>

                <!-- Categoría: Mini -->
                <div class="col-12">
                    <h3 class="category-title">Mini</h3>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="car-card" data-category="mini">
                        <div class="car-image-container">
                            <img src="https://www.lacuracaonline.com/media/catalog/product/4/6/463101700017_8.jpg?optimize=medium&bg-color=255,255,255&fit=bounds&height=700&width=700&canvas=700:700"
                                class="car-image" alt="Mini">
                        </div>
                        <div class="car-info p-4">
                            <h4 class="car-title">Mini</h4>
                            <p class="car-meta">2023 • Mini</p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="car-price">$60</span>
                            </div>
                            <a href="reservas.php?car=mini" class="btn btn-book-now w-100">Reservar</a>
                        </div>
                    </div>
                </div>

                <!-- Categoría: Minivan -->
                <div class="col-12">
                    <h3 class="category-title">Minivan</h3>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="car-card" data-category="minivan">
                        <div class="car-image-container">
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRAlbrymVJ7spOe0z0N2MCEyXvs8hx1lpldKw&s"
                                class="car-image" alt="Minivan">
                        </div>
                        <div class="car-info p-4">
                            <h4 class="car-title">Minivan</h4>
                            <p class="car-meta">2023 • Minivan</p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="car-price">$100</span>
                            </div>
                            <a href="reservas.php?car=minivan" class="btn btn-book-now w-100">Reservar</a>
                        </div>
                    </div>
                </div>

                <!-- Categoría: Lujo o Premium -->
                <div class="col-12">
                    <h3 class="category-title">Lujo o Premium</h3>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="car-card" data-category="lujo">
                        <div class="car-image-container">
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQWfsR6cgnaq7nMEzpl-mjoAfMJ3gCGzUhq5A&s"
                                class="car-image" alt="Vehículo de Lujo">
                        </div>
                        <div class="car-info p-4">
                            <h4 class="car-title">Vehículo de Lujo</h4>
                            <p class="car-meta">2023 • Premium</p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="car-price">$200</span>
                            </div>
                            <a href="reservas.php?car=vehiculo-lujo" class="btn btn-book-now w-100">Reservar</a>
                        </div>
                    </div>
                </div>

                <!-- Categoría: Convertible -->
                <div class="col-12">
                    <h3 class="category-title">Convertibles</h3>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="car-card" data-category="convertible">
                        <div class="car-image-container">
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR31WbMSju4OPRvW6t4dj8upKPJJG0g01jdHg&s"
                                class="car-image" alt="Convertible">
                        </div>
                        <div class="car-info p-4">
                            <h4 class="car-title">Convertible</h4>
                            <p class="car-meta">2023 • Convertible</p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="car-price">$150</span>
                            </div>
                            <a href="reservas.php?car=convertible" class="btn btn-book-now w-100">Reservar</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <?php include __DIR__ . '/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/Rent-a-car/assets/js/script.js"></script>
</body>

</html>