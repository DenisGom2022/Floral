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

function isUserLoggedIn() {
    return isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
}

function getUserDiscount() {
    return $_SESSION['user_discount'] ?? 0;
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