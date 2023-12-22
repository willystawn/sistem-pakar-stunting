<?php
require_once './includes/functions.php';

// Ambil email pengguna yang login saat ini
$email = $_SESSION['email'];

// Panggil fungsi getDiagnosisDataByEmail() untuk mendapatkan data diagnosis
$diagnosis_data = getDiagnosisDataByEmail($email);
?>

<!-- DataTales Data Diagnosis -->
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Tanggal Diagnosis</th>
                        <th>Nama</th>
                        <th>Status Gizi</th>
                        <th>Hasil Diagnosis</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Inisialisasi counter nomor urut
                    $counter = 1;
                    ?>

                    <?php foreach ($diagnosis_data as $row): ?>
                        <tr>
                            <td class='text-center'>
                                <?= $counter ?>
                            </td>
                            <td>
                                <?= formatTanggalIndonesia($row['tanggal_diagnosis']) ?>
                            </td>
                            <td class="capitalize">
                                <?= ucwords(strtolower(stripslashes($row['nama_balita']))) ?>
                            </td>
                            <td>
                                <?= $row['status_gizi'] ?>
                            </td>
                            <td>
                                <?= $row['hasil_diagnosis'] ?>
                            </td>
                        </tr>
                        <?php
                        // Tingkatkan nilai counter
                        $counter++;
                    endforeach;
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>