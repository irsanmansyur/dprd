    <footer class="footer">
        <div class="container">
            <nav class="float-left">
                <ul>
                    <li>
                        <a href="<?= base_url() ?>">
                            HOME
                        </a>
                    </li>

                </ul>
            </nav>
            <div class="copyright float-right">
                &copy;
                <script>
                    document.write(new Date().getFullYear())
                </script> <?= $this->setting->site_name ?>
            </div>
        </div>
    </footer>

    <!-- < p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php //echo (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' 
                                                                                        ?></> -->
    <script src="<?= $thema_folder; ?>assets/js/material-dashboard.min.js?v=2.1.0" type="text/javascript"></script>