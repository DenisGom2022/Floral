<?php
// Simple database test and setup
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Database Connection Test</h2>\n";

// Test basic MySQL connection first
try {
    $host = 'localhost';
    $username = 'root';
    $password = '';
    
    echo "1. Testing basic MySQL connection...<br>\n";
    $pdo = new PDO("mysql:host=$host", $username, $password);
    echo "✓ MySQL connection successful<br>\n";
    
    // Check if database exists
    echo "2. Checking if database 'usi_floral_db' exists...<br>\n";
    $stmt = $pdo->query("SHOW DATABASES LIKE 'usi_floral_db'");
    $dbExists = $stmt->rowCount() > 0;
    
    if (!$dbExists) {
        echo "⚠ Database doesn't exist. Creating it...<br>\n";
        $pdo->exec("CREATE DATABASE usi_floral_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo "✓ Database created<br>\n";
    } else {
        echo "✓ Database exists<br>\n";
    }
    
    // Connect to the database
    echo "3. Connecting to usi_floral_db...<br>\n";
    $pdo = new PDO("mysql:host=$host;dbname=usi_floral_db;charset=utf8mb4", $username, $password);
    echo "✓ Connected to database<br>\n";
    
    // Check if tables exist
    echo "4. Checking tables...<br>\n";
    $tables = ['categories', 'products'];
    
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        $tableExists = $stmt->rowCount() > 0;
        
        if (!$tableExists) {
            echo "⚠ Table '$table' doesn't exist. Creating it...<br>\n";
            
            if ($table === 'categories') {
                $sql = "CREATE TABLE categories (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(100) NOT NULL,
                    slug VARCHAR(100) NOT NULL UNIQUE,
                    description TEXT,
                    image_url VARCHAR(255),
                    active BOOLEAN DEFAULT TRUE,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                )";
                $pdo->exec($sql);
                
                // Insert sample categories
                $pdo->exec("INSERT INTO categories (name, slug, description, active) VALUES 
                    ('Roses', 'roses', 'Premium silk roses in various colors and styles', TRUE),
                    ('Hydrangeas', 'hydrangeas', 'Beautiful hydrangea clusters for elegant arrangements', TRUE),
                    ('Peonies', 'peonies', 'Luxurious silk peonies perfect for upscale events', TRUE),
                    ('Lilies', 'lilies', 'Elegant lily collection in multiple varieties', TRUE),
                    ('Sunflowers', 'sunflowers', 'Bright and cheerful sunflower arrangements', TRUE),
                    ('Orchids', 'orchids', 'Sophisticated orchid sprays for premium displays', TRUE),
                    ('Mixed Bouquets', 'mixed-bouquets', 'Pre-arranged mixed flower bouquets', TRUE),
                    ('Seasonal', 'seasonal', 'Seasonal and holiday-themed floral arrangements', TRUE)");
                    
            } elseif ($table === 'products') {
                $sql = "CREATE TABLE products (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(200) NOT NULL,
                    slug VARCHAR(200) NOT NULL UNIQUE,
                    description TEXT,
                    short_description VARCHAR(500),
                    category_id INT NOT NULL,
                    price DECIMAL(10,2) NOT NULL,
                    wholesale_price DECIMAL(10,2),
                    min_quantity INT DEFAULT 1,
                    min_quantity_unit VARCHAR(50) DEFAULT 'pieces',
                    stock_quantity INT DEFAULT 0,
                    sku VARCHAR(100) UNIQUE,
                    image_url TEXT,
                    gallery_images JSON,
                    featured BOOLEAN DEFAULT FALSE,
                    active BOOLEAN DEFAULT TRUE,
                    meta_title VARCHAR(255),
                    meta_description TEXT,
                    weight DECIMAL(8,2),
                    dimensions VARCHAR(100),
                    color VARCHAR(50),
                    material VARCHAR(100),
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    
                    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
                    INDEX idx_category (category_id),
                    INDEX idx_active (active),
                    INDEX idx_featured (featured),
                    INDEX idx_price (price),
                    FULLTEXT idx_search (name, description, short_description)
                )";
                $pdo->exec($sql);
                
                // Insert sample products
                $pdo->exec("INSERT INTO products (name, slug, description, short_description, category_id, price, wholesale_price, min_quantity, min_quantity_unit, stock_quantity, sku, image_url, featured, active, color, material) VALUES 
                    ('Premium Silk Roses - Red', 'premium-silk-roses-red', 'High-quality silk roses with realistic petals and natural-looking stems. Perfect for weddings, events, and home décor.', 'High-quality silk roses available in vibrant red color', 1, 12.99, 8.99, 12, 'stems', 500, 'PSR-RED-001', 'https://via.placeholder.com/400x400/8B0000/ffffff?text=Red+Roses', TRUE, TRUE, 'Red', 'Premium Silk'),
                    ('Premium Silk Roses - White', 'premium-silk-roses-white', 'Elegant white silk roses perfect for bridal arrangements and sophisticated events.', 'Premium white silk roses for elegant arrangements', 1, 12.99, 8.99, 12, 'stems', 450, 'PSR-WHT-001', 'https://via.placeholder.com/400x400/FFFFFF/000000?text=White+Roses', TRUE, TRUE, 'White', 'Premium Silk'),
                    ('Hydrangea Clusters - Blue', 'hydrangea-clusters-blue', 'Stunning blue hydrangea clusters with full, rounded blooms.', 'Realistic blue hydrangea clusters perfect for centerpieces', 2, 18.50, 12.50, 6, 'stems', 200, 'HYD-BLU-001', 'https://via.placeholder.com/400x400/4169E1/ffffff?text=Blue+Hydrangeas', TRUE, TRUE, 'Blue', 'Premium Silk'),
                    ('Peony Arrangements - Coral', 'peony-arrangements-coral', 'Luxurious coral peonies with layers of delicate petals.', 'Luxurious coral silk peonies for elegant events', 3, 22.75, 16.75, 8, 'stems', 150, 'PEO-COR-001', 'https://via.placeholder.com/400x400/FF7F50/ffffff?text=Coral+Peonies', TRUE, TRUE, 'Coral', 'Premium Silk'),
                    ('Lily Collection - White', 'lily-collection-white', 'Classic white lilies with prominent stamens and elegant form.', 'Beautiful white silk lilies for versatile arrangements', 4, 15.25, 10.25, 10, 'stems', 300, 'LIL-WHT-001', 'https://via.placeholder.com/400x400/FFFFFF/000000?text=White+Lilies', FALSE, TRUE, 'White', 'Premium Silk'),
                    ('Sunflower Bunches', 'sunflower-bunches', 'Bright and cheerful sunflowers that bring sunshine to any arrangement.', 'Bright and cheerful silk sunflowers', 5, 14.99, 10.99, 6, 'stems', 220, 'SUN-YEL-001', 'https://via.placeholder.com/400x400/FFD700/000000?text=Sunflowers', TRUE, TRUE, 'Yellow', 'Premium Silk'),
                    ('Orchid Sprays - Purple', 'orchid-sprays-purple', 'Sophisticated purple orchid sprays with multiple blooms per stem.', 'Elegant purple orchid sprays for upscale arrangements', 6, 28.99, 22.99, 4, 'stems', 100, 'ORC-PUR-001', 'https://via.placeholder.com/400x400/9370DB/ffffff?text=Purple+Orchids', TRUE, TRUE, 'Purple', 'Premium Silk'),
                    ('Orchid Sprays - White', 'orchid-sprays-white', 'Pure white orchid sprays that embody elegance and sophistication.', 'Pure white orchid sprays for luxury arrangements', 6, 28.99, 22.99, 4, 'stems', 80, 'ORC-WHT-001', 'https://via.placeholder.com/400x400/FFFFFF/000000?text=White+Orchids', FALSE, TRUE, 'White', 'Premium Silk')");
            }
            
            echo "✓ Table '$table' created<br>\n";
        } else {
            echo "✓ Table '$table' exists<br>\n";
        }
    }
    
    echo "<br><h3>✓ Database setup complete!</h3>\n";
    echo "<p><strong>Note:</strong> This setup is compatible with MySQL services that don't allow views.</p>\n";
    echo "<p>Instead of SQL views, we use PHP functions for complex queries.</p>\n";
    echo "<br><div style='text-align: center;'>\n";
    echo "<a href='floral.php' style='background: #2d5a27; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 5px;'>🌸 Go to Floral Database</a>\n";
    echo "<a href='test-no-views.php' style='background: #4a7c59; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 5px;'>🧪 Test Functions</a>\n";
    echo "</div>\n";
    
} catch (PDOException $e) {
    echo "<br><strong>❌ Database Error:</strong> " . $e->getMessage() . "<br>\n";
    echo "<br><strong>Possible solutions:</strong><br>\n";
    echo "1. Make sure MySQL/MariaDB is running<br>\n";
    echo "2. Check your database credentials in includes/database.php<br>\n";
    echo "3. Make sure you have permission to create databases<br>\n";
} catch (Exception $e) {
    echo "<br><strong>❌ Error:</strong> " . $e->getMessage() . "<br>\n";
}
?>