<div class="container d-flex flex-column justify-content-center align-content-center align-items-center">
<h1>Ini Form Regist Akun</h1>
	<div class="card p-3 mt-4">
	<form action="" method="POST">
		<div class="mb-3">
			<label for="name" class="form-label">Name</label>
			<input type="name" class="form-control" name="name" value="<?= set_value('name')?>"/>
			<div id="emailHelp" class="text-danger"><?= form_error('name')?></div>

		</div>
		<div class="mb-3">
			<label for="email" class="form-label">Email</label>
			<input type="email" class="form-control" name="email" value="<?= set_value('email')?>"/>
			<div id="emailHelp" class="text-danger"><?= form_error('email')?></div>

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
