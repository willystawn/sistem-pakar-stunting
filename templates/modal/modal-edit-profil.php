<?php
require_once('./includes/functions.php');
$data = getDataProfile();

// Memeriksa apakah tombol "Simpan" telah diklik
if (isset($_POST['simpan'])) {
    // Mendapatkan nilai input dari formulir
    $nama = $_POST['nama'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $email = $_POST['email'];
    $no_telepon = $_POST['no_telp'];

    // Memeriksa apakah email atau nomor telepon sudah terdaftar
    $emailTerdaftar = cekEmailTerdaftar($email);
    $teleponTerdaftar = cekNoTeleponTerdaftar($no_telepon);

    if ($emailTerdaftar) {
        echo "<script>
         Swal.fire({
             icon: 'error',
             title: 'Gagal',
             text: 'Email sudah terdaftar.',
             confirmButtonText: 'OK',
             allowOutsideClick: false
         }).then(function() {
             window.location.href = window.location.href;
         });
         </script>";
        exit;
    }

    if ($teleponTerdaftar) {
        echo "<script>
         Swal.fire({
             icon: 'error',
             title: 'Gagal',
             text: 'Nomor telepon sudah terdaftar.',
             confirmButtonText: 'OK',
             allowOutsideClick: false
         }).then(function() {
             window.location.href = window.location.href;
         });
         </script>";
        exit;
    }

    // Menyimpan data ke database
    $result = simpanDataProfil($nama, $jenis_kelamin, $email, $no_telepon);

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

<div class="modal fade" id="editProfilModal" tabindex="-1" role="dialog" aria-labelledby="editProfilModal"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="user-select-none modal-header bg-primary text-white">
                <h5 class="modal-title" id="editProfilModalLabel">Edit Profil</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-white">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" onsubmit="return validateForm();" action="">
                    <div class="form-group">
                        <label for="nama" class="col-form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama" name="nama"
                            placeholder="Masukkan nama lengkap..." value="<?= $data['nama']; ?>" pattern="[a-zA-Z\s]+"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="jenis_kelamin">Jenis Kelamin</label>
                        <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                            <option <?php if ($data['jenis_kelamin'] === '')
                                echo 'selected'; ?> disabled>Pilih jenis
                                kelamin</option>
                            <option value="L" <?php if ($data['jenis_kelamin'] === 'Laki-laki')
                                echo 'selected'; ?>>
                                Laki-Laki</option>
                            <option value="P" <?php if ($data['jenis_kelamin'] === 'Perempuan')
                                echo 'selected'; ?>>
                                Perempuan</option>
                        </select>
                        <p class="ml-2 mt-3 m-0 font-weight-bold text-danger" id="error-jenis-kelamin"
                            style="font-size: 0.8rem; display: none;">
                            Pilih jenis kelamin</p>
                    </div>

                    <div class="form-group">
                        <label for="email_adr" class="col-form-label">Email</label>
                        <input type="email" class="form-control" id="email_adr" name="email"
                            placeholder="Masukkan alamat email..." value="<?= $data['email']; ?>"
                            onkeyup="validateEmail()" required>
                        <p class="ml-2 mt-3 m-0 font-weight-bold text-danger" id="error-email"
                            style="font-size: 0.8rem; display: none;">
                            Email tidak valid</p>
                    </div>
                    <div class="form-group">
                        <label for="no_telp" class="col-form-label">No. Telepon</label>
                        <input type="number" class="form-control" id="no_telp" name="no_telp"
                            placeholder="Masukkan nomor telepon..." value="<?= $data['no_telepon']; ?>"
                            onkeyup="validatePhoneNumber()" required>
                        <p class="ml-2 mt-3 m-0 font-weight-bold text-danger" id="error-no-telepon"
                            style="font-size: 0.8rem; display: none;">
                            Nomor telepon harus terdiri dari 10 hingga 13 angka</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                        <button class="btn btn-primary" type="submit" name="simpan">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function validateForm() {
        let isValid = true;

        if (!validatePhoneNumber()) {
            isValid = false;
        }

        if (!validateEmail()) {
            isValid = false;
        }

        // Validasi jenis kelamin
        let jenisKelamin = document.getElementById("jenis_kelamin").value;
        if (jenisKelamin === "Pilih jenis kelamin") {
            isValid = false;
            // Tampilkan pesan error
            let errorElement = document.getElementById("error-jenis-kelamin");
            errorElement.classList.remove("text-success");
            errorElement.classList.add("text-danger");
            errorElement.style.display = "block";
        } else {
            // Sembunyikan pesan error
            let errorElement = document.getElementById("error-jenis-kelamin");
            errorElement.style.display = "none";
        }

        return isValid;
    }


    function validateEmail() {
        let input = document.getElementById("email_adr").value;
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

    function validatePhoneNumber() {
        let input = document.getElementById("no_telp").value;
        let regex = /^\d{10,13}$/;
        let errorElement = document.getElementById("error-no-telepon");
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