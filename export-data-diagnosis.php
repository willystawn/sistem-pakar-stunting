<?php
session_start();
include 'includes/functions.php';
if (!isset($_SESSION['email'])) {
    header('Location: login');
    exit();
}
require_once './includes/functions.php';
$diagnosis_data = getDiagnosisCetak();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description"
        content="Sistem pakar diagnosis stunting pada balita. Temukan informasi terkini mengenai stunting, gejala, diagnosis, dan penanganan di sini.">
    <meta name="author" content="StuntAssist">
    <link rel="icon" type="image/png" sizes="192x192" href="img/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="img/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/favicon/favicon-16x16.png">
    <link rel="manifest" href="img/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

</head>

<body>
    <div class="container">
        <div class="text-center my-2">
            <h2>Data Diagnosis StuntAssist</h2>
        </div>
        <div>
            <table class="table table-bordered table-striped" id="exportDataDiagnosis" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Tanggal Diagnosis</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Jenis Kelamin</th>
                        <th scope="col">Usia (bulan)</th>
                        <th scope="col">Berat Badan (kg)</th>
                        <th scope="col">Tinggi Badan (cm)</th>
                        <th scope="col">IMT</th>
                        <th scope="col">Status Gizi</th>
                        <th scope="col">Hasil Diagnosis</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Inisialisasi counter nomor urut
                    $counter = 1;

                    // Tampilkan data dalam tabel
                    foreach ($diagnosis_data as $row) {
                        ?>
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
                                <?= isset($row['jenis_kelamin']) ? ($row['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan') : '-' ?>
                            </td>
                            <td>
                                <?= $row['usia'] ?>
                            </td>

                            <td>
                                <?= rtrim(rtrim(number_format($row['bb'], 2), '0'), '.') ?>
                            </td>

                            <td>
                                <?= rtrim(rtrim(number_format($row['tb'], 2), '0'), '.') ?>
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
                        </tr>
                        <?php
                        // Tingkatkan nilai counter
                        $counter++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#exportDataDiagnosis').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'excel', 'print'
                ]
            });
        });
    </script>

    <script>
        let currentDate = new Date();
        let day = currentDate.getDate();
        let month = currentDate.getMonth() + 1; // Menggunakan indeks bulan 0-11, sehingga perlu ditambahkan 1
        let year = currentDate.getFullYear();

        // Daftar nama bulan dalam bahasa Indonesia
        let monthNames = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli',
            'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        let formattedDate = day + ' ' + monthNames[month - 1] + ' ' + year;

        document.title = "Data Diagnosis - Printed " + formattedDate;
    </script>


    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>

</body>

</html>