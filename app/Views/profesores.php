<?= $this->extend('templates/layout') ?>
<?= $this->section('content') ?>

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
                    <table class="table table-hover table-striped">
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
 