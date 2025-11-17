<?= $this->extend('templates/layout') ?> 

<?= $this->section('content') ?>
<div class="container mt-5">
    <h1 class="mb-4">Gestión de Carreras</h1>

    <?php if (session()->get('rol') === 'administrador'): ?>
        <!-- Botón CREAR visible solo para el ADMINISTRADOR -->
        <a href="<?= base_url('carreras/create') ?>" class="btn btn-success mb-3">
            <i class="fas fa-plus-circle"></i> Nueva Carrera
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
                    <th>Nombre</th>
                    <th>Título</th>
                    <th>Acreditación</th>
                    <!-- La columna de acciones solo se muestra si el usuario es administrador o si hay acciones de 'Ver' -->
                    <th>Acciones</th> 
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($carreras) && is_array($carreras)): ?>
                    <?php foreach ($carreras as $carrera): ?>
                        <tr>
                            <td><?= esc($carrera['id_carrera']) ?></td>
                            <td><?= esc($carrera['nombre_carrera']) ?></td>
                            <td><?= esc($carrera['titulo'] ?? '') ?></td>
                            <td><?= esc($carrera['acreditacion'] ?? '') ?></td>
                            <td>
                                <!-- La acción VER es la que queda disponible para todos los roles permitidos por el filtro -->
                                <a href="<?= base_url('carreras/show/' . $carrera['id_carrera']) ?>" class="btn btn-info btn-sm" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <?php if (session()->get('rol') === 'administrador'): ?>
                                    <!-- Editar y Eliminar SOLO para ADMINISTRADOR -->
                                    <a href="<?= base_url('carreras/edit/' . $carrera['id_carrera']) ?>" class="btn btn-warning btn-sm" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= base_url('carreras/delete/' . $carrera['id_carrera']) ?>" 
                                       class="btn btn-danger btn-sm" 
                                       title="Eliminar"
                                       onclick="return confirm('¿Está seguro de eliminar la carrera: <?= esc($carrera['nombre_carrera']) ?>?');">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No se encontraron carreras.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
 