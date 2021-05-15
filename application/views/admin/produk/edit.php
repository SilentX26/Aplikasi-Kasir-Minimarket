<? defined('BASEPATH') OR exit('No direct script access allowed.') ?>

    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Edit Produk</h6>
                </div>
                <div class="card-body">
                    <form method="post" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?= $this->security->get_csrf_hash() ?>">

                        <div class="form-group">
                            <label>Foto Produk</label><br>
                            <img id="image-preview-foto-produk" src="<?= base_url("assets/img/produk/{$data_produk->gambar}") ?>" alt="Preview Foto Produk" class="rounded mb-4" style="height: 8.5rem;">
                            
                            <input type="file" name="foto-produk" class="form-control">
                            <small class="text-muted font-italic">* Resolusi yang disarankan: 512px X 512px</small>
                        </div>

                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" name="nama" class="form-control" value="<?= $data_produk->nama ?>" placeholder="Masukkan nama produk">
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Harga Beli</label>
                                    <input type="number" name="harga_beli" class="form-control" value="<?= $data_produk->harga->beli ?>" placeholder="Masukkan harga beli">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Harga Jual</label>
                                    <input type="number" name="harga_jual" class="form-control" value="<?= $data_produk->harga->jual ?>" placeholder="Masukkan harga jual">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Stok</label>
                            <input type="number" name="stok" class="form-control" value="<?= $data_produk->stok ?>" placeholder="Masukkan stok produk">
                        </div>

                        <div class="mt-4 text-right">
                            <button type="reset" class="btn btn-danger">Reset</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mt-4 text-center">
                <a href="<?= base_url('admin/produk/') ?>" class="text-decoration-none">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>