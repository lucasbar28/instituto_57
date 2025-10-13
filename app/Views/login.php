<?php
/**
 * Vista del Formulario de Inicio de Sesión (Login)
 * Se asegura de usar los nombres de campo correctos para el controlador (nombre_de_usuario y contrasena)
 */
?>
<?= $this->extend('templates/layout') ?> 

<?= $this->section('content') ?>

<div class="login-container">
    <div class="login-card">
        <h1 class="text-center mb-4">Iniciar Sesión</h1>
        
        <?php 
        // Mostrar mensaje de error (usando 'msg' ya que es lo que el controlador envía)
        if (session()->getFlashdata('msg')): 
        ?>
            <div class="alert-danger text-center mb-4">
                <?= session()->getFlashdata('msg') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('mensaje')): ?>
            <div class="alert-success text-center mb-4">
                <?= session()->getFlashdata('mensaje') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('login/auth') ?>" method="post">
            <!-- CAMPO CSRF ESENCIAL para evitar el error 403 de seguridad -->
            <?= csrf_field() ?> 

            <div class="form-group">
                <label for="nombre_de_usuario">Usuario (Email):</label>
                <!-- CAMPO CORREGIDO: ahora usa name="nombre_de_usuario" -->
                <input type="email" name="nombre_de_usuario" class="form-control" required value="<?= old('nombre_de_usuario') ?>" placeholder="jefe@instituto.com">
            </div>

            <div class="form-group">
                <label for="contrasena">Contraseña:</label>
                <!-- CAMPO CORREGIDO: ahora usa name="contrasena" -->
                <input type="password" name="contrasena" class="form-control" required placeholder="Contraseña">
            </div>

            <button type="submit" class="btn btn-primary btn-block mt-4">Entrar</button>
        </form>
    </div>
</div>

<style>
/* Estilos específicos para la página de login, centrando el formulario */
.login-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 80vh; /* Centra en la mayor parte de la pantalla */
    padding: 20px;
}

.login-card {
    background-color: #ffffff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 400px; /* Tamaño máximo del formulario */
}

.alert-danger {
    background-color: #fdd8d8;
    color: #cc0000;
    padding: 10px;
    border-radius: 6px;
    border: 1px solid #cc0000;
}

.alert-success {
    background-color: #d8fdd8;
    color: #00cc00;
    padding: 10px;
    border-radius: 6px;
    border: 1px solid #00cc00;
}
</style>

<?= $this->endSection() ?>
 