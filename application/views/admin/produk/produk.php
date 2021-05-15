<? defined('BASEPATH') OR exit('No direct script access allowed.') ?>

    <div class="card shadow">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Tabel Data Produk</h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                    <a class="dropdown-item" href="<?= base_url('admin/produk/tambah') ?>">
                        <i class="fas fa-plus text-primary mr-1"></i> Tambah
                    </a>
                    <a class="dropdown-item" href="<?= base_url('admin/produk/export') ?>">
                        <i class="fas fa-file-export text-success mr-1"></i> Export
                    </a>
                    <a class="dropdown-item" href="<?= base_url('admin/produk/import') ?>">
                        <i class="fas fa-file-import text-danger mr-1"></i> Import
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-center" id="datatable" width="100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Produk</th>
                            <th>Harga Beli</th>
                            <th>Harga Jual</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th>Produk</th>
                            <th>Harga Beli</th>
                            <th>Harga Jual</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </tfoot>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>