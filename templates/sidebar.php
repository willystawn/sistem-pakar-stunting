<?php
require_once 'includes/functions.php';
$status_pengguna = $_SESSION['status_pengguna'] ?? '';
$pages = [
    [
        'name' => 'Dashboard',
        'link' => 'dashboard',
        'icon' => 'fas fa-fw fa-tachometer-alt',
        'show' => ($status_pengguna !== '')
    ],
    [
        'name' => 'Diagnosis',
        'link' => 'diagnosis',
        'icon' => 'fas fa-fw fa-stethoscope',
        'show' => true
    ],
    [
        'name' => 'Data Diagnosis',
        'link' => 'data-diagnosis',
        'icon' => 'fas fa-fw fa-file-medical-alt',
        'show' => ($status_pengguna !== '')
    ],
    [
        'name' => 'Profil',
        'link' => 'profil',
        'icon' => 'fas fa-fw fa-user',
        'show' => ($status_pengguna !== '')
    ],
    [
        'name' => 'Panduan',
        'link' => 'panduan',
        'icon' => 'fas fa-fw fa-question-circle',
        'show' => true
    ],
    [
        'name' => 'Data Pengguna',
        'link' => 'data-pengguna',
        'icon' => 'fas fa-fw fa-users',
        'show' => ($status_pengguna === 'admin')
    ],
    [
        'name' => 'Keluar',
        'link' => 'login',
        'icon' => 'fas fa-fw fa-sign-out-alt',
        'show' => ($status_pengguna !== ''),
        'modal' => 'logoutModal'
    ],
    [
        'name' => 'Daftar Akun',
        'link' => 'register',
        'icon' => 'fas fa-fw fa-user-plus',
        'show' => ($status_pengguna !== 'admin' && $status_pengguna !== "user")
    ]
];
?>

<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar" style="z-index:1200">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard">
        <div class="sidebar-brand-icon">
            <i class="fas fa-heartbeat"></i>
        </div>
        <div class="sidebar-brand-text mx-3">StuntAssist</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">
    <?php foreach ($pages as $page): ?>
        <?php if ($page['show']): ?>
            <li class="nav-item <?php echo isActivePage($current_page, $page['link']); ?>">
                <a class="nav-link" href="<?php echo $page['link'] ?>" <?php if (isset($page['modal']))
                       echo ' data-toggle="modal" data-target="#' . $page['modal'] . '"'; ?>>
                    <i class="<?php echo $page['icon'] ?>"></i>
                    <span>
                        <?php echo $page['name'] ?>
                    </span>
                </a>
            </li>
        <?php endif; ?>
    <?php endforeach; ?>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->