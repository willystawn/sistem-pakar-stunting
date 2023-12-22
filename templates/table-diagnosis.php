<?php
require_once './includes/functions.php';

// Ambil email pengguna yang login saat ini
$email = $_SESSION['email'];

// Panggil fungsi getDiagnosisDataByEmail() untuk mendapatkan data diagnosis
$diagnosis_data = getDiagnosisDataByEmail($email);

// Cek apakah tombol hapus diklik
if (isset($_POST['hapus'])) {
    // Ambil data yang dipilih untuk dihapus
    $id_diagnosis = $_POST['id_diagnosis'];

    // Panggil fungsi hapusData() untuk menghapus data
    hapusDataDiagnosis($id_diagnosis);
}
?>

<!-- DataTales Data Diagnosis -->
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Jenis Kelamin</th>
                        <th>Usia</th>
                        <th>Berat Badan</th>
                        <th>Tinggi Badan</th>
                        <th>IMT</th>
                        <th>Status Gizi</th>
                        <th>Hasil Diagnosis</th>
                        <th>Aksi</th>
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
                            <td class="capitalize">
                                <?= ucwords(strtolower(stripslashes($row['nama_balita']))) ?>
                            </td>
                            <td>
                                <?= isset($row['jenis_kelamin']) ? ($row['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan') : '-' ?>
                            </td>
                            <td>
                                <?= $row['usia'] ?> bulan
                            </td>

                            <td>
                                <?= rtrim(rtrim(number_format($row['bb'], 2), '0'), '.') ?> kg
                            </td>

                            <td>
                                <?= rtrim(rtrim(number_format($row['tb'], 2), '0'), '.') ?> cm
                            </td>

                            <td>
                                <?= rtrim(rtrim(number_format($row['imt'], 2), '0'), '.') ?>
                            </td>

                            <td>
                                <?= $row['status_gizi'] ?>
                            </td>
                            <td>
                                <?= $row['hasil_diagnosis'] ?>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center align-items-center">
                                    <form method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                        <input type="hidden" name="id_diagnosis" value="<?= $row['id_diagnosis'] ?>">
                                        <button type="submit" name="hapus" class="btn btn-danger rounded px-2 py-1">
                                            <i class="fas fa-trash text-white"></i>
                                        </button>
                                    </form>
                                </div>
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