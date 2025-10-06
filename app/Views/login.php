<?php
/**
 * Vista del Formulario de Inicio de Sesión (Login)
 * Utiliza el layout principal y garantiza la protección CSRF.
 */
?>
<?= $this->extend('templates/layout') ?> 

<?= $this->section('content') ?>

<div class="login-container">
    <div class="login-card">
        <h1 class="text-center mb-4">Iniciar Sesión</h1>
        
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert-danger text-center mb-4">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('mensaje')): ?>
            <div class="alert-success text-center mb-4">
                <?= session()->getFlashdata('mensaje') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('login/auth') ?>" method="post">
            <!-- CAMPO CSRF ESENCIAL PARA PREVENIR EL ERROR 403 -->
            <?= csrf_field() ?> 

            <div class="form-group">
                <label for="username">Usuario (Email):</label>
                <input type="email" name="username" class="form-control" required value="<?= old('username') ?>" placeholder="admin@instituto.com">
            </div>

            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" name="password" class="form-control" required placeholder="Contraseña">
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