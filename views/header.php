<?php
$css = '../public/assets/style.css';
$cssMobile = '../public/assets/mobile.css';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <meta name="description" content="Vexo ofrece soluciones confiables y rápidas para el hogar, tecnología y software. Reparaciones, soporte técnico, desarrollo y más.">
    <meta name="keywords" content="reparaciones del hogar, técnicos a domicilio, plomería, electricista, gasista matriculado, pintura, albañilería, mantenimiento de PC, servicio técnico de computadoras, soporte técnico informático, desarrollo de software, instalación de cámaras de seguridad, automatización del hogar, domótica, instalación de alarmas, redes informáticas, soluciones tecnológicas, servicios a domicilio en Argentina, servicio técnico confiable, técnicos en Buenos Aires, soporte informático a domicilio, servicios urgentes para el hogar, tecnología para el hogar, programación a medida">

    <link rel="icon" href="https://vexo.com.ar/public/img/x.png" type="image/png">
    
    <!-- CSS sin cache
    <link rel="stylesheet" href="../public/assets/style.css">
    <link rel="stylesheet" href="../public/assets/mobile.css" media="screen and (max-width: 768px)">
    -->
    
    <!-- CSS con cache busting -->
    <link rel="stylesheet" href="<?= $css ?>?v=<?= file_exists($css) ? filemtime($css) : time() ?>">
    <link rel="stylesheet" href="<?= $cssMobile ?>?v=<?= file_exists($cssMobile) ? filemtime($cssMobile) : time() ?>" media="screen and (max-width: 768px)">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="main-header">
        <div class="container">
            <div class="logo-container">
                <a href="/">
                    <img src="/public/img/vexoL.png" alt="Vixo Logo" class="logo">
                </a>
            </div>
            
            <nav class="main-nav">
                <ul>
                    <li><a href="/">Inicio</a></li>
                    <li><a href="/servicios">Servicios</a></li>
                    <li><a href="/contacto">Contacto</a></li>
                    <li><a href="/nosotros">¿Quiénes somos?</a></li>
                    <?php if(isset($_SESSION['user'])): ?>
                        <li class="profile-menu">
                            <a href="/perfil" class="profile-link">
                                <img src="/public/img/profiles/<?= $_SESSION['user']['foto_perfil'] ?>" alt="Foto de perfil" class="profile-pic">
                                <span>Mi Perfil</span>
                            </a>
                            <ul class="profile-dropdown">
                                <a href="/perfil"><li><i class="fas fa-user"></i> Perfil</li></a>
                                <a href="/logout"><li><i class="fas fa-sign-out-alt"></i> Cerrar sesión</li></a>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li><a href="/registro/profesional">Trabaja con nosotros</a></li>
                        <li><a href="#" id="openLoginModal" class="cta-button">Ingresar</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
            
            <div class="mobile-menu-toggle">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </header>
    
    <div class="mobile-menu">
        <ul>
            <li><a href="/">Inicio</a></li>
            <li><a href="/servicios">Servicios</a></li>
            <li><a href="/contacto">Contacto</a></li>
            <li><a href="/nosotros">¿Quiénes somos?</a></li>
            <?php if(isset($_SESSION['user'])): ?>
                <li><a href="/perfil">Mi Perfil</a></li>
                <li><a href="/logout">Cerrar sesión</a></li>
                <?php else: ?>
                <li><a href="/registro/profesional">Trabaja con nosotros</a></li>
                <li class="cta-button"><a href="#" id="openLoginModal">Ingresar</a></li>
            <?php endif; ?>
        </ul>
    </div>

    <!-- Modal de Login -->
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h2>Selecciona tu tipo de usuario</h2>
            <div class="login-options">
                <a href="/login/cliente" class="btn btn-primary btn-large">
                    <i class="fas fa-user"></i> Registrate o ingresa como Cliente
                </a>
                <a href="/login/profesional" class="btn btn-primary btn-large">
                    <i class="fas fa-tools"></i> Ingresar como Profesional
                </a>
            </div>
            <div class="modal-logo">
                <img src="/public/img/x.png" alt="WeFix Logo" class="logo">
            </div>
        </div>
    </div>