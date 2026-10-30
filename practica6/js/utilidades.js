
// Acceso rápido a los elementos por su ID
function $(id) {
    return document.getElementById(id);
}

// Comprueba si un texto está vacío
function estaVacio(texto) {
    return texto.trim() === "";
}

// Crea el mensaje de error si todavía no existe
function obtenerAviso(campo) {
    let aviso = $(`error_${campo.id}`);
    if (!aviso) {
        aviso = document.createElement("span");
        aviso.className = "mensaje-error";
        aviso.id = `error_${campo.id}`;
        aviso.setAttribute("role", "alert");
        campo.parentElement.appendChild(aviso);
    }
    return aviso;
}

// Muestra el error y marca el campo
function mostrarError(campo, mensaje) {
    const aviso = obtenerAviso(campo);
    aviso.textContent = `Error: ${mensaje}`;

    campo.classList.add("campo-error");
    campo.setAttribute("aria-invalid", "true");

    const ayuda = campo.dataset.ayuda;
    campo.setAttribute("aria-describedby", ayuda ? `${ayuda} ${aviso.id}` : aviso.id);
}

// Elimina el error del campo
function limpiarError(campo) {
    obtenerAviso(campo).textContent = "";
    campo.classList.remove("campo-error");
    campo.removeAttribute("aria-invalid");

    const ayuda = campo.dataset.ayuda;
    if (ayuda) {
        campo.setAttribute("aria-describedby", ayuda);
    } else {
        campo.removeAttribute("aria-describedby");
    }
}

// Valida un campo individual
function validarCampo(campo, validar) {
    const mensaje = validar(campo.value);
    if (mensaje) {
        mostrarError(campo, mensaje);
        return false;
    }
    limpiarError(campo);
    return true;
}

// Valida todos los campos y enfoca el primero incorrecto
function validarCampos(lista) {
    let primerError = null;

    for (const { id, validar } of lista) {
        const campo = $(id);
        if (!validarCampo(campo, validar) && primerError === null) {
            primerError = campo;
        }
    }

    if (primerError !== null) {
        primerError.focus();
    }

    return primerError === null;
}

// Activa la validación automática del formulario
function activarValidacion(idFormulario, lista) {
    const formulario = $(idFormulario);
    if (!formulario) {
        return;
    }

    // Evita que el botón pierda el foco del campo antes del envío
    for (const boton of formulario.querySelectorAll('button[type="submit"]')) {
        boton.addEventListener("mousedown", (evento) => evento.preventDefault());
    }

    // Comprueba los campos antes de enviar
    formulario.addEventListener("submit", (evento) => {
        if (!validarCampos(lista)) {
            evento.preventDefault();
        }
    });

    // Limpia los errores al borrar el formulario
    formulario.addEventListener("reset", () => {
        for (const { id } of lista) {
            limpiarError($(id));
        }
    });

    // Valida cada campo cuando termina de editarse
    for (const { id, validar } of lista) {
        $(id).addEventListener("change", () => validarCampo($(id), validar));
    }
}