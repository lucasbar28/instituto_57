<?= $this->extend('templates/layout') ?> 

<?= $this->section('content') ?>

<div class="page-header">
    <h1>Gestión de Categorías</h1>
    <p class="page-subtitle">Aquí se listan todas las categorías disponibles para clasificar carreras.</p>
</div>

<?php if (session()->getFlashdata('mensaje')): ?>
    <div class="alert-success section-box">
        <p style="font-weight: bold;"><?= session()->getFlashdata('mensaje') ?></p>
    </div>
<?php endif; ?>

<div class="section-box">
    <a href="<?= base_url('categorias/crear') ?>" class="btn btn-primary">Registrar Nueva Categoría</a>
    
    <h3 style="margin-top: 20px;">Listado de Categorías</h3>
    
    <?php if (isset($categorias) && count($categorias) > 0): ?>
        <p>Se encontraron **<?= count($categorias) ?>** categorías en la base de datos.</p>
        
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre de Categoría</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categorias as $categoria): ?>
                    <tr>
                        <td><?= esc($categoria['id_categoria']) ?></td>
                        <td><?= esc($categoria['nombre']) ?></td>
                        <td><?= esc($categoria['descripcion']) ?></td>
                        <td>
                            <a href="#" class="btn btn-sm btn-info">Editar</a>
                            <a href="#" class="btn btn-sm btn-danger">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-muted">Aún no hay categorías registradas. Intente agregar una.</p>
    <?php endif; ?>
</div>

<?= $this->endSection() ?> 