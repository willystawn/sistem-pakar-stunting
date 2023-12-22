<?php require_once 'includes/functions.php'; ?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description"
        content="Sistem pakar diagnosis stunting pada balita. Temukan informasi terkini mengenai stunting, gejala, diagnosis, dan penanganan di sini.">
    <meta name="author" content="StuntAssist">
    <link rel="icon" type="image/png" sizes="192x192" href="img/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="img/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/favicon/favicon-16x16.png">
    <link rel="manifest" href="img/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <title>StuntAssist
        <?= getPageTitle($current_page); ?>
    </title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="vendor/sweetalert2/dist/sweetalert2.min.css">

    <script src="vendor/sweetalert2/dist/sweetalert2.min.js"></script>

    <script>
        window.onload = function () {
            if (window.innerWidth < 700) {
                document.getElementById("accordionSidebar").classList.add("toggled");
            }
        };
    </script>
    <?php
    // Kondisi untuk memuat CSS pada halaman-halaman tertentu
    if ($current_page == 'data-diagnosis' || $current_page == 'data-pengguna' || $current_page == 'profil') {
        echo '<link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">';
    }
    ?>
</head>