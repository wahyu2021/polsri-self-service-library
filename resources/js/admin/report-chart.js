import ApexCharts from 'apexcharts';

document.addEventListener('DOMContentLoaded', () => {
    const chartContainer = document.querySelector("#revenue-chart");
    
    if (chartContainer && window.ReportData) {
        const { chartData } = window.ReportData;

        // Prepare data series
        const dataSeries = chartData.map(item => ({
            x: item.date,
            y: item.amount
        }));

        const options = {
            series: [{
                name: 'Pendapatan',
                data: dataSeries
            }],
            chart: {
                type: 'area',
                height: 380,
                fontFamily: 'Instrument Sans, sans-serif',
                toolbar: { show: false },
                zoom: { enabled: false },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800,
                }
            },
            colors: ['#F97316'], // Polsri Orange
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.6,
                    opacityTo: 0.05,
                    stops: [0, 90, 100]
                }
            },
            dataLabels: { enabled: false },
            stroke: {
                curve: 'smooth',
                width: 3,
                colors: ['#EA580C'] // Darker orange stroke
            },
            xaxis: {
                type: 'datetime',
                tooltip: { enabled: false },
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: {
                    format: 'dd MMM',
                    style: { colors: '#94a3b8', fontSize: '11px' }
                }
            },
            yaxis: {
                labels: {
                    style: { colors: '#94a3b8', fontSize: '11px', fontFamily: 'Mono' },
                    formatter: (value) => {
                        return 'Rp ' + (value / 1000).toFixed(0) + 'k';
                    }
                }
            },
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 4,
                yaxis: { lines: { show: true } },
                xaxis: { lines: { show: false } },
                padding: { top: 0, right: 0, bottom: 0, left: 10 }
            },
            markers: {
                size: 0,
                colors: ['#fff'],
                strokeColors: '#EA580C',
                strokeWidth: 3,
                hover: { size: 6 }
            },
            tooltip: {
                theme: 'light',
                y: {
                    formatter: function (val) {
                        return "Rp " + new Intl.NumberFormat('id-ID').format(val)
                    }
                },
                x: {
                    format: 'dd MMMM yyyy'
                },
                marker: { show: false },
            }
        };

        const chart = new ApexCharts(chartContainer, options);
        chart.render();
    }
});
