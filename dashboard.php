<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: login');
    exit();
}
// Redirect to dashboard if user is logged in and accessing the root URL
if (isset($_SESSION['email']) && empty($_GET)) {
    header('Location: dashboard');
    exit;
}
require_once 'includes/koneksi.php';
require_once 'includes/functions.php';

$email = $_SESSION['email'];
$id_pengguna = getIdPenggunaFromEmail($email);
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
                <div class="container-fluid ">
                    <!-- Page Heading -->
                    <?= generatePageTitle('Dashboard'); ?>
                    <!-- Alert Info -->
                    <?= checkProfileCompletion(); ?>
                    <!-- Card Statistik -->
                    <?php include './templates/card.php'; ?>
                    <!-- Sub Heading -->
                    <?= generateSubPageTitle('Menu'); ?>
                    <!-- Card Menu Shortcut -->
                    <?php include './templates/card-menu.php'; ?>
                    <!-- Sub Heading -->
                    <?= generateSubPageTitle('Grafik'); ?>
                    <!-- Chart Component -->
                    <div class="row">
                        <div class="col-lg-6">
                            <?php include './templates/chart-pie.php'; ?>
                        </div>
                        <div class="col-lg-6">
                            <?php include './templates/chart-line.php'; ?>
                        </div>
                    </div>
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
<script src="vendor/chart.js/Chart.min.js"></script>

</html>