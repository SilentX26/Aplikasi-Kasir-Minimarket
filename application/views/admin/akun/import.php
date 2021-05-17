<? defined('BASEPATH') OR exit('No direct script access allowed.') ?>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Import Data</h6>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-1"></i> Pastikan koneksi internet anda dalam keadaan stabil, karena jika data yang diimport banyak, maka perlu internet yang memadai juga. Ekstensi yang diizinkan ialah: xls/xlsx.
                        </div>
                    </div>

                    <form method="post" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?= $this->security->get_csrf_hash() ?>">

                        <div class="form-group">
                            <label>File</label>
                            <input type="file" name="file" class="form-control" data-image-preview="false">
                        </div>

                        <div class="mt-4 text-right">
                            <button type="reset" class="btn btn-danger">Reset</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mt-4 text-center">
                <a href="<?= base_url('admin/akun/') ?>" class="text-decoration-none">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>