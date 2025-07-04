<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="icon" href="https://vexo.com.ar/public/img/x.png" type="image/png">
     
    <title>VEXO - Sitio en Construcción</title>
    <style>
        :root {
            --primary-color: #c1121f; 
            --primary-dark: #c1121f;
            --primary-light: #f8ad9d;
            --secondary-color: #f1faee;
            --dark-color: #2a154b;
            --light-color: #f1faee;
            --gray-color: #8d99ae;
            --light-gray: #e9ecec;
            --white: #ffffff;
            --black: #000000;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(145deg, var(--dark-color), #09121d 100%);
            color: var(--dark-color);
            line-height: 1.6;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        /* Estilos para la animación de introducción */
        .intro-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(145deg, var(--dark-color), #09121d 100%);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }
        
        .animation-container {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .x-logo {
            position: absolute;
            width: 65px;
            height: auto;
            z-index: 2;
            opacity: 0;
            animation: fadeIn 1s forwards, slideLeft 1.5s 1s forwards;
        }
        
        .vexo-logo {
            position: absolute;
            width: 300px;
            height: auto;
            opacity: 0;
            animation: fadeIn 1s 1s forwards;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideLeft {
            0% { transform: translateX(0); }
            100% { transform: translateX(-200px); }
        }
        
        /* Ocultar el contenido principal inicialmente */
        .hero-section, .features-section, .services-preview, 
        .testimonials-section, .cta-section {
            opacity: 0;
            transition: opacity 0.5s ease;
        }
        
        .show-content {
            opacity: 1;
            display: block !important;
        }
        
        @media (max-width: 768px) {
            .animation-container {
                transform: translateX(30px); /* Ajustá el valor según lo que veas bien */
            }
        
            @keyframes slideLeft {
                0% { transform: translateX(0px); }
                100% { transform: translateX(-150px); } /* Menor desplazamiento en móvil */
            }
        
            .x-logo {
                width: 50px; /* más chico aún en móviles si querés */
                animation: fadeIn 1s forwards, slideLeft 1.5s 1s forwards;
            }
        
            .vexo-logo {
                width: 220px; /* opcional, para mantener proporción */
            }
        }

        .construction-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
            text-align: center;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: --white;
        }

        .logo-container {
            margin-bottom: 2rem;
        }

        .logo {
            max-width: 200px;
            height: auto;
        }

        h1 {
            color: var(--white);
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        p {
            color: var(--white);
            font-size: 1.2rem;
            margin-bottom: 2rem;
            max-width: 600px;
        }

        .countdown {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin: 2rem 0;
        }

        .countdown-item {
            background-color: var(--white);
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            min-width: 80px;
        }

        .countdown-number {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary-color);
        }

        .countdown-label {
            font-size: 0.9rem;
            color: var(--gray-color);
        }

        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .social-link {
            color: var(--primary-color);
            font-size: 1.5rem;
            transition: color 0.3s;
        }

        .social-link:hover {
            color: var(--primary-dark);
        }

        footer {
            text-align: center;
            padding: 1rem;
            background-color: var(--white);
            color: var(--gray-color);
        }

        @media (max-width: 600px) {
            h1 {
                font-size: 2rem;
            }
            
            p {
                font-size: 1rem;
            }
            
            .countdown {
                flex-wrap: wrap;
            }
        }
    </style>
</head>
<body>
    
    <!-- Animación de bienvenida -->
    <div class="intro-animation" id="introAnimation">
        <div class="animation-container">
            <img src="/public/img/x.png" alt="X" class="x-logo">
            <img src="/public/img/vexo.png" alt="VEXO" class="vexo-logo">
        </div>
    </div>

    <div class="construction-container">
        <div class="logo-container">
            <!-- Reemplaza con tu logo -->
            <img src="/public/img/vexoL.png" alt="VEXO Logo" class="logo">
        </div>
        
        <h1>¡Estamos trabajando en algo increíble!</h1>
        
        <p>Nuestro sitio web está en construcción. Estamos preparando todo para ofrecerte la mejor experiencia. Vuelve pronto para descubrir lo que tenemos preparado para ti.</p>
        
        <div class="countdown">
            <div class="countdown-item">
                <div class="countdown-number" id="days">00</div>
                <div class="countdown-label">Días</div>
            </div>
            <div class="countdown-item">
                <div class="countdown-number" id="hours">00</div>
                <div class="countdown-label">Horas</div>
            </div>
            <div class="countdown-item">
                <div class="countdown-number" id="minutes">00</div>
                <div class="countdown-label">Minutos</div>
            </div>
            <div class="countdown-item">
                <div class="countdown-number" id="seconds">00</div>
                <div class="countdown-label">Segundos</div>
            </div>
        </div>
        
        <p>Mientras tanto, síguenos en nuestras redes sociales:</p>
        <div class="social-links">
            <a href="https://wa.me/5491132701950" class="social-link whatsapp">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                    <path fill="currentColor" d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
            </a>
            <a href="https://instagram.com/vexo.gx" class="social-link instagram">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                    <path fill="currentColor" d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                </svg>
            </a>
            <a href="https://www.linkedin.com/company/vexoservicios" class="social-link linkedin">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                    <path fill="currentColor" d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                </svg>
            </a>
        </div>
    </div>

    <script>
        // Contador regresivo (opcional)
        const countdownDate = new Date();
        countdownDate.setDate(countdownDate.getDate() + 7); // 7 días desde hoy
        
        function updateCountdown() {
            const now = new Date().getTime();
            const distance = countdownDate - now;
            
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            document.getElementById('days').textContent = days.toString().padStart(2, '0');
            document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
            document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
            document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');
        }
        
        updateCountdown();
        setInterval(updateCountdown, 1000);
        
        document.addEventListener('DOMContentLoaded', function() {
            // Verificar si ya se mostró la animación
            if (!sessionStorage.getItem('animationShown')) {
                // Mostrar la animación
                const introAnimation = document.getElementById('introAnimation');
                const xLogo = document.querySelector('.x-logo');
                const vexoLogo = document.querySelector('.vexo-logo');
                const mainContent = document.querySelectorAll('.hero-section, .features-section, .services-preview, .testimonials-section, .cta-section');
                
                // Iniciar la secuencia de animación
                setTimeout(() => {
                    // Después de que termine la animación (2.5 segundos)
                    setTimeout(() => {
                        // Ocultar animación
                        introAnimation.style.opacity = '0';
                        
                        // Mostrar contenido principal
                        mainContent.forEach(section => {
                            section.classList.add('show-content');
                        });
                        
                        // Eliminar el elemento de animación después de la transición
                        setTimeout(() => {
                            introAnimation.style.display = 'none';
                        }, 1500);
                        
                        // Marcar que la animación ya se mostró
                        sessionStorage.setItem('animationShown', 'true');
                    }, 3000);
                }, 100);
            } else {
                // Si ya se mostró la animación, ocultarla inmediatamente
                const introAnimation = document.getElementById('introAnimation');
                if (introAnimation) {
                    introAnimation.style.display = 'none';
                }
                
                // Mostrar contenido principal inmediatamente
                const mainContent = document.querySelectorAll('.hero-section, .features-section, .services-preview, .testimonials-section, .cta-section');
                mainContent.forEach(section => {
                    section.classList.add('show-content');
                });
            }
        });
    </script>
</body>
</html>