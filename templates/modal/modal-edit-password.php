<?php
require_once('./includes/functions.php');

if (isset($_POST['save'])) {
    $kata_sandi = $_POST['konfirmasi_password'];

    $result = simpanPassword($kata_sandi);
    if ($result) {
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
                title: 'Perubahan berhasil disimpan'
            }).then(function() {
                window.location.href = window.location.href;
            });            
            </script>";
        exit;
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
                title: 'Perubahan gagal disimpan'
            }).then(function() {
                window.location.href = window.location.href;
            });    
            </script>";
        exit;
    }
}
?>

<div class="modal fade" id="editPasswordModal" tabindex="-1" role="dialog" aria-labelledby="editPasswordModal"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="user-select-none modal-header bg-primary text-white">
                <h5 class="modal-title" id="editPasswordModalLabel">Edit Kata Sandi</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-white">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" onsubmit="return validateFormPw();" action="">
                    <div class="form-group">
                        <div class="input-group input-group.border-0">
                            <input type="password" class="form-control form-control-user" id="password" name="password"
                                placeholder="Masukkan kata sandi baru..." required>
                            <div class="input-group-append">
                                <button class="btn btn-rounded" type="button" id="show-password-btn"
                                    data-action='show-password'
                                    style=" border-top-right-radius: 5px !important;border-bottom-right-radius: 5px !important;">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-rounded" type="button" id="hide-password-btn"
                                    data-action='hide-password'
                                    style=" border-top-right-radius: 5px !important;border-bottom-right-radius: 5px !important;">
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
                            <input type="password" class="form-control form-control-user" id="konfirmasi-password"
                                name="konfirmasi_password" placeholder="Ulangi kata sandi baru..." required>
                            <div class="input-group-append">
                                <button class="btn btn-rounded" type="button" id="show-password-btn2"
                                    data-action='show-password'
                                    style=" border-top-right-radius: 5px !important;border-bottom-right-radius: 5px !important;">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-rounded" type="button" id="hide-password-btn2"
                                    data-action='hide-password'
                                    style=" border-top-right-radius: 5px !important;border-bottom-right-radius: 5px !important;">
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

                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                        <button class="btn btn-primary" type="submit" name="save">Simpan</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script>
    let pwInput = document.getElementById("password");
    let confirmPwInput = document.getElementById("konfirmasi-password");

    // Mendapatkan elemen pesan validasi
    let passwordLengthMessage = document.getElementById("8char");
    let passwordContentMessage = document.getElementById("textChar");
    let passwordMatchMessage = document.getElementById("pwMatch");

    function validateFormPw() {
        let isValid = true;

        // Lakukan validasi password dan konfirmasi password
        if (!validatePassword() || !validatePasswordConfirmation()) {
            isValid = false;
        }

        return isValid;
    }

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
            passwordContentMessage.style.display = "block";
            isValid = false;
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