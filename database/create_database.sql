-- Crear base de datos
CREATE DATABASE IF NOT EXISTS usi_floral_db;
USE usi_floral_db;

-- Tabla de categorías
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    image_url VARCHAR(255),
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de productos
CREATE TABLE products (
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
);

-- Insertar categorías de ejemplo
INSERT INTO categories (name, slug, description, active) VALUES
('Roses', 'roses', 'Premium silk roses in various colors and styles', TRUE),
('Hydrangeas', 'hydrangeas', 'Beautiful hydrangea clusters for elegant arrangements', TRUE),
('Peonies', 'peonies', 'Luxurious silk peonies perfect for upscale events', TRUE),
('Lilies', 'lilies', 'Elegant lily collection in multiple varieties', TRUE),
('Sunflowers', 'sunflowers', 'Bright and cheerful sunflower arrangements', TRUE),
('Orchids', 'orchids', 'Sophisticated orchid sprays for premium displays', TRUE),
('Mixed Bouquets', 'mixed-bouquets', 'Pre-arranged mixed flower bouquets', TRUE),
('Seasonal', 'seasonal', 'Seasonal and holiday-themed floral arrangements', TRUE);

-- Insertar productos de ejemplo
INSERT INTO products (name, slug, description, short_description, category_id, price, wholesale_price, min_quantity, min_quantity_unit, stock_quantity, sku, image_url, featured, active, color, material) VALUES
-- Roses
('Premium Silk Roses - Red', 'premium-silk-roses-red', 'High-quality silk roses with realistic petals and natural-looking stems. Perfect for weddings, events, and home décor. These premium roses maintain their beauty indefinitely and are indistinguishable from real roses.', 'High-quality silk roses available in vibrant red color', 1, 12.99, 8.99, 12, 'stems', 500, 'PSR-RED-001', 'https://via.placeholder.com/400x400/8B0000/ffffff?text=Red+Roses', TRUE, TRUE, 'Red', 'Premium Silk'),
('Premium Silk Roses - White', 'premium-silk-roses-white', 'Elegant white silk roses perfect for bridal arrangements and sophisticated events. Made with premium materials for a realistic appearance.', 'Premium white silk roses for elegant arrangements', 1, 12.99, 8.99, 12, 'stems', 450, 'PSR-WHT-001', 'https://via.placeholder.com/400x400/FFFFFF/000000?text=White+Roses', TRUE, TRUE, 'White', 'Premium Silk'),
('Premium Silk Roses - Pink', 'premium-silk-roses-pink', 'Soft pink silk roses that add a romantic touch to any arrangement. High-quality construction with attention to detail.', 'Beautiful pink silk roses for romantic settings', 1, 12.99, 8.99, 12, 'stems', 400, 'PSR-PNK-001', 'https://via.placeholder.com/400x400/FFB6C1/000000?text=Pink+Roses', FALSE, TRUE, 'Pink', 'Premium Silk'),

-- Hydrangeas  
('Hydrangea Clusters - Blue', 'hydrangea-clusters-blue', 'Stunning blue hydrangea clusters with full, rounded blooms. Each stem features multiple flower heads for maximum impact in arrangements.', 'Realistic blue hydrangea clusters perfect for centerpieces', 2, 18.50, 12.50, 6, 'stems', 200, 'HYD-BLU-001', 'https://media.diy.com/is/image/KingfisherDigital/hydrangea-macrophylla-early-blue-in-2l-pot-stunning-clusters-of-blue-flowers~5056742316584_01c_MP?$MOB_PREV$&$width=1200&$height=1200', TRUE, TRUE, 'Blue', 'Premium Silk'),
('Hydrangea Clusters - Purple', 'hydrangea-clusters-purple', 'Rich purple hydrangeas that bring depth and color to any display. Perfect for creating dramatic floral arrangements.', 'Rich purple hydrangea clusters for dramatic displays', 2, 18.50, 12.50, 6, 'stems', 180, 'HYD-PUR-001', 'https://via.placeholder.com/400x400/8B008B/ffffff?text=Purple+Hydrangeas', FALSE, TRUE, 'Purple', 'Premium Silk'),

-- Peonies
('Peony Arrangements - Coral', 'peony-arrangements-coral', 'Luxurious coral peonies with layers of delicate petals. These statement flowers are perfect for high-end events and sophisticated décor.', 'Luxurious coral silk peonies for elegant events', 3, 22.75, 16.75, 8, 'stems', 150, 'PEO-COR-001', 'https://via.placeholder.com/400x400/FF7F50/ffffff?text=Coral+Peonies', TRUE, TRUE, 'Coral', 'Premium Silk'),
('Peony Arrangements - Blush', 'peony-arrangements-blush', 'Soft blush peonies that create an romantic and elegant atmosphere. Perfect for weddings and upscale events.', 'Soft blush peonies for romantic arrangements', 3, 22.75, 16.75, 8, 'stems', 130, 'PEO-BLU-001', 'https://via.placeholder.com/400x400/FFC0CB/000000?text=Blush+Peonies', FALSE, TRUE, 'Blush', 'Premium Silk'),

-- Lilies
('Lily Collection - White', 'lily-collection-white', 'Classic white lilies with prominent stamens and elegant form. These versatile flowers work beautifully in both modern and traditional arrangements.', 'Beautiful white silk lilies for versatile arrangements', 4, 15.25, 10.25, 10, 'stems', 300, 'LIL-WHT-001', 'https://via.placeholder.com/400x400/FFFFFF/000000?text=White+Lilies', FALSE, TRUE, 'White', 'Premium Silk'),
('Lily Collection - Orange', 'lily-collection-orange', 'Vibrant orange lilies that add warmth and energy to any space. Perfect for autumn arrangements and bold displays.', 'Vibrant orange lilies for bold arrangements', 4, 15.25, 10.25, 10, 'stems', 250, 'LIL-ORG-001', 'https://via.placeholder.com/400x400/FF8C00/ffffff?text=Orange+Lilies', FALSE, TRUE, 'Orange', 'Premium Silk'),

-- Sunflowers
('Sunflower Bunches', 'sunflower-bunches', 'Bright and cheerful sunflowers that bring sunshine to any arrangement. Large, full blooms with realistic centers and petals.', 'Bright and cheerful silk sunflowers', 5, 14.99, 10.99, 6, 'stems', 220, 'SUN-YEL-001', 'https://via.placeholder.com/400x400/FFD700/000000?text=Sunflowers', TRUE, TRUE, 'Yellow', 'Premium Silk'),

-- Orchids
('Orchid Sprays - Purple', 'orchid-sprays-purple', 'Sophisticated purple orchid sprays with multiple blooms per stem. These exotic flowers add elegance and refinement to any setting.', 'Elegant purple orchid sprays for upscale arrangements', 6, 28.99, 22.99, 4, 'stems', 100, 'ORC-PUR-001', 'https://via.placeholder.com/400x400/9370DB/ffffff?text=Purple+Orchids', TRUE, TRUE, 'Purple', 'Premium Silk'),
('Orchid Sprays - White', 'orchid-sprays-white', 'Pure white orchid sprays that embody elegance and sophistication. Perfect for minimalist and luxury arrangements.', 'Pure white orchid sprays for luxury arrangements', 6, 28.99, 22.99, 4, 'stems', 80, 'ORC-WHT-001', 'https://via.placeholder.com/400x400/FFFFFF/000000?text=White+Orchids', FALSE, TRUE, 'White', 'Premium Silk');

-- Índices adicionales para optimización
CREATE INDEX idx_products_name ON products(name);
CREATE INDEX idx_products_price_range ON products(price, active);
CREATE INDEX idx_categories_active ON categories(active);