<?= $this->extend('templates/layout') ?> 

<?= $this->section('content') ?>

<header class="hero-section">
  <div class="hero-content">
    <h1><i class="fas fa-graduation-cap"></i> Instituto Superior de Formación Docente y Técnica N 57</h1>
    <p class="hero-subtitle">El Instituto ofrece carreras docentes y técnicas del Nivel Superior.</p>
    <a href="<= base_url('estudiantes')>" class="hero-button">Comenzar ahora <i class="fas fa-arrow-right"></i></a>
  </div>
</header>

<section class="about-us-section">
    <div class="container about-content-grid">
        <div class="text-content">
            <h2 class="section-title">Un compromiso con la formación y el crecimiento profesional</h2>
            <p>Trabajamos de la mano con el sector empresarial para ofrecerte las herramientas que necesitás para crecer personal y profesionalmente.</p>
        </div>
        
        <div class="video-container">
            <video controls class="local-video">
                
        <source src="<?= base_url('videos/practicas.mp4') ?>" type="video/mp4">
        
        <source src="<?= base_url('videos/practicas.webm') ?>" type="video/webm">
                Tu navegador no soporta la etiqueta de video.
            </video>
            <p class="video-caption">Video: Prácticas Profesionalizantes - TNSHyS</p>
        </div>
</section>

<section class="university-gallery">
    <h2 class="section-title">Nuestro entorno académico</h2>
    <div class="glider-container">
        <div class="glider">
          <div class="slide">
            <img src="img/img_001.png" alt="Grupo de estudiantes en clase presencial">
            <div class="slide-caption">Reflexión y debate en el aula</div>
          </div>
       <div class="slide">
            <img src="img/img_002.jpg" alt="Maniquí de bebé para práctica de RCP o primeros auxilios">
            <div class="slide-caption">Prácticas de Salud y RCP</div>
          </div>
       <div class="slide">
         <img src="img/img_003.jpg" alt="Estudiantes conversando en un patio exterior en blanco y negro con árbol amarillo">
         <div class="slide-caption">Encuentros en el campus</div>
         </div>
       <div class="slide">
          <img src="img/img_004.jpg" alt="Estudiantes en reunión de trabajo colaborativo o tutoría">
          <div class="slide-caption">Tutorías y trabajo colaborativo</div>
        </div>
       <div class="slide">
         <img src="img/img_005.jpg" alt="Docente leyendo un libro a grupo de estudiantes en un espacio de primera infancia">
         <div class="slide-caption">Seminarios y talleres de lectura</div>
       </div>
       <div class="slide">
         <img src="img/img_006.jpg" alt="Reunión de profesores en aula con proyector encendido">
         <div class="slide-caption">Formación docente continua</div>
        </div>
       <div class="slide">
         <img src="img/img_007.jpg" alt="Tres mujeres sosteniendo el logo del ISFDyT 57 Juana Paula Manso">
         <div class="slide-caption">Identidad Institucional</div>
        </div>
       <div class="slide">
        <img src="img/img_008.jpg" alt="Material didáctico para enseñar vocales y palabras a niños pequeños">
        <div class="slide-caption">Recursos de Pedagogía y Didáctica</div>
    </div>
</div>
        </div>
        <button aria-label="Previous" class="glider-prev"><i class="fas fa-chevron-left"></i></button>
        <button aria-label="Next" class="glider-next"><i class="fas fa-chevron-right"></i></button>
        <div role="tablist" class="dots"></div>
    </div>
</section>

<section class="steps-section">
  <div class="container">
    <h2 class="section-title"><i class="fas fa-magic"></i> Cómo usar la aplicación</h2>
    <div class="steps-grid">
      <div class="step-card">
        <div class="step-number">1</div>
        <h3>Gestiona Estudiantes</h3>
        <p>Registra nuevos alumnos, actualiza sus datos y realiza seguimiento académico.</p>
        <a href="<?= base_url('estudiantes') ?>" class="step-link">Ir a Estudiantes <i class="fas fa-arrow-right"></i></a>
      </div>
      <div class="step-card">
        <div class="step-number">2</div>
        <h3>Administra Carreras</h3>
        <p>Crea y organiza las carreras académicas ofrecidas por tu institución.</p>
        <a href="<?= base_url('carreras') ?>" class="step-link">Ir a Carreras <i class="fas fa-arrow-right"></i></a>
      </div>
      <div class="step-card">
        <div class="step-number">3</div>
        <h3>Organiza Categorías</h3>
        <p>Clasifica  las carreras por áreas de conocimiento para mejor gestión.</p>
        <a href="<?= base_url('categorias') ?>" class="step-link">Ir a Categorías <i class="fas fa-arrow-right"></i></a>
      </div>
    </div>
  </div> 
</section>

<?= $this->endSection() ?> 