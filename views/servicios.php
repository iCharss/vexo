<?php 
// Verifica que $servicios esté definida
if (!isset($servicios)) {
    die('Error: No se recibieron datos de servicios');
}

// Función auxiliar para obtener iconos
function obtenerIconoServicio($categoria) {
    $iconos = [
        'Plomería' => 'fas fa-faucet',
        'Electricidad' => 'fas fa-bolt',
        'Gas' => 'fas fa-fire',
        'Pintura' => 'fas fa-paint-roller',
        'Carpintería' => 'fas fa-hammer',
        'Albañilería' => 'fas fa-trowel',
        'Limpieza de Hogar' => 'fas fa-broom',
        'Jardinería' => 'fas fa-leaf',
        'Aire Acondicionado' => 'fas fa-snowflake',
        'Electrodomésticos' => 'fas fa-plug',
        'Mudanzas' => 'fas fa-truck-moving',
        'Cerrajería' => 'fas fa-key',
        'Alarmas' => 'fas fa-shield-alt',
        'Community Manager' => 'fas fa-users',
        'Fletes'=> 'fas fa-truck',
        'Agente inmobiliario' => 'fas fa-building',
        'Consultoría de negocios' => 'fas fa-briefcase',
        'Reparacion de electrodomesticos'=> 'fas fa-tools',
        'Diseño UI/UX'=> 'fas fa-paint-brush',
        'Desarrollo de Software'=> 'fas fa-code',
        'Pequeños arreglos'=> 'fas fa-wrench'
    ];
    
    return $iconos[$categoria] ?? 'fas fa-tools';
}
?>

<section class="services-hero">
    <div class="container">
        <h1>Nuestros Servicios</h1>
        <p>Selecciona el servicio que necesitas y solicítalo en pocos pasos</p>
    </div>
</section>

<section class="services-grid">
    <div class="container">
        <div class="services-filter">
            <input type="text" id="serviceSearch" placeholder="Buscar servicio...">
        </div>
        
        <div class="services-container">
            <?php foreach ($servicios as $servicio): ?>
            <div class="service-card" data-service-id="<?= $servicio['id'] ?>">
                <div class="service-icon">
                    <i class="<?= obtenerIconoServicio($servicio['categoria']) ?>"></i>
                </div>
                <h3><?= htmlspecialchars($servicio['categoria']) ?></h3>
                <p><?= htmlspecialchars($servicio['descripcion']) ?></p>
                <button class="btn btn-primary request-service">Solicitar</button>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Modal para solicitar servicio -->
<div id="serviceModal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h2 id="modalServiceTitle"></h2>
        
        <form id="serviceRequestForm">
            <input type="hidden" id="serviceId" name="serviceId">
            
            <div id="serviceOptionsContainer"></div>
            
            <div class="form-group">
                <label for="problemDescription">Describe tu problema o necesidad:</label>
                <textarea id="problemDescription" name="problemDescription" required></textarea>
            </div>

            <div class="form-group">
                <div class="auth-file-upload">
                    <label for="prueba">Prueba del problema:</label>
                    <input type="file" id="prueba" name="prueba" accept="image/*">
                    <label for="prueba" class="auth-file-label">
                        <i class="fas fa-camera"></i> Subir foto del problema (opcional):
                    </label>
                    <small class="form-text">Formatos aceptados: JPG, PNG, JPEG, GIF (máx. 5MB)</small>
                </div>
                <div id="filePreview" style="margin-top: 10px;"></div>
            </div>
            
            <div class="form-group">
                <label>Localidad donde se necesita el servicio:</label>
                <div class="address-info">
                    <i class="fas fa-map-marker-alt"></i>
                    <span id="userAddressDisplay">Cargando tu dirección...</span>
                </div>
            </div>
            
            <div class="form-group">
                <label for="serviceDate">¿Cuándo necesitas el servicio?</label>
                <input type="date" id="serviceDate" name="serviceDate" min="<?= date('Y-m-d') ?>">
            </div>
            
            <button type="submit" class="btn btn-primary">Enviar Solicitud</button>
        </form>
    </div>
</div>