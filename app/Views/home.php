<?= view('templates/head') ?>
<?= view('templates/navbar') ?>

<header class="hero-section">
  <div class="hero-content">
    <h1><i class="fas fa-graduation-cap"></i> Instituto Superior de Formación Docente y Técnica N 57</h1>
    <p class="hero-subtitle">El Instituto ofrece carreras docentes y técnicas del Nivel Superior.</p>
    <a href="<= base_url('estudiantes')>" class="hero-button">Comenzar ahora <i class="fas fa-arrow-right"></i></a>
  </div>
</header>

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
        <p>Clasifica las carreras por áreas de conocimiento para mejor gestión.</p>
        <a href="<?= base_url('categorias') ?>" class="step-link">Ir a Categorías <i class="fas fa-arrow-right"></i></a>
      </div>
    </div>
  </div>
</section>

<?= view('templates/footer') ?>