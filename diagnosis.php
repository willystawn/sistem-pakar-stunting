<?php
session_start();
include "./includes/functions.php";
$status_pengguna = $_SESSION['status_pengguna'] ?? '';

if (isset($_POST['simpan-diagnosis'])) {
    $namaBalita = $_POST['nama-balita-submit'];
    $jenisKelamin = $_POST['jenis-kelamin-submit'];
    $usia = $_POST['usia-submit'];
    $beratBadan = $_POST['berat-badan-submit'];
    $tinggiBadan = $_POST['tinggi-badan-submit'];
    $imt = $_POST['imt-submit'];
    $tingkatStunting = $_POST['tingkat-stunting-submit'];
    $giziStatus = $_POST['gizi-status-submit'];

    simpanDiagnosis($namaBalita, $jenisKelamin, $usia, $beratBadan, $tinggiBadan, $imt, $tingkatStunting, $giziStatus);
}

?>

<!DOCTYPE html>
<html lang="id">

<!-- Header -->
<?php include './templates/header.php'; ?>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include './templates/sidebar.php'; ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Navbar -->
                <?php include './templates/navbar.php'; ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <?= generatePageTitle('Diagnosis'); ?>

                    <?php if ($status_pengguna !== 'admin' && $status_pengguna !== 'user'): ?>
                                                                                    <div class="user-select-none alert alert-warning" role="alert">
                                                                                        Anda saat ini login sebagai tamu. Untuk dapat mengakses semua fitur yang tersedia, silakan
                                                                                        melakukan login terlebih dahulu menggunakan akun yang telah terdaftar. Jika belum
                                                                                        memiliki akun klik <a href="register" class="text-decoration-underline text-link">Daftar
                                                                                            akun.</a>
                                                                                    </div>
                    <?php endif; ?>

                    <div class="d-flex justify-content-between align-items-center">
                        <div class="ml-auto">
                            <button type="button" class="btn btn-primary mb-4" data-toggle="modal"
                                data-target="#panduanModal">
                                <i class="fas fa-question-circle"></i>
                                <span class="ml-1">Panduan</span>
                            </button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 mb-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Form Diagnosis Stunting</h6>
                                </div>
                                <div class="card-body">
                                    <form id="form-diagnosis" method="POST" action="">
                                        <div class="form-group">
                                            <label for="nama_balita">Nama Balita</label>
                                            <input type="text" class="form-control" id="nama_balita" name="nama_balita"
                                                placeholder="Masukkan nama balita..." pattern="[a-zA-Z\s.']+"
                                                title="Nama hanya boleh mengandung huruf, spasi, titik, dan satu tanda kutip"
                                                required autocomplete="off">
                                        </div>

                                        <div class="form-group">
                                            <label for="jenis_kelamin">Jenis Kelamin</label>
                                            <select class="form-control" id="jenis_kelamin" name="jenis_kelamin"
                                                required>
                                                <option value="" selected disabled>Pilih jenis kelamin</option>
                                                <option value="L">Laki-Laki</option>
                                                <option value="P">Perempuan</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="usia_balita">Usia
                                                (dalam bulan)</label>
                                            <input type="number" class="form-control" id="usia_balita"
                                                name="usia_balita" placeholder="Masukkan usia dalam bulan..." required
                                                min="0" max="60" pattern="[0-9]+" autocomplete="off">
                                        </div>

                                        <div class="form-group">
                                            <label for="bb_balita">Berat Badan (dalam kg)</label>
                                            <input type="text" class="form-control" id="bb_balita" name="bb_balita"
                                                placeholder="Masukkan berat badan..." pattern="[0-9]+([.][0-9]+)?"
                                                required autocomplete="off">
                                        </div>

                                        <div class="form-group">
                                            <label for="tb_balita">Tinggi Badan (dalam cm)</label>
                                            <input type="text" class="form-control" id="tb_balita" name="tb_balita"
                                                placeholder="Masukkan tinggi badan..." pattern="[0-9]+([.][0-9]+)?"
                                                required autocomplete="off">
                                        </div>

                                        <button type="submit"
                                            class="mt-4 btn btn-primary btn-block py-2">Diagnosis</button>
                                        <button type="button" class="btn btn-danger btn-block py-2"
                                            onclick="resetFormDiagnosis()">Reset</button>
                                    </form>
                                </div>
                            </div>
                            <div class="card shadow">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Kalkulator Usia</h6>
                                </div>
                                <div class="card-body">
                                    <form id="usiaForm" onsubmit="hitungUsia(event)">
                                        <div class="form-group">
                                            <label for="tgl_lahir">Tanggal Lahir</label>
                                            <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir">
                                        </div>
                                        <div class="form-group">
                                            <label for="tgl_sekarang">Tanggal Sekarang</label>
                                            <input type="date" class="form-control" id="tgl_sekarang"
                                                name="tgl_sekarang" value="<?php echo date('Y-m-d'); ?>">
                                        </div>
                                        <button type="submit" class="mt-4 btn btn-primary btn-block py-2">Hitung
                                            Usia</button>
                                        <button type="button" class="btn btn-danger btn-block py-2"
                                            onclick="resetForm()">Reset</button>
                                    </form>

                                    <div class="mt-4 d-flex justify-content-center align-items-center text-center">
                                        <div class='col-12 card shadow'>
                                            <div class='card-body'>
                                                <div class='h5 font-weight-bold text-primary'>
                                                    <h6 class="font-weight-bold">Hasil Perhitungan Usia</h6>
                                                    <hr>
                                                    <span id="hasil_usia">~</span> <span> Bulan</span>
                                                </div>
                                                <button class="btn btn-primary btn-block mt-3" onclick="copy()">
                                                    <i class="fas fa-copy mr-2"></i> Salin
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-4" id="result-diagnosis">
                            <div class="card shadow">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Data Diagnosis</h6>
                                </div>
                                <div class="card-body row">
                                    <div class="col">
                                        <table class="table-responsive">
                                            <tbody>
                                                <tr>
                                                    <td>Nama Balita</td>
                                                    <td class="px-3">:</td>
                                                    <td id="nama-balita">-</td>
                                                </tr>
                                                <tr>
                                                    <td>Jenis Kelamin</td>
                                                    <td class="px-3">:</td>
                                                    <td id="jenis-kelamin">-</td>
                                                </tr>
                                                <tr>
                                                    <td>Usia</td>
                                                    <td class="px-3">:</td>
                                                    <td id="usia">-</td>
                                                </tr>
                                                <tr>
                                                    <td>Berat Badan</td>
                                                    <td class="px-3">:</td>
                                                    <td id="berat-badan">-</td>
                                                </tr>
                                                <tr>
                                                    <td>Tinggi Badan</td>
                                                    <td class="px-3">:</td>
                                                    <td id="tinggi-badan">-</td>
                                                </tr>
                                                <tr>
                                                    <td>IMT</td>
                                                    <td class="px-3">:</td>
                                                    <td id="imt">-</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col text-center d-none d-md-block">
                                        <img src="./img/kids.png" alt="Foto Anak" style="max-height: 140px">
                                    </div>
                                </div>

                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Hasil Diagnosis</h6>
                                </div>
                                <div class="card-body">
                                    <div class="text-center">
                                        <h4 class="text-danger font-weight-bold" id="tingkat-stunting">Tingkat Stunting
                                        </h4>
                                        <p>Tinggi Badan: <span id="tinggi-badan-output"></span></p>
                                    </div>

                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 25%;"
                                            aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"
                                            data-toggle="tooltip" data-placement="bottom" title="Stunting Berat">
                                            <small id="stuntingBeratRange" class="pointer">-</small>
                                        </div>
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 25%;"
                                            aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"
                                            data-toggle="tooltip" data-placement="bottom" title="Stunting Ringan">
                                            <small id="stuntingRinganRange" class="pointer">-</small>
                                        </div>
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 50%;"
                                            aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"
                                            data-toggle="tooltip" data-placement="bottom" title="Tidak Stunting">
                                            <small id="tidakStuntingRange" class="pointer">-</small>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <h4 class="text-primary font-weight-bold" id="gizi-status">Status Gizi</h4>
                                        <p>Indeks Massa Tubuh (IMT): <span id="imt-output"></span></p>
                                    </div>

                                    <div class="progress my-3">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 16.67%;"
                                            aria-valuenow="16.67" aria-valuemin="0" aria-valuemax="100"
                                            data-toggle="tooltip" data-placement="bottom" title="Gizi Buruk">
                                            <small id="giziBurukRange" class="pointer">-</small>
                                        </div>
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 16.67%;"
                                            aria-valuenow="16.67" aria-valuemin="0" aria-valuemax="100"
                                            data-toggle="tooltip" data-placement="bottom" title="Gizi Kurang">
                                            <small id="giziKurangRange" class="pointer">-</small>
                                        </div>
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 16.67%;"
                                            aria-valuenow="16.67" aria-valuemin="0" aria-valuemax="100"
                                            data-toggle="tooltip" data-placement="bottom" title="Gizi Baik">
                                            <small id="giziBaikRange" class="pointer">-</small>
                                        </div>
                                        <div class="progress-bar bg-info" role="progressbar" style="width: 16.67%;"
                                            aria-valuenow="16.67" aria-valuemin="0" aria-valuemax="100"
                                            data-toggle="tooltip" data-placement="bottom" title="Berisiko Gizi Lebih">
                                            <small id="giziBerisikoLebihRange" class="pointer">-</small>
                                        </div>
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 16.67%;"
                                            aria-valuenow="16.67" aria-valuemin="0" aria-valuemax="100"
                                            data-toggle="tooltip" data-placement="bottom" title="Gizi Lebih">
                                            <small id="giziLebihRange" class="pointer">-</small>
                                        </div>
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 16.67%;"
                                            aria-valuenow="16.67" aria-valuemin="0" aria-valuemax="100"
                                            data-toggle="tooltip" data-placement="bottom" title="Obesitas">
                                            <small id="giziObesitasRange" class="pointer">-</small>
                                        </div>
                                    </div>

                                    <div class="text-center mb-4">
                                        <small>Acuan Standar Antropometri Anak (PMK 2020)</small>
                                    </div>
                                </div>

                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Informasi Diagnosis</h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-0" id="informasi-diagnosis">Informasi diagnosis akan ditampilkan ketika
                                        Anda telah melakukan
                                        diagnosis.</p>
                                </div>
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Rekomendasi Diagnosis</h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-0" id="rekomendasi-diagnosis">Rekomendasi diagnosis akan ditampilkan
                                        ketika Anda telah melakukan
                                        diagnosis.</p>
                                </div>

                                <form class="m-0" method="post" action="" onsubmit="return validateFormSubmit()">
                                    <input type="hidden" id="nama-balita-submit" name="nama-balita-submit" readonly>
                                    <input type="hidden" id="jenis-kelamin-submit" name="jenis-kelamin-submit" readonly>
                                    <input type="hidden" id="usia-submit" name="usia-submit" readonly>
                                    <input type="hidden" id="berat-badan-submit" name="berat-badan-submit" readonly>
                                    <input type="hidden" id="tinggi-badan-submit" name="tinggi-badan-submit" readonly>
                                    <input type="hidden" id="imt-submit" name="imt-submit" readonly>
                                    <input type="hidden" id="tingkat-stunting-submit" name="tingkat-stunting-submit"
                                        readonly>
                                    <input type="hidden" id="gizi-status-submit" name="gizi-status-submit" readonly>
                                    <?php if ($status_pengguna !== ''): ?>
                                                                                                    <div class="card-body">
                                                                                                        <button type="submit" name="simpan-diagnosis"
                                                                                                            class="btn btn-primary btn-user btn-block">
                                                                                                            Simpan Diagnosis
                                                                                                        </button>
                                                                                                        <a href="data-diagnosis" class="btn btn-success btn-block py-2">Lihat Data
                                                                                                            Diagnosis</a>
                                                                                                    </div>
                                    <?php endif; ?>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- /.container-fluid -->

                </div>
                <!-- End of Main Content -->

                <!-- Footer -->
                <?php include './templates/footer.php'; ?>

            </div>
            <!-- End of Content Wrapper -->

        </div>
        <!-- End of Page Wrapper -->

</body>

<?php include './templates/modal/modal-panduan.php'; ?>

<!-- Page level custom scripts -->
<?php include './templates/js.php'; ?>

<script>
    function validateFormSubmit() {
        let namaBalita = document.getElementById("nama-balita-submit").value;
        let jenisKelamin = document.getElementById("jenis-kelamin-submit").value;
        let usia = document.getElementById("usia-submit").value;
        let beratBadan = document.getElementById("berat-badan-submit").value;
        let tinggiBadan = document.getElementById("tinggi-badan-submit").value;
        let imtVal = document.getElementById("imt-submit").value;
        let tingkatStunting = document.getElementById("tingkat-stunting-submit").value;
        let giziStatus = document.getElementById("gizi-status-submit").value;

        // Periksa jika salah satu input masih kosong
        if (namaBalita === "" || jenisKelamin === "" || usia === "" || beratBadan === "" || tinggiBadan === "" || imtVal === "" || tingkatStunting === "" || giziStatus === "") {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Mohon lakukan diagnosis terlebih dahulu!',
            });
            return false; // Mencegah pengiriman form
        }

        return true; // Kirim form jika semua input telah terisi
    }
</script>

<script>
    function hitungUsia(event) {
        event.preventDefault();

        let tglLahir = new Date(document.getElementById("tgl_lahir").value);
        let tglSekarang = new Date(document.getElementById("tgl_sekarang").value);

        if (isNaN(tglLahir.getTime()) || isNaN(tglSekarang.getTime())) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Mohon masukkan tanggal yang valid!',
            });
            document.getElementById("hasil_usia").textContent = "~";
            return;
        }

        if (tglLahir > tglSekarang) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Tanggal lahir tidak boleh lebih besar dari tanggal sekarang!',
            });
            document.getElementById("hasil_usia").textContent = "~";
            return;
        }

        let selisihTahun = tglSekarang.getFullYear() - tglLahir.getFullYear();
        let selisihBulan = tglSekarang.getMonth() - tglLahir.getMonth();

        if (tglSekarang.getDate() < tglLahir.getDate()) {
            selisihBulan--;
        }

        if (selisihBulan < 0) {
            selisihBulan += 12;
            selisihTahun--;
        }

        let usia = selisihBulan;

        document.getElementById("hasil_usia").textContent = usia;
        document.getElementById("tgl_sekarang").scrollIntoView();
    }


    function copy() {
        let hasilUsia = document.getElementById("hasil_usia").textContent;

        // Jika nilai hasil_usia adalah "~", jangan jalankan fungsi copy()
        if (hasilUsia === "~") {
            return;
        }

        // Buat elemen input untuk menyalin nilai hasil_usia
        let input = document.createElement("input");
        input.setAttribute("value", hasilUsia);
        document.body.appendChild(input);

        // Salin nilai hasil_usia
        input.select();
        document.execCommand("copy");
        document.body.removeChild(input);

        // Tampilkan pesan sukses
        Swal.fire({
            icon: 'success',
            title: 'Berhasil menyalin hasil usia!',
            text: 'Nilai usia telah disalin ke clipboard.',
        });
    }

    function resetForm() {
        document.getElementById("usiaForm").reset();
        document.getElementById("hasil_usia").textContent = "~";
    }
</script>

<script>
    function diagnoseGiziStunting(jenisKelamin, usia, beratBadan, tinggiBadan) {
        // Tabel basis pengetahuan diagnosa gizi
        let tabelBasisPengetahuanGizi = {
            "L": {
                "0": [10.2, 11.1, 12.2, 13.4, 14.8, 16.3, 18.1],
                "1": [11.3, 12.4, 13.6, 14.9, 16.3, 17.8, 19.4],
                "2": [12.5, 13.7, 15, 16.3, 17.8, 19.4, 21.1],
                "3": [13.1, 14.3, 15.5, 16.9, 18.4, 20, 21.8],
                "4": [13.4, 14.5, 15.8, 17.2, 18.7, 20.3, 22.1],
                "5": [13.5, 14.7, 15.9, 17.3, 18.8, 20.5, 22.3],
                "6": [13.6, 14.7, 16, 17.3, 18.8, 20.5, 22.3],
                "7": [13.7, 14.8, 16, 17.3, 18.8, 20.5, 22.3],
                "8": [13.6, 14.7, 15.9, 17.3, 18.7, 20.4, 22.2],
                "9": [13.6, 14.7, 15.8, 17.2, 18.6, 20.3, 22.1],
                "10": [13.5, 14.6, 15.7, 17, 18.5, 20.1, 22],
                "11": [13.4, 14.5, 15.6, 16.9, 18.4, 20, 21.8],
                "12": [13.4, 14.4, 15.5, 16.8, 18.2, 19.8, 21.6],
                "13": [13.3, 14.3, 15.4, 16.7, 18.1, 19.7, 21.5],
                "14": [13.2, 14.2, 15.3, 16.6, 18, 19.5, 21.3],
                "15": [13.1, 14.1, 15.2, 16.4, 17.8, 19.4, 21.2],
                "16": [13.1, 14, 15.1, 16.3, 17.7, 19.3, 21],
                "17": [13, 13.9, 15, 16.2, 17.6, 19.1, 20.9],
                "18": [12.9, 13.9, 14.9, 16.1, 17.5, 19, 20.8],
                "19": [12.9, 13.8, 14.9, 16.1, 17.4, 18.9, 20.7],
                "20": [12.8, 13.7, 14.8, 16, 17.3, 18.8, 20.6],
                "21": [12.8, 13.7, 14.7, 15.9, 17.2, 18.7, 20.5],
                "22": [12.7, 13.6, 14.7, 15.8, 17.2, 18.7, 20.4],
                "23": [12.7, 13.6, 14.6, 15.8, 17.1, 18.6, 20.3],
                "24": [12.9, 13.8, 14.8, 16, 17.3, 18.9, 20.6],
                "25": [12.8, 13.8, 14.8, 16, 17.3, 18.8, 20.5],
                "26": [12.8, 13.7, 14.8, 15.9, 17.3, 18.8, 20.5],
                "27": [12.7, 13.7, 14.7, 15.9, 17.2, 18.7, 20.4],
                "28": [12.7, 13.6, 14.7, 15.9, 17.2, 18.7, 20.4],
                "29": [12.7, 13.6, 14.7, 15.8, 17.1, 18.6, 20.3],
                "30": [12.6, 13.6, 14.6, 15.8, 17.1, 18.6, 20.2],
                "31": [12.6, 13.5, 14.6, 15.8, 17.1, 18.5, 20.2],
                "32": [12.5, 13.5, 14.6, 15.7, 17, 18.5, 20.1],
                "33": [12.5, 13.5, 14.5, 15.7, 17, 18.5, 20.1],
                "34": [12.5, 13.4, 14.5, 15.7, 17, 18.4, 20],
                "35": [12.4, 13.4, 14.5, 15.6, 16.9, 18.4, 20],
                "36": [12.4, 13.4, 14.4, 15.6, 16.9, 18.4, 20],
                "37": [12.4, 13.3, 14.4, 15.6, 16.9, 18.3, 19.9],
                "38": [12.3, 13.3, 14.4, 15.5, 16.8, 18.3, 19.9],
                "39": [12.3, 13.3, 14.3, 15.5, 16.8, 18.3, 19.9],
                "40": [12.3, 13.2, 14.3, 15.5, 16.8, 18.2, 19.9],
                "41": [12.2, 13.2, 14.3, 15.5, 16.8, 18.2, 19.9],
                "42": [12.2, 13.2, 14.3, 15.4, 16.8, 18.2, 19.8],
                "43": [12.2, 13.2, 14.2, 15.4, 16.7, 18.2, 19.8],
                "44": [12.2, 13.1, 14.2, 15.4, 16.7, 18.2, 19.8],
                "45": [12.2, 13.1, 14.2, 15.4, 16.7, 18.2, 19.8],
                "46": [12.1, 13.1, 14.2, 15.4, 16.7, 18.2, 19.8],
                "47": [12.1, 13.1, 14.2, 15.3, 16.7, 18.2, 19.9],
                "48": [12.1, 13.1, 14.1, 15.3, 16.7, 18.2, 19.9],
                "49": [12.1, 13, 14.1, 15.3, 16.7, 18.2, 19.9],
                "50": [12.1, 13, 14.1, 15.3, 16.7, 18.2, 19.9],
                "51": [12.1, 13, 14.1, 15.3, 16.6, 18.2, 19.9],
                "52": [12, 13, 14.1, 15.3, 16.6, 18.2, 19.9],
                "53": [12, 13, 14.1, 15.3, 16.6, 18.2, 20],
                "54": [12, 13, 14, 15.3, 16.6, 18.2, 20],
                "55": [12, 13, 14, 15.2, 16.6, 18.2, 20],
                "56": [12, 12.9, 14, 15.2, 16.6, 18.2, 20.1],
                "57": [12, 12.9, 14, 15.2, 16.6, 18.2, 20.1],
                "58": [12, 12.9, 14, 15.2, 16.6, 18.3, 20.2],
                "59": [12, 12.9, 14, 15.2, 16.6, 18.3, 20.2],
                "60": [12, 12.9, 14, 15.2, 16.6, 18.3, 20.3]
            },
            "P": {
                "0": [10.1, 11.1, 12.2, 13.3, 14.6, 16.1, 17.7],
                "1": [10.8, 12, 13.2, 14.6, 16, 17.5, 19.1],
                "2": [11.8, 13, 14.3, 15.8, 17.3, 19, 20.7],
                "3": [12.4, 13.6, 14.9, 16.4, 17.9, 19.7, 21.5],
                "4": [12.7, 13.9, 15.2, 16.7, 18.3, 20, 22],
                "5": [12.9, 14.1, 15.4, 16.8, 18.4, 20.2, 22.2],
                "6": [13, 14.1, 15.5, 16.9, 18.5, 20.3, 22.3],
                "7": [13, 14.2, 15.5, 16.9, 18.5, 20.3, 22.3],
                "8": [13, 14.1, 15.4, 16.8, 18.4, 20.2, 22.2],
                "9": [12.9, 14.1, 15.3, 16.7, 18.3, 20.1, 22.1],
                "10": [12.9, 14, 15.2, 16.6, 18.2, 19.9, 21.9],
                "11": [12.8, 13.9, 15.1, 16.5, 18, 19.8, 21.8],
                "12": [12.7, 13.8, 15, 16.4, 17.9, 19.6, 21.6],
                "13": [12.6, 13.7, 14.9, 16.2, 17.7, 19.5, 21.4],
                "14": [12.6, 13.6, 14.8, 16.1, 17.6, 19.3, 21.3],
                "15": [12.5, 13.5, 14.7, 16, 17.5, 19.2, 21.1],
                "16": [12.4, 13.5, 14.6, 15.9, 17.4, 19.1, 21],
                "17": [12.4, 13.4, 14.5, 15.8, 17.3, 18.9, 20.9],
                "18": [12.3, 13.3, 14.4, 15.7, 17.2, 18.8, 20.8],
                "19": [12.3, 13.3, 14.4, 15.7, 17.1, 18.8, 20.7],
                "20": [12.2, 13.2, 14.3, 15.6, 17, 18.7, 20.6],
                "21": [12.2, 13.2, 14.3, 15.5, 17, 18.6, 20.5],
                "22": [12.2, 13.1, 14.2, 15.5, 16.9, 18.5, 20.4],
                "23": [12.2, 13.1, 14.2, 15.4, 16.9, 18.5, 20.4],
                "24": [12.4, 13.3, 14.4, 15.7, 17.1, 18.7, 20.6],
                "25": [12.4, 13.3, 14.4, 15.7, 17.1, 18.7, 20.6],
                "26": [12.3, 13.3, 14.4, 15.6, 17, 18.7, 20.6],
                "27": [12.3, 13.3, 14.4, 15.6, 17, 18.6, 20.5],
                "28": [12.3, 13.3, 14.3, 15.6, 17, 18.6, 20.5],
                "29": [12.3, 13.2, 14.3, 15.6, 17, 18.6, 20.4],
                "30": [12.3, 13.2, 14.3, 15.5, 16.9, 18.5, 20.4],
                "31": [12.2, 13.2, 14.3, 15.5, 16.9, 18.5, 20.4],
                "32": [12.2, 13.2, 14.3, 15.5, 16.9, 18.5, 20.4],
                "33": [12.2, 13.1, 14.2, 15.5, 16.9, 18.5, 20.3],
                "34": [12.2, 13.1, 14.2, 15.4, 16.8, 18.5, 20.3],
                "35": [12.1, 13.1, 14.2, 15.4, 16.8, 18.4, 20.3],
                "36": [12.1, 13.1, 14.2, 15.4, 16.8, 18.4, 20.3],
                "37": [12.1, 13.1, 14.1, 15.4, 16.8, 18.4, 20.3],
                "38": [12.1, 13, 14.1, 15.4, 16.8, 18.4, 20.3],
                "39": [12, 13, 14.1, 15.3, 16.8, 18.4, 20.3],
                "40": [12, 13, 14.1, 15.3, 16.8, 18.4, 20.3],
                "41": [12, 13, 14.1, 15.3, 16.8, 18.4, 20.4],
                "42": [12, 12.9, 14, 15.3, 16.8, 18.4, 20.4],
                "43": [11.9, 12.9, 14, 15.3, 16.8, 18.4, 20.4],
                "44": [11.9, 12.9, 14, 15.3, 16.8, 18.5, 20.4],
                "45": [11.9, 12.9, 14, 15.3, 16.8, 18.5, 20.5],
                "46": [11.9, 12.9, 14, 15.3, 16.8, 18.5, 20.5],
                "47": [11.8, 12.8, 14, 15.3, 16.8, 18.5, 20.5],
                "48": [11.8, 12.8, 14, 15.3, 16.8, 18.5, 20.6],
                "49": [11.8, 12.8, 13.9, 15.3, 16.8, 18.5, 20.6],
                "50": [11.8, 12.8, 13.9, 15.3, 16.8, 18.6, 20.7],
                "51": [11.8, 12.8, 13.9, 15.3, 16.8, 18.6, 20.7],
                "52": [11.7, 12.8, 13.9, 15.2, 16.8, 18.6, 20.7],
                "53": [11.7, 12.7, 13.9, 15.3, 16.8, 18.6, 20.8],
                "54": [11.7, 12.7, 13.9, 15.3, 16.8, 18.7, 20.8],
                "55": [11.7, 12.7, 13.9, 15.3, 16.8, 18.7, 20.9],
                "56": [11.7, 12.7, 13.9, 15.3, 16.8, 18.7, 20.9],
                "57": [11.7, 12.7, 13.9, 15.3, 16.9, 18.7, 21],
                "58": [11.7, 12.7, 13.9, 15.3, 16.9, 18.8, 21],
                "59": [11.6, 12.7, 13.9, 15.3, 16.9, 18.8, 21],
                "60": [11.6, 12.7, 13.9, 15.3, 16.9, 18.8, 21.1]
            }
        };

        // Tabel basis pengetahuan diagnosa stunting
        let tabelBasisPengetahuanStunting = {
            "L": {
                "0": [44.2, 46.1, 48.0, 49.9, 51.8, 53.7, 55.6],
                "1": [48.9, 50.8, 52.8, 54.7, 56.7, 58.6, 60.6],
                "2": [52.4, 54.4, 56.4, 58.4, 60.4, 62.4, 64.4],
                "3": [55.3, 57.3, 59.4, 61.4, 63.5, 65.5, 67.6],
                "4": [57.6, 59.7, 61.8, 63.9, 66.0, 68.0, 70.1],
                "5": [59.6, 61.7, 63.8, 65.9, 68.0, 70.1, 72.2],
                "6": [61.2, 63.3, 65.5, 67.6, 69.8, 71.9, 74.0],
                "7": [62.7, 64.8, 67.0, 69.2, 71.3, 73.5, 75.7],
                "8": [64.0, 66.2, 68.4, 70.6, 72.8, 75.0, 77.2],
                "9": [65.2, 67.5, 69.7, 72.0, 74.2, 76.5, 78.7],
                "10": [66.4, 68.7, 71.0, 73.3, 75.6, 77.9, 80.1],
                "11": [67.6, 69.9, 72.2, 74.5, 76.9, 79.2, 81.5],
                "12": [68.6, 71.0, 73.4, 75.7, 78.1, 80.5, 82.9],
                "13": [69.6, 72.1, 74.5, 76.9, 79.3, 81.8, 84.2],
                "14": [70.6, 73.1, 75.6, 78.0, 80.5, 83.0, 85.5],
                "15": [71.6, 74.1, 76.6, 79.1, 81.7, 84.2, 86.7],
                "16": [72.5, 75.0, 77.6, 80.2, 82.8, 85.4, 88.0],
                "17": [73.3, 76.0, 78.6, 81.2, 83.9, 86.5, 89.2],
                "18": [74.2, 76.9, 79.6, 82.3, 85.0, 87.7, 90.4],
                "19": [75.0, 77.7, 80.5, 83.2, 86.0, 88.8, 91.5],
                "20": [75.8, 78.6, 81.4, 84.2, 87.0, 89.8, 92.6],
                "21": [76.5, 79.4, 82.3, 85.1, 88.0, 90.9, 93.8],
                "22": [77.2, 80.2, 83.1, 86.0, 89.0, 91.9, 94.9],
                "23": [78.0, 81.0, 83.9, 86.9, 89.9, 92.9, 95.9],
                "24": [78.0, 81.0, 84.1, 87.1, 90.2, 93.2, 96.3],
                "25": [78.6, 81.7, 84.9, 88.0, 91.1, 94.2, 97.3],
                "26": [79.3, 82.5, 85.6, 88.8, 92.0, 95.2, 98.3],
                "27": [79.9, 83.1, 86.4, 89.6, 92.9, 96.1, 99.3],
                "28": [80.5, 83.8, 87.1, 90.4, 93.7, 97.0, 100.3],
                "29": [81.1, 84.5, 87.8, 91.2, 94.5, 97.9, 101.2],
                "30": [81.7, 85.1, 88.5, 91.9, 95.3, 98.7, 102.1],
                "31": [82.3, 85.7, 89.2, 92.7, 96.1, 99.6, 103.0],
                "32": [82.8, 86.4, 89.9, 93.4, 96.9, 100.4, 103.9],
                "33": [83.4, 86.9, 90.5, 94.1, 97.6, 101.2, 104.8],
                "34": [83.9, 87.5, 91.1, 94.8, 98.4, 102.0, 105.6],
                "35": [84.4, 88.1, 91.8, 95.4, 99.1, 102.7, 106.4],
                "36": [85.0, 88.7, 92.4, 96.1, 99.8, 103.5, 107.2],
                "37": [85.5, 89.2, 93.0, 96.7, 100.5, 104.2, 108.0],
                "38": [86.0, 89.8, 93.6, 97.4, 101.2, 105.0, 108.8],
                "39": [86.5, 90.3, 94.2, 98.0, 101.8, 105.7, 109.5],
                "40": [87.0, 90.9, 94.7, 98.6, 102.5, 106.4, 110.3],
                "41": [87.5, 91.4, 95.3, 99.2, 103.2, 107.1, 111.0],
                "42": [88.0, 91.9, 95.9, 99.9, 103.8, 107.8, 111.7],
                "43": [88.4, 92.4, 96.4, 100.4, 104.5, 108.5, 112.5],
                "44": [88.9, 93.0, 97.0, 101.0, 105.1, 109.1, 113.2],
                "45": [89.4, 93.5, 97.5, 101.6, 105.7, 109.8, 113.9],
                "46": [89.8, 94.0, 98.1, 102.2, 106.3, 110.4, 114.6],
                "47": [90.3, 94.4, 98.6, 102.8, 106.9, 111.1, 115.2],
                "48": [90.7, 94.9, 99.1, 103.3, 107.5, 111.7, 115.9],
                "49": [91.2, 95.4, 99.7, 103.9, 108.1, 112.4, 116.6],
                "50": [91.6, 95.9, 100.2, 104.4, 108.7, 113.0, 117.3],
                "51": [92.1, 96.4, 100.7, 105.0, 109.3, 113.6, 117.9],
                "52": [92.5, 96.9, 101.2, 105.6, 109.9, 114.2, 118.6],
                "53": [93.0, 97.4, 101.7, 106.1, 110.5, 114.9, 119.2],
                "54": [93.4, 97.8, 102.3, 106.7, 111.1, 115.5, 119.9],
                "55": [93.9, 98.3, 102.8, 107.2, 111.7, 116.1, 120.6],
                "56": [94.3, 98.8, 103.3, 107.8, 112.3, 116.7, 121.2],
                "57": [94.7, 99.3, 103.8, 108.3, 112.8, 117.4, 121.9],
                "58": [95.2, 99.7, 104.3, 108.9, 113.4, 118.0, 122.6],
                "59": [95.6, 100.2, 104.8, 109.4, 114.0, 118.6, 123.2],
                "60": [96.1, 100.7, 105.3, 110.0, 114.6, 119.2, 123.9]
            },
            "P": {
                "0": [43.6, 45.4, 47.3, 49.1, 51.0, 52.9, 54.7],
                "1": [47.8, 49.8, 51.7, 53.7, 55.6, 57.6, 59.5],
                "2": [51.0, 53.0, 55.0, 57.1, 59.1, 61.1, 63.2],
                "3": [53.5, 55.6, 57.7, 59.8, 61.9, 64.0, 66.1],
                "4": [55.6, 57.8, 59.9, 62.1, 64.3, 66.4, 68.6],
                "5": [57.4, 59.6, 61.8, 64.0, 66.2, 68.5, 70.7],
                "6": [58.9, 61.2, 63.5, 65.7, 68.0, 70.3, 72.5],
                "7": [60.3, 62.7, 65.0, 67.3, 69.6, 71.9, 74.2],
                "8": [61.7, 64.0, 66.4, 68.7, 71.1, 73.5, 75.8],
                "9": [62.9, 65.3, 67.7, 70.1, 72.6, 75.0, 77.4],
                "10": [64.1, 66.5, 69.0, 71.5, 73.9, 76.4, 78.9],
                "11": [65.2, 67.7, 70.3, 72.8, 75.3, 77.8, 80.3],
                "12": [66.3, 68.9, 71.4, 74.0, 76.6, 79.2, 81.7],
                "13": [67.3, 70.0, 72.6, 75.2, 77.8, 80.5, 83.1],
                "14": [68.3, 71.0, 73.7, 76.4, 79.1, 81.7, 84.4],
                "15": [69.3, 72.0, 74.8, 77.5, 80.2, 83.0, 85.7],
                "16": [70.2, 73.0, 75.8, 78.6, 81.4, 84.2, 87.0],
                "17": [71.1, 74.0, 76.8, 79.7, 82.5, 85.4, 88.2],
                "18": [72.0, 74.9, 77.8, 80.7, 83.6, 86.5, 89.4],
                "19": [72.8, 75.8, 78.8, 81.7, 84.7, 87.6, 90.6],
                "20": [73.7, 76.7, 79.7, 82.7, 85.7, 88.7, 91.7],
                "21": [74.5, 77.5, 80.6, 83.7, 86.7, 89.8, 92.9],
                "22": [75.2, 78.4, 81.5, 84.6, 87.7, 90.8, 94.0],
                "23": [76.0, 79.2, 82.3, 85.5, 88.7, 91.9, 95.0],
                "24": [76.0, 79.3, 82.5, 85.7, 88.9, 92.2, 95.4],
                "25": [76.8, 80.0, 83.3, 86.6, 89.9, 93.1, 96.4],
                "26": [77.5, 80.8, 84.1, 87.4, 90.8, 94.1, 97.4],
                "27": [78.1, 81.5, 84.9, 88.3, 91.7, 95.0, 98.4],
                "28": [78.8, 82.2, 85.7, 89.1, 92.5, 96.0, 99.4],
                "29": [79.5, 82.9, 86.4, 89.9, 93.4, 96.9, 100.3],
                "30": [80.1, 83.6, 87.1, 90.7, 94.2, 97.7, 101.3],
                "31": [80.7, 84.3, 87.9, 91.4, 95.0, 98.6, 102.2],
                "32": [81.3, 84.9, 88.6, 92.2, 95.8, 99.4, 103.1],
                "33": [81.9, 85.6, 89.3, 92.9, 96.6, 100.3, 103.9],
                "34": [82.5, 86.2, 89.9, 93.6, 97.4, 101.1, 104.8],
                "35": [83.1, 86.8, 90.6, 94.4, 98.1, 101.9, 105.6],
                "36": [83.6, 87.4, 91.2, 95.1, 98.9, 102.7, 106.5],
                "37": [84.2, 88.0, 91.9, 95.7, 99.6, 103.4, 107.3],
                "38": [84.7, 88.6, 92.5, 96.4, 100.3, 104.2, 108.1],
                "39": [85.3, 89.2, 93.1, 97.1, 101.0, 105.0, 108.9],
                "40": [85.8, 89.8, 93.8, 97.7, 101.7, 105.7, 109.7],
                "41": [86.3, 90.4, 94.4, 98.4, 102.4, 106.4, 110.5],
                "42": [86.8, 90.9, 95.0, 99.0, 103.1, 107.2, 111.2],
                "43": [87.4, 91.5, 95.6, 99.7, 103.8, 107.9, 112.0],
                "44": [87.9, 92.0, 96.2, 100.3, 104.5, 108.6, 112.7],
                "45": [88.4, 92.5, 96.7, 100.9, 105.1, 109.3, 113.5],
                "46": [88.9, 93.1, 97.3, 101.5, 105.8, 110.0, 114.2],
                "47": [89.3, 93.6, 97.9, 102.1, 106.4, 110.7, 114.9],
                "48": [89.8, 94.1, 98.4, 102.7, 107.0, 111.3, 115.7],
                "49": [90.3, 94.6, 99.0, 103.3, 107.7, 112.0, 116.4],
                "50": [90.7, 95.1, 99.5, 103.9, 108.3, 112.7, 117.1],
                "51": [91.2, 95.6, 100.1, 104.5, 108.9, 113.3, 117.7],
                "52": [91.7, 96.1, 100.6, 105.0, 109.5, 114.0, 118.4],
                "53": [92.1, 96.6, 101.1, 105.6, 110.1, 114.6, 119.1],
                "54": [92.6, 97.1, 101.6, 106.2, 110.7, 115.2, 119.8],
                "55": [93.0, 97.6, 102.2, 106.7, 111.3, 115.9, 120.4],
                "56": [93.4, 98.1, 102.7, 107.3, 111.9, 116.5, 121.1],
                "57": [93.9, 98.5, 103.2, 107.8, 112.5, 117.1, 121.8],
                "58": [94.3, 99.0, 103.7, 108.4, 113.0, 117.7, 122.4],
                "59": [94.7, 99.5, 104.2, 108.9, 113.6, 118.3, 123.1],
                "60": [95.2, 99.9, 104.7, 109.4, 114.2, 118.9, 123.7]
            }
        };

        // Mendapatkan data IMT dan TB pada usia yang sesuai berdasarkan jenis kelamin
        let dataIMT = tabelBasisPengetahuanGizi[jenisKelamin][usia];
        let dataTB = tabelBasisPengetahuanStunting[jenisKelamin][usia];

        // Mendapatkan nilai SD untuk gizi dan stunting
        let min3SDGizi = dataIMT[0];
        let min2SDGizi = dataIMT[1];
        let plus1SDGizi = dataIMT[4];
        let plus2SDGizi = dataIMT[5];
        let plus3SDGizi = dataIMT[6];

        let min3SDStunting = dataTB[0];
        let min2SDStunting = dataTB[1];

        // Menghitung IMT
        let IMT = beratBadan / (tinggiBadan * tinggiBadan) * 10000;

        let diagnosisGizi;
        if (IMT < min3SDGizi) {
            diagnosisGizi = "Gizi Buruk";
        } else if (IMT >= min3SDGizi && IMT < min2SDGizi) {
            diagnosisGizi = "Gizi Kurang";
        } else if (IMT >= min2SDGizi && IMT <= plus1SDGizi) {
            diagnosisGizi = "Gizi Baik";
        } else if (IMT > plus1SDGizi && IMT <= plus2SDGizi) {
            diagnosisGizi = "Berisiko Gizi Lebih";
        } else if (IMT > plus2SDGizi && IMT <= plus3SDGizi) {
            diagnosisGizi = "Gizi Lebih";
        } else if (IMT > plus3SDGizi) {
            diagnosisGizi = "Obesitas";
        }

        // Melakukan diagnosis stunting berdasarkan tinggi badan
        let diagnosisStunting;
        if (tinggiBadan < min3SDStunting) {
            diagnosisStunting = "Stunting Berat";
        } else if (tinggiBadan >= min3SDStunting && tinggiBadan < min2SDStunting) {
            diagnosisStunting = "Stunting Ringan";
        } else if (tinggiBadan >= min2SDStunting) {
            diagnosisStunting = "Tidak Stunting";
        }

        // Mengembalikan hasil diagnosa gizi dan stunting
        let hasilDiagnosa = {
            gizi: diagnosisGizi,
            stunting: diagnosisStunting,
            min3SDGizi: min3SDGizi,
            min2SDGizi: min2SDGizi,
            plus1SDGizi: plus1SDGizi,
            plus2SDGizi: plus2SDGizi,
            plus3SDGizi: plus3SDGizi,
            min2SDStunting: min2SDStunting,
            min3SDStunting: min3SDStunting,
            informasi: "",
            rekomendasi: []
        };

        // Menambahkan informasi hasil diagnosa dan rekomendasi
        if (diagnosisStunting === "Stunting Berat" && diagnosisGizi === "Gizi Buruk") {
            hasilDiagnosa.informasi = "Anak mengalami stunting berat dan gizi buruk. Anak memiliki tinggi badan dan berat badan yang sangat rendah.";
            hasilDiagnosa.rekomendasi.push("Konsultasikan dengan Dokter: Pertama-tama, segera berkonsultasi dengan dokter atau ahli gizi untuk mendapatkan panduan yang tepat berdasarkan kondisi anak.");
            hasilDiagnosa.rekomendasi.push(`Perbaikan Pola Makan: Berikan makanan bergizi dan seimbang dengan variasi yang cukup. Prioritaskan makanan yang kaya akan protein, vitamin, dan mineral.`);
            hasilDiagnosa.rekomendasi.push("Pemberian ASI atau Susu Formula: Jika anak masih di bawah 2 tahun, ASI eksklusif direkomendasikan. Jika tidak memungkinkan, gunakan susu formula yang direkomendasikan oleh dokter.");
            hasilDiagnosa.rekomendasi.push("Konsumsi Karbohidrat: Pastikan anak mendapatkan sumber karbohidrat sehat seperti nasi, roti gandum, dan kentang untuk energi.");
            hasilDiagnosa.rekomendasi.push("Protein Berkualitas: Berikan sumber protein seperti telur, daging tanpa lemak, ikan, dan produk susu rendah lemak untuk pertumbuhan dan perkembangan yang baik.");
            hasilDiagnosa.rekomendasi.push("Buah dan Sayuran: Tambahkan buah-buahan dan sayuran berwarna-warni sebagai sumber vitamin dan serat penting.");
            hasilDiagnosa.rekomendasi.push("Camilan Sehat: Pilih camilan sehat seperti yogurt rendah lemak, buah potong, atau kacang-kacangan tanpa garam.");
            hasilDiagnosa.rekomendasi.push("Pemberian Suplemen: Jika diperlukan, berikan suplemen vitamin atau mineral yang direkomendasikan oleh dokter.");
            hasilDiagnosa.rekomendasi.push("Pantau Pertumbuhan: Rutin pantau tinggi dan berat badan anak untuk melihat perkembangan pertumbuhannya.");
            hasilDiagnosa.rekomendasi.push("Ciptakan Lingkungan yang Positif: Berikan dukungan emosional kepada anak agar mereka merasa nyaman dan termotivasi untuk mengikuti perubahan pola makan.");
            hasilDiagnosa.rekomendasi.push("Pendidikan Gizi: Ajarkan anak dan anggota keluarga lainnya tentang pentingnya makanan sehat dan gizi yang seimbang.");
            hasilDiagnosa.rekomendasi.push("Rutin Pemeriksaan Medis: Pastikan anak menjalani pemeriksaan medis secara rutin untuk memantau perkembangan kesehatannya.");
        } else if (diagnosisStunting === "Stunting Berat" && diagnosisGizi === "Gizi Kurang") {
            hasilDiagnosa.informasi = "Anak mengalami stunting berat dan gizi kurang. Anak memiliki tinggi badan yang sangat rendah, sedangkan berat badan masih dalam batas normal atau sedikit kurang.";
            hasilDiagnosa.rekomendasi.push("Konsultasikan dengan Dokter: Segera berkonsultasi dengan dokter atau ahli gizi untuk evaluasi menyeluruh tentang kondisi anak dan rekomendasi penanganan yang tepat.");
            hasilDiagnosa.rekomendasi.push(`Peningkatan Pola Makan: Berikan makanan yang kaya nutrisi dengan porsi yang mencukupi. Pastikan anak mendapatkan makanan yang seimbang antara karbohidrat, protein, lemak, serta vitamin dan mineral.`);
            hasilDiagnosa.rekomendasi.push("Makanan Kaya Protein: Prioritaskan makanan sumber protein seperti telur, daging tanpa lemak, ikan, dan produk susu rendah lemak untuk membantu pertumbuhan dan perkembangan anak.");
            hasilDiagnosa.rekomendasi.push("Asupan Karbohidrat Sehat: Pilih sumber karbohidrat kompleks seperti nasi merah, roti gandum, dan kentang yang memberikan energi berkelanjutan.");
            hasilDiagnosa.rekomendasi.push("Buah dan Sayuran: Sertakan buah-buahan dan sayuran dalam setiap makan. Ini memberikan vitamin, mineral, dan serat penting bagi kesehatan anak.");
            hasilDiagnosa.rekomendasi.push("Kacang-kacangan dan Biji-bijian: Sumber protein nabati seperti kacang-kacangan, lentil, dan biji-bijian juga dapat dimasukkan dalam menu.");
            hasilDiagnosa.rekomendasi.push("Suplemen Gizi: Jika diperlukan, dokter atau ahli gizi mungkin merekomendasikan suplemen vitamin dan mineral.");
            hasilDiagnosa.rekomendasi.push("Makanan Kekayaan Zat Besi: Pastikan anak mendapatkan cukup zat besi dengan memasukkan makanan seperti daging merah, hati, dan sayuran berdaun hijau.");
            hasilDiagnosa.rekomendasi.push("Perhatikan Porsi Makan: Berikan porsi makan yang cukup sesuai dengan kebutuhan pertumbuhan dan aktivitas anak.");
            hasilDiagnosa.rekomendasi.push("Pendidikan Gizi: Ajarkan anak tentang pentingnya makanan sehat dan bagaimana memilih makanan yang baik bagi pertumbuhan mereka.");
            hasilDiagnosa.rekomendasi.push("Lingkungan yang Positif: Ciptakan lingkungan yang mendukung di rumah dengan menjaga suasana hati yang baik dan memberikan dukungan emosional.");
            hasilDiagnosa.rekomendasi.push("Pantau Pertumbuhan: Rutin pantau tinggi dan berat badan anak untuk memastikan perkembangannya berjalan sesuai dengan yang diharapkan.");
        } else if (diagnosisStunting === "Stunting Berat" && diagnosisGizi === "Gizi Baik") {
            hasilDiagnosa.informasi = "Anak mengalami stunting berat dan gizi baik. Anak memiliki tinggi badan yang sangat rendah, namun berat badan dalam batas normal untuk usianya.";
            hasilDiagnosa.rekomendasi.push("Konsultasikan dengan Dokter: Meskipun gizi anak sudah baik, penting untuk berkonsultasi dengan dokter atau ahli pertumbuhan untuk memahami penyebab stunting dan memastikan tindakan yang diambil sesuai.");
            hasilDiagnosa.rekomendasi.push("Pertahankan Pola Makan Sehat: Lanjutkan memberikan makanan bergizi yang sudah diberikan kepada anak. Pastikan pola makan sehat tetap terjaga untuk mendukung pertumbuhannya.");
            hasilDiagnosa.rekomendasi.push("Aktivitas Fisik: Anjuran aktivitas fisik yang sesuai dengan usia anak. Aktivitas ini membantu perkembangan fisik dan otot yang penting.");
            hasilDiagnosa.rekomendasi.push("Berikan Gizi Tambahan: Bila diperlukan, pertimbangkan suplemen vitamin atau mineral tambahan yang disarankan oleh dokter, terutama jika ada kekurangan spesifik.");
            hasilDiagnosa.rekomendasi.push("Pantau Pertumbuhan: Tetap pantau pertumbuhan tinggi dan berat badan anak secara teratur untuk memastikan perkembangannya sesuai dengan usianya.");
            hasilDiagnosa.rekomendasi.push("Asupan Cairan Cukup: Pastikan anak mendapatkan cukup cairan, seperti air putih, untuk menjaga kesehatan tubuhnya.");
            hasilDiagnosa.rekomendasi.push("Dukungan Psikologis: Ciptakan lingkungan yang positif dan dukung anak secara emosional untuk membantu rasa percaya dirinya.");
            hasilDiagnosa.rekomendasi.push("Edukasi Keluarga: Libatkan seluruh keluarga dalam pemahaman tentang pentingnya pola makan sehat dan perawatan yang baik bagi anak.");
            hasilDiagnosa.rekomendasi.push("Perhatikan Tanda-tanda Masalah: Jika Anda melihat perubahan dalam pertumbuhan anak atau ada tanda-tanda masalah kesehatan lainnya, segera berkonsultasi dengan dokter.");
            hasilDiagnosa.rekomendasi.push("Jadwal Pemeriksaan Rutin: Tetapkan jadwal pemeriksaan kesehatan rutin untuk memantau perkembangan anak secara berkala.");
            hasilDiagnosa.rekomendasi.push("Terlibat dalam Aktivitas Edukatif: Ikuti acara atau seminar yang berkaitan dengan pertumbuhan anak, gizi, dan perkembangan.");
            hasilDiagnosa.rekomendasi.push("Konsistensi: Teruskan langkah-langkah di atas dengan konsisten untuk memastikan pertumbuhan dan kesehatan anak tetap optimal.");
        } else if (diagnosisStunting === "Stunting Berat" && diagnosisGizi === "Berisiko Gizi Lebih") {
            hasilDiagnosa.informasi = "Anak mengalami stunting berat dan berisiko mengalami gizi lebih. Anak memiliki tinggi badan yang sangat rendah, namun berat badan berisiko melebihi batas normal untuk usianya.";
            hasilDiagnosa.rekomendasi.push("Konsultasikan dengan Dokter: Penting untuk berkonsultasi dengan dokter atau ahli gizi untuk mendapatkan panduan yang tepat berdasarkan kondisi anak.");
            hasilDiagnosa.rekomendasi.push("Evaluasi Gizi: Lakukan evaluasi menyeluruh terhadap pola makan anak dan penilaian risiko gizi lebih bersama dengan dokter atau ahli gizi.");
            hasilDiagnosa.rekomendasi.push("Rencanakan Pola Makan Sehat: Dengan bantuan dokter atau ahli gizi, rencanakan pola makan sehat yang mengakomodasi kebutuhan pertumbuhan anak tanpa memicu risiko gizi lebih.");
            hasilDiagnosa.rekomendasi.push("Pilih Makanan Bergizi: Pilih makanan yang kaya protein, serat, vitamin, dan mineral, sambil memperhatikan porsi dan kualitas nutrisi.");
            hasilDiagnosa.rekomendasi.push("Batasi Makanan Rendah Gizi: Hindari makanan yang kaya gula tambahan, lemak jenuh, dan garam berlebihan.");
            hasilDiagnosa.rekomendasi.push("Aktivitas Fisik: Anjuran aktivitas fisik yang sesuai dengan usia anak untuk membantu mengelola berat badan dan meningkatkan kesehatan.");
            hasilDiagnosa.rekomendasi.push("Pantau Pertumbuhan: Pantau perkembangan tinggi dan berat badan anak secara rutin untuk memastikan pertumbuhannya sesuai dengan usia.");
            hasilDiagnosa.rekomendasi.push("Edukasi Gizi: Ajarkan anak tentang pentingnya memilih makanan sehat dan menjaga keseimbangan antara konsumsi dan aktivitas fisik.");
            hasilDiagnosa.rekomendasi.push("Ciptakan Lingkungan Positif: Dukung anak dalam mengembangkan hubungan yang sehat dengan makanan dan tubuhnya, tanpa menimbulkan stres berlebihan.");
            hasilDiagnosa.rekomendasi.push("Dukungan Emosional: Bantu anak mengatasi masalah kepercayaan diri yang mungkin muncul akibat stunting dan risiko gizi lebih.");
            hasilDiagnosa.rekomendasi.push("Jadwal Pemeriksaan Berkala: Tetapkan jadwal pemeriksaan kesehatan berkala dengan dokter untuk memantau perkembangan kesehatan anak.");
            hasilDiagnosa.rekomendasi.push("Konsistensi dan Fleksibilitas: Terapkan langkah-langkah ini secara konsisten, sambil tetap fleksibel dalam menyesuaikan kebutuhan anak seiring waktu.");
        } else if (diagnosisStunting === "Stunting Berat" && diagnosisGizi === "Gizi Lebih") {
            hasilDiagnosa.informasi = "Anak mengalami stunting berat dan gizi lebih. Anak memiliki tinggi badan yang sangat rendah, namun berat badan melebihi batas normal untuk usianya.";
            hasilDiagnosa.rekomendasi.push("Konsultasikan dengan Dokter: Pertama-tama, berkonsultasilah dengan dokter atau ahli gizi untuk evaluasi menyeluruh mengenai kondisi anak dan rekomendasi penanganan yang tepat.");
            hasilDiagnosa.rekomendasi.push("Rencanakan Pola Makan Sehat: Bekerjasama dengan dokter atau ahli gizi, buatlah rencana pola makan sehat yang mengakomodasi kebutuhan pertumbuhan anak tanpa memicu risiko gizi lebih.");
            hasilDiagnosa.rekomendasi.push("Kontrol Asupan Kalori: Pastikan anak mendapatkan jumlah kalori yang sesuai dengan pertumbuhan dan tingkat aktivitas fisiknya.");
            hasilDiagnosa.rekomendasi.push("Pilih Makanan Seimbang: Pilih makanan yang kaya nutrisi seperti protein berkualitas, serat, vitamin, dan mineral. Hindari makanan tinggi lemak jenuh, gula tambahan, dan garam berlebih.");
            hasilDiagnosa.rekomendasi.push("Porsi yang Terkendali: Pertimbangkan porsi makan yang terkendali sesuai dengan kebutuhan anak untuk mencegah konsumsi kalori berlebih.");
            hasilDiagnosa.rekomendasi.push("Aktivitas Fisik Terencana: Anjuran aktivitas fisik yang sesuai dengan usia anak untuk membantu mengelola berat badan dan meningkatkan kesehatan.");
            hasilDiagnosa.rekomendasi.push("Pantau Pertumbuhan: Pantau pertumbuhan tinggi dan berat badan anak secara teratur untuk memastikan pertumbuhannya sesuai dengan usia.");
            hasilDiagnosa.rekomendasi.push("Edukasi Gizi: Ajarkan anak tentang pentingnya memilih makanan sehat dan menjaga keseimbangan antara konsumsi dan aktivitas fisik.");
            hasilDiagnosa.rekomendasi.push("Dukungan Psikologis: Bantu anak mengembangkan hubungan yang sehat dengan makanan dan tubuhnya, sambil mendukungnya secara emosional.");
            hasilDiagnosa.rekomendasi.push("Batasi Konsumsi Makanan Ringan: Kurangi konsumsi makanan ringan tinggi gula, lemak, dan garam yang dapat menyebabkan risiko gizi lebih.");
            hasilDiagnosa.rekomendasi.push("Kolaborasi dengan Tim Medis: Bekerjasama dengan dokter, ahli gizi, dan spesialis lainnya untuk memonitor dan mengatur penanganan anak.");
            hasilDiagnosa.rekomendasi.push("Fleksibilitas: Tetap fleksibel dalam menyesuaikan rencana penanganan sesuai dengan perkembangan anak dan rekomendasi medis.");
        } else if (diagnosisStunting === "Stunting Berat" && diagnosisGizi === "Obesitas") {
            hasilDiagnosa.informasi = "Anak mengalami stunting berat dan obesitas. Anak memiliki tinggi badan yang sangat rendah, namun berat badan melebihi batas normal untuk usianya.";
            hasilDiagnosa.rekomendasi.push("Konsultasikan dengan Dokter: Pertama-tama, berkonsultasilah dengan dokter atau ahli gizi untuk evaluasi menyeluruh dan rekomendasi penanganan yang tepat.");
            hasilDiagnosa.rekomendasi.push("Rencanakan Pola Makan Sehat: Bekerjasama dengan dokter atau ahli gizi, buat rencana pola makan sehat yang seimbang dan memenuhi kebutuhan nutrisi anak tanpa memicu risiko gizi lebih.");
            hasilDiagnosa.rekomendasi.push("Pilih Makanan Nutritif: Pilih makanan yang kaya protein berkualitas, serat, vitamin, dan mineral. Hindari makanan tinggi lemak jenuh, gula tambahan, dan garam berlebih.");
            hasilDiagnosa.rekomendasi.push("Kontrol Asupan Kalori: Pastikan anak mendapatkan jumlah kalori yang sesuai dengan pertumbuhan dan tingkat aktivitas fisiknya, dengan menghindari kelebihan kalori.");
            hasilDiagnosa.rekomendasi.push("Porsi yang Terkendali: Pertimbangkan porsi makan yang sesuai dengan kebutuhan anak, menghindari konsumsi berlebihan.");
            hasilDiagnosa.rekomendasi.push("Aktivitas Fisik: Anjuran aktivitas fisik yang sesuai dengan usia anak, membantu mengelola berat badan dan meningkatkan kesehatan.");
            hasilDiagnosa.rekomendasi.push("Pantau Pertumbuhan: Pantau pertumbuhan tinggi dan berat badan anak secara teratur untuk memastikan pertumbuhannya sesuai dengan usia.");
            hasilDiagnosa.rekomendasi.push("Edukasi Gizi: Ajarkan anak tentang pentingnya makan sehat dan menjaga keseimbangan antara konsumsi dan aktivitas fisik.");
            hasilDiagnosa.rekomendasi.push("Dukungan Psikologis: Bantu anak mengembangkan hubungan yang sehat dengan makanan dan tubuhnya, sambil mendukungnya secara emosional.");
            hasilDiagnosa.rekomendasi.push("Batasi Konsumsi Makanan Ringan: Kurangi konsumsi makanan ringan tinggi gula, lemak, dan garam yang dapat memicu risiko gizi lebih.");
            hasilDiagnosa.rekomendasi.push("Kolaborasi dengan Tim Medis: Bekerjasama dengan dokter, ahli gizi, dan spesialis lainnya untuk memonitor dan mengatur penanganan anak.");
            hasilDiagnosa.rekomendasi.push("Ciptakan Lingkungan Sehat: Buat lingkungan di rumah yang mendukung pola makan sehat dan gaya hidup aktif bagi seluruh keluarga.");
        } else if (diagnosisStunting === "Stunting Ringan" && diagnosisGizi === "Gizi Buruk") {
            hasilDiagnosa.informasi = "Anak mengalami stunting ringan dan status gizi buruk. Tinggi badan anak kurang dari batas normal untuk usianya dan berat badan anak juga kurang.";
            hasilDiagnosa.rekomendasi.push("Konsultasikan dengan Dokter: Berkonsultasilah dengan dokter atau ahli gizi untuk mendapatkan evaluasi yang akurat tentang kondisi anak dan rekomendasi penanganan yang sesuai.");
            hasilDiagnosa.rekomendasi.push("Rencanakan Pola Makan Seimbang: Bekerjasama dengan dokter atau ahli gizi, buat rencana pola makan seimbang yang memenuhi kebutuhan nutrisi anak dan mendukung pertumbuhannya.");
            hasilDiagnosa.rekomendasi.push("Berikan Makanan Bergizi: Pilih makanan yang kaya protein, vitamin, dan mineral untuk membantu anak dalam pemulihan gizinya.");
            hasilDiagnosa.rekomendasi.push("Asupan Karbohidrat Sehat: Sertakan karbohidrat kompleks seperti nasi merah, roti gandum, dan kentang dalam makanan untuk memberikan energi.");
            hasilDiagnosa.rekomendasi.push("Porsi yang Sesuai: Pastikan anak mendapatkan porsi makan yang cukup sesuai dengan kebutuhan pertumbuhan dan aktivitasnya.");
            hasilDiagnosa.rekomendasi.push("Pantau Pertumbuhan: Rutin pantau tinggi dan berat badan anak untuk melihat perkembangan pertumbuhannya.");
            hasilDiagnosa.rekomendasi.push("Camilan Sehat: Sediakan camilan sehat seperti buah potong, kacang-kacangan, atau yogurt rendah lemak untuk memenuhi nutrisi tambahan.");
            hasilDiagnosa.rekomendasi.push("Pemberian Suplemen: Jika diperlukan, berikan suplemen vitamin atau mineral yang direkomendasikan oleh dokter.");
            hasilDiagnosa.rekomendasi.push("Edukasi Gizi: Ajarkan anak tentang pentingnya makanan sehat dan gizi yang seimbang untuk pertumbuhan dan kesehatan.");
            hasilDiagnosa.rekomendasi.push("Lingkungan Positif: Ciptakan lingkungan yang positif di rumah dengan mendorong kebiasaan makan sehat dan aktifitas fisik.");
            hasilDiagnosa.rekomendasi.push("Dukungan Emosional: Berikan dukungan emosional kepada anak untuk membantu meningkatkan kepercayaan dirinya.");
            hasilDiagnosa.rekomendasi.push("Kontinuitas dan Kesabaran: Proses pemulihan memerlukan waktu, jadi tetap konsisten dengan langkah-langkah di atas dan bersabarlah.");
        } else if (diagnosisStunting === "Stunting Ringan" && diagnosisGizi === "Gizi Kurang") {
            hasilDiagnosa.informasi = "Anak mengalami stunting ringan dan status gizi kurang. Tinggi badan anak kurang dari batas normal untuk usianya namun berat badan normal.";
            hasilDiagnosa.rekomendasi.push("Konsultasikan dengan Dokter: Segera berkonsultasi dengan dokter atau ahli gizi untuk evaluasi mendalam tentang kondisi anak dan rekomendasi penanganan yang tepat.");
            hasilDiagnosa.rekomendasi.push("Rencanakan Pola Makan Sehat: Bekerjasama dengan dokter atau ahli gizi, susun rencana pola makan yang mengandung berbagai nutrisi penting seperti protein, vitamin, dan mineral.");
            hasilDiagnosa.rekomendasi.push("Kaya Protein: Berikan makanan kaya protein seperti telur, daging tanpa lemak, ikan, dan produk susu rendah lemak untuk mendukung pertumbuhan anak.");
            hasilDiagnosa.rekomendasi.push("Sumber Karbohidrat Sehat: Pilih karbohidrat kompleks seperti nasi merah, roti gandum, dan kentang untuk memberikan energi yang berkelanjutan.");
            hasilDiagnosa.rekomendasi.push("Buah dan Sayuran: Sertakan berbagai jenis buah dan sayuran dalam makanan anak untuk mendapatkan vitamin dan serat yang penting.");
            hasilDiagnosa.rekomendasi.push("Porsi yang Cukup: Pastikan anak mendapatkan porsi makan yang sesuai dengan kebutuhan pertumbuhan dan aktivitasnya.");
            hasilDiagnosa.rekomendasi.push("Camilan Nutrisi: Pilih camilan sehat seperti buah potong, kacang-kacangan, atau yogurt rendah lemak untuk memberikan nutrisi tambahan.");
            hasilDiagnosa.rekomendasi.push("Pantau Pertumbuhan: Tetap pantau tinggi dan berat badan anak secara berkala untuk melihat perkembangan pertumbuhannya.");
            hasilDiagnosa.rekomendasi.push("Edukasi Gizi: Ajarkan anak tentang pentingnya makanan sehat dan gizi yang seimbang untuk pertumbuhan dan kesehatan.");
            hasilDiagnosa.rekomendasi.push("Pemberian Suplemen: Jika diperlukan, berikan suplemen vitamin atau mineral yang direkomendasikan oleh dokter.");
            hasilDiagnosa.rekomendasi.push("Lingkungan yang Positif: Ciptakan lingkungan yang mendukung kebiasaan makan sehat dan aktivitas fisik.");
            hasilDiagnosa.rekomendasi.push("Dukungan Emosional: Berikan dukungan emosional kepada anak untuk membantu membangun rasa percaya diri dan kesadaran akan pentingnya kesehatan.");
        } else if (diagnosisStunting === "Stunting Ringan" && diagnosisGizi === "Gizi Baik") {
            hasilDiagnosa.informasi = "Anak mengalami stunting ringan namun status gizinya baik. Tinggi badan anak kurang dari batas normal untuk usianya namun berat badan normal.";
            hasilDiagnosa.rekomendasi.push("Konsultasikan dengan Dokter: Walaupun gizi anak sudah baik, konsultasilah dengan dokter atau ahli gizi untuk evaluasi lebih lanjut mengenai kondisi stunting dan saran untuk penanganan yang lebih spesifik.");
            hasilDiagnosa.rekomendasi.push("Aktivitas Fisik Teratur: Anjurkan anak untuk berpartisipasi dalam aktivitas fisik yang sesuai dengan usia, seperti bermain di luar atau berolahraga, untuk mempromosikan pertumbuhan dan kesehatan.");
            hasilDiagnosa.rekomendasi.push("Rencanakan Pola Makan Seimbang: Pertahankan pola makan sehat yang beragam, dengan porsi yang sesuai untuk mempertahankan gizi yang baik.");
            hasilDiagnosa.rekomendasi.push("Buah dan Sayuran: Tetapkan kebiasaan makan buah-buahan dan sayuran yang beragam, memberikan vitamin dan serat yang penting untuk pertumbuhan dan kesehatan.");
            hasilDiagnosa.rekomendasi.push("Sumber Protein Berkualitas: Pastikan anak mendapatkan protein berkualitas seperti daging tanpa lemak, ikan, dan produk susu rendah lemak untuk pertumbuhan dan perkembangan yang optimal.");
            hasilDiagnosa.rekomendasi.push("Karbohidrat Sehat: Pilih sumber karbohidrat kompleks seperti nasi merah, roti gandum, dan kentang yang memberikan energi berkelanjutan.");
            hasilDiagnosa.rekomendasi.push("Pantau Pertumbuhan: Terus pantau pertumbuhan tinggi dan berat badan anak untuk memastikan pertumbuhannya tetap sesuai dengan usianya.");
            hasilDiagnosa.rekomendasi.push("Dukungan Pendidikan Gizi: Terus ajarkan anak tentang pentingnya makanan sehat dan bagaimana memilih makanan yang baik untuk kesehatan mereka.");
            hasilDiagnosa.rekomendasi.push("Lingkungan Positif: Ciptakan lingkungan yang mendukung pola makan sehat dan gaya hidup aktif bagi anak.");
            hasilDiagnosa.rekomendasi.push("Dukungan Emosional: Berikan dukungan emosional kepada anak agar merasa percaya diri dan memiliki pola pikir positif tentang pertumbuhannya.");
            hasilDiagnosa.rekomendasi.push("Pendidikan Kesehatan: Libatkan anak dalam acara atau seminar yang berkaitan dengan pertumbuhan, nutrisi, dan kesehatan.");
            hasilDiagnosa.rekomendasi.push("Pemeriksaan Kesehatan Rutin: Tetapkan jadwal pemeriksaan kesehatan berkala dengan dokter untuk memastikan pertumbuhan dan kesehatan anak tetap optimal.");
        } else if (diagnosisStunting === "Stunting Ringan" && diagnosisGizi === "Berisiko Gizi Lebih") {
            hasilDiagnosa.informasi = "Anak mengalami stunting ringan dan berisiko mengalami gizi lebih. Tinggi badan anak kurang dari batas normal untuk usianya namun berat badan berisiko melebihi batas normal.";
            hasilDiagnosa.rekomendasi.push("Konsultasikan dengan Dokter: Berkonsultasilah dengan dokter atau ahli gizi untuk mendapatkan evaluasi yang akurat tentang kondisi anak dan rekomendasi penanganan yang tepat.");
            hasilDiagnosa.rekomendasi.push("Pola Makan Seimbang: Bekerjasama dengan dokter atau ahli gizi, buat rencana pola makan yang seimbang, memenuhi kebutuhan nutrisi anak tanpa memicu risiko gizi lebih.");
            hasilDiagnosa.rekomendasi.push("Batasi Kalori Berlebih: Pastikan anak mendapatkan jumlah kalori yang sesuai dengan pertumbuhan dan aktivitas fisiknya, menghindari konsumsi berlebihan.");
            hasilDiagnosa.rekomendasi.push("Pilih Makanan Nutritif: Prioritaskan makanan yang kaya protein berkualitas, serat, vitamin, dan mineral. Hindari makanan tinggi lemak jenuh, gula tambahan, dan garam berlebih.");
            hasilDiagnosa.rekomendasi.push("Porsi yang Terkendali: Pertimbangkan porsi makan yang terkendali sesuai dengan kebutuhan pertumbuhan dan aktivitas anak.");
            hasilDiagnosa.rekomendasi.push("Aktivitas Fisik Teratur: Anjurkan anak untuk berpartisipasi dalam aktivitas fisik yang sesuai dengan usia, membantu mengelola berat badan dan meningkatkan kesehatan.");
            hasilDiagnosa.rekomendasi.push("Pantau Pertumbuhan: Pantau pertumbuhan tinggi dan berat badan anak secara teratur untuk memastikan perkembangannya sesuai dengan usia.");
            hasilDiagnosa.rekomendasi.push("Dukungan Psikologis: Bantu anak mengembangkan hubungan yang sehat dengan makanan dan tubuhnya, sambil mendukungnya secara emosional.");
            hasilDiagnosa.rekomendasi.push("Edukasi Gizi: Ajarkan anak tentang pentingnya makanan sehat dan menjaga keseimbangan antara konsumsi dan aktivitas fisik.");
            hasilDiagnosa.rekomendasi.push("Lingkungan Sehat: Buat lingkungan di rumah yang mendukung pola makan sehat dan gaya hidup aktif bagi seluruh keluarga.");
            hasilDiagnosa.rekomendasi.push("Kolaborasi dengan Tim Medis: Bekerjasama dengan dokter, ahli gizi, dan spesialis lainnya untuk memonitor dan mengatur penanganan anak.");
            hasilDiagnosa.rekomendasi.push("Fleksibilitas dan Konsistensi: Pertahankan rencana penanganan dengan konsisten sambil tetap fleksibel dalam menyesuaikan kebutuhan anak.");
        } else if (diagnosisStunting === "Stunting Ringan" && diagnosisGizi === "Gizi Lebih") {
            hasilDiagnosa.informasi = "Anak mengalami stunting ringan dan status gizi lebih. Tinggi badan anak kurang dari batas normal untuk usianya namun berat badan melebihi batas normal.";
            hasilDiagnosa.rekomendasi.push("Konsultasikan dengan Dokter: Pertama-tama, berkonsultasilah dengan dokter atau ahli gizi untuk mendapatkan evaluasi yang akurat tentang kondisi anak dan rekomendasi penanganan yang tepat.");
            hasilDiagnosa.rekomendasi.push("Rencanakan Pola Makan Sehat: Bekerjasama dengan dokter atau ahli gizi, buatlah rencana pola makan sehat yang seimbang, memperhitungkan kebutuhan nutrisi anak tanpa memicu risiko gizi lebih.");
            hasilDiagnosa.rekomendasi.push("Kontrol Asupan Kalori: Pastikan anak mendapatkan jumlah kalori yang sesuai dengan pertumbuhan dan tingkat aktivitas fisiknya, dengan memperhatikan porsi yang tepat.");
            hasilDiagnosa.rekomendasi.push("Pilih Makanan Nutritif: Prioritaskan makanan yang kaya protein berkualitas, serat, vitamin, dan mineral. Hindari makanan tinggi lemak jenuh, gula tambahan, dan garam berlebih.");
            hasilDiagnosa.rekomendasi.push("Porsi yang Terkendali: Pertimbangkan porsi makan yang sesuai dengan kebutuhan pertumbuhan dan aktivitas anak, untuk menghindari konsumsi kalori berlebih.");
            hasilDiagnosa.rekomendasi.push("Aktivitas Fisik Teratur: Anjurkan anak untuk berpartisipasi dalam aktivitas fisik yang sesuai dengan usia, membantu mengelola berat badan dan meningkatkan kesehatan.");
            hasilDiagnosa.rekomendasi.push("Pantau Pertumbuhan: Tetap pantau pertumbuhan tinggi dan berat badan anak secara berkala untuk memastikan perkembangannya sesuai dengan usia.");
            hasilDiagnosa.rekomendasi.push("Edukasi Gizi: Ajarkan anak tentang pentingnya makanan sehat dan menjaga keseimbangan antara konsumsi dan aktivitas fisik.");
            hasilDiagnosa.rekomendasi.push("Dukungan Psikologis: Bantu anak mengembangkan hubungan yang sehat dengan makanan dan tubuhnya, sambil mendukungnya secara emosional.");
            hasilDiagnosa.rekomendasi.push("Lingkungan Sehat: Ciptakan lingkungan di rumah yang mendukung pola makan sehat dan gaya hidup aktif bagi seluruh keluarga.");
            hasilDiagnosa.rekomendasi.push("Kolaborasi dengan Tim Medis: Bekerjasama dengan dokter, ahli gizi, dan spesialis lainnya untuk memonitor dan mengatur penanganan anak.");
            hasilDiagnosa.rekomendasi.push("Kesabaran dan Konsistensi: Ingatlah bahwa perubahan memerlukan waktu. Pertahankan langkah-langkah ini dengan konsisten.");
        } else if (diagnosisStunting === "Stunting Ringan" && diagnosisGizi === "Obesitas") {
            hasilDiagnosa.informasi = "Anak mengalami stunting ringan dan obesitas. Tinggi badan anak kurang dari batas normal untuk usianya namun berat badan melebihi batas normal.";
            hasilDiagnosa.rekomendasi.push("Konsultasikan dengan Dokter: Pertama-tama, konsultasilah dengan dokter atau ahli gizi untuk mendapatkan evaluasi yang akurat tentang kondisi anak dan rekomendasi penanganan yang tepat.");
            hasilDiagnosa.rekomendasi.push("Rencanakan Pola Makan Sehat: Bekerjasama dengan dokter atau ahli gizi, buat rencana pola makan sehat yang seimbang, memenuhi kebutuhan nutrisi anak sambil menghindari konsumsi berlebihan.");
            hasilDiagnosa.rekomendasi.push("Batasi Asupan Kalori: Pastikan anak mendapatkan jumlah kalori yang sesuai dengan pertumbuhan dan tingkat aktivitas fisiknya, dengan memperhatikan porsi yang terkendali.");
            hasilDiagnosa.rekomendasi.push("Pilih Makanan Nutritif: Prioritaskan makanan yang kaya protein berkualitas, serat, vitamin, dan mineral. Hindari makanan tinggi lemak jenuh, gula tambahan, dan garam berlebih.");
            hasilDiagnosa.rekomendasi.push("Aktivitas Fisik Teratur: Anjurkan anak untuk berpartisipasi dalam aktivitas fisik yang sesuai dengan usia, membantu mengelola berat badan dan meningkatkan kesehatan.");
            hasilDiagnosa.rekomendasi.push("Pantau Pertumbuhan: Tetap pantau pertumbuhan tinggi dan berat badan anak secara berkala untuk memastikan perkembangannya sesuai dengan usia.");
            hasilDiagnosa.rekomendasi.push("Dukungan Psikologis: Bantu anak mengembangkan hubungan yang sehat dengan makanan dan tubuhnya, sambil mendukungnya secara emosional.");
            hasilDiagnosa.rekomendasi.push("Edukasi Gizi: Ajarkan anak tentang pentingnya makanan sehat dan menjaga keseimbangan antara konsumsi dan aktivitas fisik.");
            hasilDiagnosa.rekomendasi.push("Lingkungan Sehat: Ciptakan lingkungan di rumah yang mendukung pola makan sehat dan gaya hidup aktif bagi seluruh keluarga.");
            hasilDiagnosa.rekomendasi.push("Kolaborasi dengan Tim Medis: Bekerjasama dengan dokter, ahli gizi, dan spesialis lainnya untuk memonitor dan mengatur penanganan anak.");
            hasilDiagnosa.rekomendasi.push("Konsistensi dan Fleksibilitas: Pertahankan rencana penanganan dengan konsisten sambil tetap fleksibel dalam menyesuaikan kebutuhan anak.");
            hasilDiagnosa.rekomendasi.push("Proses Bertahap: Ingatlah bahwa perubahan memerlukan waktu. Lakukan perubahan secara bertahap dan dengan pendekatan yang seimbang.");
        } else if (diagnosisStunting === "Tidak Stunting" && diagnosisGizi === "Gizi Buruk") {
            hasilDiagnosa.informasi = "Anak tidak mengalami stunting namun status gizi buruk. Tinggi badan anak normal untuk usianya namun berat badan anak kurang.";
            hasilDiagnosa.rekomendasi.push("Konsultasikan dengan Dokter: Berkonsultasilah dengan dokter atau ahli gizi untuk mendapatkan evaluasi mendalam tentang kondisi anak dan rekomendasi penanganan yang tepat.");
            hasilDiagnosa.rekomendasi.push("Rencanakan Pola Makan Bergizi: Bekerjasama dengan dokter atau ahli gizi, buat rencana pola makan yang mengandung berbagai nutrisi penting seperti protein, vitamin, dan mineral.");
            hasilDiagnosa.rekomendasi.push("Sumber Protein: Berikan makanan sumber protein berkualitas seperti daging tanpa lemak, ikan, dan produk susu rendah lemak untuk membantu pemulihan gizi anak.");
            hasilDiagnosa.rekomendasi.push("Pilih Karbohidrat Sehat: Sertakan karbohidrat kompleks seperti nasi merah, roti gandum, dan kentang dalam makanan untuk memberikan energi yang berkelanjutan.");
            hasilDiagnosa.rekomendasi.push("Buah dan Sayuran: Sertakan berbagai jenis buah dan sayuran dalam makanan anak untuk mendapatkan vitamin dan serat yang penting.");
            hasilDiagnosa.rekomendasi.push("Porsi yang Cukup: Pastikan anak mendapatkan porsi makan yang cukup sesuai dengan kebutuhan pertumbuhan dan aktivitasnya.");
            hasilDiagnosa.rekomendasi.push("Pantau Pertumbuhan: Rutin pantau tinggi dan berat badan anak untuk melihat perkembangan pertumbuhannya.");
            hasilDiagnosa.rekomendasi.push("Dukungan Psikologis: Bantu anak mengatasi masalah kepercayaan diri yang mungkin muncul akibat gizi buruk, dan ciptakan lingkungan yang positif di sekitarnya.");
            hasilDiagnosa.rekomendasi.push("Edukasi Gizi: Ajarkan anak dan keluarga tentang pentingnya makanan sehat dan menjaga pola makan yang baik.");
            hasilDiagnosa.rekomendasi.push("Camilan Nutrisi: Sediakan camilan sehat seperti buah potong, kacang-kacangan, atau yogurt rendah lemak untuk memenuhi nutrisi tambahan.");
            hasilDiagnosa.rekomendasi.push("Minuman Sehat: Pastikan anak mendapatkan cairan yang cukup dengan memberikan air putih atau minuman rendah gula.");
            hasilDiagnosa.rekomendasi.push("Kolaborasi dengan Tim Medis: Bekerjasama dengan dokter, ahli gizi, dan spesialis lainnya untuk memonitor dan mengatur penanganan anak.");
            hasilDiagnosa.rekomendasi.push("Kesabaran dan Konsistensi: Ingat bahwa proses pemulihan memerlukan waktu. Pertahankan langkah-langkah ini dengan konsisten.");
        } else if (diagnosisStunting === "Tidak Stunting" && diagnosisGizi === "Gizi Kurang") {
            hasilDiagnosa.informasi = "Anak tidak mengalami stunting namun status gizi kurang. Tinggi badan dan berat badan anak normal untuk usianya.";
            hasilDiagnosa.rekomendasi.push("Konsultasikan dengan Dokter: Pertama-tama, berkonsultasilah dengan dokter atau ahli gizi untuk mendapatkan evaluasi yang akurat tentang kondisi anak dan rekomendasi penanganan yang tepat.");
            hasilDiagnosa.rekomendasi.push("Rencanakan Pola Makan Bergizi: Bekerjasama dengan dokter atau ahli gizi, buatlah rencana pola makan yang mengandung berbagai nutrisi penting seperti protein, vitamin, dan mineral.");
            hasilDiagnosa.rekomendasi.push("Asupan Protein: Berikan makanan yang mengandung protein berkualitas seperti daging tanpa lemak, ikan, dan produk susu rendah lemak untuk membantu pemulihan gizi anak.");
            hasilDiagnosa.rekomendasi.push("Karbohidrat Sehat: Pilih karbohidrat kompleks seperti nasi merah, roti gandum, dan kentang untuk memberikan energi yang berkelanjutan.");
            hasilDiagnosa.rekomendasi.push("Buah dan Sayuran: Sertakan berbagai jenis buah dan sayuran dalam makanan anak untuk memenuhi kebutuhan vitamin dan serat.");
            hasilDiagnosa.rekomendasi.push("Porsi yang Cukup: Pastikan anak mendapatkan porsi makan yang cukup sesuai dengan kebutuhan pertumbuhan dan aktivitasnya.");
            hasilDiagnosa.rekomendasi.push("Pantau Pertumbuhan: Rutin pantau tinggi dan berat badan anak untuk melihat perkembangan pertumbuhannya.");
            hasilDiagnosa.rekomendasi.push("Suplemen Gizi: Jika diperlukan, berikan suplemen vitamin atau mineral yang direkomendasikan oleh dokter.");
            hasilDiagnosa.rekomendasi.push("Dukungan Psikologis: Bantu anak mengatasi masalah kepercayaan diri yang mungkin muncul akibat gizi kurang, dan ciptakan lingkungan yang positif di sekitarnya.");
            hasilDiagnosa.rekomendasi.push("Edukasi Gizi: Ajarkan anak dan keluarga tentang pentingnya makanan sehat dan menjaga pola makan yang baik.");
            hasilDiagnosa.rekomendasi.push("Camilan Nutrisi: Sediakan camilan sehat seperti buah potong, kacang-kacangan, atau yogurt rendah lemak untuk memberikan nutrisi tambahan.");
            hasilDiagnosa.rekomendasi.push("Minuman Sehat: Pastikan anak mendapatkan cairan yang cukup dengan memberikan air putih atau minuman rendah gula.");
            hasilDiagnosa.rekomendasi.push("Kolaborasi dengan Tim Medis: Bekerjasama dengan dokter, ahli gizi, dan spesialis lainnya untuk memonitor dan mengatur penanganan anak.");
            hasilDiagnosa.rekomendasi.push("Kesabaran dan Konsistensi: Ingat bahwa pemulihan memerlukan waktu. Pertahankan langkah-langkah ini dengan konsisten.");
        } else if (diagnosisStunting === "Tidak Stunting" && diagnosisGizi === "Gizi Baik") {
            hasilDiagnosa.informasi = "Anak tidak mengalami stunting dan status gizinya baik. Tinggi badan dan berat badan anak normal untuk usianya.";
            hasilDiagnosa.rekomendasi.push("Pertahankan Pola Makan Sehat: Lanjutkan dengan memberikan pola makan yang seimbang dan kaya nutrisi, dengan memperhatikan kebutuhan pertumbuhan dan aktivitas anak.");
            hasilDiagnosa.rekomendasi.push("Berikan Makanan Nutritif: Sertakan makanan yang kaya protein, vitamin, dan mineral dalam diet anak untuk mendukung kesehatan dan perkembangannya.");
            hasilDiagnosa.rekomendasi.push("Kombinasikan Beragam Makanan: Pastikan anak mendapatkan berbagai jenis makanan dari semua kelompok makanan untuk memenuhi kebutuhan nutrisi yang beragam.");
            hasilDiagnosa.rekomendasi.push("Karbohidrat Sehat: Pilih karbohidrat kompleks seperti nasi merah, roti gandum, dan kentang untuk memberikan energi berkelanjutan.");
            hasilDiagnosa.rekomendasi.push("Buah dan Sayuran: Sertakan berbagai jenis buah dan sayuran dalam makanan anak untuk memenuhi kebutuhan vitamin dan serat.");
            hasilDiagnosa.rekomendasi.push("Porsi yang Sesuai: Pastikan anak mendapatkan porsi makan yang sesuai dengan kebutuhan pertumbuhan dan aktivitasnya.");
            hasilDiagnosa.rekomendasi.push("Aktivitas Fisik Teratur: Anjurkan anak untuk berpartisipasi dalam aktivitas fisik yang sesuai dengan usia, membantu menjaga kesehatan dan kebugaran.");
            hasilDiagnosa.rekomendasi.push("Pantau Pertumbuhan: Tetap pantau tinggi dan berat badan anak secara berkala untuk memastikan perkembangan pertumbuhannya sesuai dengan usia.");
            hasilDiagnosa.rekomendasi.push("Edukasi Gizi: Terus ajarkan anak tentang pentingnya gizi seimbang dan memberi tahu mereka tentang manfaat makanan sehat.");
            hasilDiagnosa.rekomendasi.push("Camilan Sehat: Sediakan camilan sehat seperti buah potong, kacang-kacangan, atau yogurt rendah lemak untuk memberikan nutrisi tambahan.");
            hasilDiagnosa.rekomendasi.push("Minuman Sehat: Pastikan anak mendapatkan cairan yang cukup dengan memberikan air putih atau minuman rendah gula.");
            hasilDiagnosa.rekomendasi.push("Lingkungan Positif: Ciptakan lingkungan di rumah yang mendukung pola makan sehat dan gaya hidup aktif bagi seluruh keluarga.");
            hasilDiagnosa.rekomendasi.push("Kolaborasi dengan Tim Medis: Bekerjasama dengan dokter, ahli gizi, dan spesialis lainnya untuk memonitor dan menjaga kesehatan anak.");
            hasilDiagnosa.rekomendasi.push("Kesadaran Kontinu: Ingatkan anak agar tetap sadar akan pentingnya makanan sehat dan gaya hidup aktif.");
        } else if (diagnosisStunting === "Tidak Stunting" && diagnosisGizi === "Berisiko Gizi Lebih") {
            hasilDiagnosa.informasi = "Anak tidak mengalami stunting namun berisiko mengalami gizi lebih. Tinggi badan dan berat badan anak normal untuk usianya namun berat badan berisiko melebihi batas normal.";
            hasilDiagnosa.rekomendasi.push("Konsultasikan dengan Dokter: Pertama-tama, konsultasilah dengan dokter atau ahli gizi untuk mendapatkan evaluasi yang akurat tentang kondisi anak dan rekomendasi penanganan yang tepat.");
            hasilDiagnosa.rekomendasi.push("Rencanakan Pola Makan Terkendali: Bekerjasama dengan dokter atau ahli gizi, buatlah rencana pola makan yang mengatur asupan kalori dan nutrisi anak sesuai dengan kebutuhan pertumbuhan dan aktivitas.");
            hasilDiagnosa.rekomendasi.push("Pilih Makanan Nutritif: Prioritaskan makanan yang kaya protein berkualitas, serat, vitamin, dan mineral. Hindari makanan tinggi lemak jenuh dan gula tambahan.");
            hasilDiagnosa.rekomendasi.push("Kontrol Porsi: Pastikan anak mendapatkan porsi makan yang sesuai dengan kebutuhan energi dan aktivitasnya, untuk mencegah konsumsi kalori berlebihan.");
            hasilDiagnosa.rekomendasi.push("Buah dan Sayuran: Sertakan berbagai jenis buah dan sayuran dalam makanan anak untuk memenuhi kebutuhan vitamin dan serat.");
            hasilDiagnosa.rekomendasi.push("Aktivitas Fisik Teratur: Anjurkan anak untuk berpartisipasi dalam aktivitas fisik yang sesuai dengan usia, membantu menjaga kesehatan dan kebugaran.");
            hasilDiagnosa.rekomendasi.push("Pantau Pertumbuhan: Rutin pantau tinggi dan berat badan anak untuk memastikan perkembangannya sesuai dengan usia.");
            hasilDiagnosa.rekomendasi.push("Dukungan Psikologis: Bantu anak mengembangkan hubungan yang sehat dengan makanan dan tubuhnya, sambil mendukungnya secara emosional.");
            hasilDiagnosa.rekomendasi.push("Edukasi Gizi: Ajarkan anak tentang pentingnya gizi seimbang dan menjaga pola makan yang baik.");
            hasilDiagnosa.rekomendasi.push("Batasi Minuman Tinggi Gula: Hindari minuman beralkohol, minuman bersoda, dan minuman tinggi gula lainnya.");
            hasilDiagnosa.rekomendasi.push("Lingkungan Sehat: Ciptakan lingkungan di rumah yang mendukung pola makan sehat dan gaya hidup aktif bagi seluruh keluarga.");
            hasilDiagnosa.rekomendasi.push("Kolaborasi dengan Tim Medis: Bekerjasama dengan dokter, ahli gizi, dan spesialis lainnya untuk memonitor dan mengatur penanganan anak.");
            hasilDiagnosa.rekomendasi.push("Kesabaran dan Konsistensi: Ingat bahwa perubahan memerlukan waktu. Pertahankan langkah-langkah ini dengan konsisten.");
        } else if (diagnosisStunting === "Tidak Stunting" && diagnosisGizi === "Gizi Lebih") {
            hasilDiagnosa.informasi = "Anak tidak mengalami stunting namun status gizi lebih. Tinggi badan dan berat badan anak normal untuk usianya namun berat badan melebihi batas normal.";
            hasilDiagnosa.rekomendasi.push("Konsultasikan dengan Dokter: Pertama-tama, konsultasilah dengan dokter atau ahli gizi untuk mendapatkan evaluasi yang akurat tentang kondisi anak dan rekomendasi penanganan yang tepat.");
            hasilDiagnosa.rekomendasi.push("Rencanakan Pola Makan Terkendali: Bekerjasama dengan dokter atau ahli gizi, buatlah rencana pola makan yang mengatur asupan kalori dan nutrisi anak sesuai dengan kebutuhan pertumbuhan dan aktivitas.");
            hasilDiagnosa.rekomendasi.push("Kontrol Porsi: Pastikan anak mendapatkan porsi makan yang sesuai dengan kebutuhan energi dan aktivitasnya, untuk mencegah konsumsi kalori berlebihan.");
            hasilDiagnosa.rekomendasi.push("Pilih Makanan Berkualitas: Prioritaskan makanan yang kaya nutrisi seperti protein berkualitas, serat, vitamin, dan mineral. Hindari makanan tinggi lemak jenuh dan gula tambahan.");
            hasilDiagnosa.rekomendasi.push("Buah dan Sayuran: Sertakan berbagai jenis buah dan sayuran dalam makanan anak untuk memenuhi kebutuhan vitamin dan serat.");
            hasilDiagnosa.rekomendasi.push("Batasi Makanan Tinggi Gula: Hindari makanan atau minuman yang mengandung gula tambahan, seperti permen, minuman bersoda, dan makanan cepat saji.");
            hasilDiagnosa.rekomendasi.push("Aktivitas Fisik Teratur: Anjurkan anak untuk berpartisipasi dalam aktivitas fisik yang sesuai dengan usia, membantu mengontrol berat badan dan menjaga kesehatan.");
            hasilDiagnosa.rekomendasi.push("Pantau Pertumbuhan: Rutin pantau tinggi dan berat badan anak untuk memastikan perkembangannya sesuai dengan usia.");
            hasilDiagnosa.rekomendasi.push("Dukungan Psikologis: Bantu anak mengembangkan hubungan yang sehat dengan makanan dan tubuhnya, sambil mendukungnya secara emosional.");
            hasilDiagnosa.rekomendasi.push("Edukasi Gizi: Ajarkan anak tentang pentingnya gizi seimbang dan bagaimana memilih makanan yang baik untuk kesehatan mereka.");
            hasilDiagnosa.rekomendasi.push("Lingkungan Sehat: Ciptakan lingkungan di rumah yang mendukung pola makan sehat dan gaya hidup aktif bagi seluruh keluarga.");
            hasilDiagnosa.rekomendasi.push("Kolaborasi dengan Tim Medis: Bekerjasama dengan dokter, ahli gizi, dan spesialis lainnya untuk memonitor dan mengatur penanganan anak.");
            hasilDiagnosa.rekomendasi.push("Kesabaran dan Konsistensi: Ingat bahwa perubahan memerlukan waktu. Pertahankan langkah-langkah ini dengan konsisten.");
        } else if (diagnosisStunting === "Tidak Stunting" && diagnosisGizi === "Obesitas") {
            hasilDiagnosa.informasi = "Anak tidak mengalami stunting namun mengalami obesitas. Tinggi badan dan berat badan anak normal untuk usianya namun berat badan melebihi batas normal.";
            hasilDiagnosa.rekomendasi.push("Konsultasikan dengan Dokter: Pertama-tama, konsultasilah dengan dokter atau ahli gizi untuk mendapatkan evaluasi yang akurat tentang kondisi anak dan rekomendasi penanganan yang tepat.");
            hasilDiagnosa.rekomendasi.push("Rencanakan Pola Makan Seimbang: Bekerjasama dengan dokter atau ahli gizi, buat rencana pola makan yang mengontrol asupan kalori dan nutrisi anak sesuai dengan kebutuhan pertumbuhan dan aktivitas.");
            hasilDiagnosa.rekomendasi.push("Pilih Makanan Berkualitas: Prioritaskan makanan yang kaya nutrisi seperti protein berkualitas, serat, vitamin, dan mineral. Hindari makanan tinggi lemak jenuh dan gula tambahan.");
            hasilDiagnosa.rekomendasi.push("Kontrol Porsi: Pastikan anak mendapatkan porsi makan yang sesuai dengan kebutuhan energi dan aktivitasnya, untuk mencegah konsumsi kalori berlebihan.");
            hasilDiagnosa.rekomendasi.push("Batasi Makanan Tinggi Gula dan Lemak: Hindari makanan atau minuman yang mengandung gula tambahan, minyak goreng berlebihan, dan makanan cepat saji.");
            hasilDiagnosa.rekomendasi.push("Buah dan Sayuran: Sertakan berbagai jenis buah dan sayuran dalam makanan anak untuk memenuhi kebutuhan vitamin dan serat.");
            hasilDiagnosa.rekomendasi.push("Minuman Sehat: Pastikan anak mendapatkan cairan yang cukup dengan memberikan air putih atau minuman rendah gula.");
            hasilDiagnosa.rekomendasi.push("Aktivitas Fisik Teratur: Anjurkan anak untuk berpartisipasi dalam aktivitas fisik yang sesuai dengan usia, membantu mengontrol berat badan dan menjaga kesehatan.");
            hasilDiagnosa.rekomendasi.push("Pantau Pertumbuhan: Rutin pantau tinggi dan berat badan anak untuk memastikan perkembangannya sesuai dengan usia.");
            hasilDiagnosa.rekomendasi.push("Dukungan Psikologis: Bantu anak mengembangkan hubungan yang sehat dengan makanan dan tubuhnya, sambil mendukungnya secara emosional.");
            hasilDiagnosa.rekomendasi.push("Edukasi Gizi: Ajarkan anak tentang pentingnya gizi seimbang dan bagaimana memilih makanan yang baik untuk kesehatan mereka.");
            hasilDiagnosa.rekomendasi.push("Lingkungan Sehat: Ciptakan lingkungan di rumah yang mendukung pola makan sehat dan gaya hidup aktif bagi seluruh keluarga.");
            hasilDiagnosa.rekomendasi.push("Kolaborasi dengan Tim Medis: Bekerjasama dengan dokter, ahli gizi, dan spesialis lainnya untuk memonitor dan mengatur penanganan anak.");
            hasilDiagnosa.rekomendasi.push("Kesabaran dan Konsistensi: Ingat bahwa perubahan memerlukan waktu. Pertahankan langkah-langkah ini dengan konsisten.");
        }
 
        return hasilDiagnosa;
    }
</script>

<script>
    const form = document.getElementById('form-diagnosis');
    const namaBalitaElement = document.getElementById('nama-balita');
    const jenisKelaminElement = document.getElementById('jenis-kelamin');
    const usiaElement = document.getElementById('usia');
    const beratBadanElement = document.getElementById('berat-badan');
    const tinggiBadanElement = document.getElementById('tinggi-badan');
    const imtElement = document.getElementById('imt');

    const tingkatStuntingElement = document.getElementById('tingkat-stunting');
    const giziStatusElement = document.getElementById('gizi-status');
    const imtOutputElement = document.getElementById('imt-output');
    const tinggiBadanOutputElement = document.getElementById('tinggi-badan-output');

    const informasiDiagnosisElement = document.getElementById('informasi-diagnosis');
    const rekomendasiDiagnosisElement = document.getElementById('rekomendasi-diagnosis');

    const stuntingBeratRangeElement = document.getElementById('stuntingBeratRange');
    const stuntingRinganRangeElement = document.getElementById('stuntingRinganRange');
    const tidakStuntingRangeElement = document.getElementById('tidakStuntingRange');

    const giziBurukRangeElement = document.getElementById('giziBurukRange');
    const giziKurangRangeElement = document.getElementById('giziKurangRange');
    const giziBaikRangeElement = document.getElementById('giziBaikRange');
    const giziBerisikoLebihRangeElement = document.getElementById('giziBerisikoLebihRange');
    const giziLebihRangeElement = document.getElementById('giziLebihRange');
    const giziObesitasRangeElement = document.getElementById('giziObesitasRange');

    const namaBalitaElementSubmit = document.getElementById('nama-balita-submit');
    const jenisKelaminElementSubmit = document.getElementById('jenis-kelamin-submit');
    const usiaElementSubmit = document.getElementById('usia-submit');
    const beratBadanElementSubmit = document.getElementById('berat-badan-submit');
    const tinggiBadanElementSubmit = document.getElementById('tinggi-badan-submit');
    const imtElementSubmit = document.getElementById('imt-submit');
    const tingkatStuntingElementSubmit = document.getElementById('tingkat-stunting-submit');
    const giziStatusElementSubmit = document.getElementById('gizi-status-submit');

    const resultDiagnosisElement = document.getElementById('result-diagnosis');

    form.addEventListener('submit', function (event) {
        event.preventDefault();

        const namaBalita = document.getElementById('nama_balita').value;
        const jenisKelamin = document.getElementById('jenis_kelamin').value;
        const usiaBalita = parseInt(document.getElementById('usia_balita').value);
        const beratBadan = parseFloat(document.getElementById('bb_balita').value);
        const tinggiBadan = parseFloat(document.getElementById('tb_balita').value);

        // Validasi setiap input
        if (!namaBalita || jenisKelamin === 'Pilih jenis kelamin' || (usiaBalita !== 0 && !usiaBalita) || !beratBadan || !tinggiBadan) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Semua inputan diagnosis wajib diisi!'
            });
            return;
        }

        // Tampilkan SweetAlert loading
        Swal.fire({
            title: 'Sedang mendiagnosis...',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading()
                const b = Swal.getHtmlContainer().querySelector('b')
                timerInterval = setInterval(() => {
                    b.textContent = Swal.getTimerLeft()
                }, 100)
            },
            onBeforeOpen: () => {
                Swal.showLoading();
            }
        });

        // Mengatur delay sebelum menampilkan hasil
        setTimeout(function () {
            // Update nilai input pada tabel hasil
            namaBalitaElement.textContent = namaBalita;
            namaBalitaElementSubmit.value = namaBalita;

            const jenisKelaminText = (jenisKelamin === "P") ? "Perempuan" : (jenisKelamin === "L") ? "Laki-laki" : "-";
            jenisKelaminElement.textContent = jenisKelaminText;
            jenisKelaminElementSubmit.value = jenisKelaminText;

            usiaElement.textContent = usiaBalita + " bulan";
            usiaElementSubmit.value = usiaBalita;
            beratBadanElement.textContent = beratBadan + " kg";
            beratBadanElementSubmit.value = beratBadan;
            tinggiBadanElement.textContent = tinggiBadan + " cm";
            tinggiBadanElementSubmit.value = tinggiBadan;

            let resultImt = beratBadan / (tinggiBadan * tinggiBadan) * 10000;
            let roundedImt = Math.round(resultImt * 100) / 100;
            imtElement.textContent = roundedImt;
            imtElementSubmit.value = roundedImt;

            imtOutputElement.textContent = roundedImt;
            tinggiBadanOutputElement.textContent = tinggiBadan + " cm";

            // Melakukan perhitungan dan mendapatkan hasil diagnosa
            const hasilDiagnosa = diagnoseGiziStunting(jenisKelamin, usiaBalita, beratBadan, tinggiBadan);

            // Update hasil diagnosa pada elemen-elemen hasil
            tingkatStuntingElement.textContent = hasilDiagnosa.stunting;
            tingkatStuntingElementSubmit.value = hasilDiagnosa.stunting;
            giziStatusElement.textContent = hasilDiagnosa.gizi;
            giziStatusElementSubmit.value = hasilDiagnosa.gizi;
            informasiDiagnosisElement.textContent = hasilDiagnosa.informasi;

            // Membuat elemen <p> untuk setiap rekomendasi
            var semuaRekomendasi = hasilDiagnosa.rekomendasi.map(function (rekomendasi, index) {
                return "<p>" + (index + 1) + ". " + rekomendasi + "</p>";
            }).join(""); // Menggabungkan elemen-elemen <p> menjadi satu string

            // Menampilkan rekomendasi dalam elemen HTML
            rekomendasiDiagnosisElement.innerHTML = semuaRekomendasi;

            // Menghapus kelas asli pada elemen "tingkat-stunting"
            tingkatStuntingElement.classList.remove('text-danger', 'text-warning', 'text-success');

            // Menghapus kelas asli pada elemen "gizi-status"
            giziStatusElement.classList.remove('text-danger', 'text-warning', 'text-success', 'text-primary', 'text-info');

            // Mendapatkan nilai tingkat stunting dan gizi status dari hasil diagnosa
            const tingkatStunting = hasilDiagnosa.stunting;
            const giziStatus = hasilDiagnosa.gizi;

            // Mengatur kelas teks pada elemen "tingkat-stunting" berdasarkan nilai tingkat stunting
            if (tingkatStunting === 'Stunting Berat') {
                tingkatStuntingElement.classList.add('text-danger');
            } else if (tingkatStunting === 'Stunting Ringan') {
                tingkatStuntingElement.classList.add('text-warning');
            } else if (tingkatStunting === 'Tidak Stunting') {
                tingkatStuntingElement.classList.add('text-success');
            }

            // Mengatur kelas teks pada elemen "gizi-status" berdasarkan nilai gizi status
            if (giziStatus === 'Gizi Buruk') {
                giziStatusElement.classList.add('text-danger');
            } else if (giziStatus === 'Gizi Kurang') {
                giziStatusElement.classList.add('text-warning');
            } else if (giziStatus === 'Gizi Baik') {
                giziStatusElement.classList.add('text-success');
            } else if (giziStatus === 'Berisiko Gizi Lebih') {
                giziStatusElement.classList.add('text-info');
            } else if (giziStatus === 'Gizi Lebih') {
                giziStatusElement.classList.add('text-primary');
            } else if (giziStatus === 'Obesitas') {
                giziStatusElement.classList.add('text-danger');
            }

            // Menampilkan data pada elemen HTML
            const stuntingBeratRange = "0 - " + (hasilDiagnosa.min3SDStunting - 0.1).toFixed(1) + " cm";
            const stuntingRinganRange = hasilDiagnosa.min3SDStunting + " - " + (hasilDiagnosa.min2SDStunting - 0.1).toFixed(1) + " cm";
            const tidakStuntingRange = ">= " + hasilDiagnosa.min2SDStunting + " cm"

            const giziBurukRange = "0 - " + (hasilDiagnosa.min3SDGizi - 0.1).toFixed(1);
            const giziKurangRange = hasilDiagnosa.min3SDGizi + " - " + (hasilDiagnosa.min2SDGizi - 0.1).toFixed(1);
            const giziBaikRange = hasilDiagnosa.min2SDGizi + " - " + hasilDiagnosa.plus1SDGizi;
            const giziBerisikoLebihRange = (hasilDiagnosa.plus1SDGizi + 0.1).toFixed(1) + " - " + hasilDiagnosa.plus2SDGizi;
            const giziLebihRange = (hasilDiagnosa.plus2SDGizi + 0.1).toFixed(1) + " - " + hasilDiagnosa.plus3SDGizi;
            const giziObesitasRange = ">= " + (hasilDiagnosa.plus3SDGizi + 0.1).toFixed(1);

            // Menampilkan data pada elemen HTML yang sesuai
            stuntingBeratRangeElement.textContent = stuntingBeratRange;
            stuntingRinganRangeElement.textContent = stuntingRinganRange;
            tidakStuntingRangeElement.textContent = tidakStuntingRange;

            giziBurukRangeElement.textContent = giziBurukRange;
            giziKurangRangeElement.textContent = giziKurangRange;
            giziBaikRangeElement.textContent = giziBaikRange;
            giziBerisikoLebihRangeElement.textContent = giziBerisikoLebihRange;
            giziLebihRangeElement.textContent = giziLebihRange;
            giziObesitasRangeElement.textContent = giziObesitasRange;

            // Tutup SweetAlert loading
            Swal.close();

            // Delay scroll until after the loading modal is closed
            setTimeout(function () {
                resultDiagnosisElement.scrollIntoView({ behavior: 'smooth' });
            }, 500);

        }, 1000); // Delay 2 detik (Anda dapat menyesuaikan waktu delay sesuai kebutuhan)

    });

    function resetFormDiagnosis() {
        form.reset();
        namaBalitaElement.textContent = "-";
        jenisKelaminElement.textContent = "-";
        usiaElement.textContent = "-";
        beratBadanElement.textContent = "-";
        tinggiBadanElement.textContent = "-";
        imtElement.textContent = "-";

        tingkatStuntingElement.textContent = "Tingkat Stunting";
        giziStatusElement.textContent = "Status Gizi";
        imtOutputElement.textContent = "";
        tinggiBadanOutputElement.textContent = "";
        informasiDiagnosisElement.textContent = "Informasi diagnosis akan ditampilkan ketika Anda telah melakukan diagnosis.";
        rekomendasiDiagnosisElement.textContent = "Rekomendasi diagnosis akan ditampilkan ketika Anda telah melakukan diagnosis.";

        stuntingBeratRangeElement.textContent = "-";
        stuntingRinganRangeElement.textContent = "-";
        tidakStuntingRangeElement.textContent = "-";

        giziBurukRangeElement.textContent = "-";
        giziKurangRangeElement.textContent = "-";
        giziBaikRangeElement.textContent = "-";
        giziLebihRangeElement.textContent = "-";
    }

</script>

</html>