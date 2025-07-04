<section class="pago-resultado">
    <div class="resultado-card success">
        <i class="fas fa-check-circle"></i>
        <h1>¡Pago exitoso!</h1>
        <p>Hemos recibido tu pago correctamente.</p>
        
        <?php if ($pedido): ?>
        <div class="pedido-info">
            <p><strong>Pedido #<?= $pedido['id'] ?></strong></p>
            <p>Estado: Finalizado</p>
        </div>
        <?php endif; ?>

        <div class="action-buttons">
            <a href="/" class="btn-accion success">Volver al inicio</a>
            <a href="/pedidos" class="btn-accion secundario">Ver mis pedidos</a>
        </div>
    </div>
</section>
