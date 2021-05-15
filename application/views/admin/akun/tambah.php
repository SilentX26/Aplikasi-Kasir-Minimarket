<? defined('BASEPATH') OR exit('No direct script access allowed.') ?>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tambah Akun</h6>
                </div>
                <div class="card-body">
                    <form method="post" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?= $this->security->get_csrf_hash() ?>">

                        <div class="form-group">
                            <label>Foto Profil</label><br>
                            <img id="image-preview-foto-profile" src="" alt="Preview Foto Profile" class="rounded mb-4" style="height: 8.5rem;">
                            
                            <input type="file" name="foto-profile" class="form-control">
                            <small class="text-muted font-italic">* Resolusi yang disarankan: 32px X 32px</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Username</label>
                                    <input type="text" name="username" class="form-control" placeholder="Masukkan username">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama Lengkap</label>
                                    <input type="text" name="nama_lengkap" class="form-control" placeholder="Masukkan nama lengkap">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" name="password" class="form-control" placeholder="Masukkan password">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Konfirmasi Password</label>
                                    <input type="password" name="confirm_password" class="form-control" placeholder="Konfirmasi password">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Level</label>
                            <select name="level" class="form-control" required>
                                <option value="Karyawan">Karyawan</option>
                                <option value="Admin">Admin</option>
                            </select>
                        </div>

                        <div class="mt-4 text-right">
                            <button type="reset" class="btn btn-danger">Reset</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mt-4 text-center">
                <a href="<?= base_url('admin/akun') ?>" class="text-decoration-none">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>