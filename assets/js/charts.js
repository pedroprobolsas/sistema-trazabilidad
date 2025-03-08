/**
 * charts.js - Funciones para inicializar y gestionar gráficos en el sistema de trazabilidad
 */

/**
 * Inicializa el gráfico de pastel para el Kardex
 * Muestra la distribución de entradas, desechos y diferencias
 */
function initKardexPieChart() {
    console.log('Iniciando configuración del gráfico de Kardex');
    
    // Verificar que Chart.js esté disponible
    if (typeof Chart === 'undefined') {
        console.error('Chart.js no está cargado. Asegúrese de incluir la biblioteca antes de inicializar los gráficos.');
        return;
    }
    
    const kardexPieCtx = document.getElementById('kardexPieChart');
    
    if (!kardexPieCtx) {
        console.error('No se encontró el elemento canvas para el gráfico de Kardex');
        return;
    }
    
    console.log('Canvas encontrado:', kardexPieCtx);
    
    try {
        // Destruir el gráfico existente si hay uno
        if (window.kardexChart) {
            console.log('Destruyendo gráfico existente');
            window.kardexChart.destroy();
        }
        
        console.log('Creando nuevo gráfico');
        
        // Configuración simplificada para pruebas
        window.kardexChart = new Chart(kardexPieCtx, {
            type: 'doughnut',
            data: {
                labels: ['Entradas', 'Desechos', 'Diferencia'],
                datasets: [{
                    data: [2511.75, 57.75, 30.50],
                    backgroundColor: ['#1cc88a', '#f6c23e', '#e74a3b']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
        
        console.log('Gráfico de Kardex inicializado correctamente');
    } catch (error) {
        console.error('Error al inicializar el gráfico de Kardex:', error);
        console.error('Detalles del error:', error.message);
        console.error('Stack trace:', error.stack);
        
        // Mostrar mensaje de error en la página
        const chartContainer = kardexPieCtx.parentNode;
        if (chartContainer) {
            const errorMsg = document.createElement('div');
            errorMsg.className = 'alert alert-danger mt-3';
            errorMsg.innerHTML = `<strong>Error al cargar el gráfico:</strong> ${error.message}`;
            chartContainer.appendChild(errorMsg);
        }
        
        // Intentar con un gráfico aún más simple como fallback
        try {
            console.log('Intentando crear un gráfico de fallback simple');
            window.kardexChart = new Chart(kardexPieCtx, {
                type: 'pie',
                data: {
                    labels: ['A', 'B', 'C'],
                    datasets: [{
                        data: [300, 50, 100],
                        backgroundColor: ['red', 'blue', 'yellow']
                    }]
                }
            });
            console.log('Gráfico de fallback creado correctamente');
        } catch (fallbackError) {
            console.error('Error al crear gráfico de fallback:', fallbackError);
        }
    }
}

/**
 * Actualiza los datos del gráfico de Kardex
 * @param {Object} data - Objeto con los nuevos datos (entradas, desechos, diferencia)
 */
function updateKardexChart(data) {
    if (!window.kardexChart || !(window.kardexChart instanceof Chart)) {
        console.error('El gráfico de Kardex no está inicializado');
        return;
    }
    
    const { entradas, desechos, diferencia } = data;
    const total = entradas + desechos + diferencia;
    
    // Actualizar porcentajes en las etiquetas
    const entradasPct = ((entradas / total) * 100).toFixed(1);
    const desechosPct = ((desechos / total) * 100).toFixed(1);
    const diferenciaPct = ((diferencia / total) * 100).toFixed(1);
    
    // Actualizar datos y etiquetas
    window.kardexChart.data.labels = [
        `Entradas (${entradasPct}%)`, 
        `Desechos (${desechosPct}%)`, 
        `Diferencia (${diferenciaPct}%)`
    ];
    window.kardexChart.data.datasets[0].data = [entradas, desechos, diferencia];
    
    // Actualizar el gráfico
    window.kardexChart.update();
    console.log('Gráfico de Kardex actualizado con nuevos datos');
}

/**
 * Inicializa todos los gráficos de la página actual
 * Detecta automáticamente qué gráficos están presentes y los inicializa
 */
function initAllCharts() {
    // Inicializar gráfico de Kardex si existe el elemento
    if (document.getElementById('kardexPieChart')) {
        initKardexPieChart();
    }
    
    // Aquí se pueden agregar más inicializaciones de gráficos en el futuro
}

// Exportar funciones para uso global
window.initKardexPieChart = initKardexPieChart;
window.updateKardexChart = updateKardexChart;
window.initAllCharts = initAllCharts;
