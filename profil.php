<?php
session_start();
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
                    <?= generatePageTitle('Profil'); ?>
                    <?php include './templates/profile-card.php'; ?>
                    <!-- Sub Heading -->
                    <?= generateSubPageTitle('Riwayat Diagnosis '); ?>
                    <?php include './templates/table-riwayat.php'; ?>
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
<?php include './templates/modal/modal-edit-password.php'; ?>
<?php include './templates/js.php'; ?>
<?php include './templates/modal/modal-edit-profil.php'; ?>

</html>