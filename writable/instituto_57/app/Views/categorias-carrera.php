<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>📂 Gestión de Categorías | StudentApp</title>
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
        <a href="carreras.html">Carreras</a>
        <a href="categorias-carrera.html" class="active">Categorías</a>
      </div>
    </div>
  </nav>
<!-- Contenedor principal de la página -->
  <div class="container">
    <header class="page-header">
      <h1><i class="fas fa-tags"></i> Gestión de Categorías</h1>
      <p class="page-subtitle">Organiza las carreras por áreas de conocimiento</p>
    </header>
<!-- Sección para registrar una nueva categoría -->
    <section class="section-box">
      <h2><i class="fas fa-plus-circle"></i> Registrar Nueva Categoría</h2>
      <form id="formRegistroCategoria">
        <div class="form-grid">
          <div class="form-group">
            <label for="nombreCategoria"><i class="fas fa-tag"></i> Nombre</label>
            <input type="text" id="nombreCategoria" class="form-control" placeholder="Ej: Ciencias Exactas" required>
          </div>
        </div>
        <button type="submit" class="btn btn-primary btn-block">
          <i class="fas fa-save"></i> Registrar
        </button>
      </form>
    </section>
<!-- Sección para listar las categorías registradas -->
    <section class="section-box">
      <div class="section-header">
        <h2><i class="fas fa-list"></i> Listado de Categorías</h2>
        <div class="table-actions">
          <button class="btn btn-secondary" onclick="loadAndDisplayCategories()">
            <i class="fas fa-sync-alt"></i> Actualizar
          </button>
        </div>
      </div>
<!-- Tabla para mostrar las categorías registradas -->
      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th><i class="fas fa-hashtag"></i> ID</th>
              <th><i class="fas fa-tag"></i> Nombre</th>
              <th><i class="fas fa-cog"></i> Acciones</th>
            </tr>
          </thead>
          <tbody id="categoriesTableBody">
            <!-- Datos cargados dinámicamente -->
          </tbody>
        </table>
      </div>
    </section>
  </div>
<!-- Pie de página -->
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
<!-- Scripts necesarios -->
  <script src="app.js"></script>
</body>
</html>