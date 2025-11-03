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

// Espera a que el DOM esté completamente cargado antes de ejecutar el código
document.addEventListener('DOMContentLoaded', () => {
    
    const gliderElement = document.querySelector('.glider');
    
    if (gliderElement) {
        // Almacenamos la instancia de Glider en la variable 'gliderInstance'
        const gliderInstance = new Glider(gliderElement, {
            // ... (Tus opciones de configuración actuales) ...
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
                        slidesToShow: 1,
                        itemWidth: 300,
                        slidesToScroll: 1,
                    }
                }
            ]
        });

        // ----------------------------------------------------
        // LÓGICA DE MOVIMIENTO AUTOMÁTICO (Cada 3 segundos) ⏱️
        // ----------------------------------------------------
        
        const autoSlideInterval = 3000; // 3000 milisegundos = 3 segundos
        let timeout = null;

        // Función que avanza al siguiente slide y reinicia el temporizador
        function nextSlideAndLoop() {
            // Usa el método .scrollItem() para ir al siguiente elemento
            gliderInstance.scrollItem('next');
            
            // Llama a 'resume' para reiniciar el temporizador y crear el loop
            resumeAutoSlide(); 
        }

        // Función para detener el movimiento automático
        function pauseAutoSlide() {
            clearTimeout(timeout);
        }

        // Función para iniciar o reanudar el movimiento automático
        function resumeAutoSlide() {
            pauseAutoSlide(); // Asegura que no haya temporizadores duplicados
            timeout = setTimeout(nextSlideAndLoop, autoSlideInterval);
        }

        // 1. Inicia el movimiento automático al cargar la página
        resumeAutoSlide(); 

        // 2. Opcional: Pausa el auto-avance al pasar el mouse (mejor UX)
        gliderElement.addEventListener('mouseover', pauseAutoSlide);
        gliderElement.addEventListener('mouseout', resumeAutoSlide);
    }
});