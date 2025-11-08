<?php
require_once 'includes/database.php';

// Handle search
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$productManager = new ProductManager();

// If search term is provided, use the vulnerable search method
if (!empty($searchTerm)) {
    try {
        $flowers = $productManager->searchProducts($searchTerm);
    } catch (Exception $e) {
        // Display the error for educational purposes
        $searchError = $e->getMessage();
        $flowers = [];
    }
} else {
    // Get all flowers
    $flowers = $productManager->getProductsWithCategory();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flower Collection - Educational Database</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
/* Reset y Base */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', sans-serif;
    line-height: 1.6;
    color: #333;
    background-color: #fff;
    overflow-x: hidden;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Utility Classes */
.text-center { text-align: center; }
.mb-20 { margin-bottom: 20px; }
.mb-40 { margin-bottom: 40px; }
.mt-20 { margin-top: 20px; }
.mt-40 { margin-top: 40px; }

/* Botones */
.btn {
    display: inline-block;
    padding: 12px 24px;
    text-decoration: none;
    border-radius: 6px;
    font-weight: 500;
    border: none;
    cursor: pointer;
    text-align: center;
}

.btn-primary {
    background-color: #2d5a27;
    color: white;
}

.btn-primary:hover {
    background-color: #1e3d1c;
}

.btn-secondary {
    background-color: #f8f9fa;
    color: #2d5a27;
    border: 1px solid #2d5a27;
}

.btn-secondary:hover {
    background-color: #2d5a27;
    color: white;
}

.btn-login {
    background-color: transparent;
    color: #2d5a27;
    border: 1px solid #2d5a27;
    padding: 8px 16px;
}

.btn-login:hover {
    background-color: #2d5a27;
    color: white;
}

/* Header Enhanced */
.header {
    position: sticky;
    top: 0;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    z-index: 1000;
    border-bottom: 1px solid rgba(45, 90, 39, 0.1);
}

.header-main {
    padding: 20px 0;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo h1 {
    color: #2d5a27;
    font-size: 1.8rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0;
}

.logo h1 i {
    font-size: 2rem;
    color: #4a7c59;
}

.logo .subtitle {
    font-size: 0.9rem;
    color: #666;
    margin-top: 2px;
    font-weight: 400;
}

.main-nav ul {
    display: flex;
    list-style: none;
    gap: 30px;
    margin: 0;
    padding: 0;
}

.main-nav a {
    text-decoration: none;
    color: #555;
    font-weight: 500;
    font-size: 1.1rem;
    padding: 10px 20px;
    border-radius: 25px;
    position: relative;
}

.main-nav a:hover {
    color: #2d5a27;
}

.main-nav a.active {
    color: white;
    background: linear-gradient(135deg, #2d5a27, #4a7c59);
    font-weight: 600;
}

.main-nav a.active::before {
    content: '';
    position: absolute;
    top: -5px;
    left: 50%;
    width: 6px;
    height: 6px;
    background: #4a7c59;
    border-radius: 50%;
}

/* Hero Section Enhanced */
.hero-section {
    background: linear-gradient(135deg, #2d5a27 0%, #1e3d1c 100%);
    color: white;
    padding: 80px 0;
    position: relative;
    overflow: hidden;
    min-height: 60vh;
}

.hero-background {
    position: relative;
    width: 100%;
    height: 100%;
}

.hero-background::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: radial-gradient(circle at 20% 50%, rgba(255,255,255,0.1) 2px, transparent 2px),
                      radial-gradient(circle at 80% 50%, rgba(255,255,255,0.1) 2px, transparent 2px),
                      radial-gradient(circle at 40% 20%, rgba(255,255,255,0.1) 2px, transparent 2px);
    background-size: 50px 50px, 60px 60px, 40px 40px;
    opacity: 0.3;
}

/* Keyframes removed - animations disabled */

.hero-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: center;
    position: relative;
    z-index: 2;
}

/* Hover effects removed - no movement animations */

.hero-title {
    font-family: 'Playfair Display', serif;
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 20px;
    line-height: 1.2;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.hero-subtitle {
    font-size: 1.3rem;
    margin-bottom: 40px;
    opacity: 0.9;
    line-height: 1.6;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
}

.hero-stats {
    display: flex;
    gap: 40px;
    margin-top: 30px;
}

.stat-item {
    text-align: center;
    background: rgba(255,255,255,0.1);
    padding: 20px;
    border-radius: 15px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
}

/* Hover effects removed - no movement animations */

.stat-number {
    display: block;
    font-size: 2rem;
    font-weight: 700;
    color: #a8d5a8;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.8;
}

.hero-visual {
    position: relative;
    height: 400px;
}

.floating-elements {
    position: relative;
    width: 100%;
    height: 100%;
}

.floating-flower {
    position: absolute;
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: #a8d5a8;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
}

.flower-1 {
    top: 20%;
    left: 20%;
}

.flower-2 {
    top: 60%;
    left: 60%;
}

.flower-3 {
    top: 40%;
    left: 80%;
}

/* All keyframes removed - animations disabled */

/* Search Section Enhanced */
.search-section {
    padding: 80px 0 60px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    position: relative;
}

.search-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: 
        radial-gradient(circle at 25% 25%, rgba(45, 90, 39, 0.05) 0%, transparent 50%),
        radial-gradient(circle at 75% 75%, rgba(45, 90, 39, 0.05) 0%, transparent 50%);
    pointer-events: none;
}

.search-header {
    text-align: center;
    margin-bottom: 50px;
    position: relative;
    z-index: 2;
}

.section-title {
    font-family: 'Playfair Display', serif;
    font-size: 2.8rem;
    color: #2d5a27;
    margin-bottom: 20px;
    position: relative;
    display: inline-block;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 3px;
    background: linear-gradient(90deg, #2d5a27, #4a7c59);
    border-radius: 2px;
}

.section-description {
    font-size: 1.2rem;
    color: #555;
    max-width: 700px;
    margin: 0 auto;
    line-height: 1.7;
}

.quick-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 35px;
    margin-bottom: 40px;
    position: relative;
    z-index: 2;
}

.stat-card {
    background: white;
    padding: 35px 30px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    gap: 25px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    position: relative;
    border: 1px solid rgba(45, 90, 39, 0.1);
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: linear-gradient(180deg, #2d5a27, #4a7c59);
    transition: width 0.3s ease;
}

.stat-card:hover::before {
    width: 8px;
}

.stat-card:hover {
    box-shadow: 0 15px 35px rgba(45, 90, 39, 0.15);
    border-color: rgba(45, 90, 39, 0.2);
}

.stat-card i {
    font-size: 3rem;
    color: #2d5a27;
    width: 70px;
    text-align: center;
    background: linear-gradient(135deg, rgba(45, 90, 39, 0.1), rgba(45, 90, 39, 0.05));
    padding: 15px;
    border-radius: 15px;
}

.stat-card:hover i {
    background: linear-gradient(135deg, rgba(45, 90, 39, 0.15), rgba(45, 90, 39, 0.1));
}

.stat-info {
    flex: 1;
}

.stat-count {
    display: block;
    font-size: 2rem;
    font-weight: 700;
    color: #2d5a27;
    margin-bottom: 8px;
}

.stat-card:hover .stat-count {
    color: #1e3d1c;
}

.stat-text {
    color: #666;
    font-size: 1.1rem;
    font-weight: 500;
}

.stat-card:hover .stat-text {
    color: #555;
}

/* Main Content Enhanced */
.main-content {
    min-height: 60vh;
    background: white;
    position: relative;
}

/* Search Form Styles */
.search-form {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    padding: 30px;
    border-radius: 15px;
    margin-bottom: 30px;
    border: 2px solid rgba(45, 90, 39, 0.1);
}

.search-container {
    display: flex;
    gap: 15px;
    align-items: center;
    max-width: 600px;
    margin: 0 auto;
}

.search-input {
    flex: 1;
    padding: 15px 20px;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: white;
}

.search-input:focus {
    outline: none;
    border-color: #2d5a27;
    box-shadow: 0 0 15px rgba(45, 90, 39, 0.1);
}

.search-btn {
    padding: 15px 25px;
    background: #2d5a27;
    color: white;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-size: 1rem;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.search-btn:hover {
    background: #1e3d1c;
    transform: translateY(-2px);
}

.clear-search {
    padding: 15px 20px;
    background: #f8f9fa;
    color: #666;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    text-decoration: none;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.clear-search:hover {
    background: #e9ecef;
    color: #333;
    text-decoration: none;
}

.search-error {
    background: #f8d7da;
    color: #721c24;
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 20px;
    border: 1px solid #f5c6cb;
    font-family: monospace;
    white-space: pre-wrap;
    overflow-x: auto;
}

.search-results-info {
    text-align: center;
    margin-bottom: 20px;
    padding: 15px;
    background: #d4edda;
    color: #155724;
    border-radius: 10px;
    border: 1px solid #c3e6cb;
}

/* Flowers Grid Enhanced */
.flowers-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 35px;
    margin: 50px 0;
    padding: 20px 0;
}

.flower-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    overflow: hidden;
    position: relative;
    border: 1px solid rgba(45, 90, 39, 0.1);
}

.flower-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #2d5a27, #4a7c59, #6ba86f);
    transform: scaleX(0);
    z-index: 1;
}

.flower-card:hover::before {
    transform: scaleX(1);
}

.flower-card:hover {
    box-shadow: 0 20px 40px rgba(45, 90, 39, 0.15);
    border-color: rgba(45, 90, 39, 0.2);
}

.flower-image {
    height: 220px;
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
}

.flower-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: brightness(1) saturate(1);
}

.flower-card:hover .flower-image img {
    filter: brightness(1.1) saturate(1.2);
}

.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(45, 90, 39, 0.8), rgba(30, 61, 28, 0.9));
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    backdrop-filter: blur(2px);
}

.image-overlay i {
    color: white;
    font-size: 2.5rem;
    text-shadow: 0 2px 10px rgba(0,0,0,0.3);
}

/* Pulse keyframes removed - animations disabled */

.flower-card:hover .image-overlay {
    opacity: 1;
}

.no-image {
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    color: #6c757d;
    font-size: 3rem;
    position: relative;
}

.no-image::before {
    content: '';
    position: absolute;
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: rgba(45, 90, 39, 0.1);
    z-index: 0;
}

.no-image i {
    position: relative;
    z-index: 1;
}

.flower-content {
    padding: 25px;
    position: relative;
}

.flower-name {
    color: #2d5a27;
    font-size: 1.4rem;
    margin-bottom: 12px;
    font-weight: 700;
    line-height: 1.3;
}

.flower-card:hover .flower-name {
    color: #1e3d1c;
}

.flower-category {
    display: inline-flex;
    align-items: center;
    background: linear-gradient(135deg, #e8f5e8, #d4edda);
    color: #2d5a27;
    padding: 6px 15px;
    border-radius: 25px;
    font-size: 0.85rem;
    margin-bottom: 15px;
    font-weight: 600;
    border: 1px solid rgba(45, 90, 39, 0.2);
}

.flower-category:hover {
    background: linear-gradient(135deg, #d4edda, #c3e6cb);
}

.flower-category i {
    margin-right: 6px;
    font-size: 0.8rem;
}

.flower-description {
    color: #555;
    margin-bottom: 18px;
    line-height: 1.7;
    font-size: 0.95rem;
}

.flower-specs {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 25px;
}

.flower-specs .spec {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    color: #495057;
    border: 1px solid rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
}

.flower-specs .spec:hover {
    background: linear-gradient(135deg, #e9ecef, #dee2e6);
}

.flower-specs .spec i {
    margin-right: 6px;
    color: #2d5a27;
}

.btn-detail {
    background: linear-gradient(135deg, #2d5a27, #4a7c59);
    color: white;
    width: 100%;
    text-align: center;
    padding: 15px 20px;
    font-weight: 600;
    border-radius: 12px;
    position: relative;
    overflow: hidden;
    text-decoration: none;
    font-size: 1rem;
    border: none;
    cursor: pointer;
}

.btn-detail::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
}

.btn-detail:hover::before {
    left: 100%;
}

.btn-detail:hover {
    background: linear-gradient(135deg, #1e3d1c, #2d5a27);
    color: white;
    text-decoration: none;
    box-shadow: 0 8px 20px rgba(45, 90, 39, 0.3);
}

.btn-detail i {
    margin-right: 8px;
}

/* No Results Enhanced */
.no-results {
    grid-column: 1 / -1;
    text-align: center;
    padding: 80px 20px;
    color: #666;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-radius: 20px;
    margin: 40px 0;
    border: 2px dashed rgba(45, 90, 39, 0.2);
}

.no-results i {
    font-size: 5rem;
    color: rgba(45, 90, 39, 0.3);
    margin-bottom: 30px;
}

/* Bounce keyframes removed - animations disabled */

.no-results h3 {
    margin-bottom: 15px;
    color: #495057;
    font-size: 1.5rem;
}

.no-results p {
    font-size: 1.1rem;
    color: #666;
}

/* Footer */
.footer {
    background-color: #2d5a27;
    color: white;
    padding: 60px 0 30px;
}

.footer-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 40px;
    margin-bottom: 40px;
}

.footer-section h4 {
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 20px;
}

.footer-section ul {
    list-style: none;
}

.footer-section ul li {
    margin-bottom: 10px;
}

.footer-section a {
    color: #ccc;
    text-decoration: none;
}

.footer-section a:hover {
    color: white;
}

.footer-section p {
    color: #ccc;
    line-height: 1.6;
}

.footer-bottom {
    border-top: 1px solid #4a7c43;
    padding-top: 30px;
    text-align: center;
}

.footer-bottom p {
    color: #ccc;
    margin-bottom: 10px;
}

.footer-bottom a {
    color: white;
    text-decoration: none;
}

.footer-bottom a:hover {
    text-decoration: underline;
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-content {
        grid-template-columns: 1fr;
        gap: 40px;
        text-align: center;
    }
    
    .hero-title {
        font-size: 2.5rem;
    }
    
    .hero-subtitle {
        font-size: 1.1rem;
    }
    
    .hero-stats {
        justify-content: center;
        gap: 20px;
    }
    
    .hero-visual {
        height: 200px;
    }
    
    .floating-flower {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }
    
    .section-title {
        font-size: 2rem;
    }
    
    .quick-stats {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .stat-card {
        padding: 20px;
        gap: 15px;
    }
    
    .stat-card i {
        font-size: 2rem;
        width: 50px;
    }
    
    .stat-count {
        font-size: 1.5rem;
    }
    
    .flowers-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
    }
    
    .header-content {
        flex-direction: column;
        gap: 20px;
    }
    
    .main-nav ul {
        flex-wrap: wrap;
        justify-content: center;
        gap: 15px;
    }
    
    .search-container {
        flex-direction: column;
        gap: 15px;
    }
    
    .search-input,
    .search-btn,
    .clear-search {
        width: 100%;
        text-align: center;
    }
}

@media (max-width: 480px) {
    .container {
        padding: 0 15px;
    }
    
    .hero-section {
        padding: 60px 0;
    }
    
    .hero-title {
        font-size: 2rem;
    }
    
    .hero-stats {
        flex-direction: column;
        gap: 15px;
        align-items: center;
    }
    
    .stat-item {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .stat-number {
        font-size: 1.5rem;
    }
    
    .floating-flower {
        width: 50px;
        height: 50px;
        font-size: 1.2rem;
    }
    
    .search-section {
        padding: 40px 0 30px;
    }
    
    .logo h1 {
        font-size: 1.5rem;
    }
    
    .main-nav ul {
        gap: 10px;
    }
    
    .main-nav a {
        font-size: 0.9rem;
        padding: 8px 15px;
    }
    
    .flowers-grid {
        grid-template-columns: 1fr;
    }
}
    </style>
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

            <!-- Search Form -->
            <div class="search-form">
                <form method="GET" action="">
                    <div class="search-container">
                        <input 
                            type="text" 
                            name="search" 
                            class="search-input" 
                            placeholder="Search for flowers..." 
                            value="<?php echo htmlspecialchars($searchTerm); ?>"
                        >
                        <button type="submit" class="search-btn">
                            <i class="fas fa-search"></i>
                            Search
                        </button>
                        <?php if (!empty($searchTerm)): ?>
                            <a href="index.php" class="clear-search">
                                <i class="fas fa-times"></i>
                                Clear
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <!-- Search Results Info -->
            <?php if (!empty($searchTerm)): ?>
                <div class="search-results-info">
                    <i class="fas fa-info-circle"></i>
                    Search results for: "<strong><?php echo $searchTerm; ?></strong>" 
                    (<?php echo count($flowers); ?> results found)
                </div>
            <?php endif; ?>

            <!-- Display SQL Error if exists -->
            <?php if (isset($searchError)): ?>
                <div class="search-error">
                    <strong><i class="fas fa-exclamation-triangle"></i> SQL Error Detected:</strong>
                    <?php echo htmlspecialchars($searchError); ?>
                </div>
            <?php endif; ?>

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