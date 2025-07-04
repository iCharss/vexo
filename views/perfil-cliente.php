<?php
// Verificar sesión
if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] != '1') {
    header("Location: /login/cliente");
    exit();
}

$user = $_SESSION['user'];
// Obtener información adicional del cliente si es necesario
global $pdo;
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$user['username']]);
$userData = $stmt->fetch();
?>
<section class="profile-section">
    <div class="container">
        <div class="profile-header">
            <div class="profile-avatar">
                <img src="/public/img/profiles/<?= $userData['foto_perfil'] ?>" alt="Foto de perfil" class="avatar-img">
                <div class="avatar-upload">
                    <label for="avatar-input">
                        <i class="fas fa-camera"></i> Cambiar foto
                    </label>
                    <input type="file" id="avatar-input" accept="image/*">
                </div>
            </div>
            <div class="profile-info">
                <h1><?= $userData['nombre'] ?> <?= $userData['apellido'] ?></h1>
                <p class="profile-role"><i class="fas fa-user"></i> Cliente</p>
                <p class="profile-email"><i class="fas fa-envelope"></i> <?= $userData['email'] ?></p>
                <p class="profile-phone"><i class="fas fa-phone"></i> <?= $userData['telefono'] ?></p>
                
            </div>
        </div>
        
        <div class="profile-content">
            <div class="profile-card">
                <h2><i class="fas fa-home"></i> Información de contacto</h2>
                <div class="profile-details">
                    <p><strong>Dirección:</strong> <?= $userData['direccion'] ?></p>
                    <p class="localidad"><strong>Localidad:</strong> <?= $userData['localidad'] ?></p>
                </div>
                <a href="/editar-perfil-c" class="btn btn-secondary">
                    <i class="fas fa-edit"></i> Editar perfil
                </a>
            </div>
            
            <div class="profile-card">
                <h2><i class="fas fa-tools"></i> Mis solicitudes de servicio</h2>
                <a href="/pedidos" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Ver mis pedidos
                </a>
            </div>
        </div>
    </div>
</section>

<div id="editarModal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <div id="editarModalContent"></div>
    </div>
</div>