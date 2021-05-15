<? defined('BASEPATH') OR exit('No direct script access allowed.') ?>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Logo & Icon</h6>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('admin/konfigurasi/logo') ?>" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?= $this->security->get_csrf_hash() ?>">

                        <div class="form-group">
                            <label>Logo</label><br>
                            <img id="image-preview-logo" src="<?= base_url("assets/img/{$webConfig->logo}") ?>" alt="Preview Logo Website" class="rounded mb-4" style="height: 8.5rem;">
                            
                            <input type="file" name="logo" class="form-control">
                            <small class="text-muted font-italic">* Ekstensi yang diperbolehkan ialah jpg, png, ico, svg.</small>
                        </div>

                        <div class="form-group">
                            <label>Icon</label><br>
                            <img id="image-preview-icon" src="<?= base_url("assets/img/{$webConfig->icon}") ?>" alt="Preview Icon Website" class="rounded mb-4" style="height: 8.5rem;">
                            
                            <input type="file" name="icon" class="form-control">
                            <small class="text-muted font-italic">* Ekstensi yang diperbolehkan ialah jpg, png, ico, svg.</small>
                        </div>

                        <div class="mt-4 text-right">
                            <button type="reset" class="btn btn-danger">Reset</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Lainnya</h6>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('admin/konfigurasi/lainnya') ?>" method="post">
                        <input type="hidden" name="csrf_token" value="<?= $this->security->get_csrf_hash() ?>">

                        <div class="form-group">
                            <label>Judul</label>
                            <textarea name="judul" rows="5" class="form-control" placeholder="Masukkan judul website"><?= $webConfig->judul ?></textarea>
                        </div>

                        <div class="form-group">
                            <label>Deskripsi</label>
                            <textarea name="deskripsi" rows="5" class="form-control" placeholder="Masukkan deskripsi website" required><?= $webConfig->deskripsi ?></textarea>
                        </div>

                        <div class="form-group">
                            <label>Keyword</label>
                            <textarea name="keyword" rows="5" class="form-control" placeholder="Masukkan keyword" required><?= $webConfig->keyword ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Pajak (%)</label>
                                    <input type="number" name="pajak" class="form-control" placeholder="Masukkan biaya pajak (dalam persentase)" value="<?= $webConfig->pajak?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status Website</label>
                                    <select name="website" class="form-control" required>
                                        <option value="AKTIF" <?= select_opt($webConfig->website, 'AKTIF') ?>>AKTIF</option>
                                        <option value="MAINTENANCE" <?= select_opt($webConfig->website, 'MAINTENANCE') ?>>MAINTENANCE</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 text-right">
                            <button type="reset" class="btn btn-danger">Reset</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>