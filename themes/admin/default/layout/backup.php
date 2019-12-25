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
            <h4 class="card-title">Daftar BackUp DB</h4>
        </div>
        <div class="card-body">
            <a href="<?= base_url('admin/admin/backup/new') ?>" class="btn btn-primary mb-3">Tambah BackUp</a>
            <ul>
                <?php
                if (count($db_backup) < 1) {
                    echo "Backup Kosong";
                } else
                    foreach ($db_backup as $row => $value) : ?>
                    <li><?= $value ?> <a href="<?= base_url("admin/admin/backup/download/" . $value) ?>">Download</a> | <a href="<?= base_url("admin/admin/backup/delete/" . $value) ?>">Hapus</a></li>
                <?php endforeach ?>
            </ul>
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

</body>

</html>