<?php
// app/views/registro-profesional.php
global $pdo;
$servicios = $pdo->query("SELECT id, categoria FROM servicios")->fetchAll(PDO::FETCH_ASSOC);
?>
<section class="auth-section">
    <div class="auth-container">
        <div class="auth-header">
            <h1>Registro de Profesionales</h1>
            <p>Únete a nuestra red de expertos</p>
        </div>
        
        <div class="auth-main">
            <form id="registerForm" action="/auth/register" method="POST" enctype="multipart/form-data" class="auth-form">
                <input type="hidden" name="tipo" value="profesional">
                
                <div class="auth-form-row">
                    <div class="auth-form-group">
                        <label for="nombre">Nombre*</label>
                        <input type="text" id="nombre" name="nombre" required>
                        <small class="invalid-feedback">Este campo es obligatorio</small>
                    </div>
                    
                    <div class="auth-form-group">
                        <label for="apellido">Apellido*</label>
                        <input type="text" id="apellido" name="apellido" required>
                        <small class="invalid-feedback">Este campo es obligatorio</small>
                    </div>
                </div>
                
                <div class="auth-form-row">
                    <div class="auth-form-group">
                        <label for="direccion">Dirección*</label>
                        <input type="text" id="direccion" name="direccion" required minlength="5">
                    </div>
                    
                    <div class="auth-form-group">
                        <label for="localidad">Localidad*</label>
                        <input type="text" id="localidad" name="localidad" required maxlength="25">
                        <small class="invalid-feedback">Este campo es obligatorio</small>
                    </div>
                </div>
                
                <div class="auth-form-row">
                    <div class="auth-form-group">
                        <label for="cuil">Cuil*</label>
                        <input type="text" id="cuil" name="cuil" required>
                        <small class="invalid-feedback"></small>
                    </div>
                    
                    <div class="auth-form-group">
                        <label for="telefono">Teléfono*</label>
                        <input type="tel" id="telefono" name="telefono" required>
                        <small class="invalid-feedback">Este campo es obligatorio</small>
                    </div>
                </div>
                
                <div class="auth-form-row">                    
                    <div class="auth-form-group">
                        <label for="username">Usuario*</label>
                        <input type="text" id="username" name="username" required>
                        <small class="invalid-feedback"></small>
                    </div>
                    
                    <div class="auth-form-group">
                        <label for="email">Email*</label>
                        <input type="email" id="email" name="email" required>
                        <small class="invalid-feedback"></small>
                    </div>
                </div>
                
                <div class="auth-form-row">
                    <div class="auth-form-group">
                        <label for="password">Contraseña*</label>
                        <input type="password" id="password" name="password" required minlength="8">
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
                    <label>Servicios que ofreces*</label>
                    <div class="services-checkbox-container">
                        <?php foreach ($servicios as $servicio): ?>
                        <div class="service-checkbox">
                            <input type="checkbox" id="servicio_<?= $servicio['id'] ?>" name="servicios[]" value="<?= $servicio['id'] ?>">
                            <label for="servicio_<?= $servicio['id'] ?>">  <?= htmlspecialchars($servicio['categoria']) ?>  </label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                
                <div class="auth-form-row">
                    <div class="auth-form-group">
                        <label for="foto_perfil">Foto de Perfil*</label>
                        <div class="auth-file-upload">
                            <input type="file" id="foto_perfil" name="foto_perfil" accept="image/*">
                            <label for="foto_perfil" class="auth-file-label">
                                <i class="fas fa-camera"></i> Subir foto (obligatorio)
                            </label>
                        </div>
                        <div id="preview-foto-perfil" class="auth-image-preview"></div>
                    </div>
                </div>

                <div class="auth-form-row">
                    <div class="auth-form-group">
                        <label class="checkbox-container">
                            <input type="checkbox" id="no_matricula" name="no_matricula">
                            No tengo matrícula/certificación profesional
                            <span class="checkmark"></span>
                        </label>
                        <p class="auth-form-hint">Si no tenés matrícula profesional o no la encontrás en este momento, podés cargarla más adelante.</p>
                    </div>
                </div>
                
                <div class="auth-form-row">
                    <div class="auth-form-group">
                        <label for="nro_matricula">N° de Matrícula/Certificación*</label>
                        <input type="text" id="nro_matricula" name="nro_matricula">
                        <p class="auth-form-hint">Ejemplo: MAT-12345678</p>
                    </div>

                    <div class="auth-form-group">
                        <label for="documento">Documento que acredita tu profesión*</label>
                        <div class="auth-file-upload">
                            <input type="file" id="documento" name="documento" accept=".pdf,.jpg,.jpeg,.png">
                            <label for="documento" class="auth-file-label">
                                <i class="fas fa-file-upload"></i> Subir documento (PDF o imagen)
                            </label>
                        </div>
                        <p class="auth-form-hint">Puede ser tu matrícula, certificado o documento que acredite tu profesión</p>
                    </div>
                </div>
                
                
                <div class="auth-terms">
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms">Acepto los <a href="/terminos-y-condiciones" target="_blank">términos y condiciones</a> y <a href="/pautas-de-privacidad" target="_blank">política de privacidad</a></label>
                </div>
                
                <div class="auth-notice">
                    <i class="fas fa-info-circle"></i> Tu cuenta será revisada por un administrador. Te notificaremos por email cuando sea aprobada.
                </div>
                
                <button type="submit" class="auth-submit-btn">
                    <i class="fas fa-check-circle"></i> Registrar como Profesional
                </button>
            </form>
            
            <div class="auth-footer">
                <p>¿Ya tienes cuenta? <a href="/login/profesional">Inicia sesión aquí</a></p>
            </div>
        </div>
    </div>
</section>