<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JobConnect - Portal de Empleos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        /* Custom LinkedIn-style animations and effects */
        .job-card {
            transition: all 0.2s ease-in-out;
        }

        .job-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .navbar-shadow {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
    <!-- LinkedIn-style navbar -->
    <nav class="bg-white navbar-shadow sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-blue-800">JobConnect</h1>
                </div>

                <div class="hidden md:flex items-center space-x-4">
                    <a href="#" class="text-gray-700 hover:text-blue-600">Empleos</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600">Mi Red</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600">Mensajes</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600">Notificaciones</a>

                </div>

                <!-- Botón de Iniciar Sesión -->
                <div class="flex items-center relative">
                    <?php
                    session_start();
                    if (isset($_SESSION['user'])) {
                        // Mostrar el nombre del usuario logueado con un menú desplegable
                        echo '
        <div class="relative">
            <button id="user-menu-button" class="flex items-center px-4 py-2 bg-violet-600 text-white text-sm font-medium rounded-full hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 transition-colors">
                <span>' . htmlspecialchars($_SESSION['user']['nombre']) . '</span>
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div id="user-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5">
                <a href="mis_postulaciones.html" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Mis Postulaciones</a>
                <a href="#" id="logout-button" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Cerrar Sesión</a>
            </div>
        </div>';
                    } else {
                        // Mostrar el botón de "Iniciar Sesión"
                        echo '<a href="login.html" class="flex items-center px-4 py-2 bg-violet-600 text-white text-sm font-medium rounded-full hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="white" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                </svg>
                Iniciar Sesión
              </a>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main content with LinkedIn-style layout -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Empleos recomendados para ti</h2>
            <p class="text-gray-600">Basado en tu perfil y actividad reciente</p>
        </div>

        <!-- Removido loading, error y stats - ahora es estático -->
        <!-- Stats Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Ofertas disponibles</h3>
                    <p class="text-gray-600">Actualizado hace unos minutos</p>
                </div>
                <div class="text-right">
                    <div id="empleos-activos" class="text-3xl font-bold text-blue-600"></div>
                    <p class="text-sm text-gray-500">empleos activos</p>
                </div>
            </div>
        </div>

        <!-- Jobs Grid dinámico -->
        <div id="container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"></div>
    </main>

    <script src="llamados.js"></script>
    <script>
        // Menú hamburguesa responsive
        document.addEventListener('DOMContentLoaded', function () {
            const menuToggle = document.getElementById('menu-toggle');
            const navLinks = document.getElementById('nav-links');
            menuToggle.addEventListener('click', function () {
                navLinks.classList.toggle('hidden');
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            const userMenuButton = document.getElementById('user-menu-button');
            const userMenu = document.getElementById('user-menu');

            if (userMenuButton && userMenu) {
                userMenuButton.addEventListener('click', () => {
                    userMenu.classList.toggle('hidden');
                });

                // Cerrar el menú si se hace clic fuera de él
                document.addEventListener('click', (event) => {
                    if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
                        userMenu.classList.add('hidden');
                    }
                });
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            const logoutButton = document.getElementById('logout-button');

            if (logoutButton) {
                logoutButton.addEventListener('click', async (e) => {
                    e.preventDefault();

                    try {
                        const response = await fetch('api/routes/api.php?action=logout', {
                            method: 'GET',
                        });

                        const data = await response.json();
                        if (data.success) {
                            // Redirigir al usuario a la página principal
                            window.location.href = 'index.php';
                        }
                    } catch (error) {
                        console.error('Error al cerrar sesión:', error);
                    }
                });
            }
        });
    </script>
</body>

</html>