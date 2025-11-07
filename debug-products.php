<?php
// Debug script to check available products
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Available Products Debug</h2>\n";

try {
    require_once 'includes/database.php';
    
    $db = Database::getInstance()->getConnection();
    
    // Check if products table exists
    $stmt = $db->query("SHOW TABLES LIKE 'products'");
    if ($stmt->rowCount() == 0) {
        echo "<p>❌ Products table doesn't exist. <a href='setup-database.php'>Run database setup</a></p>";
        exit;
    }
    
    // Get all products
    $stmt = $db->query("SELECT id, name, slug, category_id FROM products");
    $products = $stmt->fetchAll();
    
    if (empty($products)) {
        echo "<p>⚠ Products table is empty. <a href='setup-database.php'>Run database setup</a> to add sample data.</p>";
    } else {
        echo "<h3>Available Products:</h3>\n";
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>\n";
        echo "<tr><th>ID</th><th>Name</th><th>Slug</th><th>Category ID</th><th>Detail Link</th></tr>\n";
        
        foreach ($products as $product) {
            echo "<tr>";
            echo "<td>" . $product['id'] . "</td>";
            echo "<td>" . htmlspecialchars($product['name']) . "</td>";
            echo "<td>" . htmlspecialchars($product['slug']) . "</td>";
            echo "<td>" . $product['category_id'] . "</td>";
            echo "<td><a href='product-detail.php?id=" . $product['id'] . "'>View Detail</a></td>";
            echo "</tr>\n";
        }
        echo "</table>\n";
    }
    
    // Check categories too
    echo "<h3>Available Categories:</h3>\n";
    $stmt = $db->query("SELECT id, name, slug FROM categories");
    $categories = $stmt->fetchAll();
    
    if (empty($categories)) {
        echo "<p>⚠ Categories table is empty.</p>";
    } else {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>\n";
        echo "<tr><th>ID</th><th>Name</th><th>Slug</th></tr>\n";
        
        foreach ($categories as $category) {
            echo "<tr>";
            echo "<td>" . $category['id'] . "</td>";
            echo "<td>" . htmlspecialchars($category['name']) . "</td>";
            echo "<td>" . htmlspecialchars($category['slug']) . "</td>";
            echo "</tr>\n";
        }
        echo "</table>\n";
    }
    
} catch (Exception $e) {
    echo "<p>❌ Database Error: " . $e->getMessage() . "</p>\n";
    echo "<p><a href='setup-database.php'>Run database setup</a></p>\n";
}

echo "<br><p><a href='floral.php'>← Back to Floral Database</a></p>\n";
?>