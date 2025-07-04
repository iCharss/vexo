document.addEventListener('DOMContentLoaded', function() {
    // Animaciones para el hero section
    const heroTitle = document.querySelector('.hero-title');
    const heroSubtitle = document.querySelector('.hero-subtitle');
    const heroButtons = document.querySelector('.hero-buttons');
    const heroImage = document.querySelector('.hero-image');
    const heroFeatures = document.querySelectorAll('.feature-item');
    
    if (heroTitle) {
        // Animación escalonada para los elementos del hero
        setTimeout(() => {
            heroTitle.style.opacity = '1';
            heroTitle.style.transform = 'translateY(0)';
            
            setTimeout(() => {
                heroSubtitle.style.opacity = '1';
                heroSubtitle.style.transform = 'translateY(0)';
                
                setTimeout(() => {
                    heroButtons.style.opacity = '1';
                    heroButtons.style.transform = 'translateY(0)';
                    
                    setTimeout(() => {
                        heroImage.style.opacity = '1';
                        heroImage.style.transform = 'translateX(0) rotateY(0)';
                        
                        // Animación de los features items
                        heroFeatures.forEach((item, index) => {
                            setTimeout(() => {
                                item.style.opacity = '1';
                                item.style.transform = 'translateY(0) scale(1)';
                            }, index * 150);
                        });
                        
                        // Animación del badge de experiencia
                        const experienceBadge = document.querySelector('.experience-badge');
                        if (experienceBadge) {
                            setTimeout(() => {
                                experienceBadge.style.opacity = '1';
                                experienceBadge.style.transform = 'rotate(0)';
                            }, 1200);
                        }
                    }, 300);
                }, 300);
            }, 300);
        }, 100);
    }
    
    
    // Animación de elementos al hacer scroll (Intersection Observer mejorado)
    const animateOnScroll = (elements, animationClass) => {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add(animationClass);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        
        elements.forEach(element => observer.observe(element));
    };
    
    // Configurar animaciones para diferentes elementos
    const fadeUpElements = document.querySelectorAll('.animate-fade-up');
    const fadeInElements = document.querySelectorAll('.animate-fade-in');
    const slideLeftElements = document.querySelectorAll('.animate-slide-left');
    const slideRightElements = document.querySelectorAll('.animate-slide-right');
    const scaleElements = document.querySelectorAll('.animate-scale');
    const rotateElements = document.querySelectorAll('.animate-rotate');
    
    animateOnScroll(fadeUpElements, 'fade-up-active');
    animateOnScroll(fadeInElements, 'fade-in-active');
    animateOnScroll(slideLeftElements, 'slide-left-active');
    animateOnScroll(slideRightElements, 'slide-right-active');
    animateOnScroll(scaleElements, 'scale-active');
    animateOnScroll(rotateElements, 'rotate-active');
    
    // Animación de cards con efecto "stagger"
    const serviceCards = document.querySelectorAll('.service-card');
    const featureCards = document.querySelectorAll('.feature-card');
    
    if (serviceCards.length > 0) {
        const serviceObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    serviceCards.forEach((card, index) => {
                        setTimeout(() => {
                            card.style.opacity = '1';
                            card.style.transform = 'translateY(0) rotate(0)';
                        }, index * 150);
                    });
                    serviceObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        
        serviceObserver.observe(document.querySelector('.services-grid'));
    }
    
    if (featureCards.length > 0) {
        const featureObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    featureCards.forEach((card, index) => {
                        setTimeout(() => {
                            card.style.opacity = '1';
                            card.style.transform = 'translateY(0) scale(1)';
                        }, index * 150);
                    });
                    featureObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        
        featureObserver.observe(document.querySelector('.features-grid'));
    }
    
    // Efecto hover mejorado para botones
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px) scale(1.05)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
    
    // Efecto hover para cards
    const cards = document.querySelectorAll('.card-hover');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px)';
            this.style.boxShadow = '0 20px 40px rgba(0, 0, 0, 0.15)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 10px 20px rgba(0, 0, 0, 0.1)';
        });
    });
    
    // Animación de iconos
    const icons = document.querySelectorAll('.animated-icon');
    icons.forEach(icon => {
        icon.addEventListener('mouseenter', function() {
            this.style.transform = 'rotate(15deg) scale(1.2)';
        });
        
        icon.addEventListener('mouseleave', function() {
            this.style.transform = 'rotate(0) scale(1)';
        });
    });
    
    // Slider de testimonios mejorado
    const testimonialSlider = document.querySelector('.testimonials-slider');
    if (testimonialSlider) {
        let isDown = false;
        let startX;
        let scrollLeft;
        
        // Agregar efecto de rebote al soltar
        testimonialSlider.style.transition = 'transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
        
        testimonialSlider.addEventListener('mousedown', (e) => {
            isDown = true;
            startX = e.pageX - testimonialSlider.offsetLeft;
            scrollLeft = testimonialSlider.scrollLeft;
            testimonialSlider.style.cursor = 'grabbing';
        });
        
        testimonialSlider.addEventListener('mouseleave', () => {
            if (isDown) {
                testimonialSlider.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    testimonialSlider.style.transform = 'scale(1)';
                }, 200);
            }
            isDown = false;
            testimonialSlider.style.cursor = 'grab';
        });
        
        testimonialSlider.addEventListener('mouseup', () => {
            if (isDown) {
                testimonialSlider.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    testimonialSlider.style.transform = 'scale(1)';
                }, 200);
            }
            isDown = false;
            testimonialSlider.style.cursor = 'grab';
        });
        
        testimonialSlider.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - testimonialSlider.offsetLeft;
            const walk = (x - startX) * 2;
            testimonialSlider.scrollLeft = scrollLeft - walk;
            testimonialSlider.style.transform = 'scale(0.99)';
        });
        
        // Touch events para móviles
        testimonialSlider.addEventListener('touchstart', (e) => {
            isDown = true;
            startX = e.touches[0].pageX - testimonialSlider.offsetLeft;
            scrollLeft = testimonialSlider.scrollLeft;
        });
        
        testimonialSlider.addEventListener('touchend', () => {
            if (isDown) {
                testimonialSlider.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    testimonialSlider.style.transform = 'scale(1)';
                }, 200);
            }
            isDown = false;
        });
    }
    
    // Efecto de ondas al hacer clic en botones
    const buttonsWithRipple = document.querySelectorAll('.btn-ripple');
    buttonsWithRipple.forEach(button => {
        button.addEventListener('click', function(e) {
            const x = e.clientX - e.target.getBoundingClientRect().left;
            const y = e.clientY - e.target.getBoundingClientRect().top;
            
            const ripple = document.createElement('span');
            ripple.classList.add('ripple');
            ripple.style.left = `${x}px`;
            ripple.style.top = `${y}px`;
            
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 1000);
        });
    });
});