<?php $this->load->view($thema_load . 'template/header.php'); ?>
<!-- Home Title -->
<header class="masthead">
  <div class="container">
    <div class="intro-text">
      <div class="intro-lead-in"><?= @$page['title']; ?></div>
      <div class="intro-heading text-uppercase">DPRD Makassar</div>
      <?php if ($this->router->fetch_class() == "home" && "index" == $this->router->fetch_method()) : ?>
        <a class="btn btn-primary btn-xl text-uppercase js-scroll-trigger" data-toggle="modal" data-target="#laporModal" href="<?= base_url('user/lapor') ?>">Laporkan!</a>
      <?php endif; ?>
    </div>
  </div>
</header>
<!-- panduan -->
<section class="page-section" id="panduan">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 text-center">
        <h2 class="section-heading text-uppercase">Panduan</h2>
        <h3 class="section-subheading text-muted">Tata Cara dalam melakukan atau mengirim aspirasi anda.!</h3>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <ul class="timeline">
          <li>
            <div class="timeline-image">
              <img class="rounded-circle img-fluid" src="<?= $thema_folder; ?>assets/img/about/1.jpg" alt="">
            </div>
            <div class="timeline-panel">
              <div class="timeline-heading">
                <h4>(1) Pertama</h4>
                <h4 class="subheading">Tulis Laporan</h4>
              </div>
              <div class="timeline-body">
                <p class="text-muted">Silahakan tulis atau ketikkan aspirasi anda pada kolom inputan yang tersedia .! </br>Laporkan keluhan atau aspirasi anda dengan jelas dan lengkap!</p>
              </div>
            </div>
          </li>
          <li class="timeline-inverted">
            <div class="timeline-image">
              <img class="rounded-circle img-fluid" src="<?= $thema_folder; ?>assets/img/about/2.jpg" alt="">
            </div>
            <div class="timeline-panel">
              <div class="timeline-heading">
                <h4>(2) Kedua</h4>
                <h4 class="subheading">Proses Validasi</h4>
              </div>
              <div class="timeline-body">
                <p class="text-muted">Aspirasi anda akan di validasi dan akan di teruskan ke komisi yang berwenang!</p>
              </div>
            </div>
          </li>
          <li>
            <div class="timeline-image">
              <img class="rounded-circle img-fluid" src="<?= $thema_folder; ?>assets/img/about/3.jpg" alt="">
            </div>
            <div class="timeline-panel">
              <div class="timeline-heading">
                <h4>(3) Ketiga</h4>
                <h4 class="subheading">Tahap Menunggu</h4>
              </div>
              <div class="timeline-body">
                <p class="text-muted">Anda akan menunggu tindak lanjut dari komisi yang bersangkutan!</p>
              </div>
            </div>
          </li>
          <li class="timeline-inverted">
            <div class="timeline-image">
              <img class="rounded-circle img-fluid" src="<?= $thema_folder; ?>assets/img/about/4.jpg" alt="">
            </div>
            <div class="timeline-panel">
              <div class="timeline-heading">
                <h4>(4) Keempat</h4>
                <h4 class="subheading">Menanggapi</h4>
              </div>
              <div class="timeline-body">
                <p class="text-muted">Setelah menerima tanggapan, anda bisa menanggapi balik atas balasan yang dikirim oleh komisi!</p>
              </div>
            </div>
          </li>
          <li class="timeline-inverted">
            <div class="timeline-image">
              <h4>(End)
                <br>Selesai!</h4>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</section>


<!-- about -->
<section class="page-section" id="about">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 text-center">
        <h2 class="section-heading text-uppercase">Apa itu Aspirasi Masyarakat.?</h2>
        <h3 class="section-subheading text-muted">penjelesan dan pengertian.</h3>
      </div>
    </div>
    <div class="row text-center">
      <div class="col-md-12">
        <span class="fa-stack fa-4x">
          <i class="fas fa-circle fa-stack-2x text-primary"></i>
          <i class="fas fa-people-carry fa-stack-1x fa-inverse"></i>
        </span>
        <h4 class="service-heading">Aspirasi Masyarakat</h4>
        <p class="text-muted">Dalam menerima laporan atau aspirasi masyarakat secara online dan terbuka , maka di buatlah WEB ini guna untuk mempermudah masyarakat dalam mengirim aspirasinya ke instansi pemerintah DPRD kota Makassar, yang kemudian akan di teruskan ke bagia komisi yang bersangkutan.</br> Masyarakat yang melakukan aspirasi akan meneirma tanggapan dari komisi yang bersangkutan lewat akun masyarakat tsb.</p>
      </div>
      <div class="col-md-6">
        <span class="fa-stack fa-4x">
          <i class="fas fa-circle fa-stack-2x text-primary"></i>
          <i class="fas fa-paper-plane fa-stack-1x fa-inverse"></i>
        </span>
        <h4 class="service-heading">Aspirasi</h4>
        <p class="text-muted">Merupakan harapan perubahan yang lebih baik dengan tujuan untuk meraih keberhasilan di masa depan.</p>
      </div>
      <div class="col-md-6">
        <span class="fa-stack fa-4x">
          <i class="fas fa-circle fa-stack-2x text-primary"></i>
          <i class="fas fa-users fa-stack-1x fa-inverse"></i>
        </span>
        <h4 class="service-heading">Masyarakat</h4>
        <p class="text-muted">adalah sekelompok orang yang membentuk sebuah sistem semi tertutup (atau semi terbuka), di mana sebagian besar interaksi adalah antara individu-individu yang berada dalam kelompok tersebut..</p>
      </div>
    </div>
  </div>
</section>

<!-- Central Modal Small -->


<?php $this->load->view($thema_load . 'template/footer.php'); ?>
<script src="<?= base_url() ?>assets/js/vendor/jquery.validate.min.js"></script>
<script>
  // validate modal

  jQuery.validator.setDefaults({
    errorElement: 'span',
    errorPlacement: function(error, element) {
      error.addClass('invalid-feedback');
      element.closest('.form-group').append(error);
    },
    highlight: function(element, errorClass, validClass) {
      $(element).addClass('is-invalid');
    },
    unhighlight: function(element, errorClass, validClass) {
      $(element).removeClass('is-invalid');
    }
  });
  $(function() {
    $("#formInput").validate({
      rules: {
        message: {
          required: true,
          minlength: 12
        },
        action: "required"
      },
      messages: {
        pName: {
          required: "Please enter some data",
          minlength: "Your data must be at least 12 characters"
        },
        action: "Please provide some data"
      }
    });
  });
</script>



</body>

</html>