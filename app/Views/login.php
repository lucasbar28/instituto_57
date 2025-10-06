<?= $this->extend('templates/layout') ?> 

<?= $this->section('content') ?>

<div class="login-container" style="max-width: 400px; margin: 50px auto; padding: 20px;">
    
    <div class="page-header">
        <h1>Iniciar Sesión</h1>
        <p class="page-subtitle">Accede a la plataforma de gestión académica.</p>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert-error section-box">
            <p><?= session()->getFlashdata('error') ?></p>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert-success section-box">
            <p><?= session()->getFlashdata('success') ?></p>
        </div>
    <?php endif; ?>

    <div class="section-box">
        <form action="<?= base_url('auth') ?>" method="post">
            
            <div class="form-group">
                <label for="nombre_de_usuario">Nombre de usuario:</label>
                <input type="text" name="nombre_de_usuario" class="form-control" required value="<?= old('nombre_de_usuario') ?>">
            </div>

            <div class="form-group">
                <label for="contrasena">Contraseña:</label>
                <input type="password" name="contrasena" class="form-control" required>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block" style="margin-top: 20px;">Iniciar sesión</button>
        </form> 
    </div>
</div>

<?= $this->endSection() ?> 