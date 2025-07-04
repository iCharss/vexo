document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validación básica
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const phone = document.getElementById('phone').value.trim();
            const message = document.getElementById('message').value.trim();
            
            if (!name || !email || !phone || !message) {
                alert('Por favor completa todos los campos obligatorios.');
                return;
            }
            
            // Enviar formulario
            const formData = new FormData(contactForm);
            
            fetch('/enviar-mensaje', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.redirected) {
                    window.location.href = response.url;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Hubo un error al enviar el mensaje. Por favor intenta nuevamente.');
            });
        });
        
        // Mostrar mensaje de éxito si viene en la URL
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('success')) {
            alert('¡Gracias por tu mensaje! Nos pondremos en contacto contigo a la brevedad.');
            history.replaceState(null, '', '/contacto');
        }
    }
});