<? defined('BASEPATH') OR exit('No direct script access allowed.') ?>

    <div class="row">
        <div class="col-12">
            <div class="text-center">
                <div class="error mx-auto text-uppercase" data-text="<?= $code ?>"><?= $code ?></div>
                <p class="lead text-gray-800 mb-5"><?= $pageTitle ?></p>
                <p class="text-gray-500 mb-0"><?= $message ?></p>
                <a href="<?= base_url() ?>">← Kembali ke Dashboard</a>
            </div>
        </div>
    </div>