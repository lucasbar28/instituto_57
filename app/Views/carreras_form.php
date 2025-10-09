<?php 
/**
 * Vista: Formulario de Creación y Edición de Carreras.
 * Extiende el layout principal 'templates/layout'.
 */
?>
<?= $this->extend('templates/layout') ?> 

<?= $this->section('content') ?>

<?php
    // --- LÓGICA DE DETECCIÓN DE MODO (Crear vs. Editar) ---
    if (!isset($carrera)) {
        $carrera = null;
    }

    $is_edit = $carrera !== null;
    
    // Determina el título y la acción del formulario
    $form_title = $is_edit ? 'Editar Carrera: ' . esc($carrera['nombre_carrera']) : 'Registrar Nueva Carrera';
    $form_action = $is_edit ? base_url('carreras/actualizar') : base_url('carreras/guardar');
    
    // Obtiene el objeto de validación
    $validation = \Config\Services::validation(); 

    // Función de ayuda para obtener valores viejos o de edición
    $get_value = function($field, $default = '') use ($is_edit, $carrera) {
        $carrera_data = $is_edit && isset($carrera[$field]) ? $carrera[$field] : null;
        return old($field) ?? $carrera_data ?? $default;
    };

    // Función de ayuda para chequear si hay error en un campo
    $has_error = function($field) use ($validation) {
        return $validation->hasError($field) ? 'is-invalid' : '';
    };

    // Opciones para Modalidad
    $modalidad_options = [
        'Presencial' => 'Presencial',
        'Virtual' => 'Virtual',
        'Mixta' => 'Mixta',
    ];

    // Opciones para Estado (Usando 1/0 para consistencia con el controlador)
    $estado_options = [
        1 => 'Activa',
        0 => 'Inactiva',
    ];

?>

<div class="container mt-5">

    <!-- Encabezado de la Página y Botón de Regreso -->
    <div class="page-header">
        <h1>
            <i class="fas fa-graduation-cap"></i> <?= $form_title ?>
        </h1>
        <a href="<?= base_url('carreras') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver al Listado
        </a>
    </div>

    <!-- Contenedor principal del formulario -->
    <div class="section-box">
        <p class="page-subtitle">Complete los campos para <?= $is_edit ? 'actualizar la carrera.' : 'añadir una nueva carrera.' ?></p>
        
        <!-- ALERTAS DE SESIÓN (Éxito, Error) -->
        <?= view('templates/_alerts') ?>

        <!-- Muestra errores de validación -->
        <?php if ($validation->getErrors()): ?>
            <div class="section-box mb-4 alert alert-warning">
                <p><strong>🚨 Error en el formulario:</strong> Por favor, revise los siguientes errores:</p>
                <ul class="list-unstyled" style="margin-bottom: 0;">
                    <?php foreach ($validation->getErrors() as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- INICIO DEL FORMULARIO -->
        <form action="<?= $form_action ?>" method="post">
            <?= csrf_field() ?>
            
            <?php if ($is_edit): ?>
                <!-- CAMPO OCULTO: ID necesario para la función actualizar() -->
                <input type="hidden" name="id_carrera" value="<?= esc($carrera['id_carrera']) ?>">
            <?php endif; ?>

            <!-- Contenedor de Formulario con GRID -->
            <div class="form-grid">
                
                <!-- Nombre de la Carrera -->
                <div class="form-group">
                    <label for="nombre_carrera">Nombre de la Carrera:</label>
                    <input type="text" name="nombre_carrera" id="nombre_carrera" 
                            class="form-control <?= $has_error('nombre_carrera') ?>" 
                            value="<?= esc($get_value('nombre_carrera')) ?>" required>
                    <?php if ($validation->hasError('nombre_carrera')): ?>
                        <small class="invalid-feedback-text"><?= $validation->getError('nombre_carrera') ?></small>
                    <?php endif; ?>
                </div>

                <!-- Duración (en años o semestres) -->
                <div class="form-group">
                    <label for="duracion">Duración (ej: 4 años):</label>
                    <input type="number" name="duracion" id="duracion" 
                            class="form-control <?= $has_error('duracion') ?>" 
                            value="<?= esc($get_value('duracion')) ?>" required min="1">
                    <?php if ($validation->hasError('duracion')): ?>
                        <small class="invalid-feedback-text"><?= $validation->getError('duracion') ?></small>
                    <?php endif; ?>
                </div>
                
                <!-- Modalidad (Presencial/Virtual/Mixta) -->
                <div class="form-group">
                    <label for="modalidad">Modalidad:</label>
                    <?php $current_modalidad = $get_value('modalidad'); ?>
                    <select name="modalidad" id="modalidad" 
                            class="form-control <?= $has_error('modalidad') ?>" required>
                        <option value="">Seleccione Modalidad</option>
                        <?php foreach ($modalidad_options as $value => $label): ?>
                            <option value="<?= esc($value) ?>" 
                                <?= $current_modalidad == $value ? 'selected' : '' ?>>
                                <?= esc($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if ($validation->hasError('modalidad')): ?>
                        <small class="invalid-feedback-text"><?= $validation->getError('modalidad') ?></small>
                    <?php endif; ?>
                </div>

                <!-- Categoría Asociada (Dropdown) -->
                <div class="form-group">
                    <label for="id_categoria">Categoría Asociada:</label>
                    <?php $current_categoria_id = $get_value('id_categoria'); ?>
                    <select name="id_categoria" id="id_categoria" 
                            class="form-control <?= $has_error('id_categoria') ?>" required>
                        <option value="">Seleccione Categoría</option>
                        <?php if (!empty($categorias)): ?>
                            <?php foreach ($categorias as $categoria): ?>
                                <option value="<?= esc($categoria['id_categoria']) ?>"
                                    <?= $current_categoria_id == $categoria['id_categoria'] ? 'selected' : '' ?>>
                                    <?= esc($categoria['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="" disabled>No hay categorías disponibles</option>
                        <?php endif; ?>
                    </select>
                    <?php if ($validation->hasError('id_categoria')): ?>
                        <small class="invalid-feedback-text"><?= $validation->getError('id_categoria') ?></small>
                    <?php endif; ?>
                </div>
                
                <!-- Estado (Solo se muestra en edición) -->
                <?php if ($is_edit): ?>
                <div class="form-group">
                    <label for="estado">Estado:</label>
                    <?php $current_estado_val = $get_value('estado', 1); ?>
                    <select name="estado" id="estado" 
                            class="form-control <?= $has_error('estado') ?>" required>
                        <!-- Usamos 1 y 0 como valor para guardar el estado en la BD -->
                        <?php foreach ($estado_options as $value => $label): ?>
                            <option value="<?= $value ?>" 
                                <?= $current_estado_val == $value ? 'selected' : '' ?>>
                                <?= esc($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if ($validation->hasError('estado')): ?>
                        <small class="invalid-feedback-text"><?= $validation->getError('estado') ?></small>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Botón de Guardar/Actualizar -->
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas <?= $is_edit ? 'fa-save' : 'fa-plus-circle' ?>"></i> <?= $is_edit ? 'Actualizar Carrera' : 'Guardar Carrera' ?>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* CSS copiado de estudiantes_form.php para asegurar consistencia */
.invalid-feedback-text {
    color: #ef4444; /* Rojo para errores */
    margin-top: 0.25rem;
    display: block;
    font-size: 0.875rem; /* Pequeño */
}
</style>

<?= $this->endSection() ?>
 