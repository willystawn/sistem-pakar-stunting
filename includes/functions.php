<?php

$current_page = basename($_SERVER['REQUEST_URI']);

function isActivePage($currentPage, $pageUrl)
{
    return ($currentPage === $pageUrl) ? 'active' : '';
}

function getPageTitle($currentPage)
{
    $currentPage = explode('?', $currentPage)[0];
    switch ($currentPage) {
        case 'login':
            return '- Login';
        case 'forgot-password':
            return '- Lupa Kata Sandi';
        case 'change-password':
            return '- Ganti Kata Sandi';
        case 'register':
            return '- Daftar Akun Baru';
        case 'dashboard':
            return '- Dashboard';
        case 'diagnosis':
            return '- Diagnosis';
        case 'data-diagnosis':
            return '- Data Diagnosis';
        case 'profil':
            return '- Profil';
        case 'panduan':
            return '- Panduan';
        case 'data-pengguna':
            return '- Data Pengguna';
        default:
            return '';
    }
}

function getNamaPengguna($koneksi)
{
    if (isset($_SESSION['email'])) {
        // Lakukan query ke database untuk mendapatkan nama pengguna berdasarkan email
        $email = $_SESSION['email'];
        $query = "SELECT nama FROM tbl_pengguna WHERE email = '$email' OR no_telepon='$email'";
        $result = mysqli_query($koneksi, $query);
        $row = mysqli_fetch_assoc($result);
        $namaPengguna = $row['nama'];

        return isset($namaPengguna) ? $namaPengguna : 'Stun Sensei';
    } else {
        return 'Stun Sensei';
    }
}

function getProfileImage($koneksi)
{
    // Periksa jika sesi email tidak ada
    if (!isset($_SESSION['email'])) {
        return 'img/profile-man.svg';
    }

    // Lakukan query untuk mendapatkan jenis kelamin berdasarkan email
    $email = $_SESSION['email'];
    $query = "SELECT jenis_kelamin FROM tbl_pengguna WHERE email = '$email' OR no_telepon='$email'";
    $result = mysqli_query($koneksi, $query);
    $row = mysqli_fetch_assoc($result);

    // Periksa jika data pengguna ditemukan
    if ($row) {
        $jenisKelamin = $row['jenis_kelamin'];
        if ($jenisKelamin === 'P') {
            return 'img/profile-woman.svg';
        } else {
            return 'img/profile-man.svg';
        }
    } else {
        return 'img/profile-man.svg';
    }
}

function getDataProfile()
{
    // Koneksi ke database
    include 'includes/koneksi.php';

    $email = $_SESSION['email'];
    $query = "SELECT * FROM tbl_pengguna WHERE email = '$email' OR no_telepon='$email'";
    $result = mysqli_query($koneksi, $query);
    $row = mysqli_fetch_assoc($result);
    $nama = $row['nama'];
    $jenis_kelamin = $row['jenis_kelamin'];
    $email = $row['email'];
    $no_telepon = $row['no_telepon'];

    mysqli_close($koneksi);

    if ($jenis_kelamin === 'L') {
        $jenis_kelamin = 'Laki-laki';
    } elseif ($jenis_kelamin === 'P') {
        $jenis_kelamin = 'Perempuan';
    }

    return array('nama' => $nama, 'jenis_kelamin' => $jenis_kelamin, 'email' => $email, 'no_telepon' => $no_telepon);
}

function simpanDataProfil($nama, $jenis_kelamin, $email, $no_telepon)
{
    // Koneksi ke database
    include 'includes/koneksi.php';

    $emailSession = $_SESSION['email'];

    // Melakukan sanitasi pada data yang diinput
    $nama = mysqli_real_escape_string($koneksi, $nama);
    $jenis_kelamin = mysqli_real_escape_string($koneksi, $jenis_kelamin);
    $email = mysqli_real_escape_string($koneksi, $email);
    $no_telepon = mysqli_real_escape_string($koneksi, $no_telepon);

    // Melakukan penyimpanan data ke database dengan prepared statement
    $query = "UPDATE tbl_pengguna SET nama=?, jenis_kelamin=?, email=?, no_telepon=? WHERE email=? OR no_telepon=?";
    $stmt = mysqli_prepare($koneksi, $query);

    // Bind parameter ke prepared statement
    mysqli_stmt_bind_param($stmt, "ssssss", $nama, $jenis_kelamin, $email, $no_telepon, $emailSession, $emailSession);

    // Eksekusi prepared statement
    $result = mysqli_stmt_execute($stmt);

    // Periksa apakah data berhasil disimpan
    if ($result) {
        // Ubah nilai sesi email
        $_SESSION['email'] = $email;
    }

    mysqli_stmt_close($stmt);
    mysqli_close($koneksi);

    return $result;
}



function login()
{
    session_start();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        include 'includes/koneksi.php';

        $input = mysqli_real_escape_string($koneksi, $_POST['email']);
        $kata_sandi = mysqli_real_escape_string($koneksi, $_POST['password']);

        // Mengenkripsi kata sandi dengan SHA-256
        $hashedPassword = hash('sha256', $kata_sandi);

        // Memeriksa apakah input merupakan email atau nomor telepon
        if (filter_var($input, FILTER_VALIDATE_EMAIL)) {
            // Input merupakan email
            // Menggunakan parameter binding dengan prepared statements
            $query = "SELECT * FROM tbl_pengguna WHERE email = ? AND BINARY kata_sandi = ?";
            $stmt = mysqli_prepare($koneksi, $query);
            mysqli_stmt_bind_param($stmt, "ss", $input, $hashedPassword);

            // Mengambil session email
            $sessionKey = 'email';
            $sessionStatusKey = 'status_pengguna';
        } else {
            // Input merupakan nomor telepon
            // Sesuaikan kolom database yang sesuai dengan nomor telepon
            $query = "SELECT * FROM tbl_pengguna WHERE no_telepon = ? AND BINARY kata_sandi = ?";
            $stmt = mysqli_prepare($koneksi, $query);
            mysqli_stmt_bind_param($stmt, "ss", $input, $hashedPassword);

            // Mengambil session no_telepon
            $sessionKey = 'no_telepon';
            $sessionStatusKey = 'status_pengguna';
        }

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['email'] = htmlspecialchars($row[$sessionKey], ENT_QUOTES, 'UTF-8');
            $_SESSION['status_pengguna'] = htmlspecialchars($row[$sessionStatusKey], ENT_QUOTES, 'UTF-8');

            header('Location: dashboard');
            exit();
        } else {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Email/No. Telp atau kata sandi salah!'
                    }).then(function() {                        
                        window.location.href = window.location.href;
                    });;
                });
            </script>";
        }

        mysqli_stmt_close($stmt);
        mysqli_close($koneksi);
    }
}


// Fungsi untuk mengambil data email dan nomor telepon dari database
function getDataKontak()
{
    // Koneksi ke database
    include 'includes/koneksi.php';

    // Mendapatkan data email dan nomor telepon dari database
    $query = "SELECT email, no_telepon FROM tbl_kontak";
    $result = mysqli_query($koneksi, $query);
    $row = mysqli_fetch_assoc($result);
    $email = $row['email'];
    $no_telepon = $row['no_telepon'];

    mysqli_close($koneksi);

    return array('email' => $email, 'no_telepon' => $no_telepon);
}

// Fungsi untuk menyimpan data ke database
function simpanDataKontak($email, $no_telepon)
{
    // Koneksi ke database
    include 'includes/koneksi.php';

    // Sanitasi input email dan no_telepon
    $email = mysqli_real_escape_string($koneksi, $email);
    $no_telepon = mysqli_real_escape_string($koneksi, $no_telepon);

    // Melakukan penyimpanan data ke database dengan parameter binding
    $query = "UPDATE tbl_kontak SET email=?, no_telepon=?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "ss", $email, $no_telepon);
    $result = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
    mysqli_close($koneksi);

    return $result;
}


function generateFAQ($faq)
{
    include 'includes/koneksi.php';

    // Mendapatkan data nomor telepon dan alamat email dari database
    $query = "SELECT no_telepon, email FROM tbl_kontak WHERE id_kontak = 1";
    $result = mysqli_query($koneksi, $query);
    $row = mysqli_fetch_assoc($result);
    $no_telepon = $row['no_telepon'];
    $email = $row['email'];

    mysqli_close($koneksi);

    $output = '
        <div id="accordion" class="mb-4">
            <div class="card shadow p-4 p-lg-5">
                <div class="d-flex justify-content-center align-items-center mb-4">
                    <div class="text-center">
                        <h3>Ada Pertanyaan? Baca disini.</h3>
                        <p>Tidak menemukan jawaban? Hubungi kami melalui WhatsApp <span class="font-weight-bold text-primary" id="no_wa">' . $no_telepon . '</span> atau melalui email <span class="font-weight-bold text-primary" id="email_adr">' . $email . '</span></p>                        
                    </div>
                </div>
    ';
    foreach ($faq as $key => $value) {
        $output .= '
            <div class="card shadow mb-3">
                <div class="pointer card-header py-3 bg-primary text-white" id="heading' . $key . '" data-toggle="collapse" data-target="#collapse' . $key . '" aria-expanded="false" aria-controls="collapse' . $key . '">
                    <h5 class="m-0">' . $value['title'] . '</h5>
                </div>

                <div id="collapse' . $key . '" class="collapse' . ($key == 0 ? ' show' : '') . '" aria-labelledby="heading' . $key . '" data-parent="#accordion">
                    <div class="card-body">' . $value['content'] . '</div>
                </div>
            </div>
        ';
    }

    $output .= '
            </div>
        </div>
    ';

    return $output;
}

$faq = [
    [
        'title' => 'Bagaimana cara menggunakan sistem pakar diagnosis stunting ini?',
        'content' => 'Anda dapat menggunakan sistem pakar diagnosis stunting ini dengan mengakses halaman website kami dan mengisi form yang tersedia dengan data balita yang akan didiagnosis. Setelah itu, sistem akan memberikan hasil diagnosis beserta rekomendasi tindakan yang harus dilakukan.'
    ],
    [
        'title' => 'Apakah sistem pakar ini dapat digunakan untuk mendiagnosis kesehatan balita selain stunting?',
        'content' => 'Tidak, sistem pakar ini hanya digunakan untuk mendiagnosis stunting pada balita.'
    ],
    [
        'title' => 'Apakah sistem ini gratis digunakan?',
        'content' => 'Ya, sistem ini dapat digunakan secara gratis dan tanpa biaya apapun.'
    ],
    [
        'title' => 'Bagaimana cara mendapatkan bantuan jika saya mengalami kesulitan dalam menggunakan sistem ini?',
        'content' => 'Anda dapat menghubungi kami melalui email yang tersedia di halaman panduan untuk mendapatkan bantuan jika mengalami kesulitan dalam menggunakan sistem ini.'
    ],
    [
        'title' => 'Apakah data yang saya inputkan ke dalam sistem aman?',
        'content' => 'Ya, data yang Anda inputkan ke dalam sistem akan dijaga kerahasiaannya dan tidak akan disebarkan ke pihak lain tanpa persetujuan Anda.'
    ],
    [
        'title' => 'Apakah sistem ini terintegrasi dengan sistem kesehatan lain?',
        'content' => 'Tidak, saat ini sistem ini belum terintegrasi dengan sistem kesehatan lain. Namun kami berupaya untuk terus mengembangkan sistem ini agar dapat lebih terintegrasi dan memberikan manfaat yang lebih besar bagi masyarakat.'
    ]
];


function generatePageTitle($pageTitle)
{
    return '
        <div class="user-select-none d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h4 mb-0 text-gray-800">' . $pageTitle . '</h1>
        </div>
    ';
}
function generateSubPageTitle($pageTitle)
{
    return '
        <div class="user-select-none d-sm-flex align-items-center justify-content-between my-4">
            <h1 class="h5 mb-0 text-gray-800">' . $pageTitle . '</h1>
        </div>
    ';
}

function checkProfileCompletion()
{
    // Koneksi ke database
    include 'includes/koneksi.php';

    // Ambil email pengguna yang sedang login dari session
    $email = $_SESSION['email'];

    // Query untuk mendapatkan data pengguna yang belum lengkap
    $query = "SELECT COUNT(*) as count FROM tbl_pengguna WHERE (email = '$email' OR no_telepon = '$email') AND (jenis_kelamin IS NULL OR email IS NULL OR no_telepon IS NULL)";
    $result = mysqli_query($koneksi, $query);
    $row = mysqli_fetch_assoc($result);
    $count = $row['count'];

    // Cek apakah data pengguna belum lengkap
    if ($count > 0) {
        // Tampilkan alert component
        echo '<div class="user-select-none alert alert-warning" role="alert">
        Profil Anda belum lengkap, silahkan lengkapi profil Anda. Klik
        <a href="profil" class="text-decoration-underline text-link">Pengaturan Profil</a>.
        </div>';
    }
}

function generateCard($title, $value, $icon, $color)
{
    return "
    <div class='col-xl-4 col-md-6 mb-3'>
        <div class='card border-left-$color shadow h-100 py-1 px-3'>
            <div class='card-body'>
                <div class='row no-gutters align-items-center'>
                    <div class='col mr-2'>
                        <div class='font-weight-bold text-$color mb-3'>
                            $title
                        </div>
                        <div class='h5 mb-0 font-weight-bold text-gray-800'>
                            <span>$value</span>
                        </div>
                    </div>
                    <div class='col-auto'>
                        <i class='$icon fa-3x text-gray-300'></i>
                    </div>
                </div>
            </div>
        </div>
    </div>";
}

function getDataBalita()
{

    // Koneksi ke database
    include 'includes/koneksi.php';

    // Ambil email pengguna yang sedang login dari session
    $email = $_SESSION['email'];

    // Mengambil data dari database
    $query = "SELECT COUNT(*) as jumlah_balita 
    FROM tbl_diagnosis d 
    JOIN tbl_pengguna p ON d.id_pengguna = p.id_pengguna 
    WHERE p.email = '$email' OR p.no_telepon = '$email'
    ";
    $result = mysqli_query($koneksi, $query);
    $row = mysqli_fetch_assoc($result);
    $jumlah_balita = $row['jumlah_balita'];

    $query = "SELECT COUNT(*) as jumlah_stunting
    FROM tbl_diagnosis d
    JOIN tbl_pengguna p ON d.id_pengguna = p.id_pengguna
    WHERE (p.email = '$email' OR p.no_telepon = '$email')
    AND d.hasil_diagnosis IN ('Stunting Ringan', 'Stunting Berat')
    ";
    $result = mysqli_query($koneksi, $query);
    $row = mysqli_fetch_assoc($result);
    $jumlah_stunting = $row['jumlah_stunting'];

    if ($jumlah_balita != 0) {
        $persentase_stunting = round($jumlah_stunting / $jumlah_balita * 100);
    } else {
        $persentase_stunting = 0;
    }

    return array($jumlah_balita, $jumlah_stunting, $persentase_stunting);
}

// Fungsi untuk mengambil data diagnosis berdasarkan email pengguna yang login saat ini
function getDiagnosisDataByEmail($email)
{
    // Koneksi ke database
    include 'includes/koneksi.php';

    // Ganti bagian ini dengan kode yang sesuai untuk menghubungkan dengan database dan melakukan kueri
    $query = "SELECT tbl_diagnosis.*, tbl_pengguna.email, tbl_pengguna.no_telepon
    FROM tbl_diagnosis
    INNER JOIN tbl_pengguna ON tbl_diagnosis.id_pengguna = tbl_pengguna.id_pengguna
    WHERE tbl_pengguna.email = '$email' OR tbl_pengguna.no_telepon = '$email'
    ORDER BY tbl_diagnosis.tanggal_diagnosis DESC;
    ";

    // Eksekusi kueri dan dapatkan hasilnya
    // Misalnya, menggunakan mysqli:
    $result = mysqli_query($koneksi, $query);

    // Inisialisasi array untuk menyimpan data diagnosis
    $diagnosis_data = [];

    // Iterasi melalui hasil query dan tambahkan data ke array
    while ($row = mysqli_fetch_assoc($result)) {
        $diagnosis_data[] = $row;
    }

    return $diagnosis_data;
}

function getUserData()
{
    // Koneksi ke database
    include 'includes/koneksi.php';

    $query = "SELECT *
    FROM tbl_pengguna
    WHERE status_pengguna <> 'admin'
    ORDER BY id_pengguna DESC;
    ";
    $result = mysqli_query($koneksi, $query);

    $user_data = [];

    // Iterasi melalui hasil query dan tambahkan data ke array
    while ($row = mysqli_fetch_assoc($result)) {
        $user_data[] = $row;
    }

    return $user_data;
}


// Fungsi untuk menghapus data berdasarkan email atau nomor telepon
function hapusDataPengguna($email, $no_telepon)
{
    // Koneksi ke database
    include 'includes/koneksi.php';

    // Escape string email dan nomor telepon untuk menghindari serangan SQL Injection
    $email = mysqli_real_escape_string($koneksi, $email);
    $no_telepon = mysqli_real_escape_string($koneksi, $no_telepon);

    // Tentukan kondisi query berdasarkan email dan nomor telepon
    $condition = "";
    if (!empty($email) && !empty($no_telepon)) {
        $condition = "email = '$email' AND no_telepon = '$no_telepon'";
    } elseif (!empty($email)) {
        $condition = "email = '$email'";
    } elseif (!empty($no_telepon)) {
        $condition = "no_telepon = '$no_telepon'";
    }

    // Query hapus data pada tabel tbl_diagnosis berdasarkan id_pengguna
    $diagnosisQuery = "DELETE FROM tbl_diagnosis WHERE id_pengguna IN (SELECT id_pengguna FROM tbl_pengguna WHERE $condition)";

    // Query hapus data pada tabel tbl_pengguna berdasarkan email atau nomor telepon
    $penggunaQuery = "DELETE FROM tbl_pengguna WHERE $condition";

    // Mulai transaksi
    mysqli_begin_transaction($koneksi);

    try {
        // Eksekusi query hapus data pada tabel tbl_diagnosis
        mysqli_query($koneksi, $diagnosisQuery);

        // Eksekusi query hapus data pada tabel tbl_pengguna
        mysqli_query($koneksi, $penggunaQuery);

        // Commit transaksi
        mysqli_commit($koneksi);

        echo "<script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })

        Toast.fire({
            icon: 'success',
            title: 'Data berhasil dihapus'
        }).then(function() {
            window.location.href = window.location.href;
        });
        </script>";
    } catch (Exception $e) {
        // Rollback transaksi jika terjadi kesalahan
        mysqli_rollback($koneksi);

        echo "<script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })

        Toast.fire({
            icon: 'error',
            title: 'Data gagal dihapus'
        }).then(function() {
            window.location.href = window.location.href;
        });
        </script>";
    }

    // Tutup koneksi database
    mysqli_close($koneksi);
}


// Fungsi untuk menghapus data berdasarkan email atau nomor telepon
function hapusDataDiagnosis($id_diagnosis)
{
    // Koneksi ke database
    include 'includes/koneksi.php';

    $id_diagnosis = mysqli_real_escape_string($koneksi, $id_diagnosis);
    $email = $_SESSION['email'];

    // Query untuk mendapatkan id_pengguna berdasarkan email pengguna yang sedang login
    $query_get_id_pengguna = "SELECT id_pengguna FROM tbl_pengguna WHERE email = '$email' OR no_telepon = '$email'";
    $result = mysqli_query($koneksi, $query_get_id_pengguna);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $id_pengguna = $row['id_pengguna'];

        // Query hapus data berdasarkan ID diagnosis dan ID pengguna yang sedang login
        $query = "DELETE FROM tbl_diagnosis WHERE id_diagnosis = '$id_diagnosis' AND id_pengguna = '$id_pengguna'";

        // Eksekusi query
        if (mysqli_query($koneksi, $query)) {
            echo "<script>
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })
          
            Toast.fire({
                icon: 'success',
                title: 'Data berhasil dihapus'
            }).then(function() {
                window.location.href = window.location.href;
            });            
            </script>";
        } else {
            echo "<script>
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })

            Toast.fire({
                icon: 'error',
                title: 'Data gagal dihapus'
            }).then(function() {
                window.location.href = window.location.href;
            });    
            </script>";
        }
    } else {
        // Tidak dapat menemukan id_pengguna berdasarkan email pengguna yang sedang login
        echo "<script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })

        Toast.fire({
            icon: 'warning',
            title: 'Anda tidak memiliki akses untuk menghapus data ini'
        }).then(function() {
            window.location.href = window.location.href;
        });    
        </script>";
    }

    // Tutup koneksi database
    mysqli_close($koneksi);
}


// Fungsi untuk memeriksa email terdaftar
function cekEmailTerdaftar($email)
{
    // Koneksi ke database
    include 'includes/koneksi.php';

    // Ambil email pengguna yang sedang login dari session
    $emailSession = $_SESSION['email'];

    $query = "SELECT COUNT(*) AS count FROM tbl_pengguna WHERE email = '$email' AND email != '$emailSession'";

    // Jika session email berupa nomor telepon
    if (strpos($emailSession, '@') === false) {
        $query .= " AND no_telepon != '$emailSession'";
    }

    $result = mysqli_query($koneksi, $query);
    $row = mysqli_fetch_assoc($result);

    // Jika email sudah terdaftar, return true
    if ($row['count'] > 0) {
        return true;
    }

    // Jika email belum terdaftar, return false
    return false;
}



// Fungsi untuk memeriksa nomor telepon terdaftar
function cekNoTeleponTerdaftar($no_telepon)
{
    // Koneksi ke database
    include 'includes/koneksi.php';

    // Ambil email pengguna yang sedang login dari session
    $emailSession = $_SESSION['email'];

    $query = "SELECT COUNT(*) AS count FROM tbl_pengguna WHERE no_telepon = '$no_telepon' AND email != '$emailSession'";

    // Jika session email berupa nomor telepon
    if (strpos($emailSession, '@') === false) {
        $query .= " AND no_telepon != '$emailSession'";
    }

    $result = mysqli_query($koneksi, $query);
    $row = mysqli_fetch_assoc($result);

    // Jika nomor telepon sudah terdaftar, return true
    if ($row['count'] > 0) {
        return true;
    }

    // Jika nomor telepon belum terdaftar, return false
    return false;
}

function formatTanggalIndonesia($tanggal)
{
    $bulanIndonesia = array(
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    );

    $tanggalObj = DateTime::createFromFormat('Y-m-d', $tanggal);
    $bulan = $bulanIndonesia[(int) $tanggalObj->format('n')];
    $tahun = $tanggalObj->format('Y');
    $tanggal = $tanggalObj->format('d');

    return $tanggal . ' ' . $bulan . ' ' . $tahun;
}


function tambahPenggunaBaru($nama, $email, $no_telepon, $kata_sandi)
{
    // Koneksi ke database
    include 'includes/koneksi.php';

    // Escape karakter khusus pada data input
    $nama = $koneksi->real_escape_string($nama);
    $email = $koneksi->real_escape_string($email);
    $no_telepon = $koneksi->real_escape_string($no_telepon);
    $kata_sandi = $koneksi->real_escape_string($kata_sandi);

    // Memastikan bahwa email atau nomor telepon unik
    $query_check = "SELECT * FROM tbl_pengguna WHERE email = '$email' OR no_telepon = '$no_telepon'";
    $result_check = $koneksi->query($query_check);

    if ($result_check->num_rows > 0) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Email/No. Telp sudah digunakan. Silakan gunakan Email atau No. Telp yang lain.'
                    }).then(function() {
                        window.location.href = window.location.href;
                    });    
                });
            </script>";
    } else {
        // Menambahkan pengguna baru ke tabel
        if (!empty($email)) {
            // Jika email diisi, nomor telepon dikosongkan
            $hashed_password = hash('sha256', $kata_sandi);
            $query_insert = "INSERT INTO tbl_pengguna (nama, status_pengguna, email, kata_sandi) VALUES ('$nama', 'user', '$email', '$hashed_password')";
        } elseif (!empty($no_telepon)) {
            // Jika nomor telepon diisi, email dikosongkan
            $hashed_password = hash('sha256', $kata_sandi);
            $query_insert = "INSERT INTO tbl_pengguna (nama, status_pengguna, no_telepon, kata_sandi) VALUES ('$nama', 'user', '$no_telepon', '$hashed_password')";
        } else {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Mohon isi Email atau No. Telp.'
                    }).then(function() {
                        window.location.href = window.location.href;
                    });    
                });
            </script>";
            exit;
        }

        if ($koneksi->query($query_insert) === TRUE) {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Pendaftaran Berhasil',
                        text: 'Silahkan login ke akun Anda!'
                    }).then(function() {
                        window.location.href = 'login';
                    });
                });
            </script>";
        } else {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Pendaftaran Gagal',
                        text: 'Silahkan hubungi admin untuk bantuan.'
                        footer: 'Email: stuntassisten@gmail.com'
                    }).then(function() {
                        window.location.href = window.location.href;
                    });
                });
            </script>";
        }
    }
}

function simpanPassword($kata_sandi)
{
    // Koneksi ke database
    include 'includes/koneksi.php';

    // Sanitasi input kata sandi
    $kata_sandi = mysqli_real_escape_string($koneksi, $kata_sandi);

    // Hash password dengan algoritma SHA256
    $hashedPassword = hash('sha256', $kata_sandi);

    $email = $_SESSION['email'];

    // Melakukan penyimpanan data ke database dengan prepared statement
    $query = "UPDATE tbl_pengguna SET kata_sandi = ? WHERE email = ? OR no_telepon = ?";
    $stmt = mysqli_prepare($koneksi, $query);

    // Bind parameter ke prepared statement
    mysqli_stmt_bind_param($stmt, "sss", $hashedPassword, $email, $email);

    // Eksekusi prepared statement
    $result = mysqli_stmt_execute($stmt);

    // Tutup prepared statement
    mysqli_stmt_close($stmt);

    // Tutup koneksi ke database
    mysqli_close($koneksi);

    return $result;
}


function getIdPenggunaFromEmail($email)
{
    // Koneksi ke database
    include 'includes/koneksi.php';

    $email = $_SESSION['email'];

    // Melakukan query untuk mendapatkan id_pengguna berdasarkan email
    $query = "SELECT id_pengguna FROM tbl_pengguna WHERE email = '$email' OR no_telepon = '$email'";
    $result = mysqli_query($koneksi, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        $id_pengguna = $row['id_pengguna'];
    } else {
        $id_pengguna = null;
    }

    // Tutup koneksi database
    mysqli_close($koneksi);

    return $id_pengguna;
}


function getChartHasilDiagnosis()
{
    // Koneksi ke database
    include 'includes/koneksi.php';

    $email = $_SESSION['email'];
    $id_pengguna = getIdPenggunaFromEmail($email);

    // Query untuk mendapatkan jumlah data berdasarkan hasil_diagnosis
    $query = "SELECT hasil_diagnosis, COUNT(*) AS jumlah FROM tbl_diagnosis WHERE id_pengguna = '$id_pengguna' GROUP BY hasil_diagnosis";
    $result = mysqli_query($koneksi, $query);

    // Array untuk menyimpan hasil_diagnosis dan jumlah
    $dataHasilDiagnosis = [];
    $dataJumlah = [];

    if ($result) {
        // Mengisi array dengan data dari database
        while ($row = mysqli_fetch_assoc($result)) {
            $dataHasilDiagnosis[] = $row['hasil_diagnosis'];
            $dataJumlah[] = $row['jumlah'];
        }
    } else {
        // Menampilkan pesan kesalahan jika kueri gagal
        echo "Error: " . mysqli_error($koneksi);
    }

    // Menutup koneksi database
    mysqli_close($koneksi);

    // Mengembalikan data sebagai array
    return array(
        'hasilDiagnosis' => $dataHasilDiagnosis,
        'jumlah' => $dataJumlah
    );
}

function getChartDataPerkembanganStunting()
{
    // Koneksi ke database
    include 'includes/koneksi.php';

    $email = $_SESSION['email'];
    $id_pengguna = getIdPenggunaFromEmail($email);

    // Mendapatkan tahun saat ini
    $tahunSaatIni = date('Y');

    // Mendapatkan 5 tahun terakhir
    $tahunTerakhir = $tahunSaatIni;
    $tahunAwal = $tahunTerakhir - 4;

    // Mendapatkan data jumlah perkembangan stunting per tahun
    $query = "SELECT YEAR(tanggal_diagnosis) AS tahun, COUNT(*) AS jumlah
              FROM tbl_diagnosis 
              WHERE id_pengguna = '$id_pengguna' 
              AND hasil_diagnosis IN ('Stunting Berat', 'Stunting Ringan') 
              AND YEAR(tanggal_diagnosis) BETWEEN $tahunAwal AND $tahunTerakhir 
              GROUP BY YEAR(tanggal_diagnosis) 
              ORDER BY YEAR(tanggal_diagnosis) ASC";

    $result = mysqli_query($koneksi, $query);

    // Menginisialisasi array untuk menyimpan data
    $dataTahun = range($tahunAwal, $tahunTerakhir); // Membuat array tahun dari tahunAwal hingga tahunTerakhir
    $dataJumlah = array_fill(0, count($dataTahun), 0); // Mengisi array jumlah dengan nilai 0

    // Mengisi array dengan data dari database
    while ($row = mysqli_fetch_assoc($result)) {
        $tahun = $row['tahun'];
        $jumlah = $row['jumlah'];

        // Menentukan indeks array berdasarkan tahun
        $index = array_search($tahun, $dataTahun);

        // Memperbarui nilai jumlah pada indeks yang sesuai
        $dataJumlah[$index] = $jumlah;
    }

    // Menutup koneksi database
    mysqli_close($koneksi);

    // Mengembalikan data sebagai array
    return array(
        'tahun' => $dataTahun,
        'jumlah' => $dataJumlah
    );
}

function getDiagnosisCetak()
{
    // Koneksi ke database
    include 'includes/koneksi.php';

    $email = $_SESSION['email'];
    $id_pengguna = getIdPenggunaFromEmail($email);

    // Query untuk mengambil data diagnosis
    $query = "SELECT * FROM tbl_diagnosis WHERE id_pengguna = '$id_pengguna'";
    $result = mysqli_query($koneksi, $query);

    $diagnosis_data = [];

    // Iterasi melalui hasil query dan tambahkan data ke array
    while ($row = mysqli_fetch_assoc($result)) {
        $diagnosis_data[] = $row;
    }

    return $diagnosis_data;
}

function simpanDiagnosis($namaBalita, $jenisKelamin, $usia, $beratBadan, $tinggiBadan, $imt, $tingkatStunting, $giziStatus)
{
    include "./includes/koneksi.php";

    // Sanitasi nilai-nilai yang diterima sebelum menyimpan ke database
    $namaBalita = mysqli_real_escape_string($koneksi, $namaBalita);
    $jenisKelamin = mysqli_real_escape_string($koneksi, $jenisKelamin);
    if ($jenisKelamin == 'Laki-laki') {
        $jenisKelamin = 'L';
    } elseif ($jenisKelamin == 'Perempuan') {
        $jenisKelamin = 'P';
    }
    $usia = mysqli_real_escape_string($koneksi, $usia);
    $beratBadan = mysqli_real_escape_string($koneksi, $beratBadan);
    $tinggiBadan = mysqli_real_escape_string($koneksi, $tinggiBadan);
    $imt = mysqli_real_escape_string($koneksi, $imt);
    $tingkatStunting = mysqli_real_escape_string($koneksi, $tingkatStunting);
    $giziStatus = mysqli_real_escape_string($koneksi, $giziStatus);

    $email = $_SESSION['email'];
    // Query untuk mengambil id_pengguna berdasarkan email
    $queryPengguna = "SELECT id_pengguna FROM tbl_pengguna WHERE email = ? OR no_telepon = ?";
    $stmtPengguna = mysqli_prepare($koneksi, $queryPengguna);
    mysqli_stmt_bind_param($stmtPengguna, "ss", $email, $email);
    mysqli_stmt_execute($stmtPengguna);

    $resultPengguna = mysqli_stmt_get_result($stmtPengguna);
    $rowPengguna = mysqli_fetch_assoc($resultPengguna);

    $id_pengguna = $rowPengguna['id_pengguna'];
    $tanggal_diagnosis = date("Y-m-d");

    // Membuat query INSERT dengan parameter binding
    $query = "INSERT INTO tbl_diagnosis (tanggal_diagnosis, nama_balita, jenis_kelamin, usia, bb, tb, imt, status_gizi, hasil_diagnosis, id_pengguna)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "sssssssssi", $tanggal_diagnosis, $namaBalita, $jenisKelamin, $usia, $beratBadan, $tinggiBadan, $imt, $giziStatus, $tingkatStunting, $id_pengguna);

    // Eksekusi query
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Data diagnosis berhasil disimpan.'
                }).then(function() {
                    window.location.href = window.location.href;
                });    
            });
        </script>";
    } else {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi kesalahan saat menyimpan data.'
                }).then(function() {
                    window.location.href = window.location.href;
                });    
            });
        </script>";
    }

    // Tutup statement dan koneksi ke database
    mysqli_stmt_close($stmt);
    mysqli_stmt_close($stmtPengguna);
    mysqli_close($koneksi);
}


?>