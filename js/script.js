// Enhanced functionality for the educational flower database
document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize all components
    initSmoothScrolling();
    initMobileMenu();
    initFlowerCardAnimations();
    initHeroAnimations();
    initSearchFeatures();
    
    // Smooth scrolling for internal links
    function initSmoothScrolling() {
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
    }

    // Enhanced mobile menu functionality
    function initMobileMenu() {
        const nav = document.querySelector('.main-nav');
        const headerContent = document.querySelector('.header-content');
        
        // Create hamburger button if it doesn't exist
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
                padding: 10px;
            `;
            
            headerContent.appendChild(toggleButton);
            
            // Add mobile styles
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
                        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                        z-index: 1000;
                    }
                    .main-nav.active {
                        display: block !important;
                    }
                    .main-nav ul {
                        flex-direction: column;
                        padding: 20px;
                    }
                    .main-nav li {
                        margin: 10px 0;
                    }
                }
            `;
            document.head.appendChild(style);
            
            // Toggle functionality
            toggleButton.addEventListener('click', function() {
                nav.classList.toggle('active');
                const icon = this.querySelector('i');
                icon.className = nav.classList.contains('active') ? 'fas fa-times' : 'fas fa-bars';
            });
        }
    }

    // Flower card hover and scroll animations
    function initFlowerCardAnimations() {
        const cards = document.querySelectorAll('.flower-card');
        
        // Intersection Observer for scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const cardObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);
        
        cards.forEach((card, index) => {
            // Initial state for animation
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
            
            cardObserver.observe(card);
            
            // Enhanced hover effects
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-15px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    }

    // Hero section animations
    function initHeroAnimations() {
        const heroElements = document.querySelectorAll('.hero-title, .hero-subtitle, .hero-stats');
        
        heroElements.forEach((element, index) => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(30px)';
            
            setTimeout(() => {
                element.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';
            }, index * 200);
        });
        
        // Floating flower animations
        const floatingFlowers = document.querySelectorAll('.floating-flower');
        floatingFlowers.forEach((flower, index) => {
            flower.style.animationDelay = `${index * 2}s`;
        });
    }

    // Search and filter functionality
    function initSearchFeatures() {
        const searchInput = document.querySelector('#flower-search');
        const cards = document.querySelectorAll('.flower-card');
        
        if (searchInput && cards.length > 0) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                
                cards.forEach(card => {
                    const flowerName = card.querySelector('.flower-name').textContent.toLowerCase();
                    const flowerCategory = card.querySelector('.flower-category')?.textContent.toLowerCase() || '';
                    const flowerDescription = card.querySelector('.flower-description')?.textContent.toLowerCase() || '';
                    
                    const matches = flowerName.includes(searchTerm) || 
                                   flowerCategory.includes(searchTerm) || 
                                   flowerDescription.includes(searchTerm);
                    
                    if (matches) {
                        card.style.display = 'block';
                        card.style.animation = 'fadeIn 0.3s ease';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        }
    }

    // Stat counter animation
    function animateCounters() {
        const counters = document.querySelectorAll('.stat-count');
        
        counters.forEach(counter => {
            const finalValue = counter.textContent;
            
            if (!isNaN(finalValue)) {
                let startValue = 0;
                const increment = finalValue / 50;
                
                const timer = setInterval(() => {
                    startValue += increment;
                    counter.textContent = Math.floor(startValue);
                    
                    if (startValue >= finalValue) {
                        counter.textContent = finalValue;
                        clearInterval(timer);
                    }
                }, 50);
            }
        });
    }

    // Initialize counter animation when stats come into view
    const statsSection = document.querySelector('.quick-stats');
    if (statsSection) {
        const statsObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounters();
                    statsObserver.unobserve(entry.target);
                }
            });
        });
        
        statsObserver.observe(statsSection);
    }

    // Add loading animation for images
    const images = document.querySelectorAll('.flower-image img');
    images.forEach(img => {
        img.addEventListener('load', function() {
            this.style.opacity = '1';
        });
        
        img.style.transition = 'opacity 0.3s ease';
        if (img.complete) {
            img.style.opacity = '1';
        } else {
            img.style.opacity = '0';
        }
    });

    // Enhanced button interactions
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Ripple effect
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.cssText = `
                position: absolute;
                width: ${size}px;
                height: ${size}px;
                left: ${x}px;
                top: ${y}px;
                background: rgba(255, 255, 255, 0.3);
                border-radius: 50%;
                pointer-events: none;
                animation: ripple 0.6s ease-out;
            `;
            
            this.style.position = 'relative';
            this.style.overflow = 'hidden';
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });

    // Add CSS for ripple animation
    const rippleStyle = document.createElement('style');
    rippleStyle.textContent = `
        @keyframes ripple {
            from {
                transform: scale(0);
                opacity: 1;
            }
            to {
                transform: scale(2);
                opacity: 0;
            }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    `;
    document.head.appendChild(rippleStyle);

});
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