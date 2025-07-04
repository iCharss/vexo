<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecer Contraseña</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Estilos -->
    <link rel="stylesheet" href="/public/assets/style.css"> <!-- Asegúrate de tener tu estilo aquí -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<section class="auth-section">
    <div class="auth-container">
        <div class="auth-header">
            <h1>Restablecer contraseña</h1>
            <p>Crea una nueva contraseña para tu cuenta</p>
        </div>
        
        <div class="auth-main">
            <form id="resetPasswordForm" class="auth-form">
                <input type="hidden" id="resetToken" name="token" value="<?= htmlspecialchars($token) ?>">
                
                <div class="auth-form-row">
                    <div class="auth-form-group">
                        <label for="newPassword">Nueva contraseña*</label>
                        <input type="password" id="newPassword" name="password" required minlength="6">
                        <small class="invalid-feedback"></small>
                        <span onclick="togglePassword()" style="position: absolute; right: 10px; top: 73%; transform: translateY(-50%); cursor: pointer;">
                            <i id="toggleIcon" class="fa-solid fa-eye"></i>
                        </span>
                    </div>
                </div>

                <div class="auth-form-row">
                    <div class="auth-form-group">
                        <label for="confirmPassword">Confirmar contraseña*</label>
                        <input type="password" id="confirmPassword" name="confirm_password" required minlength="6">
                        <small class="invalid-feedback"></small>
                        <span onclick="toggleConfirmPassword()" style="position: absolute; right: 10px; top: 73%; transform: translateY(-50%); cursor: pointer;">
                            <i id="toggleIcon2" class="fa-solid fa-eye"></i>
                        </span>
                    </div>
                </div>

                <button type="submit" class="auth-submit-btn">
                    <i class="fas fa-save"></i> Guardar nueva contraseña
                </button>
            </form>
            
            <div id="resetPasswordMessage" class="auth-message" style="display: none;"></div>
        </div>
    </div>
</section>

<script>
    
function togglePassword() {
    const passwordInput = document.getElementById('newPassword');
    const toggleIcon = document.getElementById('toggleIcon');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}

function toggleConfirmPassword() {
    const confirmPasswordInput = document.getElementById('confirmPassword');
    const toggleIconConfirm = document.getElementById('toggleIcon2');

    if (confirmPasswordInput.type === 'password') {
        confirmPasswordInput.type = 'text';
        toggleIconConfirm.classList.remove('fa-eye');
        toggleIconConfirm.classList.add('fa-eye-slash');
    } else {
        confirmPasswordInput.type = 'password';
        toggleIconConfirm.classList.remove('fa-eye-slash');
        toggleIconConfirm.classList.add('fa-eye');
    }
}

// Función para enviar el formulario
document.getElementById('resetPasswordForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const password = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    const token = document.getElementById('resetToken').value;

    if (password !== confirmPassword) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Las contraseñas no coinciden'
        });
        return;
    }

    try {
        const response = await fetch('/auth/reset-password', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ token, password })
        });

        const data = await response.json();

        if (response.ok) {
            Swal.fire({
                icon: 'success',
                title: 'Contraseña actualizada',
                text: data.message
            });

            setTimeout(() => {
                window.location.href = '/';
            }, 2000);
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message
            });
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error del servidor',
            text: 'No se pudo procesar la solicitud.'
        });
    }
});
</script>

</body>
</html>