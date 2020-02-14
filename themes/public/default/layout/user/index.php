<?php $this->load->view($thema_load . 'template/header.php'); ?>



<div class="page-header" style="background-image: url('<?= base_url(); ?>/assets/img/setting/background.jpg');background-attachment: fixed;background-position: top center;">
    <div class="container">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="page-caption">
                    <h1 class="page-title"><?= $page['title']; ?></h1>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="card-section">
    <div class="container">
        <div class="rounded bg-white px-3 shadow-lg">
            <div class="row">
                <div class="col-md-6 mr-auto ml-auto">
                    <div class="profile">
                        <div class="avatar">
                            <img src="<?= getThumb($user['image']) ?>">
                        </div>
                        <div class="name">
                            <h3 class="title"><?= $user['name'] ?></h3>
                            <h6><?= $user['email'] ?></h6>
                            <span class="join-in text-italic"><b>Join in</b> <?= date("d, M Y", $user['date_created']) ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-3">

                <a class="btn btn-primary btn-xl text-uppercase js-scroll-trigger" data-toggle="modal" data-target="#laporModal" href="<?= base_url('user/lapor') ?>">Kirim Masukan!</a>
            </div>
            <div class="row mt-3">
                <div class="col-md-8">
                    <div class="section-title">
                        <h2 class="">Daftar Aspirasi</h2>
                        <ul class="nav aspirasi-type nav-pills border-bottom">
                            <li class="nav-item asp" data-status="0">
                                <a class="nav-link text-dark active" href="#">Semua</a>
                            </li>
                            <li class="nav-item asp" data-status="1">
                                <a class="nav-link text-dark" href="#">Ditanggapi</a>
                            </li>
                            <li class="nav-item asp" data-status="2">
                                <a class="nav-link text-dark" href="#">Belum Di Tanggapi</a>
                            </li>
                            <li class="nav-item asp" data-status="3">
                                <a class="nav-link text-dark" href="#">Belum Dibaca</a>
                            </li>
                        </ul>
                    </div>
                    <div id="data-aspirasi">
                    </div>
                </div>
                <div class="col-md-4 border-left shadow-lg">
                    <div class="content section-title" id="asp-terbaru">
                        <h2 class="pb-2 border-bottom">Aspirasi Terbaru</h2>
                    </div>

                </div>
            </div>

        </div>
    </div>
    <div class="row">

    </div>
</div>
</div>

<div class="modal fade" id="modalDeleteAsp" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-notify modal-danger" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="exampleModalLongTitle">Delete Aspirasi</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah Anda Yakin ingin menghapus..!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger waves-effect" data-dismiss="modal">Close</button>
                <button type="button" id="deletedAsp" class="btn btn-danger waves-effect waves-light">Deleted</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal 1 -->

<?php $this->load->view($thema_load . 'template/footer.php'); ?>
<script src="<?= $thema_folder; ?>layout/user/index.js"></script>
</body>

</html>