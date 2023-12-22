<?php
session_start();
require_once('./includes/functions.php');
$status_pengguna = $_SESSION['status_pengguna'] ?? '';
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
                    <?= generatePageTitle('Panduan'); ?>
                    <div class="d-flex justify-content-end align-items-center">
                        <div class="ml-auto">
                            <button type="button" class="btn btn-primary mr-2 mb-4" data-toggle="modal"
                                data-target="#kebijakanPrivasiModal">
                                <i class="fas fa-lock"></i>
                                <span class="ml-1 d-none d-sm-inline-block">Kebijakan Privasi</span>
                            </button>
                            <button type="button" class="btn btn-primary mr-2 mb-4" data-toggle="modal"
                                data-target="#ketentuanPenggunaanModal">
                                <i class="fas fa-file-alt"></i>
                                <span class="ml-1 d-none d-sm-inline-block">Ketentuan Penggunaan</span>
                            </button>
                            <?php if ($status_pengguna === 'admin'): ?>
                                <button type="button" class="btn btn-primary mb-4" data-toggle="modal"
                                    data-target="#editKontakModal">
                                    <i class="fas fa-edit"></i>
                                    <span class="ml-1 d-none d-sm-inline-block">Edit Kontak</span>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!-- FAQ -->
                    <?= generateFAQ($faq) ?>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->
            <?php include './templates/footer.php'; ?>
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->
</body>

<?php include './templates/js.php'; ?>
<?php include './templates/modal/modal-ketentuan-penggunaan.php'; ?>
<?php include './templates/modal/modal-privacy-policy.php'; ?>
<?php include './templates/modal/modal-edit-kontak.php'; ?>

</html>