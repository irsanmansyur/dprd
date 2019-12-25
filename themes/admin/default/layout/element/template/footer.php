            <!-- end content -->
            </div>

            <!-- /footer -->
            <footer class="footer">
                <div class="container-fluid">
                    <nav class="float-left">
                        <ul>
                            <li>
                                <a href="<?= @$user['site_url'] ?>">
                                    <?= @$user['site_name'] ?>
                                </a>
                            </li>
                            <?php
                            if ("a" == "ddd") : ?>

                            <?php endif; ?>
                        </ul>
                    </nav>
                    <div class="copyright float-right">
                        <div class="">Page rendered in <strong>{elapsed_time}</strong> seconds.
                            &copy; <script>
                                document.write(new Date().getFullYear())
                            </script>,
                            <i class="material-icons">favorite</i>
                            by <a href="<?= @$this->setting->site_url ? @$this->setting->site_url : base_url(); ?>user" target="_blank"><?= $this->setting->site_name ?></a></div>
                    </div>
                </div>
            </footer>
            <script>
                $(document).ready(function() {
                    $(".preloader").fadeOut();
                })
            </script>

            <!-- end main-panel -->
            </div>
            <!-- end wrapper -->
            </div>