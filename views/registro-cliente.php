<?php
// app/views/registro-cliente.php
?>
<section class="auth-section">
    <div class="auth-container">
        <div class="auth-header">
            <h1>Registro de Cliente</h1>
            <p>Crea tu cuenta para solicitar servicios</p>
        </div>
        
        <div class="auth-main">
            <form id="registerForm" action="/auth/register" method="POST" class="auth-form">
                <input type="hidden" name="tipo" value="cliente">
                
                <div class="auth-form-row">
                    <div class="auth-form-group">
                        <label for="nombre">Nombre*</label>
                        <input type="text" id="nombre" name="nombre" required maxlength="10">
                        <small class="invalid-feedback">Este campo es obligatorio</small>
                    </div>
                    
                    <div class="auth-form-group">
                        <label for="apellido">Apellido*</label>
                        <input type="text" id="apellido" name="apellido" required maxlength="15">
                        <small class="invalid-feedback">Este campo es obligatorio</small>
                    </div>
                </div>
                
                <div class="auth-form-row">
                    <div class="auth-form-group">
                        <label for="direccion">Dirección*</label>
                        <input type="text" id="direccion" name="direccion" required maxlength="25">
                        <small class="invalid-feedback">Este campo es obligatorio</small>
                    </div>
                    
                    <div class="auth-form-group">
                        <label for="localidad">Localidad*</label>
                        <input type="text" id="localidad" name="localidad" required maxlength="25">
                        <small class="invalid-feedback">Este campo es obligatorio</small>
                    </div>
                </div>
                
                <div class="auth-form-row">
                    <div class="auth-form-group">
                        <label for="telefono">Teléfono*</label>
                        <input type="tel" id="telefono" name="telefono" required maxlength="11">
                        <small class="invalid-feedback">Este campo es obligatorio</small>
                    </div>
                    <div class="auth-form-group">
                        <label for="email">Email*</label>
                        <input type="email" id="email" name="email" required>
                        <small class="invalid-feedback">Por favor ingresa un email válido</small>
                    </div>
                </div>
                
                <div class="auth-form-row">
                    <div class="auth-form-group">
                        <label for="username">Usuario*</label>
                        <input type="text" id="username" name="username" required maxlength="10">
                        <small class="invalid-feedback"></small>
                    </div>
                </div>
                    
                <div class="auth-form-row">
                    <div class="auth-form-group">
                        <label for="password">Contraseña*</label>
                        <input type="password" id="password" name="password" required minlength="6">
                        <small class="invalid-feedback"></small>
                        <span onclick="togglePassword()" style="position: absolute; right: 10px; top: 73%; transform: translateY(-50%); cursor: pointer;">
                            <i id="toggleIcon" class="fa-solid fa-eye"></i>
                        </span>
                    </div>

                    <div class="auth-form-group">
                        <label for="confirm_password">Confirmar Contraseña*</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                        <small class="invalid-feedback"></small>
                        <span onclick="toggleConfirmPassword()" style="position: absolute; right: 10px; top: 73%; transform: translateY(-50%); cursor: pointer;">
                            <i id="toggleIcon2" class="fa-solid fa-eye"></i>
                        </span>
                    </div>
                </div>
                    
                    <div class="auth-form-group">
                        <label for="foto_perfil">Foto de Perfil</label>
                        <div class="auth-file-upload">
                            <input type="file" id="foto_perfil" name="foto_perfil" accept="image/*">
                            <label for="foto_perfil" class="auth-file-label">
                                <i class="fas fa-camera"></i> Subir foto(opcional)
                            </label>
                        </div>
                        <div id="preview-foto-perfil" class="auth-image-preview"></div>
                    </div>
                
                <div class="auth-terms">
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms">Acepto los <a href="/terminos-y-condiciones" target="_blank">términos y condiciones</a></label>
                </div>
                
                <button type="submit" class="auth-submit-btn">
                    <i class="fas fa-user-plus"></i> Registrarse
                </button>
            </form>
            
            <div class="auth-social">
                <p>O regístrate con</p>
                <a href="/auth/google" class="auth-btn-google">
                    <i class="fab fa-google"></i> Google
                </a>
            </div>
        </div>
        
        <div class="auth-footer">
            <p>¿Ya tienes cuenta? <a href="/login/cliente">Inicia sesión aquí</a></p>
        </div>
    </div>
</section>