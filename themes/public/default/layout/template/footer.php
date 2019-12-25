  <!-- Footer -->
  <footer class="footer">
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