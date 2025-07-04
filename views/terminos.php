<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Términos y Condiciones | Vexo</title>
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
            <h3>Términos y Condiciones</h3>
        </div>
        
        <ul class="terms-nav">
            <li><a href="#introduccion">Introducción</a></li>
            <li><a href="#rol-vexo">1. Rol de Vexo</a></li>
            <li><a href="#responsabilidad">2. Responsabilidad</a></li>
            <li><a href="#validacion">3. Validación de perfiles</a></li>
            <li><a href="#pagos">4. Gestión de pagos</a></li>
            <li><a href="#laboral">5. Responsabilidad laboral</a></li>
            <li><a href="#cambios">6. Cambios en los Términos</a></li>
        </ul>
    </nav>

    <!-- Contenido principal -->
    <main class="terms-main-content">
        <div class="terms-container">
            <header class="terms-header">
                <h1>Términos y Condiciones de Uso</h1>
                <p class="update-date">Última actualización: <?= $fecha_actualizacion ?></p>
            </header>

            <div class="terms-content">
                <div class="terms-section" id="introduccion">
                    <div class="highlight">
                        <p>Vexo es una plataforma digital que conecta personas que necesitan servicios con profesionales independientes que los ofrecen. Al utilizar Vexo, aceptás los siguientes términos:</p>
                    </div>
                </div>

                <div class="terms-section" id="rol-vexo">
                    <h2><span class="section-number">1</span> Rol de Vexo</h2>
                    <p>Vexo actúa como intermediario digital, facilitando el contacto entre usuarios y profesionales. Nuestro rol es brindar la tecnología, herramientas de coordinación y un sistema de pagos seguro.</p>
                    <p>No somos empleadores, contratistas ni representantes de los profesionales.</p>
                </div>

                <div class="terms-section" id="responsabilidad">
                    <h2><span class="section-number">2</span> Responsabilidad del servicio</h2>
                    <p>El servicio contratado, sus condiciones, ejecución, calidad y cumplimiento son responsabilidad exclusiva entre el cliente y el profesional.</p>
                    <p>Vexo no se responsabiliza por problemas, daños, incumplimientos o conflictos que puedan surgir entre las partes, fuera de lo relacionado con el uso de la plataforma.</p>
                </div>

                <div class="terms-section" id="validacion">
                    <h2><span class="section-number">3</span> Validación de perfiles</h2>
                    <p>Los perfiles en Vexo pueden incluir verificaciones básicas (identidad, reputación, certificaciones), pero la plataforma no garantiza el desempeño ni el resultado final del servicio.</p>
                </div>

                <div class="terms-section" id="pagos">
                    <h2><span class="section-number">4</span> Gestión de pagos</h2>
                    <p>Vexo gestiona el sistema de cobro y pago entre el cliente y el profesional. Nos hacemos responsables de:</p>
                    <ul>
                        <li>Asegurar que el pago se realice de forma segura</li>
                        <li>Retener el pago hasta la validación del servicio (si aplica)</li>
                        <li>Liberarlo correctamente al profesional</li>
                    </ul>
                    <p>Vexo no se hace responsable por pagos realizados fuera de la plataforma.</p>
                </div>

                <div class="terms-section" id="laboral">
                    <h2><span class="section-number">5</span> Exclusión de responsabilidad laboral</h2>
                    <p>Vexo no mantiene relaciones laborales con los profesionales. Cualquier reclamo vinculado a empleo, ART, seguros, accidentes o derechos laborales es responsabilidad exclusiva del profesional o del cliente que haya contratado el servicio.</p>
                </div>

                <div class="terms-section" id="cambios">
                    <h2><span class="section-number">6</span> Cambios en los Términos</h2>
                    <p>Vexo podrá modificar estos Términos y Condiciones en cualquier momento. El uso continuado de la plataforma implica la aceptación de dichos cambios.</p>
                </div>
            </div>

            <footer class="terms-footer">
                <p>© <?= date('Y') ?> Vexo. Todos los derechos reservados.</p>
            </footer>
        </div>
    </main>

    
</body>
</html>