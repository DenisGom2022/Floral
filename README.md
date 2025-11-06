# USI Floral Imports - Sitio Web PHP

Una recreación del sitio web de USI Floral Imports (https://www.usifloral.com/) construida con PHP, HTML, CSS y JavaScript.

## Características

### ✅ Funcionalidades Implementadas
- **Página principal** con diseño responsivo similar al original
- **Sistema de autenticación** (Login/Logout)
- **Registro de usuarios B2B** con validación
- **Dashboard de usuario** con estadísticas y acciones rápidas
- **Catálogo de productos** con filtros y categorías
- **Página de contacto** con formulario funcional
- **Diseño responsive** compatible con móviles y tablets
- **Navegación moderna** con menú hamburguesa en móvil

### 🎨 Diseño
- Esquema de colores verde (#2d5a27) similar al original
- Tipografía Inter para un look moderno
- Iconos Font Awesome para elementos visuales
- Animaciones CSS suaves
- Grid layouts responsivos

### 📱 Responsive Design
- Compatible con dispositivos móviles
- Menú hamburguesa en pantallas pequeñas
- Grid adaptativo para productos
- Formularios optimizados para touch

## Estructura de Archivos

```
floral/
├── index.php              # Página principal
├── login.php              # Sistema de login
├── logout.php             # Cerrar sesión
├── registration.php       # Registro de usuarios B2B
├── dashboard.php          # Panel de usuario logueado
├── contact.php            # Página de contacto
├── floral.php             # Catálogo de productos florales
├── css/
│   └── style.css          # Estilos principales
└── js/
    └── script.js          # JavaScript interactivo
```

## Instalación y Configuración

### Requisitos
- Servidor web con PHP 7.4 o superior
- Apache o Nginx
- (Opcional) MySQL para base de datos

### Instalación Local

1. **Servidor Local (XAMPP/WAMP/MAMP):**
   ```bash
   # Copiar archivos a la carpeta del servidor
   # XAMPP: C:\xampp\htdocs\floral\
   # WAMP: C:\wamp64\www\floral\
   ```

2. **Acceder al sitio:**
   ```
   http://localhost/floral/
   ```

### Credenciales de Prueba

**Usuario de prueba:**
- **Usuario:** admin
- **Contraseña:** password

## Funcionalidades por Página

### 🏠 Página Principal (index.php)
- Hero section con call-to-action
- Sección de productos navideños
- Información de la empresa
- Servicios destacados (Diseño, Importación, Mayoreo, etc.)
- FAQ section
- Footer completo

### 🔐 Login (login.php)
- Formulario de autenticación
- Validación de credenciales
- Redirección al dashboard
- Enlace a registro

### 📝 Registro (registration.php)
- Formulario completo B2B
- Validación de campos requeridos
- Información sobre requisitos
- Proceso de verificación simulado

### 📊 Dashboard (dashboard.php)
- Estadísticas de cuenta
- Pedidos recientes
- Acciones rápidas
- Productos populares
- Información de cuenta

### 📞 Contacto (contact.php)
- Formulario de contacto funcional
- Información de contacto
- Horarios de atención
- Información de servicios

### 🌸 Catálogo (floral.php)
- Grid de productos
- Filtros por categoría
- Ordenamiento de productos
- Vista previa de productos
- Solicitud de cotizaciones

## Características Técnicas

### Backend (PHP)
- Manejo de sesiones
- Validación de formularios
- Redirecciones de seguridad
- Estructura modular

### Frontend
- CSS Grid y Flexbox
- Animaciones CSS
- JavaScript vanilla (sin dependencias)
- Intersection Observer API
- Responsive design

### Seguridad
- Validación de entrada con `htmlspecialchars()`
- Verificación de sesiones
- Protección contra XSS básica
- Sanitización de datos

## Personalización

### Cambiar Colores
Editar variables CSS en `css/style.css`:
```css
:root {
    --primary-color: #2d5a27;
    --primary-light: #4a7c43;
    --primary-dark: #1e3d1c;
}
```

### Agregar Productos
Modificar el array `$products` en `floral.php`:
```php
$products = [
    [
        'id' => 7,
        'name' => 'Nuevo Producto',
        'category' => 'Categoria',
        'price' => '$XX.XX',
        'min_qty' => 'X stems',
        'image' => 'ruta/imagen.jpg',
        'description' => 'Descripción del producto'
    ]
];
```

### Configurar Base de Datos
Para implementar una base de datos real:

1. Crear tabla de usuarios:
```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    email VARCHAR(100),
    password VARCHAR(255),
    company_name VARCHAR(100),
    business_license VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

2. Crear tabla de productos:
```sql
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    category VARCHAR(50),
    price DECIMAL(10,2),
    min_qty VARCHAR(20),
    image_url VARCHAR(255),
    description TEXT,
    active BOOLEAN DEFAULT TRUE
);
```

## Funcionalidades Futuras

### 🚀 Mejoras Sugeridas
- **Base de datos MySQL** para persistencia
- **Sistema de carrito** de compras
- **Gestión de pedidos** completa
- **Panel de administración**
- **API REST** para móvil
- **Integración de pagos** (Stripe/PayPal)
- **Sistema de notificaciones**
- **Chat en vivo**
- **Subida de imágenes** de productos
- **Sistema de reviews**

### 🔧 Optimizaciones Técnicas
- Implementar **caché** de PHP
- Optimizar **imágenes** con WebP
- Implementar **CDN** para assets
- **Minificar** CSS/JS
- **Lazy loading** para imágenes
- **Service Workers** para PWA

## Soporte de Navegadores

### ✅ Compatibilidad
- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+
- Opera 76+

### 📱 Dispositivos Móviles
- iOS Safari 14+
- Chrome Mobile 90+
- Samsung Internet 14+

## Contribución

Para contribuir al proyecto:

1. Fork el repositorio
2. Crear una rama feature (`git checkout -b feature/nueva-funcionalidad`)
3. Commit los cambios (`git commit -am 'Agregar nueva funcionalidad'`)
4. Push a la rama (`git push origin feature/nueva-funcionalidad`)
5. Crear un Pull Request

## Licencia

Este proyecto es una recreación educativa del sitio USI Floral Imports. 
Todos los derechos de la marca y contenido original pertenecen a USI Floral Imports.

## Contacto

Para preguntas sobre el código o implementación, contactar al desarrollador.

---

**Versión:** 1.0  
**Fecha:** Noviembre 2024  
**Desarrollado con:** PHP, HTML5, CSS3, JavaScript  
**Compatible:** Todos los navegadores modernos