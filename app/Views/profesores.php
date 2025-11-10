<?= $this->extend('templates/layout') ?>
<?= $this->section('content') ?>

<section class="profesores-section steps-section">
    <div class="container">
        <h2 class="section-title"><i class="fas fa-chalkboard-teacher"></i> Gestión Pedagógica: Rol del Profesor</h2>
        <p class="section-subtitle">Herramientas esenciales para el dictado, la evaluación y la comunicación con tus estudiantes.</p>
        
        <div class="steps-grid">
            
            <div class="step-card">
                <div class="step-number">1</div>
                <h3>Carga de Calificaciones</h3>
                <p>Ingresa notas finales o parciales y retroalimenta el desempeño de tus estudiantes.</p>
                <a href="<?= base_url('profesores/notas') ?>" class="step-link">Cargar Notas <i class="fas fa-arrow-right"></i></a>
            </div>
            
            <div class="step-card">
                <div class="step-number">2</div>
                <h3>Registro de Asistencia</h3>
                <p>Lleva el control diario de la presencia de los alumnos en tus diferentes cátedras.</p>
                <a href="<?= base_url('profesores/asistencia') ?>" class="step-link">Tomar Lista <i class="fas fa-arrow-right"></i></a>
            </div>
            
            <div class="step-card">
                <div class="step-number">3</div>
                <h3>Compartir Contenido</h3>
                <p>Sube archivos, links y materiales didácticos accesibles para todos tus cursos.</p>
                <a href="<?= base_url('profesores/contenido') ?>" class="step-link">Subir Material <i class="fas fa-arrow-right"></i></a>
            </div>
            
        </div>
    </div> 
</section>

<div class="container mt-5">
    <!-- Mensajes de Sesión (Éxito o Error) -->
    <?php if (session()->getFlashdata('mensaje')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('mensaje') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 text-primary"><i class="bi bi-people-fill me-2"></i><?= esc($title) ?></h1>
        
        <?php if (session()->get('rol') === 'administrador'): ?>
            <a href="<?= base_url('profesores/crear') ?>" class="btn btn-success">
                <i class="fas fa-user-plus me-1"></i> Nuevo Profesor
            </a>
        <?php endif; ?>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <?php if (empty($profesores)): ?>
                <div class="alert alert-info text-center">
                    No hay profesores registrados en el sistema.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead class="thead-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nombre Completo</th>
                                <th>Especialidad</th>
                                <th>Teléfono</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($profesores as $profesor): ?>
                                <tr>
                                    <td><?= esc($profesor['id_profesor']) ?></td>
                                    <td class="fw-bold"><?= esc($profesor['nombre_completo']) ?></td>
                                    <td><?= esc($profesor['especialidad']) ?></td>
                                    <td><?= esc($profesor['telefono'] ?? 'N/A') ?></td>
                                    <td>
                                        <!-- Botón de Ver (Visible para Administrador y Profesor, si el filtro lo permite) -->
                                        <a href="<?= base_url('profesores/ver/' . $profesor['id_profesor']) ?>" class="btn btn-sm btn-info" title="Ver Detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <?php if (session()->get('rol') === 'administrador'): ?>
                                            <!-- Botones de Edición y Eliminación (SOLO ADMINISTRADOR) -->
                                            <a href="<?= base_url('profesores/editar/' . $profesor['id_profesor']) ?>" class="btn btn-sm btn-warning" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <!-- Botón de Eliminar (Requiere JS para confirmación) -->
                                            <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?= $profesor['id_profesor'] ?>, '<?= esc($profesor['nombre_completo']) ?>')" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Script simple para confirmación de eliminación -->
<script>
    function confirmDelete(id, nombre) {
        if (confirm('¿Está seguro de que desea eliminar al profesor ' + nombre + '? Esta acción es irreversible.')) {
            window.location.href = '<?= base_url('profesores/eliminar') ?>/' + id;
        }
    }
</script>

<?= $this->endSection() ?>
 