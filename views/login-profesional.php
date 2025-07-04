<?php
// app/views/login-profesional.php
?>
<section class="auth-section">
    <div class="auth-container">
        <div class="auth-header">
            <h1>Ingreso de Profesionales</h1>
            <p>Accede a tu cuenta para gestionar tus servicios</p>
        </div>
        
        <div class="auth-main">
            <form id="loginForm" action="/auth/login" method="POST" class="auth-form">
                <input type="hidden" name="tipo1" value="profesional">
                
                <div class="auth-form-group">
                    <label for="username1">Usuario*</label>
                    <input type="text" id="username1" name="username1" required>
                </div>
                
                <div class="auth-form-group">
                    <label for="password">Contraseña*</label>
                    <input type="password" id="password" name="password" required>
                    <span onclick="togglePassword()" style="position: absolute; right: 10px; top: 73%; transform: translateY(-50%); cursor: pointer;">
                            <i id="toggleIcon" class="fa-solid fa-eye"></i>
                        </span>
                </div>
                
                <div class="auth-notice">
                    <i class="fas fa-info-circle"></i> Solo profesionales verificados pueden ingresar
                </div>
                
                <button type="submit" class="auth-submit-btn">
                    <i class="fas fa-sign-in-alt"></i> Ingresar
                </button>
            </form>
            
            <div class="auth-footer">
                <p>¿No tienes cuenta? <a href="/registro/profesional">Regístrate como profesional</a></p>
                <p><a href="#" data-forgot-password>¿Olvidaste tu contraseña?</a></p>
            </div>
        </div>
    </div>
    
    <!-- Modal Olvidé contraseña -->
<div id="forgotPasswordModal" class="auth-modal" style="display: none;">
    <div class="auth-modal-content">
        <span class="auth-close-modal">&times;</span>
        <h2>Recuperar contraseña</h2>
        <form id="forgotPasswordForm" class="auth-form">
            <div class="auth-form-group">
                <label for="recoveryEmail">Correo electrónico*</label>
                <input type="email" id="recoveryEmail" name="email" required>
            </div>
            <button type="submit" class="auth-submit-btn">
                <i class="fas fa-paper-plane"></i> Enviar enlace
            </button>
        </form>
        <div id="forgotPasswordMessage" class="auth-message" style="display: none;"></div>
    </div>
</div>
</section>