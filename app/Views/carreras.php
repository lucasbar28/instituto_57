<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>📚 Gestión de Carreras | StudentApp</title>
  <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
  <nav class="navbar">
    <div class="navbar-container">
      <a href="index.html" class="navbar-logo">🎓 StudentApp</a>
      <div class="navbar-links">
        <a href="estudiantes.html">Estudiantes</a>
        <a href="carreras.html" class="active">Carreras</a>
        <a href="categorias-carrera.html">Categorías</a>
      </div>
    </div>
  </nav>
<!-- Contenedor principal de la página -->
  <div class="container">
    <header class="page-header">
      <h1><i class="fas fa-graduation-cap"></i> Gestión de Carreras</h1>
      <p class="page-subtitle">Administra las carreras académicas ofrecidas</p>
    </header>
<!--Sección para registrar una nueva carrera--> 
    <section class="section-box">
      <h2><i class="fas fa-plus-circle"></i> Registrar Nueva Carrera</h2>
      <form id="careerForm">
        <div class="form-grid">
          <div class="form-group">
            <label for="careerName"><i class="fas fa-book"></i> Nombre</label>
            <input type="text" id="careerName" class="form-control" placeholder="Ej: Ingeniería de Software" required>
          </div>
<!-- Campo para seleccionar la categoría de la carrera -->
          <div class="form-group">
            <label for="careerCategory"><i class="fas fa-tag"></i> Categoría</label>
            <select id="careerCategory" class="form-control" required>
              <option value="">Seleccionar...</option>
            </select>
          </div>
<!-- Campo para seleccionar la duración de la carrera -->
          <div class="form-group">
            <label for="careerDuration"><i class="fas fa-clock"></i> Duración (años)</label>
            <select id="careerDuration" class="form-control" required>
              <option value="">Seleccionar...</option>
              <option value="1">1 año</option>
              <option value="2">2 años</option>
              <option value="3">3 años</option>
              <option value="4">4 años</option>
              <option value="5">5 años</option>
              <option value="6">6 años</option>
            </select>
          </div>
        </div>
<!-- Botón para enviar el formulario y registrar la carrera -->
        <button type="submit" class="btn btn-primary btn-block">
          <i class="fas fa-save"></i> Guardar Carrera
        </button>
      </form>
    </section>
<!--Sección para listar las carreras registradas-->
    <section class="section-box">
      <div class="section-header">
        <h2><i class="fas fa-list"></i> Listado de Carreras</h2>
        <div class="table-actions">
          <button class="btn btn-secondary" onclick="loadAndDisplayCareers()">
            <i class="fas fa-sync-alt"></i> Actualizar
          </button>
        </div>
      </div>
      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th><i class="fas fa-hashtag"></i> ID</th>
              <th><i class="fas fa-graduation-cap"></i> Nombre</th>
              <th><i class="fas fa-tag"></i> Categoría</th>
              <th><i class="fas fa-clock"></i> Duración</th>
              <th><i class="fas fa-cog"></i> Acciones</th>
            </tr>
          </thead>
          <tbody id="careersTableBody">
            <!-- Datos cargados dinámicamente -->
          </tbody>
        </table>
      </div>
    </section>
  </div>
<!-- Footer de la página -->
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
<!-- Scripts necesarios para la funcionalidad -->
  <script src="app.js"></script>
</body>
</html>         