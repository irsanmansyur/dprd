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
            <h4 class="card-title">Pencarian <?= $this->input->post('search') ?></h4>
        </div>
        <div class="card-body">
            <ul>
                <?php foreach ($sub_menu as $row) : ?>
                    <li><?= $row['title'] ?> <a href="<?= base_url($row['url']) ?>" class="badge badge-primary">Kunjungi</a></li>
                <?php endforeach; ?>
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