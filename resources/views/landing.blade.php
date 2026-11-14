<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chef - Plataforma de Distribución y Negocios</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        /* Header/Navigation */
        header {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            color: white;
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            letter-spacing: 2px;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-left: 2rem;
            transition: opacity 0.3s;
            font-weight: 500;
        }

        nav a:hover {
            opacity: 0.8;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            color: white;
            padding: 4rem 2rem;
            text-align: center;
        }

        .hero-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .hero p {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            opacity: 0.95;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
            font-weight: 600;
        }

        .btn-primary {
            background: white;
            color: #ff6b6b;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }

        .btn-secondary {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 2px solid white;
        }

        .btn-secondary:hover {
            background: white;
            color: #ff6b6b;
        }

        /* Features Section */
        .features {
            background: #f9f9f9;
            padding: 4rem 2rem;
        }

        .features-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 3rem;
            color: #333;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .feature-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .feature-card h3 {
            color: #ff6b6b;
            margin-bottom: 1rem;
        }

        .feature-card p {
            color: #666;
            font-size: 0.95rem;
        }

        /* CTA Section - Formulario */
        .cta-section {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            padding: 4rem 2rem;
            color: white;
        }

        .cta-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .cta-section h2 {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .cta-section p {
            text-align: center;
            margin-bottom: 2rem;
            opacity: 0.95;
            font-size: 1.1rem;
        }

        /* Form Styles */
        .form-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-family: inherit;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #ff6b6b;
            box-shadow: 0 0 0 3px rgba(255,107,107,0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        @media (max-width: 640px) {
            .form-row {
                grid-template-columns: 1fr;
            }

            .hero h1 {
                font-size: 2rem;
            }

            .section-title {
                font-size: 1.8rem;
            }

            .hero-buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }

        .form-submit {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .form-submit:hover {
            transform: translateY(-2px);
        }

        .form-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 5px;
            border-left: 4px solid;
        }

        .alert-success {
            background: #d4edda;
            border-color: #28a745;
            color: #155724;
        }

        .alert-danger {
            background: #f8d7da;
            border-color: #dc3545;
            color: #721c24;
        }

        /* Stats Section */
        .stats {
            background: white;
            padding: 3rem 2rem;
            border-top: 1px solid #eee;
            border-bottom: 1px solid #eee;
        }

        .stats-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            text-align: center;
        }

        .stat-item h3 {
            font-size: 2.5rem;
            color: #ff6b6b;
            margin-bottom: 0.5rem;
        }

        .stat-item p {
            color: #666;
            font-weight: 500;
        }

        /* Footer */
        footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 2rem;
        }

        .loading {
            display: none;
            text-align: center;
        }

        .loading.show {
            display: block;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-container">
            <div class="logo">🍳 CHEF</div>
            <nav>
                <a href="#features">Características</a>
                <a href="#contact">Contacto</a>
                <a href="/login">Iniciar Sesión</a>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-container">
            <h1>Bienvenido a CHEF</h1>
            <p>La plataforma integral de distribución, gestión de inventario y conexión con clientesddd</p>
            <div class="hero-buttons">
                <a href="#contact" class="btn btn-primary">Registrarse Ahora</a>
                <a href="#features" class="btn btn-secondary">Conocer Más</a>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="stats-container">
            <div class="stat-item">
                <h3>500+</h3>
                <p>Clientes Activos</p>
            </div>
            <div class="stat-item">
                <h3>1000+</h3>
                <p>Productos</p>
            </div>
            <div class="stat-item">
                <h3>24/7</h3>
                <p>Soporte Disponible</p>
            </div>
            <div class="stat-item">
                <h3>99.9%</h3>
                <p>Uptime Garantizado</p>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="features-container">
            <h2 class="section-title">¿Por qué elegir CHEF?</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">📦</div>
                    <h3>Gestión de Inventario</h3>
                    <p>Control completo de tus productos, stock en tiempo real y alertas automáticas</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📊</div>
                    <h3>Análisis y Reportes</h3>
                    <p>Intuitive dashboards with key metrics to make better decisions</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🚀</div>
                    <h3>Rápido y Confiable</h3>
                    <p>Plataforma optimizada para máximo rendimiento y disponibilidad</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🔒</div>
                    <h3>Seguridad Garantizada</h3>
                    <p>Tus datos protegidos con encriptación de nivel empresarial</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">👥</div>
                    <h3>Gestión de Clientes</h3>
                    <p>Administra tus prospectos y clientes en un único lugar</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">💬</div>
                    <h3>Soporte Dedicado</h3>
                    <p>Equipo de expertos listo para ayudarte en todo momento</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section - Formulario -->
    <section class="cta-section" id="contact">
        <div class="cta-container">
            <h2>¡Únete a CHEF Hoy!</h2>
            <p>Completa el formulario y nos pondremos en contacto contigo en 24 horas</p>

            <div class="form-container">
                <div id="success-message" class="alert alert-success" style="display: none;">
                    ✅ ¡Gracias! Tu solicitud ha sido recibida. Nos contactaremos pronto.
                </div>
                <div id="error-message" class="alert alert-danger" style="display: none;">
                    ❌ Hubo un error. Por favor, intenta nuevamente.
                </div>

                <form id="prospect-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="full_name">Nombre Completo *</label>
                            <input
                                type="text"
                                id="full_name"
                                name="full_name"
                                placeholder="Tu nombre completo"
                                required
                            >
                        </div>
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                placeholder="tu@email.com"
                                required
                            >
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone">Teléfono *</label>
                            <input
                                type="tel"
                                id="phone"
                                name="phone"
                                placeholder="+1 (555) 000-0000"
                                required
                            >
                        </div>
                        <div class="form-group">
                            <label for="city">Ciudad</label>
                            <input
                                type="text"
                                id="city"
                                name="city"
                                placeholder="Tu ciudad"
                            >
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message">¿Cuéntanos sobre tu negocio? (Opcional)</label>
                        <textarea
                            id="message"
                            name="message"
                            rows="4"
                            placeholder="Déjanos saber qué tipo de negocio tienes o qué necesitas de CHEF..."
                        ></textarea>
                    </div>

                    <button type="submit" class="form-submit" id="submit-btn">
                        Enviar Solicitud
                    </button>

                    <div class="loading" id="loading">
                        <p>⏳ Enviando tu solicitud...</p>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2026 CHEF - Plataforma de Distribución. Todos los derechos reservados.</p>
    </footer>

    <script>
        const form = document.getElementById('prospect-form');
        const submitBtn = document.getElementById('submit-btn');
        const loading = document.getElementById('loading');
        const successMessage = document.getElementById('success-message');
        const errorMessage = document.getElementById('error-message');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            // Ocultar mensajes previos
            successMessage.style.display = 'none';
            errorMessage.style.display = 'none';

            // Mostrar loading
            submitBtn.disabled = true;
            loading.classList.add('show');

            try {
                const formData = {
                    full_name: document.getElementById('full_name').value,
                    email: document.getElementById('email').value,
                    phone: document.getElementById('phone').value,
                    city: document.getElementById('city').value,
                };

                const response = await fetch('/api/prospects', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(formData),
                });

                const data = await response.json();

                if (response.ok) {
                    successMessage.style.display = 'block';
                    form.reset();

                    // Scroll a mensaje de éxito
                    successMessage.scrollIntoView({ behavior: 'smooth' });
                } else {
                    errorMessage.style.display = 'block';
                    if (data.message) {
                        errorMessage.innerHTML = '❌ Error: ' + data.message;
                    }
                }
            } catch (error) {
                errorMessage.style.display = 'block';
                errorMessage.innerHTML = '❌ Error de conexión. Por favor, intenta nuevamente.';
                console.error('Error:', error);
            } finally {
                submitBtn.disabled = false;
                loading.classList.remove('show');
            }
        });

        // Smooth scroll para enlaces
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    </script>
</body>
</html>
