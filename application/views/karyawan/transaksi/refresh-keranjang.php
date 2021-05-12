<? defined('BASEPATH') OR exit('No direct script access allowed.') ?>

    <button class="btn btn-success" data-toggle="modal" href="#modal-keranjang-belanja">
        Keranjang Belanja
        <span class="badge badge-light ml-2"><?= (isset($_SESSION['cart'])) ? formatted('currency', count($_SESSION['cart'])) : 0 ?></span>
    </button>

    <div class="modal fade" tabindex="-1" id="modal-keranjang-belanja">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Keranjang Belanja</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?
                    if(isset($_SESSION['cart'])):
                        foreach($_SESSION['cart'] as $key => $value):
                    ?>
                    <div class="d-flex flex-row">
                        <div class="image-product">
                            <img src="<?= $value['gambar'] ?>" style="height: 7rem;">    
                        </div>
                        <div class="ml-4">
                            <div class="font-weight-bold mb-1"><?= $value['nama'] ?></div>
                            <div>Rp <?= formatted('currency', $value['harga_jual']) ?></div>
                                            
                            <div class="mt-3">
                                <span class="bg-light p-1 px-1 text-center" id="kurang-jumlah" style="cursor: pointer;  border-radius: 50%; box-shadow: 0 .15rem 1rem 0 rgba(58,59,69,.15)!important;">
                                    <i class="fas fa-minus"></i>
                                </span>
                                <span id="jumlah-pesanan" class="ml-3 mr-3"><?= $value['jumlah'] ?></span>
                                <span class="bg-light p-1 px-2 text-center" id="tambah-jumlah" style="cursor: pointer; border-radius: 50%; box-shadow: 0 .15rem 1rem 0 rgba(58,59,69,.15)!important;">
                                    <i class="fas fa-plus"></i>
                                </span>
                            </div>  
                        </div>
                    </div>

                    <? endforeach; else: ?>
                        <div class="text-center">
                            <img src="<?= base_url('assets/img/undraw_empty_cart.svg') ?>" style="height: 6rem;">
                            <p class="mt-3">Ups, keranjang belanja kosong!</p>
                        </div>
                    <? endif ?>
                </div>

                <? if(isset($_SESSION['cart'])): ?>
                    <div class="modal-footer">
                        <form method="POST">
                            <input type="hidden" name="csrf_token" value="<?= $this->security->get_csrf_hash() ?>">
                            <button type="button" id="btn-reset-keranjang-belanja" class="btn btn-secondary" data-dismiss="modal">Reset</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                <? endif ?>
            </div>
        </div>
    </div>