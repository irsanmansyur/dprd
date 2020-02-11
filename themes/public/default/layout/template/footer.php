  <!-- Footer -->
  <footer class="footer bg-dark text-white">
      <div class="container">
          <div class="row align-items-center">
              <div class="col-md-4">
                  <span class="copyright">Copyright &copy; <?= $this->setting->site_name ?> 2019</span>
              </div>
              <div class="col-md-4">
                  <ul class="list-inline quicklinks">
                      <li class="list-inline-item">
                          <!-- <a href="#">Privacy Policy</a> -->
                      </li>
                      <li class="list-inline-item">
                          <!-- <a href="#">Terms of Use</a> -->
                      </li>
                  </ul>
              </div>
          </div>
      </div>
  </footer>
  </div>
  <!-- modal keluar -->
  <div class="modal fade" id="logout" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Apaka anda yakin ingin keluar ?</h5>
                  <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span>
                  </button>
              </div>
              <div class="modal-body">
                  <p>Tekan "Exit" jika ingin keluar, Tekan "Cancel" untuk membatalkan</p>
              </div>
              <div class="modal-footer">
                  <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                  <a class="btn btn-primary" href="<?= base_url('admin/auth/logout'); ?>">Exit</a>
              </div>
          </div>
      </div>
  </div>
  <div class="container2">
      <div class="loading2"><span>Loading..</span></div>
  </div>

  <!-- Modal input aspirasi -->
  <div class="modal fade" id="laporModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" id="modal-add-aspirasi" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">New message</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">

                  <form class="mt-3 komentar" method="post" action="<?= base_url() ?>">
                      <div class="form-group mt-2">
                          <select class="custom-select" name="kec_id">
                              <option selected value=''>Open this select menu</option>
                              <?php
                                $all_kec = $this->db->get("web_kecamatan")->result_array();
                                foreach ($all_kec as $row) : ?>
                                  <option value="<? $row['id_kec'] ?>"><?= $row['kecamatan'] ?></option>
                              <?php endforeach ?>
                          </select>
                      </div>
                      <div class="form-group mt-4 komentar position-relative">
                          <textarea name='message' required class="form-control komentar_1" id="add_komentar_1" rows="3"></textarea>
                          <label for="add_komentar_1" class="label-komentar">
                              <span class="label-text">Ketikkan Komentar Anda Disini</span></label>
                          <input type="submit" value="Kirim" class="btn btn-primary submit-komentar">
                      </div>

                  </form>

              </div>

          </div>
      </div>
  </div>




  <!-- Bootstrap core JavaScript -->
  <script src="<?= $thema_folder; ?>assets/vendor/jquery/jquery.min.js"></script>
  <script src="<?= $thema_folder; ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Plugin JavaScript -->
  <script src="<?= $thema_folder; ?>assets/vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Contact form JavaScript -->
  <script src="<?= $thema_folder; ?>assets/js/jqBootstrapValidation.js"></script>
  <script src="<?= $thema_folder; ?>assets/js/contact_me.js"></script>

  <!-- Custom scripts for this template -->
  <script src="<?= $thema_folder; ?>assets/js/agency.min.js"></script>
  <script src="<?= $thema_folder; ?>assets/js/main.js"></script>

  <script src="<?= $thema_folder; ?>layout/template/footer.js"></script>