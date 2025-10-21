// ==================== FUNCIONES COMUNES (Mantenidas) ====================
// Función para mostrar mensajes de éxito o error usando SweetAlert2
function showSuccess(msg) {
    Swal.fire("Éxito", msg, "success");
}
// Función para mostrar mensajes de error usando SweetAlert2
function showError(msg) {
    Swal.fire("Error", msg, "error");
}

// ==================== FUNCIONES DE CONFIRMACIÓN (Mantenidas) ====================
// Nota: Las funciones de confirmación como 'confirmDeleteStudent' ahora solo mostrarán el modal.
// El manejo de la eliminación y la recarga de la página se hará vía PHP/Formularios/Redirección,
// ya que ya no hay un `loadAndDisplayStudents` en JS.

/**
 * Muestra el modal de confirmación para eliminar un estudiante.
 * * @param {number} id - El ID del estudiante.
 * @param {string} deleteUrl - La URL de CodeIgniter para manejar la eliminación.
 */
window.confirmDelete = async function(deleteUrl) {
    const confirm = await Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción no puede deshacerse. Asegúrate de que no haya dependencias.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    });
    
    if (confirm.isConfirmed) {
        // Redirige a la URL de CodeIgniter para que el controlador maneje la eliminación.
        // La URL debe ser generada previamente en la vista PHP.
        window.location.href = deleteUrl;
    }
};

// ==================== INICIALIZACIÓN ====================
// Espera a que el DOM esté completamente cargado antes de ejecutar el código
document.addEventListener('DOMContentLoaded', () => {
    // Esto elimina todo el código de formularios, carga de tablas y AJAX que ya no necesitamos.
    // Solo dejamos la inicialización de Glider.js si existe en la página.
    
    // Inicialización de Glider.js (para la página de inicio)
    const gliderElement = document.querySelector('.glider');
    if (gliderElement) {
        // Nota: Asume que la librería Glider.js está cargada en tu layout.
        // Si no está cargada, esta línea causará un error "Glider is not defined".
        new Glider(gliderElement, {
            slidesToShow: 1,
            dots: '.dots',
            arrows: {
                prev: '.glider-prev',
                next: '.glider-next'
            },
            responsive: [
                {
                    // Pantallas mayores a 768px
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 'auto',
                        itemWidth: 300,
                        slidesToScroll: 1,
                    }
                }
            ]
        });
    }
});

