<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_logged_in']) || !$_SESSION['user_logged_in']) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'] ?? 'Usuario';

// Datos de ejemplo para el dashboard
$recent_orders = [
    ['id' => 'USI-2024-001', 'date' => '2024-11-01', 'total' => '$1,245.50', 'status' => 'Enviado'],
    ['id' => 'USI-2024-002', 'date' => '2024-11-03', 'total' => '$856.75', 'status' => 'Procesando'],
    ['id' => 'USI-2024-003', 'date' => '2024-11-05', 'total' => '$2,130.25', 'status' => 'Entregado']
];

$popular_products = [
    ['name' => 'Premium Silk Roses', 'orders' => 45],
    ['name' => 'Hydrangea Clusters', 'orders' => 32],
    ['name' => 'Peony Arrangements', 'orders' => 28],
    ['name' => 'Orchid Sprays', 'orders' => 24]
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - USI Floral Imports</title>
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
                            <li><a href="contact.php">Contact</a></li>
                            <li><a href="dashboard.php" class="active">Mi Cuenta</a></li>
                        </ul>
                    </nav>
                    
                    <div class="header-actions">
                        <div class="user-menu">
                            <span><i class="fas fa-user"></i> <?php echo htmlspecialchars($username); ?></span>
                            <a href="logout.php" class="btn btn-secondary">Cerrar Sesión</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Dashboard Content -->
    <div class="dashboard-container">
        <div class="container">
            <div class="dashboard-header">
                <h1>Bienvenido, <?php echo htmlspecialchars($username); ?></h1>
                <p>Panel de control de tu cuenta mayorista</p>
            </div>

            <!-- Dashboard Stats -->
            <div class="dashboard-stats">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="stat-info">
                        <h3>12</h3>
                        <p>Pedidos este mes</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="stat-info">
                        <h3>$8,245</h3>
                        <p>Total gastado</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <div class="stat-info">
                        <h3>24</h3>
                        <p>Productos favoritos</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-truck"></i>
                    </div>
                    <div class="stat-info">
                        <h3>2</h3>
                        <p>Envíos pendientes</p>
                    </div>
                </div>
            </div>

            <!-- Dashboard Grid -->
            <div class="dashboard-grid">
                <!-- Recent Orders -->
                <div class="dashboard-section">
                    <div class="section-header">
                        <h2><i class="fas fa-clock"></i> Pedidos Recientes</h2>
                        <a href="#" class="view-all">Ver todos</a>
                    </div>
                    
                    <div class="orders-list">
                        <?php foreach ($recent_orders as $order): ?>
                            <div class="order-item">
                                <div class="order-info">
                                    <div class="order-id"><?php echo $order['id']; ?></div>
                                    <div class="order-date"><?php echo date('d/m/Y', strtotime($order['date'])); ?></div>
                                </div>
                                <div class="order-total"><?php echo $order['total']; ?></div>
                                <div class="order-status status-<?php echo strtolower($order['status']); ?>">
                                    <?php echo $order['status']; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="dashboard-section">
                    <div class="section-header">
                        <h2><i class="fas fa-zap"></i> Acciones Rápidas</h2>
                    </div>
                    
                    <div class="quick-actions">
                        <a href="floral.php" class="action-btn">
                            <i class="fas fa-flower"></i>
                            <span>Explorar Productos</span>
                        </a>
                        
                        <a href="#" class="action-btn">
                            <i class="fas fa-clipboard-list"></i>
                            <span>Nueva Cotización</span>
                        </a>
                        
                        <a href="#" class="action-btn">
                            <i class="fas fa-history"></i>
                            <span>Historial de Pedidos</span>
                        </a>
                        
                        <a href="contact.php" class="action-btn">
                            <i class="fas fa-headset"></i>
                            <span>Soporte</span>
                        </a>
                    </div>
                </div>

                <!-- Popular Products -->
                <div class="dashboard-section">
                    <div class="section-header">
                        <h2><i class="fas fa-star"></i> Productos Populares</h2>
                        <a href="floral.php" class="view-all">Ver catálogo</a>
                    </div>
                    
                    <div class="popular-products">
                        <?php foreach ($popular_products as $product): ?>
                            <div class="product-item">
                                <div class="product-name"><?php echo $product['name']; ?></div>
                                <div class="product-orders"><?php echo $product['orders']; ?> pedidos</div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Account Info -->
                <div class="dashboard-section">
                    <div class="section-header">
                        <h2><i class="fas fa-user"></i> Información de Cuenta</h2>
                        <a href="#" class="view-all">Editar</a>
                    </div>
                    
                    <div class="account-info">
                        <div class="info-item">
                            <strong>Tipo de Cuenta:</strong> Comprador B2B Verificado
                        </div>
                        <div class="info-item">
                            <strong>Miembro desde:</strong> Enero 2024
                        </div>
                        <div class="info-item">
                            <strong>Método de Pago:</strong> Tarjeta de Crédito ****1234
                        </div>
                        <div class="info-item">
                            <strong>Descuento Actual:</strong> 15% (Comprador Regular)
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-menu span {
            color: #2d5a27;
            font-weight: 500;
        }
        
        .dashboard-container {
            padding: 40px 0 80px;
            background-color: #f8f9fa;
            min-height: calc(100vh - 200px);
        }
        
        .dashboard-header {
            margin-bottom: 40px;
        }
        
        .dashboard-header h1 {
            color: #2d5a27;
            font-size: 36px;
            margin-bottom: 10px;
        }
        
        .dashboard-header p {
            color: #666;
            font-size: 18px;
        }
        
        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            background-color: #2d5a27;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }
        
        .stat-info h3 {
            color: #2d5a27;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .stat-info p {
            color: #666;
            font-size: 14px;
            margin: 0;
        }
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 30px;
        }
        
        .dashboard-section {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        
        .section-header {
            padding: 20px 25px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .section-header h2 {
            color: #2d5a27;
            font-size: 20px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .view-all {
            color: #2d5a27;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }
        
        .view-all:hover {
            text-decoration: underline;
        }
        
        .orders-list {
            padding: 0 25px 25px;
        }
        
        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .order-item:last-child {
            border-bottom: none;
        }
        
        .order-info {
            flex: 1;
        }
        
        .order-id {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }
        
        .order-date {
            color: #666;
            font-size: 14px;
        }
        
        .order-total {
            font-weight: 600;
            color: #2d5a27;
            margin-right: 20px;
        }
        
        .order-status {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-enviado {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-procesando {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-entregado {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        
        .quick-actions {
            padding: 25px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .action-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
        }
        
        .action-btn:hover {
            background-color: #2d5a27;
            color: white;
            transform: translateY(-2px);
        }
        
        .action-btn i {
            font-size: 24px;
            margin-bottom: 10px;
            color: #2d5a27;
        }
        
        .action-btn:hover i {
            color: white;
        }
        
        .action-btn span {
            font-size: 14px;
            font-weight: 500;
            text-align: center;
        }
        
        .popular-products {
            padding: 0 25px 25px;
        }
        
        .product-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .product-item:last-child {
            border-bottom: none;
        }
        
        .product-name {
            color: #333;
            font-weight: 500;
        }
        
        .product-orders {
            color: #666;
            font-size: 14px;
        }
        
        .account-info {
            padding: 25px;
        }
        
        .info-item {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .info-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        
        .info-item strong {
            color: #2d5a27;
        }
        
        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
            
            .dashboard-stats {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .quick-actions {
                grid-template-columns: 1fr;
            }
            
            .order-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .order-total {
                margin-right: 0;
            }
        }
        
        @media (max-width: 480px) {
            .dashboard-stats {
                grid-template-columns: 1fr;
            }
            
            .stat-card {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>

    <script src="js/script.js"></script>
</body>
</html>