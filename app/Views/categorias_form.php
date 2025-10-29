<?= $this->extend('templates/layout') ?> 

<?= $this->section('content') ?>

<?php 
    // Determina si estamos en modo edición o creación
    $is_edit = isset($categoria); 
    $title = $is_edit ? 'Editar Categoría' : 'Crear Nueva Categoría';
    $action_url = base_url($is_edit ? 'categorias/actualizar' : 'categorias/guardar');
    // Prepara los datos para prellenar los campos
    $categoria_nombre = $is_edit ? $categoria['nombre'] : '';
    $categoria_descripcion = $is_edit ? $categoria['descripcion'] : '';
    // Obtiene los servicios de validación para verificar errores
    $validation = \Config\Services::validation(); 
?>

<!-- Contenedor principal del formulario -->
<div class="container mt-5">

    <!-- Encabezado de la página y botón de regreso -->
    <div class="page-header">
        <h1><i class="fas fa-tags"></i> <?= esc($title) ?></h1>
        <!-- El botón "Volver" usa btn-secondary para ser un botón de acción no primaria -->
        <a href="<?= base_url('categorias') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-alt-circle-left"></i> Volver al Listado de Categorías
        </a>
    </div>

    <div class="section-box">
        <p class="page-subtitle">Utilice este formulario para gestionar las categorías (Ej: Ingeniería, Humanidades, Artes).</p>
        
        <!-- Muestra errores de sesión (si se regresa con withInput()) -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert-danger section-box">
                <p><?= session()->getFlashdata('error') ?></p>
            </div>
        <?php endif; ?>
        
        <!-- Muestra la lista de errores de validación -->
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert-danger section-box">
                <p><strong>🚨 Error en el formulario:</strong> Por favor, revise los siguientes errores:</p>
                <ul>
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= esc($action_url) ?>" method="post">
            <?= csrf_field() ?> <!-- Protección CSRF -->

            <!-- Campo Oculto ID (SOLO en modo edición) -->
            <?php if ($is_edit): ?>
                <input type="hidden" name="id_categoria" value="<?= esc($categoria['id_categoria']) ?>">
            <?php endif; ?>
            
            <!-- Nombre de la Categoría -->
            <div class="form-group mb-3">
                <label for="nombre">Nombre de la Categoría:</label>
                <input type="text" name="nombre" id="nombre" 
                       class="form-control <?= $validation->hasError('nombre') ? 'is-invalid' : '' ?>" 
                       value="<?= old('nombre', $categoria_nombre) ?>" required>
                <?php if ($validation->hasError('nombre')): ?>
                    <div class="invalid-feedback"><?= $validation->getError('nombre') ?></div>
                <?php endif; ?>
            </div>

            <!-- Descripción (Opcional) -->
            <div class="form-group mb-4">
                <label for="descripcion">Descripción (Opcional):</label>
                <textarea name="descripcion" id="descripcion" rows="3" 
                          class="form-control <?= $validation->hasError('descripcion') ? 'is-invalid' : '' ?>"><?= old('descripcion', $categoria_descripcion) ?></textarea>
                <?php if ($validation->hasError('descripcion')): ?>
                    <div class="invalid-feedback"><?= $validation->getError('descripcion') ?></div>
                <?php endif; ?>
            </div>
            
            <!-- Botón de Guardar / Actualizar (Usando clase personalizada btn-submit) -->
            <div class="form-group mt-4">
                <button type="submit" class="btn btn-primary btn-block **btn-submit**">
                    <i class="fas fa-save"></i> <?= $is_edit ? 'Actualizar Categoría' : 'Guardar Categoría' ?>
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
