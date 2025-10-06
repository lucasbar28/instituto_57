<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>🎓 StudentApp - Gestión Académica</title>
  <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/glider-js/1.7.8/glider.min.css">
</head>
<body>
<!-- Barra de navegación -->  
<nav class="navbar">
  <div class="navbar-container">
    <a href="index.html" class="navbar-logo">🎓 StudentApp</a>
    <div class="navbar-links">
      <a href="estudiantes.html">Estudiantes</a>
      <a href="carreras.html">Carreras</a>
      <a href="categorias-carrera.html">Categorías</a>
    </div>
  </div>
</nav>
<!-- Sección de encabezado -->
<header class="hero-section">
  <div class="hero-content">
    <h1><i class="fas fa-graduation-cap"></i> Bienvenido a StudentApp</h1>
    <p class="hero-subtitle">La plataforma definitiva para la gestión académica universitaria</p>
    <a href="estudiantes.html" class="hero-button">Comenzar ahora <i class="fas fa-arrow-right"></i></a>
  </div>
</header>

<!-- Carrusel de Imágenes -->
<section class="university-gallery">
  <h2 class="section-title">Nuestro entorno académico</h2>
  <div class="glider-container">
    <div class="glider">
      <div class="slide">
        <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?auto=format&fit=crop&w=1200&q=80" 
             alt="Estudiantes en biblioteca">
        <div class="slide-caption">Ambiente de estudio colaborativo</div>
      </div>
      <div class="slide">
        <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1200&q=80" 
             alt="Grupo de estudiantes">
        <div class="slide-caption">Trabajo en equipo</div>
      </div>
      <div class="slide">
        <img src="https://images.unsplash.com/photo-1541178735493-479c1a27ed24?auto=format&fit=crop&w=1200&q=80" 
             alt="Aula universitaria">
        <div class="slide-caption">Aulas modernas</div>
      </div>
    </div>
    <button aria-label="Previous" class="glider-prev"><i class="fas fa-chevron-left"></i></button>
    <button aria-label="Next" class="glider-next"><i class="fas fa-chevron-right"></i></button>
    <div role="tablist" class="dots"></div>
  </div>
</section>

<!-- Pasos de uso -->
<section class="steps-section">
  <div class="container">
    <h2 class="section-title"><i class="fas fa-magic"></i> Cómo usar la aplicación</h2>
    <div class="steps-grid">
      <div class="step-card">
        <div class="step-number">1</div>
        <h3>Gestiona Estudiantes</h3>
        <p>Registra nuevos alumnos, actualiza sus datos y realiza seguimiento académico.</p>
        <a href="estudiantes.html" class="step-link">Ir a Estudiantes <i class="fas fa-arrow-right"></i></a>
      </div>
      <div class="step-card">
        <div class="step-number">2</div>
        <h3>Administra Carreras</h3>
        <p>Crea y organiza las carreras académicas ofrecidas por tu institución.</p>
        <a href="carreras.html" class="step-link">Ir a Carreras <i class="fas fa-arrow-right"></i></a>
      </div>
      <div class="step-card">
        <div class="step-number">3</div>
        <h3>Organiza Categorías</h3>
        <p>Clasifica las carreras por áreas de conocimiento para mejor gestión.</p>
        <a href="categorias-carrera.html" class="step-link">Ir a Categorías <i class="fas fa-arrow-right"></i></a>
      </div>
    </div>
  </div>
</section>
<!-- Sección de contacto -->
<footer class="main-footer">
  <div class="footer-content">
    <p>© 2025 StudentApp. Todos los derechos reservados.</p>
    <div class="social-links">
      <a href="#"><i class="fab fa-twitter"></i></a>
      <a href="#"><i class="fab fa-facebook"></i></a>
      <a href="#"><i class="fab fa-instagram"></i></a>
    </div>
  </div>
</footer>

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/glider-js/1.7.8/glider.min.js"></script>
<script src="app.js"></script>
</body>
</html>