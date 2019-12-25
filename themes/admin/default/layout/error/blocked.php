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
    <div class="row">
        <div class="col-md-2 text-center">
            <p><i class="fa fa-exclamation-triangle fa-5x"></i><br />Status Code: 403</p>
        </div>
        <div class="col-md-10">
            <h3>OPPSSS!!!! Sorry...</h3>
            <p>Sorry, your access is refused due to security reasons of our server and also our sensitive data.<br />Please go back to the previous page to continue browsing.</p>
            <a class="btn btn-danger" href="javascript:history.back()">Go Back</a>
        </div>
    </div>
    <!-- end isi -->

    <?php
    $this->load->view($thema_load . 'element/template/footer.php');
    ?>
    <?php
    $this->load->view($thema_load . 'element/template/fixed-setting.php');
    ?>
</body>

</html>