<?php 
/**
 * Vista: Formulario para la creación o edición de un Estudiante.
 * Extiende el layout principal 'templates/layout'.
 */
?>
<?= $this->extend('templates/layout') ?> 

<?= $this->section('content') ?>

<?php
// Determinar si estamos en modo edición (si $estudiante existe)
$is_edit = isset($estudiante);

// Definir la acción del formulario y el título de la página
$action_url = $is_edit ? base_url('estudiantes/actualizar') : base_url('estudiantes/guardar');
$form_title = $is_edit ? 'Editar Alumno' : 'Registrar Nuevo Alumno';

// Obtener los datos para prellenar, priorizando old() si hay error de validación, 
// o los datos del estudiante si es modo edición.
$data_estudiante = $is_edit ? $estudiante : [];

// FUNCIÓN DE AYUDA (Sintaxis limpia sin caracteres ocultos)
$get_value = function($field) use ($data_estudiante) {
    // Si hay un error de validación, old() tiene prioridad
    return old($field) ?? ($data_estudiante[$field] ?? '');
};

// Servicio de validación para manejar errores
$validation = \Config\Services::validation();

// Función de ayuda para chequear si hay error en un campo
$has_error = function($field) use ($validation) {
    return $validation->hasError($field) ? 'is-invalid' : '';
};
?>

<div class="container mt-5">

    <!-- Encabezado de la Página y Botón de Regreso -->
    <div class="page-header">
        <h1>
            <i class="fas fa-user-edit"></i> <?= $form_title ?>
        </h1>
        <a href="<?= base_url('estudiantes') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver al Listado de Alumnos
        </a>
    </div>

    <div class="section-box">
        <p class="page-subtitle">Complete los campos para <?= $is_edit ? 'actualizar los datos del alumno.' : 'añadir un nuevo alumno.' ?></p>
        
        <!-- 1. ALERTAS DE SESIÓN (Éxito, Error) -->
        <?= view('templates/_alerts') ?>

        <!-- Muestra errores de validación de CodeIgniter -->
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

        <form action="<?= $action_url ?>" method="post">
            <?= csrf_field() ?>
            
            <?php if ($is_edit): ?>
                <input type="hidden" name="id_alumno" value="<?= esc($data_estudiante['id_alumno']) ?>">
            <?php endif; ?>

            <div class="form-grid">
                
                <!-- Nombre Completo -->
                <div class="form-group">
                    <label for="nombre_completo">Nombre Completo:</label>
                    <input type="text" name="nombre_completo" id="nombre_completo" 
                            class="form-control <?= $has_error('nombre_completo') ?>" 
                            value="<?= esc($get_value('nombre_completo')) ?>" required>
                    <?php if ($validation->hasError('nombre_completo')): ?>
                        <small class="invalid-feedback-text"><?= $validation->getError('nombre_completo') ?></small>
                    <?php endif; ?>
                </div>

                <!-- DNI (o documento de identidad) -->
                <div class="form-group">
                    <label for="dni">DNI / Documento:</label>
                    <input type="text" name="dni" id="dni" 
                            class="form-control <?= $has_error('dni') ?>" 
                            value="<?= esc($get_value('dni')) ?>" required>
                    <?php if ($validation->hasError('dni')): ?>
                        <small class="invalid-feedback-text"><?= $validation->getError('dni') ?></small>
                    <?php endif; ?>
                </div>
                
                <!-- Email -->
                <div class="form-group">
                    <label for="email">Email (Correo Electrónico):</label>
                    <input type="email" name="email" id="email" 
                            class="form-control <?= $has_error('email') ?>" 
                            value="<?= esc($get_value('email')) ?>" required>
                    <?php if ($validation->hasError('email')): ?>
                        <small class="invalid-feedback-text"><?= $validation->getError('email') ?></small>
                    <?php endif; ?>
                </div>

                <!-- Teléfono (Opcional) -->
                <div class="form-group">
                    <label for="telefono">Teléfono:</label>
                    <input type="text" name="telefono" id="telefono" 
                            class="form-control <?= $has_error('telefono') ?>" 
                            value="<?= esc($get_value('telefono')) ?>">
                    <?php if ($validation->hasError('telefono')): ?>
                        <small class="invalid-feedback-text"><?= $validation->getError('telefono') ?></small>
                    <?php endif; ?>
                </div>

                <!-- Carrera Asociada (Dropdown) -->
                <div class="form-group"> 
                    <label for="id_carrera">Carrera Asociada:</label>
                    <?php $selected_carrera = $get_value('id_carrera'); ?>
                    <select name="id_carrera" id="id_carrera" 
                             class="form-control <?= $has_error('id_carrera') ?>" required>
                        <option value="">Seleccione una Carrera</option>
                        <?php if (!empty($carreras)): ?>
                            <?php foreach ($carreras as $carrera): ?>
                                <option value="<?= esc($carrera['id_carrera']) ?>"
                                    <?= $selected_carrera == $carrera['id_carrera'] ? 'selected' : '' ?>>
                                    <?= esc($carrera['nombre_carrera']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="" disabled>No hay carreras disponibles</option>
                        <?php endif; ?>
                    </select>
                    <?php if ($validation->hasError('id_carrera')): ?>
                        <small class="invalid-feedback-text"><?= $validation->getError('id_carrera') ?></small>
                    <?php endif; ?>
                </div>

            </div>
            
            <!-- Botón de Guardar/Actualizar -->
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas <?= $is_edit ? 'fa-save' : 'fa-plus-circle' ?>"></i> <?= $is_edit ? 'Actualizar Alumno' : 'Registrar Alumno' ?>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* CSS para corregir visualmente los errores de validación sin usar Bootstrap */
.invalid-feedback-text {
    color: #ef4444; /* Rojo para errores */
    margin-top: 0.25rem;
    display: block;
    font-size: 0.875rem; /* Pequeño */
}
</style>

<?= $this->endSection() ?>
