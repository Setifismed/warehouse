// Sample data for charts
const zones = ['Zone 1', 'Zone 2', 'Zone 3', 'Zone 4'];
const prepTimes = [45, 58, 32, 41];
const avgLines = [12, 15, 8, 10];

// Preparation Time Chart
const prepTimeCtx = document.getElementById('preparationTimeChart').getContext('2d');
const prepTimeChart = new Chart(prepTimeCtx, {
    type: 'bar',
    data: {
        labels: zones,
        datasets: [{
            label: 'Minutes',
            data: prepTimes,
            backgroundColor: '#3498db',
            borderColor: '#2980b9',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Minutes'
                }
            }
        },
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.parsed.y + ' minutes';
                    }
                }
            }
        }
    }
});

// Average Lines Chart
const avgLinesCtx = document.getElementById('linesPerZoneChart').getContext('2d');
const avgLinesChart = new Chart(avgLinesCtx, {
    type: 'line',
    data: {
        labels: zones,
        datasets: [{
            label: 'Lignes',
            data: avgLines,
            backgroundColor: 'rgba(231, 76, 60, 0.2)',
            borderColor: '#e74c3c',
            borderWidth: 2,
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Nombre de Lignes'
                }
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

// Tab switching functionality
function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.remove('active');
    });

    // Remove active class from all tabs
    document.querySelectorAll('.document-tab').forEach(tab => {
        tab.classList.remove('active');
    });

    // Show selected tab content
    document.getElementById(tabName + '-content').classList.add('active');

    // Add active class to clicked tab
    event.currentTarget.classList.add('active');
}

// Example: This would be triggered when clicking an "View" button in your table
document.addEventListener('DOMContentLoaded', function() {
    var viewButtons = document.querySelectorAll('.btn-outline-primary');
    viewButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var modal = new bootstrap.Modal(document.getElementById('productSelectionModal'));
            modal.show();
        });
    });
});