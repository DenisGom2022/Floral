<?php
// Get flower ID from URL
$flowerId = $_GET['id'] ?? null;

if (!$flowerId || !is_numeric($flowerId)) {
    header('Location: index.php');
    exit;
}

// Default values
$flower = null;
$errorMessage = '';

try {
    require_once 'includes/database.php';
    
    // Initialize database managers
    $productManager = new ProductManager();
    
    // Get flower details
    try {
        $flower = $productManager->getProductById($flowerId);
        
        // If that doesn't work, try the vulnerable search method
        if (!$flower) {
            $products = $productManager->searchProducts("id = " . intval($flowerId), 1);
            $flower = !empty($products) ? $products[0] : null;
        }
        
    } catch (Exception $e) {
        $errorMessage = "Error loading flower: " . $e->getMessage();
    }
    
} catch (Exception $e) {
    $errorMessage = "Database connection error: " . $e->getMessage();
}

// If no flower found, redirect to catalog
if (!$flower) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($flower['name']); ?> - Flower Collection</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-main">
            <div class="container">
                <div class="header-content">
                    <div class="logo">
                        <h1><i class="fas fa-seedling"></i> Botanical Collection</h1>
                        <p class="subtitle">Educational Flower Database</p>
                    </div>
                    
                    <nav class="main-nav">
                        <ul>
                            <li><a href="index.php">Catalog</a></li>
                            <li><a href="about.php">About</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Breadcrumb -->
            <nav class="breadcrumb">
                <a href="index.php"><i class="fas fa-home"></i> Catalog</a>
                <span class="separator">></span>
                <span class="current"><?php echo htmlspecialchars($flower['name']); ?></span>
            </nav>

            <div class="flower-detail">
                <div class="flower-detail-grid">
                    <!-- Image Gallery -->
                    <div class="flower-gallery">
                        <?php if (!empty($flower['image_url'])): ?>
                            <div class="main-image">
                                <img src="<?php echo htmlspecialchars($flower['image_url']); ?>" 
                                     alt="<?php echo htmlspecialchars($flower['name']); ?>"
                                     id="mainImage">
                            </div>
                            
                            <?php 
                            // Check for additional images (assuming they might be in gallery_images field or similar)
                            $additionalImages = [];
                            if (!empty($flower['gallery_images'])) {
                                $additionalImages = json_decode($flower['gallery_images'], true) ?: [];
                            }
                            ?>
                            
                            <?php if (!empty($additionalImages)): ?>
                                <div class="thumbnail-gallery">
                                    <img src="<?php echo htmlspecialchars($flower['image_url']); ?>" 
                                         alt="<?php echo htmlspecialchars($flower['name']); ?>"
                                         class="thumbnail active"
                                         onclick="changeMainImage(this.src)">
                                    <?php foreach ($additionalImages as $image): ?>
                                        <img src="<?php echo htmlspecialchars($image); ?>" 
                                             alt="<?php echo htmlspecialchars($flower['name']); ?>"
                                             class="thumbnail"
                                             onclick="changeMainImage(this.src)">
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="no-image-large">
                                <i class="fas fa-flower"></i>
                                <p>No image available</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Flower Information -->
                    <div class="flower-info">
                        <div class="flower-header">
                            <h1 class="flower-title"><?php echo htmlspecialchars($flower['name']); ?></h1>
                            
                            <?php if (!empty($flower['category_name'])): ?>
                                <span class="category-badge">
                                    <i class="fas fa-tag"></i>
                                    <?php echo htmlspecialchars($flower['category_name']); ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($flower['description'])): ?>
                            <div class="flower-description">
                                <h3><i class="fas fa-info-circle"></i> Description</h3>
                                <p><?php echo nl2br(htmlspecialchars($flower['description'])); ?></p>
                            </div>
                        <?php endif; ?>

                        <!-- Technical Specifications -->
                        <div class="technical-specs">
                            <h3><i class="fas fa-clipboard-list"></i> Specifications</h3>
                            <div class="specs-grid">
                                <?php if (!empty($flower['color'])): ?>
                                    <div class="spec-item">
                                        <span class="spec-label">
                                            <i class="fas fa-palette"></i>
                                            Color:
                                        </span>
                                        <span class="spec-value"><?php echo htmlspecialchars($flower['color']); ?></span>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($flower['material'])): ?>
                                    <div class="spec-item">
                                        <span class="spec-label">
                                            <i class="fas fa-leaf"></i>
                                            Material:
                                        </span>
                                        <span class="spec-value"><?php echo htmlspecialchars($flower['material']); ?></span>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($flower['dimensions'])): ?>
                                    <div class="spec-item">
                                        <span class="spec-label">
                                            <i class="fas fa-ruler"></i>
                                            Dimensions:
                                        </span>
                                        <span class="spec-value"><?php echo htmlspecialchars($flower['dimensions']); ?></span>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($flower['origin'])): ?>
                                    <div class="spec-item">
                                        <span class="spec-label">
                                            <i class="fas fa-globe"></i>
                                            Origin:
                                        </span>
                                        <span class="spec-value"><?php echo htmlspecialchars($flower['origin']); ?></span>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($flower['season'])): ?>
                                    <div class="spec-item">
                                        <span class="spec-label">
                                            <i class="fas fa-calendar"></i>
                                            Season:
                                        </span>
                                        <span class="spec-value"><?php echo htmlspecialchars($flower['season']); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Navigation Actions -->
                        <div class="flower-actions">
                            <a href="index.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i>
                                Back to Catalog
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4><i class="fas fa-seedling"></i> Botanical Collection</h4>
                    <p>Educational database developed for learning purposes about different flower species and plants.</p>
                </div>
                
                <div class="footer-section">
                    <h4>Navigation</h4>
                    <ul>
                        <li><a href="index.php">Catalog</a></li>
                        <li><a href="about.php">About</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Purpose</h4>
                    <p>This application was created for educational purposes to study and learn about flowers and botany.</p>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2025 - Educational Flower Application</p>
                <p>Developed with love for flowers and learning 🌸</p>
            </div>
        </div>
    </footer>

    <script>
        // Function to change main image when clicking thumbnails
        function changeMainImage(src) {
            document.getElementById('mainImage').src = src;
            
            // Update active thumbnail
            document.querySelectorAll('.thumbnail').forEach(thumb => {
                thumb.classList.remove('active');
            });
            event.target.classList.add('active');
        }
    </script>
</body>
</html>