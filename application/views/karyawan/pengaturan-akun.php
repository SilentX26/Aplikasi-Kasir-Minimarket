<? defined('BASEPATH') OR exit('No direct script access allowed.') ?>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4 mb-md-0">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Umum</h6>
                </div>
                <div class="card-body">
                    <form method="post" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?= $this->security->get_csrf_hash() ?>">
                        <input type="hidden" name="action" value="umum">

                        <div class="form-group">
                            <label>Foto Profil</label><br>
                            <img id="image-preview-foto-profile" src="<?= base_url("assets/img/akun/{$user->data->foto_profile}") ?>" class="rounded mb-4" style="height: 8.5rem;">
                            <input type="file" name="foto-profile" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" class="form-control" value="<?= $user->username ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control" value="<?= $user->data->nama_lengkap ?>" required>
                        </div>

                        <div class="form-group mt-4">
                            <label>Konfirmasi Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Masukkan password akun anda" required>
                        </div>

                        <div class="mt-4 text-right">
                            <button type="reset" class="btn btn-danger">Reset</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
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

                        <div class="form-group mt-4">
                            <label>Konfirmasi Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Masukkan password akun anda" required>
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