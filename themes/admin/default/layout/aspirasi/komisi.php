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
            <h4 class="card-title">Daftar Aspirasi Masyarakat Pada KOmisi A</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover data-table">
                    <thead class="load">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">User</th>
                            <th scope="col">Message</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody class="daftar-aspirasi">

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

    <div class="modal fade" id="lihat" tabindex="-1" role="dialog" aria-labelledby="#modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Pesan Aspirasi</b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body content-aspirasi">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-testimonial border">
                                <div class="icon">
                                    <i class="material-icons">format_quote</i>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-description">

                                    </h5>
                                </div>
                                <div class="card-footer clear">
                                    <h4 class="card-title">Alec Thompson</h4>
                                    <div class="card-avatar">
                                        <a href="#pablo">
                                            <img class="img image" src="">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="post-complaint mt-5">
                            <div class="complaint-meta d-block w-100">
                                <span class="meta-info tanggapi" data-url='4'>Tanggapi</span>
                                <span class="meta-info getInfo" id="tanggapan" data-role="2" data-url="">Tanggapan</span>
                                <span class="meta-info getInfo" id="komentar" data-role="3" data-url="">Komentar</span>
                            </div>
                        </div>
                        <div class="complaint-content">
                            <!-- Contenedor Principal -->
                            <div class="container">
                                <div class="row">
                                    <!-- <h1>Comentarios <a href="http://creaticode.com">creaticode.com</a></h1> -->
                                    <ul id="comments-list" class="comments-list w-100">
                                    </ul>
                                </div>
                            </div>
                            <div class="default my-3 alert alert-danger" role='alert'>
                                Mohon maaf jika ada kesalahan dalam sistem kami. </br> silahakan anda melaporkannya agar kami bisa memperbaikinya
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    <script>
        var dftAspirasi = $("tbody.daftar-aspirasi");
        var baseUrl = "<?= base_url() ?>";
        const user = <?= json_encode($user) ?>;
        var urlLoadAspirasi = baseUrl + "api/aspirasi?id_komisi=<?= $komisi['komisi_id'] ?>"
        var tr = null;
    </script>
    <script src="<?= $thema_folder ?>layout/aspirasi/komisi.js"></script>

    <script src="<?= $thema_folder ?>assets/js/plugins/jquery.validate.min.js"></script>


</body>

</html>