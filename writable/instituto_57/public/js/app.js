// Configuración de la API
const API_BASE = "http://localhost:5001/api";
const API_KEY = "12345ABCDEF";

// Encabezados comunes para las solicitudes HTTP
const headers = {
    "Content-Type": "application/json",
    "Authorization": `Bearer ${API_KEY}`
};

// ==================== FUNCIONES COMUNES ====================
// Función para mostrar mensajes de éxito o error usando SweetAlert2
function showSuccess(msg) {
    Swal.fire("Éxito", msg, "success");
}
// Función para mostrar mensajes de error usando SweetAlert2
function showError(msg) {
    Swal.fire("Error", msg, "error");
}

// ==================== FUNCIONES PARA ESTUDIANTES ====================
// Registra un nuevo estudiante
async function registerStudent(name, career) {
    const response = await fetch(`${API_BASE}/students`, {
        method: "POST",
        headers,
        body: JSON.stringify({ name, career })
    });
    if (!response.ok) throw new Error(await response.text());
    return response.json();
}
// Obtiene todos los estudiantes
async function getStudentById(id) {
    const response = await fetch(`${API_BASE}/students/${id}`, { headers });
    if (!response.ok) throw new Error(await response.text());
    return response.json();
}
// Obtiene estudiantes filtrados por carrera
async function getStudentsByCareer(career = '') {
    try {
        const url = `${API_BASE}/students?career=${encodeURIComponent(career)}`;
        const response = await fetch(url, { headers });
        if (!response.ok) throw new Error(await response.text());
        return await response.json();
    } catch (error) {
        console.error("Error en getStudentsByCareer:", error);
        throw error;
    }
}
// Obtiene todos los estudiantes
async function updateStudent(id, data) {
    const response = await fetch(`${API_BASE}/students/${id}`, {
        method: "PUT", 
        headers,
        body: JSON.stringify(data)
    });
    if (!response.ok) {
        // Mejorar el manejo de errores para obtener el mensaje real del backend
        const errorText = await response.text();
        let errorMessage = "Error desconocido al actualizar estudiante.";
        try {
            const errorJson = JSON.parse(errorText);
            errorMessage = errorJson.error || errorMessage;
        } catch (e) {
            errorMessage = errorText || errorMessage;
        }
        throw new Error(errorMessage);
    }
    return response.json();
}
// Elimina un estudiante por ID
async function deleteStudent(id) {
    const response = await fetch(`${API_BASE}/students/${id}`, { 
        method: "DELETE", 
        headers 
    });
    if (!response.ok) throw new Error(await response.text());
    return response.json();
}

// ==================== FUNCIONES PARA CARRERAS ====================
// Registra una nueva carrera
async function registerCareer(name, category, duration) {
    const response = await fetch(`${API_BASE}/careers`, {
        method: "POST",
        headers,
        body: JSON.stringify({ name, categoryId: category, duration }) 
    });
    if (!response.ok) throw new Error(await response.text());
    return response.json();
}
// Obtiene todas las carreras
async function getCareers() {
    const response = await fetch(`${API_BASE}/careers`, { headers });
    if (!response.ok) throw new Error(await response.text());
    return response.json();
}
// Obtiene una carrera por ID
async function getCareerById(id) {
    const response = await fetch(`${API_BASE}/careers/${id}`, { headers });
    if (!response.ok) throw new Error(await response.text());
    return response.json();
}
// Actualiza una carrera por ID
async function updateCareer(id, data) {
    const response = await fetch(`${API_BASE}/careers/${id}`, {
        method: "PUT", 
        headers,
        body: JSON.stringify(data)
    });
    if (!response.ok) {
        const errorText = await response.text();
        let errorMessage = "Error desconocido al actualizar carrera.";
        try {
            const errorJson = JSON.parse(errorText);
            errorMessage = errorJson.error || errorMessage;
        } catch (e) {
            errorMessage = errorText || errorMessage;
        }
        throw new Error(errorMessage);
    }
    return response.json();
}
// Elimina una carrera por ID
async function deleteCareer(id) {
    const response = await fetch(`${API_BASE}/careers/${id}`, { 
        method: "DELETE", 
        headers 
    });
    if (!response.ok) throw new Error(await response.text());
    return response.json();
}

// ==================== FUNCIONES PARA CATEGORÍAS ====================
// Registra una nueva categoría
async function registerCategory(name) {
    const response = await fetch(`${API_BASE}/categories`, {
        method: "POST",
        headers,
        body: JSON.stringify({ name })
    });
    if (!response.ok) throw new Error(await response.text());
    return response.json();
}
// Obtiene todas las categorías
async function getCategories() {
    const response = await fetch(`${API_BASE}/categories`, { headers });
    if (!response.ok) throw new Error(await response.text());
    return response.json();
}
// Obtiene una categoría por ID
async function getCategoryById(id) {
    const response = await fetch(`${API_BASE}/categories/${id}`, { headers });
    if (!response.ok) throw new Error(await response.text());
    return response.json();
}
// Actualiza una categoría por ID
async function updateCategory(id, data) {
    const response = await fetch(`${API_BASE}/categories/${id}`, {
        method: "PUT", // Asegúrate de que coincida con tu backend (en backend es PUT)
        headers,
        body: JSON.stringify(data)
    });
    if (!response.ok) {
        const errorText = await response.text();
        let errorMessage = "Error desconocido al actualizar categoría.";
        try {
            const errorJson = JSON.parse(errorText);
            errorMessage = errorJson.error || errorMessage;
        } catch (e) {
            errorMessage = errorText || errorMessage;
        }
        throw new Error(errorMessage);
    }
    return response.json();
}
// Elimina una categoría por ID
async function deleteCategory(id) {
    const response = await fetch(`${API_BASE}/categories/${id}`, { 
        method: "DELETE", 
        headers 
    });
    if (!response.ok) throw new Error(await response.text());
    return response.json();
}

// ==================== FUNCIONES DE VISUALIZACIÓN ====================
// Carga y muestra los estudiantes en la tabla
async function loadAndDisplayStudents() {
    try {
        const students = await getStudentsByCareer(''); 
        const careers = await getCareers(); // Obtener carreras para el select de edición

        const tableBody = document.getElementById('studentsTableBody');
        if (!tableBody) return;

        tableBody.innerHTML = students.map(student => {
            return `
                <tr>
                    <td>${student.id}</td>
                    <td>${student.name}</td>
                    <td>${student.career || 'Sin carrera'}</td>
                    <td class="action-buttons">
                        <button class="btn-action btn-edit" onclick="editStudent(${student.id})">
                            <i class="fas fa-edit"></i> Editar
                        </button>
                        <button class="btn-action btn-delete" onclick="confirmDeleteStudent(${student.id})">
                            <i class="fas fa-trash-alt"></i> Eliminar
                        </button>
                    </td>
                </tr>
            `;
        }).join('');
    } catch (error) {
        console.error("Error al cargar estudiantes:", error);
        showError("Error al cargar estudiantes: " + error.message);
    }
}
// Carga y muestra las carreras en la tabla
async function loadAndDisplayCareers() {
    try {
        const [careers, categories] = await Promise.all([
            getCareers(),
            getCategories()
        ]);
        
        const tableBody = document.getElementById('careersTableBody');
        if (!tableBody) return;
        
        tableBody.innerHTML = careers.map(career => {
            // Buscar el nombre de la categoría por su ID
            const categoryName = categories.find(cat => cat.id === career.categoryId)?.name || 'Desconocida';
            return `
                <tr>
                    <td>${career.id}</td>
                    <td>${career.name}</td>
                    <td>${categoryName}</td>
                    <td>${career.duration || '-'} años</td>
                    <td class="action-buttons">
                        <button class="btn-action btn-edit" onclick="editCareer(${career.id})">
                            <i class="fas fa-edit"></i> Editar
                        </button>
                        <button class="btn-action btn-delete" onclick="confirmDeleteCareer(${career.id})">
                            <i class="fas fa-trash-alt"></i> Eliminar
                        </button>
                    </td>
                </tr>
            `;
        }).join('');
    } catch (error) {
        console.error("Error al cargar carreras:", error);
        showError("Error al cargar carreras: " + error.message);
    }
}
// Carga y muestra las categorías en la tabla
async function loadAndDisplayCategories() {
    try {
        const categories = await getCategories();
        const tableBody = document.getElementById('categoriesTableBody');
        if (!tableBody) return;

        tableBody.innerHTML = categories.map(category => `
            <tr>
                <td>${category.id}</td>
                <td>${category.name}</td>
                <td class="action-buttons">
                    <button class="btn-action btn-edit" onclick="editCategory(${category.id})">
                        <i class="fas fa-edit"></i> Editar
                    </button>
                    <button class="btn-action btn-delete" onclick="confirmDeleteCategory(${category.id})">
                        <i class="fas fa-trash-alt"></i> Eliminar
                    </button>
                </td>
            </tr>
        `).join('');
    } catch (error) {
        console.error("Error al cargar categorías:", error);
        showError("Error al cargar categorías: " + error.message);
    }
}

// ==================== FUNCIONES DE EDICIÓN (enlazadas al window para ser accesibles) ====================
// Edita un estudiante
window.editStudent = async function(id) {
    try {
        const student = await getStudentById(id);
        const careers = await getCareers(); // Obtener lista de carreras para el select
        
        const { value: formValues } = await Swal.fire({
            title: 'Editar Estudiante',
            html:
                `<input id="swal-input-name" class="swal2-input" value="${student.name}" required>
                 <select id="swal-input-career" class="swal2-input" required>
                    <option value="">Selecciona una carrera</option>
                    ${careers.map(c => `<option value="${c.name}" ${c.name === student.career ? 'selected' : ''}>${c.name}</option>`).join('')}
                 </select>`,
            focusConfirm: false,
            preConfirm: () => {
                const name = document.getElementById('swal-input-name').value;
                const career = document.getElementById('swal-input-career').value;
                if (!name || !career) {
                    Swal.showValidationMessage('Por favor, completa ambos campos');
                    return false; // Evita que se cierre el modal
                }
                return { name, career };
            }
        });

        if (formValues) {
            await updateStudent(id, formValues);
            showSuccess('Estudiante actualizado correctamente');
            await loadAndDisplayStudents(); // Recargar la tabla de estudiantes
        }
    } catch (error) {
        console.error("Error en editStudent:", error);
        showError('Error al editar estudiante: ' + error.message);
    }
};
// Edita una carrera
window.editCareer = async function(id) {
    try {
        const [career, categories] = await Promise.all([
            getCareerById(id),
            getCategories()
        ]);
        
        const { value: formValues } = await Swal.fire({
            title: 'Editar Carrera',
            html:
                `<input id="swal-input-name" class="swal2-input" value="${career.name}" required>
                 <select id="swal-input-category" class="swal2-input" required>
                    <option value="">Selecciona una categoría</option>
                    ${categories.map(c => `<option value="${c.id}" ${c.id === career.categoryId ? 'selected' : ''}>${c.name}</option>`).join('')}
                 </select>
                 <input id="swal-input-duration" class="swal2-input" type="number" min="1" max="6" value="${career.duration || 4}" required>`,
            focusConfirm: false,
            preConfirm: () => {
                const name = document.getElementById('swal-input-name').value;
                const categoryId = parseInt(document.getElementById('swal-input-category').value);
                const duration = parseInt(document.getElementById('swal-input-duration').value);

                if (!name || !categoryId || !duration) {
                    Swal.showValidationMessage('Por favor, completa todos los campos');
                    return false;
                }
                return { name, categoryId, duration };
            }
        });

        if (formValues) {
            await updateCareer(id, formValues);
            showSuccess('Carrera actualizada correctamente');
            await loadAndDisplayCareers();
            await populateSelects(); // Actualizar selects en otras páginas si es necesario
        }
    } catch (error) {
        console.error("Error en editCareer:", error);
        showError('Error al editar carrera: ' + error.message);
    }
};
// Edita una categoría
window.editCategory = async function(id) {
    try {
        const category = await getCategoryById(id);
        
        const { value: name } = await Swal.fire({
            title: 'Editar Categoría',
            input: 'text',
            inputValue: category.name,
            showCancelButton: true,
            inputValidator: (value) => {
                if (!value) {
                    return 'El nombre no puede estar vacío!';
                }
            }
        });

        if (name) {
            await updateCategory(id, { name });
            showSuccess('Categoría actualizada correctamente');
            await loadAndDisplayCategories();
            await populateSelects();
        }
    } catch (error) {
        console.error("Error en editCategory:", error);
        showError('Error al editar categoría: ' + error.message);
    }
};


// ==================== FUNCIONES AUXILIARES ====================
// Función para limpiar formularios (opcional, si se usa SweetAlert2)
async function populateSelects() {
    try {
        const [careers, categories] = await Promise.all([getCareers(), getCategories()]);

        // Actualizar selects de carreras (ej. para registro de estudiantes)
        const careerSelects = ['registerCareer', 'careerFilter']; // IDs de los selects de carrera
        careerSelects.forEach(selectId => {
            const select = document.getElementById(selectId);
            if (select) {
                const currentValue = select.value; // Guardar el valor actual para intentar restaurarlo
                select.innerHTML = '<option value="">Selecciona una carrera</option>'; // Limpiar opciones
                careers.forEach(career => {
                    const option = new Option(career.name, career.name);
                    select.appendChild(option);
                });
                // Restaurar el valor si existe y es válido
                if (currentValue && careers.some(c => c.name === currentValue)) {
                    select.value = currentValue;
                }
            }
        });

        // Actualizar selects de categorías (ej. para registro de carreras)
        const categorySelect = document.getElementById('careerCategory'); // ID del select de categoría
        if (categorySelect) {
            const currentValue = categorySelect.value;
            categorySelect.innerHTML = '<option value="">Selecciona una categoría</option>';
            categories.forEach(cat => {
                const option = new Option(cat.name, cat.id); // Usamos el ID como valor
                categorySelect.appendChild(option);
            });
            if (currentValue && categories.some(c => c.id === parseInt(currentValue))) {
                categorySelect.value = currentValue;
            }
        }
    } catch (error) {
        console.error("Error al cargar selects:", error);
        showError("Error al cargar opciones: " + error.message);
    }
}

// ==================== FUNCIONES DE CONFIRMACIÓN (enlazadas al window) ====================
// Funciones de confirmación para eliminar entidades
window.confirmDeleteStudent = async function(id) {
    const confirm = await Swal.fire({
        title: '¿Eliminar estudiante?',
        text: 'Esta acción no puede deshacerse',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar'
    });
    if (confirm.isConfirmed) {
        try {
            await deleteStudent(id);
            await loadAndDisplayStudents();
            showSuccess("Estudiante eliminado");
        } catch (err) {
            showError(err.message || "Error al eliminar estudiante");
        }
    }
};
// Funciones de confirmación para eliminar carreras 
window.confirmDeleteCareer = async function(id) {
    const confirm = await Swal.fire({
        title: '¿Eliminar carrera?',
        text: 'No se puede eliminar si tiene estudiantes asociados',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar'
    });
    if (confirm.isConfirmed) {
        try {
            await deleteCareer(id);
            await loadAndDisplayCareers();
            await populateSelects(); // Actualizar selects por si cambian las carreras
            showSuccess("Carrera eliminada");
        } catch (err) {
            showError(err.message || "No se pudo eliminar (¿tiene estudiantes asociados?)");
        }
    }
};
// Funciones de confirmación para eliminar categorías
window.confirmDeleteCategory = async function(id) {
    const confirm = await Swal.fire({
        title: '¿Eliminar categoría?',
        text: 'No se puede eliminar si tiene carreras asociadas',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar'
    });
    if (confirm.isConfirmed) {
        try {
            await deleteCategory(id);
            await loadAndDisplayCategories();
            await populateSelects(); // Actualizar selects por si cambian las categorías
            showSuccess("Categoría eliminada");
        } catch (err) {
            showError(err.message || "No se pudo eliminar (¿tiene carreras asociadas?)");
        }
    }
};

// ==================== MANEJADORES DE FORMULARIOS ====================
// Maneja el registro de estudiantes
async function handleStudentRegistration() {
    const name = document.getElementById('registerName')?.value.trim();
    // Obtener el nombre de la carrera directamente del texto seleccionado
    const careerSelect = document.getElementById('registerCareer');
    const career = careerSelect?.options[careerSelect.selectedIndex]?.text;

    if (!name || !career) return showError("Completa todos los campos");

    try {
        await registerStudent(name, career);
        showSuccess("Estudiante registrado");
        document.getElementById('registerForm')?.reset(); // Limpiar formulario
        await loadAndDisplayStudents(); // Recargar la tabla
    } catch (err) {
        showError(err.message || "Error al registrar estudiante");
    }
}
// Maneja la búsqueda de estudiantes por ID
async function handleSearchById() {
    const idInput = document.getElementById('studentId');
    const id = idInput?.value;
    if (!id) return showError("Ingresa un ID");

    try {
        const student = await getStudentById(id);
        const container = document.getElementById('getResult');
        if (!student.id) { // Si el backend devuelve un objeto vacío o sin ID
            container.innerHTML = '<p>Estudiante no encontrado</p>';
        } else {
            container.innerHTML = `
                <div class="student-card">
                    <strong>ID:</strong> ${student.id}<br>
                    <strong>Nombre:</strong> ${student.name}<br>
                    <strong>Carrera:</strong> ${student.career || 'Sin carrera'}
                </div>
            `;
        }
    } catch (err) {
        console.error("Error en handleSearchById:", err);
        showError(err.message || "Error al buscar estudiante por ID");
    }
}
// Maneja el registro de carreras
async function handleCareerRegistration() {
    const name = document.getElementById('careerName')?.value.trim();
    const categoryId = document.getElementById('careerCategory')?.value; // Obtener el ID de la categoría
    const duration = document.getElementById('careerDuration')?.value;

    if (!name || !categoryId || !duration) return showError("Completa todos los campos");

    try {
        await registerCareer(name, parseInt(categoryId), parseInt(duration)); // Enviar ID y duración como números
        showSuccess("Carrera registrada");
        document.getElementById('careerForm')?.reset();
        await loadAndDisplayCareers();
        await populateSelects(); // Actualizar selects por si se añade una nueva carrera
    } catch (err) {
        showError(err.message || "Error al registrar carrera");
    }
}
// Maneja el registro de categorías
async function handleCategoryRegistration() {
    const name = document.getElementById('nombreCategoria')?.value.trim();
    if (!name) return showError("Ingresa un nombre válido");

    try {
        await registerCategory(name);
        showSuccess("✅ Categoría registrada");
        document.getElementById('formRegistroCategoria').reset();
        await loadAndDisplayCategories();
        await populateSelects(); // Actualizar selects por si se añade una nueva categoría
    } catch (err) {
        showError(err.message || "❌ Error al registrar categoría");
    }
}

// ==================== INICIALIZACIÓN ====================
// Espera a que el DOM esté completamente cargado antes de ejecutar el código
// Esto asegura que todos los elementos del DOM estén disponibles para manipulación.
// También se encarga de inicializar los manejadores de eventos y cargar los datos iniciales
// de estudiantes, carreras y categorías según la página actual.
document.addEventListener('DOMContentLoaded', () => {
    // Estudiantes - Manejadores de eventos de formularios
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', (e) => {
            e.preventDefault();
            handleStudentRegistration();
        });
    }
// Búsqueda de estudiantes por ID
    const searchByIdForm = document.getElementById('searchByIdForm');
    if (searchByIdForm) {
        searchByIdForm.addEventListener('submit', (e) => {
            e.preventDefault();
            handleSearchById();
        });
    }
// Búsqueda de estudiantes por carrera
    const searchByCareerForm = document.getElementById('careerSearchForm');
    if (searchByCareerForm) {
        searchByCareerForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const career = document.getElementById('careerFilter')?.value;
            if (!career) return showError("Selecciona una carrera para buscar");
            try {
                const students = await getStudentsByCareer(career);
                displayStudentResults(students);
            } catch (err) {
                showError(err.message || "Error al buscar estudiantes por carrera");
            }
        });
    }

    // Carreras - Manejadores de eventos de formularios
    const careerForm = document.getElementById('careerForm');
    if (careerForm) {
        careerForm.addEventListener('submit', (e) => {
            e.preventDefault();
            handleCareerRegistration();
        });
    }

    // Categorías - Manejadores de eventos de formularios
    const categoryForm = document.getElementById('formRegistroCategoria');
    if (categoryForm) {
        categoryForm.addEventListener('submit', (e) => {
            e.preventDefault();
            handleCategoryRegistration();
        });
    }

    // Inicialización de la visualización de datos al cargar la página
    // Esto asegura que la tabla correcta se cargue según el HTML actual.
    if (document.getElementById('studentsTableBody')) {
        loadAndDisplayStudents();
    }
    if (document.getElementById('careersTableBody')) {
        loadAndDisplayCareers();
    }
    if (document.getElementById('categoriesTableBody')) {
        loadAndDisplayCategories();
    }

    // Inicializar los selects (dropdowns) con datos del backend
    populateSelects();
});
// ==================== INICIALIZACIÓN DE GLIDER.JS (PARA index.html) ====================
    // Solo inicializar Glider si el elemento existe en la página (para evitar errores en otras páginas)
    const gliderElement = document.querySelector('.glider');
    if (gliderElement) {
        new Glider(gliderElement, {
            slidesToShow: 1,
            dots: '.dots',
            arrows: {
                prev: '.glider-prev',
                next: '.glider-next'
            }
        });
    }
// ==================== FUNCIONES AUXILIARES PARA RESULTADOS DE BÚSQUEDA ====================;
// Función auxiliar para mostrar resultados de búsqueda de estudiantes por carrera
function displayStudentResults(students) {
    const container = document.getElementById('careerResult');
    if (!container) return;

    if (!Array.isArray(students) || !students.length) {
        container.innerHTML = '<p>No se encontraron estudiantes para esta carrera.</p>';
        return;
    }

    container.innerHTML = students.map(s => `
        <div class="student-card">
            <strong>ID:</strong> ${s.id}<br>
            <strong>Nombre:</strong> ${s.name}<br>
            <strong>Carrera:</strong> ${s.career || 'Sin carrera'}
        </div>
    `).join('');
}
// ==================== RESUMEN DEL FLUJO PRINCIPAL DE app.js ====================
/*
    Este archivo `app.js` es el controlador principal del frontend de StudentApp.
    Se encarga de la interacción del usuario con la interfaz y la comunicación con la API del backend.

    Al cargar cualquier página HTML:
    1. Se configuran la URL base de la API y las cabeceras de las solicitudes.
    2. Se inicializan funciones de utilidad como `showSuccess`, `showError` y `clearForm` (usando SweetAlert2).
    3. Se cargan los datos iniciales para los desplegables (selects) de carreras y categorías (`populateSelects`).
    4. Se detecta qué página HTML está activa (estudiantes, carreras o categorías) y se cargan y muestran los datos correspondientes en sus respectivas tablas (`loadAndDisplayStudents`, `loadAndDisplayCareers`, `loadAndDisplayCategories`).

    Para cada entidad (Estudiantes, Carreras, Categorías), `app.js` maneja:
    -   **Creación/Registro:** Recopila datos de formularios y envía solicitudes `POST` a la API.
    -   **Lectura/Listado:** Realiza solicitudes `GET` a la API y renderiza los datos en tablas dinámicas.
    -   **Búsqueda (solo estudiantes por ID y carrera):** Recopila criterios de búsqueda y realiza solicitudes `GET` filtradas.
    -   **Actualización/Edición:** Abre modales (SweetAlert2) para editar datos y envía solicitudes `PUT` a la API.
    -   **Eliminación:** Pide confirmación al usuario (SweetAlert2) y envía solicitudes `DELETE` a la API.

    Los manejadores de eventos (ej. `handleStudentRegistration`, `handleCareerFormSubmit`) actúan como intermediarios,
    tomando los datos del DOM y llamando a las funciones asíncronas que interactúan directamente con la API.
    Los resultados de las operaciones de la API se comunican al usuario mediante SweetAlert2.
*/