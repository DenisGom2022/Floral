// Funcionalidad principal del sitio
document.addEventListener('DOMContentLoaded', function() {
    
    // Smooth scrolling para enlaces internos
    const links = document.querySelectorAll('a[href^="#"]');
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });

    // Toggle móvil para el menú
    const createMobileMenu = () => {
        const nav = document.querySelector('.main-nav');
        const headerContent = document.querySelector('.header-content');
        
        // Crear botón hamburguesa si no existe
        if (!document.querySelector('.mobile-menu-toggle')) {
            const toggleButton = document.createElement('button');
            toggleButton.className = 'mobile-menu-toggle';
            toggleButton.innerHTML = '<i class="fas fa-bars"></i>';
            toggleButton.style.cssText = `
                display: none;
                background: none;
                border: none;
                font-size: 24px;
                color: #2d5a27;
                cursor: pointer;
            `;
            
            headerContent.appendChild(toggleButton);
            
            // Agregar estilos para móvil
            const style = document.createElement('style');
            style.textContent = `
                @media (max-width: 768px) {
                    .mobile-menu-toggle {
                        display: block !important;
                    }
                    .main-nav {
                        display: none;
                        position: absolute;
                        top: 100%;
                        left: 0;
                        right: 0;
                        background: white;
                        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                        z-index: 1000;
                    }
                    .main-nav.active {
                        display: block;
                    }
                    .main-nav ul {
                        flex-direction: column;
                        padding: 20px;
                        gap: 15px;
                    }
                }
            `;
            document.head.appendChild(style);
            
            // Evento del toggle
            toggleButton.addEventListener('click', function() {
                nav.classList.toggle('active');
                const icon = this.querySelector('i');
                icon.className = nav.classList.contains('active') ? 'fas fa-times' : 'fas fa-bars';
            });
        }
    };

    createMobileMenu();

    // Animación de aparición en scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Aplicar animación a elementos
    const animateElements = document.querySelectorAll('.service-item, .faq-item');
    animateElements.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });

    // Funcionalidad de búsqueda
    const searchIcon = document.querySelector('.search-icon');
    if (searchIcon) {
        searchIcon.addEventListener('click', function() {
            const searchTerm = prompt('¿Qué productos estás buscando?');
            if (searchTerm) {
                // Aquí podrías redirigir a una página de resultados
                alert(`Buscando: ${searchTerm}\n\nEsta funcionalidad se implementaría con PHP y base de datos.`);
            }
        });
    }

    // Efecto parallax sutil para el hero
    const hero = document.querySelector('.hero');
    if (hero) {
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.5;
            hero.style.transform = `translateY(${rate}px)`;
        });
    }

    // Formulario de contacto rápido (si se implementa)
    const contactForms = document.querySelectorAll('.contact-form');
    contactForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validación básica
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = '#dc3545';
                } else {
                    field.style.borderColor = '#28a745';
                }
            });
            
            if (isValid) {
                // Aquí enviarías los datos al servidor
                alert('Gracias por tu interés. Nos pondremos en contacto contigo pronto.');
                form.reset();
            } else {
                alert('Por favor, completa todos los campos requeridos.');
            }
        });
    });

    // Contador de productos (simulado)
    const updateProductCount = () => {
        const productCountElements = document.querySelectorAll('.product-count');
        productCountElements.forEach(el => {
            let count = 0;
            const target = parseInt(el.dataset.count) || 1000;
            const increment = target / 100;
            
            const timer = setInterval(() => {
                count += increment;
                if (count >= target) {
                    count = target;
                    clearInterval(timer);
                }
                el.textContent = Math.floor(count).toLocaleString();
            }, 20);
        });
    };

    // Ejecutar contador cuando sea visible
    const countElements = document.querySelectorAll('.product-count');
    if (countElements.length > 0) {
        const countObserver = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    updateProductCount();
                    countObserver.unobserve(entry.target);
                }
            });
        });
        
        countElements.forEach(el => countObserver.observe(el));
    }

    // Lazy loading para imágenes
    const images = document.querySelectorAll('img[data-src]');
    const imageObserver = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                imageObserver.unobserve(img);
            }
        });
    });
    
    images.forEach(img => imageObserver.observe(img));

    console.log('USI Floral Imports - Sitio cargado correctamente');
});