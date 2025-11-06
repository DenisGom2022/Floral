# Configuración de Base de Datos - USI Floral Imports

## Pasos para la Instalación

### 1. Configurar la Base de Datos

#### Opción A: XAMPP/WAMP (Recomendado para desarrollo)
1. Iniciar XAMPP/WAMP
2. Abrir phpMyAdmin (http://localhost/phpmyadmin)
3. Ejecutar el script `database/create_database.sql`

#### Opción B: MySQL Command Line
```bash
mysql -u root -p < database/create_database.sql
```

### 2. Configurar Conexión

Editar el archivo `includes/database.php` y ajustar los parámetros de conexión:

```php
private $host = 'localhost';
private $database = 'usi_floral_db';
private $username = 'root';      // Tu usuario de MySQL
private $password = '';          // Tu contraseña de MySQL
```

### 3. Verificar Permisos

Asegurar que la carpeta del proyecto tenga permisos de lectura/escritura para el servidor web.

## Estructura de la Base de Datos

### Tablas Principales:
- **categories**: Categorías de productos
- **products**: Productos con toda su información
- **users**: Usuarios/compradores B2B
- **orders**: Pedidos de los usuarios
- **order_items**: Items individuales de cada pedido
- **cart_items**: Carrito de compras temporal
- **quotes**: Cotizaciones solicitadas
- **quote_items**: Items de cada cotización

### Datos de Prueba Incluidos:

#### Productos:
- Premium Silk Roses (Red, White, Pink)
- Hydrangea Clusters (Blue, Purple)
- Peony Arrangements (Coral, Blush)
- Lily Collection (White, Orange)
- Sunflower Bunches
- Orchid Sprays (Purple, White)

#### Usuarios:
- **admin**: Usuario administrador
  - Email: admin@usifloral.com
  - Password: password

- **testbuyer**: Usuario comprador de prueba
  - Email: buyer@example.com
  - Password: password
  - Descuento: 15%

## Funcionalidades de la Base de Datos

### Para Productos:
✅ Filtrado por categoría
✅ Búsqueda de texto completo
✅ Ordenamiento por precio/nombre/categoría
✅ Paginación
✅ Productos destacados
✅ Control de stock
✅ Precios mayoristas
✅ Cantidad mínima por producto

### Para Usuarios:
✅ Registro B2B con validación
✅ Sistema de descuentos por usuario
✅ Tipos de negocio
✅ Estados de aprobación

### Para Carritos y Pedidos:
✅ Carrito persistente por usuario
✅ Sistema de pedidos completo
✅ Seguimiento de estados
✅ Historial de compras

### Para Cotizaciones:
✅ Solicitud de cotizaciones
✅ Seguimiento de estados
✅ Validez temporal

## Índices y Optimizaciones

La base de datos incluye índices optimizados para:
- Búsquedas rápidas por categoría
- Búsqueda de texto completo en productos
- Consultas por usuario
- Ordenamiento por precios
- Filtrado por estado activo

## Vistas Incluidas

### products_with_category:
Vista optimizada que combina productos con información de categoría.

### product_stats:
Vista con estadísticas por categoría (conteos, precios promedio, etc.).

## Seguridad

- Uso de prepared statements (PDO)
- Validación de tipos de datos
- Sanitización de entrada
- Control de acceso por sesiones
- Manejo seguro de errores

## Extensibilidad

La estructura está diseñada para ser fácilmente extensible:

### Agregar Nuevos Campos:
```sql
ALTER TABLE products ADD COLUMN new_field VARCHAR(255);
```

### Agregar Nuevas Categorías:
```sql
INSERT INTO categories (name, slug, description) VALUES ('Nueva Categoría', 'nueva-categoria', 'Descripción');
```

### Agregar Nuevos Productos:
```sql
INSERT INTO products (name, slug, description, category_id, price, min_quantity, sku, active) 
VALUES ('Nuevo Producto', 'nuevo-producto', 'Descripción', 1, 25.99, 6, 'SKU-001', TRUE);
```

## Respaldo y Mantenimiento

### Crear Respaldo:
```bash
mysqldump -u root -p usi_floral_db > backup_$(date +%Y%m%d).sql
```

### Restaurar Respaldo:
```bash
mysql -u root -p usi_floral_db < backup_file.sql
```

### Limpieza de Carritos Antiguos:
```sql
DELETE FROM cart_items WHERE updated_at < DATE_SUB(NOW(), INTERVAL 30 DAY);
```

## Monitoreo

### Productos Más Populares:
```sql
SELECT p.name, SUM(oi.quantity) as total_sold 
FROM products p 
JOIN order_items oi ON p.id = oi.product_id 
GROUP BY p.id 
ORDER BY total_sold DESC 
LIMIT 10;
```

### Usuarios Más Activos:
```sql
SELECT u.company_name, COUNT(o.id) as total_orders 
FROM users u 
JOIN orders o ON u.id = o.user_id 
GROUP BY u.id 
ORDER BY total_orders DESC;
```

## Solución de Problemas

### Error de Conexión:
1. Verificar que MySQL esté corriendo
2. Comprobar credenciales en `database.php`
3. Verificar que la base de datos existe

### Productos No Aparecen:
1. Verificar que `active = TRUE` en productos y categorías
2. Comprobar que existen datos en las tablas
3. Revisar logs de errores de PHP

### Errores de Permisos:
1. Verificar permisos del usuario de MySQL
2. Comprobar que el usuario puede crear/modificar tablas
3. Verificar permisos de archivos del servidor web

Para más ayuda, revisar los logs de errores en:
- PHP error log
- MySQL error log
- Apache/Nginx error log