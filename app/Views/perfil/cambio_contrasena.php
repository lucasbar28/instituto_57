<?= $this->extend('templates/layout') ?> 

<?= $this->section('content') ?>

<div class="container mt-5 step-card-login">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center">
                    <h1 class="h4 mb-0"><?= esc($title) ?></h1>
                </div>
                <div class="card-body p-4">

                    <!-- Mensaje de Advertencia si es CAMBIO OBLIGATORIO -->
                    <?php if (isset($is_forced) && $is_forced): ?>
                        <div class="alert alert-warning text-center" role="alert">
                            <i class="fas fa-exclamation-triangle"></i> 
                            Por su seguridad, debe establecer una nueva contraseña antes de continuar.
                        </div>
                    <?php endif; ?>

                    <!-- Manejo de Mensajes Flash de Sesión -->
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?></div>
                    <?php endif; ?>
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger"><i class="fas fa-times-circle"></i> <?= session()->getFlashdata('error') ?></div>
                    <?php endif; ?>
                    <?php if (session()->getFlashdata('warning')): ?>
                        <div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> <?= session()->getFlashdata('warning') ?></div>
                    <?php endif; ?>

                    <!-- Mostrar Errores de Validación (Del Controlador Perfil) -->
                    <?php if (isset($validation) && $validation->getErrors()): ?>
                        <div class="alert alert-danger" role="alert">
                            <h4 class="alert-heading">¡Error de Validación!</h4>
                            <ul>
                                <?php foreach ($validation->getErrors() as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <!-- Formulario de Cambio -->
                    <form action="<?= base_url('perfil/actualizar-contrasena') ?>" method="post">
                        <?= csrf_field() ?>

                        <!-- Nueva Contraseña -->
                        <div class="mb-4">
                            <label for="contrasena" class="form-label">Nueva Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" id="contrasena" name="contrasena" 
                                       class="form-control" 
                                       placeholder="Mínimo 8 caracteres" required>
                            </div>
                        </div>

                        <!-- Confirmar Contraseña -->
                        <div class="mb-4">
                            <label for="confirmacion_contrasena" class="form-label">Confirmar Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" id="confirmacion_contrasena" name="confirmacion_contrasena" 
                                       class="form-control" 
                                       placeholder="Repetir nueva contraseña" required>
                            </div>
                        </div>

                        <button type="submit" 
                                class="btn btn-primary w-100 mt-3 py-2 fw-bold">
                            <i class="fas fa-save"></i> Establecer Nueva Contraseña
                        </button>
                        
                        <?php if (isset($is_forced) && !$is_forced): ?>
                            <!-- Mostrar solo si el cambio NO es obligatorio -->
                            <div class="text-center mt-3">
                                <a href="<?= base_url('/') ?>" class="text-secondary text-decoration-none">Volver al Panel Principal</a>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
 