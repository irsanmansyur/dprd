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
    <div class="card ">
        <div class="card-header card-header-rose card-header-icon">
            <div class="card-icon">
                <i class="material-icons">contacts</i>
            </div>
            <h4 class="card-title">Pengaturan WEB</h4>
        </div>
        <div class="card-body ">
            <a href="" class="btn btn-primary mb-3" id="addSetting" data-toggle="modal" data-url="" <?= base_url('admin/menu/submenu'); ?>" data-target="#newSubMenuModal">Tambah Setting</a>
            <form class="form form-horizontal" method="post" action="" enctype="multipart/form-data">
                <?php foreach ($setting as $row) : ?>
                    <div class="row">
                        <label class="col-md-3 col-form-label"><?= $row->name ?> <a href="#" data-url="<?= base_url('admin/admin/delete_setting/') . $row->id_setting ?>" class="ml-2 deleteSetting" data-tooltip="tooltip" data-toggle="modal" data-target="#modalDelete" data-placement="top" title="Menghapus Setting"><i class="material-icons">clear</i></a></label>
                        <div class="col-md-9">
                            <?php if ($row->name == "theme_public") { ?>
                                <select class="form-control my-2" id="exampleFormControlSelect1" name="<?= $row->name ?>" id="<?= $row->name ?>">
                                    <?php foreach ($themes_public as $rows => $value) : ?>
                                        <option <?= $row->title == $value ? "selected" : '' ?> value="<?= $value; ?>" class="py-2"><?= $value; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            <?php } else if ($row->name == "theme_admin") { ?>
                                <select class="form-control my-3" id="exampleFormControlSelect2" name="<?= $row->name ?>" id="<?= $row->name ?>">
                                    <?php foreach ($themes_admin as $rows => $value) : ?>
                                        <option <?= $row->title == $value ? "selected" : '' ?> value="<?= $value; ?>"><?= $value; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            <?php } else if ($row->name == "web_logo") { ?>
                                <div class="conatiner">
                                    <div class="card-avatar">
                                        <label for="web_logo"><img class="img" data-name="web_logo" style="width:80px;height:80px;border-radius:50%" src="http://localhost/irsan/dprd/assets/img/setting/<?= $row->title ?>"></label>
                                        <input id="web_logo" type="file" name="web_logo" />
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="form-group has-default bmd-form-group">
                                    <input type="text" name="<?= $row->name ?>" value="<?= $row->title ?>" class="form-control">
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                <!-- <div class="row">
                    <label class="col-md-3"></label>
                    <div class="col-md-9">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" value=""> Remember me
                                <span class="form-check-sign">
                                    <span class="check"></span>
                                </span>
                            </label>
                        </div>
                    </div>
                </div> -->
                <div class="card-footer ">
                    <div class="row">
                        <div class="col-md-9">
                            <button type="submit" class="btn btn-fill btn-rose">Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <!-- /.container-fluid -->

    <!-- End of Main Content -->

    <!-- Modal -->

    <!-- end content -->

    <?php
    $this->load->view($thema_load . 'element/template/footer.php');
    ?>
    <?php
    $this->load->view($thema_load . 'element/template/fixed-setting.php');
    ?>



    <!-- modal dan script -->
    <!-- Modal -->
    <div class="modal fade" id="newSubMenuModal" tabindex="-1" role="dialog" aria-labelledby="newSubMenuModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newSubMenuModalLabel">Tambah aturan baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="<?= base_url('admin/admin/add_setting'); ?>" method="post" id="modalAdd">
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Name Setting">
                        </div>

                        <div class="form-group">
                            <label for="#title" class="bmd-label-floating">Title .*</label>
                            <input type="text" class="form-control" id="title" required="true" name="title">
                        </div>
                        <div class=" form-group">
                            <div class="form-check">
                                <div class="togglebutton">
                                    <label>
                                        <input type="checkbox" checked="" value="1" name="status" id="status">
                                        <span class="toggle"></span>
                                        Is Active ?
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary submit">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Yakin Ingin Hapus ?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Harap berhati hati dalam menghapus Setting, karna dapat menyebabkan kesalahan dalan sistem.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Delete</button>
                    <a class="btn btn-primary deletethis" href="">Hapus</a>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= $thema_folder ?>assets/js/plugins/jquery.validate.min.js"></script>

    <script>
        loadFileJs("assets/js/setting.js");
        $(function() {
            $('[data-tooltip="tooltip"]').tooltip()
        })
        $('.deleteSetting').each(function() {
            $(this).click(() => {
                let url = $(this).data('url');
                $(".deletethis").attr("href", url);
            })
        })
        // add submenu
        $('#addSetting').click(function() {

            $('#formInput').attr('action', $(this).data('url')).parent().find("#newSubMenuModalLabel").html('Add Submenu');
            $('#formInput').find("button.submit").html('Edit Sub Menu');
            $('#formInput').find("#title").val('').parent().removeClass('is-filled');
            $('#formInput').find("#class").val('').parent().removeClass('is-filled');
            $('#formInput').find("#method").val('').parent().removeClass('is-filled');
            $('#formInput').find("#url").val('').parent().removeClass('is-filled');
            $('#formInput').find('option[value=""]').attr('selected', true);
            $('#formInput').find('.submit').html('Add');
            icon.each(function(index) {
                $(this).removeClass("active").find('input[type=radio]').removeAttr('checked');
            });
        });
        // edit modal
        $('.editSubmenu').each(function(index) {
            $(this).click(function() {
                const val_title = $(this).data('title');
                const val_class = $(this).data('class');
                const val_method = $(this).data('method');
                const val_urlEdit = $(this).data('url');
                const val_menu_id = $(this).data('menuid');
                const val_icon = $(this).data('iconid');
                const val_status = $(this).data('isactive');
                const val_submurl = $(this).data('suburl');
                $('#formInput').attr('action', val_urlEdit).parent().find("#newSubMenuModalLabel").html('Edit Submenu');
                $('#formInput').find("button.submit").html('Edit Sub Menu');
                $('#formInput').find("#title").val(val_title).parent().addClass('is-filled');
                $('#formInput').find("#class").val(val_class).parent().addClass('is-filled');
                $('#formInput').find("#method").val(val_method).parent().addClass('is-filled');
                $('#formInput').find("#url").val(val_submurl).parent().addClass('is-filled');
                $('#formInput').find('option[value=' + val_menu_id + ']').attr('selected', true);
                $('#formInput').find('.submit').html('Update');
                icon.each(function(index) {
                    var id_icon = $(this).find('input[type=radio]').val();
                    if (id_icon == val_icon) {
                        $(this).addClass("active").find('input[type=radio]').attr('checked', 'checked');
                    }
                });

            })
        })
        // valdiasi
        // validate modal
        $(function() {
            $("#modalAdd").validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 5
                    },
                    title: "required"
                },
                messages: {
                    name: {
                        required: "Silahkan Masukkan 'name'",
                        minlength: "Input karakter paling tidak 5 karakter"
                    }
                }
            });
        });
    </script>


</body>

</html>