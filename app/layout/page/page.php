<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>
    <?= $this->_titlepage ?>
  </title>
  <?php $infopageModel = new Page_Model_DbTable_Informacion();
  $infopage = $infopageModel->getById(1);
  ?>
  <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBWYVxdF4VwIPfmB65X2kMt342GbUXApwQ&sensor=true"> -->
  </script>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="/components/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="/components/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/components/bootstrap-datepicker/css/bootstrap-datepicker3.standalone.min.css">
  <!-- Fileinput -->
  <link rel="stylesheet" href="/components/bootstrap-fileinput/css/fileinput.css">
  <!-- FontAwesome -->
  <link rel="stylesheet" href="/components/Font-Awesome/css/all.css">
  <!-- Colorpicker -->
  <link rel="stylesheet" href="/components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">
  <!-- Skins Carousel -->
  <link rel="stylesheet" type="text/css" href="/scripts/carousel/carousel.css">


  <!-- Slick CSS -->
<!--   <link rel="stylesheet" href="/components/slick/slick.css">
  <link rel="stylesheet" href="/components/slick/slick-theme.css"> -->
  <!-- Global CSS -->
  <!-- <link rel="stylesheet" href="/skins/administracion/css/global.css?v=1.00"> -->
  <link rel="stylesheet" href="/skins/page/css/global.css?v=1.00">
  <link rel="stylesheet" href="/skins/page/css/responsive.css?v=2">

  <!-- FontAwesome -->
  <link rel="stylesheet" href="/components/Font-Awesome/css/all.css">

  <link rel="shortcut icon" href="/images/<?= $infopage->info_pagina_favicon; ?>">


  <script type="text/javascript" id="www-widgetapi-script" src="https://s.ytimg.com/yts/jsbin/www-widgetapi-vflS50iB-/www-widgetapi.js" async=""></script>

  <!-- Jquery -->
  <script src="/components/jquery/jquery-3.6.0.min.js"></script>
  <!-- Popper -->
  <script src="https://unpkg.com/@popperjs/core@2"></script>

  <!-- Carousel -->
  <script type="text/javascript" src="/scripts/carousel/carousel.js"></script>
  <!-- Slick -->
  <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
  <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
  <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />
  <!-- <script src="/components/fullpage/slick/slick.min.js"></script> -->

  <script src="/components/jquery-knob/js/jquery.knob.js"></script>
  <!-- <script src="//cdn.public.flmngr.com/FLMNFLMN/widgets.js"></script> -->

  <!-- SweetAlert -->
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Fancybox -->
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css"
/>


  <!-- Recaptcha -->
  <script src='https://www.google.com/recaptcha/api.js'></script>
  <meta name="description" content="<?= $this->_data['meta_description']; ?>" />
  <meta name=" keywords" content="<?= $this->_data['meta_keywords']; ?>" />
  <?php echo $this->_data['scripts']; ?>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBWYVxdF4VwIPfmB65X2kMt342GbUXApwQ&sensor=true"></script>
  <script type="text/javascript">
    var map;
    var longitude = 0;
    var latitude = 0;
    var icon = '/skins/administracion/images/ubicacion.png';
    var point = false;
    var zoom = 10;

    function setValuesMap(longitud, latitud, punto, zoomm, icono) {
      longitude = longitud;
      latitude = latitud;
      if (punto) {
        point = punto;
      }
      if (zoomm) {
        zoom = zoomm;
      }
      if (icono) {
        icon = icono
      }
    }

    function initializeMap() {
      var mapOptions = {
        zoom: parseInt(zoom),
        center: new google.maps.LatLng(longitude, longitude),
      };
      // Place a draggable marker on the map
      map = new google.maps.Map(document.getElementById('map'), mapOptions);
      if (point == true) {
        var marker = new google.maps.Marker({
          position: new google.maps.LatLng(longitude, latitude),
          map: map,
          icon: icon
        });
      }
      map.setCenter(new google.maps.LatLng(longitude, latitude));
    }
  </script>
</head>

<body id="body">

  <?= $this->_data['header']; ?>

  <div id="contenedor" class="contenedor-general">
    <?= $this->_content ?>
  </div>
  <footer>
    <?= $this->_data['footer']; ?>
  </footer>
  <?= $this->_data['adicionales']; ?>

  <!-- Jquery -->
  <script src="/components/jquery/jquery-3.6.0.min.js"></script>
  <!-- Popper -->
  <script src="https://unpkg.com/@popperjs/core@2"></script>
  <!-- Bootstrap Js -->
  <script src="/components/bootstrap/js/bootstrap.min.js"></script>
  <script src="/components/bootstrap-datepicker/js/bootstrap-datepicker.min.js">
  </script>
  <script src="/components/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js">
  </script>
  <script src="/components/bootstrap-validator/dist/validator.min.js">
  </script>
  <!-- File Input -->
  <script src="/components/bootstrap-fileinput/js/fileinput.min.js"></script>
  <script src="/components/bootstrap-fileinput/js/locales/es.js"></script>
  <!-- Tiny -->
  <script async src="/components/tinymce/tinymce.min.js"></script>
  <script src="/components/bootstrap-switch/dist/js/bootstrap-switch.min.js"></script>
  <script src="/components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>

  <script src="https://unpkg.com/flmngr"></script>
  <!-- main Js -->
  <script src="/skins/administracion/js/main.js"></script>
  <script src="/skins/page/js/tableHeadFixer.js"></script>

    <!-- Main Js -->
    <script src="/skins/page/js/main.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js" integrity="sha512-Rdk63VC+1UYzGSgd3u2iadi0joUrcwX0IWp2rTh6KXFoAmgOjRS99Vynz1lJPT8dLjvo6JZOqpAHJyfCEZ5KoA==" crossorigin="anonymous"></script>
</body>

</html>