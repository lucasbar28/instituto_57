<?php 
/**
 * Vista: Formulario de Registro para un nuevo Alumno.
 * Extiende el layout principal 'templates/layout' (o el layout que uses para el front-end).
 */
?>
<?= $this->extend('templates/layout') ?> 

<?= $this->section('content') ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="font-weight-light my-4"><i class="fas fa-user-plus"></i> Registro de Nuevo Alumno</h3>
                </div>
                <div class="card-body">
                    
                    <!-- 1. ALERTAS (Mensajes de éxito/error) -->
                    <?= view('templates/_alerts') ?>

                    <form action="<?= base_url('registro/alumno') ?>" method="post">
                        <?= csrf_field() ?>
                        
                        <!-- DNI / Matrícula -->
                        <div class="form-group mb-3">
                            <label class="small mb-1" for="inputDNI">DNI / Matrícula</label>
                            <input class="form-control py-4" id="inputDNI" name="dni_matricula" type="text" placeholder="Ingrese DNI o Matrícula" 
                                value="<?= old('dni_matricula') ?>" required>
                            <?php if (session('errors.dni_matricula')): ?>
                                <div class="invalid-feedback d-block"><?= session('errors.dni_matricula') ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Nombre Completo -->
                        <div class="form-group mb-3">
                            <label class="small mb-1" for="inputNombre">Nombre Completo</label>
                            <input class="form-control py-4" id="inputNombre" name="nombre_completo" type="text" placeholder="Ingrese nombre y apellido" 
                                value="<?= old('nombre_completo') ?>" required>
                            <?php if (session('errors.nombre_completo')): ?>
                                <div class="invalid-feedback d-block"><?= session('errors.nombre_completo') ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Email (Username para el login) -->
                        <div class="form-group mb-3">
                            <label class="small mb-1" for="inputEmail">Correo Electrónico</label>
                            <input class="form-control py-4" id="inputEmail" name="email" type="email" aria-describedby="emailHelp" placeholder="Ingrese su correo electrónico" 
                                value="<?= old('email') ?>" required>
                            <?php if (session('errors.email')): ?>
                                <div class="invalid-feedback d-block"><?= session('errors.email') ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Contraseña -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="small mb-1" for="inputPassword">Contraseña</label>
                                    <input class="form-control py-4" id="inputPassword" name="password" type="password" placeholder="Cree una contraseña" required>
                                    <?php if (session('errors.password')): ?>
                                        <div class="invalid-feedback d-block"><?= session('errors.password') ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="small mb-1" for="inputConfirmPassword">Confirmar Contraseña</label>
                                    <input class="form-control py-4" id="inputConfirmPassword" name="pass_confirm" type="password" placeholder="Confirme la contraseña" required>
                                    <?php if (session('errors.pass_confirm')): ?>
                                        <div class="invalid-feedback d-block"><?= session('errors.pass_confirm') ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Carrera (Select) -->
                        <div class="form-group mb-4">
                            <label class="small mb-1" for="selectCarrera">Seleccione su Carrera</label>
                            <select class="form-control py-3" id="selectCarrera" name="id_carrera" required>
                                <option value="">--- Seleccionar ---</option>
                                <?php foreach ($carreras as $carrera): ?>
                                    <option value="<?= esc($carrera['id_carrera']) ?>" 
                                            <?= old('id_carrera') == $carrera['id_carrera'] ? 'selected' : '' ?>>
                                        <?= esc($carrera['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (session('errors.id_carrera')): ?>
                                <div class="invalid-feedback d-block"><?= session('errors.id_carrera') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                            <a class="small" href="<?= base_url('login') ?>">¿Ya tienes cuenta? Inicia sesión</a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-check-circle"></i> Registrarse
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
 