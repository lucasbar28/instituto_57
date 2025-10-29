<?= $this->extend('templates/layout') ?> 

<?= $this->section('content') ?>

<!-- Envuelve el contenido en un contenedor principal para centrarlo y darle margen -->
<div class="container mt-5">

    <!-- Encabezado de la Página y Botón de Acción -->
    <div class="page-header">
        <h1>
            <i class="fas fa-tags"></i> Lista de Categorías
        </h1>
        <a href="<?= base_url('categorias/crear') ?>" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> Agregar Nueva Categoría
        </a>
    </div>

    <!-- Muestra mensajes flash (éxito o error) -->
    <?php if (session()->getFlashdata('mensaje')): ?>
        <div class="section-box mb-4 alert alert-success">
            <p><?= session()->getFlashdata('mensaje') ?></p>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="section-box mb-4 alert alert-danger">
            <p><?= session()->getFlashdata('error') ?></p>
        </div>
    <?php endif; ?>

    <!-- Contenedor de la Tabla -->
    <div class="section-box table-responsive">
        <?php if (empty($categorias)): ?>
            <p class="text-center text-muted p-4">
                <i class="fas fa-inbox fa-3x mb-3"></i><br>
                No hay categorías registradas aún. ¡Agrega la primera!
            </p>
        <?php else: ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Fecha Creación</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categorias as $categoria): ?>
                    <tr>
                        <td><?= esc($categoria['id_categoria']) ?></td>
                        <td><?= esc($categoria['nombre']) ?></td>
                        <td><?= esc($categoria['descripcion']) ?: '<span class="text-muted fst-italic">Sin descripción</span>' ?></td>
                        <td><?= esc($categoria['fecha_creacion'] ?? 'N/A') ?></td>
                        
                        <!-- Columna de Acciones: Usando las clases personalizadas (igual que en Carreras) -->
                        <td class="align-middle action-buttons">
                            
                            <!-- Botón Editar: Usando la clase personalizada de edición (btn-edit) -->
                            <a href="<?= base_url('categorias/editar/' . $categoria['id_categoria']) ?>" 
                               class="btn-action btn-edit" title="Editar Categoría">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            
                            <!-- Botón Eliminar: Usando la clase personalizada de eliminación (btn-delete) -->
                            <a href="<?= base_url('categorias/eliminar/' . $categoria['id_categoria']) ?>" 
                               class="btn-action btn-delete" title="Eliminar Categoría"
                               onclick="return confirm('¿Está seguro de que desea eliminar la categoría: <?= esc($categoria['nombre']) ?>? Esta acción es irreversible.')">
                                <i class="fas fa-trash-alt"></i> Eliminar
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
