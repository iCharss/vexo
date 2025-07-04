<?php
// Verificar sesión
if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] != '2') {
    header("Location: /login/profesional");
    exit();
}

$user = $_SESSION['user'];
global $pdo;

// Obtener información básica del profesional
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$user['username']]);
$userData = $stmt->fetch();

// Obtener servicios del profesional
$stmt = $pdo->prepare("
    SELECT s.id, s.categoria 
    FROM usuario_servicios us
    JOIN servicios s ON us.servicio_id = s.id
    WHERE us.usuario_username = ?
");
$stmt->execute([$user['username']]);
$serviciosProfesional = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener trabajos activos (en curso)
$stmt = $pdo->prepare("
    SELECT p.*, s.categoria as nombre_servicio
    FROM pedidos p
    JOIN servicios s ON p.categoria_id = s.id
    WHERE p.profesional_username = ? AND p.estado = 'en_curso'
    ORDER BY p.fecha_necesidad ASC
    limit 3
");
$stmt->execute([$user['username']]);
$trabajosActivos = $stmt->fetchAll();

// Obtener historial de trabajos completados
$stmt = $pdo->prepare("
    SELECT p.*, s.categoria as nombre_servicio, pr.precio, pr.valoracion, p.codigo
    FROM pedidos p
    JOIN servicios s ON p.categoria_id = s.id
    LEFT JOIN pedidos_realizados pr ON p.id = pr.pedido_id
    WHERE p.profesional_username = ? AND p.estado = 'completado'
    ORDER BY p.created_at DESC
    LIMIT 3
");
$stmt->execute([$user['username']]);
$trabajosCompletados = $stmt->fetchAll();
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
                <p class="profile-role"><i class="fas fa-tools"></i> 
                <?php if (!empty($serviciosProfesional)): ?>
                    Profesional de <?= implode(', ', array_column($serviciosProfesional, 'categoria')) ?>
                    <?php else: ?>
                        Profesional sin servicios asignados
                        <?php endif; ?>
                    </p>
                    <p class="profile-matricula"><i class="fas fa-id-card"></i> <?= $userData['nro_matricula'] ?: 'Sin matricula' ?></p>
                    <p class="profile-address"><i class="fas fa-map-marker-alt"></i> <?= $userData['direccion'] ?></p>
                    <p class="profile-localidad"><i class="fas fa-map-marker-alt"></i> <?= $userData['localidad'] ?></p>
                    
                    <?php if ($userData['alta']): ?>
                        <p class="profile-status active"><i class="fas fa-check-circle"></i> Cuenta verificada</p>
                        <?php else: ?>
                            <p class="profile-status pending"><i class="fas fa-clock"></i> Cuenta profesional pendiente de aprobación</p>
                            <?php endif; ?>
                        </div>
                        <div class="profile-actions">
                            <a href="/editar-perfil-p" class="btn btn-secondary">
                                <i class="fas fa-edit"></i> Editar perfil
                            </a>
                        </div>
                    </div>
                    
        <div class="profile-content">
            <!-- Sección de Trabajos Activos -->
            <div class="profile-card">
                <h2><i class="fas fa-clock"></i> Trabajos Activos</h2>
                <?php if (count($trabajosActivos) > 0): ?>
                    <div class="jobs-list active-jobs">
                        <?php foreach ($trabajosActivos as $trabajo): ?>
                            <div class="job-item">
                                <div class="job-info">
                                    <h3><?= htmlspecialchars($trabajo['nombre_servicio'])?></h3>
                                    <p><?= htmlspecialchars($trabajo['tipo_trabajo']) ?></p>
                                    <p><strong>Fecha requerida:</strong> 
                                        <?= date('d/m/Y', strtotime($trabajo['fecha_necesidad'])) ?>
                                    </p>
                                    <p><strong>Dirección:</strong> <?= htmlspecialchars($trabajo['direccion']) ?></p>
                                    <p><strong>Código:</strong> <?= $trabajo['codigo'] ?></p>
                                </div>
                                <div class="job-actions">
                                    <button class="btn btn-outline btn-small ver-detalles" 
                                        data-pedido-id="<?= $trabajo['id'] ?>">
                                        <i class="fas fa-eye"></i> Ver detalles
                                    </button>

                                    <?php if ($trabajo['estado'] === 'en_curso' && ($trabajo['cliente_username'] === $_SESSION['user']['username'] || $trabajo['profesional_username'] === $_SESSION['user']['username'])): ?>
                                        <a href="/chat?pedido_id=<?= $trabajo['id'] ?>" class="btn-chat">Abrir Chat</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="no-jobs">No tienes trabajos activos en este momento.</p>
                <?php endif; ?>
                <a href="/solicitudes" class="btn btn-primary">
                    <i class="fas fa-list"></i> Ver solicitudes disponibles
                </a>
            </div>
            
            <!-- Sección de Historial de Trabajos -->
            <div class="profile-card">
                <h2><i class="fas fa-history"></i> Historial de Trabajos (Top 3)</h2>
                <?php if (count($trabajosCompletados) > 0): ?>
                    <div class="jobs-list">
                        <?php foreach ($trabajosCompletados as $trabajo): ?>
                            <div class="job-item">
                                <div class="job-info">
                                    <h3><?= htmlspecialchars($trabajo['nombre_servicio']) ?></h3>
                                    <p><?= htmlspecialchars($trabajo['tipo_trabajo']) ?></p>
                                    <p><strong>Fecha:</strong> 
                                        <?= date('d/m/Y', strtotime($trabajo['created_at'])) ?>
                                    </p>
                                    <?php if ($trabajo['valoracion']): ?>
                                        <p><strong>Valoración:</strong> 
                                            <?php for ($i = 0; $i < $trabajo['valoracion']; $i++): ?>
                                                <i class="fas fa-star"></i>
                                            <?php endfor; ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                                <button class="btn btn-outline btn-small ver-detalles" 
                                        data-pedido-id="<?= $trabajo['id'] ?>">
                                    <i class="fas fa-eye"></i> Ver detalles
                                </button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="no-jobs">No tienes trabajos completados todavía.</p>
                <?php endif; ?>
            </div>
            
            <?php if ($userData['alta']): ?>
                <div class="profile-card stats-card">
                    <h2><i class="fas fa-chart-line"></i> Mis estadísticas</h2>
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-value"><?= count($trabajosCompletados) ?></div>
                            <div class="stat-label">Trabajos completados</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">
                                <?php 
                                $valoraciones = array_column(
                                    array_filter($trabajosCompletados, fn($t) => isset($t['valoracion'])), 
                                    'valoracion'
                                );
                                echo count($valoraciones) > 0 ? round(array_sum($valoraciones) / count($valoraciones), 1) : '0';
                                ?>
                            </div>
                            <div class="stat-label">Valoración promedio</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value"><?= count($trabajosActivos) ?></div>
                            <div class="stat-label">Trabajos activos</div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Modal para detalles del pedido -->
<div id="pedidoModal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <div id="pedidoModalContent"></div>
    </div>
</div>

<!-- Modal para finalizar trabajo -->
<div id="finalizarModal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h2>Finalizar Trabajo</h2>
        <form id="finalizarForm">
            <input type="hidden" id="pedidoIdFinalizar" name="pedidoId">
            
            <div class="form-group">
                <label for="comentarios">Comentarios sobre el trabajo (opcional):</label>
                <textarea id="comentarios" name="comentarios" rows="4"></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-check"></i> Confirmar finalización
            </button>
        </form>
    </div>
</div>

<div id="editarProModal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <div id="editarProModalContent"></div>
    </div>
</div>