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
    <div class="min-h-screen flex flex-col items-center py-10">
        <div class="w-full max-w-5xl px-4">
            <header class="bg-white shadow-lg rounded-xl p-6 mb-8 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900">Panel de Administración</h1>
                    <p class="text-sm text-gray-500">Bienvenido, <?= esc($username) ?> (<?= esc($rol) ?>)</p>
                </div>
                <a href="<?= base_url('logout') ?>" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-150">
                    Cerrar Sesión
                </a>
            </header>

            <main class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">    
                <!-- Tarjeta 1: Profesores -->
                <div class="step-card-d bg-white p-6 rounded-xl shadow-md border-t-4 border-blue-500">
                    <h2 class="text-xl font-bold text-gray-800 mb-2">Gestión de Profesores</h2>
                    <p class="text-gray-600 mb-4">Administrar la lista de docentes activos e inactivos.</p>
                    <a href="<?= base_url('profesores') ?>" class="text-blue-600 hover:text-blue-800 font-semibold flex items-center">
                        Ir a Profesores 
                        <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>

                <!-- Tarjeta 2: Carreras -->
                <div class="step-card-d bg-white p-6 rounded-xl shadow-md border-t-4 border-green-500">
                    <h2 class="text-xl font-bold text-gray-800 mb-2">Gestión de Carreras</h2>
                    <p class="text-gray-600 mb-4">Agregar, editar o eliminar las ofertas académicas.</p>
                    <a href="<?= base_url('carreras') ?>" class="text-green-600 hover:text-green-800 font-semibold flex items-center">
                        Ir a Carreras
                        <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>

                <!-- Tarjeta 3: Estudiantes -->
                <div class="step-card-d bg-white p-6 rounded-xl shadow-md border-t-4 border-purple-500">
                    <h2 class="text-xl font-bold text-gray-800 mb-2">Gestión de Estudiantes</h2>
                    <p class="text-gray-600 mb-4">Administrar alumnos, inscripciones y datos personales.</p>
                    <a href="<?= base_url('estudiantes') ?>" class="text-purple-600 hover:text-purple-800 font-semibold flex items-center">
                        Ir a Estudiantes
                        <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>

                <!-- Tarjeta 4: Cursos -->
                 <div class="step-card-d bg-white p-6 rounded-xl shadow-md border-t-4 border-yellow-500">
                    <h2 class="text-xl font-bold text-gray-800 mb-2">Gestión de Cursos</h2>
                    <p class="text-gray-600 mb-4">Administrar asignaturas, categorías y cursos ofrecidos.</p>
                    <a href="<?= base_url('cursos') ?>" class="text-yellow-600 hover:text-yellow-800 font-semibold flex items-center">
                        Ir a Cursos
                        <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
            </div>
                
            </main>
        </div>
    </div>
</body>
</html>
 