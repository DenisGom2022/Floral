<?php
session_start();

// Procesar el formulario de registro
if ($_POST) {
    $company_name = $_POST['company_name'] ?? '';
    $business_license = $_POST['business_license'] ?? '';
    $contact_name = $_POST['contact_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $business_type = $_POST['business_type'] ?? '';
    
    // Validación básica
    if ($company_name && $business_license && $contact_name && $email) {
        // Aquí normalmente guardarías en base de datos y enviarías emails
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
    <title>Registro - USI Floral Imports</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .registration-container {
            min-height: 100vh;
            padding: 40px 0;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }
        .registration-box {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: 0 auto;
        }
        .registration-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .registration-header h2 {
            color: #2d5a27;
            font-size: 28px;
            margin-bottom: 10px;
        }
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group.full-width {
            grid-column: 1 / -1;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: 500;
        }
        .required {
            color: #dc3545;
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
            min-height: 100px;
        }
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }
        .info-box {
            background-color: #e7f3ff;
            border: 1px solid #b3d7ff;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 30px;
        }
        .info-box h3 {
            color: #2d5a27;
            margin-bottom: 10px;
        }
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        .back-link a {
            color: #2d5a27;
            text-decoration: none;
        }
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="registration-container">
        <div class="container">
            <div class="registration-box">
                <div class="registration-header">
                    <h2><i class="fas fa-leaf"></i> Registro de Comprador B2B</h2>
                    <p>Únete a nuestra red de compradores mayoristas</p>
                </div>
                
                <div class="info-box">
                    <h3><i class="fas fa-info-circle"></i> Requisitos de Registro</h3>
                    <p>Como empresa mayorista, vendemos exclusivamente business-to-business (B2B). Para registrarse necesita:</p>
                    <ul>
                        <li>Licencia comercial válida</li>
                        <li>Licencia de reventa o permiso de vendedor</li>
                        <li>Documentación empresarial verificable</li>
                    </ul>
                    <p><strong>Procesamiento:</strong> Todas las solicitudes se verifican cuidadosamente y normalmente se procesan en 24 horas.</p>
                </div>
                
                <?php if (isset($success) && $success): ?>
                    <div class="success-message">
                        <h3><i class="fas fa-check-circle"></i> ¡Solicitud Enviada!</h3>
                        <p>Gracias por tu solicitud de registro. Hemos recibido tu información y nuestro equipo la revisará dentro de las próximas 24 horas.</p>
                        <p>Recibirás una confirmación por email una vez que tu cuenta sea aprobada.</p>
                        <div style="margin-top: 15px;">
                            <a href="index.php" class="btn btn-primary">Volver al Inicio</a>
                        </div>
                    </div>
                <?php else: ?>
                    <?php if (isset($error)): ?>
                        <div class="error-message">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="company_name">Nombre de la Empresa <span class="required">*</span></label>
                                <input type="text" id="company_name" name="company_name" required 
                                       value="<?php echo htmlspecialchars($_POST['company_name'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="business_license">Número de Licencia Comercial <span class="required">*</span></label>
                                <input type="text" id="business_license" name="business_license" required
                                       value="<?php echo htmlspecialchars($_POST['business_license'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="contact_name">Nombre de Contacto <span class="required">*</span></label>
                                <input type="text" id="contact_name" name="contact_name" required
                                       value="<?php echo htmlspecialchars($_POST['contact_name'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email Empresarial <span class="required">*</span></label>
                                <input type="email" id="email" name="email" required
                                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="phone">Teléfono</label>
                                <input type="tel" id="phone" name="phone"
                                       value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="business_type">Tipo de Negocio</label>
                                <select id="business_type" name="business_type">
                                    <option value="">Seleccionar...</option>
                                    <option value="floral_designer">Diseñador Floral</option>
                                    <option value="event_planner">Planificador de Eventos</option>
                                    <option value="wedding_venue">Salón de Bodas</option>
                                    <option value="retailer">Minorista</option>
                                    <option value="decorator">Decorador</option>
                                    <option value="wholesaler">Mayorista</option>
                                    <option value="other">Otro</option>
                                </select>
                            </div>
                            
                            <div class="form-group full-width">
                                <label for="address">Dirección Comercial</label>
                                <textarea id="address" name="address" placeholder="Incluye dirección completa, ciudad, estado, código postal"><?php echo htmlspecialchars($_POST['address'] ?? ''); ?></textarea>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 20px;">
                            <i class="fas fa-paper-plane"></i> Enviar Solicitud de Registro
                        </button>
                    </form>
                <?php endif; ?>
                
                <div class="back-link">
                    <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
                    <p><a href="index.php">← Volver al inicio</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>