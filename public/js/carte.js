// ...existing code...
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    try {
      // Si la vista decide NO mostrar el intro (ej: window.SHOW_INTRO === false) respetarlo
      if (typeof window.SHOW_INTRO !== 'undefined' && window.SHOW_INTRO === false) {
        return;
      }

      // Evitar mostrar si ya se mostró antes en este navegador
      if (localStorage.getItem('intro_shown')) return;

      // Comprueba que SweetAlert2 esté cargado
      if (typeof Swal === 'undefined') {
        console.error('SweetAlert2 no está cargado. La alerta no se mostrará.');
        return;
      }

      // Imagen inyectada desde footer.php; fallback a ruta relativa si no existe
      var imgUrl = window.ALERT_IMAGE || '<?= base_url('img/alert_carreras_2026.png') ?>'; // placeholder para editores; footer inyecta real

      // Mostrar alerta con contenido controlado (texto antes de imagen)
      Swal.fire({
        html: `
          <div style="text-align:center;">
            <h2 style="margin:0 0 8px;">¡Nuevas Carreras 2026!</h2>
            <p style="margin:0 0 12px;">Conocé las novedades antes de continuar.</p>
            <img src="${imgUrl}" alt="Alert Carreras 2026" style="max-width:100%;height:auto;border-radius:6px;">
          </div>
        `,
        showConfirmButton: true,
        confirmButtonText: 'Entrar',
        allowOutsideClick: false,
        allowEscapeKey: false,
        width: '650px'
      }).then(function () {
        try { localStorage.setItem('intro_shown', '1'); } catch (e) { console.warn('No se pudo escribir localStorage', e); }
      });
    } catch (err) {
      console.error('Error en carte.js:', err);
    }
  });
})();
