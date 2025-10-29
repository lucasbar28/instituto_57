<<<<<<< HEAD
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Profesor</title>
    <link rel="stylesheet" href="<?= base_url('css/styles.css') ?>"> 
</head>
<body>
=======
<?php 
/**
 * Vista: Formulario para la creación o edición de un Profesor.
 * Extiende el layout principal 'templates/layout'.
 * CORREGIDO: Eliminado el campo de Contraseña Inicial/DNI.
 */
?>
<?= $this->extend('templates/layout') ?> 

<?= $this->section('content') ?>

<?php
// Determinar si estamos en modo edición (si $profesor existe)
$is_edit = isset($profesor);

// Definir la acción del formulario y el título de la página
$action_url = $is_edit ? base_url('profesores/actualizar') : base_url('profesores/guardar');
$form_title = $is_edit ? 'Editar Profesor' : 'Registrar Nuevo Profesor';

// Obtener los datos para prellenar, priorizando old() si hay error de validación, 
// o los datos del profesor si es modo edición.
$data_profesor = $is_edit ? $profesor : [];

// FUNCIÓN DE AYUDA (Sintaxis limpia sin caracteres ocultos)
$get_value = function($field) use ($data_profesor) {
    // Si hay un error de validación, old() tiene prioridad
    return old($field) ?? ($data_profesor[$field] ?? '');
};

// Servicio de validación para manejar errores
$validation = \Config\Services::validation();

// Función de ayuda para chequear si hay error en un campo
$has_error = function($field) use ($validation) {
    return $validation->hasError($field) ? 'is-invalid' : '';
};
?>
>>>>>>> c45f0289ee82d6fcfa79dd9c099b3162d5742a95

<div class="container mt-5">

    <!-- Encabezado de la Página y Botón de Regreso -->
    <div class="page-header">
        <h1>
            <i class="fas fa-user-tie"></i> <?= $form_title ?>
        </h1>
        <a href="<?= base_url('profesores') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver al Listado de Profesores
        </a>
    </div>

    <div class="section-box">
        <p class="page-subtitle">Complete los campos para <?= $is_edit ? 'actualizar los datos del profesor.' : 'añadir un nuevo profesor.' ?></p>
        
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
                <input type="hidden" name="id_profesor" value="<?= esc($data_profesor['id_profesor']) ?>">
            <?php endif; ?>

            <!-- Usamos una grilla de 3 columnas para que se vea bien -->
            <div class="form-grid-2-col">
                
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

                <!-- Especialidad -->
                <div class="form-group">
                    <label for="especialidad">Especialidad:</label>
                    <input type="text" name="especialidad" id="especialidad" 
                            class="form-control <?= $has_error('especialidad') ?>" 
                            value="<?= esc($get_value('especialidad')) ?>" required>
                    <?php if ($validation->hasError('especialidad')): ?>
                        <small class="invalid-feedback-text"><?= $validation->getError('especialidad') ?></small>
                    <?php endif; ?>
                </div>
                
                <!-- Email (Usuario de acceso) -->
                <div class="form-group">
                    <label for="email">Email (Usuario de acceso):</label>
                    <input type="email" name="email" id="email" 
                            class="form-control <?= $has_error('email') ?>" 
                            value="<?= esc($get_value('email')) ?>" required>
                    <?php if ($validation->hasError('email')): ?>
                        <small class="invalid-feedback-text"><?= $validation->getError('email') ?></small>
                    <?php endif; ?>
                </div>
                
                <!-- Teléfono (Opcional) -->
                <div class="form-group">
                    <label for="telefono">Teléfono (Opcional):</label>
                    <input type="text" name="telefono" id="telefono" 
                            class="form-control <?= $has_error('telefono') ?>" 
                            value="<?= esc($get_value('telefono')) ?>">
                    <?php if ($validation->hasError('telefono')): ?>
                        <small class="invalid-feedback-text"><?= $validation->getError('telefono') ?></small>
                    <?php endif; ?>
                </div>

                <!-- CAMPO ELIMINADO: Ya no existe el campo de contraseña inicial -->
                
            </div>
            
            <!-- Botón de Guardar/Actualizar -->
            <div class="form-group mt-4">
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas <?= $is_edit ? 'fa-save' : 'fa-plus-circle' ?>"></i> <?= $is_edit ? 'Actualizar Profesor' : 'Registrar Profesor' ?>
                </button>
            </div>
        </form>
    </div>
</div>
<<<<<<< HEAD
</body>
</html> 
=======

<<<<<<< HEAD
<?php 
    // 3. Incluir el FOOTER y las etiquetas de cierre
    echo view('templates/footer');
?>
>>>>>>> c45f0289ee82d6fcfa79dd9c099b3162d5742a95
=======
<style>
/* CSS para corregir visualmente los errores de validación sin usar Bootstrap */
.invalid-feedback-text {
    color: #ef4444; /* Rojo para errores */
    margin-top: 0.25rem;
    display: block;
    font-size: 0.875rem; /* Pequeño */
}
/* Estilo para la grilla del formulario */
.form-grid-2-col {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
}
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}
</style>

<?= $this->endSection() ?>
>>>>>>> d4a6b1f026490e7c9592133bd4126ef228109578
