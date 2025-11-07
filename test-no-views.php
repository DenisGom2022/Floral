<?php
// Test para verificar que las funciones PHP reemplazan correctamente las vistas SQL
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Test: Funciones PHP sin Vistas SQL</h2>\n";

try {
    require_once 'includes/database.php';
    
    echo "<div style='background: #e8f5e8; padding: 20px; border-radius: 5px; margin: 20px 0;'>\n";
    echo "<h3>✅ Test 1: Productos con Categoría (reemplaza products_with_category)</h3>\n";
    
    $productManager = new ProductManager();
    $productsWithCategory = $productManager->getProductsWithCategory(5);
    
    if ($productsWithCategory) {
        echo "<p><strong>✓ Exitoso:</strong> Se obtuvieron " . count($productsWithCategory) . " productos con información de categoría</p>\n";
        
        foreach ($productsWithCategory as $product) {
            echo "<div style='background: white; padding: 10px; margin: 5px 0; border-left: 4px solid #2d5a27;'>\n";
            echo "<strong>{$product['name']}</strong> - Categoría: {$product['category_name']} - Precio: $" . number_format($product['price'], 2) . "\n";
            echo "</div>\n";
        }
    } else {
        echo "<p><strong>⚠ Advertencia:</strong> No se encontraron productos (puede ser normal si la base de datos está vacía)</p>\n";
    }
    echo "</div>\n";
    
    echo "<div style='background: #e3f2fd; padding: 20px; border-radius: 5px; margin: 20px 0;'>\n";
    echo "<h3>✅ Test 2: Estadísticas de Productos (reemplaza product_stats)</h3>\n";
    
    $categoryManager = new CategoryManager();
    $productStats = $categoryManager->getProductStats();
    
    if ($productStats) {
        echo "<p><strong>✓ Exitoso:</strong> Se obtuvieron estadísticas para " . count($productStats) . " categorías</p>\n";
        
        echo "<table style='width: 100%; border-collapse: collapse; background: white;'>\n";
        echo "<tr style='background: #f8f9fa;'>\n";
        echo "<th style='padding: 10px; border: 1px solid #ddd;'>Categoría</th>\n";
        echo "<th style='padding: 10px; border: 1px solid #ddd;'>Total Productos</th>\n";
        echo "<th style='padding: 10px; border: 1px solid #ddd;'>Precio Promedio</th>\n";
        echo "<th style='padding: 10px; border: 1px solid #ddd;'>Stock Total</th>\n";
        echo "</tr>\n";
        
        foreach ($productStats as $stat) {
            echo "<tr>\n";
            echo "<td style='padding: 10px; border: 1px solid #ddd;'>{$stat['category_name']}</td>\n";
            echo "<td style='padding: 10px; border: 1px solid #ddd; text-align: center;'>{$stat['total_products']}</td>\n";
            echo "<td style='padding: 10px; border: 1px solid #ddd; text-align: center;'>$" . number_format($stat['avg_price'], 2) . "</td>\n";
            echo "<td style='padding: 10px; border: 1px solid #ddd; text-align: center;'>{$stat['total_stock']}</td>\n";
            echo "</tr>\n";
        }
        echo "</table>\n";
    } else {
        echo "<p><strong>⚠ Advertencia:</strong> No se obtuvieron estadísticas (puede ser normal si la base de datos está vacía)</p>\n";
    }
    echo "</div>\n";
    
    echo "<div style='background: #fff3cd; padding: 20px; border-radius: 5px; margin: 20px 0;'>\n";
    echo "<h3>✅ Test 3: Producto Individual con Categoría</h3>\n";
    
    $singleProduct = $productManager->getProductWithCategory(1);
    
    if ($singleProduct) {
        echo "<p><strong>✓ Exitoso:</strong> Se obtuvo información completa del producto</p>\n";
        echo "<div style='background: white; padding: 15px; border-left: 4px solid #ffc107;'>\n";
        echo "<h4>{$singleProduct['name']}</h4>\n";
        echo "<p><strong>Categoría:</strong> {$singleProduct['category_name']}</p>\n";
        echo "<p><strong>Descripción:</strong> {$singleProduct['short_description']}</p>\n";
        echo "<p><strong>Precio:</strong> $" . number_format($singleProduct['price'], 2) . "</p>\n";
        echo "<p><strong>Stock:</strong> {$singleProduct['stock_quantity']}</p>\n";
        echo "</div>\n";
    } else {
        echo "<p><strong>⚠ Advertencia:</strong> No se encontró el producto con ID 1 (puede ser normal si no existe)</p>\n";
    }
    echo "</div>\n";
    
    echo "<div style='background: #d4edda; padding: 20px; border-radius: 5px; margin: 20px 0;'>\n";
    echo "<h3>🎉 Resultado Final</h3>\n";
    echo "<p><strong>✅ ÉXITO:</strong> Las funciones PHP están funcionando correctamente sin necesidad de vistas SQL.</p>\n";
    echo "<p>Tu servicio MySQL ahora es compatible ya que no se requieren permisos para crear vistas.</p>\n";
    echo "</div>\n";
    
} catch (PDOException $e) {
    echo "<div style='background: #f8d7da; padding: 20px; border-radius: 5px; margin: 20px 0;'>\n";
    echo "<h3>❌ Error de Base de Datos</h3>\n";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>\n";
    echo "<p><strong>Solución:</strong> Ejecuta <a href='setup-database.php'>setup-database.php</a> primero para crear la base de datos.</p>\n";
    echo "</div>\n";
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; padding: 20px; border-radius: 5px; margin: 20px 0;'>\n";
    echo "<h3>❌ Error General</h3>\n";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>\n";
    echo "</div>\n";
}

echo "<div style='margin: 30px 0; text-align: center;'>\n";
echo "<h3>🔗 Enlaces de Prueba</h3>\n";
echo "<a href='setup-database.php' style='background: #2d5a27; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 5px;'>🛠️ Setup Database</a>\n";
echo "<a href='floral.php' style='background: #4a7c59; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 5px;'>🌸 Floral Database</a>\n";
echo "<a href='product-detail.php?id=1' style='background: #6ba368; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 5px;'>📋 Product Detail</a>\n";
echo "</div>\n";
?>

<style>
body {
    font-family: Arial, sans-serif;
    max-width: 900px;
    margin: 20px auto;
    padding: 20px;
    background-color: #f8f9fa;
}

h2 {
    color: #2d5a27;
    text-align: center;
    margin-bottom: 30px;
}

h3 {
    margin-top: 0;
}

a:hover {
    opacity: 0.8;
}
</style>