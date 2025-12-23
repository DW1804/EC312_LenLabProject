{{-- Chart Widget Component --}}
@props(['title', 'chartId', 'type' => 'line', 'height' => '300'])

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $title }}</h3>
        <div class="flex items-center gap-2">
            <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors" onclick="refreshChart('{{ $chartId }}')">
                <span class="material-icons-round text-sm">refresh</span>
            </button>
        </div>
    </div>
    
    <div class="relative">
        <canvas id="{{ $chartId }}" style="height: {{ $height }}px;"></canvas>
        <div id="{{ $chartId }}-loading" class="absolute inset-0 flex items-center justify-center bg-white/80 dark:bg-gray-800/80 rounded-lg">
            <div class="flex items-center gap-2 text-gray-500">
                <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-primary"></div>
                <span class="text-sm">Đang tải...</span>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart configuration
const chartConfigs = {
    '{{ $chartId }}': {
        type: '{{ $type }}',
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        color: document.documentElement.classList.contains('dark') ? '#e5e7eb' : '#374151'
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: '#FAC638',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: false
                }
            },
            scales: {
                x: {
                    grid: {
                        color: document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb'
                    },
                    ticks: {
                        color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#6b7280'
                    }
                },
                y: {
                    grid: {
                        color: document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb'
                    },
                    ticks: {
                        color: document.documentElement.classList.contains('dark') ? '#9ca3af' : '#6b7280'
                    }
                }
            }
        }
    }
};

// Initialize chart when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initChart('{{ $chartId }}');
});

function initChart(chartId) {
    const ctx = document.getElementById(chartId);
    if (!ctx) return;
    
    const config = chartConfigs[chartId];
    if (!config) return;
    
    // Create chart instance
    window[chartId + 'Chart'] = new Chart(ctx, {
        type: config.type,
        data: {
            labels: [],
            datasets: []
        },
        options: config.options
    });
    
    // Load initial data
    refreshChart(chartId);
}

function refreshChart(chartId) {
    const loadingEl = document.getElementById(chartId + '-loading');
    if (loadingEl) loadingEl.style.display = 'flex';
    
    // This will be implemented by specific chart components
    if (window['load' + chartId.charAt(0).toUpperCase() + chartId.slice(1) + 'Data']) {
        window['load' + chartId.charAt(0).toUpperCase() + chartId.slice(1) + 'Data']();
    }
}

function hideChartLoading(chartId) {
    const loadingEl = document.getElementById(chartId + '-loading');
    if (loadingEl) loadingEl.style.display = 'none';
}
</script>
@endpush