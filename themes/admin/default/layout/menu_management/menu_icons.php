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
            <h4 class="card-title">Daftar Icons</h4>
        </div>
        <div class="card-body">
            <a href="" class="btn btn-primary mb-3" id="addIconMenu" data-toggle="modal" data-url="" <?= base_url('admin/menu/submenu'); ?>" data-target="#newIconsModal">Add New Icon</a> <button href="" class="btn btn-warning mb-3" id="editIcon" data-toggle="modal" data-url="<?= base_url('admin/icon/edit/'); ?>" data-target="#newIconsModal" disabled>Edit</button> <button class="btn btn-danger mb-3" id="deleteIcon" data-toggle="modal" data-url="<?= base_url('admin/icon/delete/'); ?>" data-target="#logout" disabled>Delete</button>
            <div class="row">
                <?php $i = 1; ?>
                <?php
                foreach ($this->db->get('tbl_icons')->result_array() as $icon) : ?>
                    <?php if ($i == 6) {
                            echo "</tr><tr>";
                            $i = 1;
                        } ?>
                    <div class="icon icon-container mb-2" data-id="<?= $icon['id']; ?>" data-kategori="<?= $icon['kategori']; ?>" data-name="<?= $icon['name']; ?>" data-code="<?= cetak($icon['code']); ?>">
                        <div class="icon-container-inner">
                            <div class='icon-preview '>
                                <?= $icon['code']; ?>
                            </div>
                            <div class="icon-text"><input type="radio" id="icon" name="icon" value='<?= htmlspecialchars($icon["id"]); ?>' class="d-none" /><?= $icon['name'] ?></div>
                        </div>
                    </div>
                <?php endforeach;
                $i++; ?>
            </div>
        </div>
    </div>

    <?php
    $this->load->view($thema_load . 'element/template/footer.php');
    ?>
    <?php
    $this->load->view($thema_load . 'element/template/fixed-setting.php');
    ?>

    <!-- Modal -->
    <div class="modal fade" id="newIconsModal" tabindex="-1" role="dialog" aria-labelledby="newSubMenuModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newSubMenuModalLabel">Add New Sub Menu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="<?= base_url('admin/icon'); ?>" method="post" id="formInput">
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Input Name title">
                        </div>

                        <div class="form-group">
                            <input type="text" class="form-control" id="code" name="code" placeholder="Input Full code icons">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="kategori" name="kategori" placeholder="Kategori (ex. awesome/material)">
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

    <script src="<?= $thema_folder ?>assets/js/plugins/jquery.validate.min.js"></script>

    <script>
        const icon = $('.icon-container');
        const ceklist = $('input[name=icon]');
        var name, code, kategori, icon_id;
        var form_url, form_title, form_submit, base_url = "<?= base_url('admin/icon/') ?>";

        function newform() {
            $('#formInput').attr('action', form_url).parent().find("#newSubMenuModalLabel").html(form_title);
            $('#formInput').find('.submit').html(form_submit);
        }

        $('#addIconMenu').click(function() {
            form_submit = "Add";
            form_title = 'Add New Icon';
            $('#formInput').find("#name").val('').parent().removeClass('is-filled');
            $('#formInput').find("#kategori").val('').parent().removeClass('is-filled');
            $('#formInput').find("#code").val('').parent().removeClass('is-filled');
            form_url = base_url;
            newform();
        });
        $('#editIcon').click(function() {
            form_submit = "Edit Icon";
            form_title = 'Edit Icons Menu';
            form_url = base_url + "edit/" + icon_id;
            $('#formInput').find("#name").val(name).parent().addClass('is-filled');
            $('#formInput').find("#kategori").val(kategori).parent().addClass('is-filled');
            $('#formInput').find("#code").val(code).parent().addClass('is-filled');
            newform();
        });
        $("#deleteIcon").click(function() {
            $("#logout").find(".modal-body").html("Silahkan tekan Delete untuk hapus Icons, dan cancel untuk membatalkan");
            $("#logout").find(".modal-footer").children("a").attr("href", "<?= base_url('admin/icon/delete/'); ?>" + icon_id).text("Delete");
        })


        icon.each(function(index) {
            $(this).click(function() {
                $("#deleteIcon").removeAttr("disabled").button('refresh');
                $("#editIcon").removeAttr("disabled").button('refresh');
                icon_id = $(this).data('id');
                name = $(this).data('name');
                code = $(this).data('code');
                kategori = $(this).data('kategori');
                icon.removeClass('active');
                ceklist.removeAttr('checked');
                $(this).find('input[type=radio]').attr('checked', 'checked');
                $(this).addClass("active");
            })
        });
        $(function() {
            $("#formInput").validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 5
                    },
                    kategori: {
                        required: true
                    },
                    code: {
                        required: true,
                        minlength: 5
                    }
                }
            });
        });
    </script>
</body>

</html>