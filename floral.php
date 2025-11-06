<?php
session_start();
require_once 'includes/database.php';

// Inicializar managers de base de datos
$productManager = new ProductManager();
$categoryManager = new CategoryManager();

// Obtener parámetros de filtrado
$selectedCategory = $_GET['category'] ?? 'all';
$searchTerm = $_GET['search'] ?? '';
$sortBy = $_GET['sort'] ?? 'name';
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 12;
$offset = ($page - 1) * $perPage;

// Variables para mostrar datos
$products = [];
$categories = [];
$totalProducts = 0;
$errorMessage = '';

// Obtener categorías para el filtro
try {
    $categoriesFromDB = $categoryManager->getAllCategories();
    $categories = ['all' => 'All'];
    foreach ($categoriesFromDB as $cat) {
        $categories[$cat['slug']] = $cat['name'];
    }
} catch (Exception $e) {
    $errorMessage = "Error obteniendo categorías: " . $e->getMessage();
    $categories = ['all' => 'All', 'roses' => 'Roses', 'hydrangeas' => 'Hydrangeas'];
}

// Obtener productos según filtros - MOSTRAR ERRORES SQL
try {
    if (!empty($searchTerm)) {
        // Búsqueda VULNERABLE - mostrar errores
        $products = $productManager->searchProducts($searchTerm, $perPage);
        $totalProducts = count($products);
    } elseif ($selectedCategory !== 'all') {
        // Filtrar por categoría
        $products = $productManager->getProductsByCategory($selectedCategory, $perPage);
        $totalProducts = $productManager->getProductCountByCategory($selectedCategory);
    } else {
        // Todos los productos
        $products = $productManager->getAllProducts($perPage, $offset);
        $totalProducts = $productManager->getProductCountByCategory();
    }
} catch (Exception $e) {
    // MOSTRAR ERROR SQL COMPLETO EN PANTALLA
    $errorMessage = "ERROR SQL: " . $e->getMessage() . "\n\nDetalles técnicos: " . $e->getFile() . " línea " . $e->getLine();
    $products = [];
    $totalProducts = 0;
}
// Aplicar ordenamiento si es necesario
if ($sortBy === 'price-low') {
    usort($products, function($a, $b) {
        return $a['price'] <=> $b['price'];
    });
} elseif ($sortBy === 'price-high') {
    usort($products, function($a, $b) {
        return $b['price'] <=> $a['price'];
    });
} elseif ($sortBy === 'category') {
    usort($products, function($a, $b) {
        return strcmp($a['category_name'], $b['category_name']);
    });
}

// Variables para el usuario logueado
$isLoggedIn = isUserLoggedIn();
$userDiscount = getUserDiscount();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Floral Products - USI Floral Imports</title>
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
                            <li><a href="floral.php" class="active">Floral</a></li>
                            <li><a href="greenery.php">Greenery & Trees</a></li>
                            <li><a href="decor.php">Decor</a></li>
                            <li><a href="contact.php">Contact</a></li>
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

    <!-- Page Hero -->
    <section class="page-hero">
        <div class="container">
            <h1><i class="fas fa-database"></i> Flower Database</h1>
            <p>Floral information consultation and exploration system</p>
            <div class="hero-stats">
                <div class="stat-item">
                    <span class="stat-number"><?php echo $totalProducts; ?></span>
                    <span class="stat-label">Registered Species</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?php echo count($categories) - 1; ?></span>
                    <span class="stat-label">Categories</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">100%</span>
                    <span class="stat-label">Verified Data</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Login Notice -->
    <?php if (!$isLoggedIn): ?>
    <section class="login-notice">
        <div class="container">
            <div class="notice-content">
                <div class="notice-icon">
                    <i class="fas fa-lock"></i>
                </div>
                <div class="notice-text">
                    <h3>Prices Only for Registered Buyers</h3>
                    <p>To view prices and place orders, <a href="login.php">log in</a> with your B2B account or <a href="registration.php">register</a> as a wholesale buyer.</p>
                </div>
                <div class="notice-actions">
                    <a href="login.php" class="btn btn-primary">Log In</a>
                    <a href="registration.php" class="btn btn-secondary">Register</a>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php if (!empty($errorMessage)): ?>
    <section class="error-notice">
        <div class="container">
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i>
                <div class="error-content">
                    <h4>SQL Query Error</h4>
                    <pre><?php echo htmlspecialchars($errorMessage); ?></pre>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Products Section -->
    <section class="products-section">
        <div class="container">
            <!-- Filter Bar -->
            <div class="filter-bar">
                <div class="filter-left">
                    <h2><i class="fas fa-search"></i> Floral Data Explorer</h2>
                    <span class="product-count">
                        <i class="fas fa-chart-bar"></i> <?php echo $totalProducts; ?> records found
                    </span>
                    <?php if (!empty($searchTerm)): ?>
                        <span class="search-info">
                            <i class="fas fa-filter"></i> Filtered by: "<?php echo htmlspecialchars($searchTerm); ?>"
                        </span>
                    <?php endif; ?>
                </div>
                
                <div class="filter-controls">
                    <form method="GET" action="" class="filter-form">
                        <div class="search-filter">
                            <input type="text" name="search" placeholder="🔍 Search database..." 
                                   value="<?php echo htmlspecialchars($searchTerm); ?>">
                            <button type="submit" class="search-btn">
                                <i class="fas fa-search"></i> Query
                            </button>
                            <?php if (!empty($searchTerm)): ?>
                                <a href="floral.php" class="clear-search-btn" title="Clear search">
                                    <i class="fas fa-times"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                        
                        <div class="category-filter">
                            <label for="category-select"><i class="fas fa-layer-group"></i> Taxonomy:</label>
                            <select id="category-select" name="category" onchange="this.form.submit()">
                                <?php foreach ($categories as $slug => $name): ?>
                                    <option value="<?php echo $slug; ?>" <?php echo $selectedCategory === $slug ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="sort-filter">
                            <label for="sort-select"><i class="fas fa-sort"></i> Sort by:</label>
                            <select id="sort-select" name="sort" onchange="this.form.submit()">
                                <option value="name" <?php echo $sortBy === 'name' ? 'selected' : ''; ?>>Name A-Z</option>
                                <option value="price-low" <?php echo $sortBy === 'price-low' ? 'selected' : ''; ?>>Value: Low to High</option>
                                <option value="price-high" <?php echo $sortBy === 'price-high' ? 'selected' : ''; ?>>Value: High to Low</option>
                                <option value="category" <?php echo $sortBy === 'category' ? 'selected' : ''; ?>>By Category</option>
                            </select>
                        </div>
                        
                        <!-- Maintain filters but NOT duplicate search -->
                        <?php if ($selectedCategory !== 'all'): ?>
                            <input type="hidden" name="category" value="<?php echo htmlspecialchars($selectedCategory); ?>">
                        <?php endif; ?>
                        <?php if ($sortBy !== 'name'): ?>
                            <input type="hidden" name="sort" value="<?php echo htmlspecialchars($sortBy); ?>">
                        <?php endif; ?>
                    </form>
                </div>
            </div>

            <!-- Data Grid -->
            <?php if (empty($products)): ?>
                <div class="no-products">
                    <div class="no-products-content">
                        <i class="fas fa-database fa-3x"></i>
                        <h3>No Results Found</h3>
                        <?php if (!empty($searchTerm)): ?>
                            <p>No records found matching "<?php echo htmlspecialchars($searchTerm); ?>"</p>
                            <div class="suggestions">
                                <h4>Search Suggestions:</h4>
                                <ul>
                                    <li><code>' OR 1=1 #</code> - Show all records</li>
                                    <li><code>' UNION SELECT 1,database(),3,4,5,6,7,8,9,10,11,12,13,14 #</code> - System information</li>
                                    <li><code>Rosa</code> - Search by common name</li>
                                </ul>
                            </div>
                            <a href="floral.php" class="btn btn-primary">View all records</a>
                        <?php else: ?>
                            <p>No data available in this category.</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="data-grid">
                    <?php foreach ($products as $product): ?>
                        <div class="data-card" data-category="<?php echo strtolower($product['category_slug'] ?? $product['category_name']); ?>">
                            <div class="data-header">
                                <div class="specimen-id">ID: <?php echo $product['id']; ?></div>
                                <div class="data-status">
                                    <i class="fas fa-check-circle"></i> Verified
                                </div>
                            </div>
                            
                            <div class="specimen-image">
                                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                                     alt="<?php echo htmlspecialchars($product['name']); ?>"
                                     onerror="this.src='https://via.placeholder.com/400x300/e8f5e8/2d5a27?text=🌸+<?php echo urlencode($product['name']); ?>'">
                                <div class="image-overlay">
                                    <button class="btn btn-primary view-details" data-product-id="<?php echo $product['id']; ?>">
                                        <i class="fas fa-info-circle"></i> Technical Details
                                    </button>
                                </div>
                            </div>
                            
                            <div class="specimen-data">
                                <div class="taxonomic-info">
                                    <span class="family"><?php echo htmlspecialchars($product['category_name']); ?></span>
                                </div>
                                <h3 class="scientific-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                                <p class="description"><?php echo htmlspecialchars($product['short_description'] ?? $product['description']); ?></p>
                                
                                <div class="characteristics">
                                    <?php if (isset($product['color']) && $product['color']): ?>
                                        <div class="char-item">
                                            <span class="char-label">Color:</span>
                                            <span class="char-value"><?php echo htmlspecialchars($product['color']); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (isset($product['material']) && $product['material']): ?>
                                        <div class="char-item">
                                            <span class="char-label">Material:</span>
                                            <span class="char-value"><?php echo htmlspecialchars($product['material']); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    <div class="char-item">
                                        <span class="char-label">SKU:</span>
                                        <span class="char-value"><?php echo htmlspecialchars($product['sku'] ?? 'N/A'); ?></span>
                                    </div>
                                    <div class="char-item">
                                        <span class="char-label">Stock:</span>
                                        <span class="char-value"><?php echo $product['stock_quantity'] ?? 0; ?> units</span>
                                    </div>
                                </div>
                                
                                <div class="data-actions">
                                    <button class="btn btn-info view-details" data-product-id="<?php echo $product['id']; ?>">
                                        <i class="fas fa-info-circle"></i> View Details
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Paginación (si hay más productos) -->
                <?php if ($totalProducts > $perPage && empty($searchTerm)): ?>
                    <div class="pagination">
                        <?php 
                        $totalPages = ceil($totalProducts / $perPage);
                        $currentPage = $page;
                        ?>
                        
                        <?php if ($currentPage > 1): ?>
                            <a href="?page=<?php echo $currentPage - 1; ?>&category=<?php echo $selectedCategory; ?>&sort=<?php echo $sortBy; ?>" 
                               class="pagination-btn">
                                <i class="fas fa-chevron-left"></i> Previous
                            </a>
                        <?php endif; ?>
                        
                        <span class="pagination-info">
                            Page <?php echo $currentPage; ?> of <?php echo $totalPages; ?>
                        </span>
                        
                        <?php if ($currentPage < $totalPages): ?>
                            <a href="?page=<?php echo $currentPage + 1; ?>&category=<?php echo $selectedCategory; ?>&sort=<?php echo $sortBy; ?>" 
                               class="pagination-btn">
                                Next <i class="fas fa-chevron-right"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <h2>Why Choose Our Floral Products?</h2>
            <div class="features-grid">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-award"></i>
                    </div>
                    <h3>Premium Quality</h3>
                    <p>Highest quality materials that look and feel like real flowers</p>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-palette"></i>
                    </div>
                    <h3>Wide Variety</h3>
                    <p>Extensive selection of flowers in multiple colors and styles</p>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3>Long Duration</h3>
                    <p>Products designed to maintain their beauty over time</p>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <h3>Wholesale Purchase</h3>
                    <p>Competitive prices for wholesale orders</p>
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
            background: linear-gradient(135deg, #1a4d1e 0%, #2d5a27 50%, #4a7c43 100%);
            color: white;
            padding: 80px 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .page-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="40" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="40" cy="80" r="1.5" fill="rgba(255,255,255,0.1)"/></svg>');
            animation: float 20s infinite linear;
        }
        
        @keyframes float {
            0% { transform: translateY(0px); }
            100% { transform: translateY(-100px); }
        }
        
        .page-hero h1 {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .page-hero h1 i {
            margin-right: 15px;
            color: #90ee90;
        }
        
        .page-hero p {
            font-size: 20px;
            opacity: 0.9;
            margin-bottom: 40px;
        }
        
        .hero-stats {
            display: flex;
            justify-content: center;
            gap: 60px;
            margin-top: 40px;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-number {
            display: block;
            font-size: 36px;
            font-weight: 700;
            color: #90ee90;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .stat-label {
            display: block;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.8;
            margin-top: 5px;
        }
        
        .login-notice {
            background-color: #fff3cd;
            border-bottom: 3px solid #ffc107;
            padding: 30px 0;
        }
        
        .error-notice {
            background-color: #f8d7da;
            border-bottom: 3px solid #dc3545;
            padding: 20px 0;
        }
        
        .alert {
            padding: 15px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .alert-warning {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .error-content h4 {
            margin: 0 0 10px 0;
            font-size: 18px;
        }
        
        .error-content pre {
            background-color: #000;
            color: #00ff00;
            padding: 15px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            overflow-x: auto;
            white-space: pre-wrap;
            margin: 10px 0 0 0;
        }
        
        .notice-content {
            display: flex;
            align-items: center;
            gap: 30px;
            flex-wrap: wrap;
        }
        
        .notice-icon {
            font-size: 48px;
            color: #856404;
        }
        
        .notice-text {
            flex: 1;
            min-width: 300px;
        }
        
        .notice-text h3 {
            color: #856404;
            margin-bottom: 10px;
            font-size: 24px;
        }
        
        .notice-text p {
            color: #856404;
            font-size: 16px;
        }
        
        .notice-text a {
            color: #2d5a27;
            text-decoration: none;
            font-weight: 600;
        }
        
        .notice-actions {
            display: flex;
            gap: 15px;
        }
        
        .products-section {
            padding: 60px 0;
        }
        
        .filter-bar {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .filter-left h2 {
            color: #2d5a27;
            font-size: 32px;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .filter-left h2 i {
            color: #4a7c43;
        }
        
        .product-count {
            color: #666;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 5px;
        }
        
        .search-info {
            color: #2d5a27;
            font-size: 14px;
            font-style: italic;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .suggestions {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: left;
        }
        
        .suggestions h4 {
            color: #495057;
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .suggestions ul {
            list-style: none;
            padding: 0;
        }
        
        .suggestions li {
            margin: 8px 0;
            padding: 8px 12px;
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
        }
        
        .suggestions code {
            background: #f1f3f4;
            padding: 2px 6px;
            border-radius: 3px;
            color: #d63384;
            font-weight: 600;
        }
        
        .filter-form {
            display: flex;
            gap: 20px;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .search-filter {
            display: flex;
            gap: 5px;
            align-items: center;
        }
        
        .search-filter input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 5px 0 0 5px;
            width: 200px;
        }
        
        .search-btn {
            padding: 8px 12px;
            background-color: #2d5a27;
            color: white;
            border: none;
            border-radius: 0 5px 5px 0;
            cursor: pointer;
        }
        
        .search-btn:hover {
            background-color: #1e3d1c;
        }
        
        .clear-search-btn {
            padding: 8px 12px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            margin-left: 5px;
        }
        
        .clear-search-btn:hover {
            background-color: #c82333;
            color: white;
        }
        
        .category-filter,
        .sort-filter {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .category-filter label,
        .sort-filter label {
            font-weight: 500;
            color: #333;
            white-space: nowrap;
        }
        
        .category-filter select,
        .sort-filter select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: white;
            min-width: 120px;
        }
        
        .no-products {
            text-align: center;
            padding: 80px 20px;
        }
        
        .no-products-content {
            max-width: 400px;
            margin: 0 auto;
        }
        
        .no-products-content i {
            color: #ccc;
            margin-bottom: 20px;
        }
        
        .no-products-content h3 {
            color: #666;
            margin-bottom: 15px;
        }
        
        .no-products-content p {
            color: #999;
            margin-bottom: 25px;
        }
        
        .data-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
        }
        
        .data-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
        }
        
        .data-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
            border-color: #2d5a27;
        }
        
        .data-header {
            background: linear-gradient(90deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #dee2e6;
        }
        
        .specimen-id {
            font-family: 'Courier New', monospace;
            font-weight: 600;
            color: #495057;
            font-size: 12px;
        }
        
        .data-status {
            color: #28a745;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .specimen-image {
            position: relative;
            overflow: hidden;
            height: 220px;
            background: #f8f9fa;
        }
        
        .specimen-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .data-card:hover .specimen-image img {
            transform: scale(1.05);
        }
        
        .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(45, 90, 39, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .data-card:hover .image-overlay {
            opacity: 1;
        }
        
        .specimen-data {
            padding: 20px;
        }
        
        .taxonomic-info {
            margin-bottom: 10px;
        }
        
        .family {
            background: #e8f5e8;
            color: #2d5a27;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .scientific-name {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin: 10px 0;
            line-height: 1.3;
        }
        
        .description {
            color: #666;
            font-size: 13px;
            line-height: 1.5;
            margin-bottom: 15px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .characteristics {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
        }
        
        .char-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
            font-size: 13px;
        }
        
        .char-item:last-child {
            margin-bottom: 0;
        }
        
        .char-label {
            font-weight: 600;
            color: #495057;
        }
        
        .char-value {
            color: #2d5a27;
            font-family: 'Courier New', monospace;
        }
        
        .data-actions {
            display: flex;
            gap: 10px;
        }
        
        .data-actions .btn {
            flex: 1;
            font-size: 12px;
            padding: 8px 12px;
        }
        
        .btn-info {
            background-color: #17a2b8;
            border-color: #17a2b8;
            color: white;
        }
        
        .btn-info:hover {
            background-color: #138496;
            color: white;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            margin-top: 40px;
            padding: 20px 0;
        }
        
        .pagination-btn {
            background-color: #2d5a27;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .pagination-btn:hover {
            background-color: #1e3d1c;
        }
        
        .pagination-info {
            color: #666;
            font-weight: 500;
        }
        
        .features-section {
            background-color: #f8f9fa;
            padding: 80px 0;
        }
        
        .features-section h2 {
            text-align: center;
            color: #2d5a27;
            font-size: 36px;
            margin-bottom: 50px;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
        }
        
        .feature-item {
            text-align: center;
            padding: 30px 20px;
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            background-color: #2d5a27;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 30px;
            margin: 0 auto 20px;
        }
        
        .feature-item h3 {
            color: #2d5a27;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .feature-item p {
            color: #666;
            line-height: 1.6;
        }
        
        @media (max-width: 768px) {
            .filter-bar {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .filter-form {
                flex-direction: column;
                width: 100%;
                gap: 15px;
            }
            
            .search-filter {
                width: 100%;
            }
            
            .search-filter input {
                flex: 1;
                width: auto;
            }
            
            .category-filter,
            .sort-filter {
                width: 100%;
                justify-content: space-between;
            }
            
            .category-filter select,
            .sort-filter select {
                min-width: 150px;
            }
            
            .notice-content {
                flex-direction: column;
                text-align: center;
            }
            
            .notice-actions {
                justify-content: center;
            }
            
            .page-hero h1 {
                font-size: 32px;
            }
            
            .product-actions {
                flex-direction: column;
            }
            
            .product-actions .btn {
                width: 100%;
            }
            
            .pagination {
                flex-direction: column;
                gap: 15px;
            }
        }
        
        @media (max-width: 480px) {
            .products-grid {
                grid-template-columns: 1fr;
            }
            
            .filter-form {
                gap: 10px;
            }
            
            .search-filter {
                flex-direction: column;
                gap: 10px;
            }
            
            .search-filter input,
            .search-btn,
            .clear-search-btn {
                border-radius: 5px;
                width: 100%;
            }
        }
    </style>

    <script>
        // Funcionalidad específica de base de datos de flores
        document.addEventListener('DOMContentLoaded', function() {
            
            // Botones de ver detalles técnicos
            const viewDetailsButtons = document.querySelectorAll('.view-details');
            viewDetailsButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const productId = this.dataset.productId;
                    showTechnicalDetails(productId);
                });
            });
            
            // Función para mostrar detalles técnicos
            function showTechnicalDetails(productId) {
                const modal = document.createElement('div');
                modal.className = 'modal-overlay';
                modal.innerHTML = `
                    <div class="modal-content technical-modal">
                        <div class="modal-header">
                            <h3><i class="fas fa-microscope"></i> Technical Analysis - ID: ${productId}</h3>
                            <button class="modal-close">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="loading">Querying database...</div>
                        </div>
                    </div>
                `;
                
                document.body.appendChild(modal);
                
                // Simular carga de datos técnicos
                setTimeout(() => {
                    modal.querySelector('.modal-body').innerHTML = `
                        <div class="technical-data">
                            <div class="data-section">
                                <h4><i class="fas fa-database"></i> Database Information</h4>
                                <div class="tech-grid">
                                    <div class="tech-item">
                                        <span class="tech-label">Record ID:</span>
                                        <span class="tech-value">${productId}</span>
                                    </div>
                                    <div class="tech-item">
                                        <span class="tech-label">Source Table:</span>
                                        <span class="tech-value">products</span>
                                    </div>
                                    <div class="tech-item">
                                        <span class="tech-label">Schema:</span>
                                        <span class="tech-value">floral_database</span>
                                    </div>
                                    <div class="tech-item">
                                        <span class="tech-label">Last Updated:</span>
                                        <span class="tech-value">${new Date().toLocaleDateString()}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="data-section">
                                <h4><i class="fas fa-code"></i> SQL Query Used</h4>
                                <pre class="sql-query">SELECT id, name, slug, description, short_description, 
price, wholesale_price, min_quantity, min_quantity_unit, 
stock_quantity, sku, image_url, featured, color, material, 
'category' as category_name, 'slug' as category_slug 
FROM products 
WHERE id = ${productId}</pre>
                            </div>
                            
                            <div class="data-section">
                                <h4><i class="fas fa-code"></i> JSON Data</h4>
                                <pre class="json-data">{
  "id": ${productId},
  "name": "Rosa Damascena Premium",
  "slug": "rosa-damascena-premium",
  "description": "High-quality specimen with exceptional botanical characteristics",
  "short_description": "Premium collection rose",
  "price": 45.99,
  "wholesale_price": 32.00,
  "min_quantity": 12,
  "min_quantity_unit": "pieces",
  "stock_quantity": 150,
  "sku": "RDP-2024-${productId}",
  "image_url": "https://example.com/images/rosa-${productId}.jpg",
  "featured": true,
  "color": "Crimson Red",
  "material": "Premium Silk",
  "category_name": "Rosaceae",
  "category_slug": "rosaceae",
  "metadata": {
    "classification": "Scientific",
    "verified": true,
    "source": "Database Query",
    "last_updated": "${new Date().toISOString()}"
  }
}</pre>
                                <button class="btn btn-primary copy-json">
                                    <i class="fas fa-copy"></i> Copy JSON
                                </button>
                            </div>
                            
                            <div class="data-section">
                                <h4><i class="fas fa-chart-line"></i> Statistics</h4>
                                <div class="stats-grid">
                                    <div class="stat-box">
                                        <span class="stat-number">1</span>
                                        <span class="stat-label">Record Queried</span>
                                    </div>
                                    <div class="stat-box">
                                        <span class="stat-number">14</span>
                                        <span class="stat-label">Available Fields</span>
                                    </div>
                                    <div class="stat-box">
                                        <span class="stat-number">0.003s</span>
                                        <span class="stat-label">Query Time</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="data-section">
                                <h4><i class="fas fa-download"></i> Export Options</h4>
                                <div class="export-options">
                                    <button class="btn btn-secondary export-csv" data-product-id="${productId}">
                                        <i class="fas fa-file-csv"></i> Export CSV
                                    </button>
                                    <button class="btn btn-secondary export-json" data-product-id="${productId}">
                                        <i class="fas fa-file-code"></i> Export JSON
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                }, 1000);
                
                // Add event listeners for the new buttons
                setTimeout(() => {
                    // Copy JSON functionality
                    const copyJsonBtn = modal.querySelector('.copy-json');
                    if (copyJsonBtn) {
                        copyJsonBtn.addEventListener('click', function() {
                            const jsonText = modal.querySelector('.json-data').textContent;
                            navigator.clipboard.writeText(jsonText).then(() => {
                                showNotification('JSON copied to clipboard', 'success');
                            });
                        });
                    }
                    
                    // Export CSV functionality
                    const exportCsvBtn = modal.querySelector('.export-csv');
                    if (exportCsvBtn) {
                        exportCsvBtn.addEventListener('click', function() {
                            exportData(productId, 'csv');
                        });
                    }
                    
                    // Export JSON functionality
                    const exportJsonBtn = modal.querySelector('.export-json');
                    if (exportJsonBtn) {
                        exportJsonBtn.addEventListener('click', function() {
                            exportData(productId, 'json');
                        });
                    }
                }, 1200);
                
                setupModalClose(modal);
            }
            
            // Función para exportar datos
            function exportData(productId, format = 'csv') {
                showNotification(`Exporting data for record ID: ${productId} as ${format.toUpperCase()}`, 'info');
                
                // Simulate export
                setTimeout(() => {
                    let data, blob, filename;
                    
                    if (format === 'csv') {
                        data = `ID,Name,Description,Color,Material,Stock
${productId},"Rosa Damascena Premium","High-quality specimen with exceptional botanical characteristics","Crimson Red","Premium Silk",150`;
                        blob = new Blob([data], { type: 'text/csv' });
                        filename = `floral_data_${productId}.csv`;
                    } else if (format === 'json') {
                        data = JSON.stringify({
                            id: productId,
                            name: "Rosa Damascena Premium",
                            description: "High-quality specimen with exceptional botanical characteristics",
                            color: "Crimson Red",
                            material: "Premium Silk",
                            stock: 150,
                            exported_at: new Date().toISOString()
                        }, null, 2);
                        blob = new Blob([data], { type: 'application/json' });
                        filename = `floral_data_${productId}.json`;
                    }
                    
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = filename;
                    a.click();
                    window.URL.revokeObjectURL(url);
                    
                    showNotification(`${format.toUpperCase()} file downloaded successfully`, 'success');
                }, 1500);
            }
            
            // Función para configurar cierre de modal
            function setupModalClose(modal) {
                modal.querySelector('.modal-close').addEventListener('click', function() {
                    document.body.removeChild(modal);
                });
                
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        document.body.removeChild(modal);
                    }
                });
            }
            
            // Función para mostrar notificaciones
            function showNotification(message, type = 'info') {
                const notification = document.createElement('div');
                notification.className = `notification notification-${type}`;
                notification.innerHTML = `
                    <div class="notification-content">
                        <span>${message}</span>
                        <button class="notification-close">&times;</button>
                    </div>
                `;
                
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    if (document.body.contains(notification)) {
                        document.body.removeChild(notification);
                    }
                }, 5000);
                
                notification.querySelector('.notification-close').addEventListener('click', function() {
                    document.body.removeChild(notification);
                });
            }
        });
    </script>
    
    <style>
        /* Estilos para los modales técnicos */
        .technical-modal, .json-modal {
            max-width: 900px;
        }
        
        .technical-data {
            padding: 0;
        }
        
        .data-section {
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .data-section:last-child {
            border-bottom: none;
        }
        
        .data-section h4 {
            color: #2d5a27;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .tech-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .tech-item {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 6px;
        }
        
        .tech-label {
            font-weight: 600;
            color: #495057;
        }
        
        .tech-value {
            color: #2d5a27;
            font-family: 'Courier New', monospace;
        }
        
        .sql-query {
            background: #1e1e1e;
            color: #d4d4d4;
            padding: 20px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.5;
            overflow-x: auto;
            border: 1px solid #333;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }
        
        .stat-box {
            text-align: center;
            padding: 20px;
            background: linear-gradient(135deg, #e8f5e8 0%, #d4edda 100%);
            border-radius: 8px;
            border: 1px solid #c3e6cb;
        }
        
        .stat-box .stat-number {
            display: block;
            font-size: 24px;
            font-weight: 700;
            color: #2d5a27;
            margin-bottom: 5px;
        }
        
        .stat-box .stat-label {
            font-size: 12px;
            color: #495057;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .json-container {
            position: relative;
        }
        
        .json-data {
            background: #2d3748;
            color: #e2e8f0;
            padding: 20px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.6;
            overflow-x: auto;
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #4a5568;
            margin-bottom: 15px;
        }
        
        .copy-json {
            position: sticky;
            bottom: 0;
            width: 100%;
        }
        
        .export-options {
            display: flex;
            gap: 15px;
            justify-content: center;
        }
        
        .export-options .btn {
            flex: 1;
            max-width: 200px;
        }
        
        @media (max-width: 768px) {
            .hero-stats {
                flex-direction: column;
                gap: 20px;
            }
            
            .data-grid {
                grid-template-columns: 1fr;
            }
            
            .tech-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .technical-modal, .json-modal {
                max-width: 95vw;
                margin: 10px;
            }
        }
        
        /* Estilos para notificaciones */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 10001;
            border-radius: 5px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            min-width: 300px;
        }
        
        .notification-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .notification-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .notification-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        
        .notification-content {
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .notification-close {
            background: none;
            border: none;
            font-size: 18px;
            cursor: pointer;
            opacity: 0.7;
        }
        
        .notification-close:hover {
            opacity: 1;
        }
        
        @media (max-width: 768px) {
            .product-quick-view {
                grid-template-columns: 1fr;
            }
            
            .modal-content {
                margin: 10px;
                max-height: calc(100vh - 20px);
            }
            
            .notification {
                right: 10px;
                left: 10px;
                min-width: auto;
            }
        }
    </style>
</body>
</html>