<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../public/assets/style.css">
    <link rel="icon" href="https://vexo.com.ar/public/img/x.png" type="image/png">
    <title>VEXO | Administración.</title>
</head>
<div class="admin-container">
    <div class="admin-sidebar">
        <div class="admin-profile">
            <img src="/public/img/vixoL.jpg" alt="Admin Avatar">
            <h3><?= $_SESSION['user']['nombre'] ?></h3>
            <p>Administrador</p>
        </div>
        
        <nav class="admin-nav">
            <ul>
                <li class="active"><a href="#" data-tab="clientes"><i class="fas fa-users"></i> Clientes</a></li>
                <li><a href="#" data-tab="profesionales"><i class="fas fa-user-tie"></i> Profesionales</a></li>
                <li><a href="#" data-tab="servicios"><i class="fas fa-tools"></i> Servicios</a></li>
                <li><a href="#" data-tab="chats"><i class="fas fa-comments"></i> Chats</a></li>
                <li><a href="/admin/logout"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
            </ul>
        </nav>
    </div>
    
    <div class="admin-content">
        <!-- Sección de Clientes -->
        <div class="admin-section active" id="clientes-section">
            <h2><i class="fas fa-users"></i> Gestión de Clientes</h2>
            
            <div class="admin-table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clientes as $cliente): ?>
                        <tr>
                            <td><?= $cliente['username'] ?></td>
                            <td><?= $cliente['nombre'] ?> <?= $cliente['apellido'] ?></td>
                            <td><?= $cliente['email'] ?></td>
                            <td><?= $cliente['telefono'] ?? 'N/A' ?></td>
                            <td><?= date('d/m/Y', strtotime($cliente['created_at'])) ?></td>
                            <td>
                                <button class="btn btn-sm btn-info ver-datos" data-username="<?= $cliente['username'] ?>" data-tipo="cliente">
                                    <i class="fas fa-eye"></i> Ver
                                </button>
                                <button class="btn btn-sm btn-danger eliminar-usuario" data-username="<?= $cliente['username'] ?>">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Sección de Profesionales -->
        <div class="admin-section" id="profesionales-section">
            <h2><i class="fas fa-user-tie"></i> Gestión de Profesionales</h2>
            
            <div class="admin-table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Estado</th>
                            <th>Registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($profesionales as $profesional): ?>
                        <tr>
                            <td><?= $profesional['username'] ?></td>
                            <td><?= $profesional['nombre'] ?> <?= $profesional['apellido'] ?></td>
                            <td><?= $profesional['email'] ?></td>
                            <td>
                                <span class="badge <?= $profesional['alta'] ? 'badge-success' : 'badge-warning' ?>">
                                    <?= $profesional['alta'] ? 'Activo' : 'Pendiente' ?>
                                </span>
                            </td>
                            <td><?= date('d/m/Y', strtotime($profesional['created_at'])) ?></td>
                            <td>
                                <button class="btn btn-sm btn-info ver-datos" data-username="<?= $profesional['username'] ?>" data-tipo="profesional">
                                    <i class="fas fa-eye"></i> Ver
                                </button>
                                <?php if (!$profesional['alta']): ?>
                                <button class="btn btn-sm btn-success dar-alta" data-username="<?= $profesional['username'] ?>">
                                    <i class="fas fa-check"></i> Dar Alta
                                </button>
                                <?php endif; ?>
                                <button class="btn btn-sm btn-danger eliminar-usuario" data-username="<?= $profesional['username'] ?>">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Sección de Servicios -->
        <div class="admin-section" id="servicios-section">
            <h2><i class="fas fa-tools"></i> Gestión de Servicios</h2>
            
            <div class="admin-actions">
                <button class="btn btn-primary" id="agregar-servicio">
                    <i class="fas fa-plus"></i> Agregar Servicio
                </button>
            </div>
            
            <div class="admin-table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Categoría</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($servicios as $servicio): ?>
                        <tr>
                            <td><?= $servicio['id'] ?></td>
                            <td><?= $servicio['categoria'] ?></td>
                            <td><?= $servicio['descripcion'] ?? 'N/A' ?></td>
                            <td>
                                <button class="btn btn-sm btn-info editar-servicio" data-id="<?= $servicio['id'] ?>">
                                    <i class="fas fa-edit"></i> Editar
                                </button>
                                <button class="btn btn-sm btn-danger eliminar-servicio" data-id="<?= $servicio['id'] ?>">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Sección de Chats -->
        <div class="admin-section" id="chats-section">
            <div class="admin-chats-container">
                <h2><i class="fas fa-comments"></i> Chats entre Clientes y Profesionales</h2>

                <?php if (empty($chats)): ?>
                    <div class="alert alert-warning mt-4">
                        <i class="fas fa-exclamation-circle"></i> No hay chats activos por el momento.
                    </div>
                <?php else: ?>
                    <table class="table table-hover mt-4">
                        <thead>
                            <tr>
                                <th>ID Pedido</th>
                                <th>Cliente</th>
                                <th>Profesional</th>
                                <th>Último mensaje</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($chats as $chat): ?>
                            <tr>
                                <td>#<?= $chat['pedido_id'] ?></td>
                                <td><?= $chat['cliente'] ?></td>
                                <td><?= $chat['profesional'] ?></td>
                                <td><?= substr($chat['ultimo_mensaje'], 0, 50) ?>...</td>
                                <td>
                                    <a href="/admin/chat/<?= $chat['pedido_id'] ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> Ver chat
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal para ver datos de usuario -->
<div class="modal fade" id="usuarioModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles del Usuario</h5>
                <!-- Botón cerrar (la X) -->
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </button>
            </div>
            <div class="modal-body" id="usuarioModalBody">
                <!-- Datos se cargarán aquí via AJAX -->
            </div>
            <div class="modal-footer">
                <!-- Botón en el footer -->
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar/editar servicio -->
<div class="modal fade" id="servicioModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="servicioModalTitle">Agregar Nuevo Servicio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="servicioForm">
                <div class="modal-body">
                    <input type="hidden" id="servicioId" name="id">
                    <div class="mb-3">
                        <label for="categoria" class="form-label">Categoría</label>
                        <input type="text" class="form-control" id="categoria" name="categoria" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

 <!-- jQuery (CDN) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JS (si estás usando modales) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../public/assets/main.js"></script>
</body>
</html>