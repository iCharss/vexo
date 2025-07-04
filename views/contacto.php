<?php
global $pdo;
$servicios = $pdo->query("SELECT id, categoria FROM servicios")->fetchAll(PDO::FETCH_ASSOC);
?>
<section class="contact-hero">
    <div class="container">
        <h1>Contáctanos</h1>
        <p>Estamos listos para ayudarte con cualquier problema en tu hogar. Completa el formulario y nos pondremos en contacto contigo a la brevedad.</p>
    </div>
</section>

<section class="contact-section">
    <div class="container">
        <div class="contact-grid">
            <div class="contact-form">
                <h2>Envíanos un mensaje</h2>
                <form id="contactForm" action="/enviar-mensaje" method="POST">
                    <div class="form-group">
                        <label for="name">Nombre completo</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Correo electrónico</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Teléfono</label>
                        <input type="tel" id="phone" name="phone" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="service">Servicio de interés</label>
                        <select id="service" name="service">
                            <option value="">Selecciona un servicio</option>
                            <?php foreach ($servicios as $servicio): ?>
                                <option value="<?= $servicio['id'] ?>"><?= $servicio['categoria'] ?></option>
                            <?php endforeach; ?>
                            <option value="">Otro</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Mensaje</label>
                        <textarea id="message" name="message" rows="5" required></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <span id="btnText">Enviar mensaje</span>
                        <span id="btnSpinner" class="spinner" style="display: none;"></span>
                    </button>
                </form>
            </div>
            
            <div class="contact-info">
                <h2>Información de contacto</h2>
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <div class="info-content">
                        <h3>Teléfono</h3>
                        <p>+54 9 11 3270-1950</p>
                        <p>Emergencias: +54 9 11 3270-1950</p>
                    </div>
                </div>
                
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="info-content">
                        <h3>Email</h3>
                        <p>info@vexo.com</p>
                        <p>soporte@vexo.com</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="emergency-cta">
    <div class="container">
        <div class="emergency-content">
            <h2>¿Tienes una emergencia?</h2>
            <p>Llámanos ahora mismo y atenderemos tu urgencia en menos de 2 horas.</p>
            <a href="https://wa.me/5491132701950" target="_blank" class="btn btn-emergency">
                <i class="fas fa-phone-alt"></i> Llamar emergencia
            </a>
        </div>
    </div>
</section>