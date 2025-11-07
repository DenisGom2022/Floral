<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - Flower Collection</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
/* Reset y Base */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', sans-serif;
    line-height: 1.6;
    color: #333;
    background-color: #fff;
    overflow-x: hidden;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Utility Classes */
.text-center { text-align: center; }
.mb-20 { margin-bottom: 20px; }
.mb-40 { margin-bottom: 40px; }
.mt-20 { margin-top: 20px; }
.mt-40 { margin-top: 40px; }

/* Botones */
.btn {
    display: inline-block;
    padding: 12px 24px;
    text-decoration: none;
    border-radius: 6px;
    font-weight: 500;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    text-align: center;
}

.btn-primary {
    background-color: #2d5a27;
    color: white;
}

.btn-primary:hover {
    background-color: #1e3d1c;
}

.btn-secondary {
    background-color: #f8f9fa;
    color: #2d5a27;
    border: 1px solid #2d5a27;
}

.btn-secondary:hover {
    background-color: #2d5a27;
    color: white;
}

.btn-login {
    background-color: transparent;
    color: #2d5a27;
    border: 1px solid #2d5a27;
    padding: 8px 16px;
}

.btn-login:hover {
    background-color: #2d5a27;
    color: white;
}

/* Header Enhanced */
.header {
    position: sticky;
    top: 0;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    z-index: 1000;
    border-bottom: 1px solid rgba(45, 90, 39, 0.1);
}

.header-main {
    padding: 20px 0;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo h1 {
    color: #2d5a27;
    font-size: 1.8rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0;
}

.logo h1 i {
    font-size: 2rem;
    color: #4a7c59;
}

.logo .subtitle {
    font-size: 0.9rem;
    color: #666;
    margin-top: 2px;
    font-weight: 400;
}

.main-nav ul {
    display: flex;
    list-style: none;
    gap: 30px;
    margin: 0;
    padding: 0;
}

.main-nav a {
    text-decoration: none;
    color: #555;
    font-weight: 500;
    font-size: 1.1rem;
    padding: 10px 20px;
    border-radius: 25px;
    transition: all 0.3s ease;
    position: relative;
}

.main-nav a:hover {
    color: #2d5a27;
    background: rgba(45, 90, 39, 0.1);
}

.main-nav a.active {
    color: white;
    background: linear-gradient(135deg, #2d5a27, #4a7c59);
    font-weight: 600;
}

.main-nav a.active::before {
    content: '';
    position: absolute;
    top: -5px;
    left: 50%;
    transform: translateX(-50%);
    width: 6px;
    height: 6px;
    background: #4a7c59;
    border-radius: 50%;
}

/* Main Content Enhanced */
.main-content {
    min-height: 60vh;
    background: white;
    position: relative;
}

/* Page Header */
.page-header {
    text-align: center;
    margin: 40px 0;
}

.page-header h2 {
    color: #2d5a27;
    font-size: 2.5rem;
    margin-bottom: 10px;
}

.page-header p {
    color: #666;
    font-size: 1.1rem;
}

/* About Page */
.about-page {
    margin: 40px 0;
}

.about-content {
    margin-top: 40px;
}

.about-grid {
    display: grid;
    gap: 40px;
}

.about-section {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}

.section-header h3 {
    color: #2d5a27;
    font-size: 1.5rem;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
}

.section-header h3 i {
    margin-right: 12px;
}

.section-content p {
    margin-bottom: 20px;
    line-height: 1.7;
    color: #555;
}

.passion-points {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 30px;
}

.passion-item {
    text-align: center;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 12px;
}

.passion-item i {
    font-size: 2.5rem;
    color: #2d5a27;
    margin-bottom: 15px;
}

.passion-item h4 {
    color: #2d5a27;
    margin-bottom: 10px;
}

.purpose-list {
    list-style: none;
}

.purpose-list li {
    display: flex;
    align-items: flex-start;
    margin-bottom: 20px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
}

.purpose-list li i {
    color: #2d5a27;
    margin-right: 15px;
    margin-top: 2px;
    flex-shrink: 0;
}

.tech-stack {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.tech-item {
    text-align: center;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 12px;
}

.tech-item i {
    font-size: 2.5rem;
    color: #2d5a27;
    margin-bottom: 15px;
}

.tech-item h4 {
    color: #2d5a27;
    margin-bottom: 10px;
}

.learning-timeline {
    margin-top: 20px;
}

.timeline-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 25px;
}

.timeline-icon {
    background: #2d5a27;
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 20px;
    flex-shrink: 0;
}

.timeline-content h4 {
    color: #2d5a27;
    margin-bottom: 5px;
}

.future-features {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
    margin: 20px 0;
}

.feature-item {
    display: flex;
    align-items: center;
    padding: 12px;
    background: #e8f5e8;
    border-radius: 8px;
}

.feature-item i {
    color: #2d5a27;
    margin-right: 12px;
}

.motivation-quote {
    margin-top: 30px;
    text-align: center;
    padding: 30px;
    background: linear-gradient(135deg, #e8f5e8, #f0f8f0);
    border-radius: 12px;
}

.motivation-quote blockquote {
    font-style: italic;
    font-size: 1.1rem;
    color: #2d5a27;
    margin-bottom: 15px;
}

.motivation-quote cite {
    color: #666;
    font-size: 0.9rem;
}

/* Footer */
.footer {
    background-color: #2d5a27;
    color: white;
    padding: 60px 0 30px;
}

.footer-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 40px;
    margin-bottom: 40px;
}

.footer-section h4 {
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 20px;
}

.footer-section ul {
    list-style: none;
}

.footer-section ul li {
    margin-bottom: 10px;
}

.footer-section a {
    color: #ccc;
    text-decoration: none;
    transition: color 0.3s ease;
}

.footer-section a:hover {
    color: white;
}

.footer-section p {
    color: #ccc;
    line-height: 1.6;
}

.footer-bottom {
    border-top: 1px solid #4a7c43;
    padding-top: 30px;
    text-align: center;
}

.footer-bottom p {
    color: #ccc;
    margin-bottom: 10px;
}

.footer-bottom a {
    color: white;
    text-decoration: none;
}

.footer-bottom a:hover {
    text-decoration: underline;
}

/* Responsive Design */
@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        gap: 20px;
    }
    
    .main-nav ul {
        flex-wrap: wrap;
        justify-content: center;
        gap: 15px;
    }
    
    .page-header h2 {
        font-size: 2rem;
    }
    
    .passion-points,
    .tech-stack,
    .future-features {
        grid-template-columns: 1fr;
    }
    
    .about-section {
        padding: 20px;
    }
}

@media (max-width: 480px) {
    .container {
        padding: 0 15px;
    }
    
    .logo h1 {
        font-size: 1.5rem;
    }
    
    .main-nav ul {
        gap: 10px;
    }
    
    .main-nav a {
        font-size: 0.9rem;
        padding: 8px 15px;
    }
}
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-main">
            <div class="container">
                <div class="header-content">
                    <div class="logo">
                        <h1><i class="fas fa-seedling"></i> Botanical Collection</h1>
                        <p class="subtitle">Educational Flower Database</p>
                    </div>
                    
                    <nav class="main-nav">
                        <ul>
                            <li><a href="index.php">Catalog</a></li>
                            <li><a href="about.php" class="active">About</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="about-page">
                <!-- Page Header -->
                <div class="page-header">
                    <h2><i class="fas fa-heart"></i> About This Project</h2>
                    <p>An application developed with love for flowers and learning</p>
                </div>

                <!-- About Content -->
                <div class="about-content">
                    <div class="about-grid">
                        <!-- Personal Section -->
                        <div class="about-section">
                            <div class="section-header">
                                <h3><i class="fas fa-user"></i> About the Developer</h3>
                            </div>
                            <div class="section-content">
                                <p>Hello! I'm <strong>Luis</strong>, and I've developed this application with lots of love and dedication. As someone who absolutely loves flowers and nature, I decided to combine my passion for programming with my love for the floral world.</p>
                                
                                <p>This project draws inspiration from the beautiful floral diversity found across <strong>the United States</strong>, from the wildflowers of California to the roses of Oregon and the native species of the Great Plains. The rich botanical heritage of America serves as a constant source of inspiration for this collection.</p>
                                
                                <p>Flowers have always been a source of inspiration for me. Their beauty, diversity, and the way they can brighten up any space fascinates me enormously. Every time I see a flower, I can't help but admire the unique details that make it special.</p>
                                
                                <div class="passion-points">
                                    <div class="passion-item">
                                        <i class="fas fa-flower"></i>
                                        <h4>Love for Flowers</h4>
                                        <p>I love discovering new species and learning about their unique characteristics</p>
                                    </div>
                                    
                                    <div class="passion-item">
                                        <i class="fas fa-code"></i>
                                        <h4>Passion for Code</h4>
                                        <p>I enjoy creating applications that are useful and educational</p>
                                    </div>
                                    
                                    <div class="passion-item">
                                        <i class="fas fa-graduation-cap"></i>
                                        <h4>Continuous Learning</h4>
                                        <p>I always seek to learn something new, both in technology and botany</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Project Purpose -->
                        <div class="about-section">
                            <div class="section-header">
                                <h3><i class="fas fa-seedling"></i> Project Purpose</h3>
                            </div>
                            <div class="section-content">
                                <p>This application was born as an educational project with multiple objectives:</p>
                                
                                <ul class="purpose-list">
                                    <li>
                                        <i class="fas fa-book-open"></i>
                                        <strong>Programming Learning:</strong> 
                                        Practice and improve my skills in PHP, MySQL and web development in general
                                    </li>
                                    
                                    <li>
                                        <i class="fas fa-database"></i>
                                        <strong>Database Management:</strong> 
                                        Experiment with different data structures and query optimization
                                    </li>
                                    
                                    <li>
                                        <i class="fas fa-paint-brush"></i>
                                        <strong>Design and UX:</strong> 
                                        Create a friendly and visually attractive interface
                                    </li>
                                    
                                    <li>
                                        <i class="fas fa-share-alt"></i>
                                        <strong>Share Knowledge:</strong> 
                                        Create a platform where others can learn about flowers
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Technology Stack -->
                        <div class="about-section">
                            <div class="section-header">
                                <h3><i class="fas fa-tools"></i> Technologies Used</h3>
                            </div>
                            <div class="section-content">
                                <div class="tech-stack">
                                    <div class="tech-item">
                                        <i class="fab fa-php"></i>
                                        <h4>PHP</h4>
                                        <p>Backend and server logic</p>
                                    </div>
                                    
                                    <div class="tech-item">
                                        <i class="fas fa-database"></i>
                                        <h4>MySQL</h4>
                                        <p>Database for storing information</p>
                                    </div>
                                    
                                    <div class="tech-item">
                                        <i class="fab fa-html5"></i>
                                        <h4>HTML5 & CSS3</h4>
                                        <p>Structure and responsive design</p>
                                    </div>
                                    
                                    <div class="tech-item">
                                        <i class="fab fa-js"></i>
                                        <h4>JavaScript</h4>
                                        <p>Frontend interactivity</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Learning Journey -->
                        <div class="about-section">
                            <div class="section-header">
                                <h3><i class="fas fa-route"></i> My Learning Journey</h3>
                            </div>
                            <div class="section-content">
                                <p>During the development of this application, I have learned a tremendous amount:</p>
                                
                                <div class="learning-timeline">
                                    <div class="timeline-item">
                                        <div class="timeline-icon">
                                            <i class="fas fa-lightbulb"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h4>Planning</h4>
                                            <p>Design the database structure and application architecture</p>
                                        </div>
                                    </div>
                                    
                                    <div class="timeline-item">
                                        <div class="timeline-icon">
                                            <i class="fas fa-code"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h4>Development</h4>
                                            <p>Implement features step by step, from basics to advanced characteristics</p>
                                        </div>
                                    </div>
                                    
                                    <div class="timeline-item">
                                        <div class="timeline-icon">
                                            <i class="fas fa-bug"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h4>Debugging</h4>
                                            <p>Solve problems and optimize application performance</p>
                                        </div>
                                    </div>
                                    
                                    <div class="timeline-item">
                                        <div class="timeline-icon">
                                            <i class="fas fa-heart"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h4>Refinement</h4>
                                            <p>Polish details and ensure an excellent user experience</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact & Future -->
                        <div class="about-section">
                            <div class="section-header">
                                <h3><i class="fas fa-rocket"></i> Project Future</h3>
                            </div>
                            <div class="section-content">
                                <p>This project is a work in progress and I have many ideas to improve it:</p>
                                
                                <div class="future-features">
                                    <div class="feature-item">
                                        <i class="fas fa-search"></i>
                                        <span>Advanced search by characteristics</span>
                                    </div>
                                    
                                    <div class="feature-item">
                                        <i class="fas fa-heart"></i>
                                        <span>Favorites system</span>
                                    </div>
                                    
                                    <div class="feature-item">
                                        <i class="fas fa-camera"></i>
                                        <span>Enhanced gallery with more images</span>
                                    </div>
                                    
                                    <div class="feature-item">
                                        <i class="fas fa-book"></i>
                                        <span>Detailed botanical information</span>
                                    </div>
                                </div>
                                
                                <div class="motivation-quote">
                                    <blockquote>
                                        <i class="fas fa-quote-left"></i>
                                        "Every line of code is like planting a seed: with patience and care, it can grow into something beautiful."
                                        <i class="fas fa-quote-right"></i>
                                    </blockquote>
                                    <cite>- Luis, developer and flower lover</cite>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4><i class="fas fa-seedling"></i> Botanical Collection</h4>
                    <p>Educational database developed for learning purposes about different flower species and plants.</p>
                </div>
                
                <div class="footer-section">
                    <h4>Navigation</h4>
                    <ul>
                        <li><a href="index.php">Catalog</a></li>
                        <li><a href="about.php">About</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Purpose</h4>
                    <p>This application was created for educational purposes to study and learn about flowers and botany.</p>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2025 - Educational Flower Application</p>
                <p>Developed with love for flowers and learning 🌸</p>
            </div>
        </div>
    </footer>
</body>
</html>