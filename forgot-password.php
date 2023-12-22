<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once 'vendor/PHPMailer/src/Exception.php';
require_once 'vendor/PHPMailer/src/SMTP.php';
require_once 'vendor/PHPMailer/src/PHPMailer.php';

// Fungsi untuk mengirim email menggunakan PHPMailer
function sendEmail($email, $subject, $message)
{
    // Konfigurasi email
    $mail = new PHPMailer(true);

    try {
        // Pengaturan SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'stuntassisten@gmail.com';
        $mail->Password = 'mwnkmxlzweyaacwe';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Pengaturan email
        $mail->setFrom('stuntassisten@gmail.com', 'Stun Sensei'); // Ganti dengan alamat email dan nama pengirim
        $mail->addAddress($email); // Menambahkan alamat email penerima
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;
        // Mengirim email
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

if (isset($_POST['reset'])) {
    // Koneksi ke database
    include 'includes/koneksi.php';

    $email = $_POST['email'];
    $email = mysqli_real_escape_string($koneksi, $email);

    // Melakukan query untuk memeriksa keberadaan email
    $query = "SELECT * FROM tbl_pengguna WHERE email = '$email'";
    $result = mysqli_query($koneksi, $query);

    // Memeriksa hasil query
    if (mysqli_num_rows($result) > 0) {
        // Email terdaftar
        // Membuat token reset password
        $token = bin2hex(random_bytes(32));

        // Menyimpan token ke database
        $query = "UPDATE tbl_pengguna SET reset_token = '$token' WHERE email = '$email'";
        mysqli_query($koneksi, $query);

        // Mengirim email reset kata sandi
        $resetLink = "http://{$_SERVER['HTTP_HOST']}/change-password?token=" . $token;
        $subject = "Reset Kata Sandi - StuntAssist";
        $message = "<!DOCTYPE html>
        <html>
          <head>
            <meta charset='UTF-8' />
            <title>Reset Kata Sandi</title>
            <style>
              body {
                font-family: Arial, sans-serif;
                line-height: 1.6;
                color: #333;
              }
        
              .container {
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
                border: 1px solid #ccc;
              }
        
              h2 {
                margin-top: 0;
              }
        
              p {
                margin-bottom: 20px;
              }
        
              a {
                display: inline-block;
                padding: 10px 20px;
                background-color: #4caf50;
                color: #fff;
                text-decoration: none;
                border-radius: 4px;
              }
        
              a:hover {
                background-color: #45a049;
              }
            </style>
          </head>
          <body>
            <div class='container'>
              <h2>Reset Kata Sandi &#x1F514;</h2>
              <p>Halo &#x1F44B;,</p>
              <p>Saya Stun Sensei dari Sistem Pakar StuntAssist. Anda telah meminta untuk melakukan reset kata sandi. Silakan klik tautan berikut untuk mereset kata sandi Anda:</p>
              <p><a href='$resetLink'>Reset Kata Sandi</a></p>
              <p>Jika Anda tidak meminta reset kata sandi, Anda dapat mengabaikan email ini.</p>
              <p class='footer'>Salam Hangat &#x1F60A;,<br /><br /><br />Stun Sensei</p>
            </div>
          </body>
        </html>
        ";
        if (sendEmail($email, $subject, $message)) {
            echo "
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'success',
                        title: 'Email terkirim.',
                        html: 'Tautan reset kata sandi telah dikirim ke email Anda. <br><br> Jika tidak menerima email, cek pada folder spam atau coba gunakan email lain.',
                    }).then(function () {
                        window.location.href = 'login';
                    });
                });
            </script>";
        } else {
            echo "
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Gagal mengirim email. Silakan coba lagi.'
                    }).then(function () {
                        window.location.href = window.location.href;
                    });
                });
            </script>";
        }
    } else {
        // Email tidak terdaftar
        echo "
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Email tidak terdaftar',
                }).then(function () {
                    window.location.href = window.location.href;
                });
            });
        </script>";
    }

    // Menutup koneksi database
    mysqli_close($koneksi);
}
?>

<!DOCTYPE html>
<html lang="id">
<?php include './templates/header.php'; ?>

<body class="bg-gradient-primary d-flex align-items-center justify-content-center min-vh-100 user-select-none">
    <div class="container align-items-center justify-content-center">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0 py-3">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-12">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Lupa Kata Sandi Anda?
                                        </h1>
                                    </div>
                                    <form class="user" method="POST" onsubmit="return validateForm();" action="">
                                        <div class="mb-4 text-center">
                                            <span>Masukkan email akun Anda, agar kami dapat mengirimkan tautan ke email
                                                untuk mereset kata sandi.</span>
                                        </div>
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user" id="email"
                                                name="email" placeholder="Masukkan email..." onkeyup="validateEmail()"
                                                required>
                                            <p class="ml-2 mt-3 m-0 font-weight-bold text-danger" id="error-email"
                                                style="font-size: 0.8rem; display: none;">
                                                Email tidak valid</p>
                                        </div>
                                        <button name="reset" class="btn btn-primary btn-user btn-block" type="submit">
                                            Reset kata sandi
                                        </button>
                                        <div class="my-3 text-center">
                                            <a href="login" class="text-decoration-none text-muted">
                                                <u>Sudah punya akun? Login!</u></a>
                                        </div>
                                        <div class="text-center">
                                            <a href="register" class="text-decoration-none text-muted">
                                                <u>Buat akun baru!</u></a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
    function validateForm() {
        let isValid = true;

        if (!validateEmail()) {
            isValid = false;
        }

        return isValid;
    }

    function validateEmail() {
        let input = document.getElementById("email").value;
        let regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        let errorElement = document.getElementById("error-email");
        if (!regex.test(input)) {
            errorElement.classList.remove("text-success");
            errorElement.classList.add("text-danger");
            errorElement.style.display = "block";
            return false;
        } else {
            errorElement.classList.remove("text-danger");
            errorElement.classList.add("text-success");
            setTimeout(function () {
                errorElement.style.display = "none";
            }, 1000);
            return true;
        }
    }
</script>

<?php include './templates/js.php'; ?>

</html>