let videos = [];
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
      playerlets: playerDefaults,
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
  // Obtener referencias a los campos de contraseña
  let passwordField = document.getElementById("clave_principal");
  let confirmPasswordField = document.getElementById("clave_principal-r");
  let submitButton = document.getElementById("submitButton");
  

  // Función para verificar si las contraseñas coinciden
  function checkPasswordsMatch() {
    let password = passwordField.value;
    let confirmPassword = confirmPasswordField.value;

    let message = document.getElementById("message"); // Elemento para mostrar el mensaje de error

    /*   if (password === confirmPassword) {
      message.classList.add("clave-success");
      message.classList.remove("clave-error");

      message.textContent = "Las contraseñas coinciden.";
    } else {
      message.classList.remove("clave-success");
      message.classList.add("clave-error");
      message.textContent = "Las contraseñas no coinciden.";
    } */
    let conditionsList = document.getElementById("conditions"); // Elemento para mostrar la lista de condiciones cumplidas
    var submitButton = document.getElementById("submitButton");
    var botonesAcciones = document.getElementById("botones-acciones"); // Elemento para ocultar o mostrar


    let conditions = []; // Lista para almacenar las condiciones cumplidas
    let conditionsNotMet = []; // Lista para almacenar las condiciones no cumplidas

    if (password === confirmPassword) {
      conditions.push("Las contraseñas coinciden.");
    } else {
      conditionsNotMet.push("Las contraseñas no coinciden.");
    }

    // Ejemplo de condiciones adicionales
    if (password.length >= 8) {
      conditions.push("La contraseña tiene al menos 8 caracteres.");
    } else {
      conditionsNotMet.push("La contraseña debe tener al menos 8 caracteres.");
    }

    if (password.match(/[a-z]/)) {
      conditions.push("La contraseña contiene al menos una letra minúscula.");
    } else {
      conditionsNotMet.push(
        "La contraseña debe contener al menos una letra minúscula."
      );
    }

    if (password.match(/[A-Z]/)) {
      conditions.push("La contraseña contiene al menos una letra mayúscula.");
    } else {
      conditionsNotMet.push(
        "La contraseña debe contener al menos una letra mayúscula."
      );
    }

    if (password.match(/[0-9]/)) {
      conditions.push("La contraseña contiene al menos un número.");
    } else {
      conditionsNotMet.push("La contraseña debe contener al menos un número.");
    }

    if (password.match(/[^a-zA-Z0-9]/)) {
      conditions.push("La contraseña contiene al menos un carácter especial.");
    } else {
      conditionsNotMet.push(
        "La contraseña debe contener al menos un carácter especial."
      );
    }

    // Actualizar el mensaje y la lista de condiciones cumplidas
    let messageText = "";
    if (conditions.length > 0) {
      messageText =
        "Las contraseñas coinciden y cumplen las siguientes condiciones:";
      conditionsList.innerHTML = "";
      conditions.forEach(function (condition) {
        let li = document.createElement("li");
        li.textContent = condition;
        li.classList.add("condition-met");
        conditionsList.appendChild(li);

      });
    } else {
      messageText = "Las contraseñas no coinciden.";
      conditionsList.innerHTML = "";

    }

    // Agregar estilos a las condiciones no cumplidas
    conditionsNotMet.forEach(function (condition) {
      let li = document.createElement("li");
      li.textContent = condition;
      li.classList.add("condition-not-met");
      conditionsList.appendChild(li);
    });

    // Actualizar el mensaje
    // Actualizar el mensaje
    message.textContent = messageText;

    // Desactivar el botón de envío si hay condiciones no cumplidas
    if (conditionsNotMet.length > 0) {
      submitButton.disabled = true;
      botonesAcciones.style.display = "none"; // Ocultar el elemento

    } else {
      submitButton.disabled = false;
      // Ocultar el texto y la lista después de un segundo
     
        message.textContent = "";
        conditionsList.innerHTML = "";
      botonesAcciones.style.display = "block"; // Mostrar el elemento

      
    }
  }

  // Agregar evento input a los campos de contraseña
  passwordField.addEventListener("input", checkPasswordsMatch);
  confirmPasswordField.addEventListener("input", checkPasswordsMatch);
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
selectorUsuario();
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
  let metodoPago = document.getElementById("metodo_pago").value;
  if (metodoPago === "2") {
    $(".no-numero").attr("style", "display:block!important");
  } else {
    $(".no-numero").attr("style", "display:none!important");
  }
}
mostrarMetodoPago();
