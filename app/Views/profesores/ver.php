<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-12">
            <!-- Título y Botón de Volver -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h2 text-primary">
                    <i class="bi bi-person-badge-fill me-2"></i><?= esc($title) ?>
                </h1>
                <a href="<?= base_url('profesores') ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Volver a la Lista
                </a>
            </div>
        </div>
    </div>

    <!-- Tarjeta de Detalles del Profesor -->
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Información General</h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4 text-muted">ID Profesor:</dt>
                        <dd class="col-sm-8"><?= esc($profesor['id_profesor']) ?></dd>

                        <dt class="col-sm-4 text-muted">Nombre Completo:</dt>
                        <dd class="col-sm-8 text-dark fw-bold"><?= esc($profesor['nombre_completo']) ?></dd>
                        
                        <dt class="col-sm-4 text-muted">Especialidad:</dt>
                        <dd class="col-sm-8"><?= esc($profesor['especialidad']) ?></dd>
                        
                        <dt class="col-sm-4 text-muted">Email (Usuario):</dt>
                        <dd class="col-sm-8">
                            <a href="mailto:<?= esc($profesor['email']) ?>"><?= esc($profesor['email']) ?></a>
                        </dd>
                        
                        <dt class="col-sm-4 text-muted">Teléfono:</dt>
                        <dd class="col-sm-8"><?= esc($profesor['telefono'] ?? 'N/A') ?></dd>
                        
                        <dt class="col-sm-4 text-muted">ID Usuario (Credencial):</dt>
                        <dd class="col-sm-8"><?= esc($profesor['id_usuario']) ?></dd>
                    </dl>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <a href="<?= base_url('profesores/editar/' . $profesor['id_profesor']) ?>" class="btn btn-warning me-2">
                        <i class="bi bi-pencil-square"></i> Editar
                    </a>
                    <!-- Botón de Eliminar (Añadirías lógica de confirmación con JS) -->
                    <button class="btn btn-danger" onclick="confirmDelete(<?= $profesor['id_profesor'] ?>, '<?= esc($profesor['nombre_completo']) ?>')">
                        <i class="bi bi-trash"></i> Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script simple para confirmación de eliminación -->
<script>
    function confirmDelete(id, nombre) {
        if (confirm('¿Está seguro de que desea eliminar al profesor ' + nombre + '? Esta acción es irreversible y desasignará sus cursos.')) {
            // Si el administrador confirma, redirige a la ruta de eliminación
            window.location.href = '<?= base_url('profesores/eliminar') ?>/' + id;
        }
    }
</script>

<?= $this->endSection() ?>
 