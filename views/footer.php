<?php
$js = '../public/assets/main.js';
$jsAnims = '../public/assets/animations.js';
$jsForm = '../public/assets/form.js';
?>

<!-- Footer -->
<footer class="main-footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-column">
                <img src="../public/img/vexoL.png" alt="vexo Logo" class="footer-logo">
                <p>Conectamos talento con necesidad.<br>Servicio técnico profesional.</p>
                <div class="social-icons">
                    <a href="https://www.instagram.com/vexo.gx/"><i class="fab fa-instagram"></i></a>
                    <a href="https://wa.me/5491132701950"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
            <div class="footer-column">
                <h3>Servicios</h3>
                <ul>
                    <li><a href="/servicios#plomeria">Plomería</a></li>
                    <li><a href="/servicios#electricidad">Electricidad</a></li>
                    <li><a href="/servicios#gas">Gas</a></li>
                    <li><a href="/servicios#pintura">Pintura</a></li>
                    <li><a href="/servicios">Todos los servicios</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Contacto</h3>
                <ul>
                    <li><i class="fas fa-phone"></i> +54 9 11 3270-1950</li>
                    <li><i class="fas fa-envelope"></i> info@vexo.com</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?= date('Y'); ?> Vexo. Todos los derechos reservados.</p>
            <div class="legal-links">
                <a href="/terminos-y-condiciones">Términos y condiciones</a>
                <a href="/pautas-de-privacidad">Política de privacidad</a>
            </div>
        </div>
    </div>
</footer>

<!--JavaScript sin cache
<script src="../public/assets/main.js"></script>
<script src="../public/assets/animations.js"></script>
<script src="../public/assets/form.js"></script>
-->

<!-- JavaScript con cache busting -->
<script src="<?= $js ?>?v=<?= file_exists($js) ? filemtime($js) : time() ?>"></script>
<script src="<?= $jsAnims ?>?v=<?= file_exists($jsAnims) ? filemtime($jsAnims) : time() ?>"></script>
<script src="<?= $jsForm ?>?v=<?= file_exists($jsForm) ? filemtime($jsForm) : time() ?>"></script>

<!-- Notificaciones y WhatsApp -->
<div class="notification-container" id="notificationContainer"></div>
<div class="whatsapp-section">
    <div class="whatsapp-card">
        <div class="whatsapp-content">
            <a href="https://wa.me/5491132701950?text=¡Hola%20Vexo!%20" class="whatsapp-button" target="_blank">
                <i class="fab fa-whatsapp"></i>
            </a>
        </div>
    </div>
</div>
</body>
</html>
