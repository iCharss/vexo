<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Políticas de Privacidad | Vexo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --vexo-primary: #2c3e50;
            --vexo-secondary: #3498db;
            --vexo-accent: #e74c3c;
            --vexo-light: #ecf0f1;
            --vexo-dark: #2c3e50;
            --vexo-success: #27ae60;
            --gray-color: #dfdfdf;
            --dark-color: #2a154b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.8;
            color: #333;
            background: linear-gradient(145deg, var(--gray-color), #cecece 100%);
            display: flex;
            min-height: 100vh;
        }

        /* Barra lateral */
        .terms-sidebar {
            width: 280px;
            background: linear-gradient(145deg, var(--dark-color), #09121d 100%);
            color: white;
            padding: 30px 20px;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }

        .terms-sidebar-header {
            margin-bottom: 30px;
            text-align: center;
        }

        .terms-sidebar-header img {
            max-width: 150px;
            margin-bottom: 15px;
        }

        .terms-sidebar h3 {
            font-size: 1.3rem;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }

        .terms-nav {
            list-style: none;
        }

        .terms-nav li {
            margin-bottom: 10px;
        }

        .terms-nav a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 8px 15px;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .terms-nav a:hover, .terms-nav a:focus {
            background-color: rgba(255,255,255,0.1);
            color: var(--vexo-light);
        }

        .terms-nav a.active {
            background-color: var(--vexo-secondary);
            font-weight: 600;
        }

        /* Contenido principal */
        .terms-main-content {
            flex: 1;
            padding: 40px;
            overflow-y: auto;
        }

        .terms-container {
            max-width: 1100px;
            margin: 0 auto;
        }

        .terms-header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--vexo-secondary);
        }

        .terms-header h1 {
            font-size: 2.5rem;
            color: var(--vexo-primary);
            font-weight: 700;
        }

        .terms-header .update-date {
            font-size: 1rem;
            color: #7f8c8d;
            font-style: italic;
        }

        .terms-content {
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .terms-section {
            margin-bottom: 30px;
            scroll-margin-top: 20px;
        }

        .terms-section h2 {
            font-size: 1.5rem;
            color: var(--vexo-primary);
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
            font-weight: 600;
        }

        .terms-section p {
            margin-bottom: 15px;
            color: #555;
        }

        .terms-section ul {
            margin-left: 25px;
            margin-bottom: 20px;
        }

        .terms-section li {
            margin-bottom: 10px;
            color: #555;
        }

        .highlight {
            background-color: rgba(52, 152, 219, 0.1);
            padding: 20px;
            border-left: 4px solid var(--vexo-secondary);
            margin: 20px 0;
            border-radius: 0 4px 4px 0;
        }

        .terms-footer {
            text-align: center;
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: black;
            font-size: 0.9rem;
        }

        /* Estilo para los números de sección */
        .section-number {
            display: inline-block;
            width: 30px;
            height: 30px;
            background-color: var(--vexo-secondary);
            color: white;
            text-align: center;
            line-height: 30px;
            border-radius: 50%;
            margin-right: 10px;
            font-weight: bold;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .terms-sidebar {
                width: 220px;
                padding: 20px 15px;
            }
        }

        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }
            
            .terms-sidebar {
                width: 100%;
                height: auto;
                position: relative;
                padding: 20px;
            }
            
            .terms-main-content {
                padding: 20px;
            }
            
            .terms-header h1 {
                font-size: 2rem;
            }
            
            .terms-content {
                padding: 25px;
            }
            
            .terms-nav {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
            }
            
            .terms-nav li {
                margin-bottom: 0;
            }
            
            .terms-nav a {
                padding: 6px 12px;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            .terms-nav {
                flex-direction: column;
                gap: 5px;
            }
        }
    </style>
</head>
<body>
    <!-- Barra lateral de navegación -->
    <nav class="terms-sidebar">
        <div class="terms-sidebar-header">
            <a href="/"><img src="https://vexo.com.ar/public/img/vexoL.png" alt="Vexo Logo"></a>
            <h3>Políticas de Privacidad</h3>
        </div>
        
        <ul class="terms-nav">
            <li><a href="#introduccion">Introducción</a></li>
            <li><a href="#datos-recolectados">1. Datos que recopilamos</a></li>
            <li><a href="#uso-datos">2. Uso de tus datos</a></li>
            <li><a href="#compartir-datos">3. Compartir información</a></li>
            <li><a href="#proteccion-datos">4. Protección de datos</a></li>
            <li><a href="#derechos-usuarios">5. Tus derechos</a></li>
            <li><a href="#cookies">6. Cookies</a></li>
            <li><a href="#cambios-politica">7. Cambios en la política</a></li>
        </ul>
    </nav>

    <!-- Contenido principal -->
    <main class="terms-main-content">
        <div class="terms-container">
            <header class="terms-header">
                <h1>Políticas de Privacidad</h1>
                <p class="update-date">Última actualización: 05/06/2025</p>
            </header>

            <div class="terms-content">
                <div class="terms-section" id="introduccion">
                    <div class="highlight">
                        <p>En Vexo nos comprometemos a proteger la privacidad y los datos personales de todas las personas que usan nuestra plataforma. Esta política describe cómo recopilamos, usamos, almacenamos y protegemos tu información.</p>
                    </div>
                </div>

                <div class="terms-section" id="datos-recolectados">
                    <h2><span class="section-number">1</span> ¿Qué datos recopilamos?</h2>
                    <p>Recopilamos los siguientes tipos de datos:</p>
                    <ul>
                        <li><strong>Datos personales:</strong> nombre, apellido, DNI, dirección, teléfono, correo electrónico.</li>
                        <li><strong>Datos profesionales:</strong> oficio, rubro, experiencia, certificaciones, disponibilidad, tarifas (para profesionales).</li>
                        <li><strong>Datos de uso:</strong> historial de búsqueda, servicios contratados, comportamiento en la plataforma.</li>
                        <li><strong>Datos de pago:</strong> información para procesar cobros y transferencias (a través de proveedores autorizados).</li>
                        <li><strong>Datos técnicos:</strong> dirección IP, tipo de dispositivo, navegador y sistema operativo.</li>
                    </ul>
                </div>

                <div class="terms-section" id="uso-datos">
                    <h2><span class="section-number">2</span> ¿Para qué usamos tus datos?</h2>
                    <p>Utilizamos tus datos con los siguientes fines:</p>
                    <ul>
                        <li>Crear y administrar tu cuenta.</li>
                        <li>Facilitar el contacto entre usuarios y profesionales.</li>
                        <li>Procesar pagos de manera segura.</li>
                        <li>Prevenir fraudes o actividades sospechosas.</li>
                        <li>Comunicarnos con vos sobre tu cuenta o servicios.</li>
                        <li>Mejorar la experiencia en la plataforma.</li>
                        <li>Enviar comunicaciones relevantes (puedes desactivarlas en cualquier momento).</li>
                    </ul>
                </div>

                <div class="terms-section" id="compartir-datos">
                    <h2><span class="section-number">3</span> ¿Compartimos tu información?</h2>
                    <p><strong>No vendemos ni alquilamos tus datos personales.</strong></p>
                    <p>Solo compartimos tu información con:</p>
                    <ul>
                        <li>Otros usuarios con los que decidís interactuar (por ejemplo, cuando contratas un servicio).</li>
                        <li>Proveedores tecnológicos que nos permiten operar (pasarelas de pago, alojamiento, soporte).</li>
                        <li>Autoridades competentes si existe una obligación legal.</li>
                    </ul>
                    <div class="highlight">
                        <p>Todos nuestros proveedores están obligados contractualmente a proteger tus datos y solo pueden usarlos para los fines específicos que les indicamos.</p>
                    </div>
                </div>

                <div class="terms-section" id="proteccion-datos">
                    <h2><span class="section-number">4</span> ¿Cómo protegemos tu información?</h2>
                    <p>Aplicamos medidas técnicas y organizativas para garantizar la seguridad de tus datos, como:</p>
                    <ul>
                        <li>Encriptación de datos sensibles.</li>
                        <li>Accesos restringidos y autenticados.</li>
                        <li>Monitoreo de actividad para prevenir usos indebidos.</li>
                        <li>Revisiones periódicas de seguridad.</li>
                        <li>Capacitación a nuestro personal en protección de datos.</li>
                    </ul>
                    <p>A pesar de estas medidas, ninguna transmisión por Internet o almacenamiento electrónico es 100% segura. Te recomendamos usar contraseñas fuertes y no compartir tus credenciales.</p>
                </div>

                <div class="terms-section" id="derechos-usuarios">
                    <h2><span class="section-number">5</span> Tus derechos</h2>
                    <p>De acuerdo con la Ley de Protección de Datos Personales, podés solicitar en cualquier momento:</p>
                    <ul>
                        <li>Acceso a tus datos.</li>
                        <li>Rectificación o actualización.</li>
                        <li>Eliminación o anonimización.</li>
                        <li>Restricción del uso de tus datos.</li>
                        <li>Retiro del consentimiento para el uso de los mismos.</li>
                        <li>Portabilidad de tus datos a otro proveedor.</li>
                    </ul>
                    <p>Para ejercer estos derechos, escribinos a <a href="mailto:vexo@grupoyex.com">vexo@grupoyex.com</a>. Responderemos tu solicitud en un plazo máximo de 10 días hábiles.</p>
                </div>

                <div class="terms-section" id="cookies">
                    <h2><span class="section-number">6</span> Cookies y tecnologías similares</h2>
                    <p>Vexo puede usar cookies y tecnologías similares para:</p>
                    <ul>
                        <li>Mejorar la navegación y el rendimiento de la plataforma.</li>
                        <li>Recordar tus preferencias.</li>
                        <li>Analizar el uso del sitio para mejorarlo.</li>
                        <li>Personalizar contenido y anuncios.</li>
                    </ul>
                    <p>Podés configurar tu navegador para rechazar cookies, aunque esto puede afectar el funcionamiento del sitio. Al usar nuestra plataforma, aceptás el uso de estas tecnologías según esta política.</p>
                </div>

                <div class="terms-section" id="cambios-politica">
                    <h2><span class="section-number">7</span> Cambios en esta política</h2>
                    <p>Nos reservamos el derecho de actualizar esta política cuando lo creamos necesario. Te notificaremos sobre cambios significativos a través de:</p>
                    <ul>
                        <li>Un aviso en nuestra plataforma.</li>
                        <li>Un correo electrónico a la dirección asociada a tu cuenta.</li>
                    </ul>
                    <p>La versión vigente estará siempre disponible en nuestro sitio web. El uso continuado de Vexo después de los cambios implica la aceptación de la política revisada.</p>
                </div>
            </div>

            <footer class="terms-footer">
                <p>© 2025 Vexo. Todos los derechos reservados.</p>
                <p>Para consultas sobre privacidad: <a href="mailto:vexo@grupoyex.com">vexo@grupoyex.com</a></p>
            </footer>
        </div>
    </main>
</body>
</html>