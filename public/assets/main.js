document.addEventListener('DOMContentLoaded', function() {
    // Modal de Login - Versión mejorada
    const modal = document.getElementById('loginModal');
    const btns = document.querySelectorAll('#openLoginModal'); // Todos los botones
    const span = document.querySelector('.close-modal');
    
    function openModal(e) {
        if (e) e.preventDefault();
        console.log('Opening modal');
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
        
        // Cerrar menú móvil si está abierto
        const mobileMenu = document.querySelector('.mobile-menu');
        if (mobileMenu && mobileMenu.classList.contains('active')) {
            mobileMenu.classList.remove('active');
            const menuToggleIcon = document.querySelector('.mobile-menu-toggle i');
            if (menuToggleIcon) {
                menuToggleIcon.classList.remove('fa-times');
                menuToggleIcon.classList.add('fa-bars');
            }
        }
    }
    
    function closeModal() {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
    
    if (modal) {
        // Configurar todos los botones de apertura
        btns.forEach(btn => {
            btn.addEventListener('click', openModal);
            btn.addEventListener('touchstart', openModal, {passive: false});
        });
        
        // Configurar el cierre del modal
        if (span) {
            span.addEventListener('click', closeModal);
            span.addEventListener('touchstart', closeModal, {passive: false});
        }
        
        // Cerrar al hacer clic fuera
        modal.addEventListener('click', function(e) {
            if (e.target === modal) closeModal();
        });
        
        // Cerrar al tocar fuera (para móviles)
        modal.addEventListener('touchstart', function(e) {
            if (e.target === modal) {
                e.preventDefault();
                closeModal();
            }
        }, {passive: false});
    }

    // Menú móvil mejorado
    const menuToggle = document.querySelector('.mobile-menu-toggle');
    const mobileMenu = document.querySelector('.mobile-menu');
    const profileMenu = document.querySelector('.profile-menu');

    menuToggle.addEventListener('click', function() {
        mobileMenu.classList.toggle('active');
        this.querySelector('i').classList.toggle('fa-bars');
        this.querySelector('i').classList.toggle('fa-times');
        
        // Cerrar el dropdown del perfil si está abierto
        if (profileMenu) {
            const dropdown = profileMenu.querySelector('.profile-dropdown');
            if (dropdown) {
                dropdown.style.display = 'none';
            }
        }
    });

    // Cerrar menú al hacer clic en un enlace (versión mejorada)
    const mobileLinks = document.querySelectorAll('.mobile-menu a');
    mobileLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if (this.id === 'openLoginModal') {
                e.preventDefault();
                modal.style.display = 'block';
                document.body.style.overflow = 'hidden';
                mobileMenu.classList.remove('active');
                return;
            }

            // Solo cerrar el menú si no es un dropdown
            if (!this.closest('.profile-dropdown')) {
                mobileMenu.classList.remove('active');
                const menuToggleIcon = document.querySelector('.mobile-menu-toggle i');
                if (menuToggleIcon) {
                    menuToggleIcon.classList.remove('fa-times');
                    menuToggleIcon.classList.add('fa-bars');
                }
            }
            
            // Manejar clicks en el dropdown del perfil
            if (this.closest('.profile-dropdown')) {
                e.stopPropagation(); // Evitar que el click se propague al menú principal
            }
        });
    });

    // Toggle para el dropdown del perfil en móvil
    if (profileMenu) {
        const profileLink = profileMenu.querySelector('.profile-link');
        const dropdown = profileMenu.querySelector('.profile-dropdown');
        
        if (profileLink && dropdown) {
            profileLink.addEventListener('click', function(e) {
                if (window.innerWidth <= 768) { // Solo en móvil
                    e.preventDefault();
                    const isDisplayed = dropdown.style.display === 'block';
                    dropdown.style.display = isDisplayed ? 'none' : 'block';
                }
            });
        }
    }
    
    // Efecto sticky header
    const header = document.querySelector('.main-header');
    let lastScroll = 0;
    
    window.addEventListener('scroll', function() {
        const currentScroll = window.pageYOffset;
        
        if (currentScroll <= 0) {
            header.classList.remove('scroll-up');
            return;
        }
        
        if (currentScroll > lastScroll && !header.classList.contains('scroll-down')) {
            header.classList.remove('scroll-up');
            header.classList.add('scroll-down');
        } else if (currentScroll < lastScroll && header.classList.contains('scroll-down')) {
            header.classList.remove('scroll-down');
            header.classList.add('scroll-up');
        }
        
        lastScroll = currentScroll;
    });
    
    // Animaciones al hacer scroll
    const animateElements = document.querySelectorAll('[class*="animate-"]');
    
    function checkAnimation() {
        animateElements.forEach(element => {
            const elementPosition = element.getBoundingClientRect().top;
            const screenPosition = window.innerHeight / 1.2;
            
            if (elementPosition < screenPosition) {
                const animationClass = Array.from(element.classList).find(cls => cls.startsWith('animate-'));
                const delay = element.getAttribute('data-delay') || 0;
                
                setTimeout(() => {
                    element.style.opacity = '1';
                    element.style.transform = 'translate(0, 0)';
                }, delay * 1000);
            }
        });
    }
    
    window.addEventListener('scroll', checkAnimation);
    checkAnimation(); // Ejecutar al cargar la página
    
    // Smooth scrolling para anclas
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 80,
                    behavior: 'smooth'
                });
            }
        });
    });
});

document.querySelectorAll('.auth-file-upload input[type="file"]').forEach(input => {
    input.addEventListener('change', function () {
        const label = this.nextElementSibling;
        const fileName = this.files.length > 0 ? this.files[0].name : '';
        if (fileName) {
            label.innerHTML = `<i class="fas fa-check-circle"></i> ${fileName}`;
        }

        // Mostrar preview si es imagen y es el input de foto de perfil
        if (this.id === 'foto_perfil' && this.files[0]) {
            const preview = document.getElementById('preview-foto-perfil');
            const file = this.files[0];
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview de foto" />`;
                };
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = '';
            }
        }
    });
});

// Manejo del formulario de login
const loginForms = document.querySelectorAll('#loginForm');
if (loginForms.length > 0) {
    loginForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Ingresando...';
            
            const tipoInput = this.querySelector('input[name="tipo1"]');
            if (tipoInput) {
                formData.append('tipo1', tipoInput.value);
            }
            
            fetch('/auth/login', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        throw new Error(text || 'Error en la respuesta del servidor');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data && data.success) {
                    showNotification('success', '¡Bienvenido!', 'Has iniciado sesión correctamente');
                    setTimeout(() => {
                        window.location.href = data.redirect || '/perfil';
                    }, 1500);
                } else {
                    throw new Error(data?.message || 'Error desconocido');
                }
            })
            .catch(error => {
                console.error('Error en login:', error);
                let errorMessage = 'Error al iniciar sesión';
                
                try {
                    const errorData = JSON.parse(error.message);
                    errorMessage = errorData.message || error.message;
                } catch (e) {
                    errorMessage = error.message;
                }
                
                showNotification('error', 'Error', errorMessage);
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Ingresar';
            });
        });
    });
}

function togglePassword() {
    const passwordInput = document.getElementById('password');
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
    const confirmPasswordInput = document.getElementById('confirm_password');
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


// Validación de formularios de registro
const registerForms = document.querySelectorAll('#registerForm');
if (registerForms.length > 0) {
    registerForms.forEach(form => {
        // Validación en tiempo real
        form.querySelectorAll('input, select, textarea').forEach(input => {
            input.addEventListener('blur', validateField);
            if (input.type === 'password' || input.name === 'username' || input.type === 'email'
                || input.name === 'documento') {
                input.addEventListener('input', validateField);
            }
        });

        // Validación al enviar
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            let isValid = true;
            form.querySelectorAll('[required]').forEach(input => {
                if (!validateField({ target: input })) {
                    isValid = false;
                }
            });
            

            // Verificar que las contraseñas coincidan
            const password = form.querySelector('#password');
            const confirmPassword = form.querySelector('#confirm_password');
            if (password && confirmPassword && password.value !== confirmPassword.value) {
                confirmPassword.classList.add('is-invalid');
                confirmPassword.nextElementSibling.textContent = 'Las contraseñas no coinciden';
                confirmPassword.nextElementSibling.style.display = 'block';
                isValid = false;
            }
            
            if (isValid) {
                const formData = new FormData(form);
                const submitBtn = form.querySelector('button[type="submit"]');
                const tipoInput = form.querySelector('input[name="tipo"]');
                if (tipoInput === 'profesional') {
                    const serviciosCheckboxes = form.querySelectorAll('input[name="servicios[]"]:checked');
                    if (serviciosCheckboxes.length === 0) {
                        const serviciosContainer = form.querySelector('.services-checkbox-container');
                        const feedback = form.querySelector('.auth-form-group .invalid-feedback');
                        if (feedback) {
                            feedback.style.display = 'block';
                            serviciosContainer.style.border = '1px solid #ff3860';
                            isValid = false;
                        }
                    }
                }
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Registrando...';
                
                fetch('/auth/register', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    console.log('Raw response:', response);
                    return response.text(); // Primero obtén el texto
                })
                .then(text => {
                    console.log('Response text:', text);
                    try {
                        return JSON.parse(text); // Intenta parsearlo como JSON
                    } catch (e) {
                        throw new Error('Invalid JSON: ' + text);
                    }
                })
                .then(data => {
                    if (data.success) {
                        const message = form.dataset.tipo === 'cliente' 
                            ? '¡Registro exitoso! Redirigiendo...' 
                            : 'Registro exitoso! Tu cuenta será revisada por un administrador.';
                        
                        showNotification('success', '¡Éxito!', message);
                        setTimeout(() => {
                            window.location.href = data.redirect;
                        }, 2000);
                    } else {
                        showNotification('error', 'Error', data.message);
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = form.dataset.tipo === 'cliente' 
                            ? '<i class="fas fa-user-plus"></i> Registrarse' 
                            : '<i class="fas fa-tools"></i> Registrarse como Profesional';
                        
                        if (data.errors) {
                            Object.keys(data.errors).forEach(field => {
                                const input = form.querySelector(`[name="${field}"]`);
                                if (input) {
                                    const formGroup = input.closest('.auth-form-group');
                                    const feedback = formGroup ? formGroup.querySelector('.invalid-feedback') : null;
                                    if (feedback) {
                                        input.classList.add('is-invalid');
                                        feedback.textContent = data.errors[field];
                                        feedback.style.display = 'block';
                                    }
                                }
                            });
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al conectar con el servidor');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = form.dataset.tipo === 'cliente' 
                        ? '<i class="fas fa-user-plus"></i> Registrarse' 
                        : '<i class="fas fa-tools"></i> Registrarse como Profesional';
                });
            }
        });
    });
}

function validateField(e) {
    const input = e.target;
    const formGroup = input.closest('.auth-form-group');
    let feedback = formGroup ? formGroup.querySelector('.invalid-feedback') : null;

    if (input.name === 'servicios[]') {
        const checkboxes = document.querySelectorAll('input[name="servicios[]"]:checked');
        if (checkboxes.length === 0) {
            const container = input.closest('.auth-form-group');
            const feedback = container.querySelector('.invalid-feedback');
            if (feedback) {
                feedback.style.display = 'block';
                return false;
            }
        }
    }

    
    // Reset estado
    input.classList.remove('is-invalid');
    if (feedback) feedback.style.display = 'none';

    // Validación requerida (excepto para matrícula/documento si el checkbox está marcado)
    const noMatriculaChecked = document.getElementById('no_matricula')?.checked;
    
    // Validación requerida
    if (input.required && !input.value.trim() && 
        !(noMatriculaChecked && (input.name === 'nro_matricula' || input.name === 'documento'))) {
        input.classList.add('is-invalid');
        if (feedback) {
            feedback.textContent = 'Este campo es obligatorio';
            feedback.style.display = 'block';
        }
        return false;
    }

    if (input.name === 'documento') {
            if (input.required && !input.files || input.files.length === 0) {
                input.classList.add('is-invalid');
                const feedback = input.nextElementSibling || input.parentNode.nextElementSibling;
                if (feedback) {
                    feedback.textContent = 'Este documento es obligatorio';
                    feedback.style.display = 'block';
                }
                return false;
            }
            
            // Validar extensión del archivo
            if (input.files && input.files.length > 0) {
                const allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png'];
                const fileName = input.files[0].name;
                const fileExt = fileName.split('.').pop().toLowerCase();
                
                if (!allowedExtensions.includes(fileExt)) {
                    input.classList.add('is-invalid');
                    const feedback = input.nextElementSibling || input.parentNode.nextElementSibling;
                    if (feedback) {
                        feedback.textContent = 'Formatos permitidos: PDF, JPG, PNG';
                        feedback.style.display = 'block';
                    }
                    return false;
                }
                
                // Validar tamaño del archivo (5MB)
                if (input.files[0].size > 5000000) {
                    input.classList.add('is-invalid');
                    const feedback = input.nextElementSibling || input.parentNode.nextElementSibling;
                    if (feedback) {
                        feedback.textContent = 'El archivo no debe superar 5MB';
                        feedback.style.display = 'block';
                    }
                    return false;
                }
            }
        }
    
    // Validación mejorada de email
    if (input.type === 'email' && input.value) {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const validDomains = [
            'gmail.com', 'hotmail.com', 'yahoo.com', 'outlook.com', 
            'hotmail.com.ar', 'yahoo.com.ar', 'live.com.ar', 'icloud.com',
            'protonmail.com', 'mail.com', 'zoho.com', 'yandex.com'
        ];
            
        if (!emailPattern.test(input.value)) {
            input.classList.add('is-invalid');
            if (feedback) {
                feedback.textContent = 'Ingresa un email válido';
                feedback.style.display = 'block';
            }
            return false;
        }
            
        // Verificar dominio
        const domain = input.value.split('@')[1];
        if (!validDomains.some(valid => domain === valid || domain.endsWith('.' + valid))) {
            input.classList.add('is-invalid');
            if (feedback) {
                feedback.textContent = 'Por favor usa un proveedor de email válido.';
                feedback.style.display = 'block';
            }
            return false;
        }
    }
    
    if (input.name === 'password' && input.value.length < 6) {
        input.classList.add('is-invalid');
        if (feedback) {
            feedback.textContent = 'La contraseña debe tener al menos 6 caracteres';
            feedback.style.display = 'block';
        }
        return false;
    }
    
    if (input.name === 'username' && input.value.length < 4) {
        input.classList.add('is-invalid');
        if (feedback) {
            feedback.textContent = 'El usuario debe tener al menos 4 caracteres';
            feedback.style.display = 'block';
        }
        return false;
    }
    
    return true;
}


// Verificar disponibilidad de username
document.querySelectorAll('input[name="username"]').forEach(input => {
    input.addEventListener('blur', function() {
        if (this.value.length >= 4) {
            fetch(`/auth/check-username?username=${this.value}`)
                .then(response => response.json())
                .then(data => {
                    if (!data.available) {
                        this.classList.add('is-invalid');
                        const feedback = this.nextElementSibling || this.parentNode.nextElementSibling;
                        if (feedback) {
                            feedback.textContent = 'Este nombre de usuario ya está en uso';
                            feedback.style.display = 'block';
                        }
                    }
                });
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const noMatriculaCheckbox = document.getElementById('no_matricula');
    const nroMatricula = document.getElementById('nro_matricula');
    const documento = document.getElementById('documento');

    if (noMatriculaCheckbox && nroMatricula && documento) {
        const matriculaField = nroMatricula.closest('.auth-form-row');
        const documentoField = documento.closest('.auth-form-row');

        if (matriculaField && documentoField) {
            function handleNoMatriculaChange() {
                if (noMatriculaCheckbox.checked) {
                    matriculaField.classList.add('hidden-field');
                    documentoField.classList.add('hidden-field');
                    nroMatricula.removeAttribute('required');
                    documento.removeAttribute('required');
                } else {
                    matriculaField.classList.remove('hidden-field');
                    documentoField.classList.remove('hidden-field');
                    nroMatricula.setAttribute('required', 'required');
                    documento.setAttribute('required', 'required');
                }
            }

            noMatriculaCheckbox.addEventListener('change', handleNoMatriculaChange);
            
            handleNoMatriculaChange();
        } else {
            console.warn('No se encontró el contenedor con la clase .auth-form-row');
        }
    }
});

function showNotification(type, title, message, duration = 1500) {
    const container = document.getElementById('notificationContainer');
    if (!container) return;
    
    const icons = {
        success: 'fas fa-check-circle',
        error: 'fas fa-exclamation-circle',
        info: 'fas fa-info-circle'
    };
    
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <i class="notification-icon ${icons[type]}"></i>
        <div class="notification-content">
            <div class="notification-title">${title}</div>
            <div class="notification-message">${message}</div>
        </div>
    `;
    
    container.appendChild(notification);
    
    // Eliminar la notificación después de la animación
    setTimeout(() => {
        notification.addEventListener('animationend', () => {
            notification.remove();
        });
    }, duration);
}

// Manejo de servicios
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM completamente cargado'); // Debug
    
    const serviceModal = document.getElementById('serviceModal');
    const serviceRequestForm = document.getElementById('serviceRequestForm');
    const serviceCards = document.querySelectorAll('.service-card');
    const serviceSearch = document.getElementById('serviceSearch');
    
    // Función para abrir el modal de servicio
    function openServiceModal(serviceId, serviceTitle) {
        console.log(`Abriendo modal para servicio ${serviceId}: ${serviceTitle}`); // Debug
        
        document.getElementById('modalServiceTitle').textContent = `Servicio de ${serviceTitle}`;
        document.getElementById('serviceId').value = serviceId;
        
        // Limpiar opciones anteriores
        const optionsContainer = document.getElementById('serviceOptionsContainer');
        optionsContainer.innerHTML = '';
        
        // Mostrar dirección del usuario
        fetch('/user/address')
            .then(response => {
                if (!response.ok) throw new Error('Error al obtener dirección');
                return response.json();
            })
            .then(data => {
                console.log('Datos de dirección recibidos:', data); // Debug
                if (data.success) {
                    document.getElementById('userAddressDisplay').textContent = data.localidad;
                } else {
                    document.getElementById('userAddressDisplay').textContent = 'Dirección/Localidad no configurada';
                    showNotification('error', 'Dirección requerida', 'Debes configurar tu dirección/localidad en tu perfil antes de solicitar servicios');
                }
            })
            .catch(error => {
                console.error('Error al obtener dirección:', error);
                document.getElementById('userAddressDisplay').textContent = 'Error al cargar dirección';
            });
        
        // Verificar si es el servicio de fletes (tiene_opciones = 2)
        fetch(`/servicios/${serviceId}/info`)
            .then(response => {
                if (!response.ok) throw new Error('Error al obtener info del servicio');
                return response.json();
            })
            .then(serviceInfo => {
                console.log('Información del servicio recibida:', serviceInfo); // Debug
                
                if (serviceInfo.tiene_opciones === 2) {
                    // Mostrar campos específicos para fletes
                    const fleteFields = `
                        <div class="form-group">
                            <label for="puntoPartida">Punto de partida:</label>
                            <input type="text" id="puntoPartida" name="puntoPartida" required>
                        </div>
                        <div class="form-group">
                            <label for="puntoFinal">Punto de llegada:</label>
                            <input type="text" id="puntoFinal" name="puntoFinal" required>
                        </div>
                        <div class="form-group">
                            <label for="kilometros">Kilómetros totales:</label>
                            <input type="number" id="kilometros" name="kilometros" min="1" required>
                        </div>
                        <input type="hidden" name="esFlete" value="1">
                    `;
                    optionsContainer.innerHTML = fleteFields;
                }
                else if (serviceInfo.tiene_opciones === 1) {
                    // Obtener opciones normales del servicio
                    fetch(`/servicios/${serviceId}/opciones`)
                        .then(response => {
                            if (!response.ok) throw new Error('Error al obtener opciones');
                            return response.json();
                        })
                        .then(options => {
                            console.log('Opciones del servicio recibidas:', options); // Debug
                            if (options && options.length > 0) {
                                const optionsTitle = document.createElement('h4');
                                optionsTitle.textContent = 'Opciones disponibles:';
                                optionsContainer.appendChild(optionsTitle);
                                
                                options.forEach(option => {
                                    const optionDiv = document.createElement('div');
                                    optionDiv.className = 'service-option';
                                    
                                    const input = document.createElement('input');
                                    input.type = 'radio';
                                    input.id = `option_${option.id}`;
                                    input.name = 'serviceOption';
                                    input.value = option.id;
                                    
                                    const label = document.createElement('label');
                                    label.htmlFor = `option_${option.id}`;
                                    
                                    label.appendChild(input);
                                    label.appendChild(document.createTextNode(option.nombre));
                                    
                                    optionDiv.appendChild(label);
                                    optionsContainer.appendChild(optionDiv);
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error al obtener opciones:', error);
                            showNotification('error', 'Error', 'No se pudieron cargar las opciones del servicio');
                        });
                }
            })
            .catch(error => {
                console.error('Error al obtener información del servicio:', error);
                showNotification('error', 'Error', 'No se pudo cargar la información del servicio');
            });
        
        serviceModal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    // Abrir modal de servicio
    serviceCards.forEach(card => {
        card.addEventListener('click', function(e) {
            if (e.target.classList.contains('request-service') || e.target.closest('.request-service')) {
                const serviceId = this.getAttribute('data-service-id');
                const serviceTitle = this.querySelector('h3').textContent;
                
                fetch('/auth/check-session')
                    .then(response => {
                        if (!response.ok) throw new Error('Error al verificar sesión');
                        return response.json();
                    })
                    .then(data => {
                        console.log('Datos de sesión:', data); // Debug
                        if (!data.loggedIn || data.userType !== 'cliente') {
                            showNotification('error', 'Acceso requerido', 'Debes iniciar sesión como cliente para solicitar servicios');
                            document.getElementById('openLoginModal').click();
                            return;
                        }
                        
                        openServiceModal(serviceId, serviceTitle);
                    })
                    .catch(error => {
                        console.error('Error al verificar sesión:', error);
                        showNotification('error', 'Error', 'No se pudo verificar tu sesión');
                    });
            }
        });
    });

    // Enviar formulario de solicitud
    if (serviceRequestForm) {
        serviceRequestForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Validaciones rápidas primero
            const addressDisplay = document.getElementById('userAddressDisplay').textContent;
            if (addressDisplay.includes('no configurada')) {
                showNotification('error', 'Dirección requerida', 'Configura tu dirección en el perfil primero');
                return;
            }
        
            // Mostrar loader inmediatamente
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';
            
            try {
                // Preparar FormData en paralelo con las validaciones
                const formData = new FormData();
                const esFlete = document.querySelector('input[name="esFlete"]');
                
                // Campos básicos (rápidos)
                formData.append('categoria_id', document.getElementById('serviceId').value);
                formData.append('tipo_trabajo', document.getElementById('problemDescription').value);
                formData.append('fecha_necesidad', document.getElementById('serviceDate').value);
                
                // Validaciones específicas
                if (esFlete && esFlete.value === '1') {
                    const puntoPartida = document.getElementById('puntoPartida').value.trim();
                    const puntoFinal = document.getElementById('puntoFinal').value.trim();
                    const kilometros = document.getElementById('kilometros').value.trim();
                    
                    if (!puntoPartida || !puntoFinal || !kilometros || isNaN(kilometros) || kilometros <= 0) {
                        throw new Error('Datos de flete incompletos');
                    }
                    
                    formData.append('esFlete', '1');
                    formData.append('puntoPartida', puntoPartida);
                    formData.append('puntoFinal', puntoFinal);
                    formData.append('kilometros', kilometros);
                } else {
                    const selectedOption = document.querySelector('input[name="serviceOption"]:checked');
                    if (selectedOption) {
                        formData.append('opciones_seleccionadas[]', selectedOption.value);
                    }
                }
                
                // Archivo adjunto (último, ya que es lo más pesado)
                const fileInput = document.getElementById('prueba');
                if (fileInput.files.length > 0) {
                    if (fileInput.files[0].size > 10 * 1024 * 1024) {
                        throw new Error('El archivo es demasiado grande (máx. 10MB)');
                    }
                    formData.append('prueba', fileInput.files[0]);
                }
        
                // Enviar con timeout y AbortController
                const controller = new AbortController();
                const timeoutId = setTimeout(() => controller.abort(), 10000); // 10 segundos timeout
                
                const response = await fetch('/pedidos/nuevo', {
                    method: 'POST',
                    body: formData,
                    signal: controller.signal
                });
                
                clearTimeout(timeoutId);
                
                if (!response.ok) {
                    const error = await response.json().catch(() => null);
                    throw new Error(error?.error || 'Error en el servidor');
                }
                
                const data = await response.json();
                
                if (!data.success) {
                    throw new Error(data.error || 'Error al procesar la solicitud');
                }
                
                // Éxito
                showNotification('success', 'Éxito', 'Solicitud enviada correctamente');
                serviceModal.style.display = 'none';
                document.body.style.overflow = 'auto';
                this.reset();
                document.getElementById('filePreview').innerHTML = '';
                
            } catch (error) {
                console.error('Error en el envío:', error);
                showNotification('error', 'Error', error.message || 'No se pudo completar la solicitud');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Enviar Solicitud';
            }
        });
    }
    
    // Mostrar vista previa del archivo
    document.addEventListener('DOMContentLoaded', () => {
        const inputFile = document.getElementById('prueba');
    
        inputFile.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('filePreview');
            preview.innerHTML = '';
            
            if (!file) return;
            
            // Validar tamaño (10MB máximo)
            if (file.size > 10 * 1024 * 1024) {
                showNotification('error', 'Archivo demasiado grande', 'El tamaño máximo permitido es 10MB');
                e.target.value = '';
                return;
            }
            
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.maxWidth = '200px';
                    img.style.maxHeight = '200px';
                    preview.appendChild(img);
                };
                reader.readAsDataURL(file);
            } else if (file.type.startsWith('video/')) {
                const video = document.createElement('video');
                video.controls = true;
                video.style.maxWidth = '200px';
                video.style.maxHeight = '200px';
                
                const source = document.createElement('source');
                source.src = URL.createObjectURL(file);
                source.type = file.type;
                
                video.appendChild(source);
                preview.appendChild(video);
            }
        });
    });

    
    // Buscador de servicios
    if (serviceSearch) {
        serviceSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            serviceCards.forEach(card => {
                const title = card.querySelector('h3').textContent.toLowerCase();
                const description = card.querySelector('p').textContent.toLowerCase();
                
                if (title.includes(searchTerm) || description.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }
    
    // Cerrar modal al hacer clic fuera
    window.addEventListener('click', function(e) {
        if (e.target === serviceModal) {
            serviceModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });
    
});

// Manejo de la página de pedidos
document.addEventListener('DOMContentLoaded', function() {
    const pedidoModal = document.getElementById('pedidoModal');
    const presupuestosModal = document.getElementById('presupuestosModal');
    
    // Cerrar modales con la X
    document.querySelectorAll('.modal .close-modal').forEach(btn => {
        btn.addEventListener('click', function() {
            const modal = this.closest('.modal');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        });
    });
    
    // Cerrar modales al hacer clic fuera
    window.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal')) {
            e.target.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });
    
    // Función para cargar detalles del pedido (versión mejorada)
    function cargarDetallesPedido(pedidoId) {
        console.log(`Cargando detalles del pedido ${pedidoId}`); // Debug
        
        // Mostrar loader
        const modalContent = document.getElementById('pedidoModalContent');
        modalContent.innerHTML = `
            <div class="loading-spinner">
                <i class="fas fa-spinner fa-spin"></i> Cargando detalles...
            </div>`;
        
        pedidoModal.style.display = 'block';
        document.body.style.overflow = 'hidden';
        
        fetch(`/api/pedidos/${pedidoId}`)
            .then(response => {
                console.log('Respuesta recibida, status:', response.status); // Debug
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Datos recibidos:', data); // Debug
                
                if (!data.success || !data.pedido) {
                    throw new Error(data.error || 'Datos del pedido no disponibles');
                }
                
                const pedido = data.pedido;
                
                // Manejo del campo prueba (corregido typo "prueba" vs "prueba")
                const prueba = pedido.prueba && pedido.prueba.trim() !== '' ? 
                    `<div class="profesional-info">
                        <p><a href="#" onclick="abrirModalFoto('/public/img/prueba/${pedido.prueba}')">Ver prueba</a></p>
                    </div>` : 
                    'No especificada';
                        
                const fechaCreacion = new Date(pedido.created_at).toLocaleDateString();
                const fechaNecesidad = pedido.fecha_necesidad 
                    ? new Date(pedido.fecha_necesidad + 'T00:00:00').toLocaleDateString('es-AR') 
                    : 'No especificada';
                const opciones = pedido.opciones_info || [];
                const fleteInfo = pedido.flete_info || null;
                const ofertaAceptada = pedido.profesional_username !== null;
                const puedeEditar = pedido.estado === 'pendiente';
                
                let html = `
                    <div class="pedido-detalles">
                        <div class="detalle-header">
                            <h2>Detalles del Pedido #${pedido.id}</h2>
                            <div class="status-badge ${pedido.estado}">
                                ${pedido.estado.replace('_', ' ')}
                            </div>
                        </div>
                        
                        <div class="detalle-grid">
                            <div class="detalle-card">
                                <h3><i class="fas fa-tools"></i> Servicio solicitado</h3>
                                <p>${pedido.nombre_servicio || 'Servicio no especificado'}</p>
                            </div>`;
                            
                if (fleteInfo) {
                    html += `
                        <div class="detalle-card">
                            <h3><i class="fas fa-truck"></i> Detalles del Flete</h3>
                            <div class="flete-details">
                                <p><strong>Punto de partida:</strong> ${fleteInfo.inicio}</p>
                                <p><strong>Punto final:</strong> ${fleteInfo.fin}</p>
                                <p><strong>Kilómetros:</strong> ${fleteInfo.kilometros_totales} km</p>
                            </div>
                        </div>`;
                }
                // Mostrar opciones seleccionadas
                else if (opciones.length > 0) {
                    html += `
                            <div class="detalle-card">
                                <h3><i class="fas fa-list-check"></i> Tipo de trabajo</h3>
                                <ul class="opciones-list">`;
                    
                    opciones.forEach(opcion => {
                        html += `<li>${opcion.nombre}</li>`;
                    });
                    
                    html += `</ul></div>`;
                }
                
                html += `            
                            <div class="detalle-card">
                                <h3><i class="fas fa-file-alt"></i> Descripción del trabajo</h3>
                                <p>${pedido.tipo_trabajo}</p>
                            </div>
    
                            <div class="detalle-card">
                                <h3><i class="fas fa-map-marker-alt"></i> Localidad</h3>
                                <p>${pedido.direccion || pedido.localidad || 'No especificada'}</p>
                            </div>
                            
                            <div class="detalle-card">
                                <h3><i class="fas fa-calendar-plus"></i> Fecha de creación</h3>
                                <p>${fechaCreacion}</p>
                            </div>
                            
                            <div class="detalle-card">
                                <h3><i class="fas fa-calendar-day"></i> Fecha necesitada ${puedeEditar ? `<button class="btn-edit-fecha"><i class="fas fa-edit"></i></button>` : ''}</h3>
                                <div class="fecha-container">
                                    <p>${fechaNecesidad}</p>
                                </div>
                                ${puedeEditar ? `
                                <div class="edit-fecha-container" style="display: none;">
                                    <input type="date" id="editFechaNecesidad" class="form-control" 
                                        value="${pedido.fecha_necesidad || ''}" min="${new Date().toISOString().split('T')[0]}">
                                    <div class="edit-fecha-buttons">
                                        <button class="btn btn-small btn-primary guardar-fecha">Guardar</button>
                                        <button class="btn btn-small btn-outline cancelar-edicion">Cancelar</button>
                                    </div>
                                </div>` : ''}
                            </div>
                            <div class="detalle-card">
                                <h3><i class="fas fa-file-alt"></i> Prueba</h3>
                                ${prueba}
                            </div>`;
                
                // Mostrar oferta aceptada si existe
                if (ofertaAceptada) {
                    html += `
                            <div class="detalle-card profesional-card">
                                <h3><i class="fas fa-user-tie"></i> Profesional asignado</h3>
                                <div class="profesional-info">
                                    <img src="/public/img/profiles/${pedido.profesional_foto || 'default.jpg'}" 
                                        alt="${pedido.profesional_nombre}" class="profesional-foto">
                                    <div>
                                        <p><strong>${pedido.profesional_nombre} ${pedido.profesional_apellido}</strong></p>
                                        <p><i class="fas fa-phone"></i> ${pedido.profesional_telefono || 'No especificado'}</p>
                                        ${pedido.profesional_oferta ? `<p><i class="fas fa-dollar-sign"></i> $${pedido.profesional_oferta}</p>` : ''}
                                    </div>
                                </div>
                            </div>`;
                }
                
                html += `</div></div>`;
                
                modalContent.innerHTML = html;
                
                // Configurar event listeners para edición de fecha
                if (puedeEditar) {
                    const btnEdit = document.querySelector('.btn-edit-fecha');
                    const fechaContainer = document.querySelector('.fecha-container');
                    const editContainer = document.querySelector('.edit-fecha-container');
                    
                    btnEdit.addEventListener('click', function() {
                        fechaContainer.style.display = 'none';
                        editContainer.style.display = 'block';
                    });
                    
                    document.querySelector('.cancelar-edicion').addEventListener('click', function() {
                        fechaContainer.style.display = 'block';
                        editContainer.style.display = 'none';
                    });
                    
                    document.querySelector('.guardar-fecha').addEventListener('click', function() {
                        const nuevaFecha = document.getElementById('editFechaNecesidad').value;
                        if (!nuevaFecha) {
                            showNotification('error', 'Error', 'Debes seleccionar una fecha válida');
                            return;
                        }
                        
                        actualizarFechaPedido(pedidoId, nuevaFecha);
                    });
                }
            })
            .catch(error => {
                console.error('Error al cargar detalles:', error);
                modalContent.innerHTML = `
                    <div class="error-message">
                        <h3>Error al cargar detalles</h3>
                        <p>${error.message || 'Ocurrió un error al obtener los detalles del pedido.'}</p>
                        <div class="error-actions">
                            <button class="btn btn-primary" onclick="cargarDetallesPedido(${pedidoId})">Reintentar</button>
                            <button class="btn btn-outline" onclick="pedidoModal.style.display='none'">Cerrar</button>
                        </div>
                    </div>`;
            });
    }
    
    // Función para actualizar la fecha del pedido
    function actualizarFechaPedido(pedidoId, nuevaFecha) {
        fetch(`/api/pedidos/${pedidoId}/actualizar-fecha`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ fecha_necesidad: nuevaFecha })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('success', 'Fecha actualizada', 'La fecha del pedido ha sido actualizada correctamente');
                cargarDetallesPedido(pedidoId); // Recargar los detalles
                setTimeout(() => location.reload(), 2000);
            } else {
                showNotification('error', 'Error', data.message || 'Error al actualizar la fecha');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('error', 'Error', 'No se pudo actualizar la fecha');
        });
    }
    
    // Función para cargar presupuestos
    function cargarPresupuestos(pedidoId) {
        fetch(`/api/pedidos/${pedidoId}/presupuestos`)
            .then(response => response.json())
            .then(data => {
                let html = `
                    <div class="presupuestos-container">
                        <h2>Presupuestos para el Pedido #${pedidoId}</h2>
                        <p>Estas son las ofertas que has recibido de los profesionales:</p>`;
                
                if (data.ofertas && data.ofertas.length > 0) {
                    data.ofertas.forEach((oferta, index) => {
                        html += `
                            <div class="oferta-card">
                                <div class="oferta-header">
                                    <div class="profesional-info">
                                        <img src="/public/img/profiles/${oferta.profesional.foto_perfil || 'default.jpg'}" 
                                             alt="${oferta.profesional.nombre}" class="profesional-foto">
                                        <div>
                                            <h4>${oferta.profesional.nombre} ${oferta.profesional.apellido}</h4>
                                            <p><i class="fas fa-tools"></i> ${oferta.profesional.categoria || 'No especificado'}</p>
                                        </div>
                                    </div>
                                    <div class="oferta-monto">
                                        <span>$${oferta.monto}</span>
                                    </div>
                                </div>
                                
                                <div class="oferta-body">
                                    <p><strong>Descripción:</strong> ${oferta.descripcion || 'Sin descripción adicional'}</p>
                                    <p><strong>Fecha propuesta:</strong> ${oferta.fecha_propuesta || 'No especificada'}</p>
                                </div>
                                
                                <div class="oferta-footer">
                                    <button class="btn btn-success aceptar-oferta" 
                                            data-pedido-id="${pedidoId}" 
                                            data-oferta-id="${index}">
                                        <i class="fas fa-check"></i> Aceptar oferta
                                    </button>
                                </div>
                            </div>`;
                    });
                } else {
                    html += `<p class="no-ofertas">No hay presupuestos disponibles para este pedido.</p>`;
                }
                
                html += `</div>`;
                document.getElementById('presupuestosModalContent').innerHTML = html;
                presupuestosModal.style.display = 'block';
                document.body.style.overflow = 'hidden';
                
                // Configurar event listeners para los botones de aceptar oferta
                document.querySelectorAll('.aceptar-oferta').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const pedidoId = this.getAttribute('data-pedido-id');
                        const ofertaId = this.getAttribute('data-oferta-id');
                        aceptarOferta(pedidoId, ofertaId);
                    });
                });
            })
            .catch(error => {
                console.error('Error al cargar presupuestos:', error);
                document.getElementById('presupuestosModalContent').innerHTML = `
                    <div class="error-message">
                        <p>Ocurrió un error al cargar los presupuestos.</p>
                        <button class="btn btn-primary" onclick="location.reload()">Recargar</button>
                    </div>`;
                presupuestosModal.style.display = 'block';
            });
    }
    
    
    // Event listeners para los botones de ver detalles
    document.querySelectorAll('.ver-detalles').forEach(btn => {
        btn.addEventListener('click', function() {
            const pedidoId = this.getAttribute('data-pedido-id');
            cargarDetallesPedido(pedidoId);
        });
    });
    
    // Modificar el event listener para los botones de presupuestos
    document.querySelectorAll('.ver-presupuestos').forEach(btn => {
        btn.addEventListener('click', function() {
            const pedidoId = this.getAttribute('data-pedido-id');
            const badge = this.querySelector('.badge');
            const numOfertas = parseInt(badge.textContent);
            
            if (numOfertas === 0) {
                showNotification('info', 'Presupuestos', 'Aún no has recibido ofertas para este pedido');
                return;
            }
            
            cargarPresupuestos(pedidoId);
        });
    });
    
    // Función para confirmaciones personalizadas
    async function confirmAction(options) {
        const defaults = {
            title: '¿Estás seguro?',
            text: 'Esta acción no se puede deshacer',
            confirmButtonText: 'Sí, continuar',
            cancelButtonText: 'Cancelar',
            icon: 'warning'
        };
        
        const config = { ...defaults, ...options };
        
        const result = await Swal.fire({
            title: config.title,
            text: config.text,
            icon: config.icon,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: config.confirmButtonText,
            cancelButtonText: config.cancelButtonText,
            customClass: {
                popup: 'custom-swal',
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-danger'
            }
        });
        
        return result.isConfirmed;
    }
    
    // Función para aceptar una oferta (versión mejorada con SweetAlert)
    async function aceptarOferta(pedidoId, ofertaId) {
        // Mostrar confirmación con SweetAlert
        const confirmResult = await Swal.fire({
            title: '¿Aceptar oferta?',
            text: '¿Estás seguro de que deseas aceptar esta oferta?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, aceptar',
            cancelButtonText: 'Cancelar',
            customClass: {
                popup: 'custom-swal',
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-outline-secondary'
            },
            buttonsStyling: false,
            reverseButtons: true
        });

        // Si el usuario cancela, salir de la función
        if (!confirmResult.isConfirmed) return;
        
        // Buscar el botón correspondiente
        const btn = document.querySelector(
            `.aceptar-oferta[data-pedido-id="${pedidoId}"][data-oferta-id="${ofertaId}"]`
        );
    
        if (!btn) return;
    
        // Mostrar spinner y desactivar el botón
        btn.disabled = true;
        btn.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Procesando...`;

        try {
            // Realizar la petición para aceptar la oferta
            const response = await fetch(`/api/pedidos/${pedidoId}/aceptar-oferta`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ ofertaId })
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Error al aceptar la oferta');
            }

            // Mostrar notificación de éxito
            await Swal.fire({
                title: '¡Oferta aceptada!',
                text: 'Has aceptado la oferta exitosamente',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false,
                timerProgressBar: true,
                willClose: () => {
                    presupuestosModal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                    location.reload();
                }
            });

        } catch (error) {
            // Restaurar el botón y mostrar error
            btn.disabled = false;
            btn.innerHTML = `<i class="fas fa-check"></i> Aceptar oferta`;
            
            await Swal.fire({
                title: 'Error',
                text: error.message,
                icon: 'error',
                confirmButtonText: 'Entendido'
            });
        }
    }

    // Función para cancelar pedido (versión mejorada)
    async function cancelarPedido(pedidoId) {
        const confirmed = await confirmAction({
            title: 'Cancelar pedido',
            text: '¿Estás seguro de que deseas cancelar este pedido?',
            icon: 'question'
        });
        
        if (!confirmed) return;
        
        try {
            const response = await fetch(`/api/pedidos/${pedidoId}/cancelar`, {
                method: 'POST'
            });
            const data = await response.json();
            
            if (data.success) {
                await Swal.fire({
                    title: '¡Cancelado!',
                    text: 'El pedido ha sido cancelado exitosamente',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false,
                    willClose: () => {
                        location.reload();
                    }
                });
            } else {
                throw new Error(data.message || 'Error al cancelar el pedido');
            }
        } catch (error) {
            await Swal.fire({
                title: 'Error',
                text: error.message,
                icon: 'error'
            });
        }
    }

    // Event listeners para los botones de cancelar (versión corregida)
    document.querySelectorAll('.cancelar-pedido').forEach(btn => {
        btn.addEventListener('click', async function(e) {
            e.preventDefault(); // Prevenir comportamiento por defecto
            e.stopPropagation(); // Detener propagación del evento
            
            const pedidoId = this.getAttribute('data-pedido-id');
            await cancelarPedido(pedidoId);
        });
    });
});

// Manejo de valoraciones (versión corregida)
document.addEventListener('DOMContentLoaded', function() {
    try {
        const valoracionModal = document.getElementById('valoracionModal');
        const valoracionForm = document.getElementById('valoracionForm');
        const stars = document.querySelectorAll('.rating-stars .fa-star');
        
        // Verificar que los elementos existen
        if (!valoracionModal || !valoracionForm || stars.length === 0) {
            return;
        }

        // Mostrar modal de valoración
        document.querySelectorAll('.valorar-pedido').forEach(btn => {
            btn.addEventListener('click', function() {
                try {
                    const pedidoId = this.getAttribute('data-pedido-id');
                    const profesional = this.getAttribute('data-profesional');
                    const precio = this.getAttribute('data-precio');
                    
                    if (!pedidoId || !profesional) {
                        throw new Error('Datos incompletos para la valoración');
                    }

                    const pedidoIdInput = document.getElementById('valoracionPedidoId');
                    const profesionalInput = document.getElementById('valoracionProfesional');
                    const precioInput = document.getElementById('valoracionPrecio');
                    
                    if (pedidoIdInput) pedidoIdInput.value = pedidoId;
                    if (profesionalInput) profesionalInput.value = profesional;
                    if (precioInput) precioInput.value = precio || '';
                    
                    // Resetear estrellas y formulario
                    stars.forEach(star => star.classList.remove('active'));
                    const valoracionInput = document.getElementById('valoracionInput');
                    if (valoracionInput) valoracionInput.value = '';
                    valoracionForm.reset();
                    
                    valoracionModal.style.display = 'block';
                    document.body.style.overflow = 'hidden';
                } catch (error) {
                    console.error('Error al mostrar modal de valoración:', error);
                    showNotification('error', 'Error', 'No se pudo abrir el formulario de valoración');
                }
            });
        });
        
        // Manejar clic en estrellas
        stars.forEach(star => {
            star.addEventListener('click', function() {
                try {
                    const rating = parseInt(this.getAttribute('data-rating'));
                    const valoracionInput = document.getElementById('valoracionInput');
                    
                    if (!valoracionInput) {
                        throw new Error('Campo de valoración no encontrado');
                    }
                    
                    valoracionInput.value = rating;
                    
                    // Actualizar visualización de estrellas
                    stars.forEach((s, index) => {
                        if (index < rating) {
                            s.classList.add('active');
                        } else {
                            s.classList.remove('active');
                        }
                    });
                } catch (error) {
                    console.error('Error al seleccionar estrella:', error);
                }
            });
        });
        
        // Enviar valoración
        valoracionForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            try {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (!submitBtn) {
                    throw new Error('Botón de enviar no encontrado');
                }
                
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';
                
                const formData = new FormData(this);
                const pedidoId = formData.get('pedido_id');
                
                if (!pedidoId) {
                    throw new Error('ID de pedido no especificado');
                }
                
                const response = await fetch('/api/pedidos/valorar', {
                    method: 'POST',
                    body: formData
                });
                
                if (!response.ok) {
                    const errorData = await response.json().catch(() => null);
                    throw new Error(errorData?.error || 'Error en la respuesta del servidor');
                }
                
                const data = await response.json();
                
                if (data.success) {
                    showNotification('success', 'Gracias', 'Tu valoración ha sido enviada');
                    valoracionModal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                    
                    // Ocultar botón de valorar
                    const btn = document.querySelector(`.valorar-pedido[data-pedido-id="${pedidoId}"]`);
                    if (btn) {
                        btn.style.display = 'none';
                    }
                    
                    // Recargar después de 2 segundos para ver cambios
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    throw new Error(data.error || 'Error al enviar valoración');
                }
            } catch (error) {
                console.error('Error al enviar valoración:', error);
                showNotification('error', 'Error', error.message || 'Error al enviar valoración');
            } finally {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Enviar Valoración';
                }
            }
        });
        
        // Cerrar modal al hacer clic fuera o en la X
        const closeModal = () => {
            valoracionModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        };
        
        valoracionModal.addEventListener('click', (e) => {
            if (e.target === valoracionModal || e.target.classList.contains('close-modal')) {
                closeModal();
            }
        });
        
    } catch (error) {
        console.error('Error en el sistema de valoraciones:', error);
    }
});

// Manejo de la página de solicitudes
document.addEventListener('DOMContentLoaded', function() {
    const solicitudModal = document.getElementById('solicitudModal');
    const ofertaModal = document.getElementById('ofertaModal');
    const ofertaForm = document.getElementById('ofertaForm');
    
    // Event listener para enviar oferta (versión corregida)
    if (ofertaForm) {
        ofertaForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            console.log('Enviando oferta...'); // Debug
            
            const pedidoId = this.pedidoId.value;
            const monto = parseFloat(this.ofertaMonto.value);
            const fecha = this.ofertaFecha.value;
            const descripcion = this.ofertaDescripcion.value;
            
            // Validaciones
            if (!monto || monto <= 0 || isNaN(monto)) {
                showNotification('error', 'Error', 'Ingresa un monto válido');
                return;
            }
            
            if (!fecha) {
                showNotification('error', 'Error', 'Selecciona una fecha válida');
                return;
            }
            
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';
            
            try {
                console.log('Preparando datos para enviar:', { pedidoId, monto, fecha, descripcion }); // Debug
                
                const response = await fetch(`/api/pedidos/${pedidoId}/enviar-oferta`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        oferta: monto,
                        fecha_propuesta: fecha,
                        descripcion: descripcion
                    })
                });
                
                console.log('Respuesta recibida, status:', response.status); // Debug
                
                if (!response.ok) {
                    const errorData = await response.json().catch(() => null);
                    throw new Error(errorData?.message || 'Error en la respuesta del servidor');
                }
                
                const data = await response.json();
                console.log('Datos recibidos:', data); // Debug
                
                if (data.success) {
                    showNotification('success', 'Éxito', 'Oferta enviada correctamente');
                    ofertaModal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                    
                    // Actualizar el botón en la tarjeta
                    const btn = document.querySelector(`.enviar-oferta[data-pedido-id="${pedidoId}"]`);
                    if (btn) {
                        btn.disabled = true;
                        btn.innerHTML = '<i class="fas fa-paper-plane"></i> Oferta enviada';
                    }
                    
                    // Recargar después de 2 segundos
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    throw new Error(data.message || 'Error al enviar oferta');
                }
            } catch (error) {
                console.error('Error al enviar oferta:', error);
                showNotification('error', 'Error', error.message || 'Error al enviar oferta');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Enviar Oferta';
            }
        });
    } else {
        
    }
    
    // Cerrar modales con la X
    document.querySelectorAll('.modal .close-modal').forEach(btn => {
        btn.addEventListener('click', function() {
            const modal = this.closest('.modal');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        });
    });
    
    // Cerrar modales al hacer clic fuera
    window.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal')) {
            e.target.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });
    
    // Función para cargar detalles de la solicitud
    function cargarDetallesSolicitud(pedidoId) {
        console.log(`Cargando detalles del pedido ${pedidoId}`); // Debug
        
        // Mostrar loader
        const modalContent = document.getElementById('solicitudModalContent');
        modalContent.innerHTML = `
            <div class="loading-spinner">
                <i class="fas fa-spinner fa-spin"></i> Cargando detalles...
            </div>`;
        
        solicitudModal.style.display = 'block';
        document.body.style.overflow = 'hidden';
        
        fetch(`/api/pedidos/${pedidoId}`)
            .then(response => response.json())
            .then(data => {
                console.log('Datos recibidos:', data); // Debug
                
                if (!data.success || !data.pedido) {
                    throw new Error(data.error || 'Datos del pedido no disponibles');
                }
                
                const pedido = data.pedido;
                
                const fechaCreacion = new Date(pedido.created_at).toLocaleDateString();
                const fechaNecesidad = pedido.fecha_necesidad 
                    ? new Date(pedido.fecha_necesidad + 'T00:00:00').toLocaleDateString('es-AR') 
                    : 'No especificada';
                const opciones = pedido.opciones_info || [];
                const fleteInfo = pedido.flete_info || null;
                // Manejo del campo prueba
                const prueba = pedido.prueba && pedido.prueba.trim() !== '' ? 
                `<div class="profesional-info">
                    <p><a href="#" onclick="abrirModalFoto('/public/img/prueba/${pedido.prueba}')">Ver foto</a></p>
                </div>` : 
                'No especificada';

                let html = `
                    <div class="pedido-detalles">
                        <div class="detalle-header">
                            <h2>Detalles de la Solicitud #${pedido.id}</h2>
                            <div class="status-badge ${pedido.estado}">
                                ${pedido.estado.replace('_', ' ')}
                            </div>
                        </div>
                        
                        <div class="detalle-grid">
                            <div class="detalle-card">
                                <h3><i class="fas fa-tools"></i> Servicio solicitado</h3>
                                <p>${pedido.nombre_servicio || 'Servicio no especificado'}</p>
                            </div>`;
                if(fleteInfo){
                    html +=`
                        <div class="detalle-card flete-info">
                            <h3><i class="fas fa-truck"></i> Detalles del Flete</h3>
                            <p><strong>Punto de partida:</strong> ${fleteInfo.inicio}</p>
                            <p><strong>Punto final:</strong> ${fleteInfo.fin}</p>
                            <p><strong>Kilómetros:</strong> ${fleteInfo.kilometros_totales} km</p>
                        </div>`;
                }
                // Mostrar opciones seleccionadas
                else if (opciones.length > 0) {
                    html += `
                            <div class="detalle-card">
                                <h3><i class="fas fa-list-check"></i> Tipo de trabajo</h3>
                                <ul class="opciones-list">`;
                    
                    opciones.forEach(opcion => {
                        html += `<li>${opcion.nombre}}</li>`;
                    });
                    
                    html += `</ul></div>`;
                }
                
                html += `            
                            <div class="detalle-card">
                                <h3><i class="fas fa-file-alt"></i> Descripción del trabajo</h3>
                                <p>${pedido.tipo_trabajo}</p>
                            </div>

                            <div class="detalle-card">
                                <h3><i class="fas fa-map-marker-alt"></i> Localidad</h3>
                                <p>${pedido.direccion}</p>
                            </div>
                            
                            <div class="detalle-card">
                                <h3><i class="fas fa-calendar-plus"></i> Fecha de creación</h3>
                                <p>${fechaCreacion}</p>
                            </div>
                            
                            <div class="detalle-card">
                                <h3><i class="fas fa-calendar-day"></i> Fecha necesitada</h3>
                                <p>${fechaNecesidad}</p>
                            </div>

                            <div class="detalle-card">
                                <h3><i class="fas fa-file-alt"></i> Prueba</h3>
                                ${prueba}
                            </div>
                        </div>
                    </div>`;
                
                // Usar el modal correcto para solicitudes
                const modalContent = document.getElementById('solicitudModalContent') || document.getElementById('pedidoModalContent');
                modalContent.innerHTML = html;
                
                const modal = document.getElementById('solicitudModal') || document.getElementById('pedidoModal');
                modal.style.display = 'block';
                document.body.style.overflow = 'hidden';
            })
            .catch(error => {
                console.error('Error al cargar detalles:', error);
                const modalContent = document.getElementById('solicitudModalContent') || document.getElementById('pedidoModalContent');
                modalContent.innerHTML = `
                    <div class="error-message">
                        <p>Ocurrió un error al cargar los detalles del pedido.</p>
                        <button class="btn btn-primary" onclick="location.reload()">Recargar</button>
                    </div>`;
                
                const modal = document.getElementById('solicitudModal') || document.getElementById('pedidoModal');
                modal.style.display = 'block';
            });
    }
    
    // Event listeners para los botones de ver detalles
    document.querySelectorAll('.ver-detallesSolicitud').forEach(btn => {
        btn.addEventListener('click', function() {
            const pedidoId = this.getAttribute('data-pedido-id');
            cargarDetallesSolicitud(pedidoId);
        });
    });
    
    // Event listeners para los botones de enviar oferta
    document.querySelectorAll('.enviar-oferta').forEach(btn => {
        btn.addEventListener('click', function() {
            if (this.disabled) return;
            
            const pedidoId = this.getAttribute('data-pedido-id');
            document.getElementById('pedidoId').value = pedidoId;
            
            // Resetear formulario
            ofertaForm.reset();
            
            // Mostrar modal
            ofertaModal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        });
    });
});

// Manejo del perfil profesional
document.addEventListener('DOMContentLoaded', function() {
    // Finalizar trabajo
    document.querySelectorAll('.finalizar-trabajo').forEach(btn => {
        btn.addEventListener('click', function() {
            const pedidoId = this.getAttribute('data-pedido-id');
            document.getElementById('pedidoIdFinalizar').value = pedidoId;
            document.getElementById('finalizarModal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        });
    });

    // Enviar formulario de finalización
    document.addEventListener('DOMContentLoaded', function () {
        const finalizarForm = document.getElementById('finalizarForm');
    
        if (finalizarForm) {
            finalizarForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const pedidoId = this.pedidoIdFinalizar.value;
            const comentarios = this.comentarios.value;
            
            fetch(`/api/pedidos/${pedidoId}/finalizar`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ comentarios })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('success', 'Trabajo finalizado', 'El trabajo ha sido marcado como completado');
                    document.getElementById('finalizarModal').style.display = 'none';
                    document.body.style.overflow = 'auto';
                    setTimeout(() => location.reload(), 1500);
                } else {
                    throw new Error(data.message || 'Error al finalizar trabajo');
                }
            })
            .catch(error => {
                showNotification('error', 'Error', error.message);
            });
        });
        }
    });
});

function abrirModalFoto(urlFoto) {
    // Crear el modal si no existe
    let modalFoto = document.getElementById('modalFoto');
    if (!modalFoto) {
        modalFoto = document.createElement('div');
        modalFoto.id = 'modalFoto';
        modalFoto.className = 'modal';
        modalFoto.innerHTML = `
            <div class="modal-content" style="max-width: 60%; max-height: 60%;">
                <span class="close-modal" onclick="cerrarModalFoto()">&times;</span>
                <img src="${urlFoto}" style="max-width: 60%; max-height: 60vh; display: block; margin: 0 auto;">
            </div>
        `;
        document.body.appendChild(modalFoto);
    } else {
        // Actualizar la foto si el modal ya existe
        modalFoto.querySelector('img').src = urlFoto;
    }
    
    // Mostrar el modal
    modalFoto.style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function cerrarModalFoto() {
    const modalFoto = document.getElementById('modalFoto');
    if (modalFoto) {
        modalFoto.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

// Manejar clic fuera de la imagen para cerrar
document.addEventListener('click', function(e) {
    if (e.target.id === 'modalFoto') {
        cerrarModalFoto();
    }
});


//ADMIN
// Admin Panel Tabs
document.querySelectorAll('.admin-nav a[data-tab]').forEach(tab => {
    tab.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Activar tab
        document.querySelectorAll('.admin-nav li').forEach(li => {
            li.classList.remove('active');
        });
        this.parentElement.classList.add('active');
        
        // Mostrar sección correspondiente
        const tabId = this.getAttribute('data-tab');
        document.querySelectorAll('.admin-section').forEach(section => {
            section.classList.remove('active');
        });
        document.getElementById(`${tabId}-section`).classList.add('active');
    });
});

// Función para mostrar datos de usuario en el modal
async function showUserDetails(username, tipo) {
    try {
        // Mostrar loader
        $('#usuarioModalBody').html(`
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-2">Cargando datos del usuario...</p>
            </div>
        `);
        $('#usuarioModal').modal('show');
        
        // Hacer la petición
        const response = await fetch(`/admin/get-user-data?username=${encodeURIComponent(username)}&tipo=${encodeURIComponent(tipo)}`);
        const data = await response.json();
        
        if (!data.success) {
            throw new Error(data.error || 'Error al cargar datos');
        }
        
        // Construir el HTML con los datos
        let html = `
            <div class="usuario-details">
                <img src="${data.data.foto_perfil}" class="usuario-avatar" onerror="this.src='/public/img/profiles/default.jpg'">
                <div class="usuario-info">
                    <h4>${data.data.nombre}</h4>
                    <p><strong>Usuario:</strong> ${data.data.username}</p>
                    <p><strong>Email:</strong> ${data.data.email}</p>
                    <p><strong>Teléfono:</strong> ${data.data.telefono}</p>
                    <p><strong>Dirección:</strong> ${data.data.direccion}</p>
                    <p><strong>Localidad:</strong> ${data.data.localidad}</p>
                    <p><strong>Registro:</strong> ${data.data.registro}</p>
        `;
        
        // Agregar sección profesional si corresponde
        if (tipo === 'profesional' && data.profesional) {
            html += `
                <div class="usuario-documento mt-3">
                    <h5>Información Profesional</h5>
                    <p><strong>Estado:</strong> ${data.profesional.estado}</p>
                    ${data.profesional.matricula ? `<p><strong>N° Matrícula:</strong> ${data.profesional.matricula}</p>` : ''}
            `;
            
            if (data.profesional.documento) {
                html += `
                    <p><strong>Documento:</strong> 
                        <a href="${data.profesional.documento.url}" target="_blank">
                            Ver documento (.${data.profesional.documento.ext})
                        </a>
                    </p>
                `;
            } else {
                html += `<p><strong>Matricula/documentacion:</strong> No tiene.</p>`;
            }
            
            html += `
                    <p><strong>Servicios:</strong> 
                        ${data.profesional.servicios.length ? data.profesional.servicios.join(', ') : 'Ninguno'}
                    </p>
                </div>
            `;
        }
        
        html += `</div></div>`;
        
        // Insertar el HTML en el modal
        document.getElementById('usuarioModalBody').innerHTML = html;

        
    } catch (error) {
        console.error('Error:', error);
        $('#usuarioModal').modal('hide');
        
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message,
            confirmButtonText: 'Entendido'
        });
    }
}

// Asignar eventos a los botones
document.querySelectorAll('.ver-datos').forEach(btn => {
    btn.addEventListener('click', function() {
        const username = this.getAttribute('data-username');
        const tipo = this.getAttribute('data-tipo');
        showUserDetails(username, tipo);
    });
});

// Dar de alta a profesional
document.querySelectorAll('.dar-alta').forEach(btn => {
    btn.addEventListener('click', function() {
        const username = this.getAttribute('data-username');
        
        Swal.fire({
            title: '¿Dar de alta a este profesional?',
            text: 'El profesional recibirá un email confirmando su alta en el sistema.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, dar de alta',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('/admin/actualizar-profesional', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ username, alta: 1 })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Profesional dado de alta correctamente.', data.message, 'success').then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Error', data.message, 'error');
                            }
                        })
                        .catch(() => {
                            Swal.fire('Error', 'Error al eliminar el servicio', 'error');
                        });
                }
        });
    });
});

// Eliminar usuario
document.querySelectorAll('.eliminar-usuario').forEach(btn => {
    btn.addEventListener('click', function() {
        const username = this.getAttribute('data-username');
        
        Swal.fire({
            title: '¿Eliminar este usuario?',
            text: 'Esta acción no se puede deshacer. Todos los datos asociados se perderán.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#d33'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('/admin/eliminar-usuario', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ username })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Eliminado', data.message, 'success').then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Error', data.message, 'error');
                            }
                        })
                        .catch(() => {
                            Swal.fire('Error', 'Error al eliminar el servicio', 'error');
                        });
                }
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    // Mostrar modal para agregar servicio
    const agregarServicioBtn = document.getElementById('agregar-servicio');
    if (agregarServicioBtn) {
        agregarServicioBtn.addEventListener('click', function () {
            document.getElementById('servicioModalTitle').textContent = 'Agregar Nuevo Servicio';
            document.getElementById('servicioId').value = '';
            document.getElementById('servicioForm').reset();
            new bootstrap.Modal(document.getElementById('servicioModal')).show();
        });
    }

    // Editar servicio
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('editar-servicio')) {
            const servicioId = e.target.dataset.id;

            fetch('/admin/servicios/obtener/' + servicioId)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('servicioModalTitle').textContent = 'Editar Servicio';
                        document.getElementById('servicioId').value = data.data.id;
                        document.getElementById('categoria').value = data.data.categoria;
                        document.getElementById('descripcion').value = data.data.descripcion;
                        new bootstrap.Modal(document.getElementById('servicioModal')).show();
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .catch(() => {
                    Swal.fire('Error', 'No se pudo obtener el servicio', 'error');
                });
        }
    });

    // Guardar servicio
    const servicioForm = document.getElementById('servicioForm');
    if (servicioForm) {
        servicioForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(servicioForm);
            const url = document.getElementById('servicioId').value
                ? '/admin/servicios/editar'
                : '/admin/servicios/agregar';

            fetch(url, {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Éxito',
                            text: data.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            bootstrap.Modal.getInstance(document.getElementById('servicioModal')).hide();
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .catch(() => {
                    Swal.fire('Error', 'Error en la solicitud', 'error');
                });
        });
    }

    // Eliminar servicio
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('eliminar-servicio')) {
            const servicioId = e.target.dataset.id;

            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡Esto eliminará el servicio y todas sus asignaciones!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('/admin/servicios/eliminar/' + servicioId, {
                        method: 'DELETE'
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Eliminado', data.message, 'success').then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Error', data.message, 'error');
                            }
                        })
                        .catch(() => {
                            Swal.fire('Error', 'Error al eliminar el servicio', 'error');
                        });
                }
            });
        }
    });
});



// Manejo del modal de edición de perfil cliente
document.addEventListener('DOMContentLoaded', function() {
    // Elementos del DOM
    const editarBtn = document.querySelector('a[href="/editar-perfil-c"]');
    const modal = document.getElementById('editarModal');
    const modalContent = document.getElementById('editarModalContent');
    const closeModal = document.querySelector('.close-modal');
    
    if (editarBtn) {
        editarBtn.addEventListener('click', function(e) {
            e.preventDefault();
            cargarFormularioEdicion();
            if (modal) {
                modal.style.display = 'block';
                document.body.style.overflow = 'hidden';
            }
        });
    }

    if (closeModal) {
        closeModal.addEventListener('click', function() {
            if (modal) {
                modal.style.display = 'none';
                document.body.style.overflow = '';
            }
        });
    }
    
    // Cerrar modal al hacer clic fuera del contenido
    window.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }
    });
    
    // Función para cargar el formulario de edición
    function cargarFormularioEdicion() {
        // Obtener datos del usuario desde el DOM
        const nombreCompleto = document.querySelector('.profile-info h1').textContent.trim().split(' ');
        const nombre = nombreCompleto[0];
        const apellido = nombreCompleto.slice(1).join(' ');
        const telefono = document.querySelector('.profile-phone').textContent.replace('📱 ', '');
        const direccion = document.querySelector('.profile-details p').textContent.replace('Dirección: ', '');
        const localidad = document.querySelector('.localidad').textContent.replace('Localidad: ', '');
        
        // Crear formulario HTML
        modalContent.innerHTML = `
            <h2>Editar Perfil</h2>
            <form id="editarPerfilForm" class="profile-form">
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" value="${nombre}" required>
                </div>
                
                <div class="form-group">
                    <label for="apellido">Apellido:</label>
                    <input type="text" id="apellido" name="apellido" value="${apellido}" required>
                </div>
                
                <div class="form-group">
                    <label for="telefono">Teléfono:</label>
                    <input type="tel" id="telefono" name="telefono" value="${telefono}" required maxlength="11">
                </div>
                
                <div class="form-group">
                    <label for="direccion">Dirección:</label>
                    <input type="text" id="direccion" name="direccion" value="${direccion}" required>
                </div>
                
                <div class="form-group">
                    <label for="localidad">Localidad:</label>
                    <input type="text" id="localidad" name="localidad" value="${localidad}" required>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" id="cancelarEdicion">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        `;
        
        // Manejar cancelar
        document.getElementById('cancelarEdicion').addEventListener('click', function() {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        });
        
        // Manejar envío del formulario
        document.getElementById('editarPerfilForm').addEventListener('submit', function(e) {
            e.preventDefault();
            actualizarPerfil();
        });
    }
    
    // Función para actualizar el perfil
    function actualizarPerfil() {
        const formData = {
            nombre: document.getElementById('nombre').value.trim(),
            apellido: document.getElementById('apellido').value.trim(),
            telefono: document.getElementById('telefono').value.trim(),
            direccion: document.getElementById('direccion').value.trim(),
            localidad: document.getElementById('localidad').value.trim()
        };
        
        fetch('/api/actualizar-perfil', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Actualizar la vista sin recargar
                actualizarVistaPerfil(formData);
                modal.style.display = 'none';
                showNotification('success', 'Cambios guardados', 'Perfil actualizado correctamente');
            } else {
                showNotification('error', 'Error', data.message || 'Error al actualizar el perfil');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('error', 'Error al conectar con el servidor');
        });
    }
    
    // Función para actualizar la vista del perfil
    function actualizarVistaPerfil(datos) {
        // Actualizar nombre en el encabezado
        document.querySelector('.profile-info h1').textContent = `${datos.nombre} ${datos.apellido}`;
        
        // Actualizar teléfono
        document.querySelector('.profile-phone').innerHTML = `<i class="fas fa-phone"></i> ${datos.telefono}`;
        
        // Actualizar dirección
        document.querySelector('.profile-details p').innerHTML = `<strong>Dirección:</strong> ${datos.direccion}`;
        
        //Actualizar localidad
        document.querySelector('.localidad').innerHTML = `<strong>Localidad:</strong> ${datos.localidad}`;
        
        // Actualizar datos en la sesión (si es necesario)
        if (typeof actualizarDatosSesion === 'function') {
            actualizarDatosSesion(datos);
        }
    }
});

//Manejo del modal de edición de perfil profesional
document.addEventListener('DOMContentLoaded', function() {
    // Elementos del DOM
    const editarBtn = document.querySelector('a[href="/editar-perfil-p"]');
    const modal = document.getElementById('editarProModal');
    const modalContent = document.getElementById('editarProModalContent');
    const closeModal = document.querySelectorAll('.close-modal')[2]; // El tercer modal
    
    // Manejar clic en el botón de editar
    if (editarBtn) {
        editarBtn.addEventListener('click', function(e) {
            e.preventDefault();
            cargarFormularioEdicion();
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        });
    }
    
    // Cerrar modal al hacer clic en la X
    if(closeModal){
        closeModal.addEventListener('click', function() {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        });
    }
    
    // Cerrar modal al hacer clic fuera del contenido
    window.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }
    });
    
    // Función para cargar el formulario de edición
    async function cargarFormularioEdicion() {
        try {
            // Obtener datos del profesional
            const response = await fetch(`/api/profesional/datos-edicion`);
            const data = await response.json();
            
            if (!response.ok) throw new Error(data.message || 'Error al cargar datos');
            
            // Crear formulario HTML
            modalContent.innerHTML = `
                <h2>Editar Perfil Profesional</h2>
                <form id="editarPerfilProForm" class="auth-form">
                    <div class="auth-form-row">
                        <div class="auth-form-group">
                            <label for="nombre">Nombre*</label>
                            <input type="text" id="nombre" name="nombre" value="${data.profesional.nombre}" required>
                        </div>
                        
                        <div class="auth-form-group">
                            <label for="apellido">Apellido*</label>
                            <input type="text" id="apellido" name="apellido" value="${data.profesional.apellido}" required>
                        </div>
                    </div>

                    <div class="auth-form-row">
                        <div class="auth-form-group">
                            <label for="telefono">Teléfono*</label>
                            <input type="tel" id="telefono" name="telefono" value="${data.profesional.telefono}" required>
                        </div>
                        
                    </div>
                    
                    <div class="auth-form-row">
                        <div class="auth-form-group">
                            <label for="direccion">Dirección*</label>
                            <input type="text" id="direccion" name="direccion" value="${data.profesional.direccion}" required>
                        </div>
                        
                        <div class="auth-form-group">
                            <label for="localidad">Localidad:</label>
                            <input type="text" id="localidad" name="localidad" value="${data.profesional.localidad}" required>
                        </div>
                    </div>
                    
                    ${data.profesional.nro_matricula ? '' : `
                        <div class="auth-form-row">
                            <div class="auth-form-group">
                                <label for="nro_matricula">N° de Matrícula/Certificación</label>
                                <input type="text" id="nro_matricula" name="nro_matricula">
                                <p class="auth-form-hint">Ejemplo: MAT-12345678</p>
                            </div>

                            <div class="auth-form-group">
                                <label for="documento">Documento que acredita tu profesión</label>
                                <div class="auth-file-upload">
                                    <input type="file" id="documento" name="documento" accept=".pdf,.jpg,.jpeg,.png">
                                    <label for="documento" class="auth-file-label">
                                        <i class="fas fa-file-upload"></i> Subir documento (PDF o imagen)
                                    </label>
                                </div>
                                <p class="auth-form-hint">Puede ser tu matrícula, certificado o documento que acredite tu profesión</p>
                            </div>
                        </div>
                    `}

                    <div class="auth-form-group">
                        <label>Servicios que ofreces</label>
                        <div class="services-checkbox-container">
                            ${data.servicios.map(servicio => `
                                <div class="service-checkbox">
                                    <input type="checkbox" 
                                           id="servicio_${servicio.id}" 
                                           name="servicios[]" 
                                           value="${servicio.id}"
                                           ${servicio.seleccionado ? 'checked' : ''}>
                                    <label for="servicio_${servicio.id}">${servicio.categoria}</label>
                                </div>
                            `).join('')}
                        </div>
                    </div>

                    <div class="auth-form-row">
                        <button type="submit" class="auth-submit-btn">
                            <i class="fas fa-save"></i> Guardar Cambios
                        </button>
                        <button type="button" class="auth-submit-btn" id="cancelarEdicionPro" style="background-color: #ccc;">
                            Cancelar
                        </button>
                    </div>
                </form>
            `;
            
            // Mostrar nombre de archivo seleccionado
            document.getElementById('documento')?.addEventListener('change', function(e) {
                const fileName = e.target.files[0]?.name || 'Ningún archivo seleccionado';
                document.getElementById('fileName').textContent = fileName;
            });
            
            // Manejar cancelar
            document.getElementById('cancelarEdicionPro').addEventListener('click', function() {
                modal.style.display = 'none';
                document.body.style.overflow = '';
            });
            
            // Manejar envío del formulario
            document.getElementById('editarPerfilProForm').addEventListener('submit', function(e) {
                e.preventDefault();
                actualizarPerfilProfesional();
            });
            
        } catch (error) {
            console.error('Error:', error);
            modalContent.innerHTML = `
                <div class="error-message">
                    <h2>Error</h2>
                    <p>${error.message}</p>
                    <button class="btn btn-secondary" onclick="location.reload()">Recargar</button>
                </div>
            `;
        }
    }
    
    // Función para actualizar el perfil profesional
    async function actualizarPerfilProfesional() {
        const form = document.getElementById('editarPerfilProForm');
        const formData = new FormData(form);
        
        // Agregar servicios seleccionados
        const servicios = Array.from(document.querySelectorAll('input[name="servicios[]"]:checked'))
                              .map(el => el.value);
        formData.append('servicios', JSON.stringify(servicios));
        
        try {
            const response = await fetch('/api/actualizar-perfil-pro', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (!response.ok) throw new Error(data.message || 'Error al actualizar perfil');
            
            // Actualizar la vista
            showNotification('success', 'Cambios guardados', 'Perfil actualizado correctamente');
            setTimeout(() => location.reload(), 1500); // Recargar para ver cambios
            
            
        } catch (error) {
            console.error('Error:', error);
            showNotification('error', 'Error', error.message || 'Error al actualizar el perfil');
        }
    }
});

document.addEventListener('DOMContentLoaded', function() {
    // Asegúrate de que SweetAlert esté cargado
    if (typeof Swal === 'undefined') {
        console.error('SweetAlert no está cargado');
        return;
    }

    const avatarInput = document.getElementById('avatar-input');
    const avatarImg = document.querySelector('.avatar-img');
    
    if (avatarInput) {
        avatarInput.addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                // Mostrar confirmación con SweetAlert
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¿Quieres cambiar tu foto de perfil?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#4CAF50',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, cambiar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        cambiarFotoPerfil(this.files[0]);
                    } else {
                        // Resetear el input de archivo
                        this.value = '';
                    }
                });
            }
        });
    }
    
    function cambiarFotoPerfil(file) {
        const formData = new FormData();
        formData.append('avatar', file);
        
        // Mostrar loader
        Swal.fire({
            title: 'Actualizando foto',
            html: 'Por favor espera...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        fetch('/api/actualizar-foto-perfil', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            Swal.close();
            
            if (data.success) {
                // Actualizar la imagen en la vista
                avatarImg.src = `/public/img/profiles/${data.newPhoto}?${Date.now()}`;
                
                Swal.fire(
                    '¡Éxito!',
                    'Tu foto de perfil ha sido actualizada',
                    'success',
                    setTimeout(() => location.reload(), 1000)
                );
            } else {
                Swal.fire(
                    'Error',
                    data.message || 'Hubo un problema al actualizar tu foto',
                    'error'
                );
            }
        })
        .catch(error => {
            Swal.close();
            Swal.fire(
                'Error',
                'No se pudo conectar con el servidor',
                'error'
            );
            console.error('Error:', error);
        });
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const introAnimation = document.getElementById('introAnimation');
    const mainContent = document.querySelectorAll('.main-header, .main-footer, .hero-section, .features-section, .services-preview, .testimonials-section, .cta-section');

    if (!sessionStorage.getItem('animationShown')) {
        // Mostrar animación y hacer visible el contenedor
        introAnimation.style.display = 'block';
        mainContent.forEach(section => {
            section.style.display = 'none';
        });

        introAnimation.style.opacity = '1';

        // Secuencia animación completa
        setTimeout(() => {
            introAnimation.style.opacity = '0';
            mainContent.forEach(section => section.classList.add('show-content'));

            setTimeout(() => {
                introAnimation.style.display = 'none';
            }, 1500);

            sessionStorage.setItem('animationShown', 'true');
        }, 3000);
    } else {
        // Mostrar contenido principal inmediatamente
        mainContent.forEach(section => section.classList.add('show-content'));
    }
});

/*ANIMACION QUE FUNCIONA FLAMA CON CACHE

document.addEventListener('DOMContentLoaded', function() {
    // Verificar si ya se mostró la animación
    if (!sessionStorage.getItem('animationShown')) {
        // Mostrar la animación
        const introAnimation = document.getElementById('introAnimation');
        const xLogo = document.querySelector('.x-logo');
        const vexoLogo = document.querySelector('.vexo-logo');
        const mainContent = document.querySelectorAll('.hero-section, .features-section, .services-preview, .testimonials-section, .cta-section');
        
        // Iniciar la secuencia de animación
        setTimeout(() => {
            // Después de que termine la animación (2.5 segundos)
            setTimeout(() => {
                // Ocultar animación
                introAnimation.style.opacity = '0';
                
                // Mostrar contenido principal
                mainContent.forEach(section => {
                    section.classList.add('show-content');
                });
                
                // Eliminar el elemento de animación después de la transición
                setTimeout(() => {
                    introAnimation.style.display = 'none';
                }, 1500);
                
                // Marcar que la animación ya se mostró
                sessionStorage.setItem('animationShown', 'true');
            }, 3000);
        }, 100);
    } else {
        // Si ya se mostró la animación, ocultarla inmediatamente
        const introAnimation = document.getElementById('introAnimation');
        if (introAnimation) {
            introAnimation.style.display = 'none';
        }
        
        // Mostrar contenido principal inmediatamente
        const mainContent = document.querySelectorAll('.hero-section, .features-section, .services-preview, .testimonials-section, .cta-section');
        mainContent.forEach(section => {
            section.classList.add('show-content');
        });
    }
});

*/

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('contactForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    const btnSpinner = document.getElementById('btnSpinner');
    
    if(form) {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            e.stopImmediatePropagation();
    
            // Desactiva el botón y muestra spinner
            submitBtn.disabled = true;
            btnText.textContent = 'Enviando...';
            btnSpinner.style.display = 'inline-block';
    
            // Prepara los datos del formulario
            const formData = new FormData(form);
    
            try {
                const response = await fetch('/enviar-mensaje', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest' // Identifica como AJAX
                    }
                });
    
                const result = await response.json(); // Espera una respuesta tipo { success: true, message: "..." }
    
                if (result.success) {
                    showNotification('success', 'Email enviado.', '¡Mensaje enviado con éxito!');
                    form.reset();
                } else {
                    showNotification('error', 'Error', result.message ||  'Error al enviar el mensaje');
                }
            } catch (error) {
                console.error(error);
                showNotification('error', 'Error', 'Ocurrió un error inesperado. Intenta más tarde.');
            } finally {
                // Reactivar botón y resetear texto
                submitBtn.disabled = false;
                btnText.textContent = 'Enviar mensaje';
                btnSpinner.style.display = 'none';
            }
        });
    }
    }, {once: true});


document.addEventListener('DOMContentLoaded', () => {
    const forgotPasswordLink = document.querySelector('[data-forgot-password]');
    if (forgotPasswordLink) {
        forgotPasswordLink.addEventListener('click', (e) => {
            e.preventDefault();
            const modal = document.getElementById('forgotPasswordModal');
            if (modal) {
                modal.style.display = 'block';
                document.body.style.overflow = 'hidden';
            }
        });
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const closeModalBtn = document.querySelector('.auth-close-modal');
    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', () => {
            const modal = document.getElementById('forgotPasswordModal');
            if (modal) {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        });
    }
});


// Cerrar al hacer clic fuera del modal
window.addEventListener('click', (e) => {
    if (e.target === document.getElementById('forgotPasswordModal')) {
        document.getElementById('forgotPasswordModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }
});

// Envío del formulario
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('forgotPasswordForm');
    if (form) {
        form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const email = document.getElementById('recoveryEmail').value;
        const submitBtn = e.target.querySelector('button[type="submit"]');
    
        // Validación básica de email
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            Swal.fire({
                icon: 'warning',
                title: 'Email inválido',
                text: 'Por favor ingresa un email válido',
            });
            return;
        }
    
        // Deshabilitar botón durante el envío
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';
    
        try {
            const response = await fetch('/auth/forgot-password', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ email })
            });
    
            const data = await response.json();
    
            if (response.ok) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Enlace enviado!',
                    text: data.message,
                    timer: 3000,
                    showConfirmButton: false
                });
    
                setTimeout(() => {
                    document.getElementById('forgotPasswordForm').reset();
                    document.getElementById('forgotPasswordModal').style.display = 'none';
                    document.body.style.overflow = 'auto';
                }, 3000);
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
                title: 'Error de conexión',
                text: 'No se pudo conectar con el servidor.'
            });
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Enviar enlace';
        }
    })
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const finalizarBtns = document.querySelectorAll('.finalizar-pedido');

    async function iniciarPago(pedidoId, btn) {
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
        btn.disabled = true;

        try {
            const response = await fetch(`/api/pedidos/${pedidoId}/crear-pago`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            const data = await response.json();

            if (response.ok && data.success) {
                sessionStorage.setItem('pagoIniciado', 'true');
                sessionStorage.setItem('pedidoId', pedidoId);
                window.location.href = data.init_point;
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.error || 'Error al procesar el pago'
                });
                btn.innerHTML = '<i class="fas fa-check-circle"></i> Finalizar y Pagar';
                btn.disabled = false;
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error de conexión',
                text: 'No se pudo conectar con el servidor'
            });
            btn.innerHTML = '<i class="fas fa-check-circle"></i> Finalizar y Pagar';
            btn.disabled = false;
        }
    }

    finalizarBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const pedidoId = btn.getAttribute('data-pedido-id');
            iniciarPago(pedidoId, btn);
        });
    });

    const pagoIniciado = sessionStorage.getItem('pagoIniciado');
    const estaEnPaginaSuccess = window.location.pathname === '/pago/success';
    if (pagoIniciado === 'true' && !estaEnPaginaSuccess) {
        Swal.fire({
            icon: 'warning',
            title: 'Pago no completado',
            text: 'No finalizaste el pago. ¿Querés intentarlo de nuevo?',
            showCancelButton: true,
            cancelButtonText:'No en este momento.',
            confirmButtonText: 'Sí, volver a pagar'
        }).then((result) => {
            if (result.isConfirmed) {
                const pedidoId = sessionStorage.getItem('pedidoId');
                if (pedidoId) {
                    // Crear un botón ficticio solo para pasar a la función
                    const tempBtn = document.createElement('button');
                    iniciarPago(pedidoId, tempBtn);
                } else {
                    location.reload();
                }
            } else {
                sessionStorage.removeItem('pagoIniciado');
                sessionStorage.removeItem('pedidoId');
            }
        });
    }
    // Limpia sessionStorage si estás en la página de éxito
    if (estaEnPaginaSuccess) {
        sessionStorage.removeItem('pagoIniciado');
        sessionStorage.removeItem('pedidoId');
    }
});

document.addEventListener('DOMContentLoaded', function() {
    // Asegurarse que estamos en una página que tiene el contenedor de chat
    if (!document.getElementById('chatMessages')) return;
    const chatMessages = document.getElementById('chatMessages');
    const mensajeTexto = document.getElementById('mensajeTexto');
    const enviarBtn = document.getElementById('enviarMensaje');
    
    // Obtener pedido_id de la URL
    const urlParams = new URLSearchParams(window.location.search);
    const pedido_id = urlParams.get('pedido_id');
    
    if (!pedido_id) {
        window.location.href = '/pedidos';
        return;
    }
    
    let pollingInterval;
    let lastMessageId = 0;
    
    // Función para cargar mensajes
    function cargarMensajes() {
        fetch(`/api/chat/${pedido_id}/mensajes?last_id=${lastMessageId}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error(data.error);
                    return;
                }
                
                if (data.mensajes && data.mensajes.length > 0) {
                    data.mensajes.forEach(mensaje => {
                        agregarMensaje(mensaje);
                        if (mensaje.id > lastMessageId) {
                            lastMessageId = mensaje.id;
                        }
                    });
                    
                    // Auto-scroll al final
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }
            })
            .catch(error => console.error('Error:', error));
    }
    
    // Función para agregar un mensaje al chat
    function agregarMensaje(mensaje) {
        const esMio = mensaje.remitente_username === currentUsername;
        const messageClass = esMio ? 'message-sent' : 'message-received';
        const unreadClass = !mensaje.leido && !esMio ? 'unread' : '';

        // 🚫 Verificar si el mensaje ya existe en el DOM
        if (document.getElementById(`mensaje-${mensaje.id}`)) return;
        
        const messageElement = document.createElement('div');
        messageElement.id = `mensaje-${mensaje.id}`; // <--- Asignar ID único
        messageElement.className = `message ${messageClass} ${unreadClass}`;
        messageElement.innerHTML = `
            <div class="message-header">
                <img src="/public/img/profiles/${mensaje.foto_perfil || 'default.jpg'}" alt="${mensaje.nombre}" class="message-avatar">
                <span class="message-sender">${mensaje.nombre}</span>
                <span class="message-time">${new Date(mensaje.fecha_envio).toLocaleString()}</span>
            </div>
            <div class="message-content">${mensaje.mensaje}</div>
        `;
        
        chatMessages.appendChild(messageElement);
    }
    
    function contieneNumeros(texto) {
        const regexNumeros = /\d+/; // Detecta cualquier número (una o más cifras)
        const regexEmail = /[@]/; // Caracteres comunes en mails
        const regexSQL = /['<>%;&(){}=\\]/; // Caracteres peligrosos o sospechosos
    
        return regexNumeros.test(texto) || regexEmail.test(texto) || regexSQL.test(texto);
    }
    
    // Función para enviar mensaje
    function enviarMensaje() {
        const texto = mensajeTexto.value.trim();
        if (!texto) return;
        
        if (contieneNumeros(texto)){
            showNotification('error', 'Error',  'No podés enviar numeros ni símbolos especiales en el chat .');
            return;
        }
        
        enviarBtn.disabled = true;
        enviarBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';
        
        fetch(`/api/chat/${pedido_id}/mensajes`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ mensaje: texto })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mensajeTexto.value = '';
                cargarMensajes(); // Recargar mensajes para mostrar el nuevo
            } else {
                alert(data.error || 'Error al enviar mensaje');
            }
        })
        .catch(error => console.error('Error:', error))
        .finally(() => {
            enviarBtn.disabled = false;
            enviarBtn.innerHTML = 'Enviar';
        });
    }
    
    // Event listeners
    enviarBtn.addEventListener('click', enviarMensaje);
    
    mensajeTexto.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            enviarMensaje();
        }
    });
    
    // Cargar mensajes iniciales y configurar polling
    cargarMensajes();
    pollingInterval = setInterval(cargarMensajes, 3000); // Actualizar cada 3 segundos
    
    // Limpiar intervalo al salir de la página
    window.addEventListener('beforeunload', function() {
        clearInterval(pollingInterval);
    });
});