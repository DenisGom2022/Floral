<?php
header('Content-Type: application/json');
session_start();

require_once '../includes/database.php';

// Verificar método
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

// Obtener ID del producto
$productId = intval($_GET['id'] ?? 0);

if ($productId <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'ID de producto inválido']);
    exit;
}

try {
    $productManager = new ProductManager();
    $product = $productManager->getProductById($productId);
    
    if (!$product) {
        http_response_code(404);
        echo json_encode(['error' => 'Producto no encontrado']);
        exit;
    }
    
    // Formatear el producto para la respuesta
    $response = [
        'id' => (int)$product['id'],
        'name' => $product['name'],
        'slug' => $product['slug'],
        'description' => $product['description'],
        'short_description' => $product['short_description'],
        'category_name' => $product['category_name'],
        'category_slug' => $product['category_slug'],
        'price' => (float)$product['price'],
        'wholesale_price' => $product['wholesale_price'] ? (float)$product['wholesale_price'] : null,
        'min_quantity' => (int)$product['min_quantity'],
        'min_quantity_unit' => $product['min_quantity_unit'],
        'stock_quantity' => (int)$product['stock_quantity'],
        'sku' => $product['sku'],
        'image_url' => $product['image_url'],
        'featured' => (bool)$product['featured'],
        'color' => $product['color'],
        'material' => $product['material'],
        'weight' => $product['weight'] ? (float)$product['weight'] : null,
        'dimensions' => $product['dimensions']
    ];
    
    echo json_encode($response);
    
} catch (Exception $e) {
    error_log("API Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Error interno del servidor']);
}
?>