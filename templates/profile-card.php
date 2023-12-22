<?php
require_once('./includes/functions.php');
$profile = getDataProfile();
?>

<div class='mb-3'>
    <div class='card shadow py-4'>
        <div class='card-body'>
            <div class='row no-gutters align-items-center'>
                <div class="user-select-none col text-center d-none d-md-block">
                    <img src="<?= getProfileImage($koneksi); ?>" alt="Foto Profil" style="max-height: 180px">
                </div>
                <div class="col">
                    <h2 class='font-weight-bold text-primary mb-3 capitalize'>
                        <?= ucwords(strtolower(stripslashes($profile['nama']))) ?>
                    </h2>
                    <div>
                        <table class="table-responsive">
                            <tbody>
                                <tr>
                                    <td>Email</td>
                                    <td class="px-3">:</td>
                                    <td>
                                        <?= $profile['email'] ? $profile['email'] : "-"; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>No. Telepon</td>
                                    <td class="px-3">:</td>
                                    <td>
                                        <?= $profile['no_telepon'] ? $profile['no_telepon'] : "-"; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Jenis Kelamin</td>
                                    <td class="px-3">:</td>
                                    <td>
                                        <?= $profile['jenis_kelamin'] ? $profile['jenis_kelamin'] : "-"; ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="mt-3 d-flex justify-content-start align-items-center">
                            <div>
                                <button type="button" class="btn btn-primary mr-2 mb-3" data-toggle="modal"
                                    data-target="#editProfilModal">
                                    <i class="fas fa-cogs"></i>
                                    <span class="ml-1">Pengaturan
                                        Profil</span>
                                </button>
                                <button type="button" class="btn btn-primary mr-2 mb-sm-3" data-toggle="modal"
                                    data-target="#editPasswordModal">
                                    <i class="fas fa-key"></i>
                                    <span class="ml-1">Ubah Kata
                                        Sandi</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>