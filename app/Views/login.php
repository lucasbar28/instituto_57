<?php 
/**
 * Vista: Formulario de Inicio de Sesión.
 * Extiende el layout principal 'templates/layout'.
 */
?>
<?= $this->extend('templates/layout') ?> 

<?= $this->section('content') ?>

<div class="container mt-5 step-card-login">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="font-weight-light my-4"><i class="fas fa-sign-in-alt "></i> Iniciar Sesión</h3>
                </div>
                <div class="card-body">
                    
                    <!-- 1. ALERTAS (Mensajes de éxito/error) -->
                    <?= view('templates/_alerts') ?>

                    <form action="<?= base_url('login') ?>" method="post">
                        <?= csrf_field() ?>
                        
                        <!-- Nombre de Usuario / Identificador -->
                        <div class="form-group mb-3">
                            <label class="small mb-1" for="nombre_de_usuario">Nombre de Usuario / Identificador</label>
                            <input class="form-control py-4" id="nombre_de_usuario" name="nombre_de_usuario" type="text" placeholder="Ingrese su nombre de usuario o DNI" 
                                value="<?= old('nombre_de_usuario') ?>" required>
                            <?php if (session('errors.nombre_de_usuario')): ?>
                                <div class="invalid-feedback d-block"><?= session('errors.nombre_de_usuario') ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Contraseña -->
                        <div class="form-group mb-4">
                            <label class="small mb-1" for="inputPassword">Contraseña</label>
                            <input class="form-control py-4" id="inputPassword" name="password" type="password" placeholder="Ingrese su contraseña" required>
                            <?php if (session('errors.password')): ?>
                                <div class="invalid-feedback d-block"><?= session('errors.password') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                            <!-- Enlace de registro -->
                            <a class="small" href="<?= base_url('registro') ?>">¿No tienes cuenta? Regístrate aquí</a>
                            
                            <button type="submit" class="btn btn-primary btn-lg">
                                Entrar <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?> 