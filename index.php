<!doctype html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/x-icon" href="./assets/img/nevoa_logo.png">
  <?php include './includes/bootstrap_styles.php' ?>
  <link rel="stylesheet" href="./assets/css/bootstrap_custom.css">
  <title>NévoaCore</title>
</head>

<body>

  <nav class="navbar navbar-expand-lg border-bottom">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">
        <img src="./assets/img/nevoa_logo.png" alt="Logo" width="30" height="24" class="d-inline-block align-text-top">
        NévoaCore
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarContent">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0 d-flex gap-2">
          <li class="nav-item">
            <a class="btn btn-outline-primary" href="./pages/register.php">Registrar</a>
          </li>
          <li class="nav-item">
            <a class="btn btn-outline-primary" href="./pages/login.php">Entrar</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <?php include './includes/footer.php' ?>
  <?php include './includes/bootstrap_script.php' ?>
</body>

</html>