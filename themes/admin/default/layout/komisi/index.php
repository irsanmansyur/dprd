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
            <h4 class="card-title">Daftar Komisi</h4>
        </div>
        <div class="card-body">
            <a href="" data-url="<?= base_url('admin/komisi'); ?>" id="addKomisi" class="btn btn-primary mb-3 mdl add" data-toggle="modal" data-target=".modal#komisi">Add New Komisi</a>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        <?php foreach ($all_komisi as $row) : ?>
                            <tr>
                                <th scope="row"><?= $i; ?></th>
                                <td><?= $row['name']; ?></td>
                                <td>
                                    <a href="<?= base_url('admin/label/') . $row['id_komisi']; ?>" data-id="<?= $row['id_komisi'] ?>" class="badge badge-primary" id="label">Show Label</a>
                                    <a href="" data-name="<?= $row['name'] ?>" data-url="<?= base_url('admin/komisi/edit/') . $row['id_komisi']; ?>" class="badge badge-success mdl" id="edit" data-toggle="modal" data-target=".modal#komisi">edit</a>
                                    <a href="" class="mdl badge badge-danger" id="delete" data-toggle="modal" data-url="<?= base_url('admin/komisi/delete/') . $row['id_komisi']; ?>" data-target=".modal#logout">delete</a>
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
    <?php
    $this->load->view($thema_load . 'element/template/fixed-setting.php');
    ?>


    <!-- modal dan scrip code -->
    <!-- Modal -->
    <div class="modal fade" id="komisi" tabindex="-1" role="dialog" aria-labelledby="#modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Add New Menu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="<?= base_url('admin/komisi'); ?>" method="post" id="formInput">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="#komisi" class="bmd-label-floating">Nama Komisi .*</label>
                            <input type="text" class="form-control input name" id="komisi" required="true" name="name">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary submit">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- jqery required -->
    <!-- <script src="<?= $thema_folder ?>assets/js/plugins/jquery.validate.min.js"></script> -->
    <script src="<?= $thema_folder ?>assets/js/plugins/jquery.validate.min.js"></script>

    <script>
        $('.mdl.add').on('click', function() {
            const url_add = $(this).data('url');
            $('#formInput').attr('action', url_add).parent().find("#modalLabel").html('Add Komisi');
            $('#formInput').find("button.submit").html('Add Komisi');
            $('#formInput').find(".input.name").val('').parent().removeClass('is-filled');
        });
        $('.mdl#edit').each(function(index) {
            $(this).on('click', function() {
                const val_name = $(this).data('name');
                const url_edit = $(this).data('url');
                $('#formInput').attr('action', url_edit).parent().find("#modalLabel").html('Edit Komisi');
                $('#formInput').find("button.submit").html('Edit Komisi');
                $('#formInput').find(".input.name").val(val_name).parent().addClass('is-filled');
            });
        })
        $('.mdl#delete').each(function() {
            $(this).on('click', function() {
                const url_delete = $(this).data('url');
                console.log(url_delete);
                $(".modal#logout").find('.btn.url').attr('href', url_delete).html("Delete");
                $(".modal#logout").find(".modal-title").html("Jika Anda menghapus maka data yang berkaitan akan terhapus juga");
            });
        })



        // validate modal
        $(function() {
            $("#formInput").validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 4
                    },
                    action: "required"
                },
                messages: {
                    name: {
                        required: "Please enter some data",
                        minlength: "Your data must be at least 4 characters"
                    },
                    action: "Please provide some data"
                }
            });
        });
    </script>



</body>

</html>