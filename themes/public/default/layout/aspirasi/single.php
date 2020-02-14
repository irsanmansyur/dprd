<?php $this->load->view($thema_load . 'template/header.php'); ?>

<div class="page-header">
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
                <div class="col-md-8" id="accordion">
                    <?php
                    $parent_komentar = array_filter($komentar, function ($kmntr) {
                        if ($kmntr['parent'] === '0' && $kmntr['role_id'] != 2)
                            return TRUE;
                        else
                            return FALSE;
                    });
                    $parent_tanggapan = array_filter($komentar, function ($kmntr) {
                        if ($kmntr['parent'] === '0' && $kmntr['role_id'] == 2)
                            return TRUE;
                        else
                            return FALSE;
                    });
                    ?>
                    <div class="card mb-2">
                        <div class="card-header identitas position-relative" style='padding-left:80px'>
                            <img src="<?= $aspirasi['image'] ?>" style="width: 55px;height:50px;left:15px;top:12px" alt="" class="position-absolute rounded-circle">
                            <h5 class="card-title mb-0"><?= $aspirasi['username'] ?></h5>
                            <span class="text-muted">Di arahkan ke : </span>
                            <span class="komisi font-weight-bold"><?= $aspirasi['komisi'] ?></span>
                            </br><span>Penanggung Jawab :<?= $aspirasi['nmpenanggun'] ?></span>
                            <span class="position-absolute text-muted" style="top:12px;right:15px"><?= date("D, m - Y", $aspirasi['date_created']) ?> </span>
                        </div>
                        <div class="card-body">
                            <p class="card-text text-justify text-monospace"><?= $aspirasi['message'] ?></p>
                            <hr />
                            <div class="complaint-meta">
                                <a class="meta-info mr-2" data-toggle="collapse" href="#tanggapan" role="button" aria-expanded="true" aria-controls="tanggapan"><i class="fas fa-reply"></i> Tanggapan <span class="align-text-bottom"> <?= count($parent_tanggapan) ?></span></a>
                                <a class="meta-info mr-2" data-toggle="collapse" href="#komentar" role="button" aria-expanded="true" aria-controls="komentar"><i class="far fa-comments"></i> Komentar <span class="align-top"><?= count($parent_komentar) ?></span></a>
                            </div>

                            <div class="collapse mt-3" id="tanggapan" data-parent="#accordion">
                                <?php foreach ($parent_tanggapan as $row) : ?>
                                    <div class="card card-body mb-2">
                                        <div class="card-header identitas position-relative" style='padding-left:80px'>
                                            <img src="<?= $row['image'] ?>" style="width: 55px;height:50px;left:15px;top:12px" alt="" class="position-absolute rounded-circle">
                                            <h5 class="card-title mb-0"><?= $row['username'] ?></h5>
                                            <span class="text-muted">Menanggapi : </span>
                                            <span class="position-absolute text-muted" style="top:12px;right:15px"><?= date("D, m - Y", $row['date_created']) ?> </span>
                                        </div>
                                        <div class="card-body">
                                            <p class="card-text text-justify text-monospace"><?= $row['komentar'] ?>.</p>
                                        </div>


                                        <?php
                                        $id = $row['id_komentar'];
                                        $tanggapan_child =  array_filter($komentar, function ($kmntr) use ($id) {
                                            if ($kmntr['parent'] == $id)
                                                return TRUE;
                                            else
                                                return FALSE;
                                        }); ?>

                                        <div class="bl-rp d-flex">
                                            <a class="btn btn-secondary" data-toggle="collapse" href="#balasan_<?= $row['id_komentar'] ?>"><span class="align-top mr-2"><?= count($tanggapan_child) ?> </span> Balasan</a>

                                            <a class="btn btn-primary ml-2" data-toggle="collapse" href="#reply_<?= $row['id_komentar'] ?>" role="button" aria-expanded="true" aria-controls="reply_<?= $row['id_komentar'] ?>">Balas</a>

                                        </div>
                                        <div class="collapse" id="reply_<?= $row['id_komentar'] ?>">
                                            <!-- =balasTanggapan -->
                                            <form class="mt-3 komentar" method="post" action="<?= base_url("aspirasi/send") ?>">
                                                <input type="text" hidden value="<?= $aspirasi['id_aspirasi'] ?>" name="aspirasi_id">
                                                <input type="text" hidden value="<?= $row['id_komentar'] ?>" name="parent">
                                                <div class="form-group mt-3 komentar position-relative">
                                                    <textarea name='komentar' required class="form-control komentar_<?= $row['id_komentar'] ?>" id="add_komentar_<?= $row['id_komentar'] ?>" rows="3"></textarea>
                                                    <label for="add_komentar" class="label-komentar">
                                                        <span class="label-text">Ketikkan Komentar Anda Disini</span></label>
                                                    <input type="submit" value="Kirim" class="btn btn-primary submit-komentar">
                                                </div>
                                            </form>
                                        </div>



                                        <?php foreach ($tanggapan_child as $child) : ?>
                                            <div class="mt-3 ml-3 collapse" id="balasan_<?= $id ?>">
                                                <div class="card-header identitas position-relative mt-3" style='padding-left:80px'>
                                                    <img src="<?= $child['image'] ?>" style="width: 55px;height:50px;left:15px;top:12px" alt="" class="position-absolute rounded-circle">
                                                    <h5 class="card-title mb-0"><?= $child['username'] ?></h5>
                                                    <span class="text-muted">Membalas : </span>
                                                    <span class="card-text text-justify text-monospace"><?= $child['komentar'] ?>.</span>
                                                    <span class="position-absolute text-muted" style="top:12px;right:15px"><?= date("D, m - Y", $child['date_created']) ?></span>
                                                </div>
                                            </div>
                                        <?php endforeach ?>
                                    </div>
                                <?php endforeach ?>
                            </div>

                            <div class="collapse mt-3" id="komentar" data-parent="#accordion">
                                <a class="btn btn-primary my-2" data-toggle="collapse" href="#reply" role="button" aria-expanded="true" aria-controls="reply">Tambah Komentar</a>
                                <div class="collapse mt-3" id="reply">

                                    <!-- ==addKomentar -->

                                    <form class="mt-3 komentar" method="post" action="<?= base_url("aspirasi/send") ?>">
                                        <input type="text" hidden value="<?= $aspirasi['id_aspirasi'] ?>" name="aspirasi_id">
                                        <input type="text" hidden value="0" name="parent">
                                        <div class="form-group mt-3 komentar position-relative">
                                            <textarea name='komentar' required class="form-control komentar_<?= $aspirasi['id_aspirasi'] ?>" id="add_komentar_<?= $aspirasi['id_aspirasi'] ?>" rows="3"></textarea>
                                            <label for="add_komentar_<?= $aspirasi['id_aspirasi'] ?>" class="label-komentar">
                                                <span class="label-text">Ketikkan Komentar Anda Disini</span></label>
                                            <input type="submit" value="Kirim" class="btn btn-primary submit-komentar">
                                        </div>
                                    </form>
                                </div>

                                <?php
                                foreach ($parent_komentar as $row) : ?>
                                    <div class="card card-body mb-2">
                                        <div class="card-header identitas position-relative" style='padding-left:80px'>
                                            <img src="<?= $row['image'] ?>" style="width: 55px;height:50px;left:15px;top:12px" alt="" class="position-absolute rounded-circle">
                                            <h5 class="card-title mb-0"><?= $row['username'] ?></h5>
                                            <span class="text-muted">Berkomentar : </span>
                                            <span class="position-absolute text-muted" style="top:12px;right:15px"><?= date("D, m - Y", $row['date_created']) ?> </span>
                                        </div>
                                        <div class="card-body">
                                            <p class="card-text text-justify text-monospace"><?= $row['komentar'] ?>.</p>
                                        </div>


                                        <?php
                                        $id = $row['id_komentar'];
                                        $komentar_child =  array_filter($komentar, function ($kmntr) use ($id) {
                                            if ($kmntr['parent'] == $id)
                                                return TRUE;
                                            else
                                                return FALSE;
                                        }); ?>

                                        <div class="bl-rp d-flex">
                                            <a class="btn btn-secondary" data-toggle="collapse" href="#balasan_<?= $row['id_komentar'] ?>"><span class="align-top mr-2"><?= count($komentar_child) ?> </span> Balasan</a>

                                            <a class="btn btn-primary ml-2" data-toggle="collapse" href="#reply_<?= $row['id_komentar'] ?>" role="button" aria-expanded="true" aria-controls="reply_<?= $row['id_komentar'] ?>">Balas</a>

                                        </div>

                                        <div class="collapse" id="reply_<?= $row['id_komentar'] ?>">

                                            <!-- ==balasKomentar -->
                                            <form class="mt-3 komentar" method="post" action="<?= base_url("aspirasi/send") ?>">
                                                <input type="text" hidden value="<?= $aspirasi['id_aspirasi'] ?>" name="aspirasi_id">
                                                <input type="text" hidden value="<?= $row['id_komentar'] ?>" name="parent">
                                                <div class="form-group mt-3 komentar position-relative">
                                                    <textarea name='komentar' required class="form-control komentar_<? $row['id_komentar'] ?>" id="add_komentar_<?= $row['id_komentar'] ?>" rows="3"></textarea>
                                                    <label for="add_komentar_<?= $row['id_komentar'] ?>" class="label-komentar">
                                                        <span class="label-text">Ketikkan Komentar Anda Disini</span></label>
                                                    <input type="submit" value="Kirim" class="btn btn-primary submit-komentar">
                                                </div>
                                            </form>

                                        </div>

                                        <?php foreach ($komentar_child as $child) : ?>
                                            <div class="mt-3 ml-3 collapse" id="balasan_<?= $id ?>">
                                                <div class="card-body identitas position-relative mt-3" style='padding-left:80px'>
                                                    <img src="<?= $child['image'] ?>" style="width: 55px;height:50px;left:15px;top:12px" alt="" class="position-absolute rounded-circle">
                                                    <h5 class="card-title mb-0"><?= $child['username'] ?></h5>
                                                    <span class="text-muted">Membalas : </span><span class="card-text text-justify text-monospace"><?= $child['komentar'] ?>.</span>
                                                    <span class="position-absolute text-muted" style="top:12px;right:15px"><?= date("D, m - Y", $row['date_created']) ?> </span>
                                                </div>
                                            </div>
                                        <?php endforeach ?>
                                    </div>
                                <?php endforeach ?>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-md-4 border-left shadow-lg">
                    <div class="content section-title">
                        <h2 class="pb-2 border-bottom">Aspirasi Terbaru</h2>
                        <?php foreach ($aspirasi_baru as $row) : ?>
                            <div class="card mb-2 aspirasi baru" data-idasp="<?= $row['id_aspirasi'] ?>">
                                <div class="lihat"><a href="<?= base_url("aspirasi/id/") . $row['id_aspirasi'] ?>" class="btn bg-utama">Lihat</a></div>
                                <div class="card-header identitas position-relative" style="padding-left:80px">
                                    <img src="<?= $row['image'] ?>" alt="" class="position-absolute rounded-circle cardImg-profile">
                                    <h5 class="card-title mb-0"><?= $row['username'] ?></h5>
                                    <span class="text-muted">Di arahkan ke : </span><span class="komisi font-weight-bold"><?= $row['komisi'] ?></span>
                                    <span class="position-absolute text-muted text-right" style="top:12px;right:15px"><?= date("d/M/Y", $row["date_created"]) ?></span>
                                </div>
                                <div class="card-body">
                                    <p class="card-text text-justify text-monospace"><?= $row['message'] ?>..!</p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="row">

    </div>
</div>
<!-- Modal 1 -->
<?php $this->load->view($thema_load . 'template/footer.php'); ?>
<script src="<?= $thema_folder; ?>layout/aspirasi/single.js"></script>


</body>

</html>