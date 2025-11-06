<?php
session_start();

// Procesar el formulario de contacto
if ($_POST) {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $company = $_POST['company'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';
    
    if ($name && $email && $message) {
        // Aquí normalmente enviarías el email
        $success = true;
    } else {
        $error = 'Por favor completa todos los campos requeridos.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto - USI Floral Imports</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-top">
            <div class="container">
                <div class="header-info">
                    <span><i class="fas fa-business-time"></i> Wholesale Trade Only • Import & Export • B2B Industry Supplier</span>
                    <span><i class="fas fa-clock"></i> Monday-Friday 9am-5pm PT</span>
                </div>
            </div>
        </div>
        
        <div class="header-main">
            <div class="container">
                <div class="header-content">
                    <div class="logo">
                        <a href="index.php"><h1><i class="fas fa-leaf"></i> USI Floral Imports</h1></a>
                    </div>
                    
                    <nav class="main-nav">
                        <ul>
                            <li><a href="floral.php">Floral</a></li>
                            <li><a href="greenery.php">Greenery & Trees</a></li>
                            <li><a href="decor.php">Decor</a></li>
                            <li><a href="contact.php" class="active">Contact</a></li>
                            <li><a href="registration.php">Registration</a></li>
                        </ul>
                    </nav>
                    
                    <div class="header-actions">
                        <a href="login.php" class="btn btn-login">Log In</a>
                        <i class="fas fa-search search-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Contact Hero -->
    <section class="page-hero">
        <div class="container">
            <h1>Contactanos</h1>
            <p>Estamos aquí para ayudarte con todas tus necesidades de productos florales mayoristas</p>
        </div>
    </section>

    <!-- Contact Content -->
    <section class="contact-section">
        <div class="container">
            <div class="contact-grid">
                <!-- Contact Information -->
                <div class="contact-info">
                    <h2>Información de Contacto</h2>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="contact-details">
                            <h3>Teléfono</h3>
                            <p>(555) 123-4567</p>
                            <small>Lun-Vie 9am-5pm PT</small>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-details">
                            <h3>Email</h3>
                            <p>info@usifloralimports.com</p>
                            <small>Respuesta en 24 horas</small>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-details">
                            <h3>Showroom Los Angeles</h3>
                            <p>1234 Floral District Blvd<br>Los Angeles, CA 90015</p>
                            <small>Solo con cita previa</small>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-warehouse"></i>
                        </div>
                        <div class="contact-details">
                            <h3>Almacén</h3>
                            <p>Envío directo desde nuestro almacén</p>
                            <small>Procesamiento mismo día</small>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="contact-form-section">
                    <h2>Envíanos un Mensaje</h2>
                    
                    <?php if (isset($success) && $success): ?>
                        <div class="success-message">
                            <i class="fas fa-check-circle"></i>
                            <h3>¡Mensaje Enviado!</h3>
                            <p>Gracias por contactarnos. Nos pondremos en contacto contigo dentro de las próximas 24 horas.</p>
                        </div>
                    <?php else: ?>
                        <?php if (isset($error)): ?>
                            <div class="error-message">
                                <?php echo htmlspecialchars($error); ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="" class="contact-form">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="name">Nombre *</label>
                                    <input type="text" id="name" name="name" required
                                           value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="email">Email *</label>
                                    <input type="email" id="email" name="email" required
                                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="company">Empresa</label>
                                    <input type="text" id="company" name="company"
                                           value="<?php echo htmlspecialchars($_POST['company'] ?? ''); ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="subject">Asunto</label>
                                    <select id="subject" name="subject">
                                        <option value="">Seleccionar asunto...</option>
                                        <option value="quote">Solicitar Cotización</option>
                                        <option value="account">Información de Cuenta</option>
                                        <option value="products">Consulta de Productos</option>
                                        <option value="shipping">Información de Envío</option>
                                        <option value="other">Otro</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="message">Mensaje *</label>
                                <textarea id="message" name="message" required 
                                          placeholder="Cuéntanos cómo podemos ayudarte..."><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Enviar Mensaje
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Info Section -->
    <section class="quick-info-section">
        <div class="container">
            <div class="quick-info-grid">
                <div class="quick-info-item">
                    <div class="quick-info-icon">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <h3>Envío Rápido</h3>
                    <p>Pedidos procesados el mismo día y enviados al día siguiente</p>
                </div>
                
                <div class="quick-info-item">
                    <div class="quick-info-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <h3>Pedido Mínimo</h3>
                    <p>$200 primer pedido, $100 pedidos posteriores</p>
                </div>
                
                <div class="quick-info-item">
                    <div class="quick-info-icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <h3>Múltiples Pagos</h3>
                    <p>Tarjetas de crédito, transferencias, Zelle y más</p>
                </div>
                
                <div class="quick-info-item">
                    <div class="quick-info-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Solo B2B</h3>
                    <p>Exclusivamente para empresas con licencia comercial</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>USI Floral Imports</h4>
                    <p>The best deals in high-quality silk botanicals & event decor items for floral designers, bridal & event venues, party & home decorators, retailers, set producers, organizations and more.</p>
                </div>
                
                <div class="footer-section">
                    <h4>Main menu</h4>
                    <ul>
                        <li><a href="floral.php">Floral</a></li>
                        <li><a href="greenery.php">Greenery & Trees</a></li>
                        <li><a href="decor.php">Decor</a></li>
                        <li><a href="contact.php">Contact</a></li>
                        <li><a href="registration.php">Registration</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Quick links</h4>
                    <ul>
                        <li><a href="search.php">Search</a></li>
                        <li><a href="privacy.php">Privacy Policy</a></li>
                        <li><a href="terms.php">Terms & Conditions</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2025, USI Floral Imports</p>
                <p>This site is tailored to support our B2B buyers. Questions? <a href="contact.php">Contact</a> us!</p>
            </div>
        </div>
    </footer>

    <style>
        .logo a {
            text-decoration: none;
            color: inherit;
        }
        
        .active {
            color: #2d5a27 !important;
            font-weight: 600;
        }
        
        .page-hero {
            background: linear-gradient(135deg, #2d5a27 0%, #4a7c43 100%);
            color: white;
            padding: 80px 0;
            text-align: center;
        }
        
        .page-hero h1 {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 20px;
        }
        
        .page-hero p {
            font-size: 20px;
            opacity: 0.9;
        }
        
        .contact-section {
            padding: 80px 0;
        }
        
        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: start;
        }
        
        .contact-info h2,
        .contact-form-section h2 {
            font-size: 32px;
            color: #2d5a27;
            margin-bottom: 30px;
        }
        
        .contact-item {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
            align-items: flex-start;
        }
        
        .contact-icon {
            width: 50px;
            height: 50px;
            background-color: #2d5a27;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            flex-shrink: 0;
        }
        
        .contact-details h3 {
            color: #2d5a27;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .contact-details p {
            color: #333;
            margin-bottom: 5px;
        }
        
        .contact-details small {
            color: #666;
            font-size: 14px;
        }
        
        .contact-form {
            background-color: #f8f9fa;
            padding: 30px;
            border-radius: 10px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: 500;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #2d5a27;
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }
        
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
            text-align: center;
        }
        
        .success-message i {
            font-size: 48px;
            margin-bottom: 15px;
            display: block;
        }
        
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }
        
        .quick-info-section {
            background-color: #f8f9fa;
            padding: 60px 0;
        }
        
        .quick-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }
        
        .quick-info-item {
            text-align: center;
            padding: 30px 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        .quick-info-icon {
            width: 60px;
            height: 60px;
            background-color: #2d5a27;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            margin: 0 auto 20px;
        }
        
        .quick-info-item h3 {
            color: #2d5a27;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .quick-info-item p {
            color: #666;
            line-height: 1.6;
        }
        
        @media (max-width: 768px) {
            .contact-grid {
                grid-template-columns: 1fr;
                gap: 40px;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .page-hero h1 {
                font-size: 32px;
            }
            
            .page-hero p {
                font-size: 16px;
            }
        }
    </style>

    <script src="js/script.js"></script>
</body>
</html>