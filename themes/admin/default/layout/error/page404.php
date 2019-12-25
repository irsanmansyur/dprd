<!DOCTYPE html>
<html lang="en">
<?php $this->load->view($this->theme_element . 'template/head_meta'); ?>

<body class="off-canvas-sidebar">
	<!-- Navbar -->
	<?php $this->load->view($this->theme_element . 'template/auth/navbar'); ?>


	<!-- isi content -->
	<div id="wrapper">


		<!-- Content Wrapper -->
		<div id="content-wrapper" class="d-flex flex-column">

			<!-- Main Content -->
			<div id="content">

				<!-- Begin Page Content -->
				<div class="container-fluid mt-5">

					<!-- 404 Error Text -->
					<div class="text-center">
						<div class="error mx-auto" data-text="403">404</div>
						<p class="lead text-gray-800 mb-5">Url not found</p>
						<p class="text-gray-500 mb-0">Tidak Ada Halaman Tersedia</p>
						<a href="<?= base_url(''); ?>">&larr; Back to Home</a>
					</div>

				</div>
				<!-- /.container-fluid -->

			</div>
			<!-- End of Main Content -->

		</div>
		<!-- End of Content Wrapper -->

	</div>

	<!-- end isi -->

	<!-- footer -->
	<?php $this->load->view($this->theme_element . 'template/auth/footer'); ?>

</body>

</html>