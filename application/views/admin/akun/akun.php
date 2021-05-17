<? defined('BASEPATH') OR exit('No direct script access allowed.') ?>

    <div class="modal fade" tabindex="-1" id="modal-filter">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Filter Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                        <label>Level</label>
                            <select name="3" class="form-control">
                                <option value="" selected disabled>- Pilih Salah Satu -</option>
                                <option value="Karyawan">Karyawan</option>
                                <option value="Admin">Admin</option>
                            </select>
                        </div>

                        <div class="mt-4 text-right">
                            <button type="reset" class="btn btn-danger" data-dismiss="modal">Reset</button>
                            <button type="button" class="btn btn-info" data-dismiss="modal">Filter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Tabel Data Akun</h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                    <a class="dropdown-item" data-toggle="modal" href="#modal-filter">
                        <i class="fas fa-search text-info mr-1"></i> Filter
                    </a>
                    <a class="dropdown-item" href="<?= base_url('admin/akun/tambah') ?>">
                        <i class="fas fa-plus text-primary mr-1"></i> Tambah
                    </a>
                    <a class="dropdown-item" href="<?= base_url('admin/akun/export') ?>">
                        <i class="fas fa-file-export text-success mr-1"></i> Export
                    </a>
                    <a class="dropdown-item" href="<?= base_url('admin/akun/import') ?>">
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
                            <th>Username</th>
                            <th>Nama Lengkap</th>
                            <th>Level</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th>Username</th>
                            <th>Nama Lengkap</th>
                            <th>Level</th>
                            <th>Aksi</th>
                        </tr>
                    </tfoot>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>