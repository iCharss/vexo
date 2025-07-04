<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../public/assets/style.css">
    <style>
.chat-container {
    max-width: 800px;
    margin: 2rem auto;
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    background-color: #fff;
}

.chat-header {
    background-color: #0d6efd; /* Azul Bootstrap */
    color: white;
    padding: 1rem;
    text-align: center;
    font-size: 1.25rem;
    font-weight: bold;
}

.chat-box {
    max-height: 500px;
    overflow-y: auto;
    padding: 1rem 1.5rem;
    background-color: #f8f9fa;
}

.chat-message {
    background-color: #e9ecef;
    border-radius: 10px;
    padding: 0.75rem 1rem;
    margin-bottom: 1rem;
    position: relative;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.chat-message strong {
    display: block;
    margin-bottom: 0.25rem;
    font-weight: 600;
    color: #0d6efd;
}

.chat-message p {
    margin-bottom: 0.5rem;
    line-height: 1.4;
    word-wrap: break-word;
}

.chat-message small {
    font-size: 0.8rem;
    color: #6c757d;
}

.chat-box hr {
    border-top: 1px solid #ccc;
    margin-top: 0.75rem;
    margin-bottom: 0.75rem;
}

.btn-back {
    display: inline-block;
    margin-top: 1.5rem;
    text-decoration: none;
    background-color: #6c757d;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    transition: background-color 0.3s ease;
}

.btn-back:hover {
    background-color: #5a6268;
}
    </style>
</head>

<div class="chat-container">
    <h3>Chat del Pedido #<?= $pedido_id ?></h3>
    <div class="chat-box" >
        <?php if (empty($mensajes)): ?>
            <p>No hay mensajes aún.</p>
        <?php else: ?>    
        <?php foreach ($mensajes as $msg): ?>
            <div class="chat-message">
                <strong><?= htmlspecialchars($msg['remitente_username']) ?>:</strong>
                <p><?= nl2br(htmlspecialchars($msg['mensaje'])) ?></p>
                <small class="text-muted"><?= date('d/m/Y H:i', strtotime($msg['fecha_envio'])) ?></small>
                <hr>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    </div>

    <a href="/admin" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver a la lista
    </a>
</div>

<!-- Asegúrate de tener estos en tu head o antes del cierre del body -->
 <!-- jQuery (CDN) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JS (si estás usando modales) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../public/assets/main.js"></script>
</body>
</html>