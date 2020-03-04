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
            <h4 class="card-title">Daftar Aspirasi Masyarakat</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="load">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">User</th>
                            <th scope="col">Message</th>
                            <th scope="col">Komisi</th>
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


    <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="#modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Edit Aspirasi Dari User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="post" id="formInput">
                    <div class="modal-body">
                        <div class="form-group mb-4">
                            <select class="form-control" data-style="btn btn-link" id="komisi_id" name="komisi_id">
                                <?php foreach ($all_komisi as $row) : ?>
                                    <option value="<?= $row['id_komisi'] ?>"><?= $row['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group mt-5">
                            <label for="message">Pesan Aspirasi</label>
                            <textarea class="form-control" name="message" id="message" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary submit">Edit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="tampil" tabindex="-1" role="dialog" aria-labelledby="#modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-notice modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Detail Aspirasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="card">
                    <div class="card-header card-header-rose card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">assignment</i>
                        </div>
                        <h4 class="card-title">Perhitungan Text Mining dan Cosine Similarity</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="text-center" rowspan="2">Aspirasi</th>
                                        <th colspan="4" class="text-center">komisi</th>
                                    </tr>
                                    <tr>
                                        <th>A</th>
                                        <th>B</th>
                                        <th>C</th>
                                        <th>D</th>
                                    </tr>
                                </thead>
                                <tbody class="detail-aspirasi">

                                </tbody>
                                <tfoot class="hasil">
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var dftAspirasi = $("tbody.daftar-aspirasi");
        var tr = null;
    </script>
    <script src="<?= $thema_folder ?>layout/aspirasi/admins.js"></script>
</body>

</html>