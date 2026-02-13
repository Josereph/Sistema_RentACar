<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Vehículos | GO CAR</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/Rent-a-car/assets/css/style.css">
</head>

<body>
    <?php include __DIR__ . '/navbar.php'; ?>

    <!-- Hero Section del Catálogo -->
    <section class="catalog-hero"
        style="background-image: url('https://spcdn.shortpixel.ai/spio/ret_img,q_cdnize,to_webp,s_webp/girk.com.gt/wp-content/uploads/2022/01/toyota-22r-hilux-radiadores.jpeg');">
        <div class="catalog-hero-overlay"></div>
        <div class="container position-relative" style="z-index: 2;">
            <div class="hero-breadcrumb">
                <a href="/Rent-a-car/index.php">Inicio</a>
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

                            <img src="data:image/jpeg;base64,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"
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