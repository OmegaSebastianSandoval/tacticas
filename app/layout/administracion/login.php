<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <title><?= $this->_titlepage ?></title>
  <?php $infopageModel = new Page_Model_DbTable_Informacion();
    $infopage = $infopageModel->getById(1);
    ?>

  <!-- Bootsrap CSS -->
  <link rel="stylesheet" href="/components/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/components/Font-Awesome/css/all.css">
  <!-- CSS Global -->
  <link rel="stylesheet" href="/skins/administracion/css/global.css">

  <link rel="shortcut icon" href="/images/<?= $infopage->info_pagina_favicon; ?>">

</head>

<body class="login-fondo">
  <div class="login-caja">
    <h1><?= $this->_titlepage ?></h1>
    <div class="login-logo"><img src="/skins/administracion/images/logo.png"></div>
    <div class="login-content"><?= $this->_content ?></div>
  </div>

  <div class="login-derechos">&copy;2018 Todos los derechos reservados | Diseñado por WHY CREATIVE SOLUTIONS
  </div>

  <!-- Jquery -->
  <script src="/components/jquery/jquery-3.6.0.min.js"></script>
  <!-- Popper -->
  <script src="https://unpkg.com/@popperjs/core@2"></script>
  <!-- Bootstrap Js -->
  <script src="/components/bootstrap/js/bootstrap.min.js"></script>
  <script src="/components/bootstrap-validator/dist/validator.min.js"></script>
  <!-- Main Js -->
  <script src="/skins/administracion/js/main.js"></script>
  <!-- MaskMoney -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"
    integrity="sha512-Rdk63VC+1UYzGSgd3u2iadi0joUrcwX0IWp2rTh6KXFoAmgOjRS99Vynz1lJPT8dLjvo6JZOqpAHJyfCEZ5KoA=="
    crossorigin="anonymous"></script>
</body>

</html>