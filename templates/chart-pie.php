<?php
$chartData = getChartHasilDiagnosis();
$dataHasilDiagnosis = $chartData['hasilDiagnosis'];
$dataJumlah = $chartData['jumlah'];
?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Hasil Diagnosis</h6>
    </div>
    <div class="card-body">
        <div class="chart-bar">
            <canvas id="myPieChart"></canvas>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var ctx = document.getElementById("myPieChart").getContext("2d");

        <?php if (empty($dataHasilDiagnosis) || empty($dataJumlah)): ?>
            // Data tidak tersedia, tampilkan chart kosong
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Tidak ada data'],
                    datasets: [{
                        data: [0],
                        backgroundColor: ['#dddddd'],
                        hoverBackgroundColor: ['#dddddd'],
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            boxWidth: 20
                        }
                    }
                }
            });
        <?php else: ?>
            // Data tersedia, tampilkan chart dengan data aktual
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: <?= json_encode($dataHasilDiagnosis); ?>,
                    datasets: [{
                        data: <?= json_encode($dataJumlah); ?>,
                        backgroundColor: ["#e74a3b", "#f6c23e", "#4e73df"],
                        hoverBackgroundColor: ["#e74a3b", "#f6c23e", "#4e73df"],
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            boxWidth: 20
                        }
                    }
                }
            });
        <?php endif; ?>
    });
</script>