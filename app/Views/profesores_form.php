<?= $this->extend('templates/layout') ?> 

<?= $this->section('content') ?>

<?php
$is_edit = isset($profesor);

$action_url = $is_edit ? base_url('profesores/actualizar') : base_url('profesores/guardar');
$form_title = $is_edit ? 'Editar Profesor: ' . esc($profesor['nombre_completo']) : 'Registrar Nuevo Profesor';

$data_profesor = $is_edit ? $profesor : [];
$get_value = function($field) use ($data_profesor) {
    return old($field) ?? ($data_profesor[$field] ?? '');
};
?>

<div class="page-header">
    <h1><?= $form_title ?></h1>
    <a href="<?= base_url('profesores') ?>" class="btn btn-secondary">Volver al Listado de Profesores</a>
</div>

<div class="section-box">
    <p class="page-subtitle">
        Complete los campos para <?= $is_edit ? 'actualizar los datos del profesor.' : 'registrar un nuevo profesor y crear su credencial de acceso.' ?>
    </p>
    
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert-danger section-box">
            <p><?= session()->getFlashdata('error') ?></p>
        </div>
    <?php endif; ?>

    <?php if (isset($validation) && $validation->getErrors()): ?>
        <div class="alert-danger section-box">
            <p><strong>🚨 Error en el formulario:</strong> Por favor, revise los siguientes errores:</p>
            <ul>
                <?php foreach ($validation->getErrors() as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <?php if (isset($error)): ?>
    <div class="alert-danger section-box">
        <p><strong>❌ Error interno:</strong> <?= esc($error) ?></p>
    </div>
<?php endif; ?>


    <form action="<?= $action_url ?>" method="post">
        <?= csrf_field() ?>
        
        <?php if ($is_edit): ?>
            <input type="hidden" name="id_profesor" value="<?= esc($data_profesor['id_profesor']) ?>">
        <?php endif; ?>

        <div class="form-grid">

            <div class="form-group">
                <label for="nombre_completo">Nombre Completo:</label>
                <input type="text" name="nombre_completo" id="nombre_completo" 
                       class="form-control <?= isset($validation) && $validation->hasError('nombre_completo') ? 'is-invalid' : '' ?>" 
                       value="<?= esc($get_value('nombre_completo')) ?>" required>
                <?php if (isset($validation) && $validation->hasError('nombre_completo')): ?>
                    <div class="invalid-feedback"><?= $validation->getError('nombre_completo') ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="especialidad">Especialidad:</label>
                <input type="text" name="especialidad" id="especialidad"
                       class="form-control <?= isset($validation) && $validation->hasError('especialidad') ? 'is-invalid' : '' ?>" 
                       value="<?= esc($get_value('especialidad')) ?>" required>
                <?php if (isset($validation) && $validation->hasError('especialidad')): ?>
                    <div class="invalid-feedback"><?= $validation->getError('especialidad') ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="email">Email (Usuario de acceso):</label>
                <input type="email" name="email" id="email"
                       class="form-control <?= isset($validation) && $validation->hasError('email') ? 'is-invalid' : '' ?>" 
                       value="<?= esc($get_value('email')) ?>" required>
                <?php if (isset($validation) && $validation->hasError('email')): ?>
                    <div class="invalid-feedback"><?= $validation->getError('email') ?></div>
                <?php endif; ?>
                <?php if ($is_edit): ?>
                    <small class="form-text-muted">Si cambia el email, también cambiará su credencial de acceso.</small>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="telefono">Teléfono (Opcional):</label>
                <input type="text" name="telefono" id="telefono"
                       class="form-control <?= isset($validation) && $validation->hasError('telefono') ? 'is-invalid' : '' ?>" 
                       value="<?= esc($get_value('telefono')) ?>">
                <?php if (isset($validation) && $validation->hasError('telefono')): ?>
                    <div class="invalid-feedback"><?= $validation->getError('telefono') ?></div>
                <?php endif; ?>
            </div>
            
            <?php if (!$is_edit): ?>
            <div class="form-group full-width">
                <label for="dni_o_similar">Contraseña Inicial (DNI o similar):</label>
                <input type="text" name="dni_o_similar" id="dni_o_similar" 
                       class="form-control <?= isset($validation) && $validation->hasError('dni_o_similar') ? 'is-invalid' : '' ?>" 
                       value="<?= esc(old('dni_o_similar')) ?>" required>
                <?php if (isset($validation) && $validation->hasError('dni_o_similar')): ?>
                    <div class="invalid-feedback"><?= $validation->getError('dni_o_similar') ?></div>
                <?php endif; ?>
                <small class="form-text-muted">Se usará para crear su credencial. Se recomienda que la cambie al iniciar sesión.</small>
            </div>
            <?php endif; ?>
            
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">
                <?= $is_edit ? 'Actualizar Profesor' : 'Registrar Profesor' ?>
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
