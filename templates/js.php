<?php require_once 'includes/functions.php'; ?>

<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- <script src="vendor/sweetalert2/dist/sweetalert2.min.js"></script> -->

<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="js/sb-admin-2.min.js"></script>

<script src="js/index.js"></script>

<?php
// Kondisi untuk memuat CSS dan JavaScript pada halaman-halaman tertentu
if ($current_page == 'data-diagnosis' || $current_page == 'data-pengguna' || $current_page == 'profil') {
    ?>
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <script src="js/datatables.js"></script>
    <?php
}
?>