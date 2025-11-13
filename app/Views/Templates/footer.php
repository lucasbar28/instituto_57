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
</footer>

<!-- Vendor scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/glider-js/1.7.8/glider.min.js"></script>

<!-- SweetAlert2 (debe cargarse antes de carte.js) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  // Inyecta la URL absoluta de la imagen (resuelta por PHP)
  window.ALERT_IMAGE = "<?= base_url('img/alert_carreras_2026.png') ?>";
  // Por defecto permitir que el script muestre la alerta (la vista puede cambiarlo)
  // window.SHOW_INTRO = true; // opcional setear desde la vista
</script>

<!-- Script que muestra la alerta (usa window.ALERT_IMAGE) -->
<script src="<?= base_url('js/carte.js') ?>"></script>


<!-- Carousel initializer -->
<script src="<?= base_url('js/carousel.js') ?>"></script>
<!-- App script (única inclusión) -->
<script src="<?= base_url('js/app.js') ?>"></script>

</body>
</html>