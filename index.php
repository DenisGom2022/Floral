<?php
require_once 'includes/database.php';

// Get all flowers
$productManager = new ProductManager();
$flowers = $productManager->getProductsWithCategory();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flower Collection - Educational Database</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
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
                            <li><a href="index.php" class="active">Catalog</a></li>
                            <li><a href="about.php">About</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-background">
            <div class="container">
                <div class="hero-content">
                    <div class="hero-text">
                        <h2 class="hero-title">Discover the Beauty of Nature</h2>
                        <p class="hero-subtitle">Explore our educational collection of flowers and learn about different species from around the world</p>
                        <div class="hero-stats">
                            <div class="stat-item">
                                <span class="stat-number"><?php echo count($flowers); ?></span>
                                <span class="stat-label">Flower Species</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">Educational</span>
                                <span class="stat-label">Purpose</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">Free</span>
                                <span class="stat-label">Access</span>
                            </div>
                        </div>
                    </div>
                    <div class="hero-visual">
                        <div class="floating-elements">
                            <div class="floating-flower flower-1"><i class="fas fa-flower"></i></div>
                            <div class="floating-flower flower-2"><i class="fas fa-leaf"></i></div>
                            <div class="floating-flower flower-3"><i class="fas fa-seedling"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Search and Filter Section -->
            <div class="search-section">
                <div class="search-header">
                    <h2 class="section-title">Flower Catalog</h2>
                    <p class="section-description">Explore our educational collection of flowers to learn about different species</p>
                </div>
                
                <!-- Quick Stats -->
                <div class="quick-stats">
                    <div class="stat-card">
                        <i class="fas fa-seedling"></i>
                        <div class="stat-info">
                            <span class="stat-count"><?php echo count($flowers); ?></span>
                            <span class="stat-text">Total Species</span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-palette"></i>
                        <div class="stat-info">
                            <span class="stat-count">Multiple</span>
                            <span class="stat-text">Colors Available</span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-graduation-cap"></i>
                        <div class="stat-info">
                            <span class="stat-count">Educational</span>
                            <span class="stat-text">Purpose</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Flowers Grid -->
            <div class="flowers-grid">
                <?php if (empty($flowers)): ?>
                    <div class="no-results">
                        <i class="fas fa-seedling"></i>
                        <h3>No flowers available</h3>
                        <p>There are currently no products in the database.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($flowers as $flower): ?>
                        <div class="flower-card">
                            <div class="flower-image">
                                <?php if (!empty($flower['image_url'])): ?>
                                    <img src="<?php echo htmlspecialchars($flower['image_url']); ?>" 
                                         alt="<?php echo htmlspecialchars($flower['name']); ?>"
                                         loading="lazy">
                                    <div class="image-overlay">
                                        <i class="fas fa-search-plus"></i>
                                    </div>
                                <?php else: ?>
                                    <div class="no-image">
                                        <i class="fas fa-flower"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="flower-content">
                                <h3 class="flower-name"><?php echo htmlspecialchars($flower['name']); ?></h3>
                                
                                <?php if (!empty($flower['category_name'])): ?>
                                    <span class="flower-category">
                                        <i class="fas fa-tag"></i>
                                        <?php echo htmlspecialchars($flower['category_name']); ?>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if (!empty($flower['description'])): ?>
                                    <p class="flower-description">
                                        <?php 
                                        $description = htmlspecialchars($flower['description']);
                                        echo strlen($description) > 100 ? substr($description, 0, 100) . '...' : $description;
                                        ?>
                                    </p>
                                <?php endif; ?>
                                
                                <div class="flower-specs">
                                    <?php if (!empty($flower['color'])): ?>
                                        <span class="spec">
                                            <i class="fas fa-palette"></i>
                                            <?php echo htmlspecialchars($flower['color']); ?>
                                        </span>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($flower['material'])): ?>
                                        <span class="spec">
                                            <i class="fas fa-leaf"></i>
                                            <?php echo htmlspecialchars($flower['material']); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <a href="flower-detail.php?id=<?php echo $flower['id']; ?>" class="btn btn-detail">
                                    <i class="fas fa-eye"></i>
                                    View Details
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
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

    <script src="js/script.js"></script>
</body>
</html>