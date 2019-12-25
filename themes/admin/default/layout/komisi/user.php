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
            <a href="" id="addUser" class="btn btn-primary mb-3 mdl add">Add User</a>
            <div class=" table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Image</th>
                            <th scope="col">Nama</th>
                            <th scope="col">email</th>
                            <th scope="col">Komisi</th>
                            <th scope="col">Status</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        <?php foreach ($komisi_user->result_array() as $row) : ?>
                            <tr>
                                <th scope="row"><?= $i; ?></th>
                                <td><img src="<?= getThumb("profile_" . $row['file']) ?>" alt="" />
                                </td>
                                <td><?= $row['name']; ?></td>
                                <td><?= $row['email']; ?></td>
                                <?php $komisi = cekInAccessKomisi($row['id_user']) ?>
                                <td>
                                    <?= ($komisi['name'] != null) ? "<span class='badge badge-success'>" . $komisi['name'] . "</span>" : "<a href='" . base_url("admin/komisi/setUser/") . "' data-url='" . base_url('admin/komisi/setkomisi/') . "' data-id_user='" . $row["id_user"] . "' class='setUser " . $row["id_user"] . "' data-toggle='modal' data-target='.modal#setuser'><span class='badge badge-danger'>Sets</span></a>"; ?>
                                <td>
                                    <div class="togglebutton">
                                        <label>
                                            <input class="userActive <?= $row['id_user']; ?>" type="checkbox" <?= ($row['is_active'] == 1) ? "Checked=checked" : "" ?> data-userid="<?= $row['id_user']; ?>" data-active="<?= $row['is_active'] ?>">
                                            <span class="toggle"></span>
                                            <span class="label <?= $row['id_user']; ?> mt-2"><?= $row['is_active'] ? "Active" : "Non Active"; ?></span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <a href="" class="mdl btn btn-danger" id="delete" data-toggle="modal" data-url="<?= base_url('admin/user/delete/') . $row['id_user']; ?>" data-target=".modal#logout"><i class="material-icons">close</i>Delete<div class="ripple-container"></div></a>
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
    <!-- jqery required -->

    <script>
        $('.userActive').on('click', function() {
            const userId = $(this).data('userid');
            const is_active = $(this).data('active');
            $.ajax({
                url: "<?= base_url('admin/komisi/userSet'); ?>",
                type: 'post',
                dataType: 'json',
                data: {
                    id_user: userId,
                    is_active: is_active == 1 ? 0 : 1
                },
                beforeSend: function() {
                    $("#loader-wrapper").show();
                },
                success: function(e) {
                    $("#loader-wrapper").hide();
                    if (e.status) {
                        console.log("sukses");
                        console.log(e.data.is_active);
                        $(".userActive." + userId).data('active', e.data.is_active)
                        const lbl = (e.data.is_active == 1) ? "active" : "Non Active";
                        $(".label." + userId).text(lbl);
                    } else {
                        console.log("Gagal");
                    }
                }
            });

        });
        $(".setUser").on('click', function() {
            const userId = $(this).data("id_user");
            const url = $(this).data("url");
            $(".input.user-id").val(userId);
            $(".form.setKomisi").attr("action", url);
            console.log(url);

        })
    </script>


</body>

</html>