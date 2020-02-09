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
        <div class=" col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-header card-header-warning card-header-icon">
                    <div class="card-icon">
                        <i class="material-icons">
                            message
                        </i>
                    </div>
                    <p class="card-category">Aspirasi Ditanggapi</p>
                    <h3 class="card-title"><?= $aspirasi_read ?></h3>
                </div>
                <div class="card-footer">
                    <div class="stats">
                        <i class="material-icons text-danger">warning</i>
                        <p>Jumlah Aspirasi yang sudah ditanggapi...</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-header card-header-danger card-header-icon">
                    <div class="card-icon">
                        <i class="material-icons">
                            swap_horizontal_circle
                        </i>
                    </div>
                    <p class="card-category">Aspirasi Belum Ditanggapi</p>
                    <h3 class="card-title"><?= $aspirasi_not_read ?></h3>
                </div>
                <div class="card-footer">
                    <div class="stats">
                        <i class="material-icons text-danger">warning</i>
                        <p>Jumlah Aspirasi yang Belum ditanggapi...</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <canvas id="myVisitor"></canvas>
        </div>
    </div>

    <!-- end isi -->

    <?php
    $this->load->view($thema_load . 'element/template/footer.php');
    ?>
    <?php
    $this->load->view($thema_load . 'element/template/fixed-setting.php');
    ?>

    <script src="<?= base_url('vendor/chartjs/Chart.min.js') ?>" type="text/javascript"></script>

    <script>
        var ctx = document.getElementById("myVisitor").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($days); ?>,
                datasets: [{
                    label: '# Jumlah Visit',
                    data: <?php echo json_encode($count); ?>,

                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    </script>
</body>

</html>