<?= $this->extend('templates/layout') ?> 

<?= $this->section('content') ?>
<div class="container mt-5">
    <h1 class="mb-4">Gestión de Cursos</h1>

    <?php if (session()->get('rol') === 'administrador'): ?>
        <!-- Botón CREAR visible solo para el ADMINISTRADOR -->
        <a href="<?= base_url('cursos/create') ?>" class="btn btn-success mb-3">
            <i class="fas fa-plus-circle"></i> Nuevo Curso
        </a>
    <?php endif; ?>

    <!-- Manejo de Mensajes de Sesión -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre del Curso</th>
                    <th>Duración (hs)</th>
                    <th>Carrera Asociada</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($cursos) && is_array($cursos)): ?>
                    <?php foreach ($cursos as $curso): ?>
                        <tr>
                            <td><?= esc($curso['id_curso']) ?></td>
                            <td><?= esc($curso['nombre']) ?></td>
                            <td><?= esc($curso['duracion_horas']) ?></td>
                            <td><?= esc($curso['nombre_carrera'] ?? 'N/A') ?></td>
                            <td>
                                <a href="<?= base_url('cursos/show/' . $curso['id_curso']) ?>" class="btn btn-info btn-sm" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <?php if (session()->get('rol') === 'administrador'): ?>
                                    <!-- Editar y Eliminar SOLO para ADMINISTRADOR -->
                                    <a href="<?= base_url('cursos/edit/' . $curso['id_curso']) ?>" class="btn btn-warning btn-sm" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= base_url('cursos/delete/' . $curso['id_curso']) ?>" 
                                       class="btn btn-danger btn-sm" 
                                       title="Eliminar"
                                       onclick="return confirm('¿Está seguro de eliminar el curso: <?= esc($curso['nombre']) ?>?');">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No se encontraron cursos.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
 