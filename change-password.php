<?php
// Periksa apakah token reset ada dalam URL
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Koneksi ke database
    include 'includes/koneksi.php';

    // Melakukan query untuk memeriksa keberadaan token reset
    $query = "SELECT * FROM tbl_pengguna WHERE reset_token = ?";
    $statement = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($statement, 's', $token);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);

    // Memeriksa hasil query
    if (mysqli_num_rows($result) > 0) {
        // Token reset valid
        if (isset($_POST['change_password'])) {
            $password = mysqli_real_escape_string($koneksi, $_POST['password']);
            $konfirmasiPassword = mysqli_real_escape_string($koneksi, $_POST['konfirmasi_password']);

            if ($password === $konfirmasiPassword) {
                // Mengenkripsi kata sandi baru menggunakan SHA-256
                $hashedPassword = hash('sha256', $password);

                // Melakukan query untuk mengubah kata sandi
                $updateQuery = "UPDATE tbl_pengguna SET kata_sandi = ?, reset_token = NULL WHERE reset_token = ?";
                $updateStatement = mysqli_prepare($koneksi, $updateQuery);
                mysqli_stmt_bind_param($updateStatement, 'ss', $hashedPassword, $token);
                mysqli_stmt_execute($updateStatement);

                echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Yeayy...',
                        text: 'Kata sandi berhasil diubah',
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
                        title: 'Oops...',
                        text: 'Kata sandi baru dan konfirmasi kata sandi tidak cocok.',
                    }).then(function() {
                        window.location.href = window.location.href;
                    });
                });
                </script>";
            }
        }
    } else {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                html: 'Token reset tidak valid. Submit ulang untuk reset kata sandi.',
            }).then(function() {
                window.location.href = 'forgot-password';
            });
        });
        </script>";
    }

    // Menutup koneksi database
    mysqli_stmt_close($statement);
    mysqli_close($koneksi);
} else {
    echo "<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Token reset tidak ditemukan. Submit ulang untuk reset kata sandi.',
        }).then(function() {
            window.location.href = 'forgot-password';
        });
    });
    </script>";
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
                        <div class="row">
                            <div class="col-12">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Ganti Kata Sandi Anda</h1>
                                    </div>
                                    <form class="user" method="POST" onsubmit="return validateForm();">
                                        <div class="mb-4 text-center">
                                            <span>Masukkan kata sandi baru untuk mengubah kata sandi akun Anda.</span>
                                        </div>

                                        <div class="form-group">
                                            <div class="input-group input-group.border-0">
                                                <input type="password" class="form-control form-control-user"
                                                    id="password" name="password"
                                                    placeholder="Masukkan kata sandi baru..." required>
                                                <div class="input-group-append">
                                                    <button class="btn btn-rounded" type="button" id="show-password-btn"
                                                        data-action='show-password'>
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-rounded" type="button" id="hide-password-btn"
                                                        data-action='hide-password'>
                                                        <i class="fas fa-eye-slash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 ml-2">
                                            <p id="8char" class="m-0 font-weight-bold text-success"
                                                style="font-size: 0.9rem; display: none">
                                                &bull; Harus berisi
                                                minimal 8
                                                karakter</p>
                                            <p id="textChar" class="m-0 font-weight-bold text-danger"
                                                style="font-size: 0.9rem; display: none">&bull;
                                                Harus mengandung
                                                huruf &
                                                angka</p>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group input-group.border-0">
                                                <input type="password" class="form-control form-control-user"
                                                    id="konfirmasi-password" name="konfirmasi_password"
                                                    placeholder="Ulangi kata sandi baru..." required>
                                                <div class="input-group-append">
                                                    <button class="btn btn-rounded" type="button"
                                                        id="show-password-btn2" data-action='show-password'>
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-rounded" type="button"
                                                        id="hide-password-btn2" data-action='hide-password'>
                                                        <i class="fas fa-eye-slash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 ml-2">
                                            <span id="pwMatch" class="m-0 font-weight-bold text-danger"
                                                style="font-size: 0.9rem; display: none;">&bull;
                                                Kata sandi
                                                tidak sama. Silahkan coba
                                                lagi.</span>
                                        </div>

                                        <button type="submit" name="change_password"
                                            class="btn btn-primary btn-user btn-block">Ganti kata sandi</button>
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

        // Lakukan validasi password dan konfirmasi password
        if (!validatePassword() || !validatePasswordConfirmation()) {
            isValid = false;
        }

        return isValid;
    }

    let pwInput = document.getElementById("password");
    let confirmPwInput = document.getElementById("konfirmasi-password");

    // Mendapatkan elemen pesan validasi
    let passwordLengthMessage = document.getElementById("8char");
    let passwordContentMessage = document.getElementById("textChar");
    let passwordMatchMessage = document.getElementById("pwMatch");

    // Menambahkan event listener pada saat input password berubah
    pwInput.addEventListener("input", validatePassword);
    confirmPwInput.addEventListener("input", validatePasswordConfirmation);

    function validatePassword() {
        let password = pwInput.value;
        let isValid = true;

        // Validasi panjang password
        if (password.length >= 8) {
            passwordLengthMessage.classList.remove("text-danger");
            passwordLengthMessage.classList.add("text-success");
        } else {
            passwordLengthMessage.classList.remove("text-success");
            passwordLengthMessage.classList.add("text-danger");
            passwordLengthMessage.style.display = "block";
            isValid = false;
        }

        // Validasi kombinasi huruf dan angka
        if (/\d/.test(password) && /[a-zA-Z]/.test(password)) {
            passwordContentMessage.classList.remove("text-danger");
            passwordContentMessage.classList.add("text-success");
        } else {
            passwordContentMessage.classList.remove("text-success");
            passwordContentMessage.classList.add("text-danger");
            passwordContentMessage.style.display = "block"; isValid = false;
        }

        return isValid;
    }

    function validatePasswordConfirmation() {
        let password = pwInput.value;
        let confirmPassword = confirmPwInput.value;
        let isValid = true;

        if (password === confirmPassword && confirmPassword !== "") {
            passwordMatchMessage.classList.remove("text-danger");
            passwordMatchMessage.classList.add("text-success");

            // Menunda perubahan display menjadi none selama 1 detik
            setTimeout(function () {
                passwordMatchMessage.style.display = "none";
            }, 1000);
        } else {
            passwordMatchMessage.classList.remove("text-success");
            passwordMatchMessage.classList.add("text-danger");
            passwordMatchMessage.style.display = "block";
            isValid = false;
        }

        return isValid;
    }
</script>

<?php include './templates/js.php'; ?>

</html>