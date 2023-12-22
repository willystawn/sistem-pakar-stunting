<?php
require_once './includes/functions.php';

$user_data = getUserData();

// Cek apakah tombol hapus diklik
if (isset($_POST['hapus'])) {
    // Ambil data yang dipilih untuk dihapus
    $email = $_POST['email'];
    $no_telepon = $_POST['no_telepon'];

    // Panggil fungsi hapusData() untuk menghapus data
    hapusDataPengguna($email, $no_telepon);
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
                        <th>Email</th>
                        <th>No. Telp</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Inisialisasi counter nomor urut
                    $counter = 1;
                    ?>

                    <?php foreach ($user_data as $row): ?>
                        <tr>
                            <td class='text-center'>
                                <?= $counter ?>
                            </td>
                            <td class="capitalize">
                                <?= $row['nama'] ?>
                            </td>
                            <td>
                                <?= isset($row['jenis_kelamin']) ? ($row['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan') : '-' ?>
                            </td>
                            <td>
                                <?= $row['email'] ?? '-' ?>
                            </td>
                            <td>
                                <?= $row['no_telepon'] ?? '-' ?>
                            </td>
                            <td>
                                <form method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                    <input type="hidden" name="email" value="<?= $row['email'] ?>">
                                    <input type="hidden" name="no_telepon" value="<?= $row['no_telepon'] ?>">
                                    <button type="submit" name="hapus" class="btn btn-danger rounded px-2 py-1">
                                        <i class="fas fa-trash text-white"></i>
                                    </button>
                                </form>
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