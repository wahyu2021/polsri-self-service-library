import ApexCharts from 'apexcharts';

document.addEventListener('DOMContentLoaded', () => {
    const chartContainer = document.querySelector("#revenue-chart");
    
    if (chartContainer && window.ReportData) {
        const { chartData } = window.ReportData;

        // Prepare data
        const dates = chartData.map(item => item.date);
        const amounts = chartData.map(item => item.amount);

        const options = {
            series: [{
                name: 'Pendapatan Denda',
                data: amounts
            }],
            chart: {
                type: 'area',
                height: 350,
                fontFamily: 'Instrument Sans, sans-serif',
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                }
            },
            colors: ['#F97316'], // Polsri Primary Orange
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.2,
                    stops: [0, 90, 100]
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            xaxis: {
                categories: dates,
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                },
                labels: {
                    style: {
                        colors: '#64748b',
                        fontSize: '12px'
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: '#64748b',
                        fontSize: '12px'
                    },
                    formatter: (value) => {
                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                    }
                }
            },
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 4,
                yaxis: {
                    lines: {
                        show: true
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return "Rp " + new Intl.NumberFormat('id-ID').format(val)
                    }
                }
            }
        };

        const chart = new ApexCharts(chartContainer, options);
        chart.render();
    }
});
