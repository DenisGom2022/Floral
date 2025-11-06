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
    image_url VARCHAR(255),
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

-- Tabla de usuarios (compradores B2B)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    company_name VARCHAR(200) NOT NULL,
    contact_name VARCHAR(100) NOT NULL,
    business_license VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    business_type ENUM('floral_designer', 'event_planner', 'wedding_venue', 'retailer', 'decorator', 'wholesaler', 'other') DEFAULT 'other',
    status ENUM('pending', 'approved', 'suspended') DEFAULT 'pending',
    discount_percentage DECIMAL(5,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    approved_at TIMESTAMP NULL,
    last_login TIMESTAMP NULL,
    
    INDEX idx_status (status),
    INDEX idx_email (email),
    INDEX idx_company (company_name)
);

-- Tabla de pedidos
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(50) NOT NULL UNIQUE,
    user_id INT NOT NULL,
    total_amount DECIMAL(12,2) NOT NULL,
    discount_amount DECIMAL(12,2) DEFAULT 0.00,
    tax_amount DECIMAL(12,2) DEFAULT 0.00,
    shipping_amount DECIMAL(12,2) DEFAULT 0.00,
    status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    payment_status ENUM('pending', 'paid', 'failed', 'refunded') DEFAULT 'pending',
    payment_method VARCHAR(50),
    shipping_address TEXT,
    billing_address TEXT,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    shipped_at TIMESTAMP NULL,
    delivered_at TIMESTAMP NULL,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_status (status),
    INDEX idx_order_number (order_number),
    INDEX idx_created_at (created_at)
);

-- Tabla de items del pedido
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    total_price DECIMAL(12,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_order (order_id),
    INDEX idx_product (product_id)
);

-- Tabla de carrito de compras
CREATE TABLE cart_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_product (user_id, product_id),
    INDEX idx_user (user_id)
);

-- Tabla de cotizaciones
CREATE TABLE quotes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quote_number VARCHAR(50) NOT NULL UNIQUE,
    user_id INT NOT NULL,
    status ENUM('pending', 'sent', 'accepted', 'declined', 'expired') DEFAULT 'pending',
    total_amount DECIMAL(12,2),
    valid_until DATE,
    notes TEXT,
    admin_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    sent_at TIMESTAMP NULL,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_status (status),
    INDEX idx_quote_number (quote_number)
);

-- Tabla de items de cotización
CREATE TABLE quote_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quote_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2),
    total_price DECIMAL(12,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (quote_id) REFERENCES quotes(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_quote (quote_id),
    INDEX idx_product (product_id)
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
('Hydrangea Clusters - Blue', 'hydrangea-clusters-blue', 'Stunning blue hydrangea clusters with full, rounded blooms. Each stem features multiple flower heads for maximum impact in arrangements.', 'Realistic blue hydrangea clusters perfect for centerpieces', 2, 18.50, 12.50, 6, 'stems', 200, 'HYD-BLU-001', 'https://via.placeholder.com/400x400/4169E1/ffffff?text=Blue+Hydrangeas', TRUE, TRUE, 'Blue', 'Premium Silk'),
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

-- Insertar usuario administrador de ejemplo
INSERT INTO users (username, email, password, company_name, contact_name, business_license, status, discount_percentage) VALUES
('admin', 'admin@usifloral.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USI Floral Imports', 'Administrator', 'ADM-001', 'approved', 0.00);

-- Insertar usuario de prueba
INSERT INTO users (username, email, password, company_name, contact_name, business_license, phone, business_type, status, discount_percentage) VALUES
('testbuyer', 'buyer@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Test Floral Company', 'John Doe', 'TFC-12345', '(555) 123-4567', 'floral_designer', 'approved', 15.00);

-- Índices adicionales para optimización
CREATE INDEX idx_products_name ON products(name);
CREATE INDEX idx_products_price_range ON products(price, active);
CREATE INDEX idx_categories_active ON categories(active);
CREATE INDEX idx_users_status_approved ON users(status, approved_at);

-- Vista para productos con información de categoría
CREATE VIEW products_with_category AS
SELECT 
    p.*,
    c.name as category_name,
    c.slug as category_slug
FROM products p
JOIN categories c ON p.category_id = c.id
WHERE p.active = TRUE AND c.active = TRUE;

-- Vista para estadísticas de productos
CREATE VIEW product_stats AS
SELECT 
    c.name as category_name,
    COUNT(p.id) as total_products,
    AVG(p.price) as avg_price,
    MIN(p.price) as min_price,
    MAX(p.price) as max_price,
    SUM(p.stock_quantity) as total_stock
FROM categories c
LEFT JOIN products p ON c.id = p.category_id AND p.active = TRUE
WHERE c.active = TRUE
GROUP BY c.id, c.name;