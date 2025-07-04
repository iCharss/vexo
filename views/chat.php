<div class="chat-container">
    <div class="chat-header">
        <h2>Chat sobre <?= htmlspecialchars($pedido['nombre_servicio']) ?></h2>
        <p>Código: <?= $pedido['codigo'] ?></p>
    </div>
    
    <div class="chat-messages" id="chatMessages">
        <!-- Los mensajes se cargarán aquí via AJAX -->
    </div>
    
    <div class="chat-input">
        <textarea id="mensajeTexto" placeholder="Escribe tu mensaje..."></textarea>
        <button id="enviarMensaje">Enviar
        </button>
    </div>
</div>
<div class="action-buttons">
    <a href="/" class="btn-accion error">Volver al inicio</a>
</div>

<script>
    const currentUsername = "<?= $_SESSION['user']['username'] ?? '' ?>";
</script>