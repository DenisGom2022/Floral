# 🌸 Colección de Flores - Aplicación Educativa

Una aplicación web desarrollada con amor por las flores y el aprendizaje, creada por **Luis** como proyecto educativo para explorar el mundo de la botánica y mejorar habilidades de programación.

## 🎯 Propósito

Esta aplicación fue creada con fines completamente educativos:
- **Aprender programación web** con PHP, MySQL, HTML5, CSS3 y JavaScript
- **Explorar el mundo de las flores** y compartir conocimiento botánico
- **Practicar desarrollo full-stack** con un proyecto real y funcional
- **Crear una herramienta útil** para estudiantes y entusiastas de las flores

## ✨ Funcionalidades

### 1. 📱 Catálogo de Flores
- Vista de cards responsive con diseño moderno
- Información básica de cada flor (nombre, categoría, color, material)
- Grid adaptativo para diferentes tamaños de pantalla
- Navegación intuitiva con botones "Ver Detalles"

### 2. 🌺 Detalle de Flores
- Página individual para cada flor con información completa
- Galería de imágenes con thumbnails intercambiables
- Especificaciones técnicas detalladas
- Breadcrumb navigation para fácil navegación

### 3. 👨‍💻 Acerca de
- Historia personal del desarrollador y su pasión por las flores
- Documentación del proceso de aprendizaje
- Tecnologías utilizadas y arquitectura del proyecto
- Planes futuros y motivación detrás del desarrollo

## 🛠️ Tecnologías Utilizadas

- **Backend:** PHP 7.4+
- **Base de Datos:** MySQL 5.7+
- **Frontend:** HTML5, CSS3, JavaScript
- **Estilos:** CSS Grid, Flexbox, Responsive Design
- **Iconos:** Font Awesome 6.0
- **Fuentes:** Google Fonts (Inter)

## 📁 Estructura del Proyecto

```
floral/
├── 📄 index.php              # Catálogo principal de flores
├── 📄 flower-detail.php      # Página de detalle individual
├── 📄 about.php              # Página acerca de
├── 📁 css/
│   └── style.css             # Estilos principales
├── 📁 js/
│   └── script.js             # JavaScript del frontend
├── 📁 includes/
│   └── database.php          # Conexión y funciones de BD
├── 📁 database/
│   └── create_database.sql   # Script de creación de BD
└── 📄 README.md              # Este archivo
```

## 🚀 Instalación y Configuración

### Prerrequisitos
- Servidor web con PHP 7.4+
- MySQL 5.7+ o MariaDB
- Apache/Nginx

### Pasos de Instalación

1. **Clonar el repositorio**
   ```bash
   git clone <url-del-repo>
   cd floral
   ```

2. **Configurar la base de datos**
   ```bash
   mysql -u root -p < database/create_database.sql
   ```

3. **Configurar conexión a BD**
   Editar `includes/database.php` con tus credenciales:
   ```php
   private $host = 'localhost';
   private $dbname = 'floral_db';
   private $username = 'tu_usuario';
   private $password = 'tu_password';
   ```

4. **Ejecutar en servidor web**
   - Copiar archivos al directorio web
   - Acceder a `http://localhost/floral/`

## 🌱 Base de Datos

### Estructura Simplificada
- **categories** - Categorías de flores
- **products** - Información de flores y plantas

### Características
- ✅ Estructura simple y eficiente
- ✅ Compatible con cualquier servicio MySQL
- ✅ Sin vistas SQL para máxima compatibilidad
- ✅ Datos de ejemplo incluidos

## 🎨 Diseño y UX

### Principios de Diseño
- **Simplicidad:** Interfaz limpia y minimalista
- **Responsividad:** Adaptable a todos los dispositivos
- **Accesibilidad:** Uso de colores contrastantes y navegación clara
- **Naturalidad:** Paleta de colores inspirada en la naturaleza

### Paleta de Colores
- **Verde Principal:** #2d5a27 (naturaleza y crecimiento)
- **Verde Claro:** #e8f5e8 (frescura y tranquilidad)
- **Grises:** #f8f9fa, #666 (neutralidad y legibilidad)

## 📚 Aprendizajes del Proyecto

Durante el desarrollo de esta aplicación, se exploraron:

- **PHP Orientado a Objetos** - Clases ProductManager y CategoryManager
- **Arquitectura MVC** - Separación de lógica, presentación y datos
- **Diseño Responsive** - CSS Grid y Flexbox para adaptabilidad
- **Optimización de Consultas** - Funciones PHP en lugar de vistas SQL
- **UX/UI Design** - Creación de interfaces intuitivas y atractivas

## 🔮 Planes Futuros

### Características Planeadas
- 🔍 **Búsqueda avanzada** por características específicas
- ❤️ **Sistema de favoritos** para guardar flores preferidas
- 📸 **Galería mejorada** con más imágenes por producto
- 📖 **Información botánica** detallada y científica
- 🏷️ **Etiquetado avanzado** con filtros múltiples

## 💝 Mensaje del Desarrollador

> "Cada línea de código es como plantar una semilla: con paciencia y cuidado, puede crecer en algo hermoso."
> 
> *- Luis, desarrollador y amante de las flores*

Este proyecto representa no solo mis habilidades técnicas, sino también mi pasión por la naturaleza y el aprendizaje continuo. Espero que esta aplicación inspire a otros a explorar tanto el mundo de la programación como la belleza de las flores.

## 🤝 Contribuciones

Aunque este es un proyecto personal de aprendizaje, las sugerencias y comentarios son siempre bienvenidos. Si encuentras este proyecto útil o inspirador, ¡me encantaría escuchar tu experiencia!

## 📜 Licencia

Este proyecto fue creado con fines educativos. Siéntete libre de usar el código como referencia para tus propios proyectos de aprendizaje.

---

**Desarrollado con 💚 por Luis**  
*Una aplicación donde la tecnología se encuentra con la belleza natural*