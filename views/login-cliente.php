<?php
// app/views/login-cliente.php
?>
<section class="auth-section">
    <div class="auth-container">
        <div class="auth-header">
            <h1>Ingreso de Clientes</h1>
            <p>Accede para solicitar servicios</p>
        </div>
        
        <div class="auth-main">
            <form id="loginForm" action="/auth/login" method="POST" class="auth-form">
                <input type="hidden" name="tipo1" value="cliente">
                
                <div class="auth-form-row">
                    <div class="auth-form-group">
                        <label for="username1">Usuario o Email*</label>
                        <input type="text" id="username1" name="username1" required>
                    </div>
                </div>

                <div class="auth-form-row">
                    <div class="auth-form-group">
                        <label for="password">Contraseña*</label>
                        <input type="password" id="password" name="password" required>
                        <span onclick="togglePassword()" style="position: absolute; right: 10px; top: 73%; transform: translateY(-50%); cursor: pointer;">
                            <i id="toggleIcon" class="fa-solid fa-eye"></i>
                        </span>
                    </div>
                </div>

                <button type="submit" class="auth-submit-btn">
                    <i class="fas fa-sign-in-alt"></i> Ingresar
                </button>
            </form>
            
            <div class="auth-social">
                <p>O ingresa con</p>
                <a href="/auth/google" class="auth-btn-google">
                    <i class="fab fa-google"></i> Google
                </a>
                <?php if (isset($_GET['error']) && $_GET['error'] === 'profesionales_deben_usar_login_normal'): ?>
                    <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle" style="margin-right: 8px;"></i>
                    Los profesionales deben usar el login para profesionales, no el inicio con Google.
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="auth-footer">
                <p>¿No tienes cuenta? <a href="/registro/cliente">Regístrate aquí</a></p>
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