<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__.'/php_errors.log');
error_reporting(E_ALL);

// Verificar sesión y tipo de usuario
if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] != '1') {
    header("Location: /login/cliente");
    exit();
}

$user = $_SESSION['user'];
global $pdo;

// Obtener los pedidos del cliente con información del servicio
$stmt = $pdo->prepare("
    SELECT p.*, s.categoria as nombre_servicio, 
           pr.id as valoracion_id, pr.valoracion, pr.testimonio
    FROM pedidos p
    LEFT JOIN servicios s ON p.categoria_id = s.id
    LEFT JOIN pedidos_realizados pr ON p.id = pr.pedido_id AND pr.cliente_username = ?
    WHERE p.cliente_username = ? 
    ORDER BY p.created_at DESC
");
$stmt->execute([$user['username'], $user['username']]);
$pedidos = $stmt->fetchAll();
?>

<section class="pedidos-section">
    <div class="container">
        <div class="section-header">
            <h1>Mis Pedidos de Servicio</h1>
            <p>Aquí puedes ver el estado de todos tus pedidos y gestionarlos</p>
        </div>
        
        <div class="pedidos-container">
            <?php if (count($pedidos) > 0): ?>
                <div class="pedidos-grid">
                    <?php foreach ($pedidos as $pedido): 
                        $precio_oferta_aceptada = null;

                        // Decodificar información adicional
                        $ofertas = json_decode($pedido['ofertas'] ?? '[]', true);
                        $tieneOfertas = count($ofertas);
                        $ofertaAceptada = $pedido['profesional_username'] !== null;
                        
                        if ($ofertaAceptada && is_array($ofertas)) {
                            foreach ($ofertas as $oferta) {
                                if (
                                    isset($oferta['username']) &&
                                    $oferta['username'] === $pedido['profesional_username']
                                ) {
                                    $precio_oferta_aceptada = $oferta['oferta'] ?? null;
                                    break;
                                }
                            }
                        }
                    ?>
                        <div class="pedido-card" data-pedido-id="<?= $pedido['id'] ?>">
                            <div class="pedido-header">
                                <h3>#<?= htmlspecialchars($pedido['id']) ?> | <?= htmlspecialchars($pedido['nombre_servicio']) ?></h3>
                                <span class="status-badge <?= $pedido['estado'] ?>">
                                    <?= ucfirst(str_replace('_', ' ', $pedido['estado'])) ?>
                                </span>
                            </div>
                            
                            <div class="pedido-body">
                                <p class="pedido-desc"><?= htmlspecialchars($pedido['tipo_trabajo']) ?></p>
                                
                                <div class="pedido-meta">
                                    <div class="meta-item">
                                        <i class="fas fa-calendar-alt"></i>
                                        <span><?= date('d/m/Y', strtotime($pedido['created_at'])) ?></span>
                                    </div>
                                    
                                    <?php if ($pedido['fecha_necesidad']): ?>
                                    <div class="meta-item">
                                        <i class="fas fa-clock"></i>
                                        <span>Para <?= date('d/m/Y', strtotime($pedido['fecha_necesidad'])) ?></span>
                                    </div>
                                    <?php endif; ?>

                                    <?php if ($pedido['estado'] == 'en_curso'): ?>
                                    <div class="meta-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span><?= $pedido['codigo'] ?></span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="pedido-footer">
                                <?php if ($pedido['estado'] == 'pendiente'): ?>
                                <button class="btn btn-small btn-primary ver-presupuestos" data-pedido-id="<?= $pedido['id'] ?>">
                                    <span class="badge"><?= count($ofertas) ?></span> Presupuestos 
                                </button>
                                <?php endif; ?>

                                <?php if ($pedido['estado'] === 'en_curso' && ($pedido['cliente_username'] === $_SESSION['user']['username'] || $pedido['profesional_username'] === $_SESSION['user']['username'])): ?>
                                    <a href="/chat?pedido_id=<?= $pedido['id'] ?>" class="btn btn-small btn-primary">Abrir Chat</a>
                                <?php endif; ?>
                                
                                <?php if ($pedido['estado'] == 'pendiente' || $pedido['estado'] == 'en_curso'): ?>
                                <button class="btn btn-small btn-outline ver-detalles" data-pedido-id="<?= $pedido['id'] ?>">
                                    <i class="fas fa-eye"></i> Ver detalles
                                </button>
                                <?php endif; ?>
                                
                                <?php if ($pedido['estado'] == 'en_curso' && $pedido['cliente_username'] === $_SESSION['user']['username']): ?>
                                    <?php if (empty($pedido['pago_id'])): ?>
                                        <button class="btn btn-small btn-success finalizar-pedido" data-pedido-id="<?= $pedido['id'] ?>">
                                            <i class="fas fa-check-circle"></i> Finalizar y Pagar
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-small btn-success" disabled>
                                            <i class="fas fa-check-circle"></i> Pago realizado
                                        </button>
                                    <?php endif; ?>
                                <?php endif; ?>
                                
                                <?php if ($pedido['estado'] == 'completado' && $pedido['cliente_username'] === $_SESSION['user']['username'] && empty($pedido['valoracion_id'])): ?>
                                    <button class="btn btn-small btn-info valorar-pedido" 
                                            data-pedido-id="<?= $pedido['id'] ?>"
                                            data-profesional="<?= htmlspecialchars($pedido['profesional_username']) ?>"
                                            data-precio="<?= htmlspecialchars($precio_oferta_aceptada) ?>"
                                        <i class="fas fa-star"></i> Valorar Servicio
                                    </button>
                                <?php endif; ?>
    
                                <?php if ($pedido['estado'] == 'pendiente'): ?>
                                <button class="btn btn-small btn-danger cancelar-pedido" data-pedido-id="<?= $pedido['id'] ?>">
                                    <i class="fas fa-times"></i> Cancelar
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-pedidos">
                    <img src="/public/img/empty-orders.webp" alt="No hay pedidos" class="empty-illustration">
                    <h3>No tienes pedidos realizados</h3>
                    <p>Cuando solicites un servicio, aparecerá listado aquí</p>
                    <a href="/servicios" class="btn btn-primary">
                        <i class="fas fa-tools"></i> Solicitar un servicio
                    </a>
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

<!-- Modal para presupuestos -->
<div id="presupuestosModal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <div id="presupuestosModalContent"></div>
    </div>
</div>

<!-- Modal para valoración -->
<div id="valoracionModal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h2>Valorar Servicio</h2>
        
        <form id="valoracionForm">
            <input type="hidden" id="valoracionPedidoId" name="pedido_id">
            <input type="hidden" id="valoracionProfesional" name="profesional_username">
            <input type="hidden" id="valoracionPrecio" name="precio">
            
            <div class="form-group">
                <label>Valoración:</label>
                <div class="rating-stars">
                    <i class="fas fa-star" data-rating="1"></i>
                    <i class="fas fa-star" data-rating="2"></i>
                    <i class="fas fa-star" data-rating="3"></i>
                    <i class="fas fa-star" data-rating="4"></i>
                    <i class="fas fa-star" data-rating="5"></i>
                </div>
                <input type="hidden" id="valoracionInput" name="valoracion" required>
            </div>
            
            <div class="form-group">
                <label for="testimonio">Testimonio:</label>
                <textarea id="testimonio" name="testimonio" rows="4" required></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">Enviar Valoración</button>
        </form>
    </div>
</div>