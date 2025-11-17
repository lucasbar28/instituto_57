<?= $this->extend('templates/layout') ?> 

<?= $this->section('content') ?>
<div class="container mt-5">
    <h1 class="mb-4">Gestión de Categorías</h1>

    <?php if (session()->get('rol') === 'administrador'): ?>
        <!-- Botón CREAR visible solo para el ADMINISTRADOR -->
        <a href="<?= base_url('categorias/create') ?>" class="btn btn-success mb-3">
            <i class="fas fa-plus-circle"></i> Nueva Categoría
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
        <table class="data-table">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre de Categoría</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($categorias) && is_array($categorias)): ?>
                    <?php foreach ($categorias as $categoria): ?>
                        <tr>
                            <td><?= esc($categoria['id_categoria']) ?></td>
                            <td><?= esc($categoria['nombre_categoria']) ?></td>
                            <td><?= esc($categoria['descripcion'] ?? 'N/A') ?></td>
                            <td>
                                <a href="<?= base_url('categorias/show/' . $categoria['id_categoria']) ?>" class="btn btn-info btn-sm" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <?php if (session()->get('rol') === 'administrador'): ?>
                                    <!-- Editar y Eliminar SOLO para ADMINISTRADOR -->
                                    <a href="<?= base_url('categorias/edit/' . $categoria['id_categoria']) ?>" class="btn btn-warning btn-sm" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= base_url('categorias/delete/' . $categoria['id_categoria']) ?>" 
                                       class="btn btn-danger btn-sm" 
                                       title="Eliminar"
                                       onclick="return confirm('¿Está seguro de eliminar la categoría: <?= esc($categoria['nombre_categoria']) ?>?');">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No se encontraron categorías.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
 