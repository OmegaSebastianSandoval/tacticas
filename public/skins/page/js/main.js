var videos = [];
$(document).ready(function () {
  $(".dropdown-toggle").dropdown();
  $(".carouselsection").carousel({
    quantity: 4,
    sizes: {
      900: 3,
      500: 1,
    },
  });

  $(".banner-video-youtube").each(function () {
    // console.log($(this).attr('data-video'));
    const datavideo = $(this).attr("data-video");
    const idvideo = $(this).attr("id");
    const playerDefaults = {
      autoplay: 0,
      autohide: 1,
      modestbranding: 0,
      rel: 0,
      showinfo: 0,
      controls: 0,
      disablekb: 1,
      enablejsapi: 0,
      iv_load_policy: 3,
    };
    const video = {
      videoId: datavideo,
      suggestedQuality: "hd1080",
    };
    videos[videos.length] = new YT.Player(idvideo, {
      videoId: datavideo,
      playerVars: playerDefaults,
      events: {
        onReady: onAutoPlay,
        onStateChange: onFinish,
      },
    });
  });

  function onAutoPlay(event) {
    event.target.playVideo();
    event.target.mute();
  }

  function onFinish(event) {
    if (event.data === 0) {
      event.target.playVideo();
    }
  }
  const tooltipTriggerList = document.querySelectorAll(
    '[data-bs-toggle="tooltip"]'
  );
  const tooltipList = [...tooltipTriggerList].map(
    (tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl)
  );
});
//texto validacion de contraseñas en recuperacion
function validarPassword() {
  let pass1 = document.querySelector("#password");
  let pass2 = document.querySelector("#repassword");
  let texto = document.querySelector("#text-alert");
  const btnRecuperar = document.querySelector("#btn-recuperar");

  /*   console.log(pass1.value);
  console.log(pass2.value); */

  if (pass1.value != pass2.value) {
    texto.style.display = "block";
    btnRecuperar.disabled = true;
  } else {
    texto.style.display = "none";

    btnRecuperar.disabled = false;
  }
}

//Seleccionar nivel
// selectorUsuario();
function selectorUsuario() {
  let contenedorEmpresa = document.querySelector(".contenedor-empresa");
  let contenedorOcupacion = document.querySelector(".contenedor-ocupacion");
  const nivel = document.getElementById("nivel");
  // console.log(nivel.value);
  nivel.value === "2"
    ? (contenedorEmpresa.style.display = "block")
    : (contenedorEmpresa.style.display = "none");
  nivel.value === "3"
    ? (contenedorOcupacion.style.display = "block")
    : (contenedorOcupacion.style.display = "none");
}

//ocultar o mostrar metodo de pago

function mostrarMetodoPago() {
  let metodoPago = document.getElementById('metodo_pago').value;
  if (metodoPago === '2') {
    $(".no-numero").attr("style", "display:block!important")

  } else {

    $(".no-numero").attr("style", "display:none!important")

  }
}
mostrarMetodoPago();
