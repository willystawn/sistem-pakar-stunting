<?php
require_once 'includes/functions.php';

// Call the getDataBalita() function to get the data we need
list($jumlah_balita, $jumlah_stunting, $persentase_stunting) = getDataBalita();

?>

<!-- Content Row -->
<div class="row">
    <?php
    echo generateCard("Jumlah Balita Yang <br> Diperiksa", $jumlah_balita . ' Anak', "fas fa-stethoscope", "primary");
    echo generateCard("Jumlah Diagnosis <br> Stunting", $jumlah_stunting . ' Anak', "fas fa-chart-pie", "success");
    echo generateCard("Persentase Diagnosis <br> Stunting", $persentase_stunting . " %", "fas fa-percentage", "info");
    ?>
</div>