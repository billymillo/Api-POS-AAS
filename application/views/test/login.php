<div class="container d-flex flex-column justify-content-center align-content-center align-items-center w-100 h-100">
<h1>Ini Form Login Akun</h1>
	<div class="card p-3 mt-4 w-50 h-50 d-flex flex-column justify-content-center align-content-center">
	<?php if ($this->session->flashdata('flash')) : ?>
		<div class="row mt-3 mb-3 w-100">
				<div class="col-md-6">
					<div class="alert alert-danger alert-dismissible fade show" role="alert">
						<?= $this->session->flashdata('flash')?>.
						<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
					</div>
				</div>
			</div>
			<?php unset($_SESSION['flash']);?> 
	<?php endif; ?>
	<form action="" method="POST" class="">
		<div class="mb-3">
			<label for="name" class="form-label">Name</label>
			<input type="name" class="form-control" name="name" value="<?= set_value('name')?>"/>
			<div id="emailHelp" class="text-danger"><?= form_error('name')?></div>
		</div>
		<div class="mb-3">
			<label for="password" class="form-label">Password</label>
			<input type="password" class="form-control" name="password"/>
			<div id="emailHelp" class="text-danger"><?= form_error('password')?></div>
		</div>
		<button class="btn btn-primary" type="submit">Submit</button>
	</form>
	</div>
	
</div>
