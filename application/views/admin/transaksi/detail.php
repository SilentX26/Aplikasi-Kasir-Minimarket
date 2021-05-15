<? defined('BASEPATH') OR exit('No direct script access allowed.') ?>

    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card shadow">
                <div class="card-body">
                    <div class="row">
                        <span class="col-6">ID</span>
                        <span class="col-6 text-right"><?= $data_transaksi->id ?></span>
                    </div>
                    <div class="row mt-1">
                        <span class="col-6">Tanggal</span>
                        <span class="col-6 text-right"><?= formatted('datetime', $data_transaksi->tanggal) ?></span>
                    </div>

                    <hr style="border-style: dashed;">

                    <? $i=0; foreach($data_transaksi->data as $key => $value): ?>
                        <div class="row <?= ($i != 0) ? 'mt-1' : '' ?>">
                            <span class="col-6"><?= $value->nama ?></span>
                            <span class="col-6 text-right font-weight-bold">Rp <?= formatted('currency', $value->harga_jual) ?></span>
                        </div>
                    <? $i++; endforeach ?>
                </div>

                <div class="card-footer" style="border-top: 3px dashed #e3e6f0!important;">
                    <div class="row">
                        <span class="col-6 font-weight-bold">Total Harga</span>
                        <h5 class="col-6 text-right text-primary font-weight-bold">Rp <?= formatted('currency', $data_transaksi->total->harga) ?></h5>
                    </div>
                    <div class="row">
                        <span class="col-6 font-weight-bold">Total Pajak</span>
                        <h5 class="col-6 text-right text-primary font-weight-bold">Rp <?= formatted('currency', $data_transaksi->total->pajak) ?></h5>
                    </div>
                    <div class="row">
                        <span class="col-6 font-weight-bold">Total Bayar</span>
                        <h5 class="col-6 text-right text-primary font-weight-bold">Rp <?= formatted('currency', $data_transaksi->total->bayar) ?></h5>
                    </div>
                    <div class="row">
                        <span class="col-6 font-weight-bold">Total Laba</span>
                        <h5 class="col-6 text-right text-primary font-weight-bold">Rp <?= formatted('currency', $data_transaksi->total->laba) ?></h5>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-5">
                <a href="<?= base_url('admin/transaksi/') ?>" class="text-decoration-none">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>