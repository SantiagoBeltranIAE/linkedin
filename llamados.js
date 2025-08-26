// jobs.js
// Este script obtiene los llamados desde la API y los muestra dinámicamente en el HTML

document.addEventListener('DOMContentLoaded', function() {
    fetch('./api/routes/llamados.php')
        .then(response => response.json())
        .then(data => {
            const jobsGrid = document.getElementById('container');
            jobsGrid.innerHTML = '';
            // Actualizar el número de empleos activos
            const empleosActivos = document.getElementById('empleos-activos');
            if (empleosActivos) {
                empleosActivos.textContent = data.llamados.length;
            }
            data.llamados.forEach(llamado => {
                const card = document.createElement('div');
                card.className = 'job-card bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md cursor-pointer';
                card.innerHTML = `
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            ${llamado.logo ? `<img src="${llamado.logo}" alt="Logo" class="w-12 h-12 rounded-lg object-cover">` : ''}
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 hover:text-blue-600 transition-colors">${llamado.titulo}</h3>
                                <p class="text-sm text-gray-500">Empresa: ${llamado.empresa_nombre}</p>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-700 text-sm leading-relaxed mb-4">${llamado.descripcion}</p>
                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <button 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white ${llamado.postulado ? 'bg-gray-400 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700'} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors postular-btn" 
                            data-llamado-id="${llamado.id}" 
                            ${llamado.postulado ? 'disabled' : ''}>
                            ${llamado.postulado ? 'Postulado' : 'Postular'}
                        </button>
                    </div>
                `;
                jobsGrid.appendChild(card);
            });
        })
        .catch(error => {
            console.error('Error al cargar los llamados:', error);
        });
});

document.addEventListener('click', async (e) => {
    if (e.target.classList.contains('postular-btn')) {
        const llamadoId = e.target.dataset.llamadoId;

        const response = await fetch('./api/routes/api.php?action=postular', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ llamado_id: llamadoId }),
        });

        const data = await response.json();
        if (data.success) {
            alert('Te has postulado con éxito');
            e.target.textContent = 'Postulado';
            e.target.disabled = true;
            e.target.classList.add('bg-gray-400', 'cursor-not-allowed');
        } else {
            alert(data.message || 'Error al postularse');
        }
    }
});
