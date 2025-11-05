<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <!-- Usa Tailwind CSS (o tu framework CSS preferido) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f4f7f9; }
        .card { transition: all 0.3s ease-in-out; }
        .card:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="container mx-auto px-4 py-8"> <div class="bg-white p-6 rounded-xl shadow-md flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-blue-800">Panel de Administración</h1>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6"> 
            <div class="step-card-d bg-white p-6 rounded-xl shadow-md border-t-4 border-blue-500">
             <h2 class="text-xl font-bold text-gray-800 mb-2">Gestión de Profesores</h2>
             <p class="text-gray-600 mb-4">Administrar la lista de docentes activos e inactivos.</p>
             <a href="<?= base_url('profesores') ?>" class="text-blue-600 hover:text-blue-800 font-semibold flex items-center">
                Ir a Profesores 
                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
             </a>
            </div>

            <div class="step-card-d bg-white p-6 rounded-xl shadow-md border-t-4 border-green-500">
             <h2 class="text-xl font-bold text-gray-800 mb-2">Gestión de Carreras</h2>
             <p class="text-gray-600 mb-4">Agregar, editar o eliminar las ofertas académicas.</p>
             <a href="<?= base_url('carreras') ?>" class="text-green-600 hover:text-green-800 font-semibold flex items-center">
                Ir a Carreras
                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
             </a>
            </div>

             <div class="step-card-d bg-white p-6 rounded-xl shadow-md border-t-4 border-purple-500">
             <h2 class="text-xl font-bold text-gray-800 mb-2">Gestión de Estudiantes</h2>
             <p class="text-gray-600 mb-4">Administrar alumnos, inscripciones y datos personales.</p>
             <a href="<?= base_url('estudiantes') ?>" class="text-purple-600 hover:text-purple-800 font-semibold flex items-center">
                Ir a Estudiantes
                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
             </a>
            </div>

            <div class="step-card-d bg-white p-6 rounded-xl shadow-md border-t-4 border-yellow-500">
            <h2 class="text-xl font-bold text-gray-800 mb-2">Gestión de Cursos</h2>
            <p class="text-gray-600 mb-4">Administrar asignaturas, categorías y cursos ofrecidos.</p>
            <a href="<?= base_url('cursos') ?>" class="text-yellow-600 hover:text-yellow-800 font-semibold flex items-center">
                Ir a Cursos
                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
             </a>
              </div>
            </div>
            </div>  
            </main>
        </div>
    </div>
</body>
</html>
 