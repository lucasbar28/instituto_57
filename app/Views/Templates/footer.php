<footer class="main-footer">
    <div class="footer-content">
        <p>&copy; <?= date('Y') ?> Instituto 57. Todos los derechos reservados.</p>
        <p> Grupo 4: Barreu - Bertoglio - González - Newton - Sarmiento - Vázquez</p>
        <div class="social-links">
            <a href="tel:+542241422051" aria-label="Llamar al Instituto 57"><i class="fas fa-phone-alt"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="https://www.facebook.com/instituto57chascomus/" target="_blank"><i class="fab fa-facebook"></i></a>
            <a href="https://www.instagram.com/instituto57chascomus/" target="_blank"><i class="fab fa-instagram"></i></a>
        </div>
    </div>
<!-- ...existing code... -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
document.addEventListener('DOMContentLoaded', function () {
    // Mostrar solo una vez por navegador
    if (!localStorage.getItem('intro_shown')) {
        Swal.fire({
            // usamos 'html' para controlar orden: texto primero, luego imagen
            html: `
                <div style="text-align:center;">
                    <h2 style="margin:0 0 8px;">¡Nuevas Carreras 2026!</h2>
                    <p style="margin:0 0 12px;">Conocé las novedades antes de continuar.</p>
                    <img src="<?= base_url('img/alert_carreras_2026.png') ?>" alt="Alert Carreras 2026" style="max-width:100%;height:auto;border-radius:6px;">
                </div>
            `,
            showConfirmButton: true,
            confirmButtonText: 'Entrar',
            allowOutsideClick: false,
            allowEscapeKey: false,
            width: '500px'
        }).then(function () {
            localStorage.setItem('intro_shown', '1');
        });
    }
});
</script>

<script src="<?= base_url('js/app.js') ?>"></script>
</body>
</html>

</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/glider-js/1.7.8/glider.min.js"></script>
<script src="<?= base_url('js/app.js') ?>"></script>

</body>
</html>