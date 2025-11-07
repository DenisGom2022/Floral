<?php
session_start();

// Add error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get product ID from URL
$productId = $_GET['id'] ?? null;

if (!$productId || !is_numeric($productId)) {
    header('Location: floral.php');
    exit;
}

// Default values
$product = null;
$errorMessage = '';

try {
    require_once 'includes/database.php';
    
    // Initialize database managers
    $productManager = new ProductManager();
    $categoryManager = new CategoryManager();
    
    // Get product details - try multiple methods
    try {
        // First try the getProductById method
        $product = $productManager->getProductById($productId);
        
        // If that doesn't work, try the vulnerable search method
        if (!$product) {
            $products = $productManager->searchProducts("id = " . intval($productId), 1);
            $product = !empty($products) ? $products[0] : null;
        }
        
        // If still no product, try direct SQL query
        if (!$product) {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("SELECT p.*, c.name as category_name, c.slug as category_slug FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = ?");
            $stmt->execute([$productId]);
            $product = $stmt->fetch();
        }
        
    } catch (Exception $e) {
        $errorMessage = "Error loading product: " . $e->getMessage();
    }
    
    
} catch (Exception $e) {
    $errorMessage = "Database connection error: " . $e->getMessage();
    
    // Create a sample product for testing if database fails
    $product = [
        'id' => $productId,
        'name' => 'Sample Product #' . $productId,
        'description' => 'This is a sample product because the database is not connected. Please run setup-database.php first.',
        'short_description' => 'Sample product',
        'price' => 45.99,
        'wholesale_price' => 32.00,
        'stock_quantity' => 150,
        'sku' => 'SAMPLE-' . str_pad($productId, 3, '0', STR_PAD_LEFT),
        'image_url' => 'https://via.placeholder.com/600x600/e8f5e8/2d5a27?text=Sample+Product+' . $productId,
        'color' => 'Sample Color',
        'material' => 'Sample Material',
        'category_name' => 'Sample Category'
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product ? htmlspecialchars($product['name']) : 'Product Detail'; ?> - USI Floral Imports</title>
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
                    <span><i class="fas fa-leaf"></i> Premium Botanical Collection • Quality Silk Flowers • Professional Grade</span>
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
                        <i class="fas fa-search search-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Breadcrumb -->
    <section class="breadcrumb-section">
        <div class="container">
            <nav class="breadcrumb">
                <a href="index.php"><i class="fas fa-home"></i> Home</a>
                <span class="separator">/</span>
                <a href="floral.php"><i class="fas fa-database"></i> Flower Database</a>
                <span class="separator">/</span>
                <span class="current"><?php echo $product ? htmlspecialchars($product['name']) : 'Product Detail'; ?></span>
            </nav>
        </div>
    </section>

    <?php if (!empty($errorMessage)): ?>
    <section class="error-notice">
        <div class="container">
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i>
                <div class="error-content">
                    <h4>Error Loading Product</h4>
                    <pre><?php echo htmlspecialchars($errorMessage); ?></pre>
                    <p><strong>Debug Info:</strong> Looking for product ID: <?php echo htmlspecialchars($productId); ?></p>
                    <?php if (strpos($errorMessage, 'Database connection error') !== false): ?>
                        <p><strong>Quick Fix:</strong> <a href="setup-database.php" style="color: #fff; text-decoration: underline;">Run Database Setup</a></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php if ($product): ?>
    <!-- Product Detail Section -->
    <section class="product-detail-section">
        <div class="container">
            <div class="product-detail-grid">
                <!-- Product Image -->
                <div class="product-image-section">
                    <div class="main-image">
                        <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>"
                             onerror="this.src='https://via.placeholder.com/600x600/e8f5e8/2d5a27?text=🌸+<?php echo urlencode($product['name']); ?>'">
                        <div class="image-badge">
                            <i class="fas fa-check-circle"></i> Verified Specimen
                        </div>
                    </div>
                    
                    <div class="image-gallery">
                        <div class="gallery-item active">
                            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="Main view">
                        </div>
                        <div class="gallery-item">
                            <img src="https://via.placeholder.com/150x150/f0f8f0/2d5a27?text=Detail+1" alt="Detail view 1">
                        </div>
                        <div class="gallery-item">
                            <img src="https://via.placeholder.com/150x150/f0f8f0/2d5a27?text=Detail+2" alt="Detail view 2">
                        </div>
                        <div class="gallery-item">
                            <img src="https://via.placeholder.com/150x150/f0f8f0/2d5a27?text=Detail+3" alt="Detail view 3">
                        </div>
                    </div>
                </div>

                <!-- Product Information -->
                <div class="product-info-section">
                    <div class="product-header">
                        <div class="specimen-id">
                            <i class="fas fa-barcode"></i> Specimen ID: <?php echo $product['id']; ?>
                        </div>
                        <div class="verification-status">
                            <i class="fas fa-shield-check"></i> Database Verified
                        </div>
                    </div>

                    <div class="taxonomic-classification">
                        <span class="family-tag"><?php echo htmlspecialchars($product['category_name']); ?></span>
                    </div>

                    <h1 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h1>
                    
                    <div class="product-description">
                        <p><?php echo htmlspecialchars($product['description'] ?? $product['short_description']); ?></p>
                    </div>

                    <!-- Technical Specifications -->
                    <div class="technical-specs">
                        <h3><i class="fas fa-microscope"></i> Technical Specifications</h3>
                        <div class="specs-grid">
                            <?php if (isset($product['color']) && $product['color']): ?>
                            <div class="spec-item">
                                <span class="spec-label">Color Variation:</span>
                                <span class="spec-value"><?php echo htmlspecialchars($product['color']); ?></span>
                            </div>
                            <?php endif; ?>
                            <?php if (isset($product['material']) && $product['material']): ?>
                            <div class="spec-item">
                                <span class="spec-label">Material Composition:</span>
                                <span class="spec-value"><?php echo htmlspecialchars($product['material']); ?></span>
                            </div>
                            <?php endif; ?>
                            <?php if (isset($product['dimensions']) && $product['dimensions']): ?>
                            <div class="spec-item">
                                <span class="spec-label">Dimensions:</span>
                                <span class="spec-value"><?php echo htmlspecialchars($product['dimensions']); ?></span>
                            </div>
                            <?php endif; ?>
                            <?php if (isset($product['weight']) && $product['weight']): ?>
                            <div class="spec-item">
                                <span class="spec-label">Weight:</span>
                                <span class="spec-value"><?php echo htmlspecialchars($product['weight']); ?> g</span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php else: ?>
    <!-- Product Not Found -->
    <section class="product-not-found">
        <div class="container">
            <div class="not-found-content">
                <i class="fas fa-search fa-4x"></i>
                <h2>Product Not Found</h2>
                <p>The requested product (ID: <?php echo htmlspecialchars($productId); ?>) could not be found in our database.</p>
                
                <div class="debug-info">
                    <h4>Debug Information:</h4>
                    <ul>
                        <li>Product ID requested: <?php echo htmlspecialchars($productId); ?></li>
                        <li>Is numeric: <?php echo is_numeric($productId) ? 'Yes' : 'No'; ?></li>
                        <li>Database connected: <?php echo empty($errorMessage) ? 'Yes' : 'No'; ?></li>
                    </ul>
                </div>
                
                <div class="suggested-actions">
                    <a href="floral.php" class="btn btn-primary">
                        <i class="fas fa-arrow-left"></i> Back to Flower Database
                    </a>
                    <a href="setup-database.php" class="btn btn-secondary">
                        <i class="fas fa-tools"></i> Setup Database
                    </a>
                    <a href="debug-products.php" class="btn btn-info">
                        <i class="fas fa-bug"></i> Debug Products
                    </a>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>Floral Collection</h4>
                    <p>Explore our comprehensive database of premium silk botanicals and decorative flowers. Discover detailed information about various flower species, materials, and design specifications.</p>
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
                <p>&copy; 2025, Floral Database Collection</p>
                <p>Premium botanical information database for reference and educational purposes.</p>
            </div>
        </div>
    </footer>

    <style>
        .breadcrumb-section {
            background-color: #f8f9fa;
            padding: 15px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
        }

        .breadcrumb a {
            color: #2d5a27;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        .breadcrumb .separator {
            color: #6c757d;
        }

        .breadcrumb .current {
            color: #495057;
            font-weight: 500;
        }

        .product-detail-section {
            padding: 60px 0;
        }

        .product-detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: start;
        }

        .product-image-section {
            position: sticky;
            top: 20px;
        }

        .main-image {
            position: relative;
            background: #f8f9fa;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 20px;
            border: 1px solid #e9ecef;
        }

        .main-image img {
            width: 100%;
            height: 500px;
            object-fit: cover;
        }

        .image-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(40, 167, 69, 0.9);
            color: white;
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .image-gallery {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }

        .gallery-item {
            aspect-ratio: 1;
            border-radius: 8px;
            overflow: hidden;
            border: 2px solid #e9ecef;
            cursor: pointer;
            transition: border-color 0.3s ease;
        }

        .gallery-item.active {
            border-color: #2d5a27;
        }

        .gallery-item:hover {
            border-color: #4a7c43;
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-info-section {
            padding: 20px 0;
        }

        .product-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .specimen-id {
            font-family: 'Courier New', monospace;
            color: #495057;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .verification-status {
            color: #28a745;
            font-weight: 600;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .taxonomic-classification {
            margin-bottom: 15px;
        }

        .family-tag {
            background: #e8f5e8;
            color: #2d5a27;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .product-title {
            font-size: 36px;
            font-weight: 700;
            color: #333;
            margin: 20px 0;
            line-height: 1.2;
        }

        .product-description {
            font-size: 16px;
            line-height: 1.6;
            color: #666;
            margin-bottom: 30px;
        }

        .technical-specs,
        .pricing-section,
        .data-export-section {
            margin-bottom: 30px;
            padding: 25px;
            background: #f8f9fa;
            border-radius: 12px;
            border: 1px solid #e9ecef;
        }

        .technical-specs h3,
        .data-export-section h3 {
            color: #2d5a27;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 18px;
        }

        .specs-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .spec-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 15px;
            background: white;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }

        .spec-label {
            font-weight: 600;
            color: #495057;
        }

        .spec-value {
            color: #2d5a27;
            font-family: 'Courier New', monospace;
            font-weight: 600;
        }

        .export-buttons {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }

        .btn-outline {
            background: white;
            border: 2px solid #2d5a27;
            color: #2d5a27;
            padding: 12px 20px;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-outline:hover {
            background: #2d5a27;
            color: white;
        }

        .product-not-found {
            padding: 100px 0;
            text-align: center;
        }

        .not-found-content {
            max-width: 500px;
            margin: 0 auto;
        }

        .not-found-content i {
            color: #ccc;
            margin-bottom: 30px;
        }

        .not-found-content h2 {
            color: #666;
            margin-bottom: 15px;
        }

        .not-found-content p {
            color: #999;
            margin-bottom: 30px;
        }

        .debug-info {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: left;
        }

        .debug-info h4 {
            color: #495057;
            margin-bottom: 10px;
        }

        .debug-info ul {
            list-style-type: none;
            padding: 0;
        }

        .debug-info li {
            padding: 5px 0;
            border-bottom: 1px solid #e9ecef;
            font-family: 'Courier New', monospace;
            font-size: 14px;
        }

        .debug-info li:last-child {
            border-bottom: none;
        }

        .suggested-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
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

        @media (max-width: 768px) {
            .product-detail-grid {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .product-image-section {
                position: static;
            }

            .product-actions {
                grid-template-columns: 1fr;
            }

            .specs-grid,
            .price-grid {
                grid-template-columns: 1fr;
            }

            .export-buttons {
                grid-template-columns: 1fr;
            }

            .db-info-grid {
                grid-template-columns: 1fr;
            }

            .stats-mini-grid {
                grid-template-columns: 1fr;
            }

            .product-title {
                font-size: 28px;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gallery functionality
            const galleryItems = document.querySelectorAll('.gallery-item');
            const mainImage = document.querySelector('.main-image img');

            galleryItems.forEach(item => {
                item.addEventListener('click', function() {
                    const img = this.querySelector('img');
                    mainImage.src = img.src;
                    
                    galleryItems.forEach(i => i.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        });
    </script>
</body>
</html>