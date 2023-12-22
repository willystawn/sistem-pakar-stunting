<?php
$chartData = getChartDataPerkembanganStunting();
$dataTahun = $chartData['tahun'];
$dataJumlah = $chartData['jumlah'];
?>

<!-- Tambahkan kode HTML untuk menampilkan grafik menggunakan Chart.js -->
<div class="card shadow mb-4">
    <!-- Card Header - Dropdown -->
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Perkembangan Stunting</h6>
    </div>
    <!-- Card Body -->
    <div class="card-body">
        <div class="chart-area">
            <canvas id="myAreaChart"></canvas>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var ctx = document.getElementById("myAreaChart").getContext("2d");
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= json_encode($dataTahun); ?>,
                datasets: [{
                    label: 'Jumlah Stunting',
                    data: <?= json_encode($dataJumlah); ?>,
                    backgroundColor: 'rgba(78, 115, 223, 0.05)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    pointRadius: 3,
                    pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                    pointBorderColor: 'rgba(78, 115, 223, 1)',
                    pointHoverRadius: 3,
                    pointHoverBackgroundColor: 'rgba(78, 115, 223, 1)',
                    pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                    pointHitRadius: 10,
                    pointBorderWidth: 2,
                    fill: true,
                }],
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0,
                    },
                },
                scales: {
                    xAxes: [{
                        time: {
                            unit: "date",
                        },
                        gridLines: {
                            display: false,
                            drawBorder: false,
                        },
                        ticks: {
                            maxTicksLimit: 5,
                        },
                    }],
                    yAxes: [{
                        ticks: {
                            maxTicksLimit: 5,
                            padding: 10,
                            callback: function (value, index, values) {
                                if (Number.isInteger(value)) {
                                    return value;
                                } else {
                                    return ''; // Mengembalikan string kosong untuk nilai desimal
                                }
                            },
                        },
                    }],
                },
                legend: {
                    display: false,
                },
            }
        });
    });

</script>