<? defined('BASEPATH') OR exit('No direct script access allowed.') ?>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Umum</h6>
                </div>
                <div class="card-body">
                    <form method="post" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?= $this->security->get_csrf_hash() ?>">
                        <input type="hidden" name="action" value="umum">

                        <div class="form-group">
                            <label>Foto Profil</label><br>
                            <img id="image-preview-foto-profile" src="<?= base_url("assets/img/akun/{$data_akun->data->foto_profile}") ?>" alt="Preview Foto Profile" class="rounded mb-4" style="height: 8.5rem;">
                            
                            <input type="file" name="foto-profile" class="form-control">
                            <small class="text-muted font-italic">* Resolusi yang disarankan: 32px X 32px</small>
                        </div>

                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" class="form-control" value="<?= $data_akun->username ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control" value="<?= $data_akun->data->nama_lengkap ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Level</label>
                            <select name="level" class="form-control" required>
                                <option value="Karyawan" <?= select_opt($data_akun->data->level, 'Karyawan') ?>>Karyawan</option>
                                <option value="Admin" <?= select_opt($data_akun->data->level, 'Admin') ?>>Admin</option>
                            </select>
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
                    <h6 class="m-0 font-weight-bold text-primary">Password</h6>
                </div>
                <div class="card-body">
                    <form method="post">
                        <input type="hidden" name="csrf_token" value="<?= $this->security->get_csrf_hash() ?>">
                        <input type="hidden" name="action" value="password">

                        <div class="form-group">
                            <label>Password Baru</label>
                            <input type="password" name="new_password" class="form-control <?= invalid_validation('new_password') ?>" placeholder="Diisi jika ingin diubah" required>

                            <?= form_error('new_password', '<small class="form-text text-danger font-italic">', '</small>') ?>
                        </div>

                        <div class="form-group">
                            <label>Konfirmasi Password Baru</label>
                            <input type="password" name="new_password_2" class="form-control <?= invalid_validation('new_password_2') ?>" placeholder="Diisi jika ingin diubah" required>

                            <?= form_error('new_password_2', '<small class="form-text text-danger font-italic">', '</small>') ?>
                        </div>

                        <div class="mt-4 text-right">
                            <button type="reset" class="btn btn-danger">Reset</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 mt-4 text-center">
            <a href="<?= base_url('admin/akun') ?>" class="text-decoration-none">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>
    </div>