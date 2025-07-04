<?php
$user = $_SESSION['user'];
global $pdo;

// Obtener información básica del profesional
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$user['username']]);
$userData = $stmt->fetch();
?>

<section class="solicitudes-section">
    <div class="container">
        <div class="section-header">
            <h1>Solicitudes Disponibles</h1>
            <p>Estos son los trabajos disponibles para tus servicios profesionales</p>
            
            <div class="servicios-profesional">
                <h3>Tus especialidades:</h3>
                <div class="servicios-list">
                    <?php foreach ($serviciosProfesional as $servicio): ?>
                        <span class="servicio-badge"><?= htmlspecialchars($servicio['categoria']) ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <div class="solicitudes-container">
            <?php if (count($pedidos) > 0): ?>
                <div class="solicitudes-grid">
                    <?php foreach ($pedidos as $pedido): ?>
                        <div class="solicitud-card" data-pedido-id="<?= $pedido['id'] ?>">
                            <div class="solicitud-header">
                                <h3><?= htmlspecialchars($pedido['nombre_servicio']) ?></h3>
                                <span class="badge">Ofertas: <?= $pedido['num_ofertas'] ?></span>
                            </div>
                            
                            <div class="solicitud-body">
                                <p class="solicitud-desc"><?= htmlspecialchars($pedido['tipo_trabajo']) ?></p>
                                
                                <div class="solicitud-meta">
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
                                    
                                    <div class="meta-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span><?= htmlspecialchars($pedido['direccion']) ?></span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="solicitud-footer">
                                <?php if ($userData['alta']): ?>
                                    <button class="btn btn-primary btn-small enviar-oferta" 
                                            data-pedido-id="<?= $pedido['id'] ?>"
                                            <?= $pedido['ya_oferto'] ? 'disabled' : '' ?>>
                                        <i class="fas fa-paper-plane"></i> 
                                        <?= $pedido['ya_oferto'] ? 'Ya ofertaste' : 'Enviar presupuesto' ?>
                                    </button>
                                <?php endif; ?>
                                
                                <button class="btn btn-outline btn-small ver-detallesSolicitud" 
                                        data-pedido-id="<?= $pedido['id'] ?>">
                                    <i class="fas fa-eye"></i> Ver detalles
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-solicitudes">
                    <img src="/public/img/empty-orders.webp" alt="No hay solicitudes" class="empty-illustration">
                    <h3>No hay solicitudes disponibles</h3>
                    <p>Actualmente no hay trabajos disponibles para tus servicios profesionales</p>
                    <a href="/servicios" class="btn btn-primary">
                        <i class="fas fa-tools"></i> Ver servicios
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Modal para detalles de solicitud -->
<div id="solicitudModal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <div id="solicitudModalContent"></div>
    </div>
</div>

<!-- Modal para enviar oferta -->
<div id="ofertaModal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h2>Enviar Presupuesto</h2>
        
        <form id="ofertaForm">
            <input type="hidden" id="pedidoId" name="pedidoId">
            
            <div class="form-group">
                <label for="ofertaMonto">Monto ($):</label>
                <input type="number" id="ofertaMonto" name="ofertaMonto" min="1" required>
            </div>
            
            <div class="form-group">
                <label for="ofertaFecha">Fecha en que puedes realizar el trabajo:</label>
                <input type="date" id="ofertaFecha" name="ofertaFecha" min="<?= date('Y-m-d') ?>" required>
            </div>
            
            <div class="form-group">
                <label for="ofertaDescripcion">Descripción de tu oferta (opcional):</label>
                <textarea id="ofertaDescripcion" name="ofertaDescripcion" rows="4"></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">Enviar Oferta</button>
        </form>
    </div>
</div>