<div class="container">
<?php if ($this->session->flashdata('flash')) : ?>
		<div class="row mt-3 mb-3">
				<div class="col-md-6">
					<div class="alert alert-success alert-dismissible fade show" role="alert">
						Data<strong> User !</strong> Success <?= $this->session->flashdata('flash')?>.
						<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
					</div>
				</div>
			</div>
			<?php unset($_SESSION['flash']);?> 
<?php endif; ?>
</div>
<div class="container d-flex flex-column justify-content-center align-content-center align-items-center">

<h1>BERHASIL MELAKUKAN LOGIC</h1>
