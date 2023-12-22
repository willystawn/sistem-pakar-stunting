<?php
if (isset($_SESSION['email'])) {
    header('Location: dashboard');
    exit();
}
include 'includes/functions.php';
login();
?>

<!DOCTYPE html>
<html lang="id">
<?php include './templates/header.php'; ?>

<body class="bg-gradient-primary d-flex align-items-center justify-content-center min-vh-100">
    <div class="container align-items-center justify-content-center">
        <!-- Outer Row -->
        <div class="row justify-content-center user-select-none">
            <div class="col-lg-5">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0 py-3">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-12">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Sistem Pakar <br> Diagnosis Stunting
                                        </h1>
                                    </div>
                                    <form class="user" method="POST">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" name="email"
                                                id="email" placeholder="Masukkan email atau no. telepon..." required>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group input-group.border-0">
                                                <input type="password" class="form-control form-control-user"
                                                    name="password" id="password" placeholder="Masukkan kata sandi..."
                                                    required>
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
                                        <div class="mb-3 d-flex justify-content-end">
                                            <a href="forgot-password" class="text-decoration-underline text-muted">Lupa
                                                kata sandi?</a>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Masuk
                                        </button>
                                        <div class="my-2 text-center">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <div class="custom-border flex-grow-1"></div>
                                                <div class="px-2 text-secondary">atau</div>
                                                <div class="custom-border flex-grow-1"></div>
                                            </div>
                                        </div>
                                        <a href="diagnosis" class="btn btn-primary btn-user btn-block">
                                            Masuk sebagai tamu
                                        </a>
                                        <div class="mt-4 text-center">
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

<?php include './templates/js.php'; ?>

</html>