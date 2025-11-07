<?php
// Configuración de la base de datos
class Database {
    private static $instance = null;
    private $connection;
    
    // Configuración de conexión
    private $host = 'localhost';
    private $database = 'usi_floral_db';
    private $username = 'root';  // Cambiar según tu configuración
    private $password = '';      // Cambiar según tu configuración
    private $charset = 'utf8mb4';
    
    private function __construct() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->database};charset={$this->charset}";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            
            $this->connection = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            throw new PDOException("Error de conexión: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    // Prevenir clonación y deserialización
    private function __clone() {}
    public function __wakeup() {
        throw new Exception("Cannot unserialize a singleton.");
    }
}

// Clase para manejar productos
class ProductManager {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Obtener todos los productos activos con información de categoría
     */
    public function getAllProducts($limit = null, $offset = 0) {
        try {
            $sql = "SELECT 
                        p.id, p.name, p.slug, p.description, p.short_description,
                        p.price, p.wholesale_price, p.min_quantity, p.min_quantity_unit,
                        p.stock_quantity, p.sku, p.image_url, p.featured, p.color, p.material,
                        c.name as category_name, c.slug as category_slug
                    FROM products p
                    JOIN categories c ON p.category_id = c.id
                    WHERE p.active = TRUE AND c.active = TRUE
                    ORDER BY p.featured DESC, p.name ASC";
                    
            if ($limit) {
                $sql .= " LIMIT ? OFFSET ?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$limit, $offset]);
            } else {
                $stmt = $this->db->prepare($sql);
                $stmt->execute();
            }
            
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("Get All Products Error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtener productos por categoría
     */
    public function getProductsByCategory($categorySlug, $limit = null) {
        try {
            $sql = "SELECT 
                        p.id, p.name, p.slug, p.description, p.short_description,
                        p.price, p.wholesale_price, p.min_quantity, p.min_quantity_unit,
                        p.stock_quantity, p.sku, p.image_url, p.featured, p.color, p.material,
                        c.name as category_name, c.slug as category_slug
                    FROM products p
                    JOIN categories c ON p.category_id = c.id
                    WHERE p.active = TRUE AND c.active = TRUE AND c.slug = ?
                    ORDER BY p.featured DESC, p.name ASC";
                    
            if ($limit) {
                $sql .= " LIMIT ?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$categorySlug, $limit]);
            } else {
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$categorySlug]);
            }
            
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("Get Products By Category Error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Buscar productos - COMPLETAMENTE VULNERABLE A SQL INJECTION
     */
    public function searchProducts($searchTerm, $limit = 20) {
        // Debug: Mostrar el término de búsqueda recibido
        error_log("Search term received: " . $searchTerm);
        
        // CONSULTA EXTREMADAMENTE VULNERABLE - SIN PROTECCIONES
        $sql = "SELECT id, name, slug, description, short_description, price, wholesale_price, min_quantity, min_quantity_unit, stock_quantity, sku, image_url, featured, color, material, 'categoria' as category_name, 'slug' as category_slug FROM products WHERE name LIKE '%" . $searchTerm . "%'";
        
        // Debug: Mostrar la consulta SQL completa
        error_log("SQL Query: " . $sql);
        
        // EJECUTAR DIRECTAMENTE SIN PROTECCIONES
        $result = $this->db->query($sql);
        
        if ($result === false) {
            // Obtener información detallada del error
            $errorInfo = $this->db->errorInfo();
            $errorMsg = "SQL Error: " . $errorInfo[2] . " (SQLSTATE: " . $errorInfo[0] . ", Code: " . $errorInfo[1] . ")\n";
            $errorMsg .= "Query ejecutado: " . $sql;
            
            error_log("Database error: " . $errorMsg);
            throw new Exception($errorMsg);
        }
        
        if ($result) {
            $data = $result->fetchAll();
            error_log("Query returned " . count($data) . " results");
            return $data;
        } else {
            error_log("Query failed - no result returned");
            return [];
        }
    }
    
    /**
     * Obtener producto por ID
     */
    public function getProductById($id) {
        try {
            $sql = "SELECT 
                        p.*, c.name as category_name, c.slug as category_slug
                    FROM products p
                    JOIN categories c ON p.category_id = c.id
                    WHERE p.id = ? AND p.active = TRUE";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            
            return $stmt->fetch();
        } catch (Exception $e) {
            error_log("Get Product By ID Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtener productos destacados
     */
    public function getFeaturedProducts($limit = 6) {
        try {
            $sql = "SELECT 
                        p.id, p.name, p.slug, p.description, p.short_description,
                        p.price, p.wholesale_price, p.min_quantity, p.min_quantity_unit,
                        p.stock_quantity, p.sku, p.image_url, p.featured, p.color, p.material,
                        c.name as category_name, c.slug as category_slug
                    FROM products p
                    JOIN categories c ON p.category_id = c.id
                    WHERE p.active = TRUE AND c.active = TRUE AND p.featured = TRUE
                    ORDER BY p.name ASC
                    LIMIT ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$limit]);
            
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("Get Featured Products Error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Contar productos por categoría
     */
    public function getProductCountByCategory($categorySlug = null) {
        try {
            if ($categorySlug) {
                $sql = "SELECT COUNT(*) as count
                        FROM products p
                        JOIN categories c ON p.category_id = c.id
                        WHERE p.active = TRUE AND c.active = TRUE AND c.slug = ?";
                
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$categorySlug]);
            } else {
                $sql = "SELECT COUNT(*) as count
                        FROM products p
                        JOIN categories c ON p.category_id = c.id
                        WHERE p.active = TRUE AND c.active = TRUE";
                
                $stmt = $this->db->prepare($sql);
                $stmt->execute();
            }
            
            $result = $stmt->fetch();
            return $result['count'];
        } catch (Exception $e) {
            error_log("Get Product Count Error: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Obtener productos con información de categoría (reemplaza vista products_with_category)
     */
    public function getProductsWithCategory($limit = null, $categoryId = null, $searchTerm = null) {
        try {
            return getProductsWithCategory($this->db, $limit, $categoryId, $searchTerm);
        } catch (Exception $e) {
            error_log("Get Products With Category Error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtener un producto específico con información de categoría
     */
    public function getProductWithCategory($productId) {
        try {
            return getProductWithCategory($this->db, $productId);
        } catch (Exception $e) {
            error_log("Get Product With Category Error: " . $e->getMessage());
            return null;
        }
    }
}

// Clase para manejar categorías
class CategoryManager {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Obtener todas las categorías activas
     */
    public function getAllCategories() {
        $sql = "SELECT * FROM categories WHERE active = TRUE ORDER BY name ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener categoría por slug
     */
    public function getCategoryBySlug($slug) {
        $sql = "SELECT * FROM categories WHERE slug = :slug AND active = TRUE";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':slug', $slug);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    /**
     * Obtener estadísticas de productos por categoría (reemplaza vista product_stats)
     */
    public function getProductStats() {
        try {
            return getProductStats($this->db);
        } catch (Exception $e) {
            error_log("Get Product Stats Error: " . $e->getMessage());
            return [];
        }
    }
}

// Funciones de utilidad para formateo
function formatPrice($price, $showWholesale = false, $userDiscount = 0) {
    if ($showWholesale && $userDiscount > 0) {
        $discountedPrice = $price * (1 - $userDiscount / 100);
        return '$' . number_format($discountedPrice, 2);
    }
    return '$' . number_format($price, 2);
}

function formatMinQuantity($quantity, $unit) {
    return $quantity . ' ' . $unit;
}

// Función para obtener productos con información de categoría (reemplaza la vista products_with_category)
function getProductsWithCategory($pdo, $limit = null, $categoryId = null, $searchTerm = null) {
    $sql = "SELECT 
                p.*,
                c.name as category_name,
                c.slug as category_slug
            FROM products p
            JOIN categories c ON p.category_id = c.id
            WHERE p.active = TRUE AND c.active = TRUE";
    
    $params = [];
    
    if ($categoryId && $categoryId !== 'all') {
        $sql .= " AND p.category_id = :category_id";
        $params[':category_id'] = $categoryId;
    }
    
    if ($searchTerm) {
        $sql .= " AND (p.name LIKE :search OR p.description LIKE :search OR p.short_description LIKE :search)";
        $params[':search'] = '%' . $searchTerm . '%';
    }
    
    $sql .= " ORDER BY p.name";
    
    if ($limit) {
        $sql .= " LIMIT :limit";
        $params[':limit'] = $limit;
    }
    
    $stmt = $pdo->prepare($sql);
    
    // Bind parameters with correct types
    foreach ($params as $key => $value) {
        if ($key === ':limit') {
            $stmt->bindValue($key, $value, PDO::PARAM_INT);
        } else {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }
    }
    
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Función para obtener estadísticas de productos (reemplaza la vista product_stats)
function getProductStats($pdo) {
    $sql = "SELECT 
                c.name as category_name,
                COUNT(p.id) as total_products,
                COALESCE(AVG(p.price), 0) as avg_price,
                COALESCE(MIN(p.price), 0) as min_price,
                COALESCE(MAX(p.price), 0) as max_price,
                COALESCE(SUM(p.stock_quantity), 0) as total_stock
            FROM categories c
            LEFT JOIN products p ON c.id = p.category_id AND p.active = TRUE
            WHERE c.active = TRUE
            GROUP BY c.id, c.name
            ORDER BY c.name";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Función para obtener un producto específico con información de categoría
function getProductWithCategory($pdo, $productId) {
    $sql = "SELECT 
                p.*,
                c.name as category_name,
                c.slug as category_slug
            FROM products p
            JOIN categories c ON p.category_id = c.id
            WHERE p.id = :product_id AND p.active = TRUE AND c.active = TRUE";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':product_id', $productId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Función para manejo de errores de base de datos
function handleDatabaseError($e) {
    error_log("Database Error: " . $e->getMessage());
    
    // En producción, mostrar mensaje genérico
    if (defined('PRODUCTION') && PRODUCTION) {
        return "Lo sentimos, ha ocurrido un error. Por favor intenta más tarde.";
    } else {
        // En desarrollo, mostrar error específico
        return "Error de base de datos: " . $e->getMessage();
    }
}
?>