<?php session_start(); ?>

<!doctype html>
<html lang="pt-BR">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/x-icon" href="../assets/img/nevoa_logo.png">
	<?php include '../includes/bootstrap_styles.php' ?>
	<link rel="stylesheet" href="../assets/css/bootstrap_custom.css">
	<title>register - NévoaCore</title>
</head>

<body>
	<?php include '../includes/toast.php' ?>
	<?php session_unset();?>

	<nav class="navbar navbar-dark bg-primary fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="../assets/img/nevoa_logo.png" alt="Logo" width="35" height="35" class="d-inline-block">
        <span class="ms-2 align-middle">NévoaCore</span>
      </a>
      <a class="btn btn-outline-light" href="../index.php">Voltar</a>
    </div>
  </nav>

	<div class="container min-vh-100 d-flex align-items-center justify-content-center">
		<div class="row w-100">
			<div class="col-md-8 col-lg-6 col-xl-4 mx-auto">
				<div class="card shadow-lg p-4">
					<h3 class="text-center mb-4 squada-one-regular">Cadastro</h3>
					<form method="POST" action="../actions/register.php">

						<div class="input-group mb-3">
							<input type="text" name="username_register" class="form-control" placeholder="Digite seu nome de usuário" maxlength="255" required>
						</div>

						<div class="input-group mb-3">
							<input type="email" name="email" class="form-control" placeholder="Digite seu E-mail" maxlength="255" required>
						</div>

						<div class="input-group mb-3">
							<input id="password_register" type="password" name="password_register" class="form-control" placeholder="Digite sua senha" required>
							<button class="btn btn-outline-secondary" type="button"
								onclick="toggle_password_visibility('password_register', this)">
								<i class="bi bi-eye-slash"></i>
							</button>
						</div>

						<div class="input-group">
							<input id="confirm_password_register" type="password" name="confirm_password_register" class="form-control" placeholder="Confirme sua senha" required>
							<button class="btn btn-outline-secondary" type="button"
								onclick="toggle_password_visibility('confirm_password_register', this)">
								<i class="bi bi-eye-slash"></i>
							</button>
						</div>
						<div id="password_error_message" class="form-text text-danger"></div>

						<button type="submit" name="submit_register" class="btn btn-primary w-100 mt-3" disabled>Cadastrar</button>

						<div class="text-center mt-3">
							<span>Já tem uma conta?</span>
							<a href="./login.php" class="text-decoration-none cursor-pointer">Voltar para Login</a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<?php include '../includes/footer.php' ?>
	<?php include '../includes/bootstrap_script.php' ?>
	<script src="../assets/js/register.js"></script>
	<script src="../assets/js/toast.js"></script>
	<script src="../assets/js/toggle_password_visibility.js"></script>
</body>

</html>