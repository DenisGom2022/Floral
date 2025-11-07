<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Floral Database - Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .test-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .success {
            color: #28a745;
            font-weight: bold;
        }
        .test-links {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        .test-link {
            background: #2d5a27;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .test-link:hover {
            background: #4a7c59;
        }
        h1 {
            color: #2d5a27;
            text-align: center;
        }
        .feature-list {
            background: #e8f5e8;
            padding: 20px;
            border-radius: 5px;
            border-left: 4px solid #2d5a27;
        }
        .feature-list h3 {
            margin-top: 0;
            color: #2d5a27;
        }
        .feature-list ul {
            margin: 10px 0;
        }
        .feature-list li {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="test-card">
        <h1>🌺 Floral Database - Sistema Simplificado</h1>
        
        <div class="feature-list">
            <h3>✅ Características Actuales:</h3>
            <ul>
                <li><strong>Sin sistema de login</strong> - Acceso directo a todas las funciones</li>
                <li><strong>Sin funcionalidad de compra</strong> - Solo consulta de productos</li>
                <li><strong>Sin información de base de datos</strong> - Interfaz limpia y simple</li>
                <li><strong>Base de datos simplificada</strong> - Solo productos y categorías</li>
                <li><strong>Precios visibles</strong> - Tanto retail como wholesale siempre mostrados</li>
                <li><strong>Búsqueda vulnerable a SQL injection</strong> - Para pruebas de seguridad</li>
                <li><strong>Vista de detalle ultra-simplificada</strong> - Solo información del producto</li>
            </ul>
        </div>

        <div class="feature-list">
            <h3>🗃️ Estructura de Base de Datos:</h3>
            <ul>
                <li><strong>categories</strong> - Categorías de flores</li>
                <li><strong>products</strong> - Productos con información completa</li>
                <li><strong>Eliminadas:</strong> users, orders, cart_items, quotes, quote_items, order_items</li>
            </ul>
        </div>
        
        <h3>🔗 Enlaces de Prueba:</h3>
        <div class="test-links">
            <a href="setup-database.php" class="test-link">
                🛠️ Setup Database
            </a>
            <a href="floral.php" class="test-link">
                🌸 Flower Database
            </a>
            <a href="product-detail.php?id=1" class="test-link">
                📋 Product Detail
            </a>
            <a href="debug-products.php" class="test-link">
                🐛 Debug Products
            </a>
        </div>

        <div class="feature-list" style="margin-top: 30px;">
            <h3>🛡️ Vulnerabilidad SQL Injection:</h3>
            <p>La función <code>searchProducts()</code> en <code>includes/database.php</code> es intencionalmente vulnerable para pruebas de seguridad.</p>
            <p><strong>Ejemplo de prueba:</strong> En el campo de búsqueda, intenta: <code>' OR 1=1 --</code></p>
        </div>
    </div>
</body>
</html>