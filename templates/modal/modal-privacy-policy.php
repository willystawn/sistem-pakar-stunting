<?php
require_once 'includes/functions.php';
?>

<!-- Privacy Policy Modal -->
<div class="modal fade" id="kebijakanPrivasiModal" tabindex="-1" role="dialog" aria-labelledby="kebijakanPrivasiModal"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="user-select-none modal-header bg-primary text-white">
                <h5 class="modal-title" id="kebijakanPrivasiModalLabel">Kebijakan Privasi</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-white">×</span>
                </button>
            </div>
            <div class="modal-body">
                <?php include "./templates/content/kebijakan-privasi.php"; ?>
            </div>

            <div class="modal-footer">
                <?php if ($current_page == 'register'): ?>
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <a class="btn btn-primary" href="#"
                        onclick="document.getElementById('customCheck').checked = true; $('#kebijakanPrivasiModal').modal('hide');">Setuju</a>
                <?php else: ?>
                    <button class="btn btn-primary" type="button" data-dismiss="modal">Tutup</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>