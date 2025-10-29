<?= $this->extend('templates/layout') ?> 

<?= $this->section('content') ?>

<header class="hero-section">
  <div class="hero-content">
    <h1><i class="fas fa-graduation-cap"></i> Instituto Superior de Formación Docente y Técnica N° 57</h1>
    <p class="hero-subtitle">El Instituto ofrece carreras docentes y técnicas del Nivel Superior.</p>
    <a href="<?= base_url('estudiantes') ?>" class="hero-button">Comenzar ahora <i class="fas fa-arrow-right"></i></a>
  </div>
</header>

<section class="about-us-section">
    <div class="container about-content-grid"> 
        
        <div class="text-intro">
            <h2 class="section-title">Un compromiso con la formación y el crecimiento profesional</h2>
            <p>Trabajamos de la mano con el sector empresarial para ofrecerte las herramientas que necesitás para crecer personal y profesionalmente.</p>
        </div>
        
        <div class="video-container step-card">
            <video controls class="local-video">
                <source src="<?= base_url('videos/practicas.mp4') ?>" type="video/mp4">
                Tu navegador no soporta la etiqueta de video.
            </video>
            <p class="video-caption">Video: Prácticas Profesionalizantes - TNSHyS</p>
        </div>
    </div>
</section>

<section class="university-gallery">
  <h2 class="section-title">Nuestro entorno académico</h2>
  <div class="glider-container">
    <div class="glider">
      <div class="slide">
        <img src="<?= base_url('img/img_001.png') ?>" alt="Grupo de estudiantes en clase presencial">
        <div class="slide-caption">Reflexión y debate en el aula</div>
      </div>

      <div class="slide">
        <img src="<?= base_url('img/img_002.png') ?>" alt="Maniquí de bebé para práctica de RCP o primeros auxilios">
        <div class="slide-caption">Prácticas de Salud y RCP</div>
      </div>

      <div class="slide">
        <img src="<?= base_url('img/img_003.png') ?>" alt="Estudiantes conversando en un patio exterior">
        <div class="slide-caption">Encuentros en el campus</div>
      </div>

      <div class="slide">
        <img src="<?= base_url('img/img_004.png') ?>" alt="Estudiantes en reunión de trabajo colaborativo">
        <div class="slide-caption">Tutorías y trabajo colaborativo</div>
      </div>

      <div class="slide">
        <img src="<?= base_url('img/img_005.png') ?>" alt="Docente leyendo un libro a grupo de estudiantes">
        <div class="slide-caption">Seminarios y talleres de lectura</div>
      </div>

      <div class="slide">
        <img src="<?= base_url('img/img_006.png') ?>" alt="Reunión de profesores en aula con proyector">
        <div class="slide-caption">Formación docente continua</div>
      </div>

      <div class="slide">
        <img src="<?= base_url('img/img_007.png') ?>" alt="Tres mujeres sosteniendo el logo del instituto">
        <div class="slide-caption">Identidad Institucional</div>
      </div>

      <div class="slide">
        <img src="<?= base_url('img/img_008.png') ?>" alt="Material didáctico para enseñar vocales">
        <div class="slide-caption">Recursos de Pedagogía y Didáctica</div>
      </div>
    </div> <!-- .glider -->

    <button aria-label="Previous" class="glider-prev"><i class="fas fa-chevron-left"></i></button>
    <button aria-label="Next" class="glider-next"><i class="fas fa-chevron-right"></i></button>
    <div role="tablist" class="dots"></div>
  </div> <!-- .glider-container -->
</section>

<section class="steps-section">
  <div class="container">
    <h2 class="section-title"><i class="fas fa-magic"></i> Cómo usar la aplicación</h2>
    <div class="steps-grid">
      <div class="step-card">
        <div class="step-number">1</div>
        <h3>Gestiona Estudiantes</h3>
        <p>Registra nuevos alumnos, actualiza sus datos y realiza seguimiento académico.</p>
        <a href="<?= base_url('estudiantes') ?>" class="hero-button">Comenzar ahora <i class="fas fa-arrow-right"></i></a>

      </div>
      <div class="step-card">
        <div class="step-number">2</div>
        <h3>Administra Carreras</h3>
        <p>Crea y organiza las carreras académicas ofrecidas por tu institución.</p>
        <a href="<?= base_url('carreras') ?>" class="hero-button">Ir a Carreras <i class="fas fa-arrow-right"></i></a>
      </div>
      <div class="step-card">
        <div class="step-number">3</div>
        <h3>Organiza Categorías</h3>
        <p>Clasifica  las carreras por áreas de conocimiento para mejor gestión.</p>
        <a href="<?= base_url('categorias') ?>" class="hero-button">Ir a Categorías <i class="fas fa-arrow-right"></i></a>
      </div>
    </div>
  </div> 
</section>

  <!-- CONTENIDO --> 
<section class="steps-section">
  <div class="container">
    <h2 class="section-title">I.S.F.D. y T. N°57 “Juana Paula Manso”</h2>
    
    <!-- Imagen -->
    <img src="<?= base_url('img/instituto.png') ?>" alt="Foto del Instituto 57" class="img-fluid mb-4 img-instituto step-card">

    <p >El Instituto Superior de Formación Docente y Técnica N.º 57 "Juana Paula Manso" es una institución pública y gratuita ubicada en Chascomús, Buenos Aires. Forma parte de la Región Educativa XVII y depende de la Dirección General de Cultura y Educación de la Provincia. Ofrece una amplia gama de carreras docentes y técnicas de nivel superior, brindando títulos oficiales y de calidad.</p> 
    <p >Su historia se remonta a 1972, y desde entonces ha evolucionado para responder a las necesidades educativas de la comunidad, incluyendo extensiones en Ranchos y Lezama. Comprometido con la formación integral, el instituto promueve la innovación pedagógica y la inclusión social, consolidándose como un referente en la región.</p>
  </div>
</section>
<section class="steps-section">
  <div class="container">
    <h2 class="section-title">Carreras</h2>
    <p class="text-center">PROFESORADO DE EDUCACIÓN INICIAL (4 AÑOS) RES. N° 4154/07.</p> 
    <p class="text-center">PROFESORADO DE INGLÉS (4 AÑOS) RES: 1860/17 </p> 
    <p class="text-center">TECNICATURA SUPERIOR EN ENFERMERÍA (3 AÑOS) RES: 854/16.</p> 
    <p class="text-center">TECNICATURA SUPERIOR EN CIENCIA DE DATOS E INTELIGENCIA ARTIFICIAL (3 AÑOS) RES:2730/22</p> 
    <p class="text-center">CERTIFICACIÓN SUPERIOR: ESPECIALIZACIÓN DE ENFERMERÍA EN SALUD MENTAL RES. 43/17</p> 
  </div>
</section>
<section class="steps-section">
  <div class="container">
    <h2 class="section-title">Horarios de Cursada</h2>
    <p class="text-center" >Lunes a viernes</p> 
    <p class="text-center" >17:30 hs. a 21:30 hs.</p> 
  </div>
</section>


<?= $this->endSection() ?> 