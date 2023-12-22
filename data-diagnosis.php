<?php
session_start();
include 'includes/functions.php';
if (!isset($_SESSION['email'])) {
    header('Location: login');
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<?php include './templates/header.php'; ?>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <?php include './templates/sidebar.php'; ?>
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <?php include './templates/navbar.php'; ?>
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <?= generatePageTitle('Data Diagnosis'); ?>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="ml-auto">
                            <a href="export" class="btn btn-primary mb-4" target="_blank">
                                <i class="fas fa-print"></i>
                                <span class="ml-1 d-none d-sm-inline-block">Cetak Data</span>
                            </a>
                        </div>
                    </div>
                    <?php include './templates/table-diagnosis.php'; ?>
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

</html>