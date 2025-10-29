<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>🎓 Gestión de Estudiantes | StudentApp</title>
  <link rel="stylesheet" href="<?= base_url('css/style.css') ?>"> 
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="estudiantes-page">
  <nav class="navbar">
    <div class="navbar-container">
      <a href="index.html" class="navbar-logo">🎓 StudentApp</a>
      <div class="navbar-links">
        <a href="estudiantes.html" class="active">Estudiantes</a>
        <a href="carreras.html">Carreras</a>
        <a href="categorias-carrera.html">Categorías</a>
      </div>
    </div>
  </nav>
<!-- Contenedor principal de la página -->
  <div class="container">
    <header class="page-header">
      <h1><i class="fas fa-user-graduate"></i> Gestión de Estudiantes</h1>
      <p class="page-subtitle">Administra el registro académico de los estudiantes</p>
    </header>
<!-- Sección para registrar un nuevo estudiante -->
    <section class="section-box">
      <h2><i class="fas fa-user-plus"></i> Registrar Nuevo Estudiante</h2>
      <form id="registerForm">
        <div class="form-grid">
          <div class="form-group">
            <label for="registerName"><i class="fas fa-id-card"></i> Nombre completo</label>
            <input type="text" id="registerName" class="form-control" placeholder="Ej: María García" required>
          </div>
          <div class="form-group">
            <label for="registerCareer"><i class="fas fa-graduation-cap"></i> Carrera</label>
            <select id="registerCareer" class="form-control" required>
              <option value="">Cargando carreras...</option>
            </select>
          </div>
        </div>
<!-- Campo para seleccionar el año de ingreso del estudiante -->
        <button type="submit" class="btn btn-primary btn-block">
          <i class="fas fa-save"></i> Registrar Estudiante
        </button>
      </form>
    </section>
<!-- Sección para buscar un estudiante por ID -->
    <section class="section-box">
      <h2><i class="fas fa-search"></i> Buscar Estudiante por ID</h2>
      <form id="searchByIdForm">
        <div class="form-grid">
          <div class="form-group">
            <label for="studentId"><i class="fas fa-hashtag"></i> ID del estudiante</label>
            <input type="number" id="studentId" class="form-control" placeholder="Ej: 1001" required>
          </div>
        </div>
        <button type="submit" class="btn btn-secondary btn-block">
          <i class="fas fa-search"></i> Buscar
        </button>
      </form>
      <div id="getResult" class="result-container"></div>
    </section>
<!-- Sección para listar los estudiantes registrados -->
    <section class="section-box">
      <div class="section-header">
        <h2><i class="fas fa-list"></i> Listado de Estudiantes</h2>
        <div class="table-actions">
          <button class="btn btn-secondary" onclick="loadAndDisplayStudents()">
            <i class="fas fa-sync-alt"></i> Actualizar
          </button>
        </div>
      </div>
<!-- Tabla para mostrar los estudiantes registrados -->
      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Carrera</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody id="studentsTableBody">
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
<!-- Scripts necesarios -->
  <script src="app.js"></script>
</body>
</html>