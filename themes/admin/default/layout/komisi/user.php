<!DOCTYPE html>
<html lang="en">
<?php
$this->load->view($thema_load . 'element/template/head_meta.php');
?>

<body class="">
    <!-- sidebar   -->
    <?php $this->load->view($thema_load . 'element/template/sidebar.php'); ?>
    <!-- end sidebara -->

    <?php
    $this->load->view($thema_load . 'element/template/navbar.php');
    ?>

    <!-- isi content -->
    <div class="card">
        <div class="card-header card-header-primary card-header-icon">
            <div class="card-icon">
                <i class="material-icons">assignment</i>
            </div>
            <h4 class="card-title">Daftar User pada Komisi</h4>
        </div>
        <div class="card-body">
            <a href="#" data-name="evAdd" data-toggle="modal" data-target=".modal#addUser" class="btn btn-primary mb-3">Add User</a>
            <div class=" table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Image</th>
                            <th scope="col">Nama</th>
                            <th scope="col">email</th>
                            <th scope="col">Jabatan</th>
                            <th scope="col">Komisi</th>
                            <th scope="col">Status</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        <?php foreach ($komisi_user as $row) : ?>
                            <tr class="user-id" data-iduser="<?= $row['id_user']; ?>">
                                <th class='number'><?= $i; ?></th>
                                <td><img src="<?= getThumb($row['image'], "profile_") ?>" alt="" style="width:60px;height:60px" />
                                </td>
                                <td data-name="name" data-set="<?= $row['name']; ?>"><?= $row['name']; ?></td>
                                <td data-name="email" data-set="<?= $row['email']; ?>"><?= $row['email']; ?></td>
                                <td id="jabatan" data-set="<?= $row['jabatan']; ?>"><?= $row['jabatan']; ?></td>

                                <td id="komisiId" data-set="<?= $row['komisi_id'] ?>">
                                    <span data-komisiName="<?= $row['komisi_name'] ?>" class='badge badge-success'><?= $row['komisi_name'] ?></span>
                                    <a href="<?= base_url("admin/komisi/setUser/") ?>" data-toggle='modal' class='badge badge-danger' data-target='.modal#addUser' evChange>Ganti</a>
                                <td>
                                    <div class="togglebutton" data-name="dapil_id" data-set="<?= $row["dapil_id"] ?>">
                                        <label data-name="noHp" data-set="<?= $row['no_hp'] ?>">
                                            <input class="userActive" evActive type="checkbox" <?= ($row['is_active'] == 1) ? "Checked=checked" : "" ?>>
                                            <span class="toggle" data-name="alamat" data-set="<?= $row['alamat'] ?>"></span>
                                            <span class="label <?= $row['id_user']; ?> mt-2"><?= $row['is_active'] ? "Active" : "Non Active"; ?></span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <a href="<?= base_url('admin/komisi/user/delete/') . $row['user_id'] ?>" class="mdl btn btn-danger"><i class="material-icons">close</i>Delete<div class="ripple-container"></div></a>
                                </td>
                            </tr>
                            <?php $i++; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- end isi -->

    <?php
    $this->load->view($thema_load . 'element/template/footer.php');
    ?>
    <script src="<?= $thema_folder ?>assets/js/plugins/bootstrap-selectpicker.js"></script>

    <?php
    $this->load->view($thema_load . 'element/template/fixed-setting.php');
    ?>




    <div class="modal fade" id="addUser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-notice">
            <div class="modal-content user">
                <div class="modal-header">
                    <h5 class="modal-title" data-name='title' id="myModalLabel">Tambah User.!</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <i class="material-icons">close</i>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form setKomisi" action="" method="post" enctype="multipart/form-data">
                        <div class="form-group text-center image m-0">
                            <input type="file" atImg id="image" name='image' accept="image/*" />

                            <label for="image" id='lblImage'><img data-name='image' src="<?= base_url() ?>assets/img/thumbnail/default.png" class="profile" />
                                <span class="btn" id="text">Ganti</span>
                            </label>
                        </div>
                        <input type="hidden" name="role_id" id="role_id" value="2" class="">
                        <input type="hidden" name="is_active" value="1" id="is_active" class="">
                        <div class="form-group bmd-form-group">
                            <label class="bmd-label-floating">Nama Anggota</label>
                            <input type="text" class="form-control" name="name" id="name">
                        </div>
                        <div class="form-group bmd-form-group">
                            <label class="bmd-label-floating">Email</label>
                            <input type="text" name="email" id="email" class="form-control">
                        </div>
                        <div class="form-group bmd-form-group">
                            <label class="bmd-label-floating">Set Password</label>
                            <input type="password" name="password" id="password" class="form-control">
                        </div>
                        <div class="form-group bmd-form-group">
                            <label class="bmd-label-floating">No Handphone</label>
                            <input type="text" name="no_hp" id="no_hp" class="form-control">
                        </div>
                        <div class="form-group bmd-form-group">
                            <label class="bmd-label-floating">Alamat</label>
                            <input type="text" name="alamat" id="alamat" class="form-control">
                        </div>

                        <div class="form-group">
                            <select class="custom-select" name="dapil_id">
                                <option selected value="">Pilih Dapil</option>
                                <?php foreach ($all_dapil as $row) : ?>
                                    <option data-name="<?= $row['id_dapil'] ?>" value="<?= $row['id_dapil'] ?>"><?= $row['dapil'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <select class="custom-select" name="jabatan">
                                <option selected value="">Pilih Jabatan</option>
                                <option data-name="Ketua" value="Ketua">Ketua</option>
                                <option data-name="Wakil Ketua" value="Wakil Ketua">Wakil Ketua</option>
                                <option data-name="Sekertaris" value="Sekertaris">Sekertaris</option>
                                <option data-name="Anggota" value="Anggota">Anggota</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <select class="custom-select" name="komisi_id">
                                <option selected value="">Pilih Komisi</option>
                                <?php foreach ($all_komisi as $row) : ?>
                                    <option data-name="<?= $row['id_komisi'] ?>" value="<?= $row['id_komisi'] ?>"><?= $row['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <input evAddUser class="btn btn-primary" type="submit" value="Tambahkan">
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-info btn-round" data-dismiss="modal">Tutup!</button>
                </div>
            </div>
        </div>
    </div>
    <script src="<?= $thema_folder; ?>layout/komisi/user.js" type="text/javascript"></script>


    <div class="modal fade" id="setuser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-notice">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Silahkan anda setting User ke komisi yang dinginkan.!</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <i class="material-icons">close</i>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form setKomisi" action="" method="post">
                        <input type="hidden" class="input user-id" value="" name="user_id">
                        <div class="form-group">
                            <select class="custom-select" name="komisi_id">
                                <option selected>Open this select menu</option>
                                <?php foreach ($all_komisi->result_array() as $row) : ?>
                                    <option value="<?= $row['id_komisi'] ?>"><?= $row['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <input class="btn btn-primary" type="submit" value="Set Now">
                        </div>
                    </form>
                    <p>Hapy Coding.!</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-info btn-round" data-dismiss="modal">Tutup!</button>
                </div>
            </div>
        </div>
    </div>




</body>

</html>