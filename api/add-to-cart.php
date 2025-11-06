<?php
header('Content-Type: application/json');
session_start();

require_once '../includes/database.php';

// Verificar método
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
    exit;
}

// Verificar autenticación
if (!isset($_SESSION['user_logged_in']) || !$_SESSION['user_logged_in']) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Usuario no autenticado']);
    exit;
}

// Obtener datos del POST
$input = json_decode(file_get_contents('php://input'), true);
$productId = intval($input['product_id'] ?? 0);
$quantity = intval($input['quantity'] ?? 1);
$userId = intval($_SESSION['user_id'] ?? 0);

if ($productId <= 0 || $quantity <= 0 || $userId <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Datos inválidos']);
    exit;
}

try {
    $db = Database::getInstance()->getConnection();
    
    // Verificar que el producto existe y está activo
    $stmt = $db->prepare("SELECT id, name, min_quantity FROM products WHERE id = ? AND active = TRUE");
    $stmt->execute([$productId]);
    $product = $stmt->fetch();
    
    if (!$product) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Producto no encontrado']);
        exit;
    }
    
    // Verificar cantidad mínima
    if ($quantity < $product['min_quantity']) {
        echo json_encode([
            'success' => false, 
            'error' => "La cantidad mínima para este producto es {$product['min_quantity']}"
        ]);
        exit;
    }
    
    // Verificar si el producto ya está en el carrito
    $stmt = $db->prepare("SELECT id, quantity FROM cart_items WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$userId, $productId]);
    $existingItem = $stmt->fetch();
    
    if ($existingItem) {
        // Actualizar cantidad existente
        $newQuantity = $existingItem['quantity'] + $quantity;
        $stmt = $db->prepare("UPDATE cart_items SET quantity = ?, updated_at = NOW() WHERE id = ?");
        $stmt->execute([$newQuantity, $existingItem['id']]);
    } else {
        // Insertar nuevo item
        $stmt = $db->prepare("INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $productId, $quantity]);
    }
    
    // Obtener conteo total del carrito
    $stmt = $db->prepare("SELECT SUM(quantity) as total FROM cart_items WHERE user_id = ?");
    $stmt->execute([$userId]);
    $cartCount = $stmt->fetch()['total'] ?? 0;
    
    echo json_encode([
        'success' => true,
        'message' => "Producto agregado al carrito",
        'cart_count' => (int)$cartCount
    ]);
    
} catch (Exception $e) {
    error_log("Cart API Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Error interno del servidor']);
}
?>